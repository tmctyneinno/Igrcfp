// resources/js/Pages/Dashboard/Assessment/ModuleQuiz.jsx

import React, { useState, useEffect, useRef } from 'react';
import { Head, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { motion, AnimatePresence } from 'framer-motion';
import toast from 'react-hot-toast';
import {
    ClockIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    CheckCircleIcon,
    FlagIcon,
    ArrowPathIcon,
    BookOpenIcon
} from '@heroicons/react/24/outline';

export default function ModuleQuiz({ enrollment, module, assessment, attempt }) {
    const [currentQuestion, setCurrentQuestion] = useState(0);
    const [answers, setAnswers] = useState({});
    const [flaggedQuestions, setFlaggedQuestions] = useState([]);
    const [timeLeft, setTimeLeft] = useState(attempt.time_remaining);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const autoSaveTimer = useRef(null);

    const questions = assessment?.questions || [];
    const question = questions[currentQuestion];
    const progress = ((currentQuestion + 1) / questions.length) * 100;

    // Timer effect
    useEffect(() => {
        if (!timeLeft || timeLeft <= 0) {
            if (timeLeft === 0) handleAutoSubmit();
            return;
        }

        const timer = setInterval(() => {
            setTimeLeft(prev => {
                if (prev <= 1) {
                    clearInterval(timer);
                    handleAutoSubmit();
                    return 0;
                }
                return prev - 1;
            });
        }, 1000);

        return () => clearInterval(timer);
    }, []);

    // Auto-save effect
    useEffect(() => {
        if (autoSaveTimer.current) {
            clearTimeout(autoSaveTimer.current);
        }

        autoSaveTimer.current = setTimeout(() => {
            saveProgress();
        }, 3000);

        return () => clearTimeout(autoSaveTimer.current);
    }, [answers, currentQuestion]);

    const saveProgress = () => {
        // router.post(`/assessment/module-quiz/save/${attempt.id}`, {
        //     answers: answers,
        //     last_question: currentQuestion
        // }, {
        //     preserveScroll: true,
        //     onSuccess: () => {
        //         console.log('Progress saved');
        //     }
        // });
    };

    const handleAnswer = (questionId, answer) => {
        setAnswers(prev => ({
            ...prev,
            [questionId]: answer
        }));
    };

    const toggleFlag = () => {
        setFlaggedQuestions(prev => {
            if (prev.includes(currentQuestion)) {
                return prev.filter(q => q !== currentQuestion);
            } else {
                return [...prev, currentQuestion];
            }
        });
    };

    const goToNext = () => {
        if (currentQuestion < questions.length - 1) {
            setCurrentQuestion(prev => prev + 1);
        }
    };

    const goToPrevious = () => {
        if (currentQuestion > 0) {
            setCurrentQuestion(prev => prev - 1);
        }
    };

    const goToQuestion = (index) => {
        setCurrentQuestion(index);
    };

    const handleAutoSubmit = () => {
        toast.error('Time expired! Submitting your quiz...');
        handleSubmit(true);
    };

    const handleSubmit = (autoSubmit = false) => {
        if (!autoSubmit) {
            const unanswered = questions.filter(q => !answers[q.id]).length;
            if (unanswered > 0) {
                if (!confirm(`You have ${unanswered} unanswered questions. Submit anyway?`)) {
                    return;
                }
            }
        }

        setIsSubmitting(true);
        
        // Group answers by quiz_id for submission
        const answersByQuiz = {};
        questions.forEach(q => {
            if (!answersByQuiz[q.quiz_id]) {
                answersByQuiz[q.quiz_id] = {};
            }
            answersByQuiz[q.quiz_id][q.id] = answers[q.id];
        });
        
        router.post(`/assessment/module-quiz/submit/${enrollment.id}/${module.id}`, {
            answers: answersByQuiz,
            quiz_ids: assessment.quiz_ids
        }, {
            onSuccess: () => {
                toast.success('All quizzes submitted successfully!');
            },
            onError: (errors) => {
                setIsSubmitting(false);
                toast.error('Failed to submit quizzes. Please try again.');
                console.error(errors);
            }
        });
    };

    const formatTime = (seconds) => {
        if (!seconds && seconds !== 0) return '--:--';
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    };

    if (!questions.length) {
        return (
            <AuthenticatedLayout>
                <div className="min-h-screen bg-gray-50 py-12">
                    <div className="max-w-4xl mx-auto px-4 text-center">
                        <p>No questions found.</p>
                    </div>
                </div>
            </AuthenticatedLayout>
        );
    }

    return (
        <AuthenticatedLayout>
            <Head title={`Module ${module.number} Quiz`} />

            <div className="min-h-screen bg-gray-50">
                {/* Header */}
                <div className="fixed top-0 left-0 right-0 bg-white border-b border-gray-200 z-30">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                        <div className="flex items-center justify-between">
                            <div className="flex items-center gap-4">
                                <button
                                    onClick={() => {
                                        if (confirm('Are you sure you want to leave? Your progress will be saved.')) {
                                            window.location.href = `/courses/${enrollment.course.slug}`;
                                        }
                                    }}
                                    className="p-2 hover:bg-gray-100 rounded-lg transition"
                                >
                                    <ChevronLeftIcon className="w-5 h-5 text-gray-600" />
                                </button>
                                <div>
                                    <h1 className="text-lg font-semibold text-gray-900">{assessment.title}</h1>
                                    <p className="text-sm text-gray-500">{enrollment.course.title}</p>
                                </div>
                            </div>
                            
                            <div className="flex items-center gap-6">
                                {/* Quiz count badge */}
                                <div className="hidden md:flex items-center gap-2 px-3 py-1 bg-purple-100 text-purple-700 rounded-full">
                                    <BookOpenIcon className="w-4 h-4" />
                                    <span className="text-sm font-medium">{assessment.quiz_count} quizzes</span>
                                </div>

                                {/* Timer */}
                                <div className={`flex items-center gap-2 px-4 py-2 rounded-lg ${
                                    timeLeft && timeLeft < 300 ? 'bg-red-100 text-red-700 animate-pulse' : 'bg-gray-100'
                                }`}>
                                    <ClockIcon className="w-5 h-5" />
                                    <span className="font-mono text-xl font-bold">{formatTime(timeLeft)}</span>
                                </div>

                                {/* Question Navigator */}
                                <div className="hidden md:flex items-center gap-2">
                                    <button
                                        onClick={goToPrevious}
                                        disabled={currentQuestion === 0}
                                        className="p-2 hover:bg-gray-100 rounded-lg transition disabled:opacity-30"
                                    >
                                        <ChevronLeftIcon className="w-5 h-5" />
                                    </button>
                                    <span className="text-sm font-medium">
                                        {currentQuestion + 1}/{questions.length}
                                    </span>
                                    <button
                                        onClick={goToNext}
                                        disabled={currentQuestion === questions.length - 1}
                                        className="p-2 hover:bg-gray-100 rounded-lg transition disabled:opacity-30"
                                    >
                                        <ChevronRightIcon className="w-5 h-5" />
                                    </button>
                                </div>
                            </div>
                        </div>

                        {/* Progress Bar */}
                        <div className="mt-4 h-2 bg-gray-200 rounded-full">
                            <div 
                                className="h-2 bg-indigo-600 rounded-full transition-all duration-300"
                                style={{ width: `${progress}%` }}
                            ></div>
                        </div>
                    </div>
                </div>

                {/* Main Content */}
                <div className="pt-28 pb-12 px-4 max-w-4xl mx-auto">
                    <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
                        {/* Question Navigation Sidebar */}
                        <div className="hidden lg:block col-span-1">
                            <div className="bg-white rounded-xl shadow-sm p-4 sticky top-28">
                                <h3 className="font-medium text-gray-900 mb-3">Questions</h3>
                                <div className="grid grid-cols-4 gap-2">
                                    {questions.map((_, index) => {
                                        const isAnswered = answers[questions[index].id];
                                        const isFlagged = flaggedQuestions.includes(index);
                                        const isCurrent = currentQuestion === index;
                                        
                                        return (
                                            <button
                                                key={index}
                                                onClick={() => goToQuestion(index)}
                                                className={`
                                                    w-10 h-10 rounded-lg font-medium text-sm transition relative
                                                    ${isCurrent ? 'ring-2 ring-indigo-600 ring-offset-2' : ''}
                                                    ${isAnswered 
                                                        ? 'bg-green-100 text-green-700 hover:bg-green-200' 
                                                        : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                                    }
                                                    ${isFlagged ? 'border-2 border-amber-400' : ''}
                                                `}
                                            >
                                                {index + 1}
                                            </button>
                                        );
                                    })}
                                </div>
                                
                                {/* Quiz info */}
                                <div className="mt-4 pt-4 border-t border-gray-200">
                                    <p className="text-xs text-gray-500 mb-2">
                                        Questions from {assessment.quiz_count} quizzes
                                    </p>
                                    <div className="flex items-center gap-2 text-sm">
                                        <div className="w-4 h-4 bg-green-100 rounded"></div>
                                        <span className="text-gray-600">Answered</span>
                                    </div>
                                    <div className="flex items-center gap-2 text-sm mt-2">
                                        <div className="w-4 h-4 bg-gray-100 rounded"></div>
                                        <span className="text-gray-600">Unanswered</span>
                                    </div>
                                    <div className="flex items-center gap-2 text-sm mt-2">
                                        <FlagIcon className="w-4 h-4 text-amber-500" />
                                        <span className="text-gray-600">Flagged</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Question Card */}
                        <div className="lg:col-span-3">
                            <AnimatePresence mode="wait">
                                <motion.div
                                    key={currentQuestion}
                                    initial={{ opacity: 0, x: 20 }}
                                    animate={{ opacity: 1, x: 0 }}
                                    exit={{ opacity: 0, x: -20 }}
                                    className="bg-white rounded-xl shadow-sm overflow-hidden"
                                >
                                    {/* Question Header */}
                                    <div className="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                                        <div className="flex items-center justify-between">
                                            <div className="flex items-center gap-3">
                                                <span className="px-3 py-1 bg-white/20 text-white text-sm rounded-full">
                                                    Question {currentQuestion + 1}
                                                </span>
                                                <span className="text-white/80 text-sm">
                                                    {question.points} {question.points === 1 ? 'point' : 'points'}
                                                </span>
                                            </div>
                                            <button
                                                onClick={toggleFlag}
                                                className={`p-2 rounded-lg transition ${
                                                    flaggedQuestions.includes(currentQuestion)
                                                        ? 'bg-amber-500 text-white'
                                                        : 'bg-white/20 text-white hover:bg-white/30'
                                                }`}
                                            >
                                                <FlagIcon className="w-5 h-5" />
                                            </button>
                                        </div>
                                        {/* Show which quiz this question belongs to */}
                                        {question.quiz_title && (
                                            <p className="text-xs text-white/60 mt-1">
                                                From: {question.quiz_title}
                                            </p>
                                        )}
                                    </div>

                                    {/* Question Content */}
                                    <div className="p-6">
                                        <p className="text-lg text-gray-900 mb-6">{question.text}</p>

                                        {/* Multiple Choice Options */}
                                        {question.type === 'multiple_choice' && question.options && (
                                            <div className="space-y-3">
                                                {question.options.map((option, index) => (
                                                    <label
                                                        key={index}
                                                        className={`flex items-center p-4 border-2 rounded-xl cursor-pointer transition ${
                                                            answers[question.id] === option
                                                                ? 'border-indigo-600 bg-indigo-50'
                                                                : 'border-gray-200 hover:border-indigo-300 hover:bg-gray-50'
                                                        }`}
                                                    >
                                                        <input
                                                            type="radio"
                                                            name={`q-${question.id}`}
                                                            value={option}
                                                            checked={answers[question.id] === option}
                                                            onChange={() => handleAnswer(question.id, option)}
                                                            className="w-4 h-4 text-indigo-600"
                                                        />
                                                        <span className="ml-3 text-gray-700">{option}</span>
                                                    </label>
                                                ))}
                                            </div>
                                        )}

                                        {/* True/False Options */}
                                        {question.type === 'true_false' && (
                                            <div className="flex gap-4">
                                                {['True', 'False'].map((option) => (
                                                    <label
                                                        key={option}
                                                        className={`flex-1 flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition ${
                                                            answers[question.id] === option.toLowerCase()
                                                                ? 'border-indigo-600 bg-indigo-50'
                                                                : 'border-gray-200 hover:border-indigo-300 hover:bg-gray-50'
                                                        }`}
                                                    >
                                                        <input
                                                            type="radio"
                                                            name={`q-${question.id}`}
                                                            value={option.toLowerCase()}
                                                            checked={answers[question.id] === option.toLowerCase()}
                                                            onChange={() => handleAnswer(question.id, option.toLowerCase())}
                                                            className="hidden"
                                                        />
                                                        <span className="text-gray-700 font-medium">{option}</span>
                                                    </label>
                                                ))}
                                            </div>
                                        )}

                                        {/* Short Answer */}
                                        {question.type === 'short_answer' && (
                                            <div>
                                                <textarea
                                                    value={answers[question.id] || ''}
                                                    onChange={(e) => handleAnswer(question.id, e.target.value)}
                                                    placeholder="Type your answer here..."
                                                    rows={4}
                                                    className="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                />
                                            </div>
                                        )}

                                        {/* Essay Answer */}
                                        {question.type === 'essay' && (
                                            <div>
                                                <textarea
                                                    value={answers[question.id] || ''}
                                                    onChange={(e) => handleAnswer(question.id, e.target.value)}
                                                    placeholder="Write your essay response here..."
                                                    rows={8}
                                                    className="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                />
                                            </div>
                                        )}
                                    </div>

                                    {/* Navigation Footer */}
                                    <div className="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                                        <button
                                            onClick={goToPrevious}
                                            disabled={currentQuestion === 0}
                                            className="flex items-center gap-2 px-4 py-2 text-gray-600 disabled:opacity-30 hover:text-gray-900 transition"
                                        >
                                            <ChevronLeftIcon className="w-5 h-5" />
                                            Previous
                                        </button>
                                        
                                        {currentQuestion === questions.length - 1 ? (
                                            <button
                                                onClick={() => handleSubmit()}
                                                disabled={isSubmitting}
                                                className="flex items-center gap-2 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition disabled:opacity-50"
                                            >
                                                {isSubmitting ? (
                                                    <ArrowPathIcon className="w-5 h-5 animate-spin" />
                                                ) : (
                                                    <CheckCircleIcon className="w-5 h-5" />
                                                )}
                                                {isSubmitting ? 'Submitting...' : 'Submit All Quizzes'}
                                            </button>
                                        ) : (
                                            <button
                                                onClick={goToNext}
                                                className="flex items-center gap-2 px-4 py-2 text-indigo-600 hover:text-indigo-800 transition"
                                            >
                                                Next
                                                <ChevronRightIcon className="w-5 h-5" />
                                            </button>
                                        )}
                                    </div>
                                </motion.div>
                            </AnimatePresence>

                            {/* Mobile Navigation */}
                            <div className="lg:hidden mt-4 flex justify-center gap-2">
                                {questions.map((_, index) => (
                                    <button
                                        key={index}
                                        onClick={() => goToQuestion(index)}
                                        className={`
                                            w-8 h-8 rounded-lg text-xs font-medium transition
                                            ${currentQuestion === index ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600'}
                                            ${answers[questions[index].id] ? 'ring-2 ring-green-500' : ''}
                                        `}
                                    >
                                        {index + 1}
                                    </button>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}