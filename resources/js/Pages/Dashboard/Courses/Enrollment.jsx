import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { motion } from 'framer-motion';
import toast from 'react-hot-toast';
import { 
    BookOpenIcon, 
    ClockIcon, 
    DocumentTextIcon,
    AcademicCapIcon,
    ShieldCheckIcon,
    IdentificationIcon,
    DocumentDuplicateIcon,
    QrCodeIcon,
    GlobeAltIcon,
    CheckBadgeIcon,
    SparklesIcon,
    CameraIcon,
    LockClosedIcon,
    ClipboardDocumentCheckIcon,
    PlayCircleIcon,
    ArrowPathIcon,
    ExclamationTriangleIcon
} from '@heroicons/react/24/outline';

export default function EnrollmentIndex({ 
    course, 
    enrollment, 
    modules: initialModules = [], 
    candidate, 
    quizzes = [], 
    moduleAssessments = [], 
    finalExam = null, 
    diplomaAssessment = null,
    examResults = {} 
}) {
    // State management
    const [modules] = useState(initialModules);
    const [progress] = useState(enrollment?.progress || 0);
    const [processingExam, setProcessingExam] = useState(null);
    
    const hasCertificate = enrollment?.certificate_generated;
    const certificateNumber = enrollment?.certificate_number || candidate?.certificate_id;
    const isIdentityVerified = enrollment?.identity_verified || false;
    
    // ============== HELPER FUNCTIONS ==============
    
    const getStatusBadge = (status) => {
        const statusMap = {
            'pending_payment': 'bg-yellow-100 text-yellow-800',
            'enrolled': 'bg-green-100 text-green-800',
            'in_progress': 'bg-blue-100 text-blue-800',
            'completed': 'bg-purple-100 text-purple-800',
            'certified': 'bg-indigo-100 text-indigo-800',
            'cancelled': 'bg-red-100 text-red-800'
        };
        return statusMap[status] || 'bg-gray-100 text-gray-800';
    };
    
    const getAssessmentStatusBadge = (status) => {
        const badges = {
            'not_started': 'bg-gray-100 text-gray-800',
            'pending': 'bg-yellow-100 text-yellow-800',
            'in_progress': 'bg-blue-100 text-blue-800',
            'completed': 'bg-green-100 text-green-800',
            'graded': 'bg-indigo-100 text-indigo-800',
            'expired': 'bg-red-100 text-red-800'
        };
        return badges[status] || 'bg-gray-100 text-gray-800';
    };
    
    const getAssessmentTypeIcon = (type) => {
        const icons = {
            'quiz': <SparklesIcon className="w-5 h-5" />,
            'module_assessment': <ClipboardDocumentCheckIcon className="w-5 h-5" />,
            'final_exam': <AcademicCapIcon className="w-5 h-5" />,
            'diploma': <ShieldCheckIcon className="w-5 h-5" />
        };
        return icons[type] || <DocumentTextIcon className="w-5 h-5" />;
    };
    
    const getAssessmentTypeColor = (type) => {
        const colors = {
            'quiz': 'bg-green-100 text-green-800 border-green-200',
            'module_assessment': 'bg-blue-100 text-blue-800 border-blue-200',
            'final_exam': 'bg-purple-100 text-purple-800 border-purple-200',
            'diploma': 'bg-indigo-100 text-indigo-800 border-indigo-200'
        };
        return colors[type] || 'bg-gray-100 text-gray-800 border-gray-200';
    };
    
    const formatCandidateId = (id) => {
        if (!id) return 'IGRCFP-' + enrollment?.id?.toString().padStart(6, '0');
        return id;
    };

    const formatTimeRemaining = (dueDate) => {
        if (!dueDate) return null;
        
        const now = new Date();
        const due = new Date(dueDate);
        const diffMs = due - now;
        
        if (diffMs <= 0) return 'Expired';
        
        const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
        const diffHours = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        
        if (diffDays > 0) {
            return `${diffDays} day${diffDays > 1 ? 's' : ''} remaining`;
        }
        if (diffHours > 0) {
            return `${diffHours} hour${diffHours > 1 ? 's' : ''} remaining`;
        }
        return 'Less than an hour remaining';
    };

    // ============== ASSESSMENT NAVIGATION ==============
    
    const handleStartAssessment = (assessmentId, type) => {
        // Check if identity verification is required for this assessment type
        if ((type === 'final_exam' || type === 'diploma') && !isIdentityVerified) {
            toast.error('Please verify your identity first before starting this assessment.');
            
            // Scroll to identity verification section
            document.getElementById('identity-verification-section')?.scrollIntoView({ 
                behavior: 'smooth' 
            });
            return;
        }
        
        setProcessingExam(assessmentId);
        
        const routeName = type === 'quiz' ? 'assessment.take.quiz' 
            : type === 'module_assessment' ? 'assessment.take.module'
            : type === 'final_exam' ? 'assessment.take.final'
            : 'assessment.take.diploma';
        
        router.get(route(routeName, { 
            enrollment: enrollment.id, 
            assessment: assessmentId 
        }), {}, {
            preserveState: false,
            onStart: () => {
                toast.loading('Preparing your assessment...', { id: 'assessment-loading' });
            },
            onSuccess: () => {
                toast.dismiss('assessment-loading');
                setProcessingExam(null);
            },
            onError: (errors) => {
                toast.dismiss('assessment-loading');
                setProcessingExam(null);
                console.error('Failed to start assessment:', errors);
                toast.error(errors.message || 'Failed to start assessment. Please try again.');
            }
        });
    };

    const handleContinueAssessment = (assessmentId, type) => {
        setProcessingExam(assessmentId);
        
        const routeName = type === 'quiz' ? 'assessment.continue.quiz' 
            : type === 'module_assessment' ? 'assessment.continue.module'
            : type === 'final_exam' ? 'assessment.continue.final'
            : 'assessment.continue.diploma';
        
        router.get(route(routeName, { 
            enrollment: enrollment.id, 
            assessment: assessmentId 
        }), {}, {
            preserveState: false,
            onStart: () => {
                toast.loading('Continuing assessment...', { id: 'assessment-loading' });
            },
            onSuccess: () => {
                toast.dismiss('assessment-loading');
                setProcessingExam(null);
            },
            onError: (errors) => {
                toast.dismiss('assessment-loading');
                setProcessingExam(null);
                toast.error('Failed to continue assessment.');
            }
        });
    };

    const handleReviewAssessment = (assessmentId, type) => {
        const routeName = type === 'quiz' ? 'assessment.review.quiz' 
            : type === 'module_assessment' ? 'assessment.review.module'
            : type === 'final_exam' ? 'assessment.review.final'
            : 'assessment.review.diploma';
        
        router.get(route(routeName, { 
            enrollment: enrollment.id, 
            assessment: assessmentId 
        }));
    };

    // ============== RENDER ASSESSMENT CARD ==============
    
    const renderAssessmentCard = (assessment, type) => {
        const isProcessing = processingExam === assessment.id;
        const status = assessment.status || 'not_started';
        const timeRemaining = assessment.due_date ? formatTimeRemaining(assessment.due_date) : null;
        
        return (
            <motion.div
                key={assessment.id}
                initial={{ opacity: 0, y: 10 }}
                animate={{ opacity: 1, y: 0 }}
                className={`border rounded-xl overflow-hidden transition-all hover:shadow-md ${getAssessmentTypeColor(type)}`}
            >
                <div className="p-5">
                    {/* Header with Type and Status */}
                    <div className="flex items-center justify-between mb-3">
                        <div className="flex items-center gap-2">
                            <span className="p-1.5 bg-white rounded-lg shadow-sm">
                                {getAssessmentTypeIcon(type)}
                            </span>
                            <span className="font-medium text-sm capitalize">
                                {type.replace('_', ' ')}
                            </span>
                        </div>
                        <span className={`px-3 py-1 rounded-full text-xs font-medium ${getAssessmentStatusBadge(status)}`}>
                            {status.replace('_', ' ')}
                        </span>
                    </div>
                    
                    {/* Title and Description */}
                    <h4 className="font-semibold text-gray-900 mb-2">{assessment.title}</h4>
                    <p className="text-sm text-gray-600 mb-4 line-clamp-2">
                        {assessment.description || 'No description provided'}
                    </p>
                    
                    {/* Metadata */}
                    <div className="flex flex-wrap items-center gap-4 text-xs text-gray-500 mb-4">
                        {assessment.duration && (
                            <span className="flex items-center gap-1">
                                <ClockIcon className="w-4 h-4" />
                                {assessment.duration} mins
                            </span>
                        )}
                        {assessment.questions_count > 0 && (
                            <span className="flex items-center gap-1">
                                <DocumentTextIcon className="w-4 h-4" />
                                {assessment.questions_count} questions
                            </span>
                        )}
                        {assessment.total_marks > 0 && (
                            <span>{assessment.total_marks} marks</span>
                        )}
                        {assessment.passing_score > 0 && (
                            <span>Pass: {assessment.passing_score}%</span>
                        )}
                    </div>
                    
                    {/* Time Remaining Warning */}
                    {timeRemaining && status === 'in_progress' && (
                        <div className="mb-4 p-2 bg-yellow-50 rounded-lg flex items-center gap-2 text-xs text-yellow-700">
                            <ExclamationTriangleIcon className="w-4 h-4 flex-shrink-0" />
                            <span>{timeRemaining}</span>
                        </div>
                    )}
                    
                    {/* Score Display (for completed) */}
                    {assessment.score !== undefined && (
                        <div className="mb-4 p-3 bg-green-50 rounded-lg text-center">
                            <div className="text-2xl font-bold text-green-700">{assessment.score}%</div>
                            <div className="text-xs text-green-600">
                                {assessment.passed ? 'Passed' : 'Failed'} • 
                                Passing score: {assessment.passing_score}%
                            </div>
                        </div>
                    )}
                    
                    {/* Action Buttons */}
                    <div className="flex gap-2">
                        {status === 'not_started' && (
                            <button
                                onClick={() => handleStartAssessment(assessment.id, type)}
                                disabled={isProcessing || (type === 'final_exam' && !isIdentityVerified)}
                                className="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {isProcessing ? (
                                    <ArrowPathIcon className="w-4 h-4 animate-spin" />
                                ) : (
                                    <PlayCircleIcon className="w-4 h-4" />
                                )}
                                {isProcessing ? 'Starting...' : 'Start Assessment'}
                            </button>
                        )}
                        
                        {status === 'in_progress' && (
                            <button
                                onClick={() => handleContinueAssessment(assessment.id, type)}
                                disabled={isProcessing}
                                className="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
                            >
                                {isProcessing ? (
                                    <ArrowPathIcon className="w-4 h-4 animate-spin" />
                                ) : (
                                    <PlayCircleIcon className="w-4 h-4" />
                                )}
                                {isProcessing ? 'Continuing...' : 'Continue'}
                            </button>
                        )}
                        
                        {status === 'completed' && (
                            <button
                                onClick={() => handleReviewAssessment(assessment.id, type)}
                                className="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition"
                            >
                                <DocumentTextIcon className="w-4 h-4" />
                                Review
                            </button>
                        )}
                        
                        {status === 'graded' && (
                            <button
                                onClick={() => handleReviewAssessment(assessment.id, type)}
                                className="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition"
                            >
                                <DocumentTextIcon className="w-4 h-4" />
                                View Results
                            </button>
                        )}
                    </div>
                    
                    {/* Manual Marking Notice for Diploma */}
                    {type === 'diploma' && status === 'completed' && (
                        <div className="mt-3 text-xs text-center text-amber-600 bg-amber-50 p-2 rounded-lg">
                            ⏳ Awaiting manual review by instructors
                        </div>
                    )}
                </div>
            </motion.div>
        );
    };

    return (
        <AuthenticatedLayout>
            <Head title={`${course?.title || 'Course'} | My Learning`} />

            <div className="min-h-screen bg-gray-50 py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Breadcrumb Navigation */}
                    <nav className="mb-6 flex items-center text-sm" aria-label="Breadcrumb">
                        <Link href={route('dashboard.index')} className="text-gray-500 hover:text-gray-700 transition">
                            Dashboard
                        </Link>
                        <span className="mx-2 text-gray-400">/</span>
                        <Link href={route('my-coursesmy-courses')} className="text-gray-500 hover:text-gray-700 transition">
                            My Courses
                        </Link>
                        <span className="mx-2 text-gray-400">/</span>
                        <span className="text-gray-900 font-medium truncate max-w-xs">{course?.title}</span>
                    </nav>

                    {/* Candidate ID Banner */}
                    {candidate && (
                        <motion.div 
                            initial={{ opacity: 0, y: -20 }}
                            animate={{ opacity: 1, y: 0 }}
                            className="mb-6 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg overflow-hidden"
                        >
                            <div className="px-6 py-4 flex flex-wrap items-center justify-between">
                                <div className="flex items-center gap-4">
                                    <div className="bg-white/20 rounded-lg p-3">
                                        <IdentificationIcon className="w-8 h-8 text-white" />
                                    </div>
                                    <div>
                                        <p className="text-indigo-100 text-sm">Your Candidate ID</p>
                                        <p className="text-2xl font-mono font-bold text-white tracking-wider">
                                            {formatCandidateId(candidate.certificate_id)}
                                        </p>
                                    </div>
                                </div>
                                <div className="flex gap-3 mt-3 sm:mt-0">
                                    <button 
                                        onClick={() => {
                                            navigator.clipboard.writeText(formatCandidateId(candidate.certificate_id));
                                            toast.success('Candidate ID copied to clipboard!');
                                        }}
                                        className="flex items-center gap-2 px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition"
                                    >
                                        <DocumentDuplicateIcon className="w-4 h-4" />
                                        Copy ID
                                    </button>
                                    <Link
                                        href={route('certificate.verify', { id: candidate.certificate_id })}
                                        className="flex items-center gap-2 px-4 py-2 bg-white text-indigo-600 rounded-lg hover:bg-gray-100 transition"
                                    >
                                        <ShieldCheckIcon className="w-4 h-4" />
                                        Verify
                                    </Link>
                                </div>
                            </div>
                        </motion.div>
                    )}

                    {/* Main Content Grid */}
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {/* Left Column - Course Info & Progress */}
                        <div className="lg:col-span-2 space-y-6">
                            {/* Course Header Card */}
                            <motion.div 
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                className="bg-white rounded-2xl shadow-sm overflow-hidden"
                            >
                                {/* Cover Image */}
                                <div className="h-64 w-full bg-gradient-to-r from-blue-900 to-indigo-900 relative">
                                    {course?.image_url || course?.banner_image ? (
                                        <img 
                                            src={course.image_url || course.banner_image}
                                            alt={course.title}
                                            className="w-full h-full object-cover opacity-50"
                                        />
                                    ) : (
                                        <div className="absolute inset-0 bg-gradient-to-r from-blue-900 to-indigo-900"></div>
                                    )}
                                    
                                    <div className="absolute bottom-0 left-0 p-8 text-white">
                                        <h1 className="text-3xl font-bold mb-2">{course?.title}</h1>
                                        <div className="flex items-center gap-4 text-sm">
                                            <span className="flex items-center gap-1">
                                                <AcademicCapIcon className="w-4 h-4" />
                                                {modules?.length || 0} modules
                                            </span>
                                            <span>•</span>
                                            <span className="flex items-center gap-1">
                                                <ClockIcon className="w-4 h-4" />
                                                {course?.duration}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {/* Course Info */}
                                <div className="p-6">
                                    <div className="flex flex-wrap items-center justify-between gap-4 mb-4">
                                        <div className="flex items-center gap-3">
                                            <span className={`px-3 py-1 rounded-full text-sm font-medium ${getStatusBadge(enrollment?.status)}`}>
                                                {enrollment?.status?.replace('_', ' ') || 'Enrolled'}
                                            </span>
                                            {course?.level && (
                                                <span className="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                                    {course.level}
                                                </span>
                                            )}
                                        </div>
                                        <div className="text-sm text-gray-500">
                                            Enrolled: {enrollment?.enrollment_date ? new Date(enrollment.enrollment_date).toLocaleDateString() : 'N/A'}
                                        </div>
                                    </div>

                                    {/* Progress Bar */}
                                    <div className="mb-4">
                                        <div className="flex justify-between text-sm mb-2">
                                            <span className="font-medium text-gray-700">Course Progress</span>
                                            <span className="text-blue-600 font-medium">{progress}% Complete</span>
                                        </div>
                                        <div className="h-3 w-full bg-gray-200 rounded-full overflow-hidden">
                                            <motion.div 
                                                initial={{ width: 0 }}
                                                animate={{ width: `${progress}%` }}
                                                transition={{ duration: 0.5 }}
                                                className="h-full bg-blue-600 rounded-full"
                                            ></motion.div>
                                        </div>
                                    </div>

                                    {/* Course Description */}
                                    {course?.full_description && (
                                        <div className="prose prose-sm max-w-none text-gray-600">
                                            <div dangerouslySetInnerHTML={{ __html: course.full_description }} />
                                        </div>
                                    )}
                                </div>
                            </motion.div>

                            {/* Modules & Content */}
                            <div className="space-y-4">
                                <h2 className="text-xl font-semibold text-gray-900 flex items-center gap-2">
                                    <BookOpenIcon className="w-5 h-5 text-indigo-600" />
                                    Course Modules
                                </h2>
                                {modules?.length > 0 ? (
                                    modules.map((module, moduleIndex) => (
                                        <motion.div
                                            key={module.id}
                                            initial={{ opacity: 0, y: 20 }}
                                            animate={{ opacity: 1, y: 0 }}
                                            transition={{ delay: moduleIndex * 0.1 }}
                                            className="bg-white rounded-xl shadow-sm overflow-hidden"
                                        >
                                            {/* Module Header */}
                                            <div className="p-6 border-b bg-gray-50">
                                                <div className="flex items-start justify-between">
                                                    <div>
                                                        <div className="flex items-center gap-3 mb-2">
                                                            <span className="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                                                Module {module.module_number}
                                                            </span>
                                                            {module.code && (
                                                                <span className="text-sm text-gray-500">
                                                                    {module.code}
                                                                </span>
                                                            )}
                                                        </div>
                                                        <h3 className="text-xl font-semibold text-gray-900">
                                                            {module.title}
                                                        </h3>
                                                        {module.short_description && (
                                                            <div 
                                                                className="prose prose-sm max-w-none text-gray-600 mt-2"
                                                                dangerouslySetInnerHTML={{ __html: module.short_description }}
                                                            />
                                                        )}
                                                    </div>
                                                    <div className="flex items-center gap-2 text-sm text-gray-500">
                                                        <span className="flex items-center gap-1">
                                                            <ClockIcon className="w-4 h-4" />
                                                            {module.estimated_hours} hrs
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {/* Module Content */}
                                            <div className="p-6">
                                                {/* Materials */}
                                                {module.materials?.length > 0 && (
                                                    <div>
                                                        <h4 className="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">
                                                            Materials
                                                        </h4>
                                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                            {module.materials.map((material) => (
                                                                <a
                                                                    key={material.id}
                                                                    href={material.file_url}
                                                                    target="_blank"
                                                                    rel="noopener noreferrer"
                                                                    className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition group"
                                                                >
                                                                    <DocumentTextIcon className="w-5 h-5 text-blue-600 group-hover:scale-110 transition" />
                                                                    <span className="text-sm text-gray-900">
                                                                        {material.title}
                                                                    </span>
                                                                </a>
                                                            ))}
                                                        </div>
                                                    </div>
                                                )}
                                            </div>
                                        </motion.div>
                                    ))
                                ) : (
                                    <div className="bg-white rounded-xl shadow-sm p-12 text-center">
                                        <BookOpenIcon className="w-16 h-16 text-gray-400 mx-auto mb-4" />
                                        <h3 className="text-lg font-medium text-gray-900 mb-1">No modules yet</h3>
                                        <p className="text-gray-500">Course content is being prepared.</p>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Right Column - Assessments & Certification */}
                        <div className="space-y-6">
                            {/* Identity Verification Card */}
                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                className="bg-white rounded-xl shadow-sm p-6 border-2 border-indigo-100"
                                id="identity-verification-section"
                            >
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                    <CameraIcon className="w-5 h-5 text-indigo-600" />
                                    Identity Verification
                                </h3>
                                
                                {isIdentityVerified ? (
                                    <div className="bg-green-50 rounded-lg p-4 flex items-center gap-3">
                                        <CheckBadgeIcon className="w-8 h-8 text-green-600 flex-shrink-0" />
                                        <div>
                                            <p className="font-medium text-green-800">Identity Verified</p>
                                            <p className="text-xs text-green-600">
                                                Verified on {enrollment?.verified_at ? new Date(enrollment.verified_at).toLocaleDateString() : 'N/A'}
                                            </p>
                                        </div>
                                    </div>
                                ) : (
                                    <div className="space-y-4">
                                        <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                            <p className="text-sm text-yellow-800">
                                                <strong>Required for final exams and diploma assessments.</strong> Complete verification once to access all assessments.
                                            </p>
                                        </div>
                                        
                                        <Link
                                            href={route('identity.verify', { enrollment: enrollment.id })}
                                            className="w-full flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                                        >
                                            <CameraIcon className="w-5 h-5" />
                                            Verify Identity Now
                                        </Link>
                                    </div>
                                )}
                            </motion.div>

                            {/* Quizzes Section */}
                            {quizzes?.length > 0 && (
                                <motion.div
                                    initial={{ opacity: 0, x: 20 }}
                                    animate={{ opacity: 1, x: 0 }}
                                    transition={{ delay: 0.1 }}
                                    className="space-y-3"
                                >
                                    <h3 className="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                        <SparklesIcon className="w-5 h-5 text-green-600" />
                                        Module Quizzes
                                    </h3>
                                    <div className="space-y-4">
                                        {quizzes.map(quiz => renderAssessmentCard(quiz, 'quiz'))}
                                    </div>
                                </motion.div>
                            )}

                            {/* Module Assessments Section */}
                            {moduleAssessments?.length > 0 && (
                                <motion.div
                                    initial={{ opacity: 0, x: 20 }}
                                    animate={{ opacity: 1, x: 0 }}
                                    transition={{ delay: 0.2 }}
                                    className="space-y-3"
                                >
                                    <h3 className="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                        <ClipboardDocumentCheckIcon className="w-5 h-5 text-blue-600" />
                                        Module Assessments
                                    </h3>
                                    <div className="space-y-4">
                                        {moduleAssessments.map(assessment => renderAssessmentCard(assessment, 'module_assessment'))}
                                    </div>
                                </motion.div>
                            )}

                            {/* Final Exam Section */}
                            {finalExam && (
                                <motion.div
                                    initial={{ opacity: 0, x: 20 }}
                                    animate={{ opacity: 1, x: 0 }}
                                    transition={{ delay: 0.3 }}
                                    className="space-y-3"
                                >
                                    <h3 className="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                        <AcademicCapIcon className="w-5 h-5 text-purple-600" />
                                        Final Exam
                                    </h3>
                                    <div className="space-y-4">
                                        {renderAssessmentCard(finalExam, 'final_exam')}
                                    </div>
                                </motion.div>
                            )}

                            {/* Diploma Assessment Section */}
                            {diplomaAssessment && (
                                <motion.div
                                    initial={{ opacity: 0, x: 20 }}
                                    animate={{ opacity: 1, x: 0 }}
                                    transition={{ delay: 0.4 }}
                                    className="space-y-3"
                                >
                                    <h3 className="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                        <ShieldCheckIcon className="w-5 h-5 text-indigo-600" />
                                        Diploma Project
                                    </h3>
                                    <div className="space-y-4">
                                        {renderAssessmentCard(diplomaAssessment, 'diploma')}
                                    </div>
                                </motion.div>
                            )}

                            {/* Certification Card */}
                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                transition={{ delay: 0.5 }}
                                className="bg-white rounded-xl shadow-sm p-6 border-2 border-indigo-100"
                            >
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                    <CheckBadgeIcon className="w-5 h-5 text-indigo-600" />
                                    Digital Certification
                                </h3>

                                {hasCertificate ? (
                                    <div className="space-y-4">
                                        <div className="bg-indigo-50 rounded-lg p-4">
                                            <div className="flex items-center gap-3 mb-3">
                                                <QrCodeIcon className="w-10 h-10 text-indigo-600" />
                                                <div>
                                                    <p className="text-xs text-indigo-600">Certificate Number</p>
                                                    <p className="font-mono font-bold text-indigo-900">{certificateNumber}</p>
                                                </div>
                                            </div>
                                            
                                            <div className="flex gap-2">
                                                <Link
                                                    href={route('certificate.download', enrollment.id)}
                                                    className="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm"
                                                >
                                                    <DocumentTextIcon className="w-4 h-4" />
                                                    Download PDF
                                                </Link>
                                                <Link
                                                    href={route('certificate.preview', enrollment.id)}
                                                    className="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-white border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50 transition text-sm"
                                                >
                                                    <GlobeAltIcon className="w-4 h-4" />
                                                    Preview
                                                </Link>
                                            </div>
                                        </div>

                                        <Link
                                            href={route('certificate.badge', enrollment.id)}
                                            className="flex items-center justify-between p-3 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-lg hover:from-amber-100 hover:to-yellow-100 transition"
                                        >
                                            <div className="flex items-center gap-3">
                                                <SparklesIcon className="w-5 h-5 text-amber-600" />
                                                <div>
                                                    <p className="font-medium text-amber-900">Digital Badge</p>
                                                    <p className="text-xs text-amber-700">Claim your verifiable badge</p>
                                                </div>
                                            </div>
                                            <span className="text-amber-600">→</span>
                                        </Link>
                                    </div>
                                ) : (
                                    <div className="space-y-4">
                                        <p className="text-gray-600 text-sm">
                                            Complete all assessments to earn your digital certificate.
                                        </p>
                                        
                                        <div className="bg-gray-50 rounded-lg p-4">
                                            <h4 className="font-medium text-gray-900 mb-2">Requirements:</h4>
                                            <ul className="text-sm text-gray-600 space-y-1">
                                                <li className="flex items-center gap-2">
                                                    <span className={`w-4 h-4 rounded-full ${isIdentityVerified ? 'bg-green-500' : 'bg-gray-300'}`}></span>
                                                    Identity verification
                                                </li>
                                                <li className="flex items-center gap-2">
                                                    <span className={`w-4 h-4 rounded-full ${examResults?.all_passed ? 'bg-green-500' : 'bg-gray-300'}`}></span>
                                                    Pass all assessments
                                                </li>
                                                <li className="flex items-center gap-2">
                                                    <span className={`w-4 h-4 rounded-full ${progress === 100 ? 'bg-green-500' : 'bg-gray-300'}`}></span>
                                                    100% course completion
                                                </li>
                                            </ul>
                                        </div>

                                        <Link
                                            href={route('certificate.registry')}
                                            className="flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 transition"
                                        >
                                            <GlobeAltIcon className="w-4 h-4" />
                                            View Certification Registry
                                        </Link>
                                    </div>
                                )}
                            </motion.div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}