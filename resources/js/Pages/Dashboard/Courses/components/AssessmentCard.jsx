import React, { useState } from 'react';
import { motion } from 'framer-motion';
import { 
    ClockIcon, DocumentTextIcon, PlayCircleIcon, 
    ArrowPathIcon, ExclamationTriangleIcon, LockClosedIcon,
    CheckCircleIcon
} from '@heroicons/react/24/outline';
import { SparklesIcon, ClipboardDocumentCheckIcon, AcademicCapIcon, ShieldCheckIcon } from '@heroicons/react/24/outline';

const iconMap = {
    'quiz': SparklesIcon,
    'module_assessment': ClipboardDocumentCheckIcon,
    'final_exam': AcademicCapIcon,
    'diploma': ShieldCheckIcon
};

const colorMap = {
    'quiz': { card: 'bg-green-50 border-green-200', badge: 'text-green-800', button: 'bg-green-600 hover:bg-green-700' },
    'module_assessment': { card: 'bg-blue-50 border-blue-200', badge: 'text-blue-800', button: 'bg-blue-600 hover:bg-blue-700' },
    'final_exam': { card: 'bg-purple-50 border-purple-200', badge: 'text-purple-800', button: 'bg-purple-600 hover:bg-purple-700' },
    'diploma': { card: 'bg-indigo-50 border-indigo-200', badge: 'text-indigo-800', button: 'bg-indigo-600 hover:bg-indigo-700' }
};

const statusBadgeMap = {
    'not_started': 'bg-gray-100 text-gray-600',
    'in_progress': 'bg-blue-100 text-blue-700',
    'completed': 'bg-green-100 text-green-700',
    'graded': 'bg-indigo-100 text-indigo-700',
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
    const status = assessment.status || 'not_started';
    const isLocked = !assessment.unlocked;
    const isGrouped = assessment.quiz_ids && assessment.quiz_ids.length > 1;
    const IconComponent = iconMap[type] || DocumentTextIcon;
    const colors = colorMap[type] || colorMap.quiz;
    const isCompleted = status === 'completed' || status === 'graded';
    const needsIdentity = (type === 'final_exam' || type === 'diploma') && !isIdentityVerified;

    return (
        <motion.div
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            className={`border rounded-xl overflow-hidden transition-all ${
                isLocked 
                    ? 'bg-gray-50 border-gray-200 opacity-80' 
                    : colors.card
            }`}
        >
            <div className="p-5">
                {/* Header */}
                <div className="flex items-start justify-between mb-3">
                    <div className="flex items-center gap-2">
                        <span className="p-1.5 bg-white rounded-lg shadow-sm">
                            {isLocked 
                                ? <LockClosedIcon className="w-5 h-5 text-gray-400" />
                                : <IconComponent className="w-5 h-5" />
                            }
                        </span>
                        <div>
                            <h4 className="font-semibold text-gray-900 text-sm">{assessment.title}</h4>
                            {assessment.module_name && (
                                <p className="text-xs text-gray-500">{assessment.module_name}</p>
                            )}
                        </div>
                    </div>
                    
                    {isCompleted && (
                        <CheckCircleIcon className="w-5 h-5 text-green-500 flex-shrink-0" />
                    )}
                </div>

                {/* Stats row */}
                <div className="flex flex-wrap gap-3 text-xs text-gray-500 mb-4">
                    {assessment.duration > 0 && (
                        <span className="flex items-center gap-1">
                            <ClockIcon className="w-3.5 h-3.5" />
                            {assessment.duration} mins
                        </span>
                    )}
                    {assessment.questions_count > 0 && (
                        <span className="flex items-center gap-1">
                            <DocumentTextIcon className="w-3.5 h-3.5" />
                            {assessment.questions_count} questions
                        </span>
                    )}
                    {assessment.passing_score > 0 && (
                        <span>Pass: {assessment.passing_score}%</span>
                    )}
                </div>

                {/* Score if completed */}
                {assessment.score !== null && assessment.score !== undefined && (
                    <div className="mb-4 p-3 bg-white rounded-lg border text-center">
                        <div className={`text-2xl font-bold ${assessment.passed ? 'text-green-600' : 'text-red-600'}`}>
                            {assessment.score}%
                        </div>
                        <div className="text-xs text-gray-500">
                            {assessment.passed ? '✅ Passed' : '❌ Failed'} · Pass mark: {assessment.passing_score}%
                        </div>
                    </div>
                )}

                {/* Locked state */}
                {isLocked && (
                    <div className="mb-4">
                        <div className="flex items-center gap-2 text-xs text-orange-600 bg-orange-50 border border-orange-200 rounded-lg p-3">
                            <LockClosedIcon className="w-4 h-4 flex-shrink-0" />
                            <span>{assessment.reason}</span>
                        </div>
                        {assessment.progress !== undefined && (
                            <div className="mt-2">
                                <div className="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>Module progress</span>
                                    <span>{assessment.progress}%</span>
                                </div>
                                <div className="h-1.5 bg-gray-200 rounded-full">
                                    <div 
                                        className="h-1.5 bg-orange-400 rounded-full transition-all"
                                        style={{ width: `${assessment.progress}%` }}
                                    />
                                </div>
                            </div>
                        )}
                    </div>
                )}

                {/* Identity verification warning */}
                {!isLocked && needsIdentity && (
                    <div className="mb-4 flex items-center gap-2 text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg p-3">
                        <ExclamationTriangleIcon className="w-4 h-4 flex-shrink-0" />
                        <span>Identity verification required before starting</span>
                    </div>
                )}

                {/* Action buttons */}
                {!isLocked && (
                    <div className="flex gap-2">
                        {status === 'not_started' && (
                            <button
                                onClick={() => onStart(isGrouped ? assessment.quiz_ids[0] : assessment.id, type)}
                                disabled={isProcessing || needsIdentity}
                                className={`flex-1 flex items-center justify-center gap-2 px-4 py-2.5 text-white text-sm font-medium rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed ${colors.button}`}
                            >
                                {isProcessing 
                                    ? <ArrowPathIcon className="w-4 h-4 animate-spin" /> 
                                    : <PlayCircleIcon className="w-4 h-4" />
                                }
                                {isProcessing ? 'Starting...' : 'Take Quiz'}
                            </button>
                        )}

                        {status === 'in_progress' && (
                            <button
                                onClick={() => onContinue(isGrouped ? assessment.quiz_ids[0] : assessment.id, type)}
                                disabled={isProcessing}
                                className="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
                            >
                                {isProcessing 
                                    ? <ArrowPathIcon className="w-4 h-4 animate-spin" /> 
                                    : <PlayCircleIcon className="w-4 h-4" />
                                }
                                Continue Quiz
                            </button>
                        )}

                        {(status === 'completed' || status === 'graded') && (
                            <button
                                onClick={() => onReview(isGrouped ? assessment.quiz_ids[assessment.quiz_ids.length - 1] : assessment.id, type)}
                                className="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition"
                            >
                                <DocumentTextIcon className="w-4 h-4" />
                                Review Results
                            </button>
                        )}
                    </div>
                )}

                {/* Diploma manual review notice */}
                {type === 'diploma' && isCompleted && (
                    <div className="mt-3 text-xs text-center text-amber-600 bg-amber-50 p-2 rounded-lg">
                        ⏳ Awaiting manual review by instructors
                    </div>
                )}
            </div>
        </motion.div>
    );
}