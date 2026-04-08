import React from 'react';
import { motion } from 'framer-motion';
import { AcademicCapIcon, ClockIcon } from '@heroicons/react/24/outline';

export default function CourseHeader({ course, enrollment, modulesCount, progress }) {
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

    return (
        <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            className="bg-white rounded-2xl shadow-sm overflow-hidden"
        >
            <div className="h-48 w-full bg-gradient-to-r from-blue-900 to-indigo-900 relative">
                {(course?.image_url || course?.banner_image) && (
                    <img 
                        src={course.image_url || course.banner_image}
                        alt={course.title}
                        className="w-full h-full object-cover opacity-50"
                    />
                )}
                
                <div className="absolute bottom-0 left-0 p-6 text-white">
                    <h1 className="text-2xl font-bold mb-2">{course?.title}</h1>
                    <div className="flex items-center gap-4 text-sm">
                        <span className="flex items-center gap-1">
                            <AcademicCapIcon className="w-4 h-4" />
                            {modulesCount} modules
                        </span>
                        <span>•</span>
                        <span className="flex items-center gap-1">
                            <ClockIcon className="w-4 h-4" />
                            {course?.duration}
                        </span>
                    </div>
                </div>
            </div>

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

                <div className="mb-4">
                    <div className="flex justify-between text-sm mb-2">
                        <span className="font-medium text-gray-700">Overall Course Progress</span>
                        <span className="text-blue-600 font-medium">{progress}% Complete</span>
                    </div>
                    <div className="h-3 w-full bg-gray-200 rounded-full overflow-hidden">
                        <motion.div 
                            initial={{ width: 0 }}
                            animate={{ width: `${progress}%` }}
                            transition={{ duration: 0.5 }}
                            className="h-full bg-blue-600 rounded-full"
                        />
                    </div>
                </div>
            </div>
        </motion.div>
    );
}