import React, { useState, useEffect, useMemo, useCallback } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import toast from 'react-hot-toast';
import RichTextEditor from '@/Components/RichTextEditor';
import QuizSidebar from './QuizSidebar';
import { 
    ClockIcon, ChevronLeftIcon, ChevronRightIcon, FlagIcon, 
    CheckCircleIcon, TrophyIcon, SparklesIcon, XCircleIcon, ArrowRightIcon,
    LockClosedIcon, ShieldCheckIcon, ExclamationTriangleIcon 
} from '@heroicons/react/24/outline';

const isDev = process.env.NODE_ENV === 'development';

export default function QuizTake({ 
    course, assessment, enrollment, attempt, 
    questions: courseQuestions = [],
    timeRemaining: initialTimeRemaining,
}) {
    // State
    const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
    const [answers, setAnswers] = useState(() => attempt?.answers || {});
    const [essayAnswers, setEssayAnswers] = useState({});
    
    // Initialize timer from localStorage or initial value
    const [timeRemaining, setTimeRemaining] = useState(() => {
        const savedTime = localStorage.getItem(`quiz_timer_${attempt?.id}`);
        if (savedTime && !isNaN(parseInt(savedTime))) {
            return parseInt(savedTime);
        }
        return initialTimeRemaining;
    });
    
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [flaggedQuestions, setFlaggedQuestions] = useState(new Set());
    const [hasUnsavedChanges, setHasUnsavedChanges] = useState(false);
    
    // Part A/B Logic State
    const [partASubmitted, setPartASubmitted] = useState(false);
    const [partAScore, setPartAScore] = useState(null);
    
    // Modals
    const [showCompletionModal, setShowCompletionModal] = useState(false);
    const [showLockoutModal, setShowLockoutModal] = useState(false);
    const [lockInfo, setLockInfo] = useState(null);
    
    const [finalScore, setFinalScore] = useState(null);
    const [finalManualReview, setFinalManualReview] = useState(false);

    // NEW: State for AI Detection Results
    const [aiScores, setAiScores] = useState({}); 

    // Derived Data
    const allQuestions = courseQuestions || [];
    const getQuestionType = (question) => String(
        question.type ?? question.question_type ?? ''
    ).toLowerCase();
    const essayQuestions = allQuestions.filter(question => getQuestionType(question) === 'essay');
    const mcqQuestions = allQuestions.filter(question => getQuestionType(question) !== 'essay');
    const currentQuestion = mcqQuestions[currentQuestionIndex];
    
    // Validation Logic
    const canAccessPartB = partASubmitted && partAScore !== null && partAScore >= 50;
    const isPartALocked = essayQuestions.length > 0 && partASubmitted;
    const progress = mcqQuestions.length > 0 
        ? Math.round(((currentQuestionIndex + 1) / mcqQuestions.length) * 100) 
        : 100;

    const formatTime = (seconds) => {
        const totalSeconds = Number.isFinite(Number(seconds))
            ? Math.max(0, Math.floor(Number(seconds)))
            : 0;
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const secs = totalSeconds % 60;
        if (hours > 0) {
            return `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
        return `${minutes}:${secs.toString().padStart(2, '0')}`;
    };

    // Auto-save effect
    useEffect(() => {
        if (!hasUnsavedChanges || !attempt?.id) return;
        const saveTimer = setTimeout(() => autoSaveProgress(), 30000);
        return () => clearTimeout(saveTimer);
    }, [answers, essayAnswers, hasUnsavedChanges, attempt?.id]);

    const autoSaveProgress = useCallback(async () => {
        if (!attempt?.id || !hasUnsavedChanges) return;
        try {
            await fetch(route('dashboard.quiz.save-progress', attempt.id), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({ answers, essay_answers: essayAnswers }),
            });
            setHasUnsavedChanges(false);
        } catch (error) {
            console.error('Auto-save failed', error);
        }
    }, [answers, essayAnswers, attempt?.id, hasUnsavedChanges]);

    // Timer countdown effect
    useEffect(() => {
        if (partASubmitted || timeRemaining <= 0) return;
        const timer = setInterval(() => {
            setTimeRemaining(prev => {
                const newTime = prev - 1;
                if (attempt?.id) {
                    localStorage.setItem(`quiz_timer_${attempt.id}`, newTime.toString());
                }
                if (newTime <= 0) {
                    clearInterval(timer);
                    toast.error('Time is up! Submitting your answers...');
                    submitPartA();
                    return 0;
                }
                return newTime;
            });
        }, 1000);
        return () => clearInterval(timer);
    }, [partASubmitted, timeRemaining, attempt?.id]);

    useEffect(() => {
        return () => {
            if (attempt?.id && (partASubmitted || showCompletionModal)) {
                localStorage.removeItem(`quiz_timer_${attempt.id}`);
            }
        };
    }, [partASubmitted, showCompletionModal, attempt?.id]);

    const handleAnswer = (questionId, answer) => {
        if (isPartALocked) return;
        setAnswers(prev => ({ ...prev, [questionId]: answer }));
        setHasUnsavedChanges(true);
    };

    const handleEssayChange = (questionId, html) => {
        setEssayAnswers(prev => ({ ...prev, [questionId]: html }));
        setHasUnsavedChanges(true);
        // Reset AI score when text changes to force re-check
        setAiScores(prev => ({ ...prev, [questionId]: null }));
    };

    const toggleFlag = (questionId) => {
        setFlaggedQuestions(prev => {
            const newSet = new Set(prev);
            newSet.has(questionId) ? newSet.delete(questionId) : newSet.add(questionId);
            return newSet;
        });
    };

    const readJsonResponse = async (response) => {
        const body = await response.text();

        try {
            return JSON.parse(body);
        } catch {
            throw new Error(
                `The quiz could not be submitted because the server returned an unexpected response (status ${response.status}). Please refresh and try again.`
            );
        }
    };

    // Handler for AI Check
    const handleCheckAiScore = async (questionId, text) => {
        if (!text || text.length < 50) {
            toast.error("Please write at least 50 characters before checking AI score.");
            return;
        }

        setAiScores(prev => ({ ...prev, [questionId]: { loading: true, percentage: null, error: null } }));

        try {
            // Get CSRF Token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            if (!csrfToken) {
                throw new Error("CSRF token not found. Please refresh the page.");
            }
            const res = await fetch(route('dashboard.quiz.check-ai-score'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json', // Important: Tells Laravel you want JSON back
                },
                body: JSON.stringify({ text }),
            });
            // Check if response is OK before parsing JSON
            if (!res.ok) {
                // If it's not OK, it might be HTML (404/500)
                const contentType = res.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") === -1) {
                    throw new Error("Server returned an unexpected response (likely a 404 or 500 error). Check console.");
                }
                const errorData = await res.json();
                throw new Error(errorData.message || 'Failed to check AI score');
            }
            const data = await readJsonResponse(res);
            
            if (data.success) {
                setAiScores(prev => ({ ...prev, [questionId]: { loading: false, percentage: data.ai_percentage, error: null } }));
                
                if (data.ai_percentage > 20) {
                    toast.warning(`AI Detection: ${data.ai_percentage}% detected. This exceeds the 20% limit.`);
                } else {
                    toast.success(`AI Detection: ${data.ai_percentage}% detected. Within limits.`);
                }
            } else {
                throw new Error(data.message || 'Failed to check AI score');
            }
        } catch (err) {
            console.error(err); // Log full error to console
            setAiScores(prev => ({ ...prev, [questionId]: { loading: false, percentage: null, error: err.message } }));
            toast.error(err.message);
        }
    };

    const submitPartA = async () => {
        const unanswered = mcqQuestions.filter(q => !answers[q.id]).length;
        if (unanswered > 0 && !confirm(`You have ${unanswered} unanswered questions. Submit anyway?`)) return;

        setIsSubmitting(true);
        try {
            const res = await fetch(route('dashboard.quiz.submit', { course: course.slug, assessment: assessment.id }), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({ answers, part_a_only: true }),
            });
            const data = await readJsonResponse(res);
            if (!res.ok) throw new Error(data.message);

            setPartASubmitted(true);
            setPartAScore(data.score);
            setHasUnsavedChanges(false);
            if (attempt?.id) localStorage.removeItem(`quiz_timer_${attempt.id}`);
            
            if (data.score < 50) {
                setLockInfo({
                    failedAttempts: data.failed_attempts || 1,
                    lockedUntil: data.locked_until || null,
                    permanentlyLocked: Boolean(data.permanently_locked),
                });
                setShowLockoutModal(true);
            } else {
                toast.success(`Part A Score: ${data.score}%. Part B is now unlocked!`);
            }
        } catch (err) {
            toast.error(err.message);
        } finally {
            setIsSubmitting(false);
        }
    };

    // UPDATED: Strict AI Validation in Submit Part B
    const submitPartB = async () => {
        if (!canAccessPartB) {
            toast.error("You must score at least 50% in Part A to submit Part B.");
            return;
        }

        // 1. Check for Empty Essays
        const emptyEssays = essayQuestions.filter(q => !essayAnswers[q.id] || essayAnswers[q.id].length < 10).length;
        if (emptyEssays > 0 && !confirm(`${emptyEssays} essays are empty. Submit anyway?`)) return;

        // 2. ENFORCE AI POLICY (< 20%)
        // Check if we have AI scores for all essays
        const missingAiChecks = essayQuestions.filter(q => !aiScores[q.id]);
        if (missingAiChecks.length > 0) {
            toast.error("⚠️ Submission Blocked: Please check the AI score for all essays before submitting.");
            return;
        }

        // Check if any score exceeds 20%
        const highAiEssays = essayQuestions.filter(q => aiScores[q.id]?.percentage > 20);
        if (highAiEssays.length > 0) {
            toast.error(
                `⚠️ Submission Blocked: ${highAiEssays.length} essay(s) have an AI detection score above 20%. Please rewrite them to ensure originality.`
            );
            return;
        }

        setIsSubmitting(true);
        try {
            const res = await fetch(route('dashboard.quiz.submit', { course: course.slug, assessment: assessment.id }), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({ 
                    answers, 
                    essay_answers: essayAnswers,
                    ai_scores: aiScores // Send scores to backend for verification
                }),
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message);

            setFinalScore(data.score);
            setFinalManualReview(Boolean(data.manual_review));
            setShowCompletionModal(true);
            
            if (attempt?.id) localStorage.removeItem(`quiz_timer_${attempt.id}`);
        } catch (err) {
            toast.error(err.message);
        } finally {
            setIsSubmitting(false);
        }
    };

    const handleLockoutRedirect = () => {
        router.visit(route('dashboard.courses.show', course.slug));
    };

    return (
        <AuthenticatedLayout>
            <Head title={`${assessment.title} | Quiz`} />
            
            {/* Header */}
            <div className="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm px-4 py-3">
                <div className="max-w-[1600px] mx-auto flex justify-between items-center">
                    <div>
                        <Link href={route('dashboard.courses.show', course.slug)} className="text-sm text-gray-500 hover:text-gray-700">← Back to Course</Link>
                        <h1 className="text-xl font-bold text-gray-900">{assessment.title}</h1>
                    </div>
                    <div className="flex items-center gap-4">
                        <div className={`flex items-center gap-2 px-4 py-2 rounded-full ${timeRemaining < 300 ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'}`}>
                            <ClockIcon className="w-5 h-5" />
                            <span className="font-mono font-bold">{formatTime(timeRemaining)}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div className="flex max-w-[1600px] mx-auto bg-gray-50 min-h-screen">
                <QuizSidebar 
                    questions={mcqQuestions}
                    essayQuestions={essayQuestions}
                    currentQuestionIndex={currentQuestionIndex}
                    setCurrentQuestionIndex={setCurrentQuestionIndex}
                    answers={answers}
                    flaggedQuestions={flaggedQuestions}
                    toggleFlag={toggleFlag}
                    partASubmitted={partASubmitted}
                    canAccessPartB={canAccessPartB}
                    essayAnswers={essayAnswers}
                />

                <div className="flex-1 p-6">
                    <div className="max-w-3xl mx-auto space-y-6">
                        
                        {/* PART 2: QUESTION & ANSWER (Part A) */}
                        {mcqQuestions.length > 0 && (
                            <div className="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                                <div className="flex justify-between items-start mb-4">
                                    <span className="text-sm font-semibold text-blue-600 uppercase tracking-wide">
                                        Part A • Question {currentQuestionIndex + 1} of {mcqQuestions.length}
                                    </span>
                                    <button onClick={() => toggleFlag(currentQuestion?.id)} className="text-gray-400 hover:text-amber-500">
                                        <FlagIcon className={`w-5 h-5 ${flaggedQuestions.has(currentQuestion?.id) ? 'text-amber-500 fill-current' : ''}`} />
                                    </button>
                                </div>
                                
                                <h2 className="text-lg font-medium text-gray-900 mb-6">{currentQuestion?.text}</h2>
                                
                                <div className="space-y-3">
                                    {currentQuestion?.options?.map((option, idx) => (
                                        <label key={idx} className={`flex items-center p-4 border-2 rounded-lg cursor-pointer transition ${
                                            answers[currentQuestion?.id] === option ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:bg-gray-50'
                                        } ${isPartALocked ? 'opacity-50 cursor-not-allowed' : ''}`}>
                                            <input
                                                type="radio"
                                                name={`q-${currentQuestion?.id}`}
                                                value={option}
                                                checked={answers[currentQuestion?.id] === option}
                                                onChange={() => handleAnswer(currentQuestion?.id, option)}
                                                disabled={isPartALocked}
                                                className="w-4 h-4 text-blue-600"
                                            />
                                            <span className="ml-3 text-gray-700">{option}</span>
                                        </label>
                                    ))}
                                </div>

                                <div className="flex justify-between mt-8 pt-4 border-t border-gray-100">
                                    <button 
                                        onClick={() => setCurrentQuestionIndex(prev => Math.max(0, prev - 1))}
                                        disabled={currentQuestionIndex === 0}
                                        className="px-4 py-2 text-gray-600 disabled:opacity-50 hover:bg-gray-100 rounded-lg"
                                    >
                                        Previous
                                    </button>
                                    
                                    {currentQuestionIndex < mcqQuestions.length - 1 ? (
                                        <button 
                                            onClick={() => setCurrentQuestionIndex(prev => prev + 1)}
                                            className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2"
                                        >
                                            Next <ChevronRightIcon className="w-4 h-4" />
                                        </button>
                                    ) : (
                                        <button 
                                            onClick={submitPartA}
                                            disabled={isSubmitting || partASubmitted}
                                            className="px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 disabled:opacity-50 flex items-center gap-2"
                                        >
                                            {partASubmitted ? <><CheckCircleIcon className="w-4 h-4"/> Submitted</> : 'Submit Part A'}
                                        </button>
                                    )}
                                </div>
                            </div>
                        )}

                        {/* PART 3: ESSAY (Part B) */}
                        {essayQuestions.length > 0 && (
                            <div className={`bg-white rounded-xl shadow-sm p-6 border transition-all ${
                                canAccessPartB ? 'border-indigo-200 ring-1 ring-indigo-100' : 'border-gray-200 opacity-75'
                            }`}> 
                                <div className="mb-6">
                                    <span className="text-sm font-semibold text-indigo-700 uppercase tracking-wide">Part B • Essay Response</span>
                                    <h2 className="text-xl font-bold text-gray-900 mt-1">Essay Assessment</h2>
                                    
                                    {/* IGRCFP AI Policy Notice */}
                                    <div className="mt-4 p-4 bg-blue-50 border border-blue-100 rounded-lg text-sm text-blue-800">
                                        <p className="font-semibold flex items-center gap-2 mb-2">
                                            <ShieldCheckIcon className="w-5 h-5" />
                                            IGRCFP AI Usage Policy
                                        </p>
                                        <ul className="list-disc list-inside space-y-1 ml-1">
                                            <li>Up to <strong>20% AI assistance</strong> is acceptable for planning and editing.</li>
                                            <li>At least <strong>80%</strong> must be your own original intellectual work.</li>
                                            <li><strong>Submission Blocked:</strong> You cannot submit if AI score exceeds 20%.</li>
                                        </ul>
                                    </div>

                                    {!canAccessPartB && partASubmitted && partAScore < 50 && (
                                        <div className="mt-3 p-3 bg-red-50 text-red-700 rounded-lg text-sm border border-red-100">
                                            <strong>Access Denied:</strong> You scored {partAScore}% in Part A. A minimum of 50% is required to attempt the essay.
                                        </div>
                                    )}
                                    {!partASubmitted && (
                                        <div className="mt-3 p-3 bg-amber-50 text-amber-700 rounded-lg text-sm border border-amber-100">
                                            Please submit Part A first to unlock this section.
                                        </div>
                                    )}
                                </div>

                                <div className="space-y-8">
                                    {essayQuestions.map((q, idx) => {
                                        const aiData = aiScores[q.id];
                                        const isAiHigh = aiData && aiData.percentage > 20;
                                        
                                        return (
                                            <div key={q.id} className="space-y-2">
                                                <label className="block text-sm font-medium text-gray-700">
                                                    Essay Prompt {idx + 1}: {q.text}
                                                </label>
                                                
                                                {/* Rich Text Editor Wrapper for Smoothness */}
                                                <div className={`relative border rounded-lg overflow-hidden transition-colors ${
                                                    isAiHigh ? 'border-red-300 ring-1 ring-red-200' : 'border-gray-300 focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500'
                                                }`}>
                                                    <RichTextEditor
                                                        value={essayAnswers[q.id] || ''}
                                                        onChange={(html) => handleEssayChange(q.id, html)}
                                                        placeholder="Write your response here..."
                                                    />
                                                    
                                                    {/* Inline Status Indicator */}
                                                    {aiData && !aiData.loading && (
                                                        <div className={`px-3 py-2 text-xs font-medium flex items-center justify-between border-t ${
                                                            isAiHigh ? 'bg-red-50 text-red-700 border-red-100' : 'bg-green-50 text-green-700 border-green-100'
                                                        }`}>
                                                            <span>
                                                                {isAiHigh ? '⚠️ AI Score Too High' : '✅ AI Score Acceptable'}
                                                            </span>
                                                            <span>{aiData.percentage}%</span>
                                                        </div>
                                                    )}
                                                </div>

                                                {/* AI Detection Controls */}
                                                <div className="mt-2 flex items-center justify-between">
                                                    <button
                                                        type="button"
                                                        onClick={() => handleCheckAiScore(q.id, essayAnswers[q.id])}
                                                        disabled={!essayAnswers[q.id] || aiData?.loading}
                                                        className={`text-xs flex items-center gap-1 font-medium disabled:opacity-50 transition ${
                                                            isAiHigh 
                                                                ? 'text-red-600 hover:text-red-800' 
                                                                : 'text-indigo-600 hover:text-indigo-800'
                                                        }`}
                                                    >
                                                        {aiData?.loading ? (
                                                            <>
                                                                <svg className="animate-spin h-3 w-3" viewBox="0 0 24 24"><circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle><path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                                Checking...
                                                            </>
                                                        ) : (
                                                            <>
                                                                <ShieldCheckIcon className="w-3 h-3" />
                                                                {aiData ? 'Re-check AI Score' : 'Check AI Score'}
                                                            </>
                                                        )}
                                                    </button>

                                                    {aiData && !aiData.loading && (
                                                        <div className="flex items-center gap-2">
                                                            <div className="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                                <div 
                                                                    className={`h-full rounded-full transition-all duration-500 ${
                                                                        isAiHigh ? 'bg-red-500' : 'bg-green-500'
                                                                    }`}
                                                                    style={{ width: `${Math.min(100, aiData.percentage)}%` }}
                                                                ></div>
                                                            </div>
                                                        </div>
                                                    )}
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>

                                <div className="mt-8 flex justify-end">
                                    <button
                                        onClick={submitPartB}
                                        disabled={isSubmitting || !canAccessPartB}
                                        className="px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg transition transform hover:-translate-y-0.5"
                                    >
                                        {isSubmitting ? 'Submitting...' : 'Submit Final Assessment'}
                                    </button>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {/* Completion Modal */}
            {showCompletionModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
                    <div className="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center">
                        <TrophyIcon className="w-16 h-16 text-yellow-500 mx-auto mb-4" />
                        <h2 className="text-2xl font-bold text-gray-900 mb-2">Assessment Submitted!</h2>
                        <p className="text-gray-600 mb-6">
                            {finalManualReview ? 'Your essay is being reviewed by an examiner.' : `Your final score is ${finalScore}%.`}
                        </p>
                        <button 
                            onClick={() => window.location.href = route('dashboard.courses.show', course.slug)}
                            className="w-full py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700"
                        >
                            Return to Course
                        </button>
                    </div>
                </div>
            )}

            {/* Lockout Modal */}
            {showLockoutModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                    <div className="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center relative animate-in fade-in zoom-in duration-300">
                        <div className="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <LockClosedIcon className="w-8 h-8 text-red-600" />
                        </div>
                        
                        <h2 className="text-2xl font-bold text-gray-900 mb-2">
                            {lockInfo?.permanentlyLocked ? 'Course Permanently Locked' : 'Attempt Failed'}
                        </h2>
                        <p className="text-gray-600 mb-2">
                            You scored <span className="font-bold text-red-600">{partAScore}%</span>.
                        </p>
                        <p className="text-gray-600 mb-6">
                            {lockInfo?.permanentlyLocked
                                ? 'You have reached the maximum of six unsuccessful quiz attempts. You can no longer retake this course.'
                                : lockInfo?.failedAttempts === 3
                                    ? 'A minimum of 50% is required to proceed. After three unsuccessful attempts, this course is locked for 3 days.'
                                    : 'A minimum of 50% is required to proceed. This course is now locked for 24 hours.'}
                        </p>
                        
                        <button
                            onClick={handleLockoutRedirect}
                            className="w-full py-3 bg-gray-900 text-white font-semibold rounded-xl hover:bg-gray-800 transition"
                        >
                            Return to Course
                        </button>
                    </div>
                </div>
            )} 
        </AuthenticatedLayout>
    );
}
