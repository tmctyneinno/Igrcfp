import React, { useState, useEffect, useMemo, useCallback } from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import toast from 'react-hot-toast';
import { 
    ClockIcon, 
    ChevronLeftIcon, 
    ChevronRightIcon, 
    FlagIcon, 
    CheckCircleIcon, 
    LockClosedIcon,
    PlayCircleIcon,
    ArrowLeftIcon,
    ArrowRightIcon,
    BookOpenIcon,
    TrophyIcon,
    SparklesIcon,
    XCircleIcon
} from '@heroicons/react/24/outline';

const isDev = process.env.NODE_ENV === 'development';

export default function QuizTake({ 
    course, 
    assessment, 
    enrollment, 
    attempt, 
    modules = [],
    timeRemaining: initialTimeRemaining,
    timeLimit 
}) {
    const [currentQuizIndex, setCurrentQuizIndex] = useState(0);
    const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
    const [answers, setAnswers] = useState(() => attempt?.answers || {});
    const [timeRemaining, setTimeRemaining] = useState(initialTimeRemaining);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [isSavingProgress, setIsSavingProgress] = useState(false);
    const [lastSaved, setLastSaved] = useState(null);
    const [flaggedQuestions, setFlaggedQuestions] = useState(new Set());
    const [expandedModules, setExpandedModules] = useState({});
    const [selectedQuiz, setSelectedQuiz] = useState(null);
    const [completedQuizzes, setCompletedQuizzes] = useState(new Set());
    const [hasUnsavedChanges, setHasUnsavedChanges] = useState(false);
    const [showCompletionModal, setShowCompletionModal] = useState(false);
    const [finalScore, setFinalScore] = useState(null);
    
    // ==================== HELPER FUNCTIONS ====================
    
    const areModuleLessonsCompleted = useCallback((module) => {
        const totalLessons = module.lessons?.length || 0;
        const completedLessons = module.lessons?.filter(l => l.completed).length || 0;
        return totalLessons === 0 || completedLessons === totalLessons;
    }, []);
    
    const isModuleUnlocked = useCallback((moduleId, modulesList, completedSet) => {
        const moduleIndex = modulesList.findIndex(m => m.id === moduleId);
        if (moduleIndex === 0) return true;
        
        for (let i = 0; i < moduleIndex; i++) {
            const prevModule = modulesList[i];
            const prevModuleQuizzes = prevModule.quizzes || [];
            
            if (prevModuleQuizzes.length > 0) {
                const hasCompletedQuiz = prevModuleQuizzes.some(q => completedSet.has(q.id));
                if (!hasCompletedQuiz) return false;
            }
        }
        return true;
    }, []);
    
    // ==================== MEMOIZED DATA ====================
    
    const allQuizzes = useMemo(() => {
        return modules.flatMap(module => 
            (module.quizzes || []).map(quiz => ({
                ...quiz,
                moduleId: module.id,
                moduleTitle: module.title,
                moduleNumber: module.module_number,
                lessonsCompleted: areModuleLessonsCompleted(module),
                attempt: quiz.attempt || null
            }))
        );
    }, [modules, areModuleLessonsCompleted]);
    
    const allQuizzesWithStatus = useMemo(() => {
        return allQuizzes.map(quiz => ({
            ...quiz,
            moduleUnlocked: isModuleUnlocked(quiz.moduleId, modules, completedQuizzes)
        }));
    }, [allQuizzes, modules, completedQuizzes, isModuleUnlocked]);
    
    const currentQuiz = allQuizzesWithStatus[currentQuizIndex];
    const questions = currentQuiz?.questions || [];
    const currentQuestion = questions[currentQuestionIndex];
    const progress = questions.length > 0 
        ? Math.round(((currentQuestionIndex + 1) / questions.length) * 100) 
        : 0;
    
    const isQuizUnlocked = useCallback((quiz) => {
        if (!quiz.moduleUnlocked) return false;
        return quiz.lessonsCompleted;
    }, []);
    
    const isLastQuiz = currentQuizIndex === allQuizzesWithStatus.length - 1;
    const isOnlyQuiz = allQuizzesWithStatus.length === 1;
    const allQuizzesCompleted = allQuizzesWithStatus.length > 0 && 
        allQuizzesWithStatus.every(q => completedQuizzes.has(q.id));
    
    const isCurrentQuizCompleted = currentQuiz && completedQuizzes.has(currentQuiz.id);
    
    // ==================== AUTO-SAVE LOGIC ====================
    
    useEffect(() => {
        if (!hasUnsavedChanges || !attempt?.id) return;
        
        const saveTimer = setTimeout(() => {
            autoSaveProgress();
        }, 30000);
        
        return () => clearTimeout(saveTimer);
    }, [answers, hasUnsavedChanges, attempt?.id]);
    
    useEffect(() => {
        const handleBeforeUnload = (e) => {
            if (hasUnsavedChanges) {
                e.preventDefault();
                e.returnValue = 'You have unsaved answers. Are you sure you want to leave?';
            }
        };
        
        window.addEventListener('beforeunload', handleBeforeUnload);
        return () => window.removeEventListener('beforeunload', handleBeforeUnload);
    }, [hasUnsavedChanges]);
    
    const autoSaveProgress = useCallback(async () => {
        if (!attempt?.id || !hasUnsavedChanges) return;
        
        setIsSavingProgress(true);
        
        try {
            const response = await fetch(route('dashboard.quiz.save-progress', attempt.id), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({ answers }),
            });
            
            if (response.ok) {
                setHasUnsavedChanges(false);
                setLastSaved(new Date());
            }
        } catch (error) {
            if (isDev) console.error('Auto-save failed:', error);
        } finally {
            setIsSavingProgress(false);
        }
    }, [answers, attempt?.id, hasUnsavedChanges]);
    
    // ==================== EFFECTS ====================
    
    useEffect(() => {
        if (currentQuiz) {
            setExpandedModules(prev => ({ ...prev, [currentQuiz.moduleId]: true }));
        }
    }, [currentQuiz]);
    
    useEffect(() => {
        if (allQuizzesWithStatus.length > 0 && !selectedQuiz) {
            const firstUnlockedIndex = allQuizzesWithStatus.findIndex(q => {
                return q.moduleUnlocked && q.lessonsCompleted && !completedQuizzes.has(q.id);
            });
            
            if (firstUnlockedIndex !== -1) {
                setCurrentQuizIndex(firstUnlockedIndex);
                setSelectedQuiz(allQuizzesWithStatus[firstUnlockedIndex]);
                setExpandedModules(prev => ({ 
                    ...prev, 
                    [allQuizzesWithStatus[firstUnlockedIndex].moduleId]: true 
                }));
            } else if (allQuizzesWithStatus.length > 0) {
                setCurrentQuizIndex(0);
                setSelectedQuiz(allQuizzesWithStatus[0]);
            }
        }
    }, [allQuizzesWithStatus.length]);
    
    useEffect(() => {
        if (!currentQuiz || isCurrentQuizCompleted) return;
        
        const timer = setInterval(() => {
            setTimeRemaining(prev => {
                if (prev <= 1) {
                    clearInterval(timer);
                    toast.error('Time\'s up! Auto-submitting your quiz...');
                    setTimeout(() => {
                        if (isOnlyQuiz || isLastQuiz) {
                            handleSubmitAndFinish();
                        } else {
                            handleSubmitAndContinue();
                        }
                    }, 1000);
                    return 0;
                }
                return prev - 1;
            });
        }, 1000);
        
        return () => clearInterval(timer);
    }, [currentQuiz?.id, isLastQuiz, isOnlyQuiz, isCurrentQuizCompleted]);
    
    // ==================== EVENT HANDLERS ====================
    
    const toggleModule = useCallback((moduleId) => {
        setExpandedModules(prev => ({ ...prev, [moduleId]: !prev[moduleId] }));
    }, []);
    
    const handleAnswer = useCallback((questionId, answer) => {
        if (isCurrentQuizCompleted) return;
        setAnswers(prev => ({ ...prev, [questionId]: answer }));
        setHasUnsavedChanges(true);
    }, [isCurrentQuizCompleted]);
    
    const handleNext = useCallback(() => {
        if (currentQuestionIndex < questions.length - 1) {
            setCurrentQuestionIndex(prev => prev + 1);
        }
    }, [currentQuestionIndex, questions.length]);
    
    const handlePrevious = useCallback(() => {
        if (currentQuestionIndex > 0) {
            setCurrentQuestionIndex(prev => prev - 1);
        }
    }, [currentQuestionIndex]);
    
    const toggleFlag = useCallback((questionId) => {
        if (isCurrentQuizCompleted) return;
        setFlaggedQuestions(prev => {
            const newSet = new Set(prev);
            newSet.has(questionId) ? newSet.delete(questionId) : newSet.add(questionId);
            return newSet;
        });
    }, [isCurrentQuizCompleted]);
    
    const handleSelectQuiz = useCallback((quiz) => {
        const index = allQuizzesWithStatus.findIndex(q => q.id === quiz.id);
        if (index === -1) return;
        
        const quizAttempt = quiz.attempt;
        const hasPassed = quizAttempt?.passed === true;
        
        if (hasPassed) {
            toast.success('You have already passed this quiz! View results for details.', {
                icon: '🏆',
                duration: 3000,
            });
            return;
        }
        
        if (completedQuizzes.has(quiz.id) && !hasPassed) {
            toast('You can retake this quiz to improve your score.', { icon: '📝' });
        }
        
        if (!isQuizUnlocked(quiz)) {
            if (!quiz.moduleUnlocked) {
                toast.error('Complete the previous module first');
            } else {
                toast.error('Complete all lessons in this module first');
            }
            return;
        }
        
        if (hasUnsavedChanges) {
            autoSaveProgress();
        }
        
        setCurrentQuizIndex(index);
        setSelectedQuiz(quiz);
        setCurrentQuestionIndex(0);
        setAnswers({});
        setFlaggedQuestions(new Set());
        setHasUnsavedChanges(false);
    }, [allQuizzesWithStatus, isQuizUnlocked, completedQuizzes, hasUnsavedChanges, autoSaveProgress]);

    const handleNextQuiz = useCallback(() => {
        for (let i = currentQuizIndex + 1; i < allQuizzesWithStatus.length; i++) {
            const quiz = allQuizzesWithStatus[i];
            
            if (!completedQuizzes.has(quiz.id) && isQuizUnlocked(quiz)) {
                setCurrentQuizIndex(i);
                setSelectedQuiz(quiz);
                setCurrentQuestionIndex(0);
                setAnswers({});
                setFlaggedQuestions(new Set());
                setHasUnsavedChanges(false);
                return;
            }
        }
        toast.success('🎉 You have completed all quizzes!');
    }, [currentQuizIndex, allQuizzesWithStatus, isQuizUnlocked, completedQuizzes]);
    
    const handlePreviousQuiz = useCallback(() => {
        for (let i = currentQuizIndex - 1; i >= 0; i--) {
            const quiz = allQuizzesWithStatus[i];
            if (!completedQuizzes.has(quiz.id) && isQuizUnlocked(quiz)) {
                setCurrentQuizIndex(i);
                setSelectedQuiz(quiz);
                setCurrentQuestionIndex(0);
                setAnswers({});
                setFlaggedQuestions(new Set());
                setHasUnsavedChanges(false);
                return;
            }
        }
    }, [currentQuizIndex, allQuizzesWithStatus, isQuizUnlocked, completedQuizzes]);

    const confirmUnanswered = useCallback((unanswered) => {
        return new Promise(resolve => {
            toast((t) => (
                <div className="space-y-3">
                    <p className="font-medium">{unanswered} unanswered question(s)</p>
                    <p className="text-sm text-gray-600">Continue anyway?</p>
                    <div className="flex gap-2">
                        <button 
                            onClick={() => { toast.dismiss(t.id); resolve(false); }}
                            className="px-3 py-1 text-sm bg-gray-200 rounded hover:bg-gray-300"
                        >
                            Cancel
                        </button>
                        <button 
                            onClick={() => { toast.dismiss(t.id); resolve(true); }}
                            className="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700"
                        >
                            Continue
                        </button>
                    </div>
                </div>
            ), { duration: Infinity });
        });
    }, []);

    const handleSubmitAndContinue = useCallback(async () => {
        const unanswered = questions.filter(q => !answers[q.id]).length;
        
        if (unanswered > 0) {
            const confirmed = await confirmUnanswered(unanswered);
            if (!confirmed) return;
        }
        
        setIsSubmitting(true);
        
        try {
            const submitUrl = route('dashboard.quiz.submit', { 
                course: course.slug, 
                assessment: currentQuiz.id 
            });
            
            const response = await fetch(submitUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ answers, skip_results: true }),
            });
            
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.message || 'Submission failed');
                }
                
                setCompletedQuizzes(prev => new Set([...prev, currentQuiz.id]));
                setHasUnsavedChanges(false);
                
                toast.success(`✅ Module ${currentQuiz.moduleNumber} completed!`, {
                    icon: '🎯',
                    duration: 2000,
                });
                
                handleNextQuiz();
            } else {
                throw new Error('Server returned HTML instead of JSON');
            }
            
        } catch (error) {
            if (isDev) console.error('Submit error:', error);
            toast.error('Failed to submit quiz. Please try again.');
        } finally {
            setIsSubmitting(false);
        }
    }, [questions, answers, course.slug, currentQuiz, handleNextQuiz, confirmUnanswered]);

    const handleSubmitAndFinish = useCallback(async () => {
        const unanswered = questions.filter(q => !answers[q.id]).length;
        
        if (unanswered > 0) {
            const confirmed = await confirmUnanswered(unanswered);
            if (!confirmed) return;
        }
        
        setIsSubmitting(true);
        
        try {
            const submitUrl = route('dashboard.quiz.submit', { 
                course: course.slug, 
                assessment: currentQuiz.id 
            });
            
            const response = await fetch(submitUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ answers }),
            });
            
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.message || 'Submission failed');
                }
                
                setCompletedQuizzes(prev => new Set([...prev, currentQuiz.id]));
                setHasUnsavedChanges(false);
                setFinalScore(data.score);
                setShowCompletionModal(true);
            } else {
                throw new Error('Server returned HTML instead of JSON');
            }
            
        } catch (error) {
            if (isDev) console.error('Submit error:', error);
            toast.error('Failed to submit quiz. Please try again.');
        } finally {
            setIsSubmitting(false);
        }
    }, [questions, answers, course.slug, currentQuiz, confirmUnanswered]);
     
    const handleViewResults = useCallback(() => {
        setShowCompletionModal(false);
        window.location.href = route('dashboard.quiz.results', {
            course: course.slug,
            assessment: currentQuiz.id
        });
    }, [course.slug, currentQuiz?.id]);
     
    // ==================== HELPER FUNCTIONS ====================
    
    const formatTime = useCallback((seconds) => {
        if (isNaN(seconds) || seconds < 0) return '0:00';
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    }, []);
    
    const getQuizStatus = useCallback((quiz) => {
        const quizAttempt = quiz.attempt;
        const hasPassed = quizAttempt?.passed === true;
        
        if (hasPassed) return 'passed';
        if (completedQuizzes.has(quiz.id)) return 'completed-failed';
        if (!quiz.moduleUnlocked) return 'locked-module';
        if (!isQuizUnlocked(quiz)) return 'locked-lessons';
        if (currentQuiz?.id === quiz.id) return 'active';
        return 'available';
    }, [completedQuizzes, isQuizUnlocked, currentQuiz?.id]);
    
    const moduleQuizzesMap = useMemo(() => {
        const map = {};
        allQuizzesWithStatus.forEach(quiz => {
            if (!map[quiz.moduleId]) {
                map[quiz.moduleId] = [];
            }
            map[quiz.moduleId].push(quiz);
        });
        return map;
    }, [allQuizzesWithStatus]);
    
    // ==================== RENDER ====================
    
    return (
        <AuthenticatedLayout>
            <Head title={`${currentQuiz?.title || 'Quiz'} | Course`} />
            
            {/* Completion Celebration Modal */}
            {showCompletionModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
                    <div className="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center animate-in fade-in zoom-in duration-300">
                        <div className="w-20 h-20 bg-gradient-to-br from-yellow-400 to-amber-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <TrophyIcon className="w-10 h-10 text-white" />
                        </div>
                        
                        <h2 className="text-3xl font-bold text-gray-900 mb-2">
                            Congratulations! 🎉
                        </h2>
                        
                        <p className="text-gray-600 mb-4">
                            You've successfully completed the course quiz!
                        </p>
                        
                        {finalScore !== null && (
                            <div className="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 mb-6">
                                <p className="text-sm text-gray-600 mb-1">Your Score</p>
                                <p className={`text-4xl font-bold ${finalScore >= 70 ? 'text-green-600' : 'text-orange-600'}`}>
                                    {finalScore}%
                                </p>
                                <p className="text-sm text-gray-500 mt-1">
                                    {finalScore >= 70 ? '✓ Passed' : '✗ Did not pass'}
                                </p>
                            </div>
                        )}
                        
                        <div className="flex gap-3">
                            <button
                                onClick={handleViewResults}
                                className="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg"
                            >
                                View Detailed Results
                            </button>
                        </div>
                        
                        <button
                            onClick={() => setShowCompletionModal(false)}
                            className="mt-3 text-sm text-gray-500 hover:text-gray-700"
                        >
                            Close
                        </button>
                    </div>
                </div>
            )}
            
            <div className="min-h-screen bg-gray-50">
                {/* Header */}
                <div className="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
                    <div className="px-4 py-3">
                        <div className="flex items-center justify-between max-w-[1600px] mx-auto">
                            <div>
                                <Link href={route('dashboard.courses.show', course.slug)} className="text-sm text-gray-500 hover:text-gray-700">
                                    ← Back to Course
                                </Link>
                                <h1 className="text-xl font-bold text-gray-900">
                                    {currentQuiz?.title || 'Course Quiz'}
                                </h1>
                                <p className="text-sm text-gray-500">
                                    {currentQuiz?.moduleTitle} 
                                    {allQuizzesWithStatus.length > 1 && (
                                        <> • Quiz {currentQuizIndex + 1} of {allQuizzesWithStatus.length}</>
                                    )}
                                </p>
                            </div>
                            <div className="flex items-center gap-4">
                                {hasUnsavedChanges && !isCurrentQuizCompleted && !currentQuiz?.attempt?.passed && (
                                    <div className="text-xs text-amber-600 flex items-center gap-1">
                                        <div className="w-2 h-2 bg-amber-600 rounded-full animate-pulse"></div>
                                        Unsaved
                                    </div>
                                )}
                                {lastSaved && !hasUnsavedChanges && !isCurrentQuizCompleted && !currentQuiz?.attempt?.passed && (
                                    <div className="text-xs text-green-600">
                                        ✓ Saved
                                    </div>
                                )}
                                
                                {/* Timer - Only for active quizzes */}
                                {!isCurrentQuizCompleted && !currentQuiz?.attempt?.passed && (
                                    <div className={`flex items-center gap-2 px-4 py-2 rounded-full ${
                                        timeRemaining < 60 ? 'bg-red-100 text-red-700' : 
                                        timeRemaining < 300 ? 'bg-amber-100 text-amber-700' : 
                                        'bg-blue-100 text-blue-700'
                                    }`}>
                                        <ClockIcon className="w-5 h-5" />
                                        <span className="font-mono text-lg font-bold">{formatTime(timeRemaining)}</span>
                                    </div>
                                )}
                                
                                {/* Passed Badge */}
                                {currentQuiz?.attempt?.passed && (
                                    <div className="flex items-center gap-2 px-4 py-2 rounded-full bg-green-100 text-green-700">
                                        <TrophyIcon className="w-5 h-5" />
                                        <span className="font-semibold">Passed • {currentQuiz.attempt.score}%</span>
                                    </div>
                                )}
                                
                                {/* Failed Badge */}
                                {isCurrentQuizCompleted && !currentQuiz?.attempt?.passed && (
                                    <div className="flex items-center gap-2 px-4 py-2 rounded-full bg-orange-100 text-orange-700">
                                        <XCircleIcon className="w-5 h-5" />
                                        <span className="font-semibold">Score: {currentQuiz?.attempt?.score || 0}%</span>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                    
                    {/* Progress Bar - Only for active quizzes */}
                    {currentQuiz && !isCurrentQuizCompleted && !currentQuiz?.attempt?.passed && (
                        <div className="px-4 pb-3">
                            <div className="max-w-[1600px] mx-auto">
                                <div className="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>Quiz Progress</span>
                                    <span>{progress}%</span>
                                </div>
                                <div className="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div className="h-full bg-blue-600 rounded-full transition-all" style={{ width: `${progress}%` }} />
                                </div>
                            </div>
                        </div>
                    )}
                    
                    {/* Mini indicator for passed quizzes */}
                    {currentQuiz?.attempt?.passed && (
                        <div className="px-4 pb-3">
                            <div className="max-w-[1600px] mx-auto">
                                <div className="flex items-center gap-2 text-xs">
                                    <CheckCircleIcon className="w-4 h-4 text-green-600" />
                                    <span className="text-gray-500">Quiz completed on {currentQuiz.attempt.completed_at}</span>
                                    <span className="text-gray-400">•</span>
                                    <button
                                        onClick={() => window.location.href = route('dashboard.quiz.results', {
                                            course: course.slug,
                                            assessment: currentQuiz.id
                                        })}
                                        className="text-blue-600 hover:text-blue-800 font-medium"
                                    >
                                        View Results →
                                    </button>
                                </div>
                            </div>
                        </div>
                    )}
                    
                    {/* Mini indicator for failed quizzes */}
                    {isCurrentQuizCompleted && !currentQuiz?.attempt?.passed && (
                        <div className="px-4 pb-3">
                            <div className="max-w-[1600px] mx-auto">
                                <div className="flex items-center gap-2 text-xs">
                                    <XCircleIcon className="w-4 h-4 text-orange-600" />
                                    <span className="text-gray-500">Passing score: {currentQuiz.passing_score}%</span>
                                    <span className="text-gray-400">•</span>
                                    <button
                                        onClick={() => window.location.href = route('dashboard.quiz.results', {
                                            course: course.slug,
                                            assessment: currentQuiz.id
                                        })}
                                        className="text-blue-600 hover:text-blue-800 font-medium"
                                    >
                                        View Results →
                                    </button>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
                
                {/* Main Content */}
                <div className="flex max-w-[1600px] mx-auto">
                    {/* Left Sidebar - Modules & Quizzes */}
                    <div className="w-80 bg-white border-r border-gray-200 min-h-[calc(100vh-120px)] sticky top-[120px] overflow-y-auto">
                        <div className="p-4">
                            <h3 className="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <BookOpenIcon className="w-4 h-4" />
                                Course Modules
                            </h3>
                            
                            <div className="space-y-2">
                                {modules.map((module, idx) => {
                                    const isExpanded = expandedModules[module.id] === true;
                                    const moduleQuizzes = moduleQuizzesMap[module.id] || [];
                                    const moduleUnlocked = isModuleUnlocked(module.id, modules, completedQuizzes);
                                    const moduleCompleted = moduleQuizzes.length > 0 && moduleQuizzes.every(q => completedQuizzes.has(q.id));
                                    
                                    if (moduleQuizzes.length === 0) return null;
                                    
                                    return (
                                        <div key={module.id} className={`border rounded-lg overflow-hidden ${
                                            !moduleUnlocked ? 'border-gray-200 bg-gray-50 opacity-75' : 
                                            currentQuiz?.moduleId === module.id ? 'border-blue-300 bg-blue-50/50' : 
                                            'border-gray-200 bg-white'
                                        }`}>
                                            <button onClick={() => toggleModule(module.id)} className="w-full px-4 py-3 text-left hover:bg-gray-50">
                                                <div className="flex items-center justify-between">
                                                    <div className="flex-1">
                                                        <div className="flex items-center gap-2 mb-1">
                                                            <span className="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">
                                                                Module {module.module_number || idx + 1}
                                                            </span>
                                                            {!moduleUnlocked ? (
                                                                <LockClosedIcon className="w-3 h-3 text-gray-400" />
                                                            ) : moduleCompleted ? (
                                                                <CheckCircleIcon className="w-3 h-3 text-green-500" />
                                                            ) : null}
                                                            <span className="text-xs text-gray-500">
                                                                {moduleQuizzes.length} Quiz{moduleQuizzes.length !== 1 ? 'zes' : ''}
                                                            </span>
                                                        </div>
                                                        <p className="text-sm font-medium text-gray-900">{module.title}</p>
                                                    </div>
                                                    {isExpanded ? 
                                                        <ChevronLeftIcon className="w-4 h-4 text-gray-400 rotate-90" /> : 
                                                        <ChevronRightIcon className="w-4 h-4 text-gray-400" />
                                                    }
                                                </div>
                                            </button>
                                            
                                            {isExpanded && moduleQuizzes.length > 0 && (
                                                <div className="border-t border-gray-100 bg-gray-50">
                                                    {moduleQuizzes.map((quiz) => {
                                                        const status = getQuizStatus(quiz);
                                                        const quizAttempt = quiz.attempt;
                                                        const hasPassed = quizAttempt?.passed === true;
                                                        const isCompleted = completedQuizzes.has(quiz.id);
                                                        const isActive = currentQuiz?.id === quiz.id;
                                                        
                                                        return (
                                                            <button
                                                                key={quiz.id}
                                                                onClick={() => handleSelectQuiz(quiz)}
                                                                disabled={status === 'locked-module' || status === 'locked-lessons' || hasPassed}
                                                                className={`w-full px-4 py-3 text-left border-b border-gray-100 last:border-b-0 transition ${
                                                                    status === 'locked-module' || status === 'locked-lessons' || hasPassed
                                                                        ? 'opacity-60 cursor-not-allowed' 
                                                                        : 'hover:bg-gray-100 cursor-pointer'
                                                                } ${isActive ? 'bg-blue-100 border-l-4 border-l-blue-600' : ''}`}
                                                            >
                                                                <div className="flex items-center justify-between">
                                                                    <div className="flex items-center gap-2">
                                                                        {status === 'locked-module' ? (
                                                                            <LockClosedIcon className="w-4 h-4 text-gray-500" />
                                                                        ) : status === 'locked-lessons' ? (
                                                                            <LockClosedIcon className="w-4 h-4 text-orange-500" />
                                                                        ) : hasPassed ? (
                                                                            <TrophyIcon className="w-4 h-4 text-yellow-500" />
                                                                        ) : isCompleted ? (
                                                                            <CheckCircleIcon className="w-4 h-4 text-orange-500" />
                                                                        ) : isActive ? (
                                                                            <PlayCircleIcon className="w-4 h-4 text-blue-600 animate-pulse" />
                                                                        ) : (
                                                                            <PlayCircleIcon className="w-4 h-4 text-blue-600" />
                                                                        )}
                                                                        <span className={`text-sm font-medium ${hasPassed ? 'text-yellow-700' : isCompleted ? 'text-gray-500' : 'text-gray-800'}`}>
                                                                            {quiz.title}
                                                                        </span>
                                                                    </div>
                                                                    <span className="text-xs text-gray-500">
                                                                        {quiz.questions_count} Qs
                                                                    </span>
                                                                </div>
                                                                {status === 'locked-module' && (
                                                                    <p className="text-xs text-gray-500 mt-1">
                                                                        Complete previous module first
                                                                    </p>
                                                                )}
                                                                {status === 'locked-lessons' && (
                                                                    <p className="text-xs text-orange-600 mt-1">
                                                                        Complete module lessons first
                                                                    </p>
                                                                )}
                                                                {hasPassed && (
                                                                    <p className="text-xs text-yellow-600 mt-1 flex items-center gap-1">
                                                                        <TrophyIcon className="w-3 h-3" />
                                                                        Passed with {quizAttempt?.score}%
                                                                    </p>
                                                                )}
                                                                {isCompleted && !hasPassed && (
                                                                    <p className="text-xs text-orange-600 mt-1">
                                                                        Failed - Click to retake
                                                                    </p>
                                                                )}
                                                            </button>
                                                        );
                                                    })}
                                                </div>
                                            )}
                                        </div>
                                    );
                                })}
                            </div>
                            
                            {allQuizzesWithStatus.length > 1 && (
                                <div className="flex gap-2 mt-4">
                                    <button
                                        onClick={handlePreviousQuiz}
                                        disabled={currentQuizIndex === 0}
                                        className="flex-1 flex items-center justify-center gap-2 px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <ArrowLeftIcon className="w-4 h-4" />
                                        Previous
                                    </button>
                                    <button
                                        onClick={handleNextQuiz}
                                        disabled={allQuizzesCompleted}
                                        className="flex-1 flex items-center justify-center gap-2 px-3 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        Next
                                        <ArrowRightIcon className="w-4 h-4" />
                                    </button>
                                </div>
                            )}
                            
                            {/* Overall Progress Indicator */}
                            {allQuizzesWithStatus.length > 1 && (
                                <div className="mt-4 pt-4 border-t border-gray-200">
                                    <div className="flex justify-between text-xs text-gray-500 mb-1">
                                        <span>Overall Progress</span>
                                        <span>{completedQuizzes.size}/{allQuizzesWithStatus.length} Quizzes</span>
                                    </div>
                                    <div className="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div 
                                            className="h-full bg-green-500 rounded-full transition-all duration-500"
                                            style={{ width: `${(completedQuizzes.size / allQuizzesWithStatus.length) * 100}%` }}
                                        />
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                    
                    {/* Right Content - Questions OR Results Summary */}
                    <div className="flex-1 p-6">
                        <div className="max-w-3xl mx-auto">
                            {!currentQuiz ? (
                                <div className="bg-white rounded-xl shadow-sm p-12 text-center">
                                    <BookOpenIcon className="w-16 h-16 text-gray-400 mx-auto mb-4" />
                                    <h2 className="text-xl font-semibold text-gray-900 mb-2">Select a Quiz</h2>
                                    <p className="text-gray-500">Choose a quiz from the left sidebar to begin.</p>
                                </div>
                            ) : questions.length === 0 ? (
                                <div className="bg-white rounded-xl shadow-sm p-12 text-center">
                                    <BookOpenIcon className="w-16 h-16 text-gray-400 mx-auto mb-4" />
                                    <h2 className="text-xl font-semibold text-gray-900 mb-2">No Questions</h2>
                                    <p className="text-gray-500">This quiz doesn't have any questions yet.</p>
                                </div>
                            ) : (
                                <>
                                    {/* Check if user has already passed this quiz */}
                                    {currentQuiz?.attempt?.passed ? (
                                        // Show PASSED summary view
                                        <div className="space-y-6">
                                            <div className="bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-xl p-6 text-center">
                                                <div className="w-16 h-16 bg-gradient-to-br from-yellow-400 to-amber-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                                    <TrophyIcon className="w-8 h-8 text-white" />
                                                </div>
                                                <h2 className="text-2xl font-bold text-gray-900 mb-2">
                                                    Quiz Already Passed! 🎉
                                                </h2>
                                                <p className="text-gray-600 mb-4">
                                                    You've successfully completed this quiz.
                                                </p>
                                                
                                                <div className="bg-white rounded-lg p-4 mb-4 inline-block mx-auto">
                                                    <p className="text-sm text-gray-500">Your Score</p>
                                                    <p className="text-4xl font-bold text-green-600">
                                                        {currentQuiz.attempt.score}%
                                                    </p>
                                                    <p className="text-xs text-gray-500">
                                                        Completed on {currentQuiz.attempt.completed_at}
                                                    </p>
                                                </div>
                                                
                                                <div className="flex gap-3 justify-center">
                                                    <button
                                                        onClick={() => window.location.href = route('dashboard.quiz.results', {
                                                            course: course.slug,
                                                            assessment: currentQuiz.id
                                                        })}
                                                        className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                                    >
                                                        View Detailed Results
                                                    </button>
                                                    
                                                    {currentQuizIndex < allQuizzesWithStatus.length - 1 && (
                                                        <button
                                                            onClick={handleNextQuiz}
                                                            className="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2"
                                                        >
                                                            Next Quiz
                                                            <ArrowRightIcon className="w-4 h-4" />
                                                        </button>
                                                    )}
                                                </div>
                                            </div>
                                            
                                            <div className="bg-white rounded-xl shadow-sm p-6">
                                                <h3 className="font-semibold text-gray-900 mb-4">Quiz Summary</h3>
                                                <div className="grid grid-cols-3 gap-4 text-center">
                                                    <div>
                                                        <p className="text-2xl font-bold text-gray-900">{currentQuiz.questions_count}</p>
                                                        <p className="text-sm text-gray-500">Questions</p>
                                                    </div>
                                                    <div>
                                                        <p className="text-2xl font-bold text-green-600">{currentQuiz.attempt.score}%</p>
                                                        <p className="text-sm text-gray-500">Score</p>
                                                    </div>
                                                    <div>
                                                        <p className="text-2xl font-bold text-gray-900">{currentQuiz.passing_score}%</p>
                                                        <p className="text-sm text-gray-500">Passing Score</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ) : isCurrentQuizCompleted && !currentQuiz?.attempt?.passed ? (
                                        // Show FAILED summary view
                                        <div className="space-y-6">
                                            <div className="bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-xl p-6 text-center">
                                                <div className="w-16 h-16 bg-gradient-to-br from-red-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                                    <XCircleIcon className="w-8 h-8 text-white" />
                                                </div>
                                                <h2 className="text-2xl font-bold text-gray-900 mb-2">
                                                    Quiz Not Passed
                                                </h2>
                                                <p className="text-gray-600 mb-4">
                                                    You didn't reach the passing score. You can retake this quiz.
                                                </p>
                                                
                                                <div className="bg-white rounded-lg p-4 mb-4 inline-block mx-auto">
                                                    <p className="text-sm text-gray-500">Your Score</p>
                                                    <p className="text-4xl font-bold text-red-600">
                                                        {currentQuiz.attempt?.score || 0}%
                                                    </p>
                                                    <p className="text-xs text-gray-500">
                                                        Passing: {currentQuiz.passing_score}%
                                                    </p>
                                                </div>
                                                
                                                <div className="flex gap-3 justify-center">
                                                    <button
                                                        onClick={() => window.location.href = route('dashboard.quiz.results', {
                                                            course: course.slug,
                                                            assessment: currentQuiz.id
                                                        })}
                                                        className="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition"
                                                    >
                                                        View Results
                                                    </button>
                                                    
                                                    <button
                                                        onClick={() => {
                                                            setAnswers({});
                                                            setCurrentQuestionIndex(0);
                                                            setFlaggedQuestions(new Set());
                                                            setHasUnsavedChanges(false);
                                                            setCompletedQuizzes(prev => {
                                                                const newSet = new Set(prev);
                                                                newSet.delete(currentQuiz.id);
                                                                return newSet;
                                                            });
                                                        }}
                                                        className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                                    >
                                                        Retake Quiz
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    ) : (
                                        // Show ACTIVE quiz questions
                                        <>
                                            <div className="bg-white rounded-xl shadow-sm p-6 mb-6">
                                                <div className="flex items-start justify-between mb-4">
                                                    <div>
                                                        <span className="text-sm text-gray-500 mb-1 block">
                                                            Question {currentQuestionIndex + 1} of {questions.length}
                                                        </span>
                                                        <h2 className="text-lg font-medium text-gray-900">
                                                            {currentQuestion?.text}
                                                        </h2>
                                                    </div>
                                                    {!isCurrentQuizCompleted && (
                                                        <button 
                                                            onClick={() => toggleFlag(currentQuestion?.id)}
                                                            className={`p-2 rounded-lg transition ${
                                                                flaggedQuestions.has(currentQuestion?.id) 
                                                                    ? 'text-amber-500 bg-amber-50' 
                                                                    : 'text-gray-400 hover:text-amber-500 hover:bg-gray-50'
                                                            }`}
                                                            title="Flag for review"
                                                        >
                                                            <FlagIcon className="w-5 h-5" />
                                                        </button>
                                                    )}
                                                </div>
                                                
                                                <div className="space-y-3">
                                                    {currentQuestion?.options?.map((option, index) => (
                                                        <label key={index} className={`flex items-center p-4 border-2 rounded-lg transition ${
                                                            isCurrentQuizCompleted 
                                                                ? 'cursor-not-allowed opacity-80'
                                                                : 'cursor-pointer hover:bg-gray-50'
                                                        } ${
                                                            answers[currentQuestion?.id] === option 
                                                                ? 'border-blue-500 bg-blue-50' 
                                                                : 'border-gray-200'
                                                        }`}>
                                                            <input
                                                                type="radio"
                                                                name={`q-${currentQuestion?.id}`}
                                                                value={option}
                                                                checked={answers[currentQuestion?.id] === option}
                                                                onChange={() => handleAnswer(currentQuestion?.id, option)}
                                                                disabled={isCurrentQuizCompleted}
                                                                className="w-4 h-4 text-blue-600 disabled:opacity-50"
                                                            />
                                                            <span className="ml-3 text-gray-700">{option}</span>
                                                        </label>
                                                    ))}
                                                </div>
                                            </div>
                                            
                                            <div className="flex items-center justify-between">
                                                <button
                                                    onClick={handlePrevious}
                                                    disabled={currentQuestionIndex === 0}
                                                    className="flex items-center gap-2 px-4 py-2 text-gray-600 disabled:opacity-50 hover:bg-gray-100 rounded-lg"
                                                >
                                                    <ChevronLeftIcon className="w-4 h-4" />
                                                    Previous
                                                </button>
                                                
                                                <div className="flex gap-3">
                                                    {currentQuestionIndex < questions.length - 1 ? (
                                                        <button
                                                            onClick={handleNext}
                                                            className="flex items-center gap-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md"
                                                        >
                                                            Next
                                                            <ChevronRightIcon className="w-4 h-4" />
                                                        </button>
                                                    ) : (
                                                        <>
                                                            {!isCurrentQuizCompleted && (
                                                                <>
                                                                    {!isOnlyQuiz && !isLastQuiz ? (
                                                                        <button
                                                                            onClick={handleSubmitAndContinue}
                                                                            disabled={isSubmitting}
                                                                            className="flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 disabled:opacity-50 shadow-md transition"
                                                                        >
                                                                            {isSubmitting ? (
                                                                                <>
                                                                                    <div className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                                                                    Submitting...
                                                                                </>
                                                                            ) : (
                                                                                <>
                                                                                    <CheckCircleIcon className="w-4 h-4" />
                                                                                    Complete & Continue
                                                                                </>
                                                                            )}
                                                                        </button>
                                                                    ) : (
                                                                        <button
                                                                            onClick={handleSubmitAndFinish}
                                                                            disabled={isSubmitting}
                                                                            className="flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 disabled:opacity-50 shadow-md transition"
                                                                        >
                                                                            {isSubmitting ? (
                                                                                <>
                                                                                    <div className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                                                                    Submitting...
                                                                                </>
                                                                            ) : (
                                                                                <>
                                                                                    <SparklesIcon className="w-4 h-4" />
                                                                                    Submit & View Results
                                                                                </>
                                                                            )}
                                                                        </button>
                                                                    )}
                                                                </>
                                                            )}
                                                        </>
                                                    )}
                                                </div>
                                            </div>
                                        </>
                                    )}
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}