// resources/js/Pages/Exam/Taking.jsx

import React, { useState, useRef, useEffect } from 'react';
import { Head, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import toast from 'react-hot-toast';
import { motion, AnimatePresence } from 'framer-motion';
import {
    CameraIcon,
    ClockIcon,
    ShieldCheckIcon,
    ExclamationTriangleIcon,
    CheckBadgeIcon,
    ArrowPathIcon,
    PhotoIcon
} from '@heroicons/react/24/outline';

export default function ExamTaking({ exam, attempt, enrollment }) {
    const [timeLeft, setTimeLeft] = useState(calculateTimeLeft());
    const [currentQuestion, setCurrentQuestion] = useState(0);
    const [answers, setAnswers] = useState({});
    const [showCamera, setShowCamera] = useState(false);
    const [capturedImages, setCapturedImages] = useState([]);
    const [isCapturing, setIsCapturing] = useState(false);
    const [cameraError, setCameraError] = useState(null);
    const [photoCount, setPhotoCount] = useState(0);
    const [isSubmitting, setIsSubmitting] = useState(false);
    
    // Refs for camera
    const videoRef = useRef(null);
    const canvasRef = useRef(null);
    const streamRef = useRef(null);
    const captureIntervalRef = useRef(null);

    // Calculate time left in exam
    function calculateTimeLeft() {
        if (!attempt?.expires_at) return 0;
        const endTime = new Date(attempt.expires_at).getTime();
        const now = new Date().getTime();
        const difference = endTime - now;
        
        if (difference <= 0) return 0;
        
        return {
            hours: Math.floor((difference / (1000 * 60 * 60)) % 24),
            minutes: Math.floor((difference / 1000 / 60) % 60),
            seconds: Math.floor((difference / 1000) % 60)
        };
    }

    // Timer countdown
    useEffect(() => {
        const timer = setInterval(() => {
            const newTimeLeft = calculateTimeLeft();
            setTimeLeft(newTimeLeft);
            
            // Auto-submit when time runs out
            if (newTimeLeft === 0) {
                clearInterval(timer);
                handleAutoSubmit();
            }
        }, 1000);

        return () => clearInterval(timer);
    }, []);

    // Start periodic photo capture when exam starts
    useEffect(() => {
        if (attempt?.status === 'in_progress') {
            startPeriodicCapture();
        }

        return () => {
            stopPeriodicCapture();
            stopCamera();
        };
    }, []);

    // Start periodic photo capture
    const startPeriodicCapture = async () => {
        try {
            // Start camera first
            await startCamera();
            
            // Capture photo every 30 seconds
            captureIntervalRef.current = setInterval(() => {
                capturePhoto();
            }, 30000); // 30 seconds
            
            // Capture first photo immediately
            setTimeout(() => capturePhoto(), 5000); // First photo after 5 seconds
        } catch (error) {
            console.error('Failed to start periodic capture:', error);
            toast.error('Camera monitoring failed. Please ensure camera access is granted.');
        }
    };

    // Stop periodic capture
    const stopPeriodicCapture = () => {
        if (captureIntervalRef.current) {
            clearInterval(captureIntervalRef.current);
            captureIntervalRef.current = null;
        }
    };

    // Start camera
    const startCamera = async () => {
        setCameraError(null);
        
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            setCameraError('Camera not supported in this browser');
            return;
        }

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                }
            });
            
            if (videoRef.current) {
                videoRef.current.srcObject = stream;
                streamRef.current = stream;
                setShowCamera(true);
                
                await videoRef.current.play();
            }
        } catch (error) {
            console.error('Camera error:', error);
            setCameraError('Unable to access camera. Please check permissions.');
            toast.error('Camera access required for exam integrity.');
        }
    };

    // Stop camera
    const stopCamera = () => {
        if (streamRef.current) {
            streamRef.current.getTracks().forEach(track => track.stop());
            streamRef.current = null;
        }
        setShowCamera(false);
    };

    // Capture photo
    const capturePhoto = () => {
        if (videoRef.current && canvasRef.current && showCamera) {
            const video = videoRef.current;
            const canvas = canvasRef.current;
            const context = canvas.getContext('2d');
            
            // Set canvas dimensions
            canvas.width = 320;
            canvas.height = 240;
            
            // Draw video frame
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Convert to base64
            const imageData = canvas.toDataURL('image/jpeg', 0.6);
            
            // Add timestamp and store
            const capturedPhoto = {
                id: Date.now(),
                image: imageData,
                timestamp: new Date().toISOString(),
                questionIndex: currentQuestion
            };
            
            setCapturedImages(prev => [...prev, capturedPhoto]);
            setPhotoCount(prev => prev + 1);
            
            // Show subtle notification
            toast.success('📸 Verification photo captured', {
                duration: 2000,
                icon: '📸'
            });
        }
    };

    // Manual capture (for testing)
    const handleManualCapture = () => {
        capturePhoto();
        toast.success('Manual photo captured');
    };

    // Handle answer selection
    const handleAnswer = (questionId, answer) => {
        setAnswers(prev => ({
            ...prev,
            [questionId]: answer
        }));
    };

    // Navigate questions
    const nextQuestion = () => {
        if (currentQuestion < exam.questions.length - 1) {
            setCurrentQuestion(prev => prev + 1);
        }
    };

    const prevQuestion = () => {
        if (currentQuestion > 0) {
            setCurrentQuestion(prev => prev - 1);
        }
    };

    // Handle auto-submit when time expires
    const handleAutoSubmit = () => {
        toast.error('Time expired! Submitting exam...');
        handleSubmitExam(true);
    };

    // Handle exam submission
    const handleSubmitExam = async (isAutoSubmit = false) => {
        if (!isAutoSubmit) {
            const confirmed = confirm('Are you sure you want to submit your exam?');
            if (!confirmed) return;
        }

        setIsSubmitting(true);
        
        // Capture final photo before submission
        capturePhoto();
        
        // Stop periodic capture
        stopPeriodicCapture();
        
        const loadingToast = toast.loading('Submitting exam...');

        try {
            await router.post(route('exam.submit', { 
                enrollment: enrollment.id, 
                exam: exam.id 
            }), {
                answers,
                captured_images: capturedImages,
                completed_at: new Date().toISOString()
            }, {
                onSuccess: () => {
                    toast.dismiss(loadingToast);
                    toast.success('Exam submitted successfully!');
                    
                    // Redirect to course page
                    router.visit(route('dashboard.courses.show', enrollment.id));
                },
                onError: (errors) => {
                    toast.dismiss(loadingToast);
                    console.error('Submission error:', errors);
                    toast.error('Failed to submit exam. Please try again.');
                }
            });
        } catch (error) {
            toast.dismiss(loadingToast);
            console.error('Submission error:', error);
            toast.error('Failed to submit exam');
        } finally {
            setIsSubmitting(false);
        }
    };

    // Format time display
    const formatTime = () => {
        if (timeLeft === 0) return '00:00:00';
        
        return `${String(timeLeft.hours || 0).padStart(2, '0')}:${String(timeLeft.minutes || 0).padStart(2, '0')}:${String(timeLeft.seconds || 0).padStart(2, '0')}`;
    };

    const question = exam.questions?.[currentQuestion];

    return (
        <AuthenticatedLayout>
            <Head title={`${exam.title} | Exam`} />

            {/* Hidden canvas for capture */}
            <canvas ref={canvasRef} className="hidden" />

            {/* Main exam container */}
            <div className="min-h-screen bg-gray-50">
                {/* Fixed Header with Timer and Camera Status */}
                <div className="fixed top-0 left-0 right-0 bg-white shadow-lg z-40">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                        <div className="flex items-center justify-between">
                            {/* Exam Title */}
                            <div>
                                <h1 className="text-xl font-bold text-gray-900">{exam.title}</h1>
                                <p className="text-sm text-gray-500">Question {currentQuestion + 1} of {exam.questions?.length}</p>
                            </div>

                            {/* Timer and Camera Status */}
                            <div className="flex items-center gap-6">
                                {/* Camera Status */}
                                <div className="flex items-center gap-2">
                                    {cameraError ? (
                                        <div className="flex items-center gap-2 text-red-600">
                                            <ExclamationTriangleIcon className="w-5 h-5" />
                                            <span className="text-sm">Camera Error</span>
                                        </div>
                                    ) : showCamera ? (
                                        <div className="flex items-center gap-2 text-green-600">
                                            <div className="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                            <span className="text-sm">Live {photoCount} photos</span>
                                        </div>
                                    ) : (
                                        <div className="flex items-center gap-2 text-yellow-600">
                                            <CameraIcon className="w-5 h-5" />
                                            <span className="text-sm">Starting camera...</span>
                                        </div>
                                    )}
                                </div>

                                {/* Timer */}
                                <div className={`flex items-center gap-3 px-4 py-2 rounded-lg ${
                                    timeLeft?.minutes < 5 ? 'bg-red-100 text-red-700 animate-pulse' : 'bg-gray-100'
                                }`}>
                                    <ClockIcon className="w-5 h-5" />
                                    <span className="font-mono text-xl font-bold">{formatTime()}</span>
                                </div>
                            </div>
                        </div>

                        {/* Progress Bar */}
                        <div className="mt-4 h-2 bg-gray-200 rounded-full">
                            <div 
                                className="h-2 bg-indigo-600 rounded-full transition-all duration-300"
                                style={{ width: `${((currentQuestion + 1) / exam.questions?.length) * 100}%` }}
                            ></div>
                        </div>
                    </div>
                </div>

                {/* Camera Preview (Small overlay) */}
                {showCamera && (
                    <div className="fixed bottom-6 right-6 w-48 h-36 rounded-lg overflow-hidden shadow-2xl border-4 border-white z-50">
                        <video
                            ref={videoRef}
                            autoPlay
                            playsInline
                            muted
                            className="w-full h-full object-cover"
                        />
                        <div className="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full flex items-center gap-1">
                            <div className="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                            <span>REC</span>
                        </div>
                        <div className="absolute bottom-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                            {photoCount}
                        </div>
                    </div>
                )}

                {/* Main Content */}
                <div className="pt-32 pb-16 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
                    {cameraError && (
                        <div className="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div className="flex items-center gap-3">
                                <ExclamationTriangleIcon className="w-6 h-6 text-red-600" />
                                <div>
                                    <h3 className="font-medium text-red-800">Camera Required</h3>
                                    <p className="text-sm text-red-600">
                                        {cameraError} Please enable camera access to continue the exam.
                                    </p>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* Question Card */}
                    {question && (
                        <motion.div
                            key={currentQuestion}
                            initial={{ opacity: 0, x: 20 }}
                            animate={{ opacity: 1, x: 0 }}
                            exit={{ opacity: 0, x: -20 }}
                            className="bg-white rounded-xl shadow-lg p-8 mb-6"
                        >
                            <h2 className="text-xl font-bold text-gray-900 mb-4">
                                {question.text}
                            </h2>

                            {/* Options */}
                            <div className="space-y-3">
                                {question.options?.map((option, index) => (
                                    <label
                                        key={index}
                                        className={`flex items-center p-4 border-2 rounded-lg cursor-pointer transition ${
                                            answers[question.id] === option
                                                ? 'border-indigo-600 bg-indigo-50'
                                                : 'border-gray-200 hover:border-indigo-300'
                                        }`}
                                    >
                                        <input
                                            type="radio"
                                            name={`question-${question.id}`}
                                            value={option}
                                            checked={answers[question.id] === option}
                                            onChange={() => handleAnswer(question.id, option)}
                                            className="w-4 h-4 text-indigo-600"
                                        />
                                        <span className="ml-3 text-gray-700">{option}</span>
                                    </label>
                                ))}
                            </div>

                            {/* Question Navigation */}
                            <div className="flex justify-between mt-8">
                                <button
                                    onClick={prevQuestion}
                                    disabled={currentQuestion === 0}
                                    className="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition disabled:opacity-50"
                                >
                                    Previous
                                </button>
                                
                                {currentQuestion === exam.questions.length - 1 ? (
                                    <button
                                        onClick={() => handleSubmitExam(false)}
                                        disabled={isSubmitting}
                                        className="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition disabled:opacity-50"
                                    >
                                        {isSubmitting ? 'Submitting...' : 'Submit Exam'}
                                    </button>
                                ) : (
                                    <button
                                        onClick={nextQuestion}
                                        className="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                                    >
                                        Next
                                    </button>
                                )}
                            </div>
                        </motion.div>
                    )}

                    {/* Security Notice */}
                    <div className="bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
                        <div className="flex items-center gap-2 mb-2">
                            <ShieldCheckIcon className="w-5 h-5 text-green-600" />
                            <span className="font-medium">Exam Integrity Protection</span>
                        </div>
                        <p>
                            • Photos are being captured periodically to verify your identity<br />
                            • {photoCount} verification photos captured during this session<br />
                            • All activity is monitored for academic integrity
                        </p>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}