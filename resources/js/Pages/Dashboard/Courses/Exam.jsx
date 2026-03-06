// resources/js/Pages/Dashboard/Courses/Exam.jsx

import React, { useState, useEffect } from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { motion } from 'framer-motion';
import { 
    CameraIcon, 
    ClockIcon, 
    CheckCircleIcon, 
    ArrowLeftIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    ShieldCheckIcon,
    ExclamationTriangleIcon
} from '@heroicons/react/24/outline';

export default function Exam() {
    const [currentQuestion, setCurrentQuestion] = useState(0);
    const [selectedAnswers, setSelectedAnswers] = useState({});
    const [timeLeft, setTimeLeft] = useState({
        hours: 1,
        minutes: 30,
        seconds: 0
    });
    const [showCamera, setShowCamera] = useState(true);

    // Mock exam data for UI design
    const examData = {
        title: "Certified GRC Professional Final Exam",
        totalQuestions: 25,
        duration: "90 minutes",
        questions: [
            {
                id: 1,
                text: "Which of the following best describes the primary purpose of a Governance, Risk, and Compliance (GRC) framework?",
                options: [
                    "To maximize profits by minimizing operational costs",
                    "To integrate governance, risk management, and compliance processes",
                    "To replace all existing internal controls with automated systems",
                    "To eliminate all regulatory requirements for the organization"
                ]
            },
            {
                id: 2,
                text: "In the context of risk management, what does the acronym RCSA stand for?",
                options: [
                    "Risk Control Self-Assessment",
                    "Regulatory Compliance Standard Audit",
                    "Risk Calculation and Statistical Analysis",
                    "Reporting Control Systems Architecture"
                ]
            },
            {
                id: 3,
                text: "Which regulatory body is primarily responsible for enforcing anti-money laundering (AML) compliance in the United States?",
                options: [
                    "Securities and Exchange Commission (SEC)",
                    "Federal Reserve Board (FRB)",
                    "Financial Crimes Enforcement Network (FinCEN)",
                    "Office of the Comptroller of the Currency (OCC)"
                ]
            }
        ]
    };

    // Timer effect
    useEffect(() => {
        const timer = setInterval(() => {
            setTimeLeft(prev => {
                if (prev.seconds > 0) {
                    return { ...prev, seconds: prev.seconds - 1 };
                } else if (prev.minutes > 0) {
                    return { ...prev, minutes: prev.minutes - 1, seconds: 59 };
                } else if (prev.hours > 0) {
                    return { hours: prev.hours - 1, minutes: 59, seconds: 59 };
                }
                return prev;
            });
        }, 1000);

        return () => clearInterval(timer);
    }, []);

    const handleAnswerSelect = (questionId, answer) => {
        setSelectedAnswers(prev => ({
            ...prev,
            [questionId]: answer
        }));
    };

    const goToNextQuestion = () => {
        if (currentQuestion < examData.questions.length - 1) {
            setCurrentQuestion(prev => prev + 1);
        }
    };

    const goToPreviousQuestion = () => {
        if (currentQuestion > 0) {
            setCurrentQuestion(prev => prev - 1);
        }
    };

    const currentQ = examData.questions[currentQuestion];
    const progress = ((currentQuestion + 1) / examData.questions.length) * 100;

    return (
        <AuthenticatedLayout>
            <Head title="Exam | IGRCFP" />

            <div className="min-h-screen bg-gray-50">
                {/* Fixed Camera Overlay */}
                {showCamera && (
                    <motion.div 
                        initial={{ opacity: 0, y: -20 }}
                        animate={{ opacity: 1, y: 0 }}
                        className="fixed top-20 right-6 w-64 h-48 bg-gray-900 rounded-xl shadow-2xl border-4 border-white z-50 overflow-hidden"
                    >
                        {/* Camera placeholder */}
                        <div className="absolute inset-0 bg-gradient-to-br from-indigo-900 to-purple-900 flex items-center justify-center">
                            <div className="text-center">
                                <CameraIcon className="w-12 h-12 text-white/50 mx-auto mb-2" />
                                <p className="text-white/70 text-xs">Camera Active</p>
                            </div>
                        </div>
                        
                        {/* Recording indicator */}
                        <div className="absolute top-3 left-3 flex items-center gap-2">
                            <div className="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                            <span className="text-white text-xs font-medium">LIVE</span>
                        </div>
                        
                        {/* Timer on camera */}
                        <div className="absolute bottom-3 right-3 bg-black/50 text-white text-xs px-2 py-1 rounded">
                            {String(timeLeft.hours).padStart(2, '0')}:{String(timeLeft.minutes).padStart(2, '0')}:{String(timeLeft.seconds).padStart(2, '0')}
                        </div>
                    </motion.div>
                )}

                {/* Main Header */}
                <div className="bg-white border-b border-gray-200 sticky top-0 z-40">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                        <div className="flex items-center justify-between">
                            <div className="flex items-center gap-4">
                                <Link 
                                    href={route('dashboard.courses.index')}
                                    className="p-2 hover:bg-gray-100 rounded-lg transition"
                                >
                                    <ArrowLeftIcon className="w-5 h-5 text-gray-600" />
                                </Link>
                                <div>
                                    <h1 className="text-xl font-bold text-gray-900">{examData.title}</h1>
                                    <p className="text-sm text-gray-500">
                                        Question {currentQuestion + 1} of {examData.questions.length}
                                    </p>
                                </div>
                            </div>
                            
                            <div className="flex items-center gap-6">
                                {/* Timer Display */}
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
                                
                                {/* Question Navigator */}
                                <div className="flex items-center gap-2">
                                    <button
                                        onClick={goToPreviousQuestion}
                                        disabled={currentQuestion === 0}
                                        className="p-2 hover:bg-gray-100 rounded-lg transition disabled:opacity-30"
                                    >
                                        <ChevronLeftIcon className="w-5 h-5" />
                                    </button>
                                    <span className="text-sm font-medium">
                                        {currentQuestion + 1}/{examData.questions.length}
                                    </span>
                                    <button
                                        onClick={goToNextQuestion}
                                        disabled={currentQuestion === examData.questions.length - 1}
                                        className="p-2 hover:bg-gray-100 rounded-lg transition disabled:opacity-30"
                                    >
                                        <ChevronRightIcon className="w-5 h-5" />
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        {/* Progress Bar */}
                        <div className="mt-4 h-2 bg-gray-200 rounded-full">
                            <motion.div 
                                initial={{ width: 0 }}
                                animate={{ width: `${progress}%` }}
                                className="h-2 bg-indigo-600 rounded-full"
                            ></motion.div>
                        </div>
                    </div>
                </div>

                {/* Main Content */}
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    {/* Question Card */}
                    <motion.div
                        key={currentQuestion}
                        initial={{ opacity: 0, x: 20 }}
                        animate={{ opacity: 1, x: 0 }}
                        exit={{ opacity: 0, x: -20 }}
                        className="bg-white rounded-2xl shadow-xl overflow-hidden"
                    >
                        {/* Question Header */}
                        <div className="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6">
                            <span className="inline-block px-3 py-1 bg-white/20 text-white text-sm rounded-full mb-3">
                                Question {currentQuestion + 1}
                            </span>
                            <h2 className="text-xl font-semibold text-white">
                                {currentQ?.text}
                            </h2>
                        </div>

                        {/* Options */}
                        <div className="p-8">
                            <div className="space-y-4">
                                {currentQ?.options.map((option, index) => (
                                    <motion.label
                                        key={index}
                                        initial={{ opacity: 0, y: 10 }}
                                        animate={{ opacity: 1, y: 0 }}
                                        transition={{ delay: index * 0.1 }}
                                        className={`block p-5 border-2 rounded-xl cursor-pointer transition ${
                                            selectedAnswers[currentQ.id] === option
                                                ? 'border-indigo-600 bg-indigo-50'
                                                : 'border-gray-200 hover:border-indigo-300 hover:bg-gray-50'
                                        }`}
                                    >
                                        <div className="flex items-start">
                                            <div className="flex-shrink-0 mt-0.5">
                                                <input
                                                    type="radio"
                                                    name={`question-${currentQ.id}`}
                                                    value={option}
                                                    checked={selectedAnswers[currentQ.id] === option}
                                                    onChange={() => handleAnswerSelect(currentQ.id, option)}
                                                    className="w-4 h-4 text-indigo-600"
                                                />
                                            </div>
                                            <span className="ml-3 text-gray-700">{option}</span>
                                        </div>
                                    </motion.label>
                                ))}
                            </div>

                            {/* Navigation Buttons */}
                            <div className="flex justify-between mt-8 pt-6 border-t border-gray-200">
                                <button
                                    onClick={goToPreviousQuestion}
                                    disabled={currentQuestion === 0}
                                    className="flex items-center gap-2 px-6 py-3 text-gray-600 disabled:opacity-30 hover:text-gray-900 transition"
                                >
                                    <ChevronLeftIcon className="w-5 h-5" />
                                    Previous
                                </button>
                                
                                {currentQuestion === examData.questions.length - 1 ? (
                                    <button
                                        className="flex items-center gap-2 px-8 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition shadow-lg hover:shadow-xl"
                                    >
                                        <CheckCircleIcon className="w-5 h-5" />
                                        Submit Exam
                                    </button>
                                ) : (
                                    <button
                                        onClick={goToNextQuestion}
                                        className="flex items-center gap-2 px-8 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition shadow-lg hover:shadow-xl"
                                    >
                                        Next
                                        <ChevronRightIcon className="w-5 h-5" />
                                    </button>
                                )}
                            </div>
                        </div>
                    </motion.div>

                    {/* Security Notice */}
                    <motion.div 
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ delay: 0.3 }}
                        className="mt-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-5 border border-indigo-100"
                    >
                        <div className="flex items-start gap-4">
                            <div className="flex-shrink-0">
                                <ShieldCheckIcon className="w-6 h-6 text-indigo-600" />
                            </div>
                            <div className="flex-1">
                                <h4 className="font-semibold text-gray-900 mb-1">Exam Integrity Protection</h4>
                                <p className="text-sm text-gray-600">
                                    Your camera is active for identity verification. All activities are monitored 
                                    for academic integrity. Please ensure you remain visible throughout the exam.
                                </p>
                            </div>
                            <div className="flex-shrink-0">
                                <ExclamationTriangleIcon className="w-5 h-5 text-amber-500" />
                            </div>
                        </div>
                    </motion.div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}