// resources/js/Pages/Assessment/QuizReview.jsx

import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { motion } from 'framer-motion';
import {
    CheckCircleIcon,
    XCircleIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    DocumentTextIcon
} from '@heroicons/react/24/outline';

export default function QuizReview({ enrollment, assessment, submission, questions }) {
    const [currentQuestion, setCurrentQuestion] = useState(0);
    const question = questions[currentQuestion];

    const formatTime = (seconds) => {
        if (!seconds) return 'N/A';
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins} min ${secs} sec`;
    };

    return (
        <AuthenticatedLayout>
            <Head title={`${assessment.title} | Review`} />

            <div className="min-h-screen bg-gray-50 py-12">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="mb-6">
                        <Link
                            href={`/courses/${enrollment.course.slug}`}
                            className="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition mb-4"
                        >
                            <ChevronLeftIcon className="w-5 h-5" />
                            Back to Course
                        </Link>
                        
                        <div className="bg-white rounded-xl shadow-sm p-6">
                            <h1 className="text-2xl font-bold text-gray-900 mb-2">{assessment.title} - Results</h1>
                            <p className="text-gray-600 mb-6">{enrollment.course.title}</p>
                            
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div className="bg-green-50 rounded-lg p-4 text-center">
                                    <div className="text-3xl font-bold text-green-700">{submission.score}%</div>
                                    <div className="text-sm text-green-600">Your Score</div>
                                </div>
                                
                                <div className="bg-indigo-50 rounded-lg p-4 text-center">
                                    <div className="text-3xl font-bold text-indigo-700">{assessment.passing_score}%</div>
                                    <div className="text-sm text-indigo-600">Passing Score</div>
                                </div>
                                
                                <div className="bg-purple-50 rounded-lg p-4 text-center">
                                    <div className="text-3xl font-bold text-purple-700">
                                        {submission.passed ? 'Passed' : 'Failed'}
                                    </div>
                                    <div className="text-sm text-purple-600">Status</div>
                                </div>
                            </div>
                            
                            <div className="mt-4 text-sm text-gray-500">
                                Submitted: {new Date(submission.submitted_at).toLocaleString()} • 
                                Time spent: {formatTime(submission.time_spent)}
                            </div>
                        </div>
                    </div>

                    {/* Question Review */}
                    <div className="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div className="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h2 className="text-lg font-semibold text-gray-900">Question Review</h2>
                        </div>

                        <div className="p-6">
                            {/* Question Navigation */}
                            <div className="flex flex-wrap gap-2 mb-6">
                                {questions.map((q, index) => (
                                    <button
                                        key={index}
                                        onClick={() => setCurrentQuestion(index)}
                                        className={`
                                            w-10 h-10 rounded-lg text-sm font-medium transition
                                            ${currentQuestion === index ? 'ring-2 ring-indigo-600 ring-offset-2' : ''}
                                            ${q.is_correct 
                                                ? 'bg-green-100 text-green-700 hover:bg-green-200' 
                                                : 'bg-red-100 text-red-700 hover:bg-red-200'
                                            }
                                        `}
                                    >
                                        {index + 1}
                                    </button>
                                ))}
                            </div>

                            {/* Current Question */}
                            <motion.div
                                key={currentQuestion}
                                initial={{ opacity: 0, y: 10 }}
                                animate={{ opacity: 1, y: 0 }}
                            >
                                <div className="mb-4 flex items-center gap-3">
                                    <span className="text-sm font-medium text-gray-500">
                                        Question {currentQuestion + 1} of {questions.length}
                                    </span>
                                    <span className="text-sm text-gray-500">
                                        {question.points} points
                                    </span>
                                </div>

                                <p className="text-lg text-gray-900 mb-6">{question.text}</p>

                                {/* Answer Display */}
                                <div className="space-y-4">
                                    {question.type === 'multiple_choice' && question.options && (
                                        <div>
                                            <p className="text-sm font-medium text-gray-700 mb-2">Your Answer:</p>
                                            <div className={`p-3 rounded-lg ${
                                                question.is_correct ? 'bg-green-50' : 'bg-red-50'
                                            }`}>
                                                {question.user_answer || 'No answer provided'}
                                            </div>
                                            
                                            {!question.is_correct && (
                                                <>
                                                    <p className="text-sm font-medium text-gray-700 mt-4 mb-2">Correct Answer:</p>
                                                    <div className="p-3 bg-green-50 rounded-lg">
                                                        {question.correct_answer}
                                                    </div>
                                                </>
                                            )}
                                        </div>
                                    )}

                                    {question.type === 'true_false' && (
                                        <div>
                                            <p className="text-sm font-medium text-gray-700 mb-2">Your Answer:</p>
                                            <div className={`p-3 rounded-lg ${
                                                question.is_correct ? 'bg-green-50' : 'bg-red-50'
                                            }`}>
                                                {question.user_answer ? 
                                                    question.user_answer.charAt(0).toUpperCase() + question.user_answer.slice(1) 
                                                    : 'No answer provided'
                                                }
                                            </div>
                                            
                                            {!question.is_correct && (
                                                <>
                                                    <p className="text-sm font-medium text-gray-700 mt-4 mb-2">Correct Answer:</p>
                                                    <div className="p-3 bg-green-50 rounded-lg">
                                                        {question.correct_answer.charAt(0).toUpperCase() + question.correct_answer.slice(1)}
                                                    </div>
                                                </>
                                            )}
                                        </div>
                                    )}

                                    {question.type === 'short_answer' && (
                                        <div>
                                            <p className="text-sm font-medium text-gray-700 mb-2">Your Answer:</p>
                                            <div className={`p-3 rounded-lg ${
                                                question.is_correct ? 'bg-green-50' : 'bg-red-50'
                                            }`}>
                                                {question.user_answer || 'No answer provided'}
                                            </div>
                                            
                                            {!question.is_correct && (
                                                <>
                                                    <p className="text-sm font-medium text-gray-700 mt-4 mb-2">Expected Answer:</p>
                                                    <div className="p-3 bg-green-50 rounded-lg">
                                                        {question.correct_answer}
                                                    </div>
                                                </>
                                            )}
                                        </div>
                                    )}

                                    <div className="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                                        <div className="flex items-center gap-2">
                                            {question.is_correct ? (
                                                <CheckCircleIcon className="w-5 h-5 text-green-600" />
                                            ) : (
                                                <XCircleIcon className="w-5 h-5 text-red-600" />
                                            )}
                                            <span className="text-sm font-medium">
                                                {question.is_correct ? 'Correct' : 'Incorrect'} • 
                                                {question.is_correct ? question.points : 0}/{question.points} points
                                            </span>
                                        </div>
                                        
                                        <div className="flex gap-2">
                                            <button
                                                onClick={() => setCurrentQuestion(prev => Math.max(0, prev - 1))}
                                                disabled={currentQuestion === 0}
                                                className="p-2 text-gray-600 disabled:opacity-30 hover:text-gray-900 transition"
                                            >
                                                <ChevronLeftIcon className="w-5 h-5" />
                                            </button>
                                            <button
                                                onClick={() => setCurrentQuestion(prev => Math.min(questions.length - 1, prev + 1))}
                                                disabled={currentQuestion === questions.length - 1}
                                                className="p-2 text-gray-600 disabled:opacity-30 hover:text-gray-900 transition"
                                            >
                                                <ChevronRightIcon className="w-5 h-5" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </motion.div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}