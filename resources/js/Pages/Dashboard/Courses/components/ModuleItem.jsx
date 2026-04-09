import React from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { 
    ChevronDownIcon, 
    ChevronUpIcon, 
    LockClosedIcon,
    CheckCircleIcon,
    ArrowRightIcon
} from '@heroicons/react/24/outline';
import MaterialList from './MaterialList';
import LessonList from './LessonList';

export default function ModuleItem({ 
    module, 
    moduleIndex, 
    isExpanded, 
    toggleModule, 
    completingLesson, 
    markLessonComplete, 
    markLessonIncomplete,
    isAccessible = true,
    lockReason = null,
    previousModuleTitle = null,
    previousModuleProgress = 0,
    isPreviousCompleted = true
}) { 
    const calculateModuleProgress = (module) => {
        if (!module.lessons || module.lessons.length === 0) return 0;
        const completed = module.lessons.filter(l => l.completed).length;
        return Math.round((completed / module.lessons.length) * 100);
    };

    const moduleProgress = calculateModuleProgress(module);
    const completedLessons = module.lessons?.filter(l => l.completed).length || 0;
    const totalLessons = module.lessons?.length || 0;
    const isCompleted = moduleProgress === 100;

    const handleModuleClick = () => {
        if (isAccessible) {
            toggleModule(module.id);
        }
    };

    return (
        <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: moduleIndex * 0.1 }}
            className={`bg-white rounded-xl shadow-sm overflow-hidden border-2 transition-all ${
                isAccessible 
                    ? 'border-gray-200 hover:border-blue-200' 
                    : 'border-gray-200 opacity-90'
            }`}
        > 
            {/* Module Status Banner */}
            {isCompleted && (
                <div className="bg-green-500 text-white text-xs font-medium px-4 py-1 text-center">
                    ✓ Module Completed
                </div>
            )}

            {/* Module Header */}
            <div 
                onClick={handleModuleClick}
                className={`w-full p-6 text-left transition ${
                    isAccessible 
                        ? 'bg-gray-50 hover:bg-gray-100 cursor-pointer' 
                        : 'bg-gray-100 cursor-not-allowed'
                }`}
            >
                <div className="flex items-start justify-between">
                    <div className="flex-1">
                        <div className="flex items-center gap-3 mb-2">
                         
                            <span className="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                Module {moduleIndex+1}
                            </span>
                            
                            {!isAccessible && (
                                <span className="flex items-center gap-1 text-xs text-orange-600 bg-orange-50 px-3 py-1 rounded-full">
                                    <LockClosedIcon className="w-3 h-3" />
                                    Locked
                                </span>
                            )}
                            
                            {isCompleted && (
                                <span className="flex items-center gap-1 text-xs text-green-600 bg-green-50 px-3 py-1 rounded-full">
                                    <CheckCircleIcon className="w-3 h-3" />
                                    Completed
                                </span>
                            )}
                            
                            {totalLessons > 0 && isAccessible && (
                                <span className="text-sm text-gray-500">
                                    {completedLessons}/{totalLessons} lessons
                                </span>
                            )}
                            
                            {module.estimated_hours && (
                                <span className="text-sm text-gray-500">
                                    ⏱️ {module.estimated_hours} hr{module.estimated_hours > 1 ? 's' : ''}
                                </span>
                            )}
                        </div>
                        
                        <h3 className={`text-lg font-semibold ${isAccessible ? 'text-gray-900' : 'text-gray-500'}`}>
                            {module.title}
                        </h3>
                        
                        {module.code && (
                            <p className="text-sm text-gray-500 mt-1">Code: {module.code}</p>
                        )}
                    </div>
                    
                    <div className="flex items-center gap-3">
                        {isAccessible ? (
                            <>
                                {/* Module Progress */}
                                <div className="w-20">
                                    <div className="flex justify-between text-xs text-gray-600 mb-1">
                                        <span>{moduleProgress}%</span>
                                    </div>
                                    <div className="h-1.5 w-full bg-gray-200 rounded-full">
                                        <div 
                                            className={`h-1.5 rounded-full transition-all duration-300 ${
                                                isCompleted ? 'bg-green-500' : 'bg-blue-600'
                                            }`}
                                            style={{ width: `${moduleProgress}%` }}
                                        />
                                    </div>
                                </div>
                                {isExpanded ? (
                                    <ChevronUpIcon className="w-5 h-5 text-gray-400" />
                                ) : (
                                    <ChevronDownIcon className="w-5 h-5 text-gray-400" />
                                )}
                            </>
                        ) : (
                            <LockClosedIcon className="w-5 h-5 text-gray-400" />
                        )}
                    </div>
                </div>
            </div>

            {/* Locked Module Message */}
            {!isAccessible && (
                <div className="p-6 border-t bg-orange-50">
                    <div className="flex items-start gap-4">
                        <div className="bg-orange-100 rounded-full p-3">
                            <LockClosedIcon className="w-6 h-6 text-orange-600" />
                        </div>
                        <div className="flex-1">
                            <h4 className="font-semibold text-gray-900 mb-1">
                                Module Locked
                            </h4>
                            <p className="text-gray-600 text-sm mb-3">
                                {lockReason || `You need to complete "${previousModuleTitle}" first to unlock this module.`}
                            </p> 
                            
                            {/* Progress toward unlocking */}
                            {previousModuleProgress !== undefined && (
                                <div className="mb-3">
                                    <div className="flex justify-between text-xs text-gray-600 mb-1">
                                        <span>Progress to unlock:</span>
                                        <span className="font-medium">{previousModuleProgress}%</span>
                                    </div>
                                    <div className="h-2 w-full bg-gray-200 rounded-full">
                                        <div 
                                            className="h-2 bg-orange-500 rounded-full transition-all"
                                            style={{ width: `${previousModuleProgress}%` }}
                                        />
                                    </div>
                                </div>
                            )}
                            
                            <button
                                onClick={() => {
                                    // Scroll to previous module or expand it
                                    document.getElementById(`module-${module.module_number - 1}`)?.scrollIntoView({ 
                                        behavior: 'smooth' 
                                    });
                                }}
                                className="text-sm text-orange-600 hover:text-orange-700 font-medium flex items-center gap-1"
                            >
                                Go to {previousModuleTitle}
                                <ArrowRightIcon className="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>
            )}

            {/* Module Content - Only shown when accessible and expanded */}
            <AnimatePresence>
                {isAccessible && isExpanded && (
                    <motion.div
                        initial={{ opacity: 0, height: 0 }}
                        animate={{ opacity: 1, height: 'auto' }}
                        exit={{ opacity: 0, height: 0 }}
                        transition={{ duration: 0.2 }}
                        className="border-t"
                    >
                        <div className="p-6 space-y-6">
                            {/* Learning Objectives */}
                            {module.learning_objectives && (
                                <div className="bg-blue-50 rounded-lg p-4 border border-blue-100">
                                    <h4 className="text-md font-semibold text-blue-900 mb-2 flex items-center gap-2">
                                        🎯 Learning Objectives
                                    </h4>
                                    <div 
                                        className="prose prose-sm max-w-none text-gray-700"
                                        dangerouslySetInnerHTML={{ __html: module.learning_objectives }}
                                    />
                                </div>
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

                            {/* Materials */}
                            <MaterialList materials={module.materials} />
                            
                            {/* Lessons */}
                            <LessonList 
                                lessons={module.lessons} 
                                module={module}
                                completingLesson={completingLesson}
                                markLessonComplete={markLessonComplete}
                                markLessonIncomplete={markLessonIncomplete}
                            /> 

                            {/* Module Completion Celebration */}
                            {isCompleted && (
                                <div className="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                                    <CheckCircleIcon className="w-8 h-8 text-green-500 mx-auto mb-2" />
                                    <h4 className="font-semibold text-green-800 mb-1">
                                        Module Completed! 🎉
                                    </h4>
                                    <p className="text-sm text-green-600">
                                        Great job! You can now proceed to Module {module.module_number + 1}.
                                    </p>
                                </div>
                            )}
                        </div>
                    </motion.div>
                )}
            </AnimatePresence>
        </motion.div>
    );
}