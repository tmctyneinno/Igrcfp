import React from 'react';
import { ClockIcon } from '@heroicons/react/24/outline';
import { CheckCircleIcon as CheckCircleSolid } from '@heroicons/react/24/solid';

export default function LessonItem({ lesson, isCompleting, onComplete, onIncomplete }) {
    return (
        <div className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <div className="flex items-center gap-3">
                <input
                    type="checkbox"
                    checked={lesson.completed || false}
                    onChange={(e) => {
                        if (e.target.checked) {
                            onComplete(lesson.id);
                        } else {
                            onIncomplete(lesson.id);
                        }
                    }}
                    disabled={isCompleting}
                    className="w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                />
                <div>
                    <span className={`text-sm ${lesson.completed ? 'text-gray-500 line-through' : 'text-gray-900'}`}>
                        {lesson.title}
                    </span>
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
                {lesson.completed && (
                    <CheckCircleSolid className="w-4 h-4 text-green-500" />
                )}
            </div>
        </div>
    );
}