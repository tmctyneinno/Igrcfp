import React, { useState, useCallback, useEffect } from 'react';
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
    // Temporary debug
    console.log('initialModules on render:', JSON.stringify(
        initialModules?.[0]?.lessons?.map(l => ({ id: l.id, completed: l.completed }))
    ));
    const [expandedModules, setExpandedModules] = useState({});
    const [completingLesson, setCompletingLesson] = useState(null);
    const [processingExam, setProcessingExam] = useState(null);
    const [moduleCompletionStatus, setModuleCompletionStatus] = useState({});
    const [localModules, setLocalModules] = useState(initialModules);

    

    const progress = enrollment?.progress || 0;
    const hasCertificate = enrollment?.certificate_generated;
    const certificateNumber = enrollment?.certificate_number || candidate?.certificate_id;
    const isIdentityVerified = enrollment?.identity_verified || false;

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
            
            toast.success(`🎉 Module ${module.module_number} completed! Next module unlocked.`, {
                duration: 5000,
                icon: '🔓'
            });
            
            return true;
        }
        
        return allLessonsCompleted;
    }, [localModules, moduleCompletionStatus]);

    // Mark lesson as complete
    // In EnrollmentIndex.jsx - markLessonComplete function

    const markLessonComplete = useCallback((lessonId, moduleId, metadata = {}) => {
    console.log('EnrollmentIndex markLessonComplete:', { lessonId, moduleId, metadata });
    
    setCompletingLesson(lessonId);
    
    // Optimistically update local state FIRST
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
        return updated;
    });
    
   router.post(route('lessons.complete', lessonId), {
    time_spent: metadata.timeSpent || 0,
    auto_completed: metadata.autoCompleted || false,
    scroll_progress: metadata.scrollProgress || 100,
}, {
    preserveState: true,
    preserveScroll: true,
    only: ['flash'],  // 👈 Only fetch flash, ignore modules/enrollment props
    onSuccess: (page) => {
        setCompletingLesson(null);
        
        if (moduleId) {
            checkModuleCompletion(moduleId);
        }
        
        toast.success('Lesson completed!');
    },
    onError: (errors) => {
        console.error('ERROR:', errors);  // 👈 Add this if not there
        toast.error('Failed: ' + JSON.stringify(errors));
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
        
        router.delete(route('lessons.incomplete', lessonId), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                if (page.props.modules) {
                    setLocalModules(page.props.modules);
                }
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

    // Toggle module expansion
    const toggleModule = useCallback((moduleId) => {
        setExpandedModules(prev => ({
            ...prev,
            [moduleId]: !prev[moduleId]
        }));
    }, []);

    // Assessment navigation functions
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

    return (
        <AuthenticatedLayout>
            <Head title={`${course?.title || 'Course'} | My Learning`} />

            <div className="min-h-screen bg-gray-50 py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <Breadcrumb courseTitle={course?.title} />

                    {candidate && <CandidateBanner candidate={candidate} enrollment={enrollment} />}

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {/* Left Column - Course Info & Modules */}
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

                        {/* Right Column - Assessments & Certification */}
                        <div className="space-y-6">
                            <IdentityVerificationCard 
                                enrollment={enrollment} 
                                isIdentityVerified={isIdentityVerified} 
                            />

                            <AssessmentList
                                title="Module Quizzes"
                                icon="quiz"
                                assessments={quizzes}
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
                                assessments={moduleAssessments}
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