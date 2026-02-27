import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { 
  CheckCircleIcon, 
  ClockIcon,  
  BookOpenIcon, 
  AcademicCapIcon, 
  UsersIcon,
  LockClosedIcon,
  PlayIcon,
  StarIcon,
  DocumentTextIcon,
  ArrowDownTrayIcon,
  ChevronRightIcon,
  CalendarIcon,
  UserGroupIcon, 
  ChartBarIcon
} from '@heroicons/react/24/outline';

export default function Show({ course, enrollment, modules = [] }) {
    // Calculate progress
    const progress = enrollment?.progress || 0;
    const { startEnrollment, user } = useEnrollment();
    
    // Get status badge color
    const getStatusBadge = (status) => {
        const statusMap = {
            'pending_payment': 'bg-yellow-100 text-yellow-800',
            'enrolled': 'bg-green-100 text-green-800',
            'completed': 'bg-blue-100 text-blue-800',
            'cancelled': 'bg-red-100 text-red-800'
        };
        return statusMap[status] || 'bg-gray-100 text-gray-800';
    };

    // Safe parsing functions
    const parseFloatSafe = (value) => {
        if (value === null || value === undefined || value === '') return 0;
        const num = parseFloat(value);
        return isNaN(num) ? 0 : num;
    };

    const formatPrice = (price) => {
        const numPrice = parseFloatSafe(price);
        return numPrice === 0 ? 'Free' : `$${numPrice.toFixed(2)}`;
    };

    // Parse prices safely
    const price = parseFloatSafe(course.price);
    const discountPrice = parseFloatSafe(course.discount_price);

    // Check for discount
    const hasDiscount = discountPrice > 0 && discountPrice < price;
    const discountPercentage = hasDiscount && price > 0 
        ? Math.round(((price - discountPrice) / price) * 100) 
        : 0;

    const getCleanDescription = (text) => {
        if (!text) return 'No description available';
        
        const cleanText = text.replace(/<\/?[^>]+(>|$)/g, "");
        return cleanText.length > 100 ? cleanText.substring(0, 700) + '...' : cleanText;
    };

    return (
        <AuthenticatedLayout>
            <Head title={`${course.title} | My Learning`} />

            {/* Hero Banner */}
            <div className="relative bg-gradient-to-r from-gray-900 to-blue-900 text-white">
                <div className="absolute inset-0 bg-black/50"></div>
                <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div className="flex flex-col lg:flex-row items-start gap-8">
                    {/* Course Title & Basic Info */}
                    <div className="lg:w-2/3">
                    <div className="flex items-center gap-2 mb-4">
                        <span className="px-3 py-1 bg-white/20 rounded-full text-sm font-medium">
                        {course.level || 'All Levels'}
                        </span>
                        <span className="px-3 py-1 bg-white/20 rounded-full text-sm font-medium">
                        {course.format || 'Self-Paced'}
                        </span>
                        {course.is_featured && (
                        <span className="px-3 py-1 bg-yellow-500/20 rounded-full text-sm font-medium flex items-center">
                            <StarIcon className="h-3 w-3 mr-1" /> Featured
                        </span>
                        )}
                    </div>
                    
                    <h1 className="text-4xl lg:text-5xl font-bold mb-4">{course.title}</h1>
                    <p className="text-xl text-gray-200 mb-8 max-w-3xl">{getCleanDescription(course.short_description)}</p>
                    
                    <div className="flex flex-wrap gap-6 mb-8">
                        <div className="flex items-center">
                        <ClockIcon className="h-5 w-5 mr-2" />
                        <span>{course.duration || 'Flexible Schedule'} hours</span>
                        </div>
                        <div className="flex items-center">
                        <BookOpenIcon className="h-5 w-5 mr-2" />
                        <span>{course.total_modules || modules.length || 0} Modules</span>
                        </div>
                        <div className="flex items-center">
                        <CalendarIcon className="h-5 w-5 mr-2" />
                        <span>{course.total_hours || 'Flexible'} Hours Total</span>
                        </div>
                    </div>
                    </div>
    
                    {/* Pricing Card */}
                    <div className="lg:w-1/3 w-full">
                    <div className="bg-white rounded-xl shadow-2xl p-6 text-gray-900">
                        <div className="flex items-center justify-between mb-4">
                        <h3 className="text-lg font-bold">Enroll Now</h3>
                        {hasDiscount && (
                            <span className="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold">
                            {discountPercentage}% OFF
                            </span>
                        )}
                        </div>
                        
                        <div className="mb-6">
                        {hasDiscount ? (
                            <div className="space-y-2">
                            <div className="flex items-center">
                                <span className="text-3xl font-bold">${discountPrice.toFixed(2)}</span>
                                <span className="text-lg line-through text-gray-500 ml-2">${price.toFixed(2)}</span>
                            </div>
                            <p className="text-sm text-green-600 font-medium">Limited time offer</p>
                            </div>
                        ) : (
                            <div className="text-3xl font-bold">{formatPrice(price)}</div>
                        )}
                        <p className="text-sm text-gray-600 mt-2">One-time payment • Lifetime access</p>
                        </div>
    
                        <button 
                        onClick={() => startEnrollment(course)}
                        className="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 px-6 rounded-lg transition duration-200 mb-4"
                        >
                        {user ? 'Enroll Now' : 'Sign In to Enroll'}
                        </button>
                        
                        <div className="space-y-3 text-sm">
                        <div className="flex items-center">
                            <CheckCircleIcon className="h-5 w-5 text-green-500 mr-2" />
                            <span>30-day money-back guarantee</span>
                        </div>
                        <div className="flex items-center">
                            <CheckCircleIcon className="h-5 w-5 text-green-500 mr-2" />
                            <span>Certificate of completion</span>
                        </div>
                        <div className="flex items-center">
                            <CheckCircleIcon className="h-5 w-5 text-green-500 mr-2" />
                            <span>24/7 support access</span>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Breadcrumb */}
                    <div className="mb-6 flex items-center text-sm">
                        <Link href={route('dashboard.index')} className="text-gray-500 hover:text-gray-700">
                            Dashboard
                        </Link>
                        <span className="mx-2 text-gray-400">/</span>
                        <Link href={route('dashboard.my-courses')} className="text-gray-500 hover:text-gray-700">
                            Courses
                        </Link>
                        <span className="mx-2 text-gray-400">/</span>
                        <span className="text-gray-900 font-medium">{course.title}</span>
                    </div>

                    {/* Course Header */}
                    <div className="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                        {/* Cover Image */}
                        <div className="h-64 w-full bg-gradient-to-r from-blue-900 to-indigo-900 relative">
                            {course.image_url || course.banner_image ? (
                                <img 
                                    src={course.image_url || course.banner_image}
                                    alt={course.title}
                                    className="w-full h-full object-cover opacity-50"
                                />
                            ) : (
                                <div className="absolute inset-0 bg-gradient-to-r from-blue-900 to-indigo-900"></div>
                            )}
                            
                            <div className="absolute bottom-0 left-0 p-8 text-white">
                                <h1 className="text-3xl font-bold mb-2">{course.title}</h1>
                                <div className="flex items-center gap-4">
                                    <span className="flex items-center gap-1">
                                        <span className="text-yellow-400">★</span>
                                        <span>{course.rating || 'New'}</span>
                                    </span>
                                    <span>•</span>
                                    <span>{course.modules_count || 0} modules</span>
                                    <span>•</span>
                                    <span>{course.duration}</span>
                                </div>
                            </div>
                        </div>

                        {/* Course Info */}
                        <div className="p-6">
                            <div className="flex flex-wrap items-center justify-between gap-4 mb-4">
                                <div className="flex items-center gap-3">
                                    <span className={`px-3 py-1 rounded-full text-sm font-medium ${getStatusBadge(enrollment?.status)}`}>
                                        {enrollment?.status?.replace('_', ' ') || 'Enrolled'}
                                    </span>
                                    {course.level && (
                                        <span className="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                            {course.level}
                                        </span>
                                    )}
                                    {course.format && (
                                        <span className="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">
                                            {course.format}
                                        </span>
                                    )}
                                </div>
                                <div className="text-sm text-gray-500">
                                    Enrolled on {new Date(enrollment?.enrollment_date).toLocaleDateString()}
                                </div>
                            </div>

                            {/* Progress Bar */}
                            <div className="mb-6">
                                <div className="flex justify-between text-sm mb-2">
                                    <span className="font-medium text-gray-700">Your Progress</span>
                                    <span className="text-blue-600 font-medium">{progress}% Complete</span>
                                </div>
                                <div className="h-3 w-full bg-gray-200 rounded-full overflow-hidden">
                                    <div 
                                        className="h-full bg-blue-600 rounded-full transition-all duration-500"
                                        style={{ width: `${progress}%` }}
                                    ></div>
                                </div>
                            </div>

                            {/* Description */}
                            <div className="prose max-w-none">
                                <h3 className="text-lg font-semibold mb-2">About this course</h3>
                                <p className="text-gray-600">{course.description || 'No description available.'}</p>
                            </div>
                        </div>
                    </div>

                    {/* Course Modules */}
                    <div className="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div className="p-6 border-b">
                            <h2 className="text-xl font-semibold">Course Content</h2>
                        </div>
                        
                        {modules.length > 0 ? (
                            <div className="divide-y">
                                {modules.map((module, index) => (
                                    <div key={module.id} className="p-6">
                                        <div className="flex items-start justify-between mb-3">
                                            <div>
                                                <h3 className="font-semibold text-lg">
                                                    Module {index + 1}: {module.title}
                                                </h3>
                                                {module.description && (
                                                    <p className="text-sm text-gray-500 mt-1">{module.description}</p>
                                                )}
                                            </div>
                                            <span className="text-sm text-gray-500">
                                                {module.lessons?.length || 0} lessons
                                            </span>
                                        </div>

                                        {/* Lessons */}
                                        {module.lessons && module.lessons.length > 0 && (
                                            <div className="ml-4 space-y-2">
                                                {module.lessons.map((lesson, lessonIndex) => (
                                                    <div key={lesson.id} className="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg">
                                                        <span className="text-sm text-gray-400 w-6">{lessonIndex + 1}.</span>
                                                        <span className="flex-1">{lesson.title}</span>
                                                        <span className="text-xs text-gray-400">{lesson.duration}</span>
                                                        {lesson.is_completed && (
                                                            <span className="text-green-600">✓</span>
                                                        )}
                                                    </div>
                                                ))}
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <div className="p-12 text-center">
                                <div className="text-gray-400 mb-2">📚</div>
                                <h3 className="text-lg font-medium text-gray-900 mb-1">No modules yet</h3>
                                <p className="text-gray-500">This course content is being prepared.</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}