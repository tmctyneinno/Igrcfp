import React, { useState, useRef, useEffect } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import toast from 'react-hot-toast';
import { motion, AnimatePresence } from 'framer-motion';
import { 
    BookOpenIcon, 
    ClockIcon, 
    DocumentTextIcon,
    AcademicCapIcon,
    ShieldCheckIcon,
    IdentificationIcon,
    DocumentDuplicateIcon,
    QrCodeIcon,
    GlobeAltIcon,
    CheckBadgeIcon,
    SparklesIcon,
    CameraIcon,
    LockClosedIcon,
    ClipboardDocumentCheckIcon,
    PhotoIcon,
    ExclamationTriangleIcon
} from '@heroicons/react/24/outline';

export default function EnrollmentIndex({ course, enrollment, modules: initialModules = [], candidate, examResults }) {
    // State management
    const [modules] = useState(initialModules);
    const [progress] = useState(enrollment?.progress || 0);
    const [showIdentityVerification, setShowIdentityVerification] = useState(false);
    const [capturedImage, setCapturedImage] = useState(null);
    const [isVerifying, setIsVerifying] = useState(false);
    const [cameraActive, setCameraActive] = useState(false);
    const [cameraError, setCameraError] = useState(null);
    const [availableCameras, setAvailableCameras] = useState([]);
    const [selectedCamera, setSelectedCamera] = useState('');
    
    // Refs for camera
    const videoRef = useRef(null);
    const canvasRef = useRef(null);
    const streamRef = useRef(null);
    
    const hasCertificate = enrollment?.certificate_generated;
    const certificateNumber = enrollment?.certificate_number || candidate?.certificate_id;
    
    // ============== HELPER FUNCTIONS ==============
    
    // Get status badge color - THIS WAS MISSING
    const getStatusBadge = (status) => {
        const statusMap = {
            'pending_payment': 'bg-yellow-100 text-yellow-800',
            'enrolled': 'bg-green-100 text-green-800',
            'in_progress': 'bg-blue-100 text-blue-800',
            'completed': 'bg-purple-100 text-purple-800',
            'certified': 'bg-indigo-100 text-indigo-800',
            'cancelled': 'bg-red-100 text-red-800'
        };
        return statusMap[status] || 'bg-gray-100 text-gray-800';
    };
    
    // Format candidate ID
    const formatCandidateId = (id) => {
        if (!id) return 'IGRCFP-' + enrollment?.id?.toString().padStart(6, '0');
        return id;
    };

    // ============== CAMERA FUNCTIONS ==============
    
    // Check if camera is supported
    const checkCameraSupport = async () => {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            setCameraError('Camera is not supported in this browser. Please use Chrome, Firefox, or Safari.');
            return;
        }

        try {
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(device => device.kind === 'videoinput');
            setAvailableCameras(videoDevices);
            
            if (videoDevices.length === 0) {
                setCameraError('No camera detected. Please connect a camera and try again.');
            }
        } catch (error) {
            console.error('Error enumerating devices:', error);
        }
    };
    
    // Start camera
    const startCamera = async () => {
        setCameraError(null);
        
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            setCameraError('Your browser does not support camera access. Please use a modern browser like Chrome, Firefox, or Safari.');
            return;
        }

        try {
            // First check if we have permission
            await navigator.mediaDevices.getUserMedia({ video: true });
            
            const constraints = {
                video: {
                    facingMode: 'user',
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }
            };
            
            // If a specific camera is selected, use its deviceId
            if (selectedCamera) {
                constraints.video.deviceId = { exact: selectedCamera };
            }
            
            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            
            if (videoRef.current) {
                videoRef.current.srcObject = stream;
                streamRef.current = stream;
                setCameraActive(true);
                
                videoRef.current.onloadedmetadata = () => {
                    videoRef.current.play().catch(e => {
                        console.error('Error playing video:', e);
                        setCameraError('Could not start video playback.');
                    });
                };
            }
        } catch (error) {
            console.error('Camera error:', error);
            
            if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                setCameraError('Camera access denied. Please allow camera permissions in your browser settings.');
            } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
                setCameraError('No camera found. Please connect a camera and try again.');
            } else if (error.name === 'NotReadableError' || error.name === 'TrackStartError') {
                setCameraError('Camera is already in use by another application.');
            } else {
                setCameraError(`Unable to access camera: ${error.message || 'Unknown error'}`);
            }
            
            toast.error('Failed to start camera. Please check permissions.');
        }
    };

    // Stop camera
    const stopCamera = () => {
        if (streamRef.current) {
            streamRef.current.getTracks().forEach(track => {
                track.stop();
            });
            streamRef.current = null;
        }
        
        if (videoRef.current) {
            videoRef.current.srcObject = null;
        }
        
        setCameraActive(false);
    };

    // Capture photo from camera
    const capturePhoto = () => {
        if (videoRef.current && canvasRef.current && cameraActive) {
            const video = videoRef.current;
            const canvas = canvasRef.current;
            const context = canvas.getContext('2d');
            
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const imageData = canvas.toDataURL('image/jpeg', 0.7);
            setCapturedImage(imageData);
            
            stopCamera();
            
            toast.success('Photo captured successfully!');
        } else {
            toast.error('Camera not active. Please start the camera first.');
        }
    };

    // Retake photo
    const retakePhoto = () => {
        setCapturedImage(null);
        startCamera();
    };

    // Handle file upload fallback
    const handleFileUpload = (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onloadend = () => {
                setCapturedImage(reader.result);
            };
            reader.readAsDataURL(file);
        }
    };

    // ============== VERIFICATION FUNCTIONS ==============
    
    // Handle identity verification
    const handleIdentityVerification = async () => {
        if (!capturedImage) {
            toast.error('Please capture a photo first.');
            return;
        }

        setIsVerifying(true);
        
        const loadingToast = toast.loading('Verifying identity...');
        
        try {
            await router.post(route('exam.verify-identity', enrollment.id), {
                image: capturedImage
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    toast.dismiss(loadingToast);
                    toast.success('Identity verified successfully!', { icon: '✓' });
                    setShowIdentityVerification(false);
                    setCapturedImage(null);
                    stopCamera();
                    
                    // Refresh the page to show updated status
                    router.reload({ only: ['enrollment'] });
                },
                onError: (errors) => {
                    toast.dismiss(loadingToast);
                    console.error('Verification error:', errors);
                    
                    if (errors.image) {
                        toast.error(`Image error: ${errors.image}`);
                    } else {
                        toast.error('Identity verification failed. Please try again.');
                    }
                }
            });
        } catch (error) {
            toast.dismiss(loadingToast);
            console.error('Verification error:', error);
            toast.error('Verification failed. Please check your connection and try again.');
        } finally {
            setIsVerifying(false);
        }
    };

    // Handle modal close
    const handleModalClose = () => {
        setShowIdentityVerification(false);
        setCapturedImage(null);
        setCameraError(null);
        stopCamera();
    };

    // Handle camera selection
    const handleCameraChange = (e) => {
        setSelectedCamera(e.target.value);
    };

    // ============== EXAM FUNCTIONS ==============
    
    // Handle exam start
    const handleStartExam = (examId) => {
        router.post(route('exam.start', { enrollment: enrollment.id, exam: examId }), {}, {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Exam started. Good luck!', { icon: '📝' });
            }
        });
    };

    // Handle exam submission
    const handleSubmitExam = (examId) => {
        if (confirm('Are you sure you want to submit your exam? This action cannot be undone.')) {
            router.post(route('exam.submit', { enrollment: enrollment.id, exam: examId }), {}, {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Exam submitted successfully!');
                }
            });
        }
    };

    // ============== EFFECTS ==============
    
    // Check camera support on mount
    useEffect(() => {
        checkCameraSupport();
        
        // Cleanup on unmount
        return () => {
            stopCamera();
        };
    }, []);

    return (
        <AuthenticatedLayout>
            <Head title={`${course?.title || 'Course'} | My Learning`} />

            <div className="min-h-screen bg-gray-50 py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Breadcrumb Navigation */}
                    <nav className="mb-6 flex items-center text-sm" aria-label="Breadcrumb">
                        <Link href={route('dashboard.index')} className="text-gray-500 hover:text-gray-700 transition">
                            Dashboard
                        </Link>
                        <span className="mx-2 text-gray-400">/</span>
                        <Link href={route('dashboard.my-courses')} className="text-gray-500 hover:text-gray-700 transition">
                            My Courses
                        </Link>
                        <span className="mx-2 text-gray-400">/</span>
                        <span className="text-gray-900 font-medium truncate max-w-xs">{course?.title}</span>
                    </nav>

                    {/* Candidate ID Banner */}
                    {candidate && (
                        <motion.div 
                            initial={{ opacity: 0, y: -20 }}
                            animate={{ opacity: 1, y: 0 }}
                            className="mb-6 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg overflow-hidden"
                        >
                            <div className="px-6 py-4 flex flex-wrap items-center justify-between">
                                <div className="flex items-center gap-4">
                                    <div className="bg-white/20 rounded-lg p-3">
                                        <IdentificationIcon className="w-8 h-8 text-white" />
                                    </div>
                                    <div>
                                        <p className="text-indigo-100 text-sm">Your Candidate ID</p>
                                        <p className="text-2xl font-mono font-bold text-white tracking-wider">
                                            {formatCandidateId(candidate.certificate_id)}
                                        </p>
                                    </div>
                                </div>
                                <div className="flex gap-3 mt-3 sm:mt-0">
                                    <button 
                                        onClick={() => navigator.clipboard.writeText(formatCandidateId(candidate.certificate_id))}
                                        className="flex items-center gap-2 px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition"
                                    >
                                        <DocumentDuplicateIcon className="w-4 h-4" />
                                        Copy ID
                                    </button>
                                    <Link
                                        href={route('certificate.verify', { id: candidate.certificate_id })}
                                        className="flex items-center gap-2 px-4 py-2 bg-white text-indigo-600 rounded-lg hover:bg-gray-100 transition"
                                    >
                                        <ShieldCheckIcon className="w-4 h-4" />
                                        Verify
                                    </Link>
                                </div>
                            </div>
                        </motion.div>
                    )}

                    {/* Main Content Grid */}
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {/* Left Column - Course Info & Progress */}
                        <div className="lg:col-span-2 space-y-6">
                            {/* Course Header Card */}
                            <motion.div 
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                className="bg-white rounded-2xl shadow-sm overflow-hidden"
                            >
                                {/* Cover Image */}
                                <div className="h-64 w-full bg-gradient-to-r from-blue-900 to-indigo-900 relative">
                                    {course?.image_url || course?.banner_image ? (
                                        <img 
                                            src={course.image_url || course.banner_image}
                                            alt={course.title}
                                            className="w-full h-full object-cover opacity-50"
                                        />
                                    ) : (
                                        <div className="absolute inset-0 bg-gradient-to-r from-blue-900 to-indigo-900"></div>
                                    )}
                                    
                                    <div className="absolute bottom-0 left-0 p-8 text-white">
                                        <h1 className="text-3xl font-bold mb-2">{course?.title}</h1>
                                        <div className="flex items-center gap-4 text-sm">
                                            <span className="flex items-center gap-1">
                                                <AcademicCapIcon className="w-4 h-4" />
                                                {modules?.length || 0} modules
                                            </span>
                                            <span>•</span>
                                            <span className="flex items-center gap-1">
                                                <ClockIcon className="w-4 h-4" />
                                                {course?.duration}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {/* Course Info */}
                                <div className="p-6">
                                    <div className="flex flex-wrap items-center justify-between gap-4 mb-4">
                                        <div className="flex items-center gap-3">
                                            {/* Using getStatusBadge here */}
                                            <span className={`px-3 py-1 rounded-full text-sm font-medium ${getStatusBadge(enrollment?.status)}`}>
                                                {enrollment?.status?.replace('_', ' ') || 'Enrolled'}
                                            </span>
                                            {course?.level && (
                                                <span className="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                                    {course.level}
                                                </span>
                                            )}
                                        </div>
                                        <div className="text-sm text-gray-500">
                                            Enrolled: {enrollment?.enrollment_date ? new Date(enrollment.enrollment_date).toLocaleDateString() : 'N/A'}
                                        </div>
                                    </div>

                                    {/* Progress Bar */}
                                    <div className="mb-4">
                                        <div className="flex justify-between text-sm mb-2">
                                            <span className="font-medium text-gray-700">Course Progress</span>
                                            <span className="text-blue-600 font-medium">{progress}% Complete</span>
                                        </div>
                                        <div className="h-3 w-full bg-gray-200 rounded-full overflow-hidden">
                                            <motion.div 
                                                initial={{ width: 0 }}
                                                animate={{ width: `${progress}%` }}
                                                transition={{ duration: 0.5 }}
                                                className="h-full bg-blue-600 rounded-full"
                                            ></motion.div>
                                        </div>
                                    </div>

                                    {/* Course Description */}
                                    {course?.full_description && (
                                        <div className="prose prose-sm max-w-none text-gray-600">
                                            <div dangerouslySetInnerHTML={{ __html: course.full_description }} />
                                        </div>
                                    )}
                                </div>
                            </motion.div>

                            {/* Modules & Content */}
                            <div className="space-y-4">
                                {modules?.length > 0 ? (
                                    modules.map((module, moduleIndex) => (
                                        <motion.div
                                            key={module.id}
                                            initial={{ opacity: 0, y: 20 }}
                                            animate={{ opacity: 1, y: 0 }}
                                            transition={{ delay: moduleIndex * 0.1 }}
                                            className="bg-white rounded-xl shadow-sm overflow-hidden"
                                        >
                                            {/* Module Header */}
                                            <div className="p-6 border-b bg-gray-50">
                                                <div className="flex items-start justify-between">
                                                    <div>
                                                        <div className="flex items-center gap-3 mb-2">
                                                            <span className="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                                                Module {module.module_number}
                                                            </span>
                                                            {module.code && (
                                                                <span className="text-sm text-gray-500">
                                                                    {module.code}
                                                                </span>
                                                            )}
                                                        </div>
                                                        <h2 className="text-xl font-semibold text-gray-900">
                                                            {module.title}
                                                        </h2>
                                                        {module.short_description && (
                                                            <div 
                                                                className="prose prose-sm max-w-none text-gray-600 mt-2"
                                                                dangerouslySetInnerHTML={{ __html: module.short_description }}
                                                            />
                                                        )}
                                                    </div>
                                                    <div className="flex items-center gap-2 text-sm text-gray-500">
                                                        <span className="flex items-center gap-1">
                                                            <ClockIcon className="w-4 h-4" />
                                                            {module.estimated_hours} hrs
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {/* Module Content */}
                                            <div className="p-6">
                                                {/* Materials */}
                                                {module.materials?.length > 0 && (
                                                    <div className="mb-4">
                                                        <h3 className="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">
                                                            Learning Materials
                                                        </h3>
                                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                            {module.materials.map((material) => (
                                                                <a
                                                                    key={material.id}
                                                                    href={material.file_path || material.url}
                                                                    target="_blank"
                                                                    rel="noopener noreferrer"
                                                                    className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition group"
                                                                >
                                                                    <DocumentTextIcon className="w-5 h-5 text-blue-600 group-hover:scale-110 transition" />
                                                                    <span className="text-sm text-gray-900">
                                                                        {material.title}
                                                                    </span>
                                                                </a>
                                                            ))}
                                                        </div>
                                                    </div>
                                                )}
                                            </div>
                                        </motion.div>
                                    ))
                                ) : (
                                    <div className="bg-white rounded-xl shadow-sm p-12 text-center">
                                        <BookOpenIcon className="w-16 h-16 text-gray-400 mx-auto mb-4" />
                                        <h3 className="text-lg font-medium text-gray-900 mb-1">No modules yet</h3>
                                        <p className="text-gray-500">Course content is being prepared.</p>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Right Column - Exams & Certification */}
                        <div className="space-y-6">
                            {/* Identity Verification Card */}
                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                className="bg-white rounded-xl shadow-sm p-6"
                            >
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                    <ShieldCheckIcon className="w-5 h-5 text-indigo-600" />
                                    Identity Verification
                                </h3>
                                
                                {!enrollment?.identity_verified ? (
                                    <div className="space-y-4">
                                        <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                            <p className="text-sm text-yellow-800">
                                                Verify your identity to access exams and generate your certificate.
                                            </p>
                                        </div>
                                        
                                        <button
                                            onClick={() => {
                                                setShowIdentityVerification(true);
                                                setTimeout(() => startCamera(), 100);
                                            }}
                                            className="w-full flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                                        >
                                            <CameraIcon className="w-5 h-5" />
                                            Verify Identity Now
                                        </button>
                                    </div>
                                ) : (
                                    <div className="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center gap-3">
                                        <CheckBadgeIcon className="w-8 h-8 text-green-600" />
                                        <div>
                                            <p className="font-medium text-green-800">Identity Verified</p>
                                            <p className="text-xs text-green-600">Verified on {enrollment?.verified_at ? new Date(enrollment.verified_at).toLocaleDateString() : 'N/A'}</p>
                                        </div>
                                    </div>
                                )}
                            </motion.div>

                            {/* Identity Verification Modal */}
                            <AnimatePresence>
                                {showIdentityVerification && (
                                    <motion.div
                                        initial={{ opacity: 0 }}
                                        animate={{ opacity: 1 }}
                                        exit={{ opacity: 0 }}
                                        className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
                                        onClick={handleModalClose}
                                    >
                                        <motion.div
                                            initial={{ scale: 0.9 }}
                                            animate={{ scale: 1 }}
                                            exit={{ scale: 0.9 }}
                                            className="bg-white rounded-2xl max-w-md w-full p-6"
                                            onClick={e => e.stopPropagation()}
                                        >
                                            <h3 className="text-xl font-bold text-gray-900 mb-4">Identity Verification</h3>
                                            <p className="text-gray-600 mb-6">
                                                Please take a clear photo of your face for verification purposes.
                                            </p>
                                            
                                            <canvas ref={canvasRef} className="hidden" />
                                            
                                            {/* Camera Preview or Captured Image */}
                                            <div className="aspect-video bg-gray-100 rounded-lg mb-4 overflow-hidden relative">
                                                {cameraError ? (
                                                    <div className="absolute inset-0 flex flex-col items-center justify-center p-4 text-center">
                                                        <ExclamationTriangleIcon className="w-12 h-12 text-red-500 mb-2" />
                                                        <p className="text-sm text-red-600 mb-3">{cameraError}</p>
                                                        
                                                        <div className="mt-2">
                                                            <p className="text-xs text-gray-500 mb-2">Or upload a photo instead:</p>
                                                            <label className="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                                                <PhotoIcon className="w-5 h-5" />
                                                                Upload Photo
                                                                <input
                                                                    type="file"
                                                                    accept="image/*"
                                                                    className="hidden"
                                                                    onChange={handleFileUpload}
                                                                />
                                                            </label>
                                                        </div>
                                                        
                                                        <button
                                                            onClick={() => {
                                                                setCameraError(null);
                                                                startCamera();
                                                            }}
                                                            className="mt-3 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm"
                                                        >
                                                            Try Again
                                                        </button>
                                                    </div>
                                                ) : capturedImage ? (
                                                    <img 
                                                        src={capturedImage} 
                                                        alt="Captured" 
                                                        className="w-full h-full object-cover"
                                                    />
                                                ) : cameraActive ? (
                                                    <>
                                                        <video
                                                            ref={videoRef}
                                                            autoPlay
                                                            playsInline
                                                            muted
                                                            className="w-full h-full object-cover"
                                                        />
                                                        <div className="absolute top-2 right-2 flex items-center gap-1 bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                                                            <span className="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                                            Live
                                                        </div>
                                                    </>
                                                ) : (
                                                    <div className="absolute inset-0 flex flex-col items-center justify-center">
                                                        <CameraIcon className="w-12 h-12 text-gray-400 mb-2" />
                                                        <p className="text-sm text-gray-500">Camera is off</p>
                                                    </div>
                                                )}
                                            </div>

                                            {/* Camera Selection */}
                                            {availableCameras.length > 1 && !capturedImage && !cameraError && (
                                                <div className="mb-4">
                                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                                        Select Camera
                                                    </label>
                                                    <select
                                                        onChange={handleCameraChange}
                                                        value={selectedCamera}
                                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                                    >
                                                        <option value="">Default Camera</option>
                                                        {availableCameras.map((camera) => (
                                                            <option key={camera.deviceId} value={camera.deviceId}>
                                                                {camera.label || `Camera ${camera.deviceId.slice(0, 5)}...`}
                                                            </option>
                                                        ))}
                                                    </select>
                                                </div>
                                            )}

                                            <div className="flex gap-3">
                                                {!capturedImage ? (
                                                    <>
                                                        {!cameraActive && !cameraError ? (
                                                            <button
                                                                onClick={startCamera}
                                                                className="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                                                            >
                                                                Start Camera
                                                            </button>
                                                        ) : cameraActive ? (
                                                            <button
                                                                onClick={capturePhoto}
                                                                className="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                                                            >
                                                                Capture Photo
                                                            </button>
                                                        ) : null}
                                                        
                                                        {cameraError && !capturedImage && (
                                                            <div className="w-full">
                                                                <label className="w-full cursor-pointer inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                                                    <PhotoIcon className="w-5 h-5" />
                                                                    Upload Photo Instead
                                                                    <input
                                                                        type="file"
                                                                        accept="image/*"
                                                                        className="hidden"
                                                                        onChange={handleFileUpload}
                                                                    />
                                                                </label>
                                                            </div>
                                                        )}
                                                    </>
                                                ) : (
                                                    <>
                                                        <button
                                                            onClick={retakePhoto}
                                                            className="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition"
                                                        >
                                                            Retake
                                                        </button>
                                                        <button
                                                            onClick={handleIdentityVerification}
                                                            disabled={isVerifying}
                                                            className="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition disabled:opacity-50"
                                                        >
                                                            {isVerifying ? 'Verifying...' : 'Submit'}
                                                        </button>
                                                    </>
                                                )}
                                            </div>
                                            
                                            {/* Close button */}
                                            <button
                                                onClick={handleModalClose}
                                                className="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition"
                                            >
                                                <span className="sr-only">Close</span>
                                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </motion.div>
                                    </motion.div>
                                )}
                            </AnimatePresence>

                            {/* Exams Card */}
                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                transition={{ delay: 0.1 }}
                                className="bg-white rounded-xl shadow-sm p-6"
                            >
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                    <ClipboardDocumentCheckIcon className="w-5 h-5 text-purple-600" />
                                    Timed Online Exams
                                </h3>

                                {enrollment?.exams?.length > 0 ? (
                                    <div className="space-y-4">
                                        {enrollment.exams.map((exam) => (
                                            <div key={exam.id} className="border rounded-lg p-4">
                                                <div className="flex justify-between items-start mb-2">
                                                    <h4 className="font-medium text-gray-900">{exam.title}</h4>
                                                    <span className="text-xs px-2 py-1 bg-purple-100 text-purple-800 rounded-full">
                                                        {exam.duration} mins
                                                    </span>
                                                </div>
                                                
                                                <div className="text-sm text-gray-600 mb-3">
                                                    <p>• Randomised question bank</p>
                                                    <p>• Plagiarism detection enabled</p>
                                                    {exam.type === 'diploma' && (
                                                        <p>• Manual marking by instructors</p>
                                                    )}
                                                </div>

                                                {exam.status === 'pending' && (
                                                    <button
                                                        onClick={() => handleStartExam(exam.id)}
                                                        disabled={!enrollment?.identity_verified}
                                                        className="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition disabled:opacity-50"
                                                    >
                                                        Start Exam
                                                    </button>
                                                )}

                                                {exam.status === 'in_progress' && (
                                                    <div className="space-y-2">
                                                        <div className="h-2 bg-gray-200 rounded-full">
                                                            <div className="h-2 bg-purple-600 rounded-full" style={{ width: `${exam.progress}%` }}></div>
                                                        </div>
                                                        <button
                                                            onClick={() => handleSubmitExam(exam.id)}
                                                            className="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                                                        >
                                                            Submit Exam
                                                        </button>
                                                    </div>
                                                )}

                                                {exam.status === 'completed' && (
                                                    <div className="bg-green-50 rounded-lg p-3 text-center">
                                                        <p className="text-green-800 font-medium">Score: {exam.score}%</p>
                                                        <p className="text-xs text-green-600">Awaiting {exam.type === 'diploma' ? 'manual review' : 'finalization'}</p>
                                                    </div>
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <div className="text-center py-6">
                                        <p className="text-gray-500 mb-3">No exams scheduled yet</p>
                                        <LockClosedIcon className="w-8 h-8 text-gray-400 mx-auto" />
                                    </div>
                                )}
                            </motion.div>

                            {/* Certification Card */}
                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                transition={{ delay: 0.2 }}
                                className="bg-white rounded-xl shadow-sm p-6 border-2 border-indigo-100"
                            >
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                    <CheckBadgeIcon className="w-5 h-5 text-indigo-600" />
                                    Digital Certification
                                </h3>

                                {hasCertificate ? (
                                    <div className="space-y-4">
                                        <div className="bg-indigo-50 rounded-lg p-4">
                                            <div className="flex items-center gap-3 mb-3">
                                                <QrCodeIcon className="w-10 h-10 text-indigo-600" />
                                                <div>
                                                    <p className="text-xs text-indigo-600">Certificate Number</p>
                                                    <p className="font-mono font-bold text-indigo-900">{certificateNumber}</p>
                                                </div>
                                            </div>
                                            
                                            <div className="flex gap-2">
                                                <Link
                                                    href={route('certificate.download', enrollment.id)}
                                                    className="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm"
                                                >
                                                    <DocumentTextIcon className="w-4 h-4" />
                                                    Download PDF
                                                </Link>
                                                <Link
                                                    href={route('certificate.preview', enrollment.id)}
                                                    className="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-white border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50 transition text-sm"
                                                >
                                                    <GlobeAltIcon className="w-4 h-4" />
                                                    Preview
                                                </Link>
                                            </div>
                                        </div>

                                        <Link
                                            href={route('certificate.badge', enrollment.id)}
                                            className="flex items-center justify-between p-3 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-lg hover:from-amber-100 hover:to-yellow-100 transition"
                                        >
                                            <div className="flex items-center gap-3">
                                                <SparklesIcon className="w-5 h-5 text-amber-600" />
                                                <div>
                                                    <p className="font-medium text-amber-900">Digital Badge</p>
                                                    <p className="text-xs text-amber-700">Claim your verifiable badge</p>
                                                </div>
                                            </div>
                                            <span className="text-amber-600">→</span>
                                        </Link>
                                    </div>
                                ) : (
                                    <div className="space-y-4">
                                        <p className="text-gray-600 text-sm">
                                            Complete all exams and requirements to earn your digital certificate.
                                        </p>
                                        
                                        <div className="bg-gray-50 rounded-lg p-4">
                                            <h4 className="font-medium text-gray-900 mb-2">Requirements:</h4>
                                            <ul className="text-sm text-gray-600 space-y-1">
                                                <li className="flex items-center gap-2">
                                                    <span className={`w-4 h-4 rounded-full ${enrollment?.identity_verified ? 'bg-green-500' : 'bg-gray-300'} flex items-center justify-center text-white text-xs`}>
                                                        {enrollment?.identity_verified ? '✓' : ''}
                                                    </span>
                                                    Identity verification
                                                </li>
                                                <li className="flex items-center gap-2">
                                                    <span className={`w-4 h-4 rounded-full ${examResults?.passed ? 'bg-green-500' : 'bg-gray-300'} flex items-center justify-center text-white text-xs`}>
                                                        {examResults?.passed ? '✓' : ''}
                                                    </span>
                                                    Pass all exams
                                                </li>
                                                <li className="flex items-center gap-2">
                                                    <span className={`w-4 h-4 rounded-full ${progress === 100 ? 'bg-green-500' : 'bg-gray-300'} flex items-center justify-center text-white text-xs`}>
                                                        {progress === 100 ? '✓' : ''}
                                                    </span>
                                                    100% course completion
                                                </li>
                                            </ul>
                                        </div>

                                        {/* Certification Registry Link */}
                                        <Link
                                            href={route('dashboard.certificate.registry')}
                                            className="flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 transition"
                                        >
                                            <GlobeAltIcon className="w-4 h-4" />
                                            View Certification Registry
                                        </Link>
                                    </div>
                                )}
                            </motion.div>

                            {/* Plagiarism & Security Notice */}
                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                transition={{ delay: 0.3 }}
                                className="bg-gray-50 rounded-xl p-4"
                            >import React, { useState, useRef, useEffect } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import toast from 'react-hot-toast';
import { motion, AnimatePresence } from 'framer-motion';
import { 
    BookOpenIcon, 
    ClockIcon, 
    DocumentTextIcon,
    AcademicCapIcon,
    ShieldCheckIcon,
    IdentificationIcon,
    DocumentDuplicateIcon,
    QrCodeIcon,
    GlobeAltIcon,
    CheckBadgeIcon,
    SparklesIcon,
    CameraIcon,
    LockClosedIcon,
    ClipboardDocumentCheckIcon,
    PhotoIcon,
    ExclamationTriangleIcon
} from '@heroicons/react/24/outline';

export default function EnrollmentIndex({ course, enrollment, modules: initialModules = [], candidate, examResults }) {
    // State management
    const [modules] = useState(initialModules);
    const [progress] = useState(enrollment?.progress || 0);
    const [showIdentityVerification, setShowIdentityVerification] = useState(false);
    const [capturedImage, setCapturedImage] = useState(null);
    const [isVerifying, setIsVerifying] = useState(false);
    const [cameraActive, setCameraActive] = useState(false);
    const [cameraError, setCameraError] = useState(null);
    const [availableCameras, setAvailableCameras] = useState([]);
    const [selectedCamera, setSelectedCamera] = useState('');
    
    // Refs for camera
    const videoRef = useRef(null);
    const canvasRef = useRef(null);
    const streamRef = useRef(null);
    
    const hasCertificate = enrollment?.certificate_generated;
    const certificateNumber = enrollment?.certificate_number || candidate?.certificate_id;
    
    // ============== HELPER FUNCTIONS ==============
    
    // Get status badge color - THIS WAS MISSING
    const getStatusBadge = (status) => {
        const statusMap = {
            'pending_payment': 'bg-yellow-100 text-yellow-800',
            'enrolled': 'bg-green-100 text-green-800',
            'in_progress': 'bg-blue-100 text-blue-800',
            'completed': 'bg-purple-100 text-purple-800',
            'certified': 'bg-indigo-100 text-indigo-800',
            'cancelled': 'bg-red-100 text-red-800'
        };
        return statusMap[status] || 'bg-gray-100 text-gray-800';
    };
    
    // Format candidate ID
    const formatCandidateId = (id) => {
        if (!id) return 'IGRCFP-' + enrollment?.id?.toString().padStart(6, '0');
        return id;
    };

    // ============== CAMERA FUNCTIONS ==============
    
    // Check if camera is supported
    const checkCameraSupport = async () => {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            setCameraError('Camera is not supported in this browser. Please use Chrome, Firefox, or Safari.');
            return;
        }

        try {
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(device => device.kind === 'videoinput');
            setAvailableCameras(videoDevices);
            
            if (videoDevices.length === 0) {
                setCameraError('No camera detected. Please connect a camera and try again.');
            }
        } catch (error) {
            console.error('Error enumerating devices:', error);
        }
    };
    
    // Start camera
    const startCamera = async () => {
        setCameraError(null);
        
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            setCameraError('Your browser does not support camera access. Please use a modern browser like Chrome, Firefox, or Safari.');
            return;
        }

        try {
            // First check if we have permission
            await navigator.mediaDevices.getUserMedia({ video: true });
            
            const constraints = {
                video: {
                    facingMode: 'user',
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }
            };
            
            // If a specific camera is selected, use its deviceId
            if (selectedCamera) {
                constraints.video.deviceId = { exact: selectedCamera };
            }
            
            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            
            if (videoRef.current) {
                videoRef.current.srcObject = stream;
                streamRef.current = stream;
                setCameraActive(true);
                
                videoRef.current.onloadedmetadata = () => {
                    videoRef.current.play().catch(e => {
                        console.error('Error playing video:', e);
                        setCameraError('Could not start video playback.');
                    });
                };
            }
        } catch (error) {
            console.error('Camera error:', error);
            
            if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                setCameraError('Camera access denied. Please allow camera permissions in your browser settings.');
            } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
                setCameraError('No camera found. Please connect a camera and try again.');
            } else if (error.name === 'NotReadableError' || error.name === 'TrackStartError') {
                setCameraError('Camera is already in use by another application.');
            } else {
                setCameraError(`Unable to access camera: ${error.message || 'Unknown error'}`);
            }
            
            toast.error('Failed to start camera. Please check permissions.');
        }
    };

    // Stop camera
    const stopCamera = () => {
        if (streamRef.current) {
            streamRef.current.getTracks().forEach(track => {
                track.stop();
            });
            streamRef.current = null;
        }
        
        if (videoRef.current) {
            videoRef.current.srcObject = null;
        }
        
        setCameraActive(false);
    };

    // Capture photo from camera
    const capturePhoto = () => {
        if (videoRef.current && canvasRef.current && cameraActive) {
            const video = videoRef.current;
            const canvas = canvasRef.current;
            const context = canvas.getContext('2d');
            
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const imageData = canvas.toDataURL('image/jpeg', 0.7);
            setCapturedImage(imageData);
            
            stopCamera();
            
            toast.success('Photo captured successfully!');
        } else {
            toast.error('Camera not active. Please start the camera first.');
        }
    };

    // Retake photo
    const retakePhoto = () => {
        setCapturedImage(null);
        startCamera();
    };

    // Handle file upload fallback
    const handleFileUpload = (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onloadend = () => {
                setCapturedImage(reader.result);
            };
            reader.readAsDataURL(file);
        }
    };

    // ============== VERIFICATION FUNCTIONS ==============
    
    // Handle identity verification
    const handleIdentityVerification = async () => {
        if (!capturedImage) {
            toast.error('Please capture a photo first.');
            return;
        }

        setIsVerifying(true);
        
        const loadingToast = toast.loading('Verifying identity...');
        
        try {
            await router.post(route('exam.verify-identity', enrollment.id), {
                image: capturedImage
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    toast.dismiss(loadingToast);
                    toast.success('Identity verified successfully!', { icon: '✓' });
                    setShowIdentityVerification(false);
                    setCapturedImage(null);
                    stopCamera();
                    
                    // Refresh the page to show updated status
                    router.reload({ only: ['enrollment'] });
                },
                onError: (errors) => {
                    toast.dismiss(loadingToast);
                    console.error('Verification error:', errors);
                    
                    if (errors.image) {
                        toast.error(`Image error: ${errors.image}`);
                    } else {
                        toast.error('Identity verification failed. Please try again.');
                    }
                }
            });
        } catch (error) {
            toast.dismiss(loadingToast);
            console.error('Verification error:', error);
            toast.error('Verification failed. Please check your connection and try again.');
        } finally {
            setIsVerifying(false);
        }
    };

    // Handle modal close
    const handleModalClose = () => {
        setShowIdentityVerification(false);
        setCapturedImage(null);
        setCameraError(null);
        stopCamera();
    };

    // Handle camera selection
    const handleCameraChange = (e) => {
        setSelectedCamera(e.target.value);
    };

    // ============== EXAM FUNCTIONS ==============
    
    // Handle exam start
    const handleStartExam = (examId) => {
        router.post(route('exam.start', { enrollment: enrollment.id, exam: examId }), {}, {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Exam started. Good luck!', { icon: '📝' });
            }
        });
    };

    // Handle exam submission
    const handleSubmitExam = (examId) => {
        if (confirm('Are you sure you want to submit your exam? This action cannot be undone.')) {
            router.post(route('exam.submit', { enrollment: enrollment.id, exam: examId }), {}, {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Exam submitted successfully!');
                }
            });
        }
    };

    // ============== EFFECTS ==============
    
    // Check camera support on mount
    useEffect(() => {
        checkCameraSupport();
        
        // Cleanup on unmount
        return () => {
            stopCamera();
        };
    }, []);

    return (
        <AuthenticatedLayout>
            <Head title={`${course?.title || 'Course'} | My Learning`} />

            <div className="min-h-screen bg-gray-50 py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Breadcrumb Navigation */}
                    <nav className="mb-6 flex items-center text-sm" aria-label="Breadcrumb">
                        <Link href={route('dashboard.index')} className="text-gray-500 hover:text-gray-700 transition">
                            Dashboard
                        </Link>
                        <span className="mx-2 text-gray-400">/</span>
                        <Link href={route('dashboard.my-courses')} className="text-gray-500 hover:text-gray-700 transition">
                            My Courses
                        </Link>
                        <span className="mx-2 text-gray-400">/</span>
                        <span className="text-gray-900 font-medium truncate max-w-xs">{course?.title}</span>
                    </nav>

                    {/* Candidate ID Banner */}
                    {candidate && (
                        <motion.div 
                            initial={{ opacity: 0, y: -20 }}
                            animate={{ opacity: 1, y: 0 }}
                            className="mb-6 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg overflow-hidden"
                        >
                            <div className="px-6 py-4 flex flex-wrap items-center justify-between">
                                <div className="flex items-center gap-4">
                                    <div className="bg-white/20 rounded-lg p-3">
                                        <IdentificationIcon className="w-8 h-8 text-white" />
                                    </div>
                                    <div>
                                        <p className="text-indigo-100 text-sm">Your Candidate ID</p>
                                        <p className="text-2xl font-mono font-bold text-white tracking-wider">
                                            {formatCandidateId(candidate.certificate_id)}
                                        </p>
                                    </div>
                                </div>
                                <div className="flex gap-3 mt-3 sm:mt-0">
                                    <button 
                                        onClick={() => navigator.clipboard.writeText(formatCandidateId(candidate.certificate_id))}
                                        className="flex items-center gap-2 px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition"
                                    >
                                        <DocumentDuplicateIcon className="w-4 h-4" />
                                        Copy ID
                                    </button>
                                    <Link
                                        href={route('certificate.verify', { id: candidate.certificate_id })}
                                        className="flex items-center gap-2 px-4 py-2 bg-white text-indigo-600 rounded-lg hover:bg-gray-100 transition"
                                    >
                                        <ShieldCheckIcon className="w-4 h-4" />
                                        Verify
                                    </Link>
                                </div>
                            </div>
                        </motion.div>
                    )}

                    {/* Main Content Grid */}
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {/* Left Column - Course Info & Progress */}
                        <div className="lg:col-span-2 space-y-6">
                            {/* Course Header Card */}
                            <motion.div 
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                className="bg-white rounded-2xl shadow-sm overflow-hidden"
                            >
                                {/* Cover Image */}
                                <div className="h-64 w-full bg-gradient-to-r from-blue-900 to-indigo-900 relative">
                                    {course?.image_url || course?.banner_image ? (
                                        <img 
                                            src={course.image_url || course.banner_image}
                                            alt={course.title}
                                            className="w-full h-full object-cover opacity-50"
                                        />
                                    ) : (
                                        <div className="absolute inset-0 bg-gradient-to-r from-blue-900 to-indigo-900"></div>
                                    )}
                                    
                                    <div className="absolute bottom-0 left-0 p-8 text-white">
                                        <h1 className="text-3xl font-bold mb-2">{course?.title}</h1>
                                        <div className="flex items-center gap-4 text-sm">
                                            <span className="flex items-center gap-1">
                                                <AcademicCapIcon className="w-4 h-4" />
                                                {modules?.length || 0} modules
                                            </span>
                                            <span>•</span>
                                            <span className="flex items-center gap-1">
                                                <ClockIcon className="w-4 h-4" />
                                                {course?.duration}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {/* Course Info */}
                                <div className="p-6">
                                    <div className="flex flex-wrap items-center justify-between gap-4 mb-4">
                                        <div className="flex items-center gap-3">
                                            {/* Using getStatusBadge here */}
                                            <span className={`px-3 py-1 rounded-full text-sm font-medium ${getStatusBadge(enrollment?.status)}`}>
                                                {enrollment?.status?.replace('_', ' ') || 'Enrolled'}
                                            </span>
                                            {course?.level && (
                                                <span className="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                                    {course.level}
                                                </span>
                                            )}
                                        </div>
                                        <div className="text-sm text-gray-500">
                                            Enrolled: {enrollment?.enrollment_date ? new Date(enrollment.enrollment_date).toLocaleDateString() : 'N/A'}
                                        </div>
                                    </div>

                                    {/* Progress Bar */}
                                    <div className="mb-4">
                                        <div className="flex justify-between text-sm mb-2">
                                            <span className="font-medium text-gray-700">Course Progress</span>
                                            <span className="text-blue-600 font-medium">{progress}% Complete</span>
                                        </div>
                                        <div className="h-3 w-full bg-gray-200 rounded-full overflow-hidden">
                                            <motion.div 
                                                initial={{ width: 0 }}
                                                animate={{ width: `${progress}%` }}
                                                transition={{ duration: 0.5 }}
                                                className="h-full bg-blue-600 rounded-full"
                                            ></motion.div>
                                        </div>
                                    </div>

                                    {/* Course Description */}
                                    {course?.full_description && (
                                        <div className="prose prose-sm max-w-none text-gray-600">
                                            <div dangerouslySetInnerHTML={{ __html: course.full_description }} />
                                        </div>
                                    )}
                                </div>
                            </motion.div>

                            {/* Modules & Content */}
                            <div className="space-y-4">
                                {modules?.length > 0 ? (
                                    modules.map((module, moduleIndex) => (
                                        <motion.div
                                            key={module.id}
                                            initial={{ opacity: 0, y: 20 }}
                                            animate={{ opacity: 1, y: 0 }}
                                            transition={{ delay: moduleIndex * 0.1 }}
                                            className="bg-white rounded-xl shadow-sm overflow-hidden"
                                        >
                                            {/* Module Header */}
                                            <div className="p-6 border-b bg-gray-50">
                                                <div className="flex items-start justify-between">
                                                    <div>
                                                        <div className="flex items-center gap-3 mb-2">
                                                            <span className="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                                                Module {module.module_number}
                                                            </span>
                                                            {module.code && (
                                                                <span className="text-sm text-gray-500">
                                                                    {module.code}
                                                                </span>
                                                            )}
                                                        </div>
                                                        <h2 className="text-xl font-semibold text-gray-900">
                                                            {module.title}
                                                        </h2>
                                                        {module.short_description && (
                                                            <div 
                                                                className="prose prose-sm max-w-none text-gray-600 mt-2"
                                                                dangerouslySetInnerHTML={{ __html: module.short_description }}
                                                            />
                                                        )}
                                                    </div>
                                                    <div className="flex items-center gap-2 text-sm text-gray-500">
                                                        <span className="flex items-center gap-1">
                                                            <ClockIcon className="w-4 h-4" />
                                                            {module.estimated_hours} hrs
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {/* Module Content */}
                                            <div className="p-6">
                                                {/* Materials */}
                                                {module.materials?.length > 0 && (
                                                    <div className="mb-4">
                                                        <h3 className="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">
                                                            Learning Materials
                                                        </h3>
                                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                            {module.materials.map((material) => (
                                                                <a
                                                                    key={material.id}
                                                                    href={material.file_path || material.url}
                                                                    target="_blank"
                                                                    rel="noopener noreferrer"
                                                                    className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition group"
                                                                >
                                                                    <DocumentTextIcon className="w-5 h-5 text-blue-600 group-hover:scale-110 transition" />
                                                                    <span className="text-sm text-gray-900">
                                                                        {material.title}
                                                                    </span>
                                                                </a>
                                                            ))}
                                                        </div>
                                                    </div>
                                                )}
                                            </div>
                                        </motion.div>
                                    ))
                                ) : (
                                    <div className="bg-white rounded-xl shadow-sm p-12 text-center">
                                        <BookOpenIcon className="w-16 h-16 text-gray-400 mx-auto mb-4" />
                                        <h3 className="text-lg font-medium text-gray-900 mb-1">No modules yet</h3>
                                        <p className="text-gray-500">Course content is being prepared.</p>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Right Column - Exams & Certification */}
                        <div className="space-y-6">
                            {/* Identity Verification Card */}
                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                className="bg-white rounded-xl shadow-sm p-6"
                            >
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                    <ShieldCheckIcon className="w-5 h-5 text-indigo-600" />
                                    Identity Verification
                                </h3>
                                
                                {!enrollment?.identity_verified ? (
                                    <div className="space-y-4">
                                        <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                            <p className="text-sm text-yellow-800">
                                                Verify your identity to access exams and generate your certificate.
                                            </p>
                                        </div>
                                        
                                        <button
                                            onClick={() => {
                                                setShowIdentityVerification(true);
                                                setTimeout(() => startCamera(), 100);
                                            }}
                                            className="w-full flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                                        >
                                            <CameraIcon className="w-5 h-5" />
                                            Verify Identity Now
                                        </button>
                                    </div>
                                ) : (
                                    <div className="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center gap-3">
                                        <CheckBadgeIcon className="w-8 h-8 text-green-600" />
                                        <div>
                                            <p className="font-medium text-green-800">Identity Verified</p>
                                            <p className="text-xs text-green-600">Verified on {enrollment?.verified_at ? new Date(enrollment.verified_at).toLocaleDateString() : 'N/A'}</p>
                                        </div>
                                    </div>
                                )}
                            </motion.div>

                            {/* Identity Verification Modal */}
                            <AnimatePresence>
                                {showIdentityVerification && (
                                    <motion.div
                                        initial={{ opacity: 0 }}
                                        animate={{ opacity: 1 }}
                                        exit={{ opacity: 0 }}
                                        className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
                                        onClick={handleModalClose}
                                    >
                                        <motion.div
                                            initial={{ scale: 0.9 }}
                                            animate={{ scale: 1 }}
                                            exit={{ scale: 0.9 }}
                                            className="bg-white rounded-2xl max-w-md w-full p-6"
                                            onClick={e => e.stopPropagation()}
                                        >
                                            <h3 className="text-xl font-bold text-gray-900 mb-4">Identity Verification</h3>
                                            <p className="text-gray-600 mb-6">
                                                Please take a clear photo of your face for verification purposes.
                                            </p>
                                            
                                            <canvas ref={canvasRef} className="hidden" />
                                            
                                            {/* Camera Preview or Captured Image */}
                                            <div className="aspect-video bg-gray-100 rounded-lg mb-4 overflow-hidden relative">
                                                {cameraError ? (
                                                    <div className="absolute inset-0 flex flex-col items-center justify-center p-4 text-center">
                                                        <ExclamationTriangleIcon className="w-12 h-12 text-red-500 mb-2" />
                                                        <p className="text-sm text-red-600 mb-3">{cameraError}</p>
                                                        
                                                        <div className="mt-2">
                                                            <p className="text-xs text-gray-500 mb-2">Or upload a photo instead:</p>
                                                            <label className="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                                                <PhotoIcon className="w-5 h-5" />
                                                                Upload Photo
                                                                <input
                                                                    type="file"
                                                                    accept="image/*"
                                                                    className="hidden"
                                                                    onChange={handleFileUpload}
                                                                />
                                                            </label>
                                                        </div>
                                                        
                                                        <button
                                                            onClick={() => {
                                                                setCameraError(null);
                                                                startCamera();
                                                            }}
                                                            className="mt-3 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm"
                                                        >
                                                            Try Again
                                                        </button>
                                                    </div>
                                                ) : capturedImage ? (
                                                    <img 
                                                        src={capturedImage} 
                                                        alt="Captured" 
                                                        className="w-full h-full object-cover"
                                                    />
                                                ) : cameraActive ? (
                                                    <>
                                                        <video
                                                            ref={videoRef}
                                                            autoPlay
                                                            playsInline
                                                            muted
                                                            className="w-full h-full object-cover"
                                                        />
                                                        <div className="absolute top-2 right-2 flex items-center gap-1 bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                                                            <span className="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                                            Live
                                                        </div>
                                                    </>
                                                ) : (
                                                    <div className="absolute inset-0 flex flex-col items-center justify-center">
                                                        <CameraIcon className="w-12 h-12 text-gray-400 mb-2" />
                                                        <p className="text-sm text-gray-500">Camera is off</p>
                                                    </div>
                                                )}
                                            </div>

                                            {/* Camera Selection */}
                                            {availableCameras.length > 1 && !capturedImage && !cameraError && (
                                                <div className="mb-4">
                                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                                        Select Camera
                                                    </label>
                                                    <select
                                                        onChange={handleCameraChange}
                                                        value={selectedCamera}
                                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                                    >
                                                        <option value="">Default Camera</option>
                                                        {availableCameras.map((camera) => (
                                                            <option key={camera.deviceId} value={camera.deviceId}>
                                                                {camera.label || `Camera ${camera.deviceId.slice(0, 5)}...`}
                                                            </option>
                                                        ))}
                                                    </select>
                                                </div>
                                            )}

                                            <div className="flex gap-3">
                                                {!capturedImage ? (
                                                    <>
                                                        {!cameraActive && !cameraError ? (
                                                            <button
                                                                onClick={startCamera}
                                                                className="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                                                            >
                                                                Start Camera
                                                            </button>
                                                        ) : cameraActive ? (
                                                            <button
                                                                onClick={capturePhoto}
                                                                className="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                                                            >
                                                                Capture Photo
                                                            </button>
                                                        ) : null}
                                                        
                                                        {cameraError && !capturedImage && (
                                                            <div className="w-full">
                                                                <label className="w-full cursor-pointer inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                                                    <PhotoIcon className="w-5 h-5" />
                                                                    Upload Photo Instead
                                                                    <input
                                                                        type="file"
                                                                        accept="image/*"
                                                                        className="hidden"
                                                                        onChange={handleFileUpload}
                                                                    />
                                                                </label>
                                                            </div>
                                                        )}
                                                    </>
                                                ) : (
                                                    <>
                                                        <button
                                                            onClick={retakePhoto}
                                                            className="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition"
                                                        >
                                                            Retake
                                                        </button>
                                                        <button
                                                            onClick={handleIdentityVerification}
                                                            disabled={isVerifying}
                                                            className="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition disabled:opacity-50"
                                                        >
                                                            {isVerifying ? 'Verifying...' : 'Submit'}
                                                        </button>
                                                    </>
                                                )}
                                            </div>
                                            
                                            {/* Close button */}
                                            <button
                                                onClick={handleModalClose}
                                                className="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition"
                                            >
                                                <span className="sr-only">Close</span>
                                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </motion.div>
                                    </motion.div>
                                )}
                            </AnimatePresence>

                            {/* Exams Card */}
                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                transition={{ delay: 0.1 }}
                                className="bg-white rounded-xl shadow-sm p-6"
                            >
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                    <ClipboardDocumentCheckIcon className="w-5 h-5 text-purple-600" />
                                    Timed Online Exams
                                </h3>

                                {enrollment?.exams?.length > 0 ? (
                                    <div className="space-y-4">
                                        {enrollment.exams.map((exam) => (
                                            <div key={exam.id} className="border rounded-lg p-4">
                                                <div className="flex justify-between items-start mb-2">
                                                    <h4 className="font-medium text-gray-900">{exam.title}</h4>
                                                    <span className="text-xs px-2 py-1 bg-purple-100 text-purple-800 rounded-full">
                                                        {exam.duration} mins
                                                    </span>
                                                </div>
                                                
                                                <div className="text-sm text-gray-600 mb-3">
                                                    <p>• Randomised question bank</p>
                                                    <p>• Plagiarism detection enabled</p>
                                                    {exam.type === 'diploma' && (
                                                        <p>• Manual marking by instructors</p>
                                                    )}
                                                </div>

                                                {exam.status === 'pending' && (
                                                    <button
                                                        onClick={() => handleStartExam(exam.id)}
                                                        disabled={!enrollment?.identity_verified}
                                                        className="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition disabled:opacity-50"
                                                    >
                                                        Start Exam
                                                    </button>
                                                )}

                                                {exam.status === 'in_progress' && (
                                                    <div className="space-y-2">
                                                        <div className="h-2 bg-gray-200 rounded-full">
                                                            <div className="h-2 bg-purple-600 rounded-full" style={{ width: `${exam.progress}%` }}></div>
                                                        </div>
                                                        <button
                                                            onClick={() => handleSubmitExam(exam.id)}
                                                            className="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                                                        >
                                                            Submit Exam
                                                        </button>
                                                    </div>
                                                )}

                                                {exam.status === 'completed' && (
                                                    <div className="bg-green-50 rounded-lg p-3 text-center">
                                                        <p className="text-green-800 font-medium">Score: {exam.score}%</p>
                                                        <p className="text-xs text-green-600">Awaiting {exam.type === 'diploma' ? 'manual review' : 'finalization'}</p>
                                                    </div>
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <div className="text-center py-6">
                                        <p className="text-gray-500 mb-3">No exams scheduled yet</p>
                                        <LockClosedIcon className="w-8 h-8 text-gray-400 mx-auto" />
                                    </div>
                                )}
                            </motion.div>

                            {/* Certification Card */}
                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                transition={{ delay: 0.2 }}
                                className="bg-white rounded-xl shadow-sm p-6 border-2 border-indigo-100"
                            >
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                    <CheckBadgeIcon className="w-5 h-5 text-indigo-600" />
                                    Digital Certification
                                </h3>

                                {hasCertificate ? (
                                    <div className="space-y-4">
                                        <div className="bg-indigo-50 rounded-lg p-4">
                                            <div className="flex items-center gap-3 mb-3">
                                                <QrCodeIcon className="w-10 h-10 text-indigo-600" />
                                                <div>
                                                    <p className="text-xs text-indigo-600">Certificate Number</p>
                                                    <p className="font-mono font-bold text-indigo-900">{certificateNumber}</p>
                                                </div>
                                            </div>
                                            
                                            <div className="flex gap-2">
                                                <Link
                                                    href={route('certificate.download', enrollment.id)}
                                                    className="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm"
                                                >
                                                    <DocumentTextIcon className="w-4 h-4" />
                                                    Download PDF
                                                </Link>
                                                <Link
                                                    href={route('certificate.preview', enrollment.id)}
                                                    className="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-white border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50 transition text-sm"
                                                >
                                                    <GlobeAltIcon className="w-4 h-4" />
                                                    Preview
                                                </Link>
                                            </div>
                                        </div>

                                        <Link
                                            href={route('certificate.badge', enrollment.id)}
                                            className="flex items-center justify-between p-3 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-lg hover:from-amber-100 hover:to-yellow-100 transition"
                                        >
                                            <div className="flex items-center gap-3">
                                                <SparklesIcon className="w-5 h-5 text-amber-600" />
                                                <div>
                                                    <p className="font-medium text-amber-900">Digital Badge</p>
                                                    <p className="text-xs text-amber-700">Claim your verifiable badge</p>
                                                </div>
                                            </div>
                                            <span className="text-amber-600">→</span>
                                        </Link>
                                    </div>
                                ) : (
                                    <div className="space-y-4">
                                        <p className="text-gray-600 text-sm">
                                            Complete all exams and requirements to earn your digital certificate.
                                        </p>
                                        
                                        <div className="bg-gray-50 rounded-lg p-4">
                                            <h4 className="font-medium text-gray-900 mb-2">Requirements:</h4>
                                            <ul className="text-sm text-gray-600 space-y-1">
                                                <li className="flex items-center gap-2">
                                                    <span className={`w-4 h-4 rounded-full ${enrollment?.identity_verified ? 'bg-green-500' : 'bg-gray-300'} flex items-center justify-center text-white text-xs`}>
                                                        {enrollment?.identity_verified ? '✓' : ''}
                                                    </span>
                                                    Identity verification
                                                </li>
                                                <li className="flex items-center gap-2">
                                                    <span className={`w-4 h-4 rounded-full ${examResults?.passed ? 'bg-green-500' : 'bg-gray-300'} flex items-center justify-center text-white text-xs`}>
                                                        {examResults?.passed ? '✓' : ''}
                                                    </span>
                                                    Pass all exams
                                                </li>
                                                <li className="flex items-center gap-2">
                                                    <span className={`w-4 h-4 rounded-full ${progress === 100 ? 'bg-green-500' : 'bg-gray-300'} flex items-center justify-center text-white text-xs`}>
                                                        {progress === 100 ? '✓' : ''}
                                                    </span>
                                                    100% course completion
                                                </li>
                                            </ul>
                                        </div>

                                        {/* Certification Registry Link */}
                                        <Link
                                            href={route('dashboard.certificate.registry')}
                                            className="flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 transition"
                                        >
                                            <GlobeAltIcon className="w-4 h-4" />
                                            View Certification Registry
                                        </Link>
                                    </div>
                                )}
                            </motion.div>

                            {/* Plagiarism & Security Notice */}
                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                transition={{ delay: 0.3 }}
                                className="bg-gray-50 rounded-xl p-4"
                            >
                                <div className="flex items-start gap-3">
                                    <ShieldCheckIcon className="w-5 h-5 text-gray-600 flex-shrink-0 mt-0.5" />
                                    <div>
                                        <p className="text-sm font-medium text-gray-900 mb-1">Academic Integrity</p>
                                        <p className="text-xs text-gray-600">
                                            All submissions are monitored by our plagiarism detection software. 
                                            Your unique candidate ID ensures your work is properly attributed.
                                        </p>
                                    </div>
                                </div>
                            </motion.div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
                                <div className="flex items-start gap-3">
                                    <ShieldCheckIcon className="w-5 h-5 text-gray-600 flex-shrink-0 mt-0.5" />
                                    <div>
                                        <p className="text-sm font-medium text-gray-900 mb-1">Academic Integrity</p>
                                        <p className="text-xs text-gray-600">
                                            All submissions are monitored by our plagiarism detection software. 
                                            Your unique candidate ID ensures your work is properly attributed.
                                        </p>
                                    </div>
                                </div>
                            </motion.div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}