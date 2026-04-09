import React, { useState, useRef, useEffect } from 'react';
import { motion } from 'framer-motion';
import { ArrowLeftIcon, CheckCircleIcon, XMarkIcon } from '@heroicons/react/24/outline';

export default function LessonContent({ 
    lesson, 
    module,
    onComplete,
    onClose 
}) {
    const [isCompleting, setIsCompleting] = useState(false);
    const [scrollProgress, setScrollProgress] = useState(0);
    const [hasReachedBottom, setHasReachedBottom] = useState(false);
    const contentRef = useRef(null);

    // Track scroll progress
    useEffect(() => {
        const handleScroll = () => {
            if (contentRef.current) {
                const element = contentRef.current;
                const scrollTop = element.scrollTop;
                const scrollHeight = element.scrollHeight - element.clientHeight;
                const progress = scrollHeight > 0 ? (scrollTop / scrollHeight) * 100 : 0;
                
                setScrollProgress(Math.round(progress));
                
                if (scrollHeight - scrollTop <= 50) {
                    setHasReachedBottom(true);
                }
            }
        };

        const element = contentRef.current;
        if (element) {
            element.addEventListener('scroll', handleScroll);
            return () => element.removeEventListener('scroll', handleScroll);
        }
    }, []);

    const handleMarkComplete = () => {
        setIsCompleting(true);
        onComplete(lesson.id, module.id, { 
            manualComplete: true,
            scrollProgress 
        });
    };

    return (
        <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <motion.div 
                initial={{ opacity: 0, scale: 0.95 }}
                animate={{ opacity: 1, scale: 1 }}
                exit={{ opacity: 0, scale: 0.95 }}
                className="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col"
            >
                {/* Header */}
                <div className="border-b border-gray-200 p-6">
                    <div className="flex items-center justify-between mb-2">
                        <button
                            onClick={onClose}
                            className="flex items-center gap-2 text-gray-500 hover:text-gray-700 transition"
                        >
                            <ArrowLeftIcon className="w-5 h-5" />
                            Back to Module
                        </button>
                        <button
                            onClick={onClose}
                            className="text-gray-400 hover:text-gray-600 transition"
                        >
                            <XMarkIcon className="w-5 h-5" />
                        </button>
                    </div>
                    <h2 className="text-2xl font-bold text-gray-900">{lesson.title}</h2>
                    <div className="flex items-center gap-4 mt-2 text-sm text-gray-500">
                        <span>Module {module.module_number}: {module.title}</span>
                        <span>•</span>
                        <span>{lesson.duration} min read</span>
                    </div>
                    
                    {/* Scroll Progress Bar */}
                    <div className="mt-4">
                        <div className="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Reading Progress</span>
                            <span>{scrollProgress}%</span>
                        </div>
                        <div className="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                            <div 
                                className="h-full bg-blue-600 rounded-full transition-all duration-150"
                                style={{ width: `${scrollProgress}%` }}
                            />
                        </div>
                    </div>
                </div>
                
                {/* Content */}
                <div 
                    ref={contentRef}
                    className="flex-1 overflow-y-auto p-6 prose prose-lg max-w-none"
                >
                    {lesson.content ? (
                        <div dangerouslySetInnerHTML={{ __html: lesson.content }} />
                    ) : (
                        <p className="text-gray-500">No content available for this lesson.</p>
                    )}
                    
                    {hasReachedBottom && !lesson.completed && (
                        <div className="mt-8 p-4 bg-green-50 border border-green-200 rounded-lg text-center">
                            <CheckCircleIcon className="w-8 h-8 text-green-500 mx-auto mb-2" />
                            <p className="text-green-700 font-medium">
                                You've finished reading! Ready to mark as complete?
                            </p>
                        </div>
                    )}
                </div>
                
                {/* Footer */}
                <div className="border-t border-gray-200 p-6">
                    <div className="flex items-center justify-between">
                        <button
                            onClick={onClose}
                            className="px-4 py-2 text-gray-600 hover:text-gray-800 transition"
                        >
                            Close
                        </button>
                        
                        {!lesson.completed && (
                            <button
                                onClick={handleMarkComplete}
                                disabled={isCompleting}
                                className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                            >
                                {isCompleting ? (
                                    <>
                                        <svg className="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" fill="none" />
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                        </svg>
                                        Completing...
                                    </>
                                ) : (
                                    'Mark as Complete'
                                )}
                            </button>
                        )}
                        
                        {lesson.completed && (
                            <div className="flex items-center gap-2 text-green-600">
                                <CheckCircleIcon className="w-5 h-5" />
                                <span>Completed</span>
                            </div>
                        )}
                    </div>
                </div>
            </motion.div>
        </div>
    );
}