import React from "react";
import { Link } from "@inertiajs/react";

export default function MyLearning({ enrolledCourses = [] }) {
    // Helper function to get level badge color
    const getLevelBadgeColor = (level) => {
        const levelMap = {
            'Beginner': 'bg-blue-100 text-blue-700',
            'Intermediate': 'bg-amber-100 text-amber-700',
            'Advanced': 'bg-emerald-100 text-emerald-700',
            'Expert': 'bg-purple-100 text-purple-700'
        };
        return levelMap[level] || 'bg-slate-100 text-slate-700';
    };

    // Helper function to get format badge color
    const getFormatBadgeColor = (format) => {
        const formatMap = {
            'Reading Materials': 'bg-teal-100 text-teal-700',
            'Video Series': 'bg-indigo-100 text-indigo-700',
            'Interactive Workshop': 'bg-pink-100 text-pink-700',
            'Live Sessions': 'bg-red-100 text-red-700',
            'Self-Paced': 'bg-gray-100 text-gray-700'
        };
        return formatMap[format] || 'bg-gray-100 text-gray-700';
    };

    // Calculate progress percentage
    const calculateProgress = (course) => {
        if (course.progress !== undefined) {
            return course.progress;
        }
        if (course.completed_modules && course.modules_count) {
            return Math.round((course.completed_modules / course.modules_count) * 100);
        }
        return 0;
    };

    return (
        <div className="p-6 ">
            {/* Header */}
            <div className="mb-6 flex items-center justify-between">
                <h2 className="text-xl font-semibold text-gray-900">
                    My Learning
                </h2>
                {enrolledCourses.length > 0 && (
                    <Link 
                        href={route('dashboard.my-courses')}
                        className="text-sm font-medium text-blue-600 hover:underline"
                    >
                        View All
                    </Link>
                )}
            </div>

            {/* Course Cards */}
            {enrolledCourses.length > 0 ? (
                <div className="grid grid-cols-1 gap-6 bg-white sm:grid-cols-2 lg:grid-cols-4">
                    {enrolledCourses.map((course) => {
                        const progress = calculateProgress(course);
                        
                        return (
                            <div 
                                key={course.id}
                                className="overflow-hidden rounded-xl border bg-white shadow-sm hover:shadow-md transition-shadow duration-200"
                            >
                                {/* Course Image */}
                                <div className="h-40 w-full overflow-hidden">
                                    <img
                                        src={course.image_url || course.banner_image || '/images/default-course.jpg'}
                                        alt={course.title}
                                        className="h-full w-full object-cover hover:scale-105 transition-transform duration-300"
                                        onError={(e) => {
                                            e.target.src = '/images/default-course.jpg';
                                        }}
                                    />
                                </div>

                                <div className="p-5">
                                    {/* Badges */}
                                    <div className="mb-3 flex flex-wrap gap-2">
                                        {course.level && (
                                            <span className={`rounded-full px-3 py-1 text-xs ${getLevelBadgeColor(course.level)}`}>
                                                {course.level}
                                            </span>
                                        )}
                                        {course.format && (
                                            <span className={`rounded-full px-3 py-1 text-xs ${getFormatBadgeColor(course.format)}`}>
                                                {course.format}
                                            </span>
                                        )}
                                    </div>

                                    {/* Course Title */}
                                    <h3 className="mb-2 font-semibold text-gray-900 line-clamp-2 h-12">
                                        {course.title}
                                    </h3>

                                    {/* Course Description */}
                                    <p className="mb-4 text-sm text-gray-500 line-clamp-3 h-16">
                                        {course.short_description || 'No description available.'}
                                    </p>

                                    {/* Progress Bar */}
                                    <div className="mb-4">
                                        <div className="flex justify-between text-xs text-gray-600 mb-1">
                                            <span>Progress</span>
                                            <span>{progress}%</span>
                                        </div>
                                        <div className="h-2 w-full rounded-full bg-gray-200">
                                            <div 
                                                className="h-2 rounded-full bg-blue-900 transition-all duration-300"
                                                style={{ width: `${progress}%` }}
                                            ></div>
                                        </div>
                                    </div>

                                    {/* Continue Button */}
                                    <Link
                                        // href={route('dashboard.courses.show', { course: course.slug })}
                                        href=""
                                        className="block w-full rounded-lg bg-blue-900 py-2 text-center text-sm font-medium text-white hover:bg-blue-800 transition-colors duration-200"
                                    >
                                        {progress === 0 ? 'Start Learning' : progress === 100 ? 'Review Course' : 'Continue Learning'}
                                    </Link>
                                </div>
                            </div>
                        );
                    })}
                </div>
            ) : (
                <div className="rounded-lg border border-gray-200 bg-white p-8 text-center">
                    <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-100">
                        <span className="text-2xl">📚</span>
                    </div>
                    <h3 className="mb-2 text-lg font-medium text-gray-900">No courses yet</h3>
                    <p className="mb-6 text-gray-600 max-w-md mx-auto">
                        You haven't enrolled in any courses yet. Browse our catalog to start your learning journey.
                    </p>
                    <Link
                        href={route('courses.index')}
                        className="inline-flex items-center rounded-lg bg-blue-900 px-6 py-3 text-sm font-medium text-white hover:bg-blue-700 transition-colors duration-200"
                    >
                        Browse Courses
                        <svg className="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </Link>
                </div>
            )}
        </div>
    );
}