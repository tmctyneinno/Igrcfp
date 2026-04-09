import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { CheckCircleIcon, XCircleIcon, TrophyIcon } from '@heroicons/react/24/outline';

export default function Results({ course, assessment, attempt, questions = [] }) {
    
    const handleRetake = () => {
        router.visit(route('dashboard.quiz.take', { 
            course: course.slug, 
            assessment: assessment.id 
        }));
    };
    
    const handleBackToCourse = () => {
        router.visit(route('dashboard.courses.show', course.slug));
    };
    
    return (
        <AuthenticatedLayout>
            <Head title={`${assessment.title} | Results`} />
            
            <div className="min-h-screen bg-gray-50 py-12">
                <div className="max-w-4xl mx-auto px-4">
                    {/* Score Card */}
                    <div className="bg-white rounded-xl shadow-sm p-6 mb-6 text-center">
                        {attempt.passed ? (
                            <TrophyIcon className="w-16 h-16 text-yellow-500 mx-auto mb-4" />
                        ) : (
                            <XCircleIcon className="w-16 h-16 text-red-500 mx-auto mb-4" />
                        )}
                        
                        <h1 className="text-2xl font-bold text-gray-900 mb-2">
                            {assessment.title}
                        </h1>
                        
                        <div className="text-5xl font-bold mb-2" style={{ color: attempt.passed ? '#059669' : '#DC2626' }}>
                            {attempt.score}%
                        </div>
                        
                        <p className="text-gray-600 mb-4">
                            {attempt.passed ? 'Congratulations! You passed!' : 'You did not pass. Keep learning!'}
                        </p>
                        
                        <div className="flex justify-center gap-4 text-sm text-gray-500">
                            <span>✅ {attempt.correct_answers} correct</span>
                            <span>📊 {attempt.earned_marks}/{attempt.total_marks} points</span>
                            <span>🎯 Passing: {assessment.passing_score}%</span>
                        </div>
                    </div>
                    
                    {/* Questions Review */}
                    <div className="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <h2 className="text-lg font-semibold text-gray-900 mb-4">Question Review</h2>
                        <div className="space-y-4">
                            {questions.map((q, idx) => (
                                <div key={q.id} className={`p-4 rounded-lg border ${
                                    q.is_correct ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50'
                                }`}>
                                    <p className="font-medium text-gray-900 mb-2">
                                        {idx + 1}. {q.text}
                                    </p>
                                    <p className="text-sm mb-1">
                                        Your answer: <span className={q.is_correct ? 'text-green-600' : 'text-red-600'}>
                                            {q.user_answer || 'Not answered'}
                                        </span>
                                    </p>
                                    {!q.is_correct && (
                                        <p className="text-sm text-green-600">
                                            Correct answer: {q.correct_answer}
                                        </p>
                                    )}
                                </div>
                            ))}
                        </div>
                    </div>
                    
                    {/* Actions */}
                    <div className="flex gap-4">
                        <button
                            onClick={handleBackToCourse}
                            className="flex-1 text-center py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition"
                        >
                            Back to Course
                        </button>
                        
                        {!attempt.passed && (
                            <button
                                onClick={handleRetake}
                                className="flex-1 text-center py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                            >
                                Retake Quiz
                            </button>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}