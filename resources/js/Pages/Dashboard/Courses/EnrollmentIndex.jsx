import React, { useState, useCallback, useEffect, useMemo } from 'react';
import { Head, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import toast from 'react-hot-toast';
import { BookOpenIcon } from '@heroicons/react/24/outline';
import Breadcrumb from './components/Breadcrumb';
import CandidateBanner from './components/CandidateBanner';
import CourseHeader from './components/CourseHeader';
import ModuleList from './components/ModuleList';
import AssessmentList from './components/AssessmentList';
import IdentityVerificationCard from './components/IdentityVerificationCard';
import CertificationCard from './components/CertificationCard';

export default function EnrollmentIndex({ 
    course, 
    enrollment, 
    modules: initialModules = [], 
    candidate, 
    quizzes = [], 
    moduleAssessments = [], 
    finalExam = null, 
    diplomaAssessment = null,
    examResults = {} 
}) {
    const [expandedModules, setExpandedModules] = useState({});
    const [completingLesson, setCompletingLesson] = useState(null);
    const [processingExam, setProcessingExam] = useState(null);
    const [moduleCompletionStatus, setModuleCompletionStatus] = useState({});
    const [localModules, setLocalModules] = useState(initialModules);
    const [quizUnlockVersion, setQuizUnlockVersion] = useState(0);

    useEffect(() => {
        setLocalModules(initialModules);
    }, [initialModules]);

    const progress = enrollment?.progress || 0;
    const hasCertificate = enrollment?.certificate_generated;
    const certificateNumber = enrollment?.certificate_number || candidate?.certificate_id;
    const isIdentityVerified = enrollment?.identity_verified || false;

    const getQuizUnlockStatus = useCallback((quiz) => {
        const quizModuleId = typeof quiz.module_id === 'string' 
            ? parseInt(quiz.module_id, 10) 
            : quiz.module_id;
        
        const module = localModules.find(m => {
            const moduleId = typeof m.id === 'string' ? parseInt(m.id, 10) : m.id;
            return moduleId === quizModuleId;
        });
        
        if (!module) {
            return { unlocked: true, reason: null, progress: 100 };
        }
        
        const totalLessons = module.lessons?.length || 0;
        const completedLessons = module.lessons?.filter(l => l.completed).length || 0;
        const allLessonsComplete = totalLessons > 0 && completedLessons === totalLessons;
        
        return {
            unlocked: allLessonsComplete,
            reason: allLessonsComplete ? null : `Complete all lessons in "${module.title}" first (${completedLessons}/${totalLessons})`,
            progress: totalLessons > 0 ? Math.round((completedLessons / totalLessons) * 100) : 0
        };
    }, [localModules, quizUnlockVersion]);

    const quizzesWithUnlockStatus = useMemo(() => {
        return quizzes.map(quiz => ({ ...quiz, ...getQuizUnlockStatus(quiz) }));
    }, [quizzes, getQuizUnlockStatus, quizUnlockVersion]);

    const checkModuleCompletion = useCallback((moduleId) => {
        const module = localModules.find(m => m.id === moduleId);
        if (!module) return false;
        
        const allLessonsCompleted = module.lessons?.every(l => l.completed) ?? false;
        
        if (allLessonsCompleted && !moduleCompletionStatus[moduleId]) {
            setModuleCompletionStatus(prev => ({ ...prev, [moduleId]: true }));
            setQuizUnlockVersion(prev => prev + 1);
            toast.success(`🎉 Module ${module.module_number} completed!`);
            return true;
        }
        return allLessonsCompleted;
    }, [localModules, moduleCompletionStatus]);

    const markLessonComplete = useCallback((lessonId, moduleId, metadata = {}) => {
        setCompletingLesson(lessonId);
        setLocalModules(prevModules => prevModules.map(module => {
            if (module.id === moduleId) {
                return {
                    ...module,
                    lessons: module.lessons?.map(lesson => 
                        lesson.id === lessonId ? { ...lesson, completed: true } : lesson
                    ) || []
                };
            }
            return module;
        }));
        
        router.post(route('lessons.complete', lessonId), {
            time_spent: metadata.timeSpent || 0,
            auto_completed: metadata.autoCompleted || false,
            scroll_progress: metadata.scrollProgress || 100,
        }, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                setCompletingLesson(null);
                if (page.props?.flash?.modules) setLocalModules(page.props.flash.modules);
                setQuizUnlockVersion(prev => prev + 1);
                if (moduleId) checkModuleCompletion(moduleId);
                toast.success('Lesson completed!');
            },
            onError: () => {
                setCompletingLesson(null);
                setLocalModules(prevModules => prevModules.map(module => {
                    if (module.id === moduleId) {
                        return {
                            ...module,
                            lessons: module.lessons?.map(lesson => 
                                lesson.id === lessonId ? { ...lesson, completed: false } : lesson
                            ) || []
                        };
                    }
                    return module;
                }));
                toast.error('Failed to mark lesson as complete');
            },
        });
    }, [checkModuleCompletion]);

    const markLessonIncomplete = useCallback((lessonId, moduleId) => {
        setLocalModules(prevModules => prevModules.map(module => {
            if (module.id === moduleId) {
                return {
                    ...module,
                    lessons: module.lessons?.map(lesson => 
                        lesson.id === lessonId ? { ...lesson, completed: false } : lesson
                    ) || []
                };
            }
            return module;
        }));
        setQuizUnlockVersion(prev => prev + 1);
        
        router.delete(route('lessons.incomplete', lessonId), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                if (page.props?.flash?.modules) setLocalModules(page.props.flash.modules);
                setQuizUnlockVersion(prev => prev + 1);
                toast.success('Lesson marked as incomplete');
            },
            onError: () => {
                setLocalModules(prevModules => prevModules.map(module => {
                    if (module.id === moduleId) {
                        return {
                            ...module,
                            lessons: module.lessons?.map(lesson => 
                                lesson.id === lessonId ? { ...lesson, completed: true } : lesson
                            ) || []
                        };
                    }
                    return module;
                }));
                toast.error('Failed to update lesson status');
            }
        });
    }, []);

    const toggleModule = useCallback((moduleId) => {
        setExpandedModules(prev => ({ ...prev, [moduleId]: !prev[moduleId] }));
    }, []);

    // Start quiz - passes the module_id to the quiz page
    const handleStartQuiz = useCallback((quiz) => {
        if (!quiz.unlocked) {
            toast.error(quiz.reason || 'Complete lessons first');
            return;
        } 
        setProcessingExam(quiz.id);
        const actualQuizId = quiz.quiz_ids?.[0] || quiz.id;
        window.location.href = route('dashboard.quiz.take', { 
            course: course.slug, 
            assessment: actualQuizId,
            module: quiz.module_id  // Pass module ID
        });
    }, [course?.slug]);

    return (
        <AuthenticatedLayout>
            <Head title={`${course?.title || 'Course'} | My Learning`} />
            <div className="min-h-screen bg-gray-50 py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <Breadcrumb courseTitle={course?.title} />
                    {candidate && <CandidateBanner candidate={candidate} enrollment={enrollment} />}

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div className="lg:col-span-2 space-y-6">
                            <CourseHeader course={course} enrollment={enrollment} modulesCount={localModules?.length || 0} progress={progress} />
                            <div className="space-y-4">
                                <h2 className="text-xl font-semibold text-gray-900 flex items-center gap-2">
                                    <BookOpenIcon className="w-5 h-5 text-indigo-600" />
                                    Course Content
                                </h2>
                                <ModuleList 
                                    modules={localModules}
                                    expandedModules={expandedModules}
                                    toggleModule={toggleModule}
                                    completingLesson={completingLesson}
                                    markLessonComplete={markLessonComplete}
                                    markLessonIncomplete={markLessonIncomplete}
                                />
                            </div>
                        </div>

                        <div className="space-y-6">
                            <IdentityVerificationCard enrollment={enrollment} isIdentityVerified={isIdentityVerified} />
                            
                            {/* Quiz buttons in sidebar - NOT inside modules */}
                            {quizzesWithUnlockStatus.map((quiz) => (
                                <div key={quiz.id} className="bg-white rounded-xl border border-gray-200 p-5">
                                    <h3 className="font-semibold text-gray-900 mb-3">{quiz.title}</h3>
                                    {!quiz.unlocked ? (
                                        <button disabled className="w-full py-2 bg-gray-100 text-gray-400 rounded-lg">
                                            🔒 {quiz.reason}
                                        </button>
                                    ) : (
                                        <button onClick={() => handleStartQuiz(quiz)} className="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                            📝 Take Quiz
                                        </button>
                                    )}
                                </div> 
                            ))}

                            <CertificationCard enrollment={enrollment} hasCertificate={hasCertificate} certificateNumber={certificateNumber} isIdentityVerified={isIdentityVerified} examResults={examResults} progress={progress} />
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}