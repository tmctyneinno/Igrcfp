import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import toast from 'react-hot-toast';
import { 
    BookOpenIcon, 
    ClockIcon, 
    DocumentTextIcon,
    VideoCameraIcon,
    PresentationChartBarIcon
} from '@heroicons/react/24/outline';

export default function EnrollmentIndex({ course, enrollment, modules: initialModules = [] }) {
    // Calculate progress
    const [modules, setModules] = useState(initialModules);
    const [progress, setProgress] = useState(enrollment?.progress || 0);
    const hasCertificate = enrollment?.certificate_generated === true;
    
    // Get status badge color
    const getStatusBadge = (status) => {
        const statusMap = {
            'pending_payment': 'bg-yellow-100 text-yellow-800',
            'enrolled': 'bg-green-100 text-green-800',
            'completed': 'bg-blue-100 text-blue-800',
            'cancelled': 'bg-red-100 text-red-800'
        };
        return statusMap[status] || 'bg-gray-100 text-gray-800';
    };

    // Get icon based on material type
    const getMaterialIcon = (type) => {
        const icons = {
            'video': VideoCameraIcon,
            'document': DocumentTextIcon,
            'presentation': PresentationChartBarIcon,
            'quiz': BookOpenIcon,
        };
        return icons[type] || DocumentTextIcon;
    };

    const handleLessonClick = (lesson) => {
        // Show loading state
        toast.loading('Marking lesson as complete...', { id: 'lesson-complete' });
        
        // Make API call to mark lesson as complete
        router.post(route('lessons.complete', lesson.id), {
            enrollment_id: enrollment.id
        }, {
            preserveScroll: true,
            onSuccess: (page) => {
                // Update local state with new data
                const updatedModules = page.props.modules;
                setModules(updatedModules);
                setProgress(page.props.enrollment.progress);
                
                toast.success('Lesson completed! 🎉', { id: 'lesson-complete' });
                
                // If progress reached 100%, show certificate option
                if (page.props.enrollment.progress === 100) {
                    toast.success('Congratulations! You completed the course! You can now generate your certificate.', {
                        duration: 5000,
                        icon: '🎓'
                    });
                }
            },
            onError: (errors) => {
                toast.error('Failed to mark lesson as complete', { id: 'lesson-complete' });
                console.error(errors);
            }
        });
    };

    return (
        <AuthenticatedLayout>
            <Head title={`${course.title} | My Learning`} />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Breadcrumb */}
                    <div className="mb-6 flex items-center text-sm">
                        <Link href={route('dashboard.index')} className="text-gray-500 hover:text-gray-700">
                            Dashboard
                        </Link>
                        <span className="mx-2 text-gray-400">/</span>
                        <Link href={route('dashboard.my-courses')} className="text-gray-500 hover:text-gray-700">
                            My Courses
                        </Link>
                        <span className="mx-2 text-gray-400">/</span>
                        <span className="text-gray-900 font-medium">{course.title}</span>
                    </div>

                    {/* Course Header */}
                    <div className="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                        {/* Cover Image */}
                        <div className="h-64 w-full bg-gradient-to-r from-blue-900 to-indigo-900 relative">
                            {course.image_url || course.banner_image ? (
                                <img 
                                    src={course.image_url || course.banner_image}
                                    alt={course.title}
                                    className="w-full h-full object-cover opacity-50"
                                />
                            ) : (
                                <div className="absolute inset-0 bg-gradient-to-r from-blue-900 to-indigo-900"></div>
                            )}
                            
                            <div className="absolute bottom-0 left-0 p-8 text-white">
                                <h1 className="text-3xl font-bold mb-2">{course.title}</h1>
                                <div className="flex items-center gap-4">
                                    <span className="flex items-center gap-1">
                                        <span className="text-yellow-400">★</span>
                                        <span>{course.rating || 'New'}</span>
                                    </span>
                                    <span>•</span>
                                    <span>{modules.length} modules</span>
                                    <span>•</span>
                                    <span>{course.duration}</span>
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
                                    {course.level && (
                                        <span className="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                            {course.level}
                                        </span>
                                    )}
                                    {course.format && (
                                        <span className="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">
                                            {course.format}
                                        </span>
                                    )}
                                </div>
                                <div className="text-sm text-gray-500">
                                    Enrolled on {new Date(enrollment?.enrollment_date).toLocaleDateString()}
                                </div>
                            </div>

                            {/* Progress Bar */}
                            <div className="mb-6">
                                <div className="flex justify-between text-sm mb-2">
                                    <span className="font-medium text-gray-700">Your Progress</span>
                                    <span className="text-blue-600 font-medium">{progress}% Complete</span>
                                </div>
                                <div className="h-3 w-full bg-gray-200 rounded-full overflow-hidden">
                                    <div 
                                        className="h-full bg-blue-600 rounded-full transition-all duration-500"
                                        style={{ width: `${progress}%` }}
                                    ></div>
                                </div>
                            </div>
                            
                            {/* Certificate Section - Show when progress is 100% and certificate not generated */}
                            {progress === 100 && !hasCertificate && (
                                <div className="mt-8 bg-white rounded-xl shadow-sm overflow-hidden border-2 border-green-200">
                                    <div className="p-6 bg-gradient-to-r from-green-50 to-emerald-50">
                                        <div className="flex items-start justify-between">
                                            <div className="flex items-start gap-4">
                                                <div className="bg-green-100 rounded-lg p-3">
                                                    <svg className="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h2 className="text-2xl font-bold text-gray-900 mb-2">Congratulations! 🎉</h2>
                                                    <p className="text-gray-600 mb-4">
                                                        You've successfully completed all modules for {course.title}. 
                                                        You're now eligible to receive your certificate of completion.
                                                    </p>
                                                    <div className="flex gap-3">
                                                        <Link
                                                            href={route('dashboard.certificates.generate', { enrollment: enrollment.id })}
                                                            className="inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition"
                                                            method="get"
                                                            as="button"
                                                        >
                                                            <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            Generate Certificate
                                                        </Link>
                                                        <Link
                                                            href={route('dashboard.certificates.preview', { enrollment: enrollment.id })}
                                                            className="inline-flex items-center px-4 py-2 bg-white text-gray-700 font-semibold rounded-lg border border-gray-300 hover:bg-gray-50 transition"
                                                        >
                                                            Preview Certificate
                                                        </Link>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="text-right">
                                                <span className="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    Completed
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            )}

                            {/* Show if certificate already generated */}
                            {hasCertificate && (
                                <div className="mt-8 bg-white rounded-xl shadow-sm overflow-hidden border border-blue-200">
                                    <div className="p-6">
                                        <div className="flex items-center justify-between">
                                            <div className="flex items-center gap-3">
                                                <div className="bg-blue-100 rounded-lg p-2">
                                                    <svg className="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 className="font-semibold text-gray-900">Your Certificate</h3>
                                                    <p className="text-sm text-gray-500">
                                                        Certificate #: {enrollment.certificate_number}
                                                    </p>
                                                    <p className="text-sm text-gray-500">
                                                        Generated on {enrollment.certificate_generated_date ? new Date(enrollment.certificate_generated_date).toLocaleDateString() : 'N/A'}
                                                    </p>
                                                </div>
                                            </div>
                                            <Link
                                                href={route('dashboard.certificates.download', { enrollment: enrollment.id })}
                                                className="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                            >
                                                <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                Download Certificate
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Course Modules */}
                    <div className="space-y-6">
                        {modules.length > 0 ? (
                            modules.map((module, moduleIndex) => (
                                <div key={module.id} className="bg-white rounded-xl shadow-sm overflow-hidden">
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
                                                        className="prose prose-sm max-w-none text-gray-600 mt-2
                                                        prose-headings:font-bold prose-headings:text-gray-900
                                                        prose-p:text-gray-600 prose-p:leading-relaxed
                                                        prose-ul:list-disc prose-ul:pl-5
                                                        prose-li:text-gray-600
                                                        prose-strong:text-gray-900
                                                        prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline"
                                                        dangerouslySetInnerHTML={{ __html: module.short_description}}
                                                    />
                                                )}
                                            </div>
                                            <div className="flex items-center gap-2 text-sm text-gray-500">
                                                <span className="flex items-center gap-1">
                                                    <ClockIcon className="w-4 h-4" />
                                                    {module.estimated_hours} hrs
                                                </span>
                                                <span className="flex items-center gap-1">
                                                    <DocumentTextIcon className="w-4 h-4" />
                                                    {module.lesson_count} lessons
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Module Content */}
                                    <div className="p-6">
                                        {/* Sections */}
                                        {module.sections && module.sections.length > 0 && (
                                            <div className="mb-6">
                                                <h3 className="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">
                                                    Sections
                                                </h3>
                                                <div className="space-y-3">
                                                    {module.sections.map((section) => (
                                                        <div key={section.id} className="bg-gray-50 rounded-lg p-4">
                                                            <h4 className="font-medium text-gray-900 mb-2">
                                                                {section.title}
                                                            </h4>
                                                            {section.content && (
                                                                <p className="text-sm text-gray-600">
                                                                    {section.content}
                                                                </p>
                                                            )}
                                                        </div>
                                                    ))}
                                                </div>
                                            </div>
                                        )}

                                        {/* Lessons */}
                                        {module.lessons && module.lessons.length > 0 && (
                                            <div className="mb-6">
                                                <h3 className="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">
                                                    Lessons
                                                </h3>
                                                <div className="space-y-2">
                                                    {module.lessons.map((lesson, lessonIndex) => (
                                                        <div 
                                                            key={lesson.id} 
                                                            className={`flex items-center gap-3 p-3 rounded-lg transition group ${lesson.is_completed ? 'bg-green-50' : 'hover:bg-gray-50 cursor-pointer'}`}
                                                            onClick={() => !lesson.is_completed && handleLessonClick(lesson)}
                                                        >
                                                            <span className="text-sm text-gray-400 w-6">
                                                                {lessonIndex + 1}.
                                                            </span>
                                                            <div className="flex-1">
                                                                <span className={`font-medium ${lesson.is_completed ? 'text-green-700' : 'text-gray-900'}`}>
                                                                    {lesson.title}
                                                                </span>
                                                                {lesson.description && (
                                                                    <p className="text-sm text-gray-500">
                                                                        {lesson.description}
                                                                    </p>
                                                                )}
                                                            </div>
                                                            {lesson.duration && (
                                                                <span className="text-sm text-gray-400 flex items-center gap-1">
                                                                    <ClockIcon className="w-4 h-4" />
                                                                    {lesson.duration}
                                                                </span>
                                                            )}
                                                            {lesson.is_completed ? (
                                                                <span className="text-green-600 font-bold flex items-center">
                                                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                    Completed
                                                                </span>
                                                            ) : (
                                                                <span className="text-blue-600 text-sm opacity-0 group-hover:opacity-100 transition">
                                                                    Click to complete
                                                                </span>
                                                            )}
                                                        </div>
                                                    ))}
                                                </div>
                                            </div>
                                        )}

                                        {/* Materials */}
                                        {module.materials && module.materials.length > 0 && (
                                            <div>
                                                <h3 className="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">
                                                    Materials
                                                </h3>
                                                <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    {module.materials.map((material) => {
                                                        const Icon = getMaterialIcon(material.type);
                                                        return (
                                                            <a
                                                                key={material.id}
                                                                href={material.file_path || material.url}
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition"
                                                            >
                                                                <Icon className="w-5 h-5 text-blue-600" />
                                                                <span className="text-sm text-gray-900">
                                                                    {material.title}
                                                                </span>
                                                            </a>
                                                        );
                                                    })}
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="bg-white rounded-xl shadow-sm p-12 text-center">
                                <div className="text-gray-400 mb-2 text-4xl">📚</div>
                                <h3 className="text-lg font-medium text-gray-900 mb-1">No modules yet</h3>
                                <p className="text-gray-500">This course content is being prepared.</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}