import React, { useState, useEffect } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import toast from 'react-hot-toast';
import { 
    DocumentTextIcon,
    ClockIcon,
    CheckCircleIcon,
    XCircleIcon,
    ArrowUpTrayIcon,
    AcademicCapIcon,
    ArrowDownTrayIcon,
    CalendarIcon,
    ExclamationTriangleIcon,
    PencilSquareIcon,
    EyeIcon,
    TrophyIcon
} from '@heroicons/react/24/outline';

export default function ProjectSubmit({ 
    course, 
    assessment, 
    enrollment, 
    existingSubmission 
}) {
    const [file, setFile] = useState(null);
    const [notes, setNotes] = useState('');
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [showSubmissionForm, setShowSubmissionForm] = useState(false);
    const [showConfirmModal, setShowConfirmModal] = useState(false);
    
    const hasSubmitted = existingSubmission && existingSubmission.status !== 'draft';
    const isOverdue = assessment.due_date && new Date(assessment.due_date) < new Date();
    const canSubmit = !hasSubmitted || existingSubmission?.status === 'draft';
    
    // Calculate days remaining
    const getDaysRemaining = () => {
        if (!assessment.due_date) return null;
        const due = new Date(assessment.due_date);
        const now = new Date();
        const diff = due - now;
        const days = Math.ceil(diff / (1000 * 60 * 60 * 24));
        
        if (days < 0) return { text: 'Overdue', color: 'text-red-600', bg: 'bg-red-50' };
        if (days === 0) return { text: 'Due today', color: 'text-orange-600', bg: 'bg-orange-50' };
        if (days <= 3) return { text: `${days} days remaining`, color: 'text-yellow-600', bg: 'bg-yellow-50' };
        return { text: `${days} days remaining`, color: 'text-green-600', bg: 'bg-green-50' };
    };
    
    const daysRemaining = getDaysRemaining();
    
    const handleFileChange = (e) => {
        const selectedFile = e.target.files[0];
        if (selectedFile) {
            const maxSize = (assessment.settings?.max_file_size || 50) * 1024 * 1024;
            if (selectedFile.size > maxSize) {
                toast.error(`File size exceeds ${assessment.settings?.max_file_size || 50}MB limit`);
                e.target.value = '';
                return;
            }
            setFile(selectedFile);
        }
    };
    
    const handleSubmit = (e) => {
        e.preventDefault();
        
        if (!file && !existingSubmission) {
            toast.error('Please select a file to submit');
            return;
        }
        
        // Show confirmation modal instead of submitting directly
        setShowConfirmModal(true);
    };
    
    const handleConfirmSubmit = () => {
        setShowConfirmModal(false);
        setIsSubmitting(true);
        
        const formData = new FormData();
        if (file) {
            formData.append('submission_file', file);
        }
        formData.append('submission_notes', notes);
        
        router.post(route('dashboard.quiz.project.submit', { 
            course: course.slug, 
            assessment: assessment.id 
        }), formData, {
            onFinish: () => setIsSubmitting(false),
            onSuccess: () => {
                toast.success('Project submitted successfully!');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            },
            onError: (errors) => {
                toast.error(errors.message || 'Failed to submit project');
            },
        });
    };
    
    return (
        <AuthenticatedLayout>
            <Head title={`${assessment.title} | Project`} />
            
            <div className="min-h-screen bg-gray-50 py-8">
                <div className="max-w-5xl mx-auto px-4">
                    {/* Header */}
                    <div className="mb-6">
                        <Link 
                            href={route('dashboard.courses.show', course.slug)} 
                            className="text-sm text-gray-500 hover:text-gray-700"
                        >
                            ← Back to Course
                        </Link>
                        <h1 className="text-2xl font-bold text-gray-900 mt-2">Project Assessment</h1>
                        <p className="text-gray-600">Final Project Assessment</p>
                    </div>
                    
                    {/* Status Banner */}
                    <div className={`mb-6 p-4 rounded-xl ${hasSubmitted ? 'bg-green-50 border border-green-200' : 'bg-blue-50 border border-blue-200'}`}>
                        <div className="flex items-center justify-between">
                            <div className="flex items-center gap-3">
                                {hasSubmitted ? (
                                    <CheckCircleIcon className="w-6 h-6 text-green-600" />
                                ) : (
                                    <AcademicCapIcon className="w-6 h-6 text-blue-600" />
                                )}
                                <div>
                                    <h2 className="font-semibold text-gray-900">
                                        {hasSubmitted ? 'Project Submitted' : 'Project Pending'}
                                    </h2>
                                    <p className="text-sm text-gray-600">
                                        {hasSubmitted 
                                            ? `Submitted on ${existingSubmission.submitted_at}` 
                                            : 'Review the project requirements and submit when ready'}
                                    </p>
                                </div>
                            </div>
                            
                            {daysRemaining && !hasSubmitted && (
                                <div className={`px-4 py-2 rounded-lg ${daysRemaining.bg}`}>
                                    <span className={`font-medium ${daysRemaining.color}`}>
                                        {daysRemaining.text}
                                    </span>
                                </div>
                            )}
                        </div>
                        
                        {/* Score if graded */}
                        {existingSubmission?.status === 'graded' && (
                            <div className="mt-4 pt-4 border-t border-green-200">
                                <div className="flex items-center justify-between">
                                    <span className="font-medium text-gray-700">Your Score</span>
                                    <span className={`text-2xl font-bold ${existingSubmission.passed ? 'text-green-600' : 'text-red-600'}`}>
                                        {existingSubmission.percentage}%
                                    </span>
                                </div>
                                {existingSubmission.feedback && (
                                    <div className="mt-3">
                                        <p className="text-sm font-medium text-gray-700">Feedback:</p>
                                        <p className="text-gray-600">{existingSubmission.feedback}</p>
                                    </div>
                                )}
                            </div>
                        )}
                    </div>
                    
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {/* Left - Project Brief & Details */}
                        <div className="lg:col-span-2 space-y-6">
                            {/* Project Topics */}
                            {assessment.settings?.project_topics && (
                                <div className="bg-white rounded-xl shadow-sm p-6">
                                    <h3 className="text-lg font-semibold text-gray-900 mb-4">Project Topics</h3>
                                    <div className="prose max-w-none text-gray-600">
                                        <div dangerouslySetInnerHTML={{ __html: assessment.settings.project_topics }} />
                                    </div>
                                </div>
                            )}

                            {/* Project Overview */}
                            <div className="bg-white rounded-xl shadow-sm p-6">
                                <div className="flex items-center justify-between mb-4">
                                    <h3 className="text-lg font-semibold text-gray-900">Project Brief</h3>
                                    <div className="flex items-center gap-2 text-sm text-gray-500">
                                        <CalendarIcon className="w-4 h-4" />
                                        <span>Total Marks: {assessment.total_marks}</span>
                                        <span>•</span>
                                        <span>Passing: {assessment.passing_score}%</span>
                                    </div>
                                </div>
                                <div className="prose max-w-none text-gray-600">
                                    <div dangerouslySetInnerHTML={{ __html: assessment.project_brief }} />
                                </div>
                            </div>
                            
                            {/* Deliverables */}
                            {assessment.deliverables && (
                                <div className="bg-white rounded-xl shadow-sm p-6">
                                    <h3 className="text-lg font-semibold text-gray-900 mb-4">Deliverables</h3>
                                    <div className="prose max-w-none text-gray-600">
                                        <div dangerouslySetInnerHTML={{ __html: assessment.deliverables }} />
                                    </div>
                                </div>
                            )}
                            
                            {/* Instructions */}
                            {assessment.instructions && (
                                <div className="bg-white rounded-xl shadow-sm p-6">
                                    <h3 className="text-lg font-semibold text-gray-900 mb-4">Instructions</h3>
                                    <div className="prose max-w-none text-gray-600">
                                        <div dangerouslySetInnerHTML={{ __html: assessment.instructions }} />
                                    </div>
                                </div>
                            )}
                        </div>
                        
                        {/* Right - Submission Area */}
                        <div className="space-y-6">
                            {/* Submission Status Card */}
                            <div className="bg-white rounded-xl shadow-sm p-6">
                                <h3 className="text-lg font-semibold text-gray-900 mb-4">Submission Status</h3>
                                
                                {hasSubmitted ? (
                                    <div className="space-y-4">
                                        <div className="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                                            <DocumentTextIcon className="w-8 h-8 text-blue-600 flex-shrink-0" />
                                            <div className="flex-1 min-w-0">
                                                <p className="font-medium text-gray-900 truncate">{existingSubmission.file_name}</p>
                                                <p className="text-sm text-gray-500 ">
                                                    Submitted: {existingSubmission.submitted_at}
                                                </p>
                                            </div>
                                            <a  
                                                href={existingSubmission.file_url} 
                                                target="_blank"
                                                className="text-blue-600 hover:text-blue-800 flex-shrink-0"
                                            >
                                                <EyeIcon className="w-5 h-5" />
                                            </a>
                                        </div>
                                        
                                        {existingSubmission.status === 'graded' ? (
                                            <div className="bg-green-50 border border-green-200 rounded-lg p-4">
                                                <p className="text-sm text-green-800">
                                                    ✓ Your submission has been graded.
                                                </p>
                                            </div>
                                        ) : (
                                            <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                                <p className="text-sm text-yellow-800">
                                                    ⏳ Your submission is being reviewed. You'll be notified when graded.
                                                </p>
                                            </div>
                                        )}
                                        
                                        {/* Allow resubmission if enabled */}
                                        {assessment.settings?.allow_resubmission && existingSubmission.status !== 'graded' && (
                                            <button
                                                onClick={() => setShowSubmissionForm(true)}
                                                className="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                            >
                                                <PencilSquareIcon className="w-4 h-4 inline mr-1" />
                                                Submit New Version
                                            </button>
                                        )}
                                    </div>
                                ) : showSubmissionForm ? (
                                    <form onSubmit={handleSubmit} className="space-y-4">
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                                Upload File <span className="text-red-500">*</span>
                                            </label>
                                            <div className="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition cursor-pointer">
                                                <ArrowUpTrayIcon className="w-10 h-10 text-gray-400 mx-auto mb-2" />
                                                
                                                <div className="mb-2">
                                                    {file ? (
                                                        <div className="flex items-center justify-center gap-2">
                                                            <DocumentTextIcon className="w-4 h-4 text-blue-600 flex-shrink-0" />
                                                            <span className="text-sm text-gray-600 break-all line-clamp-1" title={file.name}>
                                                                {file.name}
                                                            </span>
                                                        </div>
                                                    ) : (
                                                        <p className="text-sm text-gray-600">
                                                            Click or drag & drop to select file
                                                        </p>
                                                    )}
                                                </div>
                                                
                                                {file && (
                                                    <p className="text-xs text-gray-500 mb-1">
                                                        {(file.size / (1024 * 1024)).toFixed(2)} MB
                                                    </p>
                                                )}
    
                                                <p className="text-xs text-gray-500">
                                                    Max size: {assessment.settings?.max_file_size || 50}MB
                                                </p>
                                                
                                                <p className="text-xs text-gray-400 mt-1">
                                                    Supported: PDF, DOC, DOCX, ZIP, PPT, PPTX
                                                </p>
                                                
                                                <input
                                                    type="file"
                                                    onChange={handleFileChange}
                                                    className="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                                    accept=".pdf,.doc,.docx,.zip,.ppt,.pptx"
                                                />
                                                
                                                {file && (
                                                    <button
                                                        type="button"
                                                        onClick={() => {
                                                            setFile(null);
                                                            const input = document.querySelector('input[type="file"]');
                                                            if (input) input.value = '';
                                                        }}
                                                        className="mt-3 text-xs text-blue-600 hover:text-blue-800 font-medium"
                                                    >
                                                        Choose a different file
                                                    </button>
                                                )}
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                                Submission Notes (Optional)
                                            </label>
                                            <textarea
                                                value={notes}
                                                onChange={(e) => setNotes(e.target.value)}
                                                rows={4}
                                                className="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Add any additional notes about your submission..."
                                            />
                                        </div>
                                        
                                        {isOverdue && !assessment.allow_late_submissions && (
                                            <div className="bg-red-50 border border-red-200 rounded-lg p-3">
                                                <ExclamationTriangleIcon className="w-4 h-4 text-red-600 inline mr-1" />
                                                <span className="text-sm text-red-800">
                                                    The deadline has passed. Late submissions are not allowed.
                                                </span>
                                            </div>
                                        )}
                                        
                                        <div className="flex gap-3">
                                            <button
                                                type="button"
                                                onClick={() => setShowSubmissionForm(false)}
                                                className="flex-1 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
                                            >
                                                Cancel
                                            </button>
                                            <button
                                                type="submit"
                                                disabled={isSubmitting || !file || (isOverdue && !assessment.allow_late_submissions)}
                                                className="flex-1 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
                                            >
                                                {isSubmitting ? (
                                                    <span className="flex items-center justify-center gap-2">
                                                        <div className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                                        Submitting...
                                                    </span>
                                                ) : (
                                                    'Submit Project'
                                                )}
                                            </button>
                                        </div>
                                    </form>
                                ) : (
                                    <div className="space-y-4">
                                        <div className="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                            <p className="text-sm text-blue-800">
                                                Review the project brief and requirements carefully before submitting.
                                            </p>
                                        </div>
                                        
                                        <button
                                            onClick={() => setShowSubmissionForm(true)}
                                            disabled={isOverdue && !assessment.allow_late_submissions}
                                            className="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
                                        >
                                            <ArrowUpTrayIcon className="w-4 h-4 inline mr-2" />
                                            {canSubmit ? 'Start Submission' : 'View Submission'}
                                        </button>
                                        
                                        {isOverdue && !assessment.allow_late_submissions && (
                                            <p className="text-sm text-red-600 text-center">
                                                The submission deadline has passed.
                                            </p>
                                        )}
                                    </div>
                                )}
                            </div>
                            
                            {/* Template Download */}
                            {assessment.file_url && (
                                <div className="bg-white rounded-xl shadow-sm p-6">
                                    <h3 className="text-sm font-medium text-gray-700 mb-3">Project Resources</h3>
                                    <a 
                                        href={assessment.file_url}
                                        target="_blank"
                                        download={assessment.file_name}
                                        className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition"
                                    >
                                        <ArrowDownTrayIcon className="w-6 h-6 text-blue-600" />
                                        <div>
                                            <p className="font-medium text-gray-900">{assessment.file_name || 'Project Template'}</p>
                                            <p className="text-sm text-gray-500">Download template</p>
                                        </div>
                                    </a>
                                </div>
                            )}
                            
                            {/* Resource Links */}
                            {assessment.settings?.resources && (
                                <div className="bg-white rounded-xl shadow-sm p-6">
                                    <h3 className="text-sm font-medium text-gray-700 mb-3">Additional Resources</h3>
                                    <div className="prose prose-sm max-w-none">
                                        <div dangerouslySetInnerHTML={{ __html: assessment.settings.resources }} />
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                    
                    {/* Bottom Navigation */}
                    <div className="mt-6 flex gap-4">
                        <Link
                            href={route('dashboard.courses.show', course.slug)}
                            className="flex-1 py-3 bg-gray-100 text-gray-700 text-center rounded-lg hover:bg-gray-200 transition"
                        >
                            Back to Course
                        </Link>
                        
                       {/* Certificate Available Badge */}
                        {existingSubmission?.status === 'graded' && existingSubmission?.passed && (
                            <a
                                href={route('dashboard.certificates.generate', enrollment.id)}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition flex items-center gap-2"
                            >
                                <TrophyIcon className="w-4 h-4" />
                                Certificate Available
                            </a>
                        )}
                    </div>
                </div>
            </div>
            
            {/* Confirmation Modal */}
            {showConfirmModal && (
                <div className="fixed inset-0 z-50 overflow-y-auto">
                    <div className="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        {/* Background overlay */}
                        <div 
                            className="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                            onClick={() => setShowConfirmModal(false)}
                        ></div>

                        {/* Modal panel */}
                        <div className="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div className="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div className="sm:flex sm:items-start">
                                    <div className="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <ExclamationTriangleIcon className="h-6 w-6 text-blue-600" />
                                    </div>
                                    <div className="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 className="text-lg leading-6 font-medium text-gray-900">
                                            Confirm Submission
                                        </h3>
                                        <div className="mt-2">
                                            <p className="text-sm text-gray-500">
                                                Please review your submission details before proceeding:
                                            </p>
                                            <ul className="mt-3 space-y-2 text-sm text-gray-600">
                                                <li className="flex items-start gap-2">
                                                    <DocumentTextIcon className="w-4 h-4 mt-0.5 text-gray-400 flex-shrink-0" />
                                                    <span>
                                                        <strong>File:</strong> {file?.name || 'No file selected'}
                                                        {file && <span className="text-gray-400 ml-1">({(file.size / (1024 * 1024)).toFixed(2)} MB)</span>}
                                                    </span>
                                                </li>
                                                {notes && (
                                                    <li className="flex items-start gap-2">
                                                        <PencilSquareIcon className="w-4 h-4 mt-0.5 text-gray-400 flex-shrink-0" />
                                                        <span>
                                                            <strong>Notes:</strong> {notes}
                                                        </span>
                                                    </li>
                                                )}
                                                {isOverdue && (
                                                    <li className="flex items-start gap-2">
                                                        <ClockIcon className="w-4 h-4 mt-0.5 text-red-400 flex-shrink-0" />
                                                        <span className="text-red-600">
                                                            ⚠️ This is a late submission (deadline has passed)
                                                        </span>
                                                    </li>
                                                )}
                                                <li className="flex items-start gap-2">
                                                    <CheckCircleIcon className="w-4 h-4 mt-0.5 text-green-400 flex-shrink-0" />
                                                    <span>
                                                        By submitting, you confirm this is your own work
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                                <button
                                    type="button"
                                    onClick={handleConfirmSubmit}
                                    className="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                                >
                                    Confirm & Submit
                                </button>
                                <button
                                    type="button"
                                    onClick={() => setShowConfirmModal(false)}
                                    className="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                                >
                                    Go Back
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </AuthenticatedLayout>
    );
}