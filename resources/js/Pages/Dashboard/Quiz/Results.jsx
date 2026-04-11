import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { 
    CheckCircleIcon, 
    XCircleIcon, 
    TrophyIcon,
    ChevronDownIcon,
    ChevronRightIcon,
    AcademicCapIcon,
    ChartBarIcon
} from '@heroicons/react/24/outline';
import toast from 'react-hot-toast';

export default function Results({ 
    course, 
    currentAssessment, 
    quizzes = [], 
    overallStats = {},
    enrollment 
}) {
    const [expandedQuizzes, setExpandedQuizzes] = useState({});
    const [expandedModules, setExpandedModules] = useState({});
    
    // Group quizzes by module
    const moduleQuizzesMap = {};
    quizzes.forEach(quiz => {
        if (quiz.module) {
            const moduleId = quiz.module.id;
            if (!moduleQuizzesMap[moduleId]) {
                moduleQuizzesMap[moduleId] = {
                    ...quiz.module,
                    quizzes: []
                };
            }
            moduleQuizzesMap[moduleId].quizzes.push(quiz);
        }
    });
    
    const modules = Object.values(moduleQuizzesMap);
    
    const toggleQuiz = (quizId) => {
        setExpandedQuizzes(prev => ({ ...prev, [quizId]: !prev[quizId] }));
    };
    
    const toggleModule = (moduleId) => {
        setExpandedModules(prev => ({ ...prev, [moduleId]: !prev[moduleId] }));
    };
    
    const handleBackToCourse = () => {
        window.location.href = `/dashboard/courses/${course.slug}`;
    };
    
    const handleRetakeQuiz = (quizId) => {
        window.location.href = `/dashboard/courses/${course.slug}/quiz/${quizId}`;
    };
    
    const handleContinueLearning = () => {
        window.location.href = `/dashboard/courses/${course.slug}`;
    };
    
    return (
        <AuthenticatedLayout>
            <Head title={`${course.title} | Results`} />
            
            <div className="min-h-screen bg-gray-50 py-8">
                <div className="max-w-6xl mx-auto px-4">
                    {/* Header */}
                    <div className="mb-6">
                        <Link 
                            href={route('dashboard.courses.show', course.slug)} 
                            className="text-sm text-gray-500 hover:text-gray-700"
                        >
                            ← Back to Course
                        </Link>
                        <h1 className="text-2xl font-bold text-gray-900 mt-2">{course.title}</h1>
                        <p className="text-gray-600">Quiz Results Summary</p>
                    </div>
                    
                    {/* Overall Stats Card */}
                    <div className="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 mb-6 text-white">
                        <div className="flex items-center gap-3 mb-4">
                            <TrophyIcon className="w-8 h-8" />
                            <h2 className="text-xl font-bold">Overall Performance</h2>
                        </div>
                        
                        <div className="grid grid-cols-2 md:grid-cols-5 gap-4">
                            <div className="text-center">
                                <p className="text-3xl font-bold">{overallStats.completed_quizzes}/{overallStats.total_quizzes}</p>
                                <p className="text-sm text-blue-100">Quizzes Completed</p>
                            </div>
                            <div className="text-center">
                                <p className="text-3xl font-bold">{overallStats.passed_quizzes}</p>
                                <p className="text-sm text-blue-100">Quizzes Passed</p>
                            </div>
                            <div className="text-center">
                                <p className="text-3xl font-bold">{overallStats.average_score}%</p>
                                <p className="text-sm text-blue-100">Average Score</p>
                            </div>
                            <div className="text-center">
                                <p className="text-3xl font-bold">{overallStats.total_points}/{overallStats.total_possible}</p>
                                <p className="text-sm text-blue-100">Total Points</p>
                            </div>
                            <div className="text-center">
                                <p className="text-3xl font-bold">{enrollment?.progress || 0}%</p>
                                <p className="text-sm text-blue-100">Course Progress</p>
                            </div>
                        </div>
                    </div>
                    
                    {/* Modules and Quizzes */}
                    <div className="space-y-4">
                        {modules.length > 0 ? (
                            modules.map((module, idx) => {
                                const isModuleExpanded = expandedModules[module.id] !== false;
                                const moduleCompleted = module.quizzes.every(q => q.has_attempt);
                                const modulePassed = module.quizzes.every(q => q.attempt?.passed);
                                
                                return (
                                    <div key={module.id} className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                        {/* Module Header */}
                                        <button
                                            onClick={() => toggleModule(module.id)}
                                            className="w-full px-6 py-4 text-left bg-gray-50 hover:bg-gray-100 transition flex items-center justify-between"
                                        >
                                            <div className="flex items-center gap-3">
                                                <div className={`w-8 h-8 rounded-full flex items-center justify-center ${
                                                    moduleCompleted 
                                                        ? (modulePassed ? 'bg-green-100' : 'bg-yellow-100')
                                                        : 'bg-gray-100'
                                                }`}>
                                                    {moduleCompleted ? (
                                                        modulePassed ? (
                                                            <CheckCircleIcon className="w-5 h-5 text-green-600" />
                                                        ) : (
                                                            <ChartBarIcon className="w-5 h-5 text-yellow-600" />
                                                        )
                                                    ) : (
                                                        <AcademicCapIcon className="w-5 h-5 text-gray-500" />
                                                    )}
                                                </div>
                                                <div>
                                                    <p className="text-sm text-gray-500">Module {module.module_number}</p>
                                                    <h3 className="font-semibold text-gray-900">{module.title}</h3>
                                                </div>
                                            </div>
                                            <div className="flex items-center gap-3">
                                                <span className="text-sm text-gray-500">
                                                    {module.quizzes.filter(q => q.has_attempt).length}/{module.quizzes.length} Quizzes
                                                </span>
                                                {isModuleExpanded ? (
                                                    <ChevronDownIcon className="w-5 h-5 text-gray-400" />
                                                ) : (
                                                    <ChevronRightIcon className="w-5 h-5 text-gray-400" />
                                                )}
                                            </div>
                                        </button>
                                        
                                        {/* Module Quizzes */}
                                        {isModuleExpanded && (
                                            <div className="divide-y divide-gray-100">
                                                {module.quizzes.map((quiz) => {
                                                    const isExpanded = expandedQuizzes[quiz.id] === true;
                                                    const hasAttempt = quiz.has_attempt;
                                                    const attempt = quiz.attempt;
                                                    
                                                    return (
                                                        <div key={quiz.id} className="bg-white">
                                                            {/* Quiz Header */}
                                                            <button
                                                                onClick={() => hasAttempt && toggleQuiz(quiz.id)}
                                                                className={`w-full px-6 py-4 text-left flex items-center justify-between ${
                                                                    hasAttempt ? 'hover:bg-gray-50 cursor-pointer' : 'cursor-default'
                                                                }`}
                                                                disabled={!hasAttempt}
                                                            >
                                                                <div className="flex items-center gap-3">
                                                                    {hasAttempt ? (
                                                                        attempt?.passed ? (
                                                                            <CheckCircleIcon className="w-5 h-5 text-green-600" />
                                                                        ) : (
                                                                            <XCircleIcon className="w-5 h-5 text-red-500" />
                                                                        )
                                                                    ) : (
                                                                        <div className="w-5 h-5 rounded-full border-2 border-gray-300" />
                                                                    )}
                                                                    <div>
                                                                        <p className="font-medium text-gray-900">{quiz.title}</p>
                                                                        <p className="text-sm text-gray-500">
                                                                            {quiz.questions.length} questions • Passing: {quiz.passing_score}%
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div className="flex items-center gap-4">
                                                                    {hasAttempt ? (
                                                                        <>
                                                                            <span className={`text-lg font-bold ${
                                                                                attempt?.passed ? 'text-green-600' : 'text-red-500'
                                                                            }`}>
                                                                                {attempt?.score}%
                                                                            </span>
                                                                            {isExpanded ? (
                                                                                <ChevronDownIcon className="w-5 h-5 text-gray-400" />
                                                                            ) : (
                                                                                <ChevronRightIcon className="w-5 h-5 text-gray-400" />
                                                                            )}
                                                                        </>
                                                                    ) : (
                                                                        <span className="text-sm text-gray-400">Not attempted</span>
                                                                    )}
                                                                </div>
                                                            </button>
                                                            
                                                            {/* Quiz Details */}
                                                            {isExpanded && hasAttempt && (
                                                                <div className="px-6 pb-4 bg-gray-50">
                                                                    <div className="flex items-center justify-between mb-3 pt-2">
                                                                        <div className="flex gap-4 text-sm">
                                                                            <span>✅ {attempt?.correct_answers} correct</span>
                                                                            <span>📊 {attempt?.earned_marks}/{attempt?.total_marks} points</span>
                                                                            <span>⏱️ {attempt?.completed_at}</span>
                                                                        </div>
                                                                        {!attempt?.passed && (
                                                                            <button
                                                                                onClick={() => handleRetakeQuiz(quiz.id)}
                                                                                className="px-4 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700"
                                                                            >
                                                                                Retake Quiz
                                                                            </button>
                                                                        )}
                                                                    </div>
                                                                    
                                                                    {/* Questions Review */}
                                                                    <div className="space-y-2 mt-3">
                                                                        <p className="text-sm font-medium text-gray-700">Question Review:</p>
                                                                        {quiz.questions.map((q, idx) => (
                                                                            <div key={q.id} className={`p-3 rounded-lg text-sm ${
                                                                                q.is_correct 
                                                                                    ? 'bg-green-100 border border-green-200' 
                                                                                    : 'bg-red-100 border border-red-200'
                                                                            }`}>
                                                                                <p className="font-medium mb-1">
                                                                                    {idx + 1}. {q.text}
                                                                                </p>
                                                                                <p>
                                                                                    Your answer: <span className={q.is_correct ? 'text-green-700' : 'text-red-700'}>
                                                                                        {q.user_answer || 'Not answered'}
                                                                                    </span>
                                                                                </p>
                                                                                {!q.is_correct && (
                                                                                    <p className="text-green-700">
                                                                                        Correct: {q.correct_answer}
                                                                                    </p>
                                                                                )}
                                                                            </div>
                                                                        ))}
                                                                    </div>
                                                                </div>
                                                            )}
                                                        </div>
                                                    );
                                                })}
                                            </div>
                                        )}
                                    </div>
                                );
                            })
                        ) : (
                            <div className="bg-white rounded-xl shadow-sm p-12 text-center">
                                <AcademicCapIcon className="w-16 h-16 text-gray-400 mx-auto mb-4" />
                                <h2 className="text-xl font-semibold text-gray-900 mb-2">No Quizzes Yet</h2>
                                <p className="text-gray-500">There are no quizzes available for this course.</p>
                            </div>
                        )}
                    </div>
                    
                    {/* Action Buttons */}
                    <div className="flex gap-4 mt-6">
                        <button
                            onClick={handleBackToCourse}
                            className="flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition"
                        >
                            Back to Course
                        </button>
                        
                        {overallStats.completed_quizzes < overallStats.total_quizzes && (
                            <button
                                onClick={handleContinueLearning}
                                className="flex-1 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                            >
                                Continue Learning
                            </button>
                        )}
                        
                        {/* {overallStats.completed_quizzes === overallStats.total_quizzes && overallStats.passed_quizzes === overallStats.total_quizzes && (
                            <button
                                onClick={() => window.location.href = route('dashboard.certificates.generate', enrollment.id)}
                                className="flex-1 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition"
                            >
                                🎓 View Certificate
                            </button>
                        )}  */}
                       {overallStats.completed_quizzes === overallStats.total_quizzes && overallStats.passed_quizzes === overallStats.total_quizzes && (
                            <button
                                onClick={() => window.location.href = route('dashboard.quiz.project-assessment', { 
                                    course: course.slug 
                                })}
                                className="flex-1 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition"
                            >
                                🏆 Take Final Project Assessment
                            </button>
                        )} 
                    </div> 
                </div>
            </div>
        </AuthenticatedLayout>
    );
} 