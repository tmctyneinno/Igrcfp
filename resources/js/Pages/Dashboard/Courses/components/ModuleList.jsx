import React from 'react';
import ModuleItem from './ModuleItem';
import { BookOpenIcon } from '@heroicons/react/24/outline';

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

    return modules.map((module, moduleIndex) => (
        <ModuleItem 
            key={module.id}
            module={module}
            moduleIndex={moduleIndex}
            isExpanded={expandedModules[module.id] !== false}
            toggleModule={toggleModule}
            completingLesson={completingLesson}
            markLessonComplete={markLessonComplete}
            markLessonIncomplete={markLessonIncomplete}
        />
    ));
}