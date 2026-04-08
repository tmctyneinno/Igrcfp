import React from 'react';
import LessonItem from './LessonItem';

export default function LessonList({ lessons, completingLesson, markLessonComplete, markLessonIncomplete }) {
    if (!lessons || lessons.length === 0) return null;

    return (
        <div>
            <h4 className="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">
                Lessons
            </h4>
            <div className="space-y-2">
                {lessons.map((lesson) => (
                    <LessonItem
                        key={lesson.id}
                        lesson={lesson}
                        isCompleting={completingLesson === lesson.id}
                        onComplete={markLessonComplete}
                        onIncomplete={markLessonIncomplete}
                    />
                ))}
            </div>
        </div>
    );
}