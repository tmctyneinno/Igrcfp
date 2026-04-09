import React, { useState, useEffect, useCallback } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import toast from 'react-hot-toast';
import { ClockIcon, ChevronLeftIcon, ChevronRightIcon, FlagIcon, CheckCircleIcon, BookOpenIcon } from '@heroicons/react/24/outline';

export default function QuizTake({ 
    course, 
    assessment, 
    enrollment, 
    attempt, 
    questions = [],
    modules = [],
    timeRemaining: initialTimeRemaining,
    timeLimit 
}) {
    const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
    const [answers, setAnswers] = useState(attempt.answers || {});
    const [timeRemaining, setTimeRemaining] = useState(initialTimeRemaining);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [flaggedQuestions, setFlaggedQuestions] = useState(new Set());
    const [expandedModules, setExpandedModules] = useState({});
    const [activeModule, setActiveModule] = useState(null);
    
    const currentQuestion = questions[currentQuestionIndex];
    const progress = questions.length > 0 ? ((currentQuestionIndex + 1) / questions.length) * 100 : 0;
    
    // Debug log
    useEffect(() => {
        console.log('QuizTake - modules:', modules);
        console.log('QuizTake - questions:', questions);
        console.log('QuizTake - currentQuestion:', currentQuestion);
    }, [modules, questions, currentQuestion]);
    
    // Auto-expand the first module
    useEffect(() => {
        if (modules.length > 0) {
            const firstModuleId = modules[0].id;
            setExpandedModules(prev => ({ ...prev, [firstModuleId]: true }));
            setActiveModule(firstModuleId);
        }
    }, [modules]);
    
    // Find which module the current question belongs to
    useEffect(() => {
        if (currentQuestion && modules.length > 0) {
            const moduleForQuestion = modules.find(m => 
                m.questions?.some(q => q.id === currentQuestion.id)
            );
            if (moduleForQuestion) {
                setActiveModule(moduleForQuestion.id);
                setExpandedModules(prev => ({ ...prev, [moduleForQuestion.id]: true }));
            }
        }
    }, [currentQuestionIndex, currentQuestion, modules]);
    
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
    }, []);
    
    const formatTime = (seconds) => {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    };
    
    const saveProgress = useCallback(() => {
        router.post(route('dashboard.quiz.save', attempt.id), { answers }, { 
            preserveState: true, 
            preserveScroll: true 
        });
    }, [answers, attempt.id]);
    
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
    
    const handleJumpTo = (index) => {
        if (index >= 0 && index < questions.length) {
            setCurrentQuestionIndex(index);
        }
    };
    
    const toggleFlag = (questionId) => {
        setFlaggedQuestions(prev => {
            const newSet = new Set(prev);
            newSet.has(questionId) ? newSet.delete(questionId) : newSet.add(questionId);
            return newSet;
        });
    };
    
    const toggleModule = (moduleId) => {
        setExpandedModules(prev => ({ ...prev, [moduleId]: !prev[moduleId] }));
    };
    
    const handleSubmit = () => {
        const unanswered = questions.filter(q => !answers[q.id]).length;
        if (unanswered > 0 && !confirm(`You have ${unanswered} unanswered question(s). Submit anyway?`)) {
            return;
        }
        
        setIsSubmitting(true);
        router.post(route('dashboard.quiz.submit', { 
            course: course.slug, 
            assessment: assessment.id 
        }), { answers }, {
            onFinish: () => setIsSubmitting(false),
            onError: () => toast.error('Failed to submit quiz.')
        });
    };
    
    const getModuleProgress = (module) => {
        if (!module.questions?.length) return 0;
        const answered = module.questions.filter(q => answers[q.id]).length;
        return Math.round((answered / module.questions.length) * 100);
    };
    
    // If no questions, show message
    if (!questions || questions.length === 0) {
        return (
            <AuthenticatedLayout>
                <Head title={`${assessment.title} | Quiz`} />
                <div className="min-h-screen bg-gray-50 flex items-center justify-center">
                    <div className="text-center p-8 bg-white rounded-xl shadow-sm">
                        <BookOpenIcon className="w-16 h-16 text-gray-400 mx-auto mb-4" />
                        <h2 className="text-xl font-semibold text-gray-900 mb-2">No Questions Available</h2>
                        <p className="text-gray-500 mb-4">This quiz doesn't have any questions yet.</p>
                        <Link href={route('dashboard.courses.show', course.slug)} className="text-blue-600 hover:text-blue-800">
                            ← Back to Course
                        </Link>
                    </div>
                </div>
            </AuthenticatedLayout>
        );
    }
    
    return (
        <AuthenticatedLayout>
            <Head title={`${assessment.title} | Quiz`} />
            <div className="min-h-screen bg-gray-50">
                {/* Header */}
                <div className="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
                    <div className="px-4 py-3">
                        <div className="flex items-center justify-between max-w-[1600px] mx-auto">
                            <div>
                                <Link href={route('dashboard.courses.show', course.slug)} className="text-sm text-gray-500 hover:text-gray-700">
                                    ← Back to Course
                                </Link>
                                <h1 className="text-xl font-bold text-gray-900">{assessment.title}</h1>
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
                    <div className="px-4 pb-3">
                        <div className="max-w-[1600px] mx-auto">
                            <div className="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Overall Progress</span>
                                <span>{Math.round(progress)}%</span>
                            </div>
                            <div className="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div className="h-full bg-blue-600 rounded-full transition-all" style={{ width: `${progress}%` }} />
                            </div>
                        </div>
                    </div>
                </div>
                 
                {/* Main Content */}
                <div className="flex max-w-[1600px] mx-auto">
                    {/* Left Sidebar - Modules */}
                    <div className="w-80 bg-white border-r border-gray-200 min-h-[calc(100vh-120px)] sticky top-[120px] overflow-y-auto">
                        <div className="p-4">
                            <h3 className="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <BookOpenIcon className="w-4 h-4" />
                                Course Modules
                            </h3>
                            
                            {modules.length === 0 ? (
                                <div className="text-center py-8 text-gray-500">
                                    <p>No modules available</p>
                                </div>
                            ) : (
                                <div className="space-y-2">
                                    {modules.map((module, idx) => {
                                        const moduleProgress = getModuleProgress(module);
                                        const isExpanded = expandedModules[module.id] === true;
                                        const isActive = activeModule === module.id;
                                        
                                        return (
                                            <div key={module.id} className={`border rounded-lg overflow-hidden ${
                                                isActive ? 'border-blue-300 bg-blue-50/50' : 'border-gray-200 bg-white'
                                            }`}>
                                                <button onClick={() => toggleModule(module.id)} className="w-full px-4 py-3 text-left hover:bg-gray-50">
                                                    <div className="flex items-center justify-between">
                                                        <div className="flex-1">
                                                            <div className="flex items-center gap-2 mb-1">
                                                                <span className="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">
                                                                    Module {module.module_number || idx + 1}
                                                                </span>
                                                                <span className="text-xs text-gray-500">
                                                                    {module.questions?.length || 0} Qs
                                                                </span>
                                                            </div>
                                                            <p className="text-sm font-medium text-gray-900 line-clamp-1">
                                                                {module.title}
                                                            </p>
                                                        </div>
                                                        {isExpanded ? 
                                                            <ChevronLeftIcon className="w-4 h-4 text-gray-400 rotate-90" /> : 
                                                            <ChevronRightIcon className="w-4 h-4 text-gray-400" />
                                                        }
                                                    </div>
                                                    <div className="mt-2">
                                                        <div className="flex justify-between text-xs text-gray-500 mb-0.5">
                                                            <span>Progress</span><span>{moduleProgress}%</span>
                                                        </div>
                                                        <div className="h-1 bg-gray-200 rounded-full">
                                                            <div className="h-1 bg-green-500 rounded-full" style={{ width: `${moduleProgress}%` }} />
                                                        </div>
                                                    </div>
                                                </button>
                                                
                                                {isExpanded && module.questions && module.questions.length > 0 && (
                                                    <div className="border-t border-gray-100 bg-gray-50 px-3 py-2">
                                                        <div className="flex flex-wrap gap-1.5">
                                                            {module.questions.map((q) => {
                                                                const qIndex = questions.findIndex(ques => ques.id === q.id);
                                                                const isAnswered = !!answers[q.id];
                                                                const isCurrent = currentQuestion?.id === q.id;
                                                                const isFlagged = flaggedQuestions.has(q.id);
                                                                
                                                                if (qIndex === -1) return null;
                                                                
                                                                return (
                                                                    <button key={q.id} onClick={() => handleJumpTo(qIndex)}
                                                                        className={`w-8 h-8 rounded-full text-xs font-medium relative transition ${
                                                                            isCurrent ? 'bg-blue-600 text-white shadow-sm' : 
                                                                            isAnswered ? 'bg-green-100 text-green-700 border border-green-300' : 
                                                                            'bg-white text-gray-600 border border-gray-200 hover:bg-gray-100'
                                                                        }`}>
                                                                        {qIndex + 1}
                                                                        {isFlagged && <span className="absolute -top-1 -right-1 w-2.5 h-2.5 bg-amber-500 rounded-full" />}
                                                                    </button>
                                                                );
                                                            })}
                                                        </div>
                                                    </div>
                                                )}
                                            </div>
                                        );
                                    })}
                                </div>
                            )}
                            
                            <div className="mt-4 p-3 bg-gray-50 rounded-lg text-xs">
                                <p className="font-medium mb-2">Legend</p>
                                <div className="space-y-1">
                                    <div className="flex items-center gap-2"><span className="w-3 h-3 bg-green-100 border border-green-300 rounded-full" /> Answered</div>
                                    <div className="flex items-center gap-2"><span className="w-3 h-3 bg-white border border-gray-200 rounded-full" /> Unanswered</div>
                                    <div className="flex items-center gap-2"><span className="w-3 h-3 bg-amber-500 rounded-full" /> Flagged</div>
                                    <div className="flex items-center gap-2"><span className="w-3 h-3 bg-blue-600 rounded-full" /> Current</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {/* Right - Questions */}
                    <div className="flex-1 p-6">
                        <div className="max-w-3xl mx-auto">
                            {activeModule && (
                                <div className="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <p className="text-sm text-blue-700">
                                        <span className="font-medium">Current Module:</span> {modules.find(m => m.id === activeModule)?.title}
                                    </p>
                                </div>
                            )}
                            
                            <div className="bg-white rounded-xl shadow-sm p-6 mb-6">
                                <div className="flex items-start justify-between mb-4">
                                    <div>
                                        <span className="text-sm text-gray-500 mb-1 block">
                                            Question {currentQuestionIndex + 1} of {questions.length}
                                        </span>
                                        <h2 className="text-lg font-medium text-gray-900">{currentQuestion?.text}</h2>
                                    </div>
                                    <button onClick={() => toggleFlag(currentQuestion?.id)}
                                        className={`p-2 rounded-lg transition ${flaggedQuestions.has(currentQuestion?.id) ? 'text-amber-500 bg-amber-50' : 'text-gray-400 hover:text-amber-500 hover:bg-gray-50'}`}>
                                        <FlagIcon className="w-5 h-5" />
                                    </button>
                                </div>
                                
                                <div className="space-y-3">
                                    {currentQuestion?.options?.map((option, index) => (
                                        <label key={index} className={`flex items-center p-4 border-2 rounded-lg cursor-pointer transition ${
                                            answers[currentQuestion?.id] === option ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:bg-gray-50'
                                        }`}>
                                            <input type="radio" name={`q-${currentQuestion?.id}`} value={option} 
                                                checked={answers[currentQuestion?.id] === option}
                                                onChange={() => handleAnswer(currentQuestion?.id, option)} 
                                                className="w-4 h-4 text-blue-600" />
                                            <span className="ml-3 text-gray-700">{option}</span>
                                        </label>
                                    ))}
                                </div>
                            </div>
                            
                            <div className="flex items-center justify-between">
                                <button onClick={handlePrevious} disabled={currentQuestionIndex === 0} 
                                    className="flex items-center gap-2 px-4 py-2 text-gray-600 disabled:opacity-50 hover:bg-gray-100 rounded-lg transition">
                                    <ChevronLeftIcon className="w-4 h-4" /> Previous
                                </button>
                                
                                <div className="flex gap-3">
                                    {currentQuestionIndex < questions.length - 1 ? (
                                        <button onClick={handleNext} className="flex items-center gap-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                            Next <ChevronRightIcon className="w-4 h-4" />
                                        </button>
                                    ) : (
                                        <button onClick={handleSubmit} disabled={isSubmitting} 
                                            className="flex items-center gap-2 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 transition">
                                            {isSubmitting ? (
                                                <>Submitting...</>
                                            ) : (
                                                <><CheckCircleIcon className="w-4 h-4" /> Submit Quiz</>
                                            )}
                                        </button>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}