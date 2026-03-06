import React from "react";
import { Link } from "@inertiajs/react";
import { motion } from 'framer-motion';

export default function MyLearning({ enrolledCourses = [] }) {
    // Helper function to get level badge color
    const getLevelBadgeColor = (level) => {
        const levelMap = {
            'Beginner': 'bg-blue-100 text-blue-800',
            'Intermediate': 'bg-amber-100 text-amber-800',
            'Advanced': 'bg-emerald-100 text-emerald-800',
            'Expert': 'bg-purple-100 text-purple-800'
        };  
        return levelMap[level] || 'bg-blue-100 text-blue-800';
    };

    // Helper function to get format badge color
    const getFormatBadgeColor = (format) => {
        const formatMap = {
            'Reading Materials': 'bg-teal-100 text-teal-800',
            'Video Series': 'bg-indigo-100 text-indigo-800',
            'Interactive Workshop': 'bg-pink-100 text-pink-800',
            'Live Sessions': 'bg-red-100 text-red-800',
            'Self-Paced': 'bg-gray-100 text-gray-800'
        };
        return formatMap[format] || 'bg-gray-100 text-gray-800';
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

    // Get clean description
    const getCleanDescription = (course) => {
        const text = course?.short_description || course?.description;
        if (!text) return 'No description available';
        
        const cleanText = text.replace(/<\/?[^>]+(>|$)/g, "");
        return cleanText.length > 100 ? cleanText.substring(0, 60) + '...' : cleanText;
    };

    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            {/* HEADER */}
            <div className="flex flex-col md:flex-row md:justify-between md:items-center mb-12">
                <div>
                    <div className="relative inline-flex items-center mb-3">
                        <motion.span
                            initial={{ width: 0 }}
                            whileInView={{ width: 64 }}
                            transition={{ duration: 0.5, ease: "easeOut" }}
                            viewport={{ once: true }}
                            className="absolute left-0 top-1/2 h-px bg-gray-300"
                        />
                        <span className="text-sm tracking-widest text-gray-400 pl-20 uppercase">
                            My Learning Journey
                        </span>
                    </div>
                    <h2 className="text-3xl font-bold text-gray-900 mt-2">
                        My Courses
                    </h2> 
                    <p className="text-gray-600 mt-3 max-w-2xl">
                        Continue your learning journey. Track your progress and pick up where you left off.
                    </p>
                </div>

                {enrolledCourses.length > 0 && (
                    <Link
                        href={route('dashboard.my-courses')}
                        className="mt-6 md:mt-0 text-blue-950 font-semibold hover:text-blue-700 transition"
                    >
                        View All Courses →
                    </Link>
                )}
            </div>

            {/* COURSE CARDS */}
            {enrolledCourses.length > 0 ? (
                <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                    {enrolledCourses.map((course, index) => {
                        const progress = calculateProgress(course);
                        
                        return (
                            <motion.div
                                key={course.id}
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.3, delay: index * 0.1 }}
                                className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-2"
                                data-aos="fade-up"
                                data-aos-delay={index * 150}
                            >
                                {/* Course Image */}
                                <div className="h-48 w-full overflow-hidden relative">
                                    <img
                                        src={course.image_url || course.banner_image || '/images/default-course.jpg'}
                                        alt={course.title}
                                        className="h-full w-full object-cover hover:scale-110 transition-transform duration-300"
                                        onError={(e) => {
                                            e.target.src = '/images/default-course.jpg';
                                        }}
                                    />
                                    
                                    {/* Progress Badge */}
                                    <div className="absolute top-3 left-3 rounded-full bg-blue-600 px-3 py-1 text-xs font-medium text-white shadow-lg">
                                        {progress}% Complete
                                    </div>
                                    
                                    {/* Status Badge */}
                                    {progress === 100 && (
                                        <div className="absolute top-3 right-3 rounded-full bg-green-500 px-3 py-1 text-xs font-medium text-white shadow-lg">
                                            ✅ Completed
                                        </div>
                                    )}
                                </div>

                                {/* Content */}
                                <div className="p-2">
                                    <Link href={route('dashboard.courses.show', course.slug || course.id)}>
                                        <h4 className="text-lg font-semibold text-gray-900 mb-2 hover:text-blue-900 transition line-clamp-2 h-14">
                                            {course.title}
                                        </h4>
                                    </Link>

                                    {/* Description */}
                                    <p className="text-gray-600 text-sm mb-3 line-clamp-2 h-10">
                                        {getCleanDescription(course)}
                                    </p>

                                    {/* Badges */}
                                    <div className="flex flex-wrap gap-2 mb-3">
                                        {course.level && (
                                            <span className={`text-xs font-semibold px-3 py-1 rounded-full ${getLevelBadgeColor(course.level)}`}>
                                                {course.level}
                                            </span>
                                        )}
                                        {course.format && (
                                            <span className={`text-xs font-semibold px-3 py-1 rounded-full ${getFormatBadgeColor(course.format)}`}>
                                                {course.format}
                                            </span>
                                        )}
                                        {/* Add Category Badge */}
                                        {course.category && (
                                            <Link 
                                                href={route('courses.by-category', { slug: course.category.slug })}
                                                className="text-xs font-semibold px-3 py-1 rounded-full bg-purple-100 text-purple-800 hover:bg-purple-200 transition"
                                            >
                                                {course.category.icon && <span className="mr-1">{course.category.icon}</span>}
                                                {course.category.name}
                                            </Link>
                                        )}
                                    </div>

                                    {/* Course Metadata */}
                                    <div className="flex items-center justify-between mb-3 text-sm text-gray-500">
                                        {course.duration && (
                                            <span className="flex items-center gap-1">
                                                ⏱️ {course.duration}
                                            </span>
                                        )}
                                        {course.modules_count > 0 && (
                                            <span className="flex items-center gap-1">
                                                📚 {course.modules_count} {course.modules_count === 1 ? 'module' : 'modules'}
                                            </span>
                                        )}
                                    </div>

                                    {/* Progress Bar - Kept as requested */}
                                    <div className="mb-4">
                                        <div className="flex justify-between text-xs text-gray-600 mb-1">
                                            <span>Progress</span>
                                            <span className="font-medium">{progress}%</span>
                                        </div>
                                        <div className="h-2 w-full rounded-full bg-gray-200">
                                            <motion.div 
                                                initial={{ width: 0 }}
                                                animate={{ width: `${progress}%` }}
                                                transition={{ duration: 0.5, delay: 0.2 }}
                                                className="h-2 rounded-full bg-blue-900"
                                            ></motion.div>
                                        </div>
                                    </div>

                                    {/* Continue Button */}
                                    <Link
                                        href={route('dashboard.courses.show', course.slug || course.id)}
                                        className="block w-full rounded-lg bg-blue-900 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 transition-colors duration-200 transform hover:-translate-y-1"
                                    >
                                        {progress === 0 ? '🚀 Start Learning' : progress === 100 ? '🔄 Review Course' : '▶️ Continue Learning'}
                                    </Link>
                                </div>
                            </motion.div>
                        );
                    })}
                </div>
            ) : (
                // Empty State
                <motion.div
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="col-span-4 text-center py-16 bg-white rounded-xl shadow-md"
                >
                    <div className="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-blue-100">
                        <span className="text-3xl">📚</span>
                    </div>
                    <h3 className="text-xl font-semibold text-gray-900 mb-2">No courses yet</h3>
                    <p className="text-gray-600 mb-6 max-w-md mx-auto">
                        You haven't enrolled in any courses yet. Start your learning journey today!
                    </p>
                    <Link
                        href={route('courses.index')}
                        className="inline-flex items-center px-6 py-3 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-700 transition transform hover:-translate-y-1"
                    >
                        Browse Courses
                        <svg className="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </Link>
                </motion.div>
            )}
        </div>
    );
}