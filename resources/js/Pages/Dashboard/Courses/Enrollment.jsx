import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { 
    BookOpenIcon, 
    ClockIcon, 
    DocumentTextIcon,
    VideoCameraIcon,
    PresentationChartBarIcon
} from '@heroicons/react/24/outline';

export default function EnrollmentIndex({ course, enrollment, modules = [] }) {
    // Calculate progress
    const progress = enrollment?.progress || 0;
    
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
                                                    <p className="text-gray-600 mt-2">
                                                        <div 
                                                            className="
                                                            prose-headings:font-bold prose-headings:text-gray-900
                                                            prose-p:text-gray-600 prose-p:leading-relaxed
                                                            prose-ul:list-disc prose-ul:pl-5
                                                            prose-li:text-gray-600
                                                            prose-strong:text-gray-900
                                                            prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline
                                                            "
                                                            dangerouslySetInnerHTML={{ __html: module.short_description}}
                                                        />
                                                    </p> 
                                                )}
                                            </div>
                                            <div className="flex items-center gap-2 text-sm text-gray-500">
                                                <span className="flex items-center gap-1 w-50">
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
                                                            className="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition group cursor-pointer"
                                                        >
                                                            <span className="text-sm text-gray-400 w-6">
                                                                {lessonIndex + 1}.
                                                            </span>
                                                            <div className="flex-1">
                                                                <span className="text-gray-900 font-medium">
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
                                                            {lesson.is_completed && (
                                                                <span className="text-green-600 font-bold">✓</span>
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