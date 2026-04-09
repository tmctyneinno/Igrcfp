import React, { useState, useCallback, useEffect, useMemo } from 'react';
import { Head, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import toast from 'react-hot-toast';
import { BookOpenIcon } from '@heroicons/react/24/outline';

// Import components
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
    const [quizUnlockVersion, setQuizUnlockVersion] = useState(0); // Force recompute

    // Sync with initialModules when they change from server
    useEffect(() => {
        console.log('initialModules updated:', initialModules);
        setLocalModules(initialModules);
    }, [initialModules]);

    const progress = enrollment?.progress || 0;
    const hasCertificate = enrollment?.certificate_generated;
    const certificateNumber = enrollment?.certificate_number || candidate?.certificate_id;
    const isIdentityVerified = enrollment?.identity_verified || false;

    // Helper function to check if a quiz/module assessment is unlocked
    const getQuizUnlockStatus = useCallback((quiz) => {
        // Convert module_id to number for comparison
        const quizModuleId = typeof quiz.module_id === 'string' 
            ? parseInt(quiz.module_id, 10) 
            : quiz.module_id;
        
        console.log('getQuizUnlockStatus - quiz:', { 
            id: quiz.id, 
            module_id: quizModuleId,
            title: quiz.title 
        });
        
        // Find the module this quiz belongs to
        const module = localModules.find(m => {
            const moduleId = typeof m.id === 'string' ? parseInt(m.id, 10) : m.id;
            return moduleId === quizModuleId;
        });
        
        console.log('getQuizUnlockStatus - found module:', module ? {
            id: module.id,
            title: module.title,
            lessonsCount: module.lessons?.length
        } : 'NOT FOUND');
        
        if (!module) {
            // If no module found, quiz might be general - unlocked by default
            return { unlocked: true, reason: null, progress: 100 };
        }
        
        const totalLessons = module.lessons?.length || 0;
        const completedLessons = module.lessons?.filter(l => l.completed).length || 0;
        const allLessonsComplete = totalLessons > 0 && completedLessons === totalLessons;
        
        console.log('getQuizUnlockStatus - lesson status:', {
            totalLessons,
            completedLessons,
            allLessonsComplete
        });
        
        return {
            unlocked: allLessonsComplete,
            reason: allLessonsComplete 
                ? null 
                : `Complete all lessons in "${module.title}" first (${completedLessons}/${totalLessons})`,
            progress: totalLessons > 0 ? Math.round((completedLessons / totalLessons) * 100) : 0
        };
    }, [localModules, quizUnlockVersion]); // Add quizUnlockVersion to force recompute

    // Use useMemo to recompute when dependencies change
    const quizzesWithUnlockStatus = useMemo(() => {
        console.log('Computing quizzesWithUnlockStatus...');
        const result = quizzes.map(quiz => ({
            ...quiz,
            ...getQuizUnlockStatus(quiz)
        }));
        console.log('quizzesWithUnlockStatus result:', result);
        return result;
    }, [quizzes, getQuizUnlockStatus, quizUnlockVersion]);

    // Also apply to module assessments
    const moduleAssessmentsWithUnlockStatus = useMemo(() => {
        return moduleAssessments.map(assessment => ({
            ...assessment,
            ...getQuizUnlockStatus(assessment)
        }));
    }, [moduleAssessments, getQuizUnlockStatus, quizUnlockVersion]);

    // Check if module is complete
    const checkModuleCompletion = useCallback((moduleId) => {
        const module = localModules.find(m => m.id === moduleId);
        if (!module) return false;
        
        const allLessonsCompleted = module.lessons?.every(l => l.completed) ?? false;
        
        if (allLessonsCompleted && !moduleCompletionStatus[moduleId]) {
            setModuleCompletionStatus(prev => ({
                ...prev,
                [moduleId]: true
            }));
            
            // Force quiz unlock status to recompute
            setQuizUnlockVersion(prev => prev + 1);
            
            toast.success(`🎉 Module ${module.module_number} completed! Quiz now unlocked.`, {
                duration: 5000,
                icon: '🔓'
            });
            
            return true;
        }
        
        return allLessonsCompleted;
    }, [localModules, moduleCompletionStatus]);

    // Mark lesson as complete
    const markLessonComplete = useCallback((lessonId, moduleId, metadata = {}) => {
        console.log('EnrollmentIndex markLessonComplete:', { lessonId, moduleId, metadata });
        
        setCompletingLesson(lessonId);
        
        // Optimistically update local state
        setLocalModules(prevModules => {
            const updated = prevModules.map(module => {
                if (module.id === moduleId) {
                    return {
                        ...module,
                        lessons: module.lessons?.map(lesson => 
                            lesson.id === lessonId 
                                ? { ...lesson, completed: true } 
                                : lesson
                        ) || []
                    };
                }
                return module;
            });
            console.log('Optimistically updated modules:', updated);
            return updated;
        });
        
        router.post(route('lessons.complete', lessonId), {
            time_spent: metadata.timeSpent || 0,
            auto_completed: metadata.autoCompleted || false,
            scroll_progress: metadata.scrollProgress || 100,
        }, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                setCompletingLesson(null);
                
                // Update with server data if provided
                if (page.props?.flash?.modules) {
                    console.log('Updating with server modules');
                    setLocalModules(page.props.flash.modules);
                }
                
                // Force quiz unlock status to recompute
                setQuizUnlockVersion(prev => prev + 1);
                
                // Check module completion - this will show the unlock toast
                if (moduleId) {
                    checkModuleCompletion(moduleId);
                }
                
                toast.success('Lesson completed!');
            },
            onError: (errors) => {
                console.error('Server error:', errors);
                setCompletingLesson(null);
                
                // Revert optimistic update on error
                setLocalModules(prevModules =>
                    prevModules.map(module => {
                        if (module.id === moduleId) {
                            return {
                                ...module,
                                lessons: module.lessons?.map(lesson => 
                                    lesson.id === lessonId 
                                        ? { ...lesson, completed: false } 
                                        : lesson
                                ) || []
                            };
                        }
                        return module;
                    })
                );
                toast.error('Failed to mark lesson as complete');
            },
        });
    }, [checkModuleCompletion]);

    // Mark lesson as incomplete
    const markLessonIncomplete = useCallback((lessonId, moduleId) => {
        setLocalModules(prevModules => 
            prevModules.map(module => {
                if (module.id === moduleId) {
                    return {
                        ...module,
                        lessons: module.lessons?.map(lesson => 
                            lesson.id === lessonId 
                                ? { ...lesson, completed: false } 
                                : lesson
                        ) || []
                    };
                }
                return module;
            })
        );
        
        // Force recompute
        setQuizUnlockVersion(prev => prev + 1);
        
        router.delete(route('lessons.incomplete', lessonId), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                if (page.props?.flash?.modules) {
                    setLocalModules(page.props.flash.modules);
                }
                setQuizUnlockVersion(prev => prev + 1);
                toast.success('Lesson marked as incomplete');
            },
            onError: () => {
                setLocalModules(prevModules => 
                    prevModules.map(module => {
                        if (module.id === moduleId) {
                            return {
                                ...module,
                                lessons: module.lessons?.map(lesson => 
                                    lesson.id === lessonId 
                                        ? { ...lesson, completed: true } 
                                        : lesson
                                ) || []
                            };
                        }
                        return module;
                    })
                );
                toast.error('Failed to update lesson status');
            }
        });
    }, []);

    const toggleModule = useCallback((moduleId) => {
        setExpandedModules(prev => ({
            ...prev,
            [moduleId]: !prev[moduleId]
        }));
    }, []);

    const handleStartAssessment = useCallback((assessmentId, type) => {
        if ((type === 'final_exam' || type === 'diploma') && !isIdentityVerified) {
            toast.error('Please verify your identity first before starting this assessment.');
            document.getElementById('identity-verification-section')?.scrollIntoView({ behavior: 'smooth' });
            return;
        }
        
        setProcessingExam(assessmentId);
        
        const urlMap = {
            'quiz': `/assessment/quiz/${enrollment.id}/${assessmentId}`,
            'module_assessment': `/assessment/module/${enrollment.id}/${assessmentId}`,
            'final_exam': `/assessment/final/${enrollment.id}/${assessmentId}`,
            'diploma': `/assessment/diploma/${enrollment.id}/${assessmentId}`
        };
        
        window.location.href = urlMap[type];
    }, [enrollment?.id, isIdentityVerified]);

    const handleContinueAssessment = useCallback((assessmentId, type) => {
        setProcessingExam(assessmentId);
        
        const urlMap = {
            'quiz': `/assessment/quiz/continue/${enrollment.id}/${assessmentId}`,
            'module_assessment': `/assessment/module/continue/${enrollment.id}/${assessmentId}`,
            'final_exam': `/assessment/final/continue/${enrollment.id}/${assessmentId}`,
            'diploma': `/assessment/diploma/continue/${enrollment.id}/${assessmentId}`
        };
        
        window.location.href = urlMap[type];
    }, [enrollment?.id]);

    const handleReviewAssessment = useCallback((assessmentId, type) => {
        const urlMap = {
            'quiz': `/assessment/quiz/review/${enrollment.id}/${assessmentId}`,
            'module_assessment': `/assessment/module/review/${enrollment.id}/${assessmentId}`,
            'final_exam': `/assessment/final/review/${enrollment.id}/${assessmentId}`,
            'diploma': `/assessment/diploma/review/${enrollment.id}/${assessmentId}`
        };
        
        window.location.href = urlMap[type];
    }, [enrollment?.id]);

    // Debug log
    useEffect(() => {
        console.log('=== STATE UPDATED ===');
        console.log('localModules lessons completed:', 
            localModules.map(m => ({
                id: m.id,
                title: m.title,
                completed: m.lessons?.filter(l => l.completed).length,
                total: m.lessons?.length
            }))
        );
        console.log('quizzesWithUnlockStatus:', 
            quizzesWithUnlockStatus.map(q => ({
                id: q.id,
                title: q.title,
                unlocked: q.unlocked,
                reason: q.reason
            }))
        );
    }, [localModules, quizzesWithUnlockStatus]);

    return (
        <AuthenticatedLayout>
            <Head title={`${course?.title || 'Course'} | My Learning`} />

            <div className="min-h-screen bg-gray-50 py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <Breadcrumb courseTitle={course?.title} />

                    {candidate && <CandidateBanner candidate={candidate} enrollment={enrollment} />}

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div className="lg:col-span-2 space-y-6">
                            <CourseHeader 
                                course={course} 
                                enrollment={enrollment} 
                                modulesCount={localModules?.length || 0} 
                                progress={progress} 
                            />

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
                                    moduleCompletionStatus={moduleCompletionStatus}
                                />
                            </div>
                        </div>

                        <div className="space-y-6">
                            <IdentityVerificationCard 
                                enrollment={enrollment} 
                                isIdentityVerified={isIdentityVerified} 
                            />

                            <AssessmentList
                                title="Module Quizzes"
                                icon="quiz"
                                assessments={quizzesWithUnlockStatus}
                                type="quiz"
                                processingExam={processingExam}
                                isIdentityVerified={isIdentityVerified}
                                onStart={handleStartAssessment}
                                onContinue={handleContinueAssessment}
                                onReview={handleReviewAssessment}
                            />

                            <AssessmentList
                                title="Module Assessments"
                                icon="module"
                                assessments={moduleAssessmentsWithUnlockStatus}
                                type="module_assessment"
                                processingExam={processingExam}
                                isIdentityVerified={isIdentityVerified}
                                onStart={handleStartAssessment}
                                onContinue={handleContinueAssessment}
                                onReview={handleReviewAssessment}
                            />

                            {finalExam && (
                                <AssessmentList
                                    title="Final Exam"
                                    icon="final"
                                    assessments={[finalExam]}
                                    type="final_exam"
                                    processingExam={processingExam}
                                    isIdentityVerified={isIdentityVerified}
                                    onStart={handleStartAssessment}
                                    onContinue={handleContinueAssessment}
                                    onReview={handleReviewAssessment}
                                />
                            )}

                            {diplomaAssessment && (
                                <AssessmentList
                                    title="Diploma Project"
                                    icon="diploma"
                                    assessments={[diplomaAssessment]}
                                    type="diploma"
                                    processingExam={processingExam}
                                    isIdentityVerified={isIdentityVerified}
                                    onStart={handleStartAssessment}
                                    onContinue={handleContinueAssessment}
                                    onReview={handleReviewAssessment}
                                />
                            )}

                            <CertificationCard
                                enrollment={enrollment}
                                hasCertificate={hasCertificate}
                                certificateNumber={certificateNumber}
                                isIdentityVerified={isIdentityVerified}
                                examResults={examResults}
                                progress={progress}
                            />
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}