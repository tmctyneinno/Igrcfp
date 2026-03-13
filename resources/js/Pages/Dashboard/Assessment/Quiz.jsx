import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { motion } from 'framer-motion';
import {
    CheckCircleIcon,
    XCircleIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    DocumentTextIcon,
    ExclamationTriangleIcon
} from '@heroicons/react/24/outline';

export default function QuizReview({ enrollment, assessment, submission, questions = [] }) {
    const [currentQuestion, setCurrentQuestion] = useState(0);
    // DEBUG: Log the received data
    console.log('=== QUIZ REVIEW DEBUG ===');
    console.log('Questions:', questions);
    console.log('Questions length:', questions?.length);
    console.log('Assessment:', assessment);
    console.log('Submission:', submission);
    console.log('Enrollment:', enrollment);
    
    // Safely check if questions exist
    if (!questions || questions.length === 0) {
        return (
            <AuthenticatedLayout>
                <Head title="Review Error" />
                <div className="min-h-screen bg-gray-50 py-12">
                    <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="bg-white rounded-xl shadow-sm p-8 text-center">
                            <ExclamationTriangleIcon className="w-16 h-16 text-yellow-500 mx-auto mb-4" />
                            <h2 className="text-2xl font-bold text-gray-900 mb-2">No Questions Found</h2>
                            <p className="text-gray-600 mb-6">Unable to load the review questions.</p>
                            <Link
                                href={`/courses/${enrollment?.course?.slug || '#'}`}
                                className="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                            >
                                <ChevronLeftIcon className="w-5 h-5" />
                                Back to Course
                            </Link>
                        </div>
                    </div>
                </div>
            </AuthenticatedLayout>
        );
    }

    const question = questions[currentQuestion] || questions[0];
    const formatTime = (seconds) => {
        if (!seconds) return 'N/A';
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins} min ${secs} sec`;
    };

    // Format answer for display
    const formatAnswer = (answer) => {
        if (!answer) return 'No answer provided';
        if (typeof answer === 'string') {
            return answer.charAt(0).toUpperCase() + answer.slice(1);
        }
        return String(answer);
    };

    return (
        <AuthenticatedLayout>
            <Head title={`${assessment?.title || 'Quiz'} | Review`} />

            <div className="min-h-screen bg-gray-50 py-12">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="mb-6">
                        <Link
                            href={`/courses/${enrollment?.course?.slug || '#'}`}
                            className="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition mb-4"
                        >
                            <ChevronLeftIcon className="w-5 h-5" />
                            Back to Course
                        </Link>
                        
                        <div className="bg-white rounded-xl shadow-sm p-6">
                            <h1 className="text-2xl font-bold text-gray-900 mb-2">
                                {assessment?.title || 'Quiz'} - Results
                            </h1>
                            <p className="text-gray-600 mb-6">{enrollment?.course?.title || 'Course'}</p>
                            
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div className={`rounded-lg p-4 text-center ${
                                    submission?.score >= assessment?.passing_score 
                                        ? 'bg-green-50' 
                                        : 'bg-red-50'
                                }`}>
                                    <div className={`text-3xl font-bold ${
                                        submission?.score >= assessment?.passing_score 
                                            ? 'text-green-700' 
                                            : 'text-red-700'
                                    }`}>
                                        {submission?.score || 0}%
                                    </div>
                                    <div className="text-sm text-gray-600">Your Score</div>
                                </div>
                                
                                <div className="bg-indigo-50 rounded-lg p-4 text-center">
                                    <div className="text-3xl font-bold text-indigo-700">
                                        {assessment?.passing_score || 0}%
                                    </div>
                                    <div className="text-sm text-indigo-600">Passing Score</div>
                                </div>
                                
                                <div className={`rounded-lg p-4 text-center ${
                                    submission?.passed ? 'bg-green-50' : 'bg-red-50'
                                }`}>
                                    <div className={`text-3xl font-bold ${
                                        submission?.passed ? 'text-green-700' : 'text-red-700'
                                    }`}>
                                        {submission?.passed ? 'Passed' : 'Failed'}
                                    </div>
                                    <div className="text-sm text-gray-600">Status</div>
                                </div>
                            </div>
                            
                            {submission?.submitted_at && (
                                <div className="mt-4 text-sm text-gray-500">
                                    Submitted: {new Date(submission.submitted_at).toLocaleString()} • 
                                    Time spent: {formatTime(submission.time_spent)}
                                </div>
                            )}
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
                                            ${q?.is_correct 
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
                            {question && (
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
                                            {question.points || 0} points
                                        </span>
                                    </div>

                                    <p className="text-lg text-gray-900 mb-6">{question.text || ''}</p>

                                    {/* Answer Display */}
                                    <div className="space-y-4">
                                        {question.type === 'multiple_choice' && (
                                            <div>
                                                <p className="text-sm font-medium text-gray-700 mb-2">Your Answer:</p>
                                                <div className={`p-3 rounded-lg ${
                                                    question.is_correct ? 'bg-green-50' : 'bg-red-50'
                                                }`}>
                                                    {formatAnswer(question.user_answer)}
                                                </div>
                                                
                                                {!question.is_correct && question.correct_answer && (
                                                    <>
                                                        <p className="text-sm font-medium text-gray-700 mt-4 mb-2">Correct Answer:</p>
                                                        <div className="p-3 bg-green-50 rounded-lg">
                                                            {formatAnswer(question.correct_answer)}
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
                                                    {formatAnswer(question.user_answer)}
                                                </div>
                                                
                                                {!question.is_correct && question.correct_answer && (
                                                    <>
                                                        <p className="text-sm font-medium text-gray-700 mt-4 mb-2">Correct Answer:</p>
                                                        <div className="p-3 bg-green-50 rounded-lg">
                                                            {formatAnswer(question.correct_answer)}
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
                                                    {formatAnswer(question.user_answer)}
                                                </div>
                                                
                                                {!question.is_correct && question.correct_answer && (
                                                    <>
                                                        <p className="text-sm font-medium text-gray-700 mt-4 mb-2">Expected Answer:</p>
                                                        <div className="p-3 bg-green-50 rounded-lg">
                                                            {formatAnswer(question.correct_answer)}
                                                        </div>
                                                    </>
                                                )}
                                            </div>
                                        )}

                                        {question.type === 'essay' && (
                                            <div>
                                                <p className="text-sm font-medium text-gray-700 mb-2">Your Essay:</p>
                                                <div className="p-4 bg-gray-50 rounded-lg whitespace-pre-wrap">
                                                    {question.user_answer || 'No answer provided'}
                                                </div>
                                                {question.feedback && (
                                                    <>
                                                        <p className="text-sm font-medium text-gray-700 mt-4 mb-2">Instructor Feedback:</p>
                                                        <div className="p-4 bg-blue-50 rounded-lg">
                                                            {question.feedback}
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
                                                    {question.is_correct ? question.points || 0 : 0}/{question.points || 0} points
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
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}