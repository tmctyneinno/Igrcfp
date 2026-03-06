// resources/js/Pages/Exam/Show.jsx

import React, { useState, useEffect } from 'react';
import { Head, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { motion } from 'framer-motion';
import toast from 'react-hot-toast';
import {
    CameraIcon,
    ClockIcon,
    AcademicCapIcon,
    CheckCircleIcon,
    ArrowPathIcon
} from '@heroicons/react/24/outline';

export default function ExamShow({ enrollment, exam, time_limit }) {
    const [timeLeft, setTimeLeft] = useState(calculateTimeLeft());
    const [currentQuestion, setCurrentQuestion] = useState(0);
    const [answers, setAnswers] = useState({});
    const [showCamera, setShowCamera] = useState(true);
    const [isSubmitting, setIsSubmitting] = useState(false);

    // Calculate time left
    function calculateTimeLeft() {
        const endTime = new Date(time_limit).getTime();
        const now = new Date().getTime();
        const difference = endTime - now;

        if (difference <= 0) {
            return { hours: 0, minutes: 0, seconds: 0 };
        }

        return {
            hours: Math.floor((difference / (1000 * 60 * 60)) % 24),
            minutes: Math.floor((difference / 1000 / 60) % 60),
            seconds: Math.floor((difference / 1000) % 60)
        };
    }

    // Timer effect
    useEffect(() => {
        const timer = setInterval(() => {
            const newTimeLeft = calculateTimeLeft();
            setTimeLeft(newTimeLeft);

            if (newTimeLeft.hours === 0 && newTimeLeft.minutes === 0 && newTimeLeft.seconds === 0) {
                clearInterval(timer);
                handleAutoSubmit();
            }
        }, 1000);

        return () => clearInterval(timer);
    }, []);

    // Handle answer selection
    const handleAnswer = (questionId, answer) => {
        setAnswers(prev => ({
            ...prev,
            [questionId]: answer
        }));
    };

    // Navigation
    const goToNext = () => {
        if (currentQuestion < exam.questions.length - 1) {
            setCurrentQuestion(prev => prev + 1);
        }
    };

    const goToPrevious = () => {
        if (currentQuestion > 0) {
            setCurrentQuestion(prev => prev - 1);
        }
    };

    // Handle auto-submit when time expires
    const handleAutoSubmit = () => {
        toast.error('Time expired! Submitting exam...');
        handleSubmit(true);
    };

    // Handle exam submission
    const handleSubmit = (autoSubmit = false) => {
        if (!autoSubmit) {
            const confirmed = window.confirm('Are you sure you want to submit your exam?');
            if (!confirmed) return;
        }

        setIsSubmitting(true);
        
        router.post(route('exam.submit', enrollment.id), {
            answers,
            completed_at: new Date().toISOString()
        }, {
            onSuccess: () => {
                toast.success('Exam submitted successfully!');
                router.visit(route('dashboard.courses.show', enrollment.id));
            },
            onError: (errors) => {
                console.error('Submission error:', errors);
                toast.error('Failed to submit exam. Please try again.');
                setIsSubmitting(false);
            }
        });
    };

    const question = exam.questions[currentQuestion];
    const progress = ((currentQuestion + 1) / exam.total_questions) * 100;

    return (
        <AuthenticatedLayout>
            <Head title={`${exam.title} | Exam`} />

            <div className="min-h-screen bg-gray-50">
                {/* Camera Overlay (always visible during exam) */}
                {showCamera && (
                    <div className="fixed top-4 right-4 w-48 h-36 bg-gray-900 rounded-lg overflow-hidden shadow-2xl border-2 border-white z-50">
                        <div className="absolute inset-0 bg-gradient-to-br from-purple-500/20 to-indigo-500/20 flex items-center justify-center">
                            <CameraIcon className="w-12 h-12 text-white/50" />
                        </div>
                        <div className="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full flex items-center gap-1">
                            <div className="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                            <span>LIVE</span>
                        </div>
                        <div className="absolute bottom-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                            Camera Active
                        </div>
                    </div>
                )}

                {/* Header with Timer */}
                <div className="fixed top-0 left-0 right-0 bg-white shadow-lg z-40">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                        <div className="flex items-center justify-between">
                            <div>
                                <h1 className="text-xl font-bold text-gray-900">{exam.title}</h1>
                                <p className="text-sm text-gray-500">
                                    Question {currentQuestion + 1} of {exam.total_questions}
                                </p>
                            </div>
                            <div className={`flex items-center gap-3 px-4 py-2 rounded-lg ${
                                timeLeft.minutes < 5 ? 'bg-red-100 text-red-700 animate-pulse' : 'bg-gray-100'
                            }`}>
                                <ClockIcon className="w-5 h-5" />
                                <span className="font-mono text-xl font-bold">
                                    {String(timeLeft.hours).padStart(2, '0')}:
                                    {String(timeLeft.minutes).padStart(2, '0')}:
                                    {String(timeLeft.seconds).padStart(2, '0')}
                                </span>
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
                <div className="pt-28 pb-12 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    {question && (
                        <motion.div
                            key={currentQuestion}
                            initial={{ opacity: 0, x: 20 }}
                            animate={{ opacity: 1, x: 0 }}
                            exit={{ opacity: 0, x: -20 }}
                            className="bg-white rounded-xl shadow-lg p-8"
                        >
                            <h2 className="text-xl font-bold text-gray-900 mb-6">
                                {question.text}
                            </h2>

                            <div className="space-y-3">
                                {question.options?.map((option, index) => (
                                    <label
                                        key={index}
                                        className={`flex items-center p-4 border-2 rounded-lg cursor-pointer transition ${
                                            answers[question.id] === option
                                                ? 'border-indigo-600 bg-indigo-50'
                                                : 'border-gray-200 hover:border-indigo-300'
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

                            <div className="flex justify-between mt-8">
                                <button
                                    onClick={goToPrevious}
                                    disabled={currentQuestion === 0}
                                    className="px-6 py-2 text-gray-600 disabled:opacity-50 hover:text-gray-900 transition"
                                >
                                    Previous
                                </button>
                                
                                {currentQuestion === exam.total_questions - 1 ? (
                                    <button
                                        onClick={() => handleSubmit()}
                                        disabled={isSubmitting}
                                        className="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition disabled:opacity-50 flex items-center gap-2"
                                    >
                                        {isSubmitting ? (
                                            <>
                                                <ArrowPathIcon className="w-4 h-4 animate-spin" />
                                                Submitting...
                                            </>
                                        ) : (
                                            <>
                                                <CheckCircleIcon className="w-4 h-4" />
                                                Submit Exam
                                            </>
                                        )}
                                    </button>
                                ) : (
                                    <button
                                        onClick={goToNext}
                                        className="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                                    >
                                        Next
                                    </button>
                                )}
                            </div>
                        </motion.div>
                    )}

                    {/* Security Notice */}
                    <div className="mt-6 bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
                        <div className="flex items-center gap-2">
                            <CameraIcon className="w-5 h-5 text-indigo-600" />
                            <span className="font-medium">Identity Protection Active</span>
                        </div>
                        <p className="mt-1 text-xs">
                            Your camera is active during this exam for identity verification purposes. 
                            All recordings are securely stored and monitored.
                        </p>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}