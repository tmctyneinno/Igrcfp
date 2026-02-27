import React from 'react';
import { Head, Link } from '@inertiajs/react';
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

                    {/* Hero Banner */}
           

                    {/* Course Header */}
                    <div className="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                        {/* Cover Image */}
                        <div className="h-80 w-full bg-gradient-to-r from-blue-950 to-indigo-950 relative">
                            {course.image_url || course.banner_image ? (
                                <img 
                                    src={course.image_url || course.banner_image}
                                    alt={course.title}
                                    className="w-full h-full object-cover opacity-50"
                                />
                            ) : (
                                <div className="absolute inset-0 bg-gradient-to-r from-blue-900 to-indigo-900"></div>
                            )}

                          
                            <div className="absolute bottom-0 left-0 p-2 text-white">
                                <div className="flex flex-col lg:flex-row items-start gap-8">
                                    <div className="lg:w-2/3">
                                        <h1 className="text-3xl font-bold mb-2">{course.title}</h1>
                                        <p className="text-xl text-gray-200 mb-8 max-w-3xl">{getCleanDescription(course.short_description)}</p>

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
                                    <div className="lg:w-1/3 w-full">
                                        <div className="bg-white rounded-xl shadow-2xl p-6 text-gray-900">
                                            <div className="flex items-center justify-between mb-4">
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
                                            <p className="text-sm text-green-600 mt-2">One-time payment • Lifetime access</p>
                                            </div>
                        
                                            <button 
                                            onClick={() => startEnrollment(course)}
                                            className="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 px-6 rounded-lg transition duration-200 mb-4"
                                            >
                                            {user ? 'Enroll Now' : 'Add to cart'}
                                            </button>
                            
                                        </div>
                                    </div>
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
                    {/* Main Content with Tabs */}
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
          {/* Tabs Navigation */}
          <div className="border-b border-gray-200 mb-8">
            <nav className="-mb-px flex space-x-8 overflow-x-auto">
              {tabs.map((tab) => {
                const Icon = tab.icon;
                return (
                  <button
                    key={tab.id}
                    onClick={() => setActiveTab(tab.id)}
                    className={`
                      py-4 px-1 border-b-2 font-medium text-sm flex items-center whitespace-nowrap
                      ${activeTab === tab.id
                        ? 'border-blue-900 text-blue-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                      }
                    `}
                  >
                    <Icon className={`h-5 w-5 mr-2 ${activeTab === tab.id ? 'text-blue-600' : 'text-gray-400'}`} />
                    {tab.label}
                  </button>
                );
              })}
            </nav>
          </div>

          {/* Tab Content */}
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Main Content Area */}
            <div className="lg:col-span-2">
              {/* Overview Tab */}
              {activeTab === 'overview' && (
                <motion.div
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ duration: 0.3 }}
                >
                  <div className="prose prose-lg max-w-none">
                    {course.full_description ? (
                      <div 
                        className="
                          prose prose-lg max-w-none
                          [&_h1]:text-3xl [&_h1]:font-bold [&_h1]:mb-4 [&_h1]:mt-8
                          [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:mb-3 [&_h2]:mt-6
                          [&_h3]:text-xl [&_h3]:font-bold [&_h3]:mb-2 [&_h3]:mt-4
                          [&_p]:mb-4 [&_p]:text-gray-700 [&_p]:leading-relaxed
                          [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:mb-4 [&_ul]:space-y-2
                          [&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:mb-4 [&_ol]:space-y-2
                          [&_li]:mb-1
                          [&_strong]:font-bold [&_strong]:text-gray-900
                          [&_b]:font-bold [&_b]:text-gray-900
                          [&_em]:italic
                          [&_i]:italic
                          [&_a]:text-blue-600 [&_a]:hover:text-blue-800 [&_a]:underline
                          [&_blockquote]:border-l-4 [&_blockquote]:border-blue-300 [&_blockquote]:pl-4 [&_blockquote]:italic [&_blockquote]:text-gray-600
                        "
                      >
                        <div dangerouslySetInnerHTML={{ __html: course.full_description }} />
                      </div>
                    ) : (
                      <div className="text-gray-600">
                        <p className="text-lg mb-6">This comprehensive certification programme is designed to provide you with the essential skills and knowledge needed to excel in your professional field.</p>
                        
                        <h3 className="text-xl font-bold text-gray-900 mb-4">Programme Highlights</h3>
                        <ul className="space-y-3">
                          <li className="flex items-start">
                            <CheckCircleIcon className="h-5 w-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" />
                            <span>Comprehensive curriculum developed by industry experts</span>
                          </li>
                          <li className="flex items-start">
                            <CheckCircleIcon className="h-5 w-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" />
                            <span>Practical, real-world case studies and applications</span>
                          </li>
                          <li className="flex items-start">
                            <CheckCircleIcon className="h-5 w-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" />
                            <span>Interactive learning with hands-on exercises</span>
                          </li>
                          <li className="flex items-start">
                            <CheckCircleIcon className="h-5 w-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" />
                            <span>Expert-led sessions and mentorship opportunities</span>
                          </li>
                        </ul>
                      </div>
                    )}
                  </div>
                </motion.div>
              )}

              {/* Curriculum Tab */}
              {activeTab === 'curriculum' && (
                <motion.div
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ duration: 0.3 }}
                >
                  <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div className="p-6 border-b border-gray-200">
                      <h3 className="text-xl font-bold text-gray-900 mb-2">Course Curriculum</h3>
                      <p className="text-gray-600">
                        {isEnrolled 
                          ? 'Access all modules and learning materials' 
                          : 'Unlock full access after enrollment'
                        }
                      </p>
                    </div>
                    
                    <div className="divide-y divide-gray-200">
                      {modules.length > 0 ? (
                        modules.map((module, index) => (
                          <div key={module.id || index} className="p-6 hover:bg-gray-50 transition">
                            <div className="flex items-center justify-between">
                              <div className="flex items-center">
                                <div className={`flex items-center justify-center w-10 h-10 rounded-full ${isEnrolled ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500'} font-bold mr-4`}>
                                  {module.module_number || index + 1}
                                </div>
                                <div>
                                  <h4 className="font-semibold text-gray-900">{module.title || `Module ${index + 1}`}</h4>
                                  {module.duration && (
                                    <p className="text-sm text-gray-500 mt-1">{module.duration}</p>
                                  )}
                                </div>
                              </div>
                              {isEnrolled ? (
                                <div className="flex items-center space-x-2">
                                  <button className="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                    Start
                                  </button>
                                  <PlayIcon className="h-5 w-5 text-blue-600" />
                                </div>
                              ) : (
                                <div className="flex items-center space-x-2">
                                  <LockClosedIcon className="h-5 w-5 text-gray-400" />
                                  <span className="text-sm text-gray-500">Locked</span>
                                </div>
                              )}
                            </div>
                            {module.description && (
                              <div className="mt-4 pl-14">
                                <div className="text-gray-600 bg-gray-50 p-4 rounded-lg">
                                  {module.description}
                                </div>
                              </div>
                            )}
                          </div>
                        ))
                      ) : (
                        <div className="p-6 text-center text-gray-500">
                          <p>Curriculum details will be available after enrollment</p>
                        </div>
                      )}
                    </div>

                    {/* Enrollment CTA at Bottom */}
                    {!isEnrolled && (
                      <div className="p-6 bg-blue-50 border-t border-blue-100">
                        <div className="flex flex-col sm:flex-row items-center justify-between gap-4">
                          <div>
                            <h4 className="font-bold text-gray-900 mb-1">Ready to start learning?</h4>
                            <p className="text-gray-600 text-sm">Enroll today to unlock all {modules.length} modules</p>
                          </div>
                          <button
                            onClick={() => startEnrollment(course)}
                            className="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition"
                          >
                            {user ? 'Enroll Now' : 'Sign In & Enroll'}
                          </button>
                        </div>
                      </div>
                    )}
                  </div>
                </motion.div>
              )}

              {/* Outcomes Tab */}
              {activeTab === 'outcomes' && (
                <motion.div
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ duration: 0.3 }}
                >
                  <div className="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 className="text-xl font-bold text-gray-900 mb-6">Learning Outcomes</h3>
                    
                    {learningOutcomes.length > 0 ? (
                      <div className="grid grid-cols-1 md:grid-cols-1 gap-4">
                        {learningOutcomes.map((outcome, index) => (
                          <div key={index} className="flex items-start p-4 bg-blue-50 rounded-lg">
                            <CheckCircleIcon className="h-5 w-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" />
                            <div 
                              className="
                                prose prose-lg max-w-none
                                [&_h1]:text-3xl [&_h1]:font-bold [&_h1]:mb-4 [&_h1]:mt-8
                                [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:mb-3 [&_h2]:mt-6
                                [&_h3]:text-xl [&_h3]:font-bold [&_h3]:mb-2 [&_h3]:mt-4
                                [&_p]:mb-4 [&_p]:text-gray-700 [&_p]:leading-relaxed
                                [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:mb-4 [&_ul]:space-y-2
                                [&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:mb-4 [&_ol]:space-y-2
                                [&_li]:mb-1
                                [&_strong]:font-bold [&_strong]:text-gray-900
                                [&_b]:font-bold [&_b]:text-gray-900
                                [&_em]:italic
                                [&_i]:italic
                                [&_a]:text-blue-600 [&_a]:hover:text-blue-800 [&_a]:underline
                                [&_blockquote]:border-l-4 [&_blockquote]:border-blue-300 [&_blockquote]:pl-4 [&_blockquote]:italic [&_blockquote]:text-gray-600
                              "
                            >
                              <div dangerouslySetInnerHTML={{ __html: outcome }} />
                            </div>
                          </div>
                        ))}
                      </div>
                    ) : (
                      <div className="space-y-4">
                        <div className="flex items-start p-4 bg-blue-50 rounded-lg">
                          <CheckCircleIcon className="h-5 w-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" />
                          <span className="text-gray-700">Master essential skills and techniques required for professional certification</span>
                        </div>
                        <div className="flex items-start p-4 bg-blue-50 rounded-lg">
                          <CheckCircleIcon className="h-5 w-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" />
                          <span className="text-gray-700">Apply theoretical knowledge to practical, real-world scenarios</span>
                        </div>
                        <div className="flex items-start p-4 bg-blue-50 rounded-lg">
                          <CheckCircleIcon className="h-5 w-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" />
                          <span className="text-gray-700">Develop critical thinking and problem-solving abilities</span>
                        </div>
                      </div>
                    )}
                  </div>
                </motion.div>
              )}

              {/* Audience Tab */}
              {activeTab === 'audience' && course.target_audience && (
                <motion.div
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ duration: 0.3 }}
                >
                  <div className="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 className="text-xl font-bold text-gray-900 mb-6">Who Should Enroll</h3>
                    
                    <div 
                      className="
                        prose prose-lg max-w-none
                        [&_h1]:text-3xl [&_h1]:font-bold [&_h1]:mb-4 [&_h1]:mt-8
                        [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:mb-3 [&_h2]:mt-6
                        [&_h3]:text-xl [&_h3]:font-bold [&_h3]:mb-2 [&_h3]:mt-4
                        [&_p]:mb-4 [&_p]:text-gray-700 [&_p]:leading-relaxed
                        [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:mb-4 [&_ul]:space-y-2
                        [&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:mb-4 [&_ol]:space-y-2
                        [&_li]:mb-1
                        [&_strong]:font-bold [&_strong]:text-gray-900
                        [&_b]:font-bold [&_b]:text-gray-900
                        [&_em]:italic
                        [&_i]:italic
                        [&_a]:text-blue-600 [&_a]:hover:text-blue-800 [&_a]:underline
                        [&_blockquote]:border-l-4 [&_blockquote]:border-blue-300 [&_blockquote]:pl-4 [&_blockquote]:italic [&_blockquote]:text-gray-600
                      "
                    >
                      <div dangerouslySetInnerHTML={{ __html: course.target_audience }} />
                    </div>
                  </div>
                </motion.div>
              )}

              {/* Certification Tab */}
              {activeTab === 'certification' && (
                <motion.div
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ duration: 0.3 }}
                >
                  <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div className="p-6 bg-blue-50 border-b border-blue-100">
                      <div className="flex items-center">
                        <AcademicCapIcon className="h-8 w-8 text-blue-600 mr-3" />
                        <div>
                          <h3 className="text-xl font-bold text-gray-900">Certification</h3>
                          <p className="text-gray-600">Official recognition of your achievement</p>
                        </div>
                      </div>
                    </div>
                    
                    <div className="p-6">
                      <div className="mb-6">
                        <h4 className="font-bold text-gray-900 mb-2">Certificate Details</h4>
                        <div className="space-y-3">
                          <div className="flex justify-between py-2 border-b border-gray-100">
                            <span className="text-gray-600">Certificate Name</span>
                            <span className="font-semibold">{course.certification_name || 'Professional Certification'}</span>
                          </div>
                          {course.certifying_body && (
                            <div className="flex justify-between py-2 border-b border-gray-100">
                              <span className="text-gray-600">Issuing Body</span>
                              <span className="font-semibold">{course.certifying_body}</span>
                            </div>
                          )}
                          <div className="flex justify-between py-2 border-b border-gray-100">
                            <span className="text-gray-600">Delivery</span>
                            <span className="font-semibold">Digital & Printable</span>
                          </div>
                          <div className="flex justify-between py-2">
                            <span className="text-gray-600">Verification</span>
                            <span className="font-semibold">Online Verification Available</span>
                          </div>
                        </div>
                      </div>
                      
                      <div className="bg-gray-50 p-4 rounded-lg">
                        <h5 className="font-bold text-gray-900 mb-2">Certificate Benefits</h5>
                        <ul className="space-y-2">
                          <li className="flex items-start">
                            <CheckCircleIcon className="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" />
                            <span>Enhance your professional credibility</span>
                          </li>
                          <li className="flex items-start">
                            <CheckCircleIcon className="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" />
                            <span>Share on LinkedIn and professional networks</span>
                          </li>
                          <li className="flex items-start">
                            <CheckCircleIcon className="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" />
                            <span>Add to your resume and portfolio</span>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </motion.div>
              )}
            </div>

            {/* Sidebar */}
            <div className="space-y-6">
              {/* Course Details Card */}
              <div className="bg-white rounded-xl border border-gray-200 p-6">
                <h3 className="text-lg font-bold text-gray-900 mb-4">Course Details</h3>
                <div className="space-y-4">
                  <div className="flex justify-between items-center">
                    <label className="text-sm text-gray-500">Skill Level</label>
                    <p className="font-medium">{course.level || 'All Levels'}</p>
                  </div>
                  <div className="flex justify-between items-center">
                    <label className="text-sm text-gray-500">Format</label>
                    <p className="font-medium">{course.format || 'Self-Paced Online'}</p>
                  </div>
                  <div className="flex justify-between items-center">
                    <label className="text-sm text-gray-500">Duration</label>
                    <p className="font-medium">{course.duration || 'Flexible Schedule'} hours</p>
                  </div>
                  <div className="flex justify-between items-center">
                    <label className="text-sm text-gray-500">Modules</label>
                    <p className="font-medium">{course.total_modules || modules.length || 0}</p>
                  </div>
                  <div className="flex justify-between items-center">
                    <label className="text-sm text-gray-500">Total Hours</label>
                    <p className="font-medium">{course.total_hours || 'Varies by pace'} hours</p>
                  </div>
                  <div className="flex justify-between items-center">
                    <label className="text-sm text-gray-500">Access Period</label>
                    <p className="font-medium text-green-600">Lifetime Access</p>
                  </div>
                </div>
              </div>

              {/* Need Help Card */}
              <div className="bg-blue-50 border border-blue-100 rounded-xl p-6">
                <h3 className="text-lg font-bold text-gray-900 mb-2">Need Assistance?</h3>
                <p className="text-gray-600 text-sm mb-4">
                  Our admissions team is here to help you with any questions about this programme.
                </p>
                <button className="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                  Contact Admissions
                </button>
              </div>

              {/* Share Card */}
              <div className="bg-white rounded-xl border border-gray-200 p-6">
                <h3 className="text-lg font-bold text-gray-900 mb-4">Share this Course</h3>
                <div className="flex space-x-3">
                  <button className="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-200">
                    LinkedIn
                  </button>
                  <button className="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-200">
                    Twitter
                  </button>
                </div>
              </div>
            </div>
          </div>

          {/* CTA Section */}
          <div className="mt-12 bg-gradient-to-r from-blue-900 to-blue-700 rounded-2xl p-8 text-white">
            <div className="max-w-3xl mx-auto text-center">
              <h2 className="text-2xl font-bold mb-4">Ready to Advance Your Career?</h2>
              <p className="text-blue-100 mb-6 max-w-2xl mx-auto">
                Join thousands of professionals who have transformed their careers with our certification programmes.
              </p>
              <div className="flex flex-col sm:flex-row gap-4 justify-center">
                <button 
                  onClick={() => startEnrollment(course)}
                  className="bg-white text-blue-900 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-200"
                >
                  Enroll Now - ${hasDiscount ? discountPrice.toFixed(2) : price.toFixed(2)}
                </button>
                <button className="bg-transparent border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white/10 transition duration-200">
                  Download Syllabus
                </button>
              </div>
            </div>
          </div>
        </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}