import React from 'react';
import { motion } from 'framer-motion';
import { ChevronDownIcon, ChevronUpIcon } from '@heroicons/react/24/outline';
import MaterialList from './MaterialList';
import LessonList from './LessonList';

export default function ModuleItem({ 
    module, 
    moduleIndex, 
    isExpanded, 
    toggleModule, 
    completingLesson, 
    markLessonComplete, 
    markLessonIncomplete 
}) { 
    const calculateModuleProgress = (module) => {
        if (!module.lessons || module.lessons.length === 0) return 0;
        const completed = module.lessons.filter(l => l.completed).length;
        return Math.round((completed / module.lessons.length) * 100);
    };

    const moduleProgress = calculateModuleProgress(module);

    return (
        <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: moduleIndex * 0.1 }}
            className="bg-white rounded-xl shadow-sm overflow-hidden"
        >
            <button
                onClick={() => toggleModule(module.id)}
                className="w-full p-6 text-left bg-gray-50 hover:bg-gray-100 transition"
            >
                <div className="flex items-start justify-between">
                    <div className="flex-1">
                        <div className="flex items-center gap-3 mb-2">
                            <span className="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                Module {module.module_number}
                            </span>
                            {module.lessons && (
                                <span className="text-sm text-gray-500">
                                    {module.lessons.filter(l => l.completed).length}/{module.lessons.length} lessons
                                </span>
                            )}
                        </div>
                        <h3 className="text-lg font-semibold text-gray-900">{module.title}</h3>
                    </div>
                    <div className="flex items-center gap-3">
                        <div className="w-20">
                            <div className="flex justify-between text-xs text-gray-600 mb-1">
                                <span>{moduleProgress}%</span>
                            </div>
                            <div className="h-1.5 w-full bg-gray-200 rounded-full">
                                <div 
                                    className="h-1.5 bg-green-500 rounded-full"
                                    style={{ width: `${moduleProgress}%` }}
                                />
                            </div>
                        </div>
                        {isExpanded ? (
                            <ChevronUpIcon className="w-5 h-5 text-gray-400" />
                        ) : (
                            <ChevronDownIcon className="w-5 h-5 text-gray-400" />
                        )}
                    </div>
                </div>
            </button>

            {isExpanded && (
                <div className="p-6 border-t">
                    {module.learning_objectives && (
                        <>
                       <p className="font-semibold text-gray-800">Learning Objective</p>
                        <div 
                            className="prose prose-sm max-w-none text-gray-600 mb-4"
                            dangerouslySetInnerHTML={{ __html: module.learning_objectives }}
                        />
                        </>
                    )} 
                    {module.course_outline && (
                        <>
                       <p className="font-semibold text-gray-800">Course Outline</p>
                        <div 
                            className="prose prose-sm max-w-none text-gray-600 mb-4"
                            dangerouslySetInnerHTML={{ __html: module.course_outline }}
                        />
                        </>
                    )}  
                    {/* Full Content - Main Module Content */}
                    {module.full_content && (
                        <div className="bg-white rounded-lg p-4 border border-gray-200">
                            <h4 className="text-md font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                📖 Module Content
                            </h4>
                            <div 
                                className="prose prose-sm max-w-none text-gray-700"
                                dangerouslySetInnerHTML={{ __html: module.full_content }}
                            />
                        </div>
                    )}


                    <MaterialList materials={module.materials} />
                    
                    <LessonList 
                        lessons={module.lessons} 
                        completingLesson={completingLesson}
                        markLessonComplete={markLessonComplete}
                        markLessonIncomplete={markLessonIncomplete}
                    />
                </div>
            )}
        </motion.div>
    );
}