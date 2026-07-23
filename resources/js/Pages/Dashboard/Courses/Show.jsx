import React, { useState } from 'react';
import toast from 'react-hot-toast';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useEnrollment } from '@/Contexts/EnrollmentContext';
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
  ChartBarIcon,
  ShieldCheckIcon,
  GlobeAltIcon,
  TrophyIcon,
  PhotoIcon,
  GiftIcon // Added for scholarship icon
} from '@heroicons/react/24/outline';
import {  
  CheckCircleIcon as CheckCircleSolid 
} from '@heroicons/react/24/solid';

// Utility functions
const formatPrice = (price) => {
  const amount = Number(price);
  if (!amount) return 'Free';
  return `£${new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2
  }).format(amount)}`;
};

const stripHtml = (html) => {
  if (!html) return '';
  return html.replace(/<\/?[^>]+(>|$)/g, "");
};

const truncateText = (text, length = 160) => {
  if (!text) return '';
  const cleanText = stripHtml(text);
  return cleanText.length > length 
    ? `${cleanText.substring(0, length)}...` 
    : cleanText;
};

export default function Show({ course, enrollment, modules = [], auth }) {
  const { props } = usePage();
  const { user } = auth || props.auth || {};
  const [activeTab, setActiveTab] = useState('overview');
  const [expandedModule, setExpandedModule] = useState(null);
  const [isEnrolling, setIsEnrolling] = useState(false);
  
  const isEnrolled = !!enrollment;
  const progress = enrollment?.progress || 0;

  // Get image URLs
  const courseImageUrl = course.image_url || course.image;

  // Price calculations
  const price = parseFloat(course.price) || 0;
  const discountPrice = parseFloat(course.discount_price) || 0;
  const hasDiscount = discountPrice > 0 && discountPrice < price;
  const discountPercentage = hasDiscount && price > 0
    ? Math.round(((price - discountPrice) / price) * 100)
    : 0;

  // Scholarship Logic
  const isScholarshipApplicant = !!user?.is_scholarship_applicant;
  const isEligibleCourse = course?.is_scholarship_eligible === true;
  const canUseScholarship = isScholarshipApplicant && isEligibleCourse;

  // Tab configuration
  const tabs = [
    { id: 'overview', label: 'Overview', icon: BookOpenIcon },
    { id: 'curriculum', label: 'Curriculum', icon: DocumentTextIcon },
    { id: 'outcomes', label: 'Learning Outcomes', icon: ChartBarIcon },
    { id: 'audience', label: 'Who It\'s For', icon: UsersIcon },
    { id: 'certification', label: 'Certification', icon: AcademicCapIcon },
  ];

  // Handle Direct Scholarship Enrollment
  const handleScholarshipEnroll = () => {
    setIsEnrolling(true);
    const loadingToast = toast.loading('Activating scholarship...');
    
    router.post(route('courses.enroll', course.slug), {}, {
      preserveScroll: true,
      onSuccess: () => {
        toast.dismiss(loadingToast);
        toast.success('Scholarship activated! You are now enrolled.', {
          icon: '🎓',
          style: { background: '#10b981', color: '#fff' }
        });
        window.location.reload();
      },
      onError: (errors) => {
        toast.dismiss(loadingToast);
        console.error("Enrollment failed", errors);
        toast.error('Failed to activate scholarship.', {
          style: { background: '#ef4444', color: '#fff' }
        });
        setIsEnrolling(false);
      }
    });
  };

  // Direct enrollment for free courses - follows cart → checkout pattern
  const directEnrollment = (course) => {
    setIsEnrolling(true);
    const loadingToast = toast.loading('Adding to cart...');
    
    // Step 1: Add to cart first (same as paid courses)
    router.post(route('dashboard.cart.add', course.slug), {}, {
      preserveState: true,
      onSuccess: (page) => {
        toast.dismiss(loadingToast);
        
        if (page.props.flash?.success) {
          toast.success('Course added to cart!');
          router.visit(route('checkout.index'));
        } else if (page.props.flash?.info) {
          toast.success('Course is already in your cart!');
          router.visit(route('checkout.index'));
        } else {
          toast.success('Proceeding to checkout...');
          router.visit(route('checkout.index'));
        }
        setIsEnrolling(false);
      },
      onError: (errors) => {
        toast.dismiss(loadingToast);
        setIsEnrolling(false);
        console.error('Cart add errors:', errors);
        toast.error('Failed to add course to cart. Please try again.');
      }
    });
  };

  const startEnrollment = (course) => {
    // Check if user is authenticated
    if (!user) {
      toast.error('Please login to enroll');
      router.visit(route('login'));
      return;
    }

    // For paid courses, add to cart and go to checkout
    if (course.price > 0) {
      // Show loading
      const loadingToast = toast.loading('Processing...');
      
      // Add to cart first
      router.post(route('dashboard.cart.add', course.slug), {}, {
        preserveState: true,
        onSuccess: (page) => {
          toast.dismiss(loadingToast);
          
          if (page.props.flash?.success) {
            router.visit(route('checkout.index'));
          } else {
            toast.success('Course added to cart!');
            router.visit(route('dashboard.cart.index'));
          }
        },
        onError: (errors) => {
          toast.dismiss(loadingToast);
          toast.error('Failed to add course to cart');
        }
      });
    } else {
      // For free courses, enroll directly
      directEnrollment(course);
    }
  };

  const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  return (
    <AuthenticatedLayout>
      <Head title={`${course.title} | Professional Certification`} />

      {/* Breadcrumb Navigation */}
      <nav className="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center h-16 text-sm">
            <Link href={route('dashboard.index')} className="text-gray-500 hover:text-gray-700 transition">
              Dashboard
            </Link>
            <ChevronRightIcon className="w-4 h-4 mx-2 text-gray-400" />
            <Link href={route('dashboard.courses.index')} className="text-gray-500 hover:text-gray-700 transition">
              Courses
            </Link>
            <ChevronRightIcon className="w-4 h-4 mx-2 text-gray-400" />
            <span className="text-gray-900 font-medium truncate">{course.title}</span>
          </div>
        </div>
      </nav>

      {/* Hero Section */}
      <div className="relative bg-gradient-to-br from-gray-900 via-blue-900 to-indigo-900">
        
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
          <div className="grid lg:grid-cols-3 gap-12">
            {/* Main Content */}
            <div className="lg:col-span-2 text-white">
              <div className="flex items-center gap-4 mb-6">
                {course.level && (
                  <span className="px-4 py-1.5 bg-white/10 backdrop-blur rounded-full text-sm font-medium border border-white/20">
                    {course.level}
                  </span>
                )}
                {course.format && (
                  <span className="px-4 py-1.5 bg-white/10 backdrop-blur rounded-full text-sm font-medium border border-white/20">
                    {course.format}
                  </span>
                )}
                {course.is_featured && (
                  <span className="px-4 py-1.5 bg-yellow-500/20 backdrop-blur rounded-full text-sm font-medium border border-yellow-400/30 flex items-center gap-1">
                    <StarIcon className="w-4 h-4 text-yellow-400" />
                    Featured
                  </span>
                )}
              </div>

              <h1 className="text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                {course.title}
              </h1>

              <div 
                className="
                  text-xl 
                  prose-headings:font-bold prose-headings:text-gray-900
                  prose-p:text-gray-600 prose-p:leading-relaxed
                  prose-ul:list-disc prose-ul:pl-5
                  prose-li:text-gray-600
                  prose-strong:text-gray-900
                  prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline
                "
                dangerouslySetInnerHTML={{ __html: course.short_description || truncateText(course.description, 200) }}
              />

              <div className="flex flex-wrap items-center gap-6 text-gray-200">
                {course.rating && (
                  <div className="flex items-center gap-2">
                    <div className="flex">
                      {[...Array(5)].map((_, i) => (
                        <StarIcon 
                          key={i} 
                          className={`w-5 h-5 ${i < Math.floor(course.rating) ? 'text-yellow-400' : 'text-gray-400'}`}
                        />
                      ))}
                    </div>
                    <span>{course.rating}</span>
                  </div>
                )}
                
                <div className="flex items-center gap-2">
                  <BookOpenIcon className="w-5 h-5" />
                  <span>{modules.length} Modules</span>
                </div>
                
                {course.duration && (
                  <div className="flex items-center gap-2">
                    <ClockIcon className="w-5 h-5" />
                    <span>{course.duration}</span>
                  </div>
                )}
              </div>
            </div>

            {/* Enrollment Card */}
            <div className="lg:col-span-1">
              <motion.div 
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                className="bg-white rounded-2xl shadow-2xl overflow-hidden"
              >
                {/* Course Image in Enrollment Card */}
                {courseImageUrl && (
                  <div className="relative h-48 bg-gray-100">
                    <img 
                      src={courseImageUrl} 
                      alt={course.title}
                      className="w-full h-full object-cover"
                    />
                  </div>
                )}
                
                {hasDiscount && !canUseScholarship && (
                  <div className="bg-gradient-to-r from-red-500 to-red-600 text-white text-center py-2 font-bold">
                    {discountPercentage}% OFF - Limited Time Offer
                  </div>
                )}
                
                <div className="p-8">
                  <div className="mb-6">
                    {canUseScholarship ? (
                      /* SCHOLARSHIP PRICING VIEW */
                      <div className="mb-4 p-4 bg-emerald-50 border border-emerald-100 rounded-lg">
                        <div className="flex items-center gap-2 mb-2">
                          <GiftIcon className="h-6 w-6 text-emerald-600" />
                          <span className="text-lg font-bold text-emerald-800">Scholarship Covered</span>
                        </div>
                        <p className="text-sm text-emerald-700">
                          Your scholarship application has been approved. You can access this course at no cost.
                        </p>
                      </div>
                    ) : hasDiscount ? (
                      <>
                        <div className="flex items-baseline gap-3 mb-2">
                          <span className="text-4xl font-bold text-gray-900">
                            {formatPrice(discountPrice)}
                          </span>
                          <span className="text-xl line-through text-gray-400">
                            {formatPrice(price)}
                          </span>
                        </div> 
                        <p className="text-green-600 font-medium flex items-center gap-1">
                          <CheckCircleSolid className="w-4 h-4" />
                          Save {formatPrice(price - discountPrice)}
                        </p>
                      </>
                    ) : (
                      <span className="text-4xl font-bold text-gray-900">
                        {formatPrice(price)}
                      </span>
                    )}
                    
                    {!canUseScholarship && (
                      <p className="text-sm text-gray-500 mt-2">
                        One-time payment • Lifetime access
                      </p>
                    )}
                  </div>

                  {isEnrolled ? (
                    <div className="space-y-4">
                      <div className="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div className="flex items-center gap-2 text-green-700 font-medium mb-2">
                          <CheckCircleSolid className="w-5 h-5" />
                          <span>You're enrolled!</span>
                        </div>
                        <div className="text-sm text-gray-600">
                          Enrolled on {formatDate(enrollment.created_at)}
                        </div>
                      </div>
                      
                      <Link
                        href={route('dashboard.learning', course.slug)}
                        className="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-xl text-center transition transform hover:-translate-y-0.5"
                      >
                        Continue Learning
                      </Link>
                    </div>
                  ) : canUseScholarship ? (
                    /* SCHOLARSHIP BUTTON */
                    <button
                      onClick={handleScholarshipEnroll}
                      disabled={isEnrolling}
                      className={`w-full text-white font-bold py-3 px-2 rounded-xl transition transform hover:-translate-y-0.5 mb-4 flex items-center justify-center gap-2 ${
                        isEnrolling 
                          ? 'bg-gray-400 cursor-not-allowed' 
                          : 'bg-emerald-600 hover:bg-emerald-700'
                      }`}
                    >
                      <CheckCircleSolid className="w-5 h-5" />
                      {isEnrolling ? 'Activating...' : 'Activate Scholarship'}
                    </button>
                  ) : (
                    <button
                      onClick={() => startEnrollment(course)}
                      disabled={isEnrolling}
                      className={`w-full text-white font-bold py-3 px-2 rounded-xl transition transform hover:-translate-y-0.5 mb-4 ${
                        isEnrolling 
                          ? 'bg-gray-400 cursor-not-allowed' 
                          : 'bg-blue-600 hover:bg-blue-700'
                      }`}
                    >
                      {isEnrolling ? 'Enrolling...' : 'Enroll Now'}
                    </button>
                  )}

                  <div className="border-t border-gray-100 pt-6 mt-2">
                    <h4 className="font-semibold text-gray-900 mb-3">This includes:</h4>
                    <ul className="space-y-2 text-sm text-gray-600">
                      <li className="flex items-center gap-2">
                        <CheckCircleSolid className="w-4 h-4 text-green-500" />
                        <span>Full course access</span>
                      </li>
                      <li className="flex items-center gap-2">
                        <CheckCircleSolid className="w-4 h-4 text-green-500" />
                        <span>Downloadable resources</span>
                      </li>
                      <li className="flex items-center gap-2">
                        <CheckCircleSolid className="w-4 h-4 text-green-500" />
                        <span>Certificate of completion</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </motion.div>
            </div>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {/* Progress Bar (if enrolled) */}
        {isEnrolled && (
          <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div className="flex items-center justify-between mb-3">
              <h3 className="font-semibold text-gray-900">Your Progress</h3>
              <span className="text-blue-600 font-bold">{progress}% Complete</span>
            </div>
            <div className="h-3 bg-gray-100 rounded-full overflow-hidden">
              <motion.div
                initial={{ width: 0 }}
                animate={{ width: `${progress}%` }}
                transition={{ duration: 0.5 }}
                className="h-full bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full"
              />
            </div>
          </div>
        )}

        {/* Tabs Navigation */}
        <div className="border-b border-gray-200 mb-8">
          <nav className="flex space-x-8 overflow-x-auto scrollbar-hide">
            {tabs.map((tab) => {
              const Icon = tab.icon;
              const isActive = activeTab === tab.id;
              
              return (
                <button
                  key={tab.id}
                  onClick={() => setActiveTab(tab.id)}
                  className={`
                    py-4 px-1 border-b-2 font-medium text-sm flex items-center whitespace-nowrap
                    transition-all duration-200
                    ${isActive 
                      ? 'border-blue-600 text-blue-600' 
                      : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                    }
                  `}
                >
                  <Icon className={`w-5 h-5 mr-2 ${isActive ? 'text-blue-600' : 'text-gray-400'}`} />
                  {tab.label}
                </button>
              );
            })}
          </nav>
        </div>

        {/* Tab Content */}
        <AnimatePresence mode="wait">
          <motion.div
            key={activeTab}
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -20 }}
            transition={{ duration: 0.2 }}
            className="grid lg:grid-cols-3 gap-8"
          >
            {/* Main Content Area */}
            <div className="lg:col-span-2 space-y-8">
              {/* Overview Tab */}
              {activeTab === 'overview' && (
                <div className="bg-white rounded-xl border border-gray-200 p-8">
                  {/* Course Image in Overview (for mobile/tablet) */}
                  {courseImageUrl && (
                    <div className="lg:hidden mb-6">
                      <img 
                        src={courseImageUrl} 
                        alt={course.title}
                        className="w-full h-48 object-cover rounded-lg shadow-md"
                      />
                    </div>
                  )}
                  
                  <h2 className="text-2xl font-bold text-gray-900 mb-6">About This Course</h2>
                  <div className="prose prose-lg max-w-none">
                    {course.full_description ? (
                      <div 
                        className="
                          prose-headings:font-bold prose-headings:text-gray-900
                          prose-p:text-gray-600 prose-p:leading-relaxed
                          prose-ul:list-disc prose-ul:pl-5
                          prose-li:text-gray-600
                          prose-strong:text-gray-900
                          prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline
                        "
                        dangerouslySetInnerHTML={{ __html: course.full_description }}
                      />
                    ) : (
                      <p className="text-gray-600">{course.description || 'No description available.'}</p>
                    )}
                  </div>
                </div>
              )}

              {/* Curriculum Tab */}
              {activeTab === 'curriculum' && (
                <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
                  <div className="p-6 border-b border-gray-200 bg-gray-50">
                    <h2 className="text-2xl font-bold text-gray-900">Course Curriculum</h2>
                    <p className="text-gray-600 mt-1">
                      {modules.length} modules • {course.total_hours || 'Self-paced'} learning
                    </p>
                  </div>

                  <div className="divide-y divide-gray-200">
                    {modules.map((module, index) => (
                      <div key={module.id || index} className="p-6">
                        <button
                          onClick={() => setExpandedModule(expandedModule === index ? null : index)}
                          className="w-full flex items-center justify-between"
                        >
                          <div className="flex items-center gap-4">
                            <div className={`
                              w-10 h-10 rounded-full flex items-center justify-center font-bold
                              ${isEnrolled 
                                ? 'bg-blue-100 text-blue-700' 
                                : 'bg-gray-100 text-gray-500'
                              }
                            `}>
                              {index + 1}
                            </div>
                            <div className="text-left">
                              <h3 className="font-semibold text-gray-900">
                                {module.title || `Module ${index + 1}`}
                              </h3>
                              {module.duration && (
                                <p className="text-sm text-gray-500 mt-1 flex items-center gap-1">
                                  <ClockIcon className="w-4 h-4" />
                                  {module.duration}
                                </p>
                              )}
                            </div>
                          </div>
                          
                          {isEnrolled ? (
                            <PlayIcon className="w-5 h-5 text-blue-600" />
                          ) : (
                            <LockClosedIcon className="w-5 h-5 text-gray-400" />
                          )}
                        </button>

                        <AnimatePresence>
                          {expandedModule === index && module.description && (
                            <motion.div
                              initial={{ opacity: 0, height: 0 }}
                              animate={{ opacity: 1, height: 'auto' }}
                              exit={{ opacity: 0, height: 0 }}
                              className="mt-4 pl-14"
                            >
                              <div className="bg-gray-50 p-4 rounded-lg text-gray-600">
                                {module.description}
                              </div>
                            </motion.div>
                          )}
                        </AnimatePresence>
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {/* Outcomes Tab */}
              {activeTab === 'outcomes' && (
                <div className="bg-white rounded-xl border border-gray-200 p-8">
                  <h2 className="text-2xl font-bold text-gray-900 mb-6">What You'll Learn</h2>
                  <div className="space-y-4">
                    {course.learning_outcomes ? (
                      <div className="prose max-w-none" 
                        dangerouslySetInnerHTML={{ __html: course.learning_outcomes }} 
                      />
                    ) : (
                      <>
                        <div className="flex items-start gap-3 p-4 bg-blue-50 rounded-lg">
                          <CheckCircleSolid className="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" />
                          <span className="text-gray-700">Master essential skills and techniques</span>
                        </div>
                        <div className="flex items-start gap-3 p-4 bg-blue-50 rounded-lg">
                          <CheckCircleSolid className="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" />
                          <span className="text-gray-700">Apply knowledge to real-world scenarios</span>
                        </div>
                        <div className="flex items-start gap-3 p-4 bg-blue-50 rounded-lg">
                          <CheckCircleSolid className="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" />
                          <span className="text-gray-700">Develop critical thinking abilities</span>
                        </div>
                      </>
                    )}
                  </div>
                </div>
              )}

              {/* Audience Tab */}
              {activeTab === 'audience' && course.target_audience && (
                <div className="bg-white rounded-xl border border-gray-200 p-8">
                  <h2 className="text-2xl font-bold text-gray-900 mb-6">Who Should Enroll</h2>
                  <div 
                    className="prose max-w-none"
                    dangerouslySetInnerHTML={{ __html: course.target_audience }}
                  />
                </div>
              )}

              {/* Certification Tab */}
              {activeTab === 'certification' && (
                <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
                  <div className="p-6 bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                    <div className="flex items-center gap-4">
                      <TrophyIcon className="w-12 h-12" />
                      <div>
                        <h2 className="text-2xl font-bold">Professional Certification</h2>
                        <p className="text-blue-100">Validate your expertise</p>
                      </div>
                    </div>
                  </div>
                  
                  <div className="p-8">
                    <div className="grid gap-6">
                      <div className="flex items-start gap-3">
                        <ShieldCheckIcon className="w-5 h-5 text-blue-600 mt-1 flex-shrink-0" />
                        <div>
                          <h3 className="font-semibold text-gray-900 mb-1">Industry Recognized</h3>
                          <p className="text-gray-600">Certificate valued by employers worldwide</p>
                        </div>
                      </div>
                      
                      <div className="flex items-start gap-3">
                        <GlobeAltIcon className="w-5 h-5 text-blue-600 mt-1 flex-shrink-0" />
                        <div>
                          <h3 className="font-semibold text-gray-900 mb-1">Shareable & Verifiable</h3>
                          <p className="text-gray-600">Add to LinkedIn, resume, or portfolio</p>
                        </div>
                      </div>
                      
                      <div className="flex items-start gap-3">
                        <AcademicCapIcon className="w-5 h-5 text-blue-600 mt-1 flex-shrink-0" />
                        <div>
                          <h3 className="font-semibold text-gray-900 mb-1">Lifetime Access</h3>
                          <p className="text-gray-600">Download and print your certificate anytime</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              )}
            </div>

            {/* Sidebar */}
            <div className="space-y-6">
              {/* Course Image in Sidebar (for desktop) */}
              {courseImageUrl && (
                <div className="hidden lg:block bg-white rounded-xl border border-gray-200 overflow-hidden">
                  <img 
                    src={courseImageUrl} 
                    alt={course.title}
                    className="w-full h-48 object-cover"
                  />
                </div>
              )}
              
              {/* Course Details Card */}
              <div className="bg-white rounded-xl border border-gray-200 p-6">
                <h3 className="font-bold text-gray-900 mb-4">Course Details</h3>
                <dl className="space-y-3 text-sm">
                  <div className="flex justify-between">
                    <dt className="text-gray-500">Skill Level</dt>
                    <dd className="font-medium text-gray-900">{course.level || 'All Levels'}</dd>
                  </div>
                  <div className="flex justify-between">
                    <dt className="text-gray-500">Format</dt>
                    <dd className="font-medium text-gray-900">{'Online, Live, Hybrid'}</dd>
                  </div>
                  <div className="flex justify-between">
                    <dt className="text-gray-500">Duration</dt>
                    <dd className="font-medium text-gray-900">{course.duration || 'Flexible'}</dd>
                  </div>
                  <div className="flex justify-between">
                    <dt className="text-gray-500">Modules</dt>
                    <dd className="font-medium text-gray-900">{modules.length}</dd>
                  </div>
                  <div className="flex justify-between">
                    <dt className="text-gray-500">Access</dt>
                    <dd className="font-medium text-green-600">Lifetime</dd>
                  </div>
                </dl>
              </div>

              {/* Need Help Card */}
              <div className="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-xl p-6">
                <h3 className="font-bold text-gray-900 mb-2">Need Help?</h3>
                <p className="text-sm text-gray-600 mb-4">
                  Our admissions team is ready to assist you.
                </p>
                <button className="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                  Contact Admissions
                </button>
              </div>

            </div>
          </motion.div>
        </AnimatePresence>
      </div>

      {/* CTA Section */}
      {!isEnrolled && (
        <section className="bg-gradient-to-r from-blue-900 to-indigo-900 py-16 mt-12">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 className="text-3xl font-bold text-white mb-4">
              Ready to Advance Your Career?
            </h2>
            <p className="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
              Join thousands of professionals who have transformed their careers with our certification programmes.
            </p>
            
            {canUseScholarship ? (
              <button
                onClick={handleScholarshipEnroll}
                disabled={isEnrolling}
                className={`text-emerald-900 font-bold py-4 px-12 rounded-xl transition transform hover:-translate-y-0.5 text-lg flex items-center justify-center gap-2 mx-auto ${
                  isEnrolling 
                    ? 'bg-gray-300 cursor-not-allowed' 
                    : 'bg-emerald-400 hover:bg-emerald-500'
                }`}
              >
                <CheckCircleSolid className="w-6 h-6" />
                {isEnrolling ? 'Activating...' : 'Activate Scholarship'}
              </button>
            ) : (
              <button
                onClick={() => startEnrollment(course)}
                disabled={isEnrolling}
                className={`text-blue-900 font-bold py-4 px-12 rounded-xl transition transform hover:-translate-y-0.5 text-lg ${
                  isEnrolling 
                    ? 'bg-gray-300 cursor-not-allowed' 
                    : 'bg-white hover:bg-gray-100'
                }`}
              >
                {isEnrolling ? 'Enrolling...' : `Enroll Now - ${hasDiscount ? formatPrice(discountPrice) : formatPrice(price)}`}
              </button>
            )}
          </div>
        </section>
      )}
    </AuthenticatedLayout>
  );
}