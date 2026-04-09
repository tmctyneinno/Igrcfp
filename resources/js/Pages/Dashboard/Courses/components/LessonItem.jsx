import React, { useState, useEffect, useRef } from 'react';
import { ClockIcon, PlayCircleIcon, DocumentTextIcon } from '@heroicons/react/24/outline';
import { CheckCircleIcon as CheckCircleSolid } from '@heroicons/react/24/solid';

export default function LessonItem({ 
    lesson, 
    moduleId,
    isCompleting, 
    onComplete, 
    onIncomplete
}) {
    console.log('LessonItem render:', { id: lesson.id, title: lesson.title, completed: lesson.completed });
    
    const [localCompleted, setLocalCompleted] = useState(Boolean(lesson.completed));
  
    // Sync with prop changes
    useEffect(() => {
        setLocalCompleted(Boolean(lesson.completed));
    }, [lesson.completed]);

    const getLessonTypeIcon = (type) => {
        if (type === 'video') return <PlayCircleIcon className="w-4 h-4" />;
        if (type === 'reading') return <DocumentTextIcon className="w-4 h-4" />;
        return <DocumentTextIcon className="w-4 h-4" />;
    };

    const handleManualToggle = (e) => {
        const checked = e.target.checked;
        console.log('LessonItem toggle:', { lessonId: lesson.id, moduleId, checked });
        
        if (checked) {
            setLocalCompleted(true);
            // Call onComplete with (lessonId, moduleId, metadata)
            onComplete(lesson.id, moduleId, { manualComplete: true });
        } else {
            setLocalCompleted(false);
            // Call onIncomplete with (lessonId, moduleId)
            onIncomplete(lesson.id, moduleId);
        }
    };

    return (
        <div className={`flex items-center justify-between p-3 rounded-lg transition ${
            localCompleted ? 'bg-green-50 border border-green-200' : 
            lesson.isActive ? 'bg-blue-50 border border-blue-200' : 
            'bg-gray-50 hover:bg-gray-100'
        }`}>
            <div className="flex items-center gap-3 flex-1">
                <input
                    type="checkbox"
                    checked={localCompleted}
                    onChange={handleManualToggle}
                    disabled={isCompleting}
                    className="w-4 h-4 text-blue-600 rounded focus:ring-blue-500 disabled:opacity-50"
                />
                
                <div className="flex-1">
                    <div className="flex items-center gap-2">
                        <span className={`text-sm ${localCompleted ? 'text-gray-500 line-through' : 'text-gray-900'}`}>
                            {lesson.title}
                        </span>
                        <span className="text-gray-400">
                            {getLessonTypeIcon(lesson.lesson_type)}
                        </span>
                        {lesson.isActive && !localCompleted && (
                            <span className="text-xs text-blue-600 bg-blue-100 px-2 py-0.5 rounded-full animate-pulse">
                                Viewing
                            </span>
                        )}
                    </div>
                    {lesson.description && (
                        <p className="text-xs text-gray-500 mt-0.5">{lesson.description}</p>
                    )}
                </div>
            </div>
            
            <div className="flex items-center gap-3">
                {lesson.duration && (
                    <span className="text-xs text-gray-500 flex items-center gap-1">
                        <ClockIcon className="w-3 h-3" />
                        {lesson.duration} min
                    </span>
                )}
                {isCompleting && (
                    <div className="w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full animate-spin" />
                )}
                {localCompleted && !isCompleting && (
                    <CheckCircleSolid className="w-4 h-4 text-green-500" />
                )}
            </div>
        </div>
    );
}