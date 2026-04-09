import React, { useState } from 'react';
import LessonItem from './LessonItem';
import LessonContent from './LessonContent';

export default function LessonList({ 
    lessons, 
    module,
    completingLesson, 
    markLessonComplete, 
    markLessonIncomplete 
}) {
    const [activeLesson, setActiveLesson] = useState(null);

    const handleLessonClick = (lesson) => {
        setActiveLesson(lesson);
    };

    // This function receives (lessonId, moduleId, metadata) from LessonItem
    const handleComplete = (lessonId, moduleId, metadata = {}) => {
        console.log('LessonList handleComplete:', { lessonId, moduleId, metadata });
        markLessonComplete(lessonId, moduleId, metadata);
        
        if (activeLesson?.id === lessonId) {
            setTimeout(() => setActiveLesson(null), 1000);
        }
    };

    const handleIncomplete = (lessonId, moduleId) => {
        console.log('LessonList handleIncomplete:', { lessonId, moduleId });
        markLessonIncomplete(lessonId, moduleId);
    };

    if (!lessons || lessons.length === 0) return null;

    const completedCount = lessons.filter(l => l.completed).length;

    return (
        <>
            <div>
                <h4 className="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">
                    Lessons ({completedCount}/{lessons.length} completed)
                </h4> 
                <div className="space-y-2">
                    {lessons.map((lesson) => (
                        <div key={lesson.id} className="relative">
                            <LessonItem
                                lesson={{
                                    ...lesson,
                                    isActive: activeLesson?.id === lesson.id
                                }}
                                moduleId={module.id}
                                isCompleting={completingLesson === lesson.id}
                                onComplete={handleComplete}
                                onIncomplete={handleIncomplete}
                            />
                            
                            {!lesson.completed && (
                                <button
                                    onClick={() => handleLessonClick(lesson)}
                                    className="absolute inset-0 w-full h-full opacity-0"
                                    aria-label={`Open ${lesson.title}`}
                                />
                            )}
                        </div>
                    ))}
                </div>
            </div>
            
            {activeLesson && (
                <LessonContent
                    lesson={activeLesson}
                    module={module}
                    onComplete={(lessonId, moduleId, metadata) => handleComplete(lessonId, moduleId, metadata)}

                    onClose={() => setActiveLesson(null)}
                />
            )}
        </>
    );
}