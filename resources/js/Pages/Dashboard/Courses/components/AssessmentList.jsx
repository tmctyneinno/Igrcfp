import React from 'react';
import { motion } from 'framer-motion';
import { PlayCircleIcon, LockClosedIcon, CheckCircleIcon } from '@heroicons/react/24/outline';

export default function AssessmentList({ 
    title,
    assessments = [], 
    type, 
    processingExam, 
    onStart,
    onContinue,
    onReview
}) {
    if (!assessments || assessments.length === 0) return null;

    // Check if ALL module quizzes are unlocked
    const allQuizzesUnlocked = assessments.every(q => q.unlocked === true);
    
    // Check if any quiz is completed
    const hasCompletedQuiz = assessments.some(q => q.status === 'completed' || q.status === 'graded');
    
    // Get the FIRST QUIZ'S ACTUAL ID (not the grouped ID)
    // If it's a grouped quiz with quiz_ids array, use the first real quiz ID
    const getActualQuizId = () => {
        const firstAssessment = assessments[0];
        // If it has quiz_ids array, use the first real quiz ID
        if (firstAssessment?.quiz_ids && firstAssessment.quiz_ids.length > 0) {
            return firstAssessment.quiz_ids[0];
        }
        // Otherwise use the assessment id
        return firstAssessment?.id;
    };
    
    const courseQuizId = getActualQuizId();
    
    // Find the reason why quiz is locked
    const lockedQuiz = assessments.find(q => !q.unlocked);
    const lockReason = lockedQuiz?.reason || 'Complete all lessons first';

    // Determine button state
    const getButtonState = () => {
        if (!allQuizzesUnlocked) {
            return {
                text: lockReason,
                icon: LockClosedIcon,
                disabled: true,
                className: 'bg-gray-100 text-gray-500 cursor-not-allowed',
                action: null
            };
        }
        
        if (hasCompletedQuiz) {
            const completedQuiz = assessments.find(q => q.status === 'completed' || q.status === 'graded');
            return {
                text: completedQuiz?.passed ? '✓ Quiz Completed' : 'Review Quiz',
                icon: CheckCircleIcon,
                disabled: false,
                className: completedQuiz?.passed 
                    ? 'bg-green-100 text-green-700 cursor-default' 
                    : 'bg-green-600 text-white hover:bg-green-700',
                action: () => onReview(courseQuizId, type)
            };
        }
        
        // Check if any quiz is in progress
        const inProgressQuiz = assessments.find(q => q.status === 'in_progress');
        if (inProgressQuiz) {
            return {
                text: 'Continue Quiz',
                icon: PlayCircleIcon,
                disabled: processingExam,
                className: 'bg-amber-500 text-white hover:bg-amber-600',
                action: () => onContinue(courseQuizId, type)
            };
        }
        
        return {
            text: 'Take Course Quiz',
            icon: PlayCircleIcon,
            disabled: processingExam,
            className: 'bg-blue-600 text-white hover:bg-blue-700',
            action: () => onStart(courseQuizId, type)
        };
    };

    const buttonState = getButtonState();
    const ButtonIcon = buttonState.icon;

    // Calculate overall progress for quiz unlock
    const totalModules = assessments.length;
    const unlockedModules = assessments.filter(q => q.unlocked).length;
    const unlockProgress = totalModules > 0 ? Math.round((unlockedModules / totalModules) * 100) : 0;

    return (
        <motion.div
            initial={{ opacity: 0, x: 20 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ delay: 0.1 }}
            className="bg-white rounded-xl border border-gray-200 p-5"
        >
            <h3 className="text-lg font-semibold text-gray-900 mb-4">
                {title || 'Course Quiz'}
            </h3>
            
            {/* Progress towards unlocking */}
            {!allQuizzesUnlocked && (
                <div className="mb-4">
                    <div className="flex justify-between text-xs text-gray-500 mb-1">
                        <span>Progress to unlock quiz</span>
                        <span>{unlockedModules}/{totalModules} modules</span>
                    </div>
                    <div className="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div 
                            className="h-full bg-blue-600 rounded-full transition-all duration-300"
                            style={{ width: `${unlockProgress}%` }}
                        />
                    </div>
                    <p className="text-xs text-gray-500 mt-2">
                        Complete all lessons in each module to unlock the quiz
                    </p>
                </div>
            )}
            
            {/* Quiz Info */}
            {allQuizzesUnlocked && !hasCompletedQuiz && (
                <div className="mb-4 text-sm text-gray-600">
                    <p>✅ All modules complete! You can now take the course quiz.</p>
                </div>
            )}
            
            {/* Single Action Button */}
            <button
                onClick={buttonState.action}
                disabled={buttonState.disabled}
                className={`w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-medium transition ${
                    buttonState.className
                } ${processingExam ? 'opacity-50 cursor-not-allowed' : ''}`}
            >
                {processingExam ? (
                    <>
                        <svg className="animate-spin h-5 w-5" viewBox="0 0 24 24">
                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" fill="none" />
                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        Loading...
                    </>
                ) : (
                    <>
                        <ButtonIcon className="w-5 h-5" />
                        {buttonState.text}
                    </>
                )} 
            </button>
        </motion.div>
    );
}