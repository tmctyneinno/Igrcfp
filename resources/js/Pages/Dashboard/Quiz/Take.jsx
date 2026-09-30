import React, { useState, useEffect, useMemo, useCallback, useRef } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import DOMPurify from 'dompurify';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import toast from 'react-hot-toast';
import RichTextEditor from '@/Components/RichTextEditor';
import QuizSidebar from './QuizSidebar';
import { 
    ClockIcon, ChevronLeftIcon, ChevronRightIcon, FlagIcon, 
    CheckCircleIcon, TrophyIcon, SparklesIcon, XCircleIcon, ArrowRightIcon,
    LockClosedIcon, InformationCircleIcon 
} from '@heroicons/react/24/outline'; 

function RichQuestionContent({ html, className = '' }) {
    return (
        <div
            className={`prose prose-sm max-w-none text-gray-800 [&_p]:my-0 [&_p+p]:mt-3 [&_ul]:my-3 [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:my-3 [&_ol]:list-decimal [&_ol]:pl-6 [&_li]:my-1 [&_a]:text-blue-700 [&_a]:underline ${className}`}
            dangerouslySetInnerHTML={{ __html: DOMPurify.sanitize(html || '') }}
        />
    );
}

export default function QuizTake({ 
    course, assessment, enrollment, attempt, 
    questions: courseQuestions = [],
    timeRemaining: initialTimeRemaining,
}) {
    // State
    const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
    
    // Initialize answers from attempt or empty object
    const [answers, setAnswers] = useState(() => {
        const saved = attempt?.answers;
        return typeof saved === 'string' ? JSON.parse(saved) : (saved || {});
    });
    
    const [essayAnswers, setEssayAnswers] = useState({});
    
    // Initialize timer
    const [timeRemaining, setTimeRemaining] = useState(() => {
        const savedTime = localStorage.getItem(`quiz_timer_${attempt?.id}`);
        if (savedTime && !isNaN(parseInt(savedTime))) {
            return parseInt(savedTime);
        }
        return initialTimeRemaining;
    });
    
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [hasStartedQuiz, setHasStartedQuiz] = useState(false);
    const [isRecording, setIsRecording] = useState(false);
    const [recordingError, setRecordingError] = useState('');
    const [flaggedQuestions, setFlaggedQuestions] = useState(new Set());
    const [hasUnsavedChanges, setHasUnsavedChanges] = useState(false);
    const mediaRecorderRef = useRef(null);
    const mediaStreamRef = useRef(null);
    const recordingChunksRef = useRef([]);
    const stoppingRecordingRef = useRef(false);
    const submissionInProgressRef = useRef(false);
    const submitCurrentAttemptRef = useRef(null);
    
    // Part A/B Logic State
    const [partASubmitted, setPartASubmitted] = useState(() => {
        return Boolean(attempt?.part_a_submitted) || attempt?.status === 'completed' || attempt?.status === 'submitted';
    });
    
    // Initialize score from attempt if available
    const [partAScore, setPartAScore] = useState(attempt?.score || null);
    
    // Modals
    const [showCompletionModal, setShowCompletionModal] = useState(false);
    const [showLockoutModal, setShowLockoutModal] = useState(false);
    const [lockInfo, setLockInfo] = useState(null);
    
    const [finalScore, setFinalScore] = useState(null);
    const [finalManualReview, setFinalManualReview] = useState(false);

    // Derived Data
    const allQuestions = courseQuestions || [];
    const getQuestionType = (question) => String(
        question.type ?? question.question_type ?? ''
    ).toLowerCase();
    const essayQuestions = allQuestions.filter(question => getQuestionType(question) === 'essay');
    const mcqQuestions = allQuestions.filter(question => getQuestionType(question) !== 'essay');
    const currentQuestion = mcqQuestions[currentQuestionIndex];
    
    // Validation Logic
    const canAccessPartB = (partASubmitted && partAScore !== null && partAScore >= 50) || mcqQuestions.length === 0;
    const isPartALocked = partASubmitted;
    
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
        if (!isRecording || partASubmitted) return;
        if (timeRemaining <= 0) {
            submitCurrentAttemptRef.current?.({ timedOut: true });
            return;
        }

        const timer = setInterval(() => {
            setTimeRemaining(prev => {
                const newTime = prev - 1;
                if (attempt?.id) {
                    localStorage.setItem(`quiz_timer_${attempt.id}`, newTime.toString());
                }
                if (newTime <= 0) {
                    clearInterval(timer);
                    toast.error('Time is up! Submitting your answers...');
                    submitCurrentAttemptRef.current?.({ timedOut: true });
                    return 0;
                }
                return newTime;
            });
        }, 1000);

        return () => clearInterval(timer);
    }, [isRecording, partASubmitted, timeRemaining, attempt?.id]);

    // Cleanup localStorage
    useEffect(() => {
        return () => {
            if (attempt?.id && (partASubmitted || showCompletionModal)) {
                localStorage.removeItem(`quiz_timer_${attempt.id}`);
            }
        };
    }, [partASubmitted, showCompletionModal, attempt?.id]);

    // Handlers
    const handleAnswer = (questionId, answer) => {
        if (isPartALocked) return;
        setAnswers(prev => ({ ...prev, [questionId]: answer }));
        setHasUnsavedChanges(true);
    };

    const handleEssayChange = (questionId, html) => {
        setEssayAnswers(prev => ({ ...prev, [questionId]: html }));
        setHasUnsavedChanges(true);
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

    const startScreenRecording = async () => {
        setRecordingError('');

        if (!navigator.mediaDevices?.getDisplayMedia || !window.MediaRecorder) {
            setRecordingError('Screen recording is not supported by this browser. Please use a current version of Chrome, Edge, or Firefox.');
            return;
        }

        try {
            const stream = await navigator.mediaDevices.getDisplayMedia({ video: true, audio: false });
            const supportedTypes = [
                'video/webm;codecs=vp9,opus',
                'video/webm;codecs=vp8,opus',
                'video/webm',
                'video/mp4',
            ];
            const mimeType = supportedTypes.find(type => MediaRecorder.isTypeSupported(type));
            const recorder = new MediaRecorder(stream, mimeType ? { mimeType } : undefined);

            recordingChunksRef.current = [];
            mediaStreamRef.current = stream;
            mediaRecorderRef.current = recorder;
            recorder.addEventListener('dataavailable', event => {
                if (event.data?.size) recordingChunksRef.current.push(event.data);
            });
            recorder.addEventListener('stop', () => setIsRecording(false));
            stream.getVideoTracks()[0]?.addEventListener('ended', () => {
                if (stoppingRecordingRef.current) return;
                toast.error('Screen sharing stopped. Your current quiz answers are being submitted.');
                submitCurrentAttemptRef.current?.({ timedOut: true, recordingEnded: true });
            });

            recorder.start(1000);
            setIsRecording(true);
            setHasStartedQuiz(true);
        } catch (error) {
            setRecordingError(error.name === 'NotAllowedError'
                ? 'Screen sharing is required to start this quiz. Allow screen sharing and try again.'
                : 'Unable to start screen recording. Please check your browser permissions and try again.');
        }
    };

    const getRecordingBlob = async (stopRecording) => {
        const recorder = mediaRecorderRef.current;

        if (recorder?.state === 'recording') {
            const dataAvailable = new Promise(resolve => {
                recorder.addEventListener('dataavailable', resolve, { once: true });
            });
            recorder.requestData();
            await dataAvailable;
        }

        if (stopRecording && recorder && recorder.state !== 'inactive') {
            stoppingRecordingRef.current = true;
            const stopped = new Promise(resolve => recorder.addEventListener('stop', resolve, { once: true }));
            recorder.stop();
            await stopped;
            mediaStreamRef.current?.getTracks().forEach(track => track.stop());
            stoppingRecordingRef.current = false;
        }

        const mimeType = recorder?.mimeType || recordingChunksRef.current[0]?.type || 'video/webm';
        return new Blob(recordingChunksRef.current, { type: mimeType });
    };

    const uploadScreenRecording = async (blob) => {
        if (!blob.size) throw new Error('No screen recording data was captured. Please restart the quiz and try again.');

        const chunkSize = 1024 * 1024;
        const chunkCount = Math.ceil(blob.size / chunkSize);
        const uploadId = crypto.randomUUID();
        const mimeType = (blob.type || 'video/webm').split(';')[0];

        for (let chunkIndex = 0; chunkIndex < chunkCount; chunkIndex += 1) {
            const chunk = blob.slice(chunkIndex * chunkSize, Math.min((chunkIndex + 1) * chunkSize, blob.size), mimeType);
            const formData = new FormData();
            formData.append('upload_id', uploadId);
            formData.append('chunk_index', String(chunkIndex));
            formData.append('chunk', chunk, `recording-${chunkIndex}.${mimeType === 'video/mp4' ? 'mp4' : 'webm'}`);

            const response = await fetch(route('dashboard.quiz.recording-chunk', attempt.id), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: formData,
            });
            const data = await readJsonResponse(response);
            if (!response.ok) throw new Error(data.message || 'The screen recording could not be uploaded. Please try again.');
        }

        return { uploadId, chunkCount, mimeType };
    };

    const sendQuizSubmission = async (payload, { stopRecording }) => {
        const blob = await getRecordingBlob(stopRecording);
        const recording = await uploadScreenRecording(blob);
        const formData = new FormData();
        formData.append('answers', JSON.stringify(payload.answers || {}));
        if (payload.essay_answers) formData.append('essay_answers', JSON.stringify(payload.essay_answers));
        if (payload.part_a_only) formData.append('part_a_only', '1');
        formData.append('screen_recording_upload_id', recording.uploadId);
        formData.append('screen_recording_chunk_count', String(recording.chunkCount));
        formData.append('screen_recording_mime_type', recording.mimeType);

        const response = await fetch(route('dashboard.quiz.submit', { course: course.slug, assessment: assessment.id }), {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: formData,
        });
        const data = await readJsonResponse(response);
        if (!response.ok) throw new Error(data.message || data.error || 'The quiz could not be submitted. Please try again.');
        return data;
    };

    // Submission Logic
    const submitPartA = async ({ timedOut = false, recordingEnded = false } = {}) => {
        if (submissionInProgressRef.current) return;
        const unanswered = mcqQuestions.filter(q => !answers[q.id]).length;
        if (!timedOut && !recordingEnded && unanswered > 0 && !confirm(`You have ${unanswered} unanswered questions. Submit anyway?`)) return;

        submissionInProgressRef.current = true;
        setIsSubmitting(true);
        try {
            const continuesToPartB = essayQuestions.length > 0 && !recordingEnded && !timedOut;
            const payload = { answers };
            if (continuesToPartB || (!timedOut && !recordingEnded && essayQuestions.length === 0)) payload.part_a_only = true;
            if (recordingEnded) payload.essay_answers = essayAnswers;

            const data = await sendQuizSubmission(payload, {
                stopRecording: !continuesToPartB,
            });

            if (!data.part_a_submitted) {
                setFinalScore(data.score);
                setFinalManualReview(Boolean(data.manual_review));
                setShowCompletionModal(true);
                if (attempt?.id) localStorage.removeItem(`quiz_timer_${attempt.id}`);
                return;
            }

            setPartASubmitted(true);
            setPartAScore(data.score);
            setHasUnsavedChanges(false);
            
            if (attempt?.id) {
                localStorage.removeItem(`quiz_timer_${attempt.id}`);
            }
            
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
            submissionInProgressRef.current = false;
            setIsSubmitting(false);
        }
    };

    const submitPartB = async ({ timedOut = false } = {}) => {
        if (submissionInProgressRef.current) return;
        if (!canAccessPartB) {
            toast.error("You must score at least 50% in Part A to submit Part B.");
            return;
        } 

        const emptyEssays = essayQuestions.filter(q => !essayAnswers[q.id] || essayAnswers[q.id].length < 10).length;
        if (!timedOut && emptyEssays > 0 && !confirm(`${emptyEssays} essays are empty. Submit anyway?`)) return;

        submissionInProgressRef.current = true;
        setIsSubmitting(true);
        try {
            const data = await sendQuizSubmission({ answers, essay_answers: essayAnswers }, {
                stopRecording: true,
            });

            setFinalScore(data.score);
            setFinalManualReview(Boolean(data.manual_review));
            setShowCompletionModal(true);
            
            if (attempt?.id) {
                localStorage.removeItem(`quiz_timer_${attempt.id}`);
            }
        } catch (err) {
            toast.error(err.message);
        } finally {
            submissionInProgressRef.current = false;
            setIsSubmitting(false);
        }
    };

    submitCurrentAttemptRef.current = options => partASubmitted
        ? submitPartB({ timedOut: Boolean(options?.timedOut || options?.recordingEnded) })
        : submitPartA(options);

    useEffect(() => () => {
        stoppingRecordingRef.current = true;
        if (mediaRecorderRef.current?.state !== 'inactive') mediaRecorderRef.current?.stop();
        mediaStreamRef.current?.getTracks().forEach(track => track.stop());
    }, []);

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
                        {isRecording && (
                            <span className="inline-flex items-center gap-2 text-sm font-medium text-red-700">
                                <span className="h-2.5 w-2.5 rounded-full bg-red-600 animate-pulse" />
                                Screen recording
                            </span>
                        )}
                        {!partASubmitted && (
                            <div className={`flex items-center gap-2 px-4 py-2 rounded-full ${timeRemaining < 300 ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'}`}>
                                <ClockIcon className="w-5 h-5" />
                                <span className="font-mono font-bold">{formatTime(timeRemaining)}</span>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            <div className="flex max-w-[1600px] mx-auto bg-gray-50 min-h-screen">
                {/* PART 1: SIDEBAR */}
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

                {/* MAIN CONTENT AREA */}
                <div className="flex-1 p-6">
                    <div className="max-w-3xl mx-auto space-y-6">
                        
                        {/* PART 2: QUESTION & ANSWER (Part A) */}
                        {mcqQuestions.length > 0 && !partASubmitted && (
                            <div className="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                                <div className="flex justify-between items-start mb-4">
                                    <span className="text-sm font-semibold text-blue-600 uppercase tracking-wide">
                                        Part A • Question {currentQuestionIndex + 1} of {mcqQuestions.length}
                                    </span>
                                    <button onClick={() => toggleFlag(currentQuestion?.id)} className="text-gray-400 hover:text-amber-500">
                                        <FlagIcon className={`w-5 h-5 ${flaggedQuestions.has(currentQuestion?.id) ? 'text-amber-500 fill-current' : ''}`} />
                                    </button>
                                </div>
                                
                                <RichQuestionContent
                                    html={currentQuestion?.text}
                                    className="mb-6 text-lg font-medium"
                                />
                                
                                <div className="space-y-3">
                                    {currentQuestion?.options?.map((option, idx) => (
                                        <label key={idx} className={`flex items-center p-4 border-2 rounded-lg cursor-pointer transition ${
                                            answers[currentQuestion?.id] === option ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:bg-gray-50'
                                        }`}>
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
                                    
                                    {/* Status Messages */}
                                    {!canAccessPartB && partASubmitted && partAScore < 50 && (
                                        <div className="mt-3 p-3 bg-red-50 text-red-700 rounded-lg text-sm border border-red-100">
                                            <strong>Access Denied:</strong> You scored {partAScore}% in Part A. A minimum of 50% is required to attempt the essay.
                                        </div>
                                    )}
                                    {!partASubmitted && mcqQuestions.length > 0 && (
                                        <div className="mt-3 p-3 bg-amber-50 text-amber-700 rounded-lg text-sm border border-amber-100">
                                            Please submit Part A first to unlock this section.
                                        </div>
                                    )}
                                    {partASubmitted && partAScore >= 50 && (
                                        <div className="mt-3 p-3 bg-green-50 text-green-700 rounded-lg text-sm border border-green-100">
                                            <strong>Part A Passed:</strong> You scored {partAScore}%. You may now complete your essay.
                                        </div>
                                    )}
                                </div>

                                <div className="space-y-8">
                                    {essayQuestions.map((q, idx) => (
                                        <div key={q.id} className="space-y-4">
                                            {/* Question Header with Points */}
                                            <div className="flex justify-between items-start">
                                                <label className="block text-lg font-medium text-gray-900">
                                                    Essay Prompt {idx + 1}
                                                </label>
                                                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    {q.points} Points
                                                </span>
                                            </div>

                                            {/* Question Text */}
                                            <RichQuestionContent
                                                html={q.text}
                                                className="bg-gray-50 p-4 rounded-lg border border-gray-200"
                                            />

                                            {/* Explanation (if available) */}
                                            {q.explanation && (
                                                <div className="flex items-start gap-2 p-3 bg-blue-50 rounded-lg border border-blue-100">
                                                    <InformationCircleIcon className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" />
                                                    <div>
                                                        <p className="text-sm font-semibold text-blue-800 mb-1">Guidance:</p>
                                                        <RichQuestionContent html={q.explanation} className="text-sm text-blue-700" />
                                                    </div>
                                                </div>
                                            )}

                                            {/* Rich Text Editor */}
                                            <RichTextEditor
                                                value={essayAnswers[q.id] || ''}
                                                onChange={(html) => handleEssayChange(q.id, html)}
                                                disabled={!canAccessPartB}
                                                minHeight={320}
                                                placeholder={canAccessPartB ? "Write your response here..." : "Complete Part A to unlock"}
                                            />
                                        </div>
                                    ))}
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

            {!hasStartedQuiz && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/80 p-4">
                    <div className="w-full max-w-lg rounded-xl bg-white p-8 shadow-2xl">
                        <h2 className="text-2xl font-bold text-gray-900">Start quiz and share your screen</h2>
                        <p className="mt-3 text-gray-600">
                            Your screen will be recorded and submitted with your answers. Select the screen or quiz window in your browser prompt, and keep sharing until you submit.
                        </p>
                        {recordingError && (
                            <p role="alert" className="mt-4 rounded-md bg-red-50 p-3 text-sm text-red-700">{recordingError}</p>
                        )}
                        <button
                            type="button"
                            onClick={startScreenRecording}
                            className="mt-6 w-full rounded-md bg-blue-700 px-5 py-3 font-semibold text-white hover:bg-blue-800"
                        >
                            Start quiz and share screen
                        </button>
                    </div>
                </div>
            )}

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
