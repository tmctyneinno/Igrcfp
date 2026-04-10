import React, { useState, useEffect } from 'react';
import { Head, Link, router } from '@inertiajs/react';
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
    BookOpenIcon
} from '@heroicons/react/24/outline';

export default function QuizTake({ 
    course, 
    assessment, 
    enrollment, 
    attempt, 
    modules = [],
    timeRemaining: initialTimeRemaining,
    timeLimit 
}) {
    // Flatten all quizzes from all modules
    const allQuizzes = modules.flatMap(module => 
        (module.quizzes || []).map(quiz => ({
            ...quiz,
            moduleId: module.id,
            moduleTitle: module.title,
            moduleNumber: module.module_number
        }))
    );
    
    const [currentQuizIndex, setCurrentQuizIndex] = useState(0);
    const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
    const [answers, setAnswers] = useState({});
    const [timeRemaining, setTimeRemaining] = useState(initialTimeRemaining);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [flaggedQuestions, setFlaggedQuestions] = useState(new Set());
    const [expandedModules, setExpandedModules] = useState({});
    const [selectedQuiz, setSelectedQuiz] = useState(null);
    
    const currentQuiz = allQuizzes[currentQuizIndex];
    const questions = currentQuiz?.questions || [];
    const currentQuestion = questions[currentQuestionIndex];
    const progress = questions.length > 0 
        ? Math.round(((currentQuestionIndex + 1) / questions.length) * 100) 
        : 0;
    
    // Auto-expand first module
    useEffect(() => {
        if (modules.length > 0) {
            const firstModuleId = modules[0].id;
            setExpandedModules(prev => ({ ...prev, [firstModuleId]: true }));
        }
    }, [modules]);
    
    // Timer
    useEffect(() => {
        const timer = setInterval(() => {
            setTimeRemaining(prev => {
                if (prev <= 1) {
                    clearInterval(timer);
                    handleSubmit();
                    return 0;
                }
                return prev - 1;
            });
        }, 1000);
        
        return () => clearInterval(timer);
    }, [currentQuiz]);
    
    // Check if quiz is unlocked (all lessons in module completed)
    const isQuizUnlocked = (quiz) => {
        const module = modules.find(m => m.id === quiz.moduleId);
        if (!module) return false;
        
        // Check if all lessons in module are completed
        const totalLessons = module.lessons?.length || 0;
        const completedLessons = module.lessons?.filter(l => l.completed).length || 0;
        return totalLessons === 0 || completedLessons === totalLessons;
    };
    
    // Toggle module expansion
    const toggleModule = (moduleId) => {
        setExpandedModules(prev => ({ ...prev, [moduleId]: !prev[moduleId] }));
    };
    
    // Navigate to next quiz
    const handleNextQuiz = () => {
        for (let i = currentQuizIndex + 1; i < allQuizzes.length; i++) {
            if (isQuizUnlocked(allQuizzes[i])) {
                setCurrentQuizIndex(i);
                setSelectedQuiz(allQuizzes[i]);
                setCurrentQuestionIndex(0);
                setAnswers({});
                setFlaggedQuestions(new Set());
                return;
            }
        }
        toast.success('You have completed all available quizzes!');
    };
    
    // Navigate to previous quiz
    const handlePreviousQuiz = () => {
        for (let i = currentQuizIndex - 1; i >= 0; i--) {
            if (isQuizUnlocked(allQuizzes[i])) {
                setCurrentQuizIndex(i);
                setSelectedQuiz(allQuizzes[i]);
                setCurrentQuestionIndex(0);
                setAnswers({});
                setFlaggedQuestions(new Set());
                return;
            }
        }
    };
    
    // Select a specific quiz
    const handleSelectQuiz = (quiz) => {
        const index = allQuizzes.findIndex(q => q.id === quiz.id);
        if (index !== -1 && isQuizUnlocked(quiz)) {
            setCurrentQuizIndex(index);
            setSelectedQuiz(quiz);
            setCurrentQuestionIndex(0);
            setAnswers({});
            setFlaggedQuestions(new Set());
        } else if (!isQuizUnlocked(quiz)) {
            toast.error('Complete all lessons in this module first');
        }
    };
    
    const handleAnswer = (questionId, answer) => {
        setAnswers(prev => ({ ...prev, [questionId]: answer }));
    };
    
    const handleNext = () => {
        if (currentQuestionIndex < questions.length - 1) {
            setCurrentQuestionIndex(prev => prev + 1);
        }
    };
    
    const handlePrevious = () => {
        if (currentQuestionIndex > 0) {
            setCurrentQuestionIndex(prev => prev - 1);
        }
    };
    
    const toggleFlag = (questionId) => {
        setFlaggedQuestions(prev => {
            const newSet = new Set(prev);
            newSet.has(questionId) ? newSet.delete(questionId) : newSet.add(questionId);
            return newSet;
        });
    };
    
    const handleSubmit = () => {
        const unanswered = questions.filter(q => !answers[q.id]).length;
        if (unanswered > 0 && !confirm(`You have ${unanswered} unanswered question(s). Submit anyway?`)) {
            return;
        }
        
        setIsSubmitting(true);
        
        // ✅ Add console log here
        const submitUrl = route('dashboard.quiz.submit', { 
            course: course.slug, 
            assessment: currentQuiz.id 
        });
        console.log('Submit URL:', submitUrl);
        console.log('Course slug:', course.slug);
        console.log('Current Quiz ID:', currentQuiz.id);
        console.log('Answers:', answers);
        
        router.post(submitUrl, { answers }, {
            onFinish: () => setIsSubmitting(false),
            onSuccess: () => {
                toast.success('Quiz submitted successfully!');
                if (currentQuiz) {
                    currentQuiz.status = 'completed';
                }
                setTimeout(() => handleNextQuiz(), 1000);
            },
            onError: (errors) => {
                console.error('Submit error:', errors);
                toast.error('Failed to submit quiz.');
            },
        });
    };
    
    const formatTime = (seconds) => {
        if (isNaN(seconds) || seconds < 0) return '0:00';
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    };
    
    return (
        <AuthenticatedLayout>
            <Head title={`${currentQuiz?.title || 'Quiz'} | Course`} />
            
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
                                    {currentQuiz?.moduleTitle} • Quiz {currentQuizIndex + 1} of {allQuizzes.length}
                                </p>
                            </div>
                            <div className={`flex items-center gap-2 px-4 py-2 rounded-full ${
                                timeRemaining < 60 ? 'bg-red-100 text-red-700' : 
                                timeRemaining < 300 ? 'bg-amber-100 text-amber-700' : 
                                'bg-blue-100 text-blue-700'
                            }`}>
                                <ClockIcon className="w-5 h-5" />
                                <span className="font-mono text-lg font-bold">{formatTime(timeRemaining)}</span>
                            </div>
                        </div>
                    </div>
                    {currentQuiz && (
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
                                    
                                    return (
                                        <div key={module.id} className="border rounded-lg overflow-hidden border-gray-200 bg-white">
                                            <button onClick={() => toggleModule(module.id)} className="w-full px-4 py-3 text-left hover:bg-gray-50">
                                                <div className="flex items-center justify-between">
                                                    <div className="flex-1">
                                                        <div className="flex items-center gap-2 mb-1">
                                                            <span className="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">
                                                                Module {module.module_number || idx + 1}
                                                            </span>
                                                            <span className="text-xs text-gray-500">
                                                                {module.quizzes_count || 0} Quiz{module.quizzes_count !== 1 ? 'zes' : ''}
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
                                            
                                            {/* Quizzes List */}
                                            {isExpanded && module.quizzes && module.quizzes.length > 0 && (
                                                <div className="border-t border-gray-100 bg-gray-50">
                                                    {module.quizzes.map((quiz) => {
                                                        const unlocked = isQuizUnlocked({...quiz, moduleId: module.id});
                                                        const isSelected = selectedQuiz?.id === quiz.id || currentQuiz?.id === quiz.id;
                                                        
                                                        return (
                                                            <button
                                                                key={quiz.id}
                                                                onClick={() => handleSelectQuiz({...quiz, moduleId: module.id})}
                                                                disabled={!unlocked}
                                                                className={`w-full px-4 py-3 text-left border-b border-gray-100 last:border-b-0 transition ${
                                                                    !unlocked 
                                                                        ? 'opacity-60 cursor-not-allowed' 
                                                                        : 'hover:bg-gray-100 cursor-pointer'
                                                                } ${isSelected ? 'bg-blue-100 border-l-4 border-l-blue-600' : ''}`}
                                                            >
                                                                <div className="flex items-center justify-between">
                                                                    <div className="flex items-center gap-2">
                                                                        {!unlocked ? (
                                                                            <LockClosedIcon className="w-4 h-4 text-gray-500" />
                                                                        ) : quiz.status === 'completed' ? (
                                                                            <CheckCircleIcon className="w-4 h-4 text-green-600" />
                                                                        ) : (
                                                                            <PlayCircleIcon className="w-4 h-4 text-blue-600" />
                                                                        )}
                                                                        <span className="text-sm font-medium text-gray-800">
                                                                            {quiz.title}
                                                                        </span>
                                                                    </div>
                                                                    <span className="text-xs text-gray-500">
                                                                        {quiz.questions_count} Qs
                                                                    </span>
                                                                </div>
                                                                {!unlocked && (
                                                                    <p className="text-xs text-orange-600 mt-1">
                                                                        Complete module lessons first
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
                            
                            {/* Quiz Navigation Buttons */}
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
                                    disabled={currentQuizIndex === allQuizzes.length - 1}
                                    className="flex-1 flex items-center justify-center gap-2 px-3 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    Next
                                    <ArrowRightIcon className="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    {/* Right Content - Questions */}
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
                                    {/* Question Card */}
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
                                            <button onClick={() => toggleFlag(currentQuestion?.id)}
                                                className={`p-2 rounded-lg transition ${
                                                    flaggedQuestions.has(currentQuestion?.id) 
                                                        ? 'text-amber-500 bg-amber-50' 
                                                        : 'text-gray-400 hover:text-amber-500 hover:bg-gray-50'
                                                }`}>
                                                <FlagIcon className="w-5 h-5" />
                                            </button>
                                        </div>
                                        
                                        <div className="space-y-3">
                                            {currentQuestion?.options?.map((option, index) => (
                                                <label key={index} className={`flex items-center p-4 border-2 rounded-lg cursor-pointer transition ${
                                                    answers[currentQuestion?.id] === option 
                                                        ? 'border-blue-500 bg-blue-50' 
                                                        : 'border-gray-200 hover:bg-gray-50'
                                                }`}>
                                                    <input
                                                        type="radio"
                                                        name={`q-${currentQuestion?.id}`}
                                                        value={option}
                                                        checked={answers[currentQuestion?.id] === option}
                                                        onChange={() => handleAnswer(currentQuestion?.id, option)}
                                                        className="w-4 h-4 text-blue-600"
                                                    />
                                                    <span className="ml-3 text-gray-700">{option}</span>
                                                </label>
                                            ))}
                                        </div>
                                    </div>
                                    
                                    {/* Navigation */}
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
                                                    className="flex items-center gap-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                                                >
                                                    Next
                                                    <ChevronRightIcon className="w-4 h-4" />
                                                </button>
                                            ) : (
                                                <button
                                                    onClick={handleSubmit}
                                                    disabled={isSubmitting}
                                                    className="flex items-center gap-2 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50"
                                                >
                                                    {isSubmitting ? (
                                                        'Submitting...'
                                                    ) : (
                                                        <>
                                                            <CheckCircleIcon className="w-4 h-4" />
                                                            Submit Quiz
                                                        </>
                                                    )}
                                                </button>
                                            )}
                                        </div>
                                    </div>
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}