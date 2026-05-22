import React, { useState, useCallback, useEffect, useMemo } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import toast from 'react-hot-toast';
import { BookOpenIcon, ClipboardDocumentCheckIcon } from '@heroicons/react/24/outline';
import Breadcrumb from './components/Breadcrumb';
import CandidateBanner from './components/CandidateBanner';
import CourseHeader from './components/CourseHeader';
import ModuleList from './components/ModuleList';
import AssessmentList from './components/AssessmentList';
import IdentityVerificationCard from './components/IdentityVerificationCard';
import CertificationCard from './components/CertificationCard';
import MaterialList from './components/MaterialList';

export default function EnrollmentIndex({ 
    course, 
    enrollment, 
    modules: initialModules = [], 
    candidate, 
    quizzes = [], 
    moduleAssessments = [], 
    finalExam = null, 
    diplomaAssessment = null,
    examResults = {},
    certification = {},
    courseMaterials = []
}) {
    const [expandedModules, setExpandedModules] = useState({});
    const [completingLesson, setCompletingLesson] = useState(null);
    const [processingExam, setProcessingExam] = useState(null);
    const [moduleCompletionStatus, setModuleCompletionStatus] = useState({});
    const [localModules, setLocalModules] = useState(initialModules);
    const [quizUnlockVersion, setQuizUnlockVersion] = useState(0);
    const [enrollmentProgress, setEnrollmentProgress] = useState(enrollment?.progress || 0);
    const [readModules, setReadModules] = useState(() => initialModules.reduce((status, module) => ({
        ...status,
        [module.id]: module.read === true,
    }), {}));
    const [moduleReadingProgress, setModuleReadingProgress] = useState(() => initialModules.reduce((progressByModule, module) => ({
        ...progressByModule,
        [module.id]: module.read === true ? 100 : (module.reading_progress || 0),
    }), {}));
    const [markingReadModule, setMarkingReadModule] = useState(null);

    useEffect(() => {
        setLocalModules(initialModules);
        setReadModules(initialModules.reduce((status, module) => ({
            ...status,
            [module.id]: module.read === true,
        }), {}));
        setModuleReadingProgress(initialModules.reduce((progressByModule, module) => ({
            ...progressByModule,
            [module.id]: module.read === true ? 100 : (module.reading_progress || 0),
        }), {}));
    }, [initialModules]);

    useEffect(() => {
        setEnrollmentProgress(enrollment?.progress || 0);
    }, [enrollment?.progress]);

    const progress = enrollmentProgress;
    const hasCertificate = enrollment?.certificate_generated;
    const certificateNumber = enrollment?.certificate_number || candidate?.certificate_id;
    const isIdentityVerified = enrollment?.identity_verified || false;
    const canShowCertificationCard = certification?.can_display_card === true;
    const completedReadModules = localModules.filter(module => readModules[module.id]).length;
    const allModulesRead = localModules.length === 0 || completedReadModules === localModules.length;

    const getQuizUnlockStatus = useCallback((quiz) => {
        return {
            unlocked: allModulesRead,
            reason: allModulesRead ? null : `Read all module content first (${completedReadModules}/${localModules.length})`,
            progress: localModules.length > 0 ? Math.round((completedReadModules / localModules.length) * 100) : 0
        };
    }, [allModulesRead, completedReadModules, localModules.length]);

    const quizzesWithUnlockStatus = useMemo(() => {
        return quizzes.map(quiz => ({ ...quiz, ...getQuizUnlockStatus(quiz) }));
    }, [quizzes, getQuizUnlockStatus, quizUnlockVersion]);
    const hasPassedQuiz = quizzesWithUnlockStatus.some(quiz => quiz.passed === true || quiz.passed === 1);

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

    const markModuleRead = useCallback(async (moduleId) => {
        if (readModules[moduleId] || markingReadModule === moduleId) return;

        setMarkingReadModule(moduleId);

        try {
            const response = await window.axios.post(route('modules.reading-progress', moduleId), {
                reading_progress: 100,
                read: true,
            });

            setReadModules(prev => ({ ...prev, [moduleId]: true }));
            setModuleReadingProgress(prev => ({ ...prev, [moduleId]: 100 }));
            setLocalModules(prevModules => prevModules.map(module => (
                module.id === moduleId
                    ? { ...module, read: true, reading_progress: 100, read_at: response.data?.module?.read_at }
                    : module
            )));

            if (response.data?.enrollment?.progress !== undefined) {
                setEnrollmentProgress(response.data.enrollment.progress);
            }

            setQuizUnlockVersion(version => version + 1);
            toast.success('Module marked as read.');
        } catch (error) {
            toast.error(error.response?.data?.message || 'Unable to save module reading progress.');
        } finally {
            setMarkingReadModule(null);
        }
    }, [markingReadModule, readModules]);

    const updateModuleReadingProgress = useCallback((moduleId, progress) => {
        setModuleReadingProgress(prev => ({
            ...prev,
            [moduleId]: Math.max(prev[moduleId] || 0, progress),
        }));
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
                                    readModules={readModules}
                                    moduleReadingProgress={moduleReadingProgress}
                                    markModuleRead={markModuleRead}
                                    updateModuleReadingProgress={updateModuleReadingProgress}
                                />
                            </div>
                        </div>

                        <div className="space-y-6">
                            {/* <IdentityVerificationCard enrollment={enrollment} isIdentityVerified={isIdentityVerified} /> */}
                             
                            {/* Quiz buttons in sidebar - NOT inside modules */}
                            {quizzesWithUnlockStatus.map((quiz) => (
                                <div key={quiz.id} className="bg-white rounded-xl border border-gray-200 p-5">
                                    {/* <h3 className="font-semibold text-gray-900 mb-3">{quiz.title}</h3> */}
                                    <h3 className="font-semibold text-gray-900 mb-3">Take Quiz</h3>
                                    {!quiz.unlocked ? (
                                        <div>
                                            <button disabled className="w-full py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                                🔒 Take Quiz
                                            </button>
                                            <p className="mt-2 text-xs text-gray-500 text-center">{quiz.reason}</p>
                                        </div>
                                    ) : (
                                        <button onClick={() => handleStartQuiz(quiz)} className="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                            📝 Take Quiz
                                        </button>
                                    )}
                                </div> 
                            ))}

                            {hasPassedQuiz && (
                                <div className="bg-white rounded-xl border border-gray-200 p-5">
                                    <h3 className="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                        <ClipboardDocumentCheckIcon className="w-5 h-5 text-indigo-600" />
                                        Project Assessment
                                    </h3>
                                    <p className="text-sm text-gray-600 mb-4">
                                        Your quiz is passed. You can now access the project assessment.
                                    </p>
                                    <Link
                                        href={route('dashboard.quiz.project-assessment', { course: course.slug })}
                                        className="w-full flex items-center justify-center gap-2 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                                    >
                                        Open Project Assessment
                                    </Link>
                                </div>
                            )}

                            {canShowCertificationCard && (
                                <CertificationCard
                                    enrollment={enrollment}
                                    hasCertificate={hasCertificate}
                                    certificateNumber={certificateNumber}
                                    isIdentityVerified={isIdentityVerified}
                                    examResults={examResults}
                                    certification={certification}
                                    progress={progress}
                                />
                            )}
                            <MaterialList materials={courseMaterials} title="Course Materials" />
                                
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
