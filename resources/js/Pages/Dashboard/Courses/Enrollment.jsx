// resources/js/Pages/Enrollment/Index.jsx (or wherever your EnrollmentIndex is located)

import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { motion } from 'framer-motion';
import { 
    AcademicCapIcon,
    ClockIcon,
    ShieldCheckIcon,
    ClipboardDocumentCheckIcon,
    PlayCircleIcon
} from '@heroicons/react/24/outline';

export default function EnrollmentIndex({ course, enrollment, modules, exams }) {
    const [processingExam, setProcessingExam] = useState(null);

    // Handle starting an exam
    const handleStartExam = (examId) => {
        setProcessingExam(examId);
        
        router.post(route('exam.start', {
            enrollment: enrollment.id,
            exam: examId
        }), {}, {
            preserveScroll: true,
            onSuccess: () => {
                // Navigation is handled by the controller response
                setProcessingExam(null);
            },
            onError: (errors) => {
                setProcessingExam(null);
                console.error('Failed to start exam:', errors);
            }
        });
    };

    // Get exam status badge
    const getExamStatusBadge = (status) => {
        const badges = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'in_progress': 'bg-blue-100 text-blue-800',
            'completed': 'bg-green-100 text-green-800',
            'expired': 'bg-red-100 text-red-800'
        };
        return badges[status] || 'bg-gray-100 text-gray-800';
    };

    return (
        <AuthenticatedLayout>
            <Head title={`${course?.title} | My Learning`} />

            <div className="min-h-screen bg-gray-50 py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Course Header */}
                    <div className="mb-8">
                        <h1 className="text-3xl font-bold text-gray-900">{course?.title}</h1>
                        <p className="text-gray-600 mt-2">{course?.short_description}</p>
                    </div>

                    {/* Main Content Grid */}
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {/* Left Column - Course Content */}
                        <div className="lg:col-span-2 space-y-6">
                            {/* Modules Section */}
                            <div className="bg-white rounded-xl shadow-sm p-6">
                                <h2 className="text-xl font-semibold text-gray-900 mb-4">Course Modules</h2>
                                {modules?.map((module, index) => (
                                    <div key={module.id} className="mb-4 last:mb-0">
                                        <div className="flex items-start gap-3">
                                            <div className="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span className="text-sm font-semibold text-blue-600">{index + 1}</span>
                                            </div>
                                            <div>
                                                <h3 className="font-medium text-gray-900">{module.title}</h3>
                                                {module.description && (
                                                    <p className="text-sm text-gray-600 mt-1">{module.description}</p>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Right Column - Exams & Verification */}
                        <div className="space-y-6">
                            {/* Identity Verification Status */}
                            <div className="bg-white rounded-xl shadow-sm p-6">
                                <div className="flex items-center gap-3 mb-4">
                                    <ShieldCheckIcon className="w-6 h-6 text-indigo-600" />
                                    <h2 className="text-lg font-semibold text-gray-900">Identity Verification</h2>
                                </div>
                                
                                {enrollment?.identity_verified ? (
                                    <div className="bg-green-50 rounded-lg p-4">
                                        <p className="text-green-800 font-medium">✓ Verified</p>
                                        <p className="text-sm text-green-600 mt-1">
                                            Verified on {new Date(enrollment.verified_at).toLocaleDateString()}
                                        </p>
                                    </div>
                                ) : (
                                    <div>
                                        <p className="text-sm text-gray-600 mb-3">
                                            Verify your identity to access exams
                                        </p>
                                        <Link
                                            href={route('exam.verify', enrollment.id)}
                                            className="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                                        >
                                            <ShieldCheckIcon className="w-4 h-4" />
                                            Verify Identity
                                        </Link>
                                    </div>
                                )}
                            </div>

                            {/* Exams Section */}
                            <div className="bg-white rounded-xl shadow-sm p-6">
                                <div className="flex items-center gap-3 mb-4">
                                    <ClipboardDocumentCheckIcon className="w-6 h-6 text-purple-600" />
                                    <h2 className="text-lg font-semibold text-gray-900">Course Exams</h2>
                                </div>

                                {exams?.length > 0 ? (
                                    <div className="space-y-4">
                                        {exams.map((exam) => (
                                            <div key={exam.id} className="border rounded-lg p-4">
                                                <div className="flex justify-between items-start mb-2">
                                                    <h3 className="font-medium text-gray-900">{exam.title}</h3>
                                                    <span className={`text-xs px-2 py-1 rounded-full ${getExamStatusBadge(exam.status)}`}>
                                                        {exam.status.replace('_', ' ')}
                                                    </span>
                                                </div>
                                                
                                                <div className="flex items-center gap-4 text-sm text-gray-500 mb-3">
                                                    <span className="flex items-center gap-1">
                                                        <ClockIcon className="w-4 h-4" />
                                                        {exam.duration} mins
                                                    </span>
                                                    <span>{exam.questions_count} questions</span>
                                                </div>

                                                {exam.status === 'pending' && (
                                                    <button
                                                        onClick={() => handleStartExam(exam.id)}
                                                        disabled={!enrollment?.identity_verified || processingExam === exam.id}
                                                        className="w-full flex items-center justify-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                                    >
                                                        {processingExam === exam.id ? (
                                                            <>
                                                                <svg className="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" fill="none" />
                                                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                                </svg>
                                                                <span>Starting...</span>
                                                            </>
                                                        ) : (
                                                            <>
                                                                <PlayCircleIcon className="w-4 h-4" />
                                                                <span>Start Exam</span>
                                                            </>
                                                        )}
                                                    </button>
                                                )}

                                                {exam.status === 'in_progress' && (
                                                    <button
                                                        onClick={() => router.get(route('exam.continue', exam.attempt_id))}
                                                        className="w-full flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                                    >
                                                        <PlayCircleIcon className="w-4 h-4" />
                                                        Continue Exam
                                                    </button>
                                                )}

                                                {exam.status === 'completed' && (
                                                    <div className="bg-green-50 rounded-lg p-3 text-center">
                                                        <p className="text-green-800 font-medium">Score: {exam.score}%</p>
                                                        <p className="text-xs text-green-600 mt-1">Completed</p>
                                                    </div>
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <p className="text-gray-500 text-sm">No exams available</p>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}