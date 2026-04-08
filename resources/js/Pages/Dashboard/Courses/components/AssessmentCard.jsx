import React, { useState } from 'react';
import { motion } from 'framer-motion';
import { ClockIcon, DocumentTextIcon, PlayCircleIcon, ArrowPathIcon, ExclamationTriangleIcon } from '@heroicons/react/24/outline';
import { SparklesIcon, ClipboardDocumentCheckIcon, AcademicCapIcon, ShieldCheckIcon } from '@heroicons/react/24/outline';

const iconMap = {
    'quiz': SparklesIcon,
    'module_assessment': ClipboardDocumentCheckIcon,
    'final_exam': AcademicCapIcon,
    'diploma': ShieldCheckIcon
};

const colorMap = {
    'quiz': 'bg-green-100 text-green-800 border-green-200',
    'module_assessment': 'bg-blue-100 text-blue-800 border-blue-200',
    'final_exam': 'bg-purple-100 text-purple-800 border-purple-200',
    'diploma': 'bg-indigo-100 text-indigo-800 border-indigo-200'
};

const statusBadgeMap = {
    'not_started': 'bg-gray-100 text-gray-800',
    'pending': 'bg-yellow-100 text-yellow-800',
    'in_progress': 'bg-blue-100 text-blue-800',
    'completed': 'bg-green-100 text-green-800',
    'graded': 'bg-indigo-100 text-indigo-800',
    'expired': 'bg-red-100 text-red-800'
};

export default function AssessmentCard({ 
    assessment, 
    type, 
    isProcessing, 
    isIdentityVerified, 
    onStart, 
    onContinue, 
    onReview 
}) {
    const [showSubItems, setShowSubItems] = useState(false);
    const status = assessment.status || 'not_started';
    const isGrouped = assessment.quiz_ids && assessment.quiz_ids.length > 1;
    const IconComponent = iconMap[type] || DocumentTextIcon;
    const colorClass = colorMap[type] || 'bg-gray-100 text-gray-800 border-gray-200';

    const formatTimeRemaining = (dueDate) => {
        if (!dueDate) return null;
        
        const now = new Date();
        const due = new Date(dueDate);
        const diffMs = due - now;
        
        if (diffMs <= 0) return 'Expired';
        
        const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
        const diffHours = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        
        if (diffDays > 0) return `${diffDays} day${diffDays > 1 ? 's' : ''} remaining`;
        if (diffHours > 0) return `${diffHours} hour${diffHours > 1 ? 's' : ''} remaining`;
        return 'Less than an hour remaining';
    };

    const timeRemaining = assessment.due_date ? formatTimeRemaining(assessment.due_date) : null;

    return (
        <motion.div
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            className={`border rounded-xl overflow-hidden transition-all hover:shadow-md ${colorClass}`}
        >
            <div className="p-5">
                <div className="flex items-center justify-between mb-3">
                    <div className="flex items-center gap-2">
                        <span className="p-1.5 bg-white rounded-lg shadow-sm">
                            <IconComponent className="w-5 h-5" />
                        </span>
                        <span className="font-medium text-sm capitalize">
                            {type.replace('_', ' ')}
                            {assessment.module_number && ` - Module ${assessment.module_number}`}
                        </span>
                    </div>
                    <span className={`px-3 py-1 rounded-full text-xs font-medium ${statusBadgeMap[status]}`}>
                        {status.replace('_', ' ')}
                    </span>
                </div>
                
                <h4 className="font-semibold text-gray-900 mb-2">{assessment.title}</h4>
                <p className="text-sm text-gray-600 mb-4 line-clamp-2">
                    {assessment.description || 'No description provided'}
                </p>
                
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
                    {assessment.passing_score > 0 && (
                        <span>Pass: {assessment.passing_score}%</span>
                    )}
                    {isGrouped && (
                        <span className="flex items-center gap-1 text-blue-600">
                            <DocumentTextIcon className="w-4 h-4" />
                            {assessment.quiz_ids.length} quizzes
                        </span>
                    )}
                </div>
                
                {timeRemaining && status === 'in_progress' && (
                    <div className="mb-4 p-2 bg-yellow-50 rounded-lg flex items-center gap-2 text-xs text-yellow-700">
                        <ExclamationTriangleIcon className="w-4 h-4 flex-shrink-0" />
                        <span>{timeRemaining}</span>
                    </div>
                )}
                
                {assessment.score !== undefined && (
                    <div className="mb-4 p-3 bg-green-50 rounded-lg text-center">
                        <div className="text-2xl font-bold text-green-700">{assessment.score}%</div>
                        <div className="text-xs text-green-600">
                            {assessment.passed ? 'Passed' : 'Failed'} • Passing: {assessment.passing_score}%
                        </div>
                    </div>
                )}
                
                <div className="flex gap-2">
                    {status === 'not_started' && (
                        <button
                            onClick={() => onStart(isGrouped ? assessment.quiz_ids[0] : assessment.id, type)}
                            disabled={isProcessing || (type === 'final_exam' && !isIdentityVerified)}
                            className="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {isProcessing ? <ArrowPathIcon className="w-4 h-4 animate-spin" /> : <PlayCircleIcon className="w-4 h-4" />}
                            {isProcessing ? 'Starting...' : isGrouped ? 'Start Module Quiz' : 'Start Assessment'}
                        </button>
                    )}
                    
                    {status === 'in_progress' && (
                        <button
                            onClick={() => onContinue(isGrouped ? assessment.quiz_ids[0] : assessment.id, type)}
                            disabled={isProcessing}
                            className="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
                        >
                            {isProcessing ? <ArrowPathIcon className="w-4 h-4 animate-spin" /> : <PlayCircleIcon className="w-4 h-4" />}
                            {isProcessing ? 'Continuing...' : 'Continue'}
                        </button>
                    )}
                    
                    {status === 'completed' && (
                        <button
                            onClick={() => onReview(isGrouped ? assessment.quiz_ids[assessment.quiz_ids.length - 1] : assessment.id, type)}
                            className="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition"
                        >
                            <DocumentTextIcon className="w-4 h-4" />
                            Review
                        </button>
                    )}
                    
                    {status === 'graded' && (
                        <button
                            onClick={() => onReview(assessment.id, type)}
                            className="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition"
                        >
                            <DocumentTextIcon className="w-4 h-4" />
                            View Results
                        </button>
                    )}
                </div>
                
                {type === 'diploma' && status === 'completed' && (
                    <div className="mt-3 text-xs text-center text-amber-600 bg-amber-50 p-2 rounded-lg">
                        ⏳ Awaiting manual review by instructors
                    </div>
                )}
            </div>
        </motion.div>
    );
}