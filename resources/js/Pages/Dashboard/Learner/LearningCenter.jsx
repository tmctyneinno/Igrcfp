import React, { useRef, useState } from "react";

export default function LearningCenter({ enrolledCourses = [] }) {
    // Calculate dynamic stats
    const calculateStats = () => {
        const totalCourses = enrolledCourses.length;
        const completedCourses = enrolledCourses.filter(course => course.progress === 100).length;
        const ongoingCourses = totalCourses - completedCourses;
        
        // Calculate total hours (assuming each course has duration in hours)
        const totalHours = enrolledCourses.reduce((sum, course) => {
            return sum + (parseInt(course.duration) || 0);
        }, 0); 
        
        return {
            totalCourses,
            completedCourses,
            ongoingCourses,
            totalHours,
            certificates: completedCourses // Assuming each completed course gives a certificate
        };
    };

    const stats = calculateStats();

    return (
        <div className="p-6 shadow-sm bg-white sm:rounded-lg">
            {/* Header */}
            <div className="mb-6">
                <h1 className="text-2xl font-semibold text-gray-900">
                    Learning Center
                </h1>
                <p className="mt-1 text-gray-500">
                    Advance your GRCFP expertise with industry-leading courses and certifications.
                </p>
            </div>

            {/* Stats Cards */}
            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                {/* Courses Completed */}
                <div className="rounded-xl bg-slate-50 p-6 text-center">
                    <div className="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-slate-200">
                        <span className="text-xl">📚</span>
                    </div>
                    <div className="text-3xl font-bold text-gray-900">{stats.completedCourses}</div>
                    <div className="mt-1 text-sm text-gray-600">
                        Courses Completed
                    </div>
                </div>

                {/* Ongoing Courses */}
                <div className="rounded-xl bg-slate-50 p-6 text-center">
                    <div className="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-slate-200">
                        <span className="text-xl">🎓</span>
                    </div>
                    <div className="text-3xl font-bold text-gray-900">{stats.ongoingCourses}</div>
                    <div className="mt-1 text-sm text-gray-600">
                        Ongoing Courses
                    </div>
                </div>

                {/* Hours Learned */}
                <div className="rounded-xl bg-orange-50 p-6 text-center">
                    <div className="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-orange-100">
                        <span className="text-xl">⏱️</span>
                    </div>
                    <div className="text-3xl font-bold text-orange-500">{stats.totalHours}</div>
                    <div className="mt-1 text-sm text-orange-600">
                        Hours Learned
                    </div>
                </div>

                {/* Certificates Earned */}
                <div className="rounded-xl bg-teal-50 p-6 text-center">
                    <div className="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-teal-100">
                        <span className="text-xl">🏆</span>
                    </div>
                    <div className="text-3xl font-bold text-teal-600">{stats.certificates}</div>
                    <div className="mt-1 text-sm text-teal-700">
                        Certificates Earned
                    </div>
                </div>
            </div>
        </div>
    );
}