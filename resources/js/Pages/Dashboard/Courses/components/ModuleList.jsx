import React from 'react';
import ModuleItem from './ModuleItem';
import { BookOpenIcon } from '@heroicons/react/24/outline';

export default function ModuleList({ 
    modules, 
    expandedModules, 
    toggleModule, 
    completingLesson, 
    markLessonComplete, 
    markLessonIncomplete,
    readModules = {},
    moduleReadingProgress = {},
    markModuleRead,
    updateModuleReadingProgress,
    totalModulesCount = 0,
    onStartCourseQuiz
}) {  
    if (!modules || modules.length === 0) {
        return (
            <div className="bg-white rounded-xl shadow-sm p-12 text-center">
                <BookOpenIcon className="w-16 h-16 text-gray-400 mx-auto mb-4" />
                <h3 className="text-lg font-medium text-gray-900 mb-1">No modules yet</h3>
                <p className="text-gray-500">Course content is being prepared.</p>
            </div>
        );
    }

    // Calculate which modules are accessible
    const getModuleAccessibility = (moduleIndex) => {
        if (moduleIndex === 0) return { accessible: true, reason: null };
        
        const previousModule = modules[moduleIndex - 1];
        const previousModuleProgress = readModules[previousModule.id] ? 100 : (moduleReadingProgress[previousModule.id] || 0);
        const isPreviousCompleted = readModules[previousModule.id] === true;
        
        return {
            accessible: isPreviousCompleted,
            reason: isPreviousCompleted ? null : `Read Module ${previousModule.module_number} content first`,
            previousModuleTitle: previousModule.title,
            previousModuleProgress
        };
    };

    return (
        <div className="space-y-4">
            {modules.map((module, moduleIndex) => {
                const { accessible, reason, previousModuleTitle, previousModuleProgress } = 
                    getModuleAccessibility(moduleIndex);
                const isPreviousCompleted = moduleIndex === 0 || readModules[modules[moduleIndex - 1].id] === true;
                 
                return (
                    <ModuleItem
                        key={module.id}
                        module={module}
                        moduleIndex={moduleIndex}
                        isExpanded={expandedModules[module.id] !== false}
                        toggleModule={toggleModule}
                        completingLesson={completingLesson}
                        markLessonComplete={markLessonComplete}
                        markLessonIncomplete={markLessonIncomplete}
                        isAccessible={accessible}
                        lockReason={reason}
                        previousModuleTitle={previousModuleTitle}
                        previousModuleProgress={previousModuleProgress}
                        isPreviousCompleted={isPreviousCompleted}
                        isModuleRead={readModules[module.id] === true}
                        moduleReadingProgress={moduleReadingProgress[module.id] || 0}
                        markModuleRead={markModuleRead}
                        updateModuleReadingProgress={updateModuleReadingProgress}
                        totalModulesCount={totalModulesCount}
                        onStartCourseQuiz={onStartCourseQuiz}
                    />
                );
            })}
        </div>
    );
}