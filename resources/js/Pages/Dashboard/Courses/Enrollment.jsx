import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { motion } from 'framer-motion';
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
    PlayCircleIcon
} from '@heroicons/react/24/outline';

export default function EnrollmentIndex({ course, enrollment, modules: initialModules = [], candidate, examResults }) {
    // State management
    const [modules] = useState(initialModules);
    const [progress] = useState(enrollment?.progress || 0);
    
    const hasCertificate = enrollment?.certificate_generated;
    const certificateNumber = enrollment?.certificate_number || candidate?.certificate_id;
    
    // ============== HELPER FUNCTIONS ==============
    
    // Get status badge color
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
    
    // Format candidate ID
    const formatCandidateId = (id) => {
        if (!id) return 'IGRCFP-' + enrollment?.id?.toString().padStart(6, '0');
        return id;
    };

    // Simple navigation to exam UI (just UI demonstration)
    // Simple navigation to exam UI - connects to controller
    const goToExam = () => {
        router.get(route('dashboard.exam.show', { enrollment: enrollment.id }));
    };

    return (
        <AuthenticatedLayout>
            <Head title={`${course?.title || 'Course'} | My Learning`} />

            <div className="min-h-screen bg-gray-50 py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Breadcrumb Navigation */}
                    <nav className="mb-6 flex items-center text-sm" aria-label="Breadcrumb">
                        <Link href={route('dashboard.index')} className="text-gray-500 hover:text-gray-700 transition">
                            Dashboard
                        </Link>
                        <span className="mx-2 text-gray-400">/</span>
                        <Link href="" className="text-gray-500 hover:text-gray-700 transition">
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
                                        onClick={() => navigator.clipboard.writeText(formatCandidateId(candidate.certificate_id))}
                                        className="flex items-center gap-2 px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition"
                                    >
                                        <DocumentDuplicateIcon className="w-4 h-4" />
                                        Copy ID
                                    </button>
                                    <Link
                                        href={route('dashboard.certificate.verify', { id: candidate.certificate_id })}
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
                                                        <h2 className="text-xl font-semibold text-gray-900">
                                                            {module.title}
                                                        </h2>
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
                                                    <div className="mb-4">
                                                        <h3 className="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">
                                                            Learning Materials
                                                        </h3>
                                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                            {module.materials.map((material) => (
                                                                <a
                                                                    key={material.id}
                                                                    href={material.file_path || material.url}
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

                        {/* Right Column - Exams & Certification */}
                        <div className="space-y-6" id="exams-section">
                            {/* Identity Verification Card */}
                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                className="bg-white rounded-xl shadow-sm p-6"
                            >
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                    <CameraIcon className="w-5 h-5 text-indigo-600" />
                                    Identity Verification
                                </h3>
                                
                                <div className="space-y-4">
                                    <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <p className="text-sm text-yellow-800">
                                            Click the button below to start the exam UI demonstration.
                                        </p>
                                    </div>
                                    
                                    <button
                                        onClick={goToExam}
                                        className="w-full flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                                    >
                                        <CameraIcon className="w-5 h-5" />
                                        Start Camera (UI Demo)
                                    </button>
                                </div>
                            </motion.div>

                            {/* Exams Card */}
                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                transition={{ delay: 0.1 }}
                                className="bg-white rounded-xl shadow-sm p-6"
                            >
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                    <ClipboardDocumentCheckIcon className="w-5 h-5 text-purple-600" />
                                    Timed Online Exams
                                </h3>

                                {enrollment?.exams?.length > 0 ? (
                                    <div className="space-y-4">
                                        {enrollment.exams.map((exam) => (
                                            <div key={exam.id} className="border rounded-lg p-4">
                                                <div className="flex justify-between items-start mb-2">
                                                    <h4 className="font-medium text-gray-900">{exam.title}</h4>
                                                    <span className={`text-xs px-2 py-1 rounded-full ${getExamStatusBadge(exam.status)}`}>
                                                        {exam.status?.replace('_', ' ') || 'pending'}
                                                    </span>
                                                </div>
                                                
                                                <div className="text-sm text-gray-600 mb-3">
                                                    <p>• {exam.duration} minutes</p>
                                                    <p>• {exam.questions_count || 0} questions</p>
                                                    {exam.type === 'diploma' && (
                                                        <p>• Manual marking by instructors</p>
                                                    )}
                                                </div>

                                                {exam.status === 'pending' && (
                                                    <button
                                                        onClick={() => alert('Exam UI would open here')}
                                                        className="w-full flex items-center justify-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition"
                                                    >
                                                        <PlayCircleIcon className="w-4 h-4" />
                                                        Start Exam
                                                    </button>
                                                )}

                                                {exam.status === 'in_progress' && (
                                                    <button
                                                        onClick={() => alert('Continue Exam UI would open here')}
                                                        className="w-full flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                                    >
                                                        <PlayCircleIcon className="w-4 h-4" />
                                                        Continue Exam
                                                    </button>
                                                )}

                                                {exam.status === 'completed' && (
                                                    <div className="bg-green-50 rounded-lg p-3 text-center">
                                                        <p className="text-green-800 font-medium">Score: {exam.score}%</p>
                                                        <p className="text-xs text-green-600 mt-1">
                                                            {exam.type === 'diploma' ? 'Awaiting manual review' : 'Completed'}
                                                        </p>
                                                    </div>
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <div className="text-center py-6">
                                        <p className="text-gray-500 mb-3">No exams scheduled yet</p>
                                        <LockClosedIcon className="w-8 h-8 text-gray-400 mx-auto" />
                                    </div>
                                )}
                            </motion.div>

                            {/* Certification Card */}
                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                transition={{ delay: 0.2 }}
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
                                                    href={route('dashboard.download', enrollment.id)}
                                                    className="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm"
                                                >
                                                    <DocumentTextIcon className="w-4 h-4" />
                                                    Download PDF
                                                </Link>
                                                <Link
                                                    href={route('dashboard.certificate.preview', enrollment.id)}
                                                    className="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-white border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50 transition text-sm"
                                                >
                                                    <GlobeAltIcon className="w-4 h-4" />
                                                    Preview
                                                </Link>
                                            </div>
                                        </div>

                                        <Link
                                            href={route('dashboard.certificate.badge', enrollment.id)}
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
                                            Complete all exams and requirements to earn your digital certificate.
                                        </p>
                                        
                                        <div className="bg-gray-50 rounded-lg p-4">
                                            <h4 className="font-medium text-gray-900 mb-2">Requirements:</h4>
                                            <ul className="text-sm text-gray-600 space-y-1">
                                                <li className="flex items-center gap-2">
                                                    <span className={`w-4 h-4 rounded-full ${enrollment?.identity_verified ? 'bg-green-500' : 'bg-gray-300'} flex items-center justify-center text-white text-xs`}>
                                                        {enrollment?.identity_verified ? '✓' : ''}
                                                    </span>
                                                    Identity verification
                                                </li>
                                                <li className="flex items-center gap-2">
                                                    <span className={`w-4 h-4 rounded-full ${examResults?.passed ? 'bg-green-500' : 'bg-gray-300'} flex items-center justify-center text-white text-xs`}>
                                                        {examResults?.passed ? '✓' : ''}
                                                    </span>
                                                    Pass all exams
                                                </li>
                                                <li className="flex items-center gap-2">
                                                    <span className={`w-4 h-4 rounded-full ${progress === 100 ? 'bg-green-500' : 'bg-gray-300'} flex items-center justify-center text-white text-xs`}>
                                                        {progress === 100 ? '✓' : ''}
                                                    </span>
                                                    100% course completion
                                                </li>
                                            </ul>
                                        </div>

                                        {/* Certification Registry Link */}
                                        <Link
                                            href={route('dashboard.certificate.registry')}
                                            className="flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 transition"
                                        >
                                            <GlobeAltIcon className="w-4 h-4" />
                                            View Certification Registry
                                        </Link>
                                    </div>
                                )}
                            </motion.div>

                            {/* Plagiarism & Security Notice */}
                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                transition={{ delay: 0.3 }}
                                className="bg-gray-50 rounded-xl p-4"
                            >
                                <div className="flex items-start gap-3">
                                    <ShieldCheckIcon className="w-5 h-5 text-gray-600 flex-shrink-0 mt-0.5" />
                                    <div>
                                        <p className="text-sm font-medium text-gray-900 mb-1">Academic Integrity</p>
                                        <p className="text-xs text-gray-600">
                                            All submissions are monitored by our plagiarism detection software. 
                                            Your unique candidate ID ensures your work is properly attributed.
                                        </p>
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