import React from 'react';
import ModuleItem from './ModuleItem';
import { BookOpenIcon, LockClosedIcon } from '@heroicons/react/24/outline';

export default function ModuleList({ 
    modules, 
    expandedModules, 
    toggleModule, 
    completingLesson, 
    markLessonComplete, 
    markLessonIncomplete 
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
        const previousModuleProgress = calculateModuleProgress(previousModule);
        const isPreviousCompleted = previousModuleProgress === 100;
        
        return {
            accessible: isPreviousCompleted,
            reason: isPreviousCompleted ? null : `Complete Module ${previousModule.module_number} first`,
            previousModuleTitle: previousModule.title,
            previousModuleProgress
        };
    };

    const calculateModuleProgress = (module) => {
        if (!module.lessons || module.lessons.length === 0) return 0;
        const completed = module.lessons.filter(l => l.completed).length;
        return Math.round((completed / module.lessons.length) * 100);
    };

    return (
        <div className="space-y-4">
            {modules.map((module, moduleIndex) => {
                const { accessible, reason, previousModuleTitle, previousModuleProgress } = 
                    getModuleAccessibility(moduleIndex);
                const moduleProgress = calculateModuleProgress(module);
                const isPreviousCompleted = moduleIndex === 0 || calculateModuleProgress(modules[moduleIndex - 1]) === 100;
                
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
                    />
                );
            })}
        </div>
    );
}