import React from 'react';
import { motion } from 'framer-motion';
import { SparklesIcon, ClipboardDocumentCheckIcon, AcademicCapIcon, ShieldCheckIcon } from '@heroicons/react/24/outline';
import AssessmentCard from './AssessmentCard';

const iconMap = {
    'quiz': { icon: SparklesIcon, color: 'text-green-600' },
    'module': { icon: ClipboardDocumentCheckIcon, color: 'text-blue-600' },
    'final': { icon: AcademicCapIcon, color: 'text-purple-600' },
    'diploma': { icon: ShieldCheckIcon, color: 'text-indigo-600' }
};

export default function AssessmentList({ 
    title, 
    icon,  
    assessments = [], 
    type, 
    processingExam, 
    isIdentityVerified, 
    onStart, 
    onContinue, 
    onReview 
}) {
    if (!assessments || assessments.length === 0) return null;

    const { icon: IconComponent, color } = iconMap[icon] || iconMap.quiz;

    return (
        <motion.div
            initial={{ opacity: 0, x: 20 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ delay: 0.1 }}
            className="space-y-3"
        >
            <h3 className="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <IconComponent className={`w-5 h-5 ${color}`} />
                {title}
            </h3>
            <div className="space-y-4">
                {assessments.map(assessment => (
                    <AssessmentCard
                        key={assessment.id}
                        assessment={assessment}
                        type={type}
                        isProcessing={processingExam === assessment.id}
                        isIdentityVerified={isIdentityVerified}
                        onStart={onStart}
                        onContinue={onContinue}
                        onReview={onReview}
                    />
                ))}
            </div>
        </motion.div>
    );
}