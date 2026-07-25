import React, { useState, useCallback, useEffect, useMemo } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import toast from 'react-hot-toast'; 
import { 
    BookOpenIcon, 
    ClipboardDocumentCheckIcon, 
    LockClosedIcon, 
    ClockIcon 
} from '@heroicons/react/24/outline';
import Breadcrumb from './components/Breadcrumb';
import CandidateBanner from './components/CandidateBanner';
import CourseHeader from './components/CourseHeader';
import ModuleList from './components/ModuleList';
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
    // ==================== STATE ====================
    const [expandedModules, setExpandedModules] = useState({});
    const [completingLesson, setCompletingLesson] = useState(null);
    const [processingExam, setProcessingExam] = useState(null);
    const [moduleCompletionStatus, setModuleCompletionStatus] = useState({});
    const [localModules, setLocalModules] = useState(initialModules);
    const [quizUnlockVersion, setQuizUnlockVersion] = useState(0);
    const [enrollmentProgress, setEnrollmentProgress] = useState(enrollment?.progress || 0);
    const [markingReadModule, setMarkingReadModule] = useState(null);
    
    // Lockout State
    const [lockedQuiz, setLockedQuiz] = useState(null);
    const [timeRemaining, setTimeRemaining] = useState('');

    // Derived State
    const [readModules, setReadModules] = useState(() => initialModules.reduce((status, module) => ({
        ...status,
        [module.id]: module.read === true,
    }), {}));
    
    const [moduleReadingProgress, setModuleReadingProgress] = useState(() => initialModules.reduce((progressByModule, module) => ({
        ...progressByModule,
        [module.id]: module.read === true ? 100 : (module.reading_progress || 0),
    }), {}));

    // ==================== EFFECTS ====================
    
    // Sync local modules when props change
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

    // Check for locked quizzes on load
    useEffect(() => {
        const locked = quizzes.find(q => q.is_locked_out);
        if (locked) {
            setLockedQuiz(locked);
        }
    }, [quizzes]);

    // Countdown Timer Effect
    useEffect(() => {
        if (!lockedQuiz || !lockedQuiz.lock_expires_at) return;

        const updateTimer = () => {
            const expires = new Date(lockedQuiz.lock_expires_at).getTime();
            const now = new Date().getTime();
            const distance = expires - now;

            if (distance < 0) {
                setTimeRemaining('00:00:00');
                // Lock expired, reload to get fresh state from backend
                window.location.reload(); 
            } else {
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                setTimeRemaining(`${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`);
            }
        };

        updateTimer();
        const timer = setInterval(updateTimer, 1000);
        return () => clearInterval(timer);
    }, [lockedQuiz]);

    // ==================== COMPUTED VALUES ====================
    
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

    // ==================== HANDLERS ====================

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

    const handleStartQuiz = useCallback((quiz) => {
        // Check lockout first
        if (quiz.is_locked_out) {
            setLockedQuiz(quiz);
            return;
        }

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

    // ==================== RENDER ====================

    return (
        <AuthenticatedLayout>
            <Head title={`${course?.title || 'Course'} | My Learning`} />
            
            {/* LOCKOUT MODAL */}
            {lockedQuiz && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                    <div className="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center relative animate-in fade-in zoom-in duration-300">
                        <button 
                            onClick={() => setLockedQuiz(null)}
                            className="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition"
                        >
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>

                        <div className="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <LockClosedIcon className="w-8 h-8 text-red-600" />
                        </div>
                        
                        <h2 className="text-2xl font-bold text-gray-900 mb-2">Quiz Locked</h2>
                        <p className="text-gray-600 mb-6">
                            You did not pass the previous attempt. Please review the course material and try again after the cooldown period.
                        </p>
                        
                        <div className="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-200">
                            <p className="text-sm text-gray-500 mb-1">Time Remaining</p>
                            <div className="flex items-center justify-center gap-2 text-3xl font-mono font-bold text-gray-800">
                                <ClockIcon className="w-6 h-6 text-blue-600" />
                                {timeRemaining}
                            </div>
                        </div>
                        
                        <button
                            onClick={() => setLockedQuiz(null)}
                            className="w-full py-3 bg-gray-900 text-white font-semibold rounded-xl hover:bg-gray-800 transition"
                        >
                            I Understand
                        </button>
                    </div>
                </div>
            )}

            <div className="min-h-screen bg-gray-50 py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <Breadcrumb courseTitle={course?.title} />
                    {candidate && <CandidateBanner candidate={candidate} enrollment={enrollment} />}

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {/* LEFT COLUMN: COURSE CONTENT */}
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

                        {/* RIGHT COLUMN: SIDEBAR */}
                        <div className="space-y-6">
                            {/* Quiz Section */}
                            {/* Quiz Section */}
{quizzesWithUnlockStatus.map((quiz) => {
    // Determine if quiz is already submitted/completed
    const isSubmitted = quiz.submitted === true || quiz.completed === true || quiz.passed === true;

    return (
        <div key={quiz.id} className="bg-white rounded-xl border border-gray-200 p-5">
            <h3 className="font-semibold text-gray-900 mb-3">Take Quiz</h3>
             
            {/* STATE 1: ALREADY SUBMITTED */}
            {isSubmitted ? (
                <button 
                    disabled 
                    className="w-full py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg cursor-default flex items-center justify-center gap-2 font-medium"
                >
                    <ClipboardDocumentCheckIcon className="w-4 h-4" />
                    Assessment Submitted
                </button>
            ) : /* STATE 2: LOCKED OUT (COOLDOWN) */
            quiz.is_locked_out ? (
                <button 
                    onClick={() => setLockedQuiz(quiz)}
                    className="w-full py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg cursor-pointer hover:bg-red-100 flex items-center justify-center gap-2 transition"
                >
                    <LockClosedIcon className="w-4 h-4" />
                    Locked ({quiz.lock_expires_at ? new Date(quiz.lock_expires_at).toLocaleDateString() : ''})
                </button>
            ) : /* STATE 3: NOT UNLOCKED YET */
            !quiz.unlocked ? (
                <div>
                    <button disabled className="w-full py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed flex items-center justify-center gap-2">
                        🔒 Take Quiz
                    </button>
                    <p className="mt-2 text-xs text-gray-500 text-center">{quiz.reason}</p>
                </div>
            ) : /* STATE 4: ACTIVE / AVAILABLE */
            (
                <button 
                    onClick={() => handleStartQuiz(quiz)} 
                    className="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2"
                >
                    📝 Take Quiz
                </button>
            )}
        </div>
    );
})}

                            {/* Project Assessment Section */}
                                                        {/* Project Assessment / Review Status Section */}
                            {hasPassedQuiz && (
                                <div className="bg-white rounded-xl border border-gray-200 p-5">
                                    <h3 className="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                        <ClipboardDocumentCheckIcon className="w-5 h-5 text-indigo-600" />
                                        {diplomaAssessment ? 'Project Assessment' : 'Application Status'}
                                    </h3>

                                    {diplomaAssessment ? (
                                        // CASE 1: Project Assessment Exists -> Show Button
                                        <>
                                            <p className="text-sm text-gray-600 mb-4">
                                                Your quiz is passed. You can now access the project assessment.
                                            </p>
                                            <Link
                                                href={route('dashboard.quiz.project-assessment', { course: course.slug })}
                                                className="w-full flex items-center justify-center gap-2 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                                            >
                                                Open Project Assessment
                                            </Link>
                                        </>
                                    ) : (
                                        // CASE 2: No Project Assessment -> Show Under Review Message
                                        <div className="bg-blue-50 border border-blue-100 rounded-lg p-4">
                                            <p className="text-sm text-blue-800 font-medium flex items-start gap-2">
                                                <svg className="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>
                                                    Your submitted application is currently under review by our team. 
                                                    You will be notified via email once the application process is complete.
                                                </span>
                                            </p>
                                        </div>
                                    )}
                                </div>
                            )}

                            {/* Certification Card */}
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

                            {/* Course Materials */}
                            <MaterialList materials={courseMaterials} title="Course Materials" />
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}