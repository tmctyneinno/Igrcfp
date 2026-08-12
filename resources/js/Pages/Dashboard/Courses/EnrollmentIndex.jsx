import React, { useState, useCallback, useEffect, useMemo } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import toast from 'react-hot-toast'; 
import {  
    BookOpenIcon, ClipboardDocumentCheckIcon, LockClosedIcon, ClockIcon,
    DocumentTextIcon, AcademicCapIcon, CheckCircleIcon, ExclamationCircleIcon
} from '@heroicons/react/24/outline';
import Breadcrumb from './components/Breadcrumb';
import CandidateBanner from './components/CandidateBanner';
import CourseHeader from './components/CourseHeader';
import ModuleList from './components/ModuleList';
import CertificationCard from './components/CertificationCard';
import MaterialList from './components/MaterialList';

export default function EnrollmentIndex({ 
    course, enrollment, modules: initialModules = [], candidate, 
    quizzes = [], moduleAssessments = [], finalExam = null, 
    diplomaAssessment = null, examResults = {}, certification = {}, 
    courseMaterials = [], totalModulesCount = 0
}) {
    const [expandedModules, setExpandedModules] = useState({});
    const [completingLesson, setCompletingLesson] = useState(null);
    const [processingExam, setProcessingExam] = useState(null);
    const [moduleCompletionStatus, setModuleCompletionStatus] = useState({});
    const [localModules, setLocalModules] = useState(initialModules);
    const [quizUnlockVersion, setQuizUnlockVersion] = useState(0);
    const [enrollmentProgress, setEnrollmentProgress] = useState(enrollment?.progress || 0);
    const [markingReadModule, setMarkingReadModule] = useState(null);
    const [lockedQuiz, setLockedQuiz] = useState(null);
    const [timeRemaining, setTimeRemaining] = useState('');

    const [readModules, setReadModules] = useState(() => initialModules.reduce((s, m) => ({ ...s, [m.id]: m.read === true }), {}));
    const [moduleReadingProgress, setModuleReadingProgress] = useState(() => initialModules.reduce((s, m) => ({ ...s, [m.id]: m.read ? 100 : (m.reading_progress || 0) }), {}));

    useEffect(() => {
        setLocalModules(initialModules);
        setReadModules(initialModules.reduce((s, m) => ({ ...s, [m.id]: m.read === true }), {}));
        setModuleReadingProgress(initialModules.reduce((s, m) => ({ ...s, [m.id]: m.read ? 100 : (m.reading_progress || 0) }), {}));
    }, [initialModules]);

    useEffect(() => { setEnrollmentProgress(enrollment?.progress || 0); }, [enrollment?.progress]);
    useEffect(() => { const l = quizzes.find(q => q.is_locked_out); if (l) setLockedQuiz(l); }, [quizzes]);

    useEffect(() => {
        if (!lockedQuiz || !lockedQuiz.lock_expires_at) return;
        const updateTimer = () => {
            const d = new Date(lockedQuiz.lock_expires_at).getTime() - Date.now();
            if (d < 0) { setTimeRemaining('00:00:00'); window.location.reload(); }
            else {
                const h = Math.floor((d % 86400000) / 3600000);
                const m = Math.floor((d % 3600000) / 60000);
                const s = Math.floor((d % 60000) / 1000);
                setTimeRemaining(`${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`);
            }
        };
        updateTimer();
        const t = setInterval(updateTimer, 1000);
        return () => clearInterval(t);
    }, [lockedQuiz]);

    const progress = enrollmentProgress;
    const hasCertificate = enrollment?.certificate_generated;
    const certificateNumber = enrollment?.certificate_number || candidate?.certificate_id;
    const isIdentityVerified = enrollment?.identity_verified || false;
    const canShowCertificationCard = certification?.can_display_card === true;
    const completedReadModules = localModules.filter(m => readModules[m.id]).length;
    const allModulesRead = localModules.length === 0 || completedReadModules === localModules.length;

    const getQuizUnlockStatus = useCallback((quiz) => ({
        unlocked: allModulesRead,
        reason: allModulesRead ? null : `Read all module content first (${completedReadModules}/${localModules.length})`,
        progress: localModules.length > 0 ? Math.round((completedReadModules / localModules.length) * 100) : 0
    }), [allModulesRead, completedReadModules, localModules.length]);

    const quizzesWithUnlockStatus = useMemo(() => quizzes.map(q => ({ ...q, ...getQuizUnlockStatus(q) })), [quizzes, getQuizUnlockStatus, quizUnlockVersion]);
    const hasPassedQuiz = quizzesWithUnlockStatus.some(q => q.passed === true || q.passed === 1);

    const checkModuleCompletion = useCallback((moduleId) => {
        const mod = localModules.find(m => m.id === moduleId);
        if (!mod) return false;
        const done = mod.lessons?.every(l => l.completed) ?? false;
        if (done && !moduleCompletionStatus[moduleId]) {
            setModuleCompletionStatus(p => ({ ...p, [moduleId]: true }));
            setQuizUnlockVersion(v => v + 1);
            toast.success(`🎉 Module ${mod.module_number} completed!`);
            return true;
        }
        return done;
    }, [localModules, moduleCompletionStatus]);

    const markLessonComplete = useCallback((lessonId, moduleId, metadata = {}) => {
        setCompletingLesson(lessonId);
        setLocalModules(prev => prev.map(m => m.id === moduleId ? { ...m, lessons: m.lessons?.map(l => l.id === lessonId ? { ...l, completed: true } : l) || [] } : m));
        router.post(route('lessons.complete', lessonId), { time_spent: metadata.timeSpent || 0, auto_completed: metadata.autoCompleted || false, scroll_progress: metadata.scrollProgress || 100 }, {
            preserveState: true, preserveScroll: true,
            onSuccess: (page) => { setCompletingLesson(null); if (page.props?.flash?.modules) setLocalModules(page.props.flash.modules); setQuizUnlockVersion(v => v + 1); if (moduleId) checkModuleCompletion(moduleId); toast.success('Lesson completed!'); },
            onError: () => { setCompletingLesson(null); setLocalModules(prev => prev.map(m => m.id === moduleId ? { ...m, lessons: m.lessons?.map(l => l.id === lessonId ? { ...l, completed: false } : l) || [] } : m)); toast.error('Failed to mark lesson as complete'); },
        });
    }, [checkModuleCompletion]);

    const markLessonIncomplete = useCallback((lessonId, moduleId) => {
        setLocalModules(prev => prev.map(m => m.id === moduleId ? { ...m, lessons: m.lessons?.map(l => l.id === lessonId ? { ...l, completed: false } : l) || [] } : m));
        setQuizUnlockVersion(v => v + 1);
        router.delete(route('lessons.incomplete', lessonId), {
            preserveState: true, preserveScroll: true,
            onSuccess: (page) => { if (page.props?.flash?.modules) setLocalModules(page.props.flash.modules); setQuizUnlockVersion(v => v + 1); toast.success('Lesson marked as incomplete'); },
            onError: () => { setLocalModules(prev => prev.map(m => m.id === moduleId ? { ...m, lessons: m.lessons?.map(l => l.id === lessonId ? { ...l, completed: true } : l) || [] } : m)); toast.error('Failed to update lesson status'); }
        });
    }, []);

    const toggleModule = useCallback((id) => setExpandedModules(p => ({ ...p, [id]: !p[id] })), []);

    const markModuleRead = useCallback(async (moduleId) => {
        if (readModules[moduleId] || markingReadModule === moduleId) return;
        setMarkingReadModule(moduleId);
        try {
            const res = await window.axios.post(route('modules.reading-progress', moduleId), { reading_progress: 100, read: true });
            setReadModules(p => ({ ...p, [moduleId]: true }));
            setModuleReadingProgress(p => ({ ...p, [moduleId]: 100 }));
            setLocalModules(prev => prev.map(m => m.id === moduleId ? { ...m, read: true, reading_progress: 100, read_at: res.data?.module?.read_at } : m));
            if (res.data?.enrollment?.progress !== undefined) setEnrollmentProgress(res.data.enrollment.progress);
            setQuizUnlockVersion(v => v + 1);
            toast.success('Module marked as read.');
        } catch (e) { toast.error(e.response?.data?.message || 'Unable to save module reading progress.'); }
        finally { setMarkingReadModule(null); }
    }, [markingReadModule, readModules]);

    const updateModuleReadingProgress = useCallback((id, p) => setModuleReadingProgress(prev => ({ ...prev, [id]: Math.max(prev[id] || 0, p) })), []);

    const handleStartQuiz = useCallback((quiz) => {
        if (quiz.is_locked_out) { setLockedQuiz(quiz); return; }
        if (!quiz.unlocked) { toast.error(quiz.reason || 'Complete lessons first'); return; }
        setProcessingExam(quiz.id);
        window.location.href = route('dashboard.quiz.take', { course: course.slug, assessment: quiz.quiz_ids?.[0] || quiz.id });
    }, [course?.slug]);

    const handleStartFinalQuiz = useCallback(() => {
        const q = quizzesWithUnlockStatus[0];
        if (q) handleStartQuiz(q); else toast.error("No quiz available.");
    }, [quizzesWithUnlockStatus, handleStartQuiz]);

    // Helper to determine overall assessment status message
    const getAssessmentStatusMessage = () => {
        const quiz = quizzesWithUnlockStatus[0];
        const hasQuiz = !!quiz;
        const hasEssay = quiz?.has_essay_questions;
        const hasProject = !!diplomaAssessment;
        
        const partASubmitted = Boolean(quiz?.part_a_submitted ?? quiz?.submitted);
        const quizPassed = quiz?.passed === true || quiz?.passed === 1;
        const quizFailed = partASubmitted && !quizPassed;
        const quizDone = partASubmitted;
        const essayDone = quiz?.essay_submitted;
        const projectDone = diplomaAssessment?.submitted;

        if (!hasQuiz && !hasProject) return null;

        if (quizFailed) {
            return { type: 'warning', msg: 'You did not pass the quiz. Please retake the quiz.' };
        }

        // Scenario: Quiz + Essay + Project
        if (hasQuiz && hasEssay && hasProject) {
            if (!quizDone) return { type: 'neutral', msg: 'Quiz ready to start.' };
            if (!essayDone) return { type: 'info', msg: 'Part A passed. Essay response pending.' };
            if (!projectDone) return { type: 'info', msg: 'Quiz & Essay submitted. Project pending.' };
            return { type: 'success', msg: 'All assessments submitted!' };
        }

        // Scenario: Quiz + Essay (No Project)
        if (hasQuiz && hasEssay && !hasProject) {
            if (!quizDone) return { type: 'neutral', msg: 'Quiz ready to start.' };
            if (!essayDone) return { type: 'info', msg: 'Part A passed. Complete your essay to finish.' };
            return { type: 'success', msg: 'All assessments submitted!' };
        }

        // Scenario: Quiz Only
        if (hasQuiz && !hasEssay && !hasProject) {
            return quizDone ? { type: 'success', msg: 'Quiz Passed' } : { type: 'neutral', msg: 'Ready to start quiz.' };
        }

        return null;
    };

    const statusMsg = getAssessmentStatusMessage();

    return (
        <AuthenticatedLayout>
            <Head title={`${course?.title || 'Course'} | My Learning`} />
            
            {lockedQuiz && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                    <div className="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center relative animate-in fade-in zoom-in duration-300">
                        <button onClick={() => setLockedQuiz(null)} className="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                        <div className="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4"><LockClosedIcon className="w-8 h-8 text-red-600" /></div>
                        <h2 className="text-2xl font-bold text-gray-900 mb-2">Quiz Locked</h2>
                        <p className="text-gray-600 mb-6">You did not pass the previous attempt. Please review the course material and try again after the cooldown period.</p>
                        <div className="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-200">
                            <p className="text-sm text-gray-500 mb-1">Time Remaining</p>
                            <div className="flex items-center justify-center gap-2 text-3xl font-mono font-bold text-gray-800"><ClockIcon className="w-6 h-6 text-blue-600" />{timeRemaining}</div>
                        </div>
                        <button onClick={() => setLockedQuiz(null)} className="w-full py-3 bg-gray-900 text-white font-semibold rounded-xl hover:bg-gray-800 transition">I Understand</button>
                    </div>
                </div>
            )}

            <div className="min-h-screen bg-gray-50 py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <Breadcrumb courseTitle={course?.title} />
                    {candidate && <CandidateBanner candidate={candidate} enrollment={enrollment} />}

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div className="lg:col-span-2 space-y-6">
                            <CourseHeader course={course} enrollment={enrollment} modulesCount={localModules?.length || 0} progress={progress} />
                            <div className="space-y-4">
                                <h2 className="text-xl font-semibold text-gray-900 flex items-center gap-2"><BookOpenIcon className="w-5 h-5 text-indigo-600" />Course Content</h2>
                                <ModuleList 
                                    modules={localModules} expandedModules={expandedModules} toggleModule={toggleModule}
                                    completingLesson={completingLesson} markLessonComplete={markLessonComplete} markLessonIncomplete={markLessonIncomplete}
                                    readModules={readModules} moduleReadingProgress={moduleReadingProgress} markModuleRead={markModuleRead}
                                    updateModuleReadingProgress={updateModuleReadingProgress} totalModulesCount={totalModulesCount} onStartCourseQuiz={handleStartFinalQuiz}
                                />
                            </div>
                        </div>

                        {/* RIGHT COLUMN: SIDEBAR */}
                        <div className="space-y-6">
                            
                            {/* ASSESSMENT STATUS OVERVIEW */}
                            {statusMsg && (
                                <div className={`rounded-xl border p-4 flex items-start gap-3 ${
                                    statusMsg.type === 'success' ? 'bg-green-50 border-green-200 text-green-800' :
                                    statusMsg.type === 'warning' ? 'bg-amber-50 border-amber-200 text-amber-800' :
                                    statusMsg.type === 'info' ? 'bg-blue-50 border-blue-200 text-blue-800' :
                                    'bg-gray-50 border-gray-200 text-gray-700'
                                }`}>
                                    {statusMsg.type === 'success' ? <CheckCircleIcon className="w-5 h-5 mt-0.5" /> :
                                     statusMsg.type === 'warning' ? <ExclamationCircleIcon className="w-5 h-5 mt-0.5" /> :
                                     <DocumentTextIcon className="w-5 h-5 mt-0.5" />}
                                    <p className="text-sm font-medium">{statusMsg.msg}</p>
                                </div>
                            )}

                            {/* QUIZ SECTION */}
                            {quizzesWithUnlockStatus.map((quiz) => {
                                const partASubmitted = Boolean(quiz.part_a_submitted ?? quiz.submitted);
                                const quizPassed = quiz.passed === true || quiz.passed === 1;
                                const quizFailed = partASubmitted && !quizPassed;
                                const isSubmitted = partASubmitted;
                                const hasEssay = quiz.has_essay_questions;
                                const essayPending = isSubmitted && quizPassed && hasEssay && !quiz.essay_submitted;
                            
                                return (
                                    <div key={quiz.id} className="bg-white rounded-xl border border-gray-200 p-5">
                                        <h3 className="font-semibold text-gray-900 mb-3">
                                            {hasEssay ? 'Part A: Quiz' : 'Course Exam'}
                                        </h3>
                                        
                                        {essayPending ? (
                                            // Quiz done but essay pending
                                            <div className="space-y-3">
                                                <div className="flex items-center gap-2 text-green-700 text-sm font-medium">
                                                    <CheckCircleIcon className="w-4 h-4" /> Quiz Submitted ({quiz.score}%)
                                                </div>
                                                <button 
                                                    onClick={() => handleStartQuiz(quiz)} 
                                                    className="w-full py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition flex items-center justify-center gap-2"
                                                >
                                                    ✍️ Continue to Essay (Part B)
                                                </button>
                                            </div>
                                        ) : isSubmitted && quizPassed ? (
                                            <button disabled className="w-full py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg cursor-default flex items-center justify-center gap-2 font-medium">
                                                <ClipboardDocumentCheckIcon className="w-4 h-4" /> Quiz Passed
                                            </button>
                                        ) : quiz.is_locked_out ? (
                                            <button onClick={() => setLockedQuiz(quiz)} className="w-full py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg cursor-pointer hover:bg-red-100 flex items-center justify-center gap-2 transition">
                                                <LockClosedIcon className="w-4 h-4" /> Locked ({quiz.lock_expires_at ? new Date(quiz.lock_expires_at).toLocaleDateString() : ''})
                                            </button>
                                        ) : quizFailed ? (
                                            <button onClick={() => handleStartQuiz(quiz)} className="w-full py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition flex items-center justify-center gap-2">
                                                📝 Retake the Quiz
                                            </button>
                                        ) : !quiz.unlocked ? (
                                            <div>
                                                <button disabled className="w-full py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed flex items-center justify-center gap-2">🔒 Start Exam</button>
                                                <p className="mt-2 text-xs text-gray-500 text-center">{quiz.reason}</p>
                                            </div>
                                        ) : (
                                            <button onClick={() => handleStartQuiz(quiz)} className="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                                                📝 Start Exam
                                            </button>
                                        )}
                                    </div>
                                );
                            })}

                            {/* PROJECT ASSESSMENT SECTION */}
                            {hasPassedQuiz && diplomaAssessment && (
                                <div className="bg-white rounded-xl border border-gray-200 p-5">
                                    <h3 className="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                        <AcademicCapIcon className="w-5 h-5 text-indigo-600" /> Project Assessment
                                    </h3>

                                    {diplomaAssessment.submitted ? (
                                        <button disabled className="w-full py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg cursor-default flex items-center justify-center gap-2 font-medium">
                                            <ClipboardDocumentCheckIcon className="w-4 h-4" /> Project Submitted
                                        </button>
                                    ) : (
                                        <>
                                            <p className="text-sm text-gray-600 mb-4">Your exam is passed. You can now access the project assessment.</p>
                                            <Link href={route('dashboard.quiz.project-assessment', { course: course.slug })} className="w-full flex items-center justify-center gap-2 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                                Open Project Assessment
                                            </Link>
                                        </>
                                    )}
                                </div>
                            )}

                            {/* APPLICATION UNDER REVIEW (Only if no project assessment exists but quiz is done) */}
                            {hasPassedQuiz && !diplomaAssessment && (
                                <div className="bg-white rounded-xl border border-gray-200 p-5">
                                    <h3 className="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                        <ClipboardDocumentCheckIcon className="w-5 h-5 text-indigo-600" /> Application Status
                                    </h3>
                                    <div className="bg-blue-50 border border-blue-100 rounded-lg p-4">
                                        <p className="text-sm text-blue-800 font-medium flex items-start gap-2">
                                            <svg className="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            <span>Your submitted application is currently under review by our team. You will be notified via email once the application process is complete.</span>
                                        </p>
                                    </div>
                                </div>
                            )}

                            {canShowCertificationCard && (
                                <CertificationCard enrollment={enrollment} hasCertificate={hasCertificate} certificateNumber={certificateNumber} isIdentityVerified={isIdentityVerified} examResults={examResults} certification={certification} progress={progress} />
                            )}

                            <MaterialList materials={courseMaterials} title="Course Materials" />
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
