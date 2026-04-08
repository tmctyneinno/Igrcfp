import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { motion } from 'framer-motion';
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
    modules = [], 
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
    
    const progress = enrollment?.progress || 0;
    const hasCertificate = enrollment?.certificate_generated;
    const certificateNumber = enrollment?.certificate_number || candidate?.certificate_id;
    const isIdentityVerified = enrollment?.identity_verified || false;
    
    // Lesson completion functions
    const markLessonComplete = (lessonId) => {
        setCompletingLesson(lessonId);
        
        router.post(route('lessons.complete', lessonId), {}, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                setCompletingLesson(null);
                if (page.props.flash?.success) {
                    toast.success(page.props.flash.success);
                }
            },
            onError: () => {
                setCompletingLesson(null);
                toast.error('Failed to mark lesson as complete');
            }
        });
    };

    const markLessonIncomplete = (lessonId) => {
        router.delete(route('lessons.incomplete', lessonId), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                if (page.props.flash?.success) {
                    toast.success(page.props.flash.success);
                }
            },
            onError: () => {
                toast.error('Failed to update lesson status');
            }
        });
    };

    const toggleModule = (moduleId) => {
        setExpandedModules(prev => ({
            ...prev,
            [moduleId]: !prev[moduleId]
        }));
    };

    // Assessment navigation functions
    const handleStartAssessment = (assessmentId, type) => {
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
    };

    const handleContinueAssessment = (assessmentId, type) => {
        setProcessingExam(assessmentId);
        
        const urlMap = {
            'quiz': `/assessment/quiz/continue/${enrollment.id}/${assessmentId}`,
            'module_assessment': `/assessment/module/continue/${enrollment.id}/${assessmentId}`,
            'final_exam': `/assessment/final/continue/${enrollment.id}/${assessmentId}`,
            'diploma': `/assessment/diploma/continue/${enrollment.id}/${assessmentId}`
        };
        
        window.location.href = urlMap[type];
    };

    const handleReviewAssessment = (assessmentId, type) => {
        const urlMap = {
            'quiz': `/assessment/quiz/review/${enrollment.id}/${assessmentId}`,
            'module_assessment': `/assessment/module/review/${enrollment.id}/${assessmentId}`,
            'final_exam': `/assessment/final/review/${enrollment.id}/${assessmentId}`,
            'diploma': `/assessment/diploma/review/${enrollment.id}/${assessmentId}`
        };
        
        window.location.href = urlMap[type];
    };

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
                                modulesCount={modules?.length || 0} 
                                progress={progress} 
                            />

                            <div className="space-y-4">
                                <h2 className="text-xl font-semibold text-gray-900 flex items-center gap-2">
                                    <BookOpenIcon className="w-5 h-5 text-indigo-600" />
                                    Course Content
                                </h2>
                                
                                <ModuleList
                                    modules={modules}
                                    expandedModules={expandedModules}
                                    toggleModule={toggleModule}
                                    completingLesson={completingLesson}
                                    markLessonComplete={markLessonComplete}
                                    markLessonIncomplete={markLessonIncomplete}
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