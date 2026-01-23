import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { 
  CheckCircleIcon, 
  ClockIcon, 
  BookOpenIcon, 
  AcademicCapIcon, 
  UsersIcon,
  ChevronRightIcon,
  PlayIcon,
  StarIcon,
  DocumentTextIcon,
  ArrowDownTrayIcon
} from '@heroicons/react/24/outline';

export default function CourseShow({ course }) {
  if (!course) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <div className="text-4xl mb-4">📚</div>
          <h2 className="text-2xl font-bold text-gray-900 mb-2">Course Not Found</h2>
          <p className="text-gray-600 mb-6">The course you're looking for doesn't exist.</p>
          <Link href="/courses" className="text-blue-600 hover:text-blue-800 font-semibold">
            ← Back to Courses
          </Link>
        </div>
      </div>
    );
  }

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

  // Parse HTML content safely
  const createMarkup = (html) => {
    if (!html) return { __html: '' };
    return { __html: html };
  };

  // Check if course has video
  const hasVideo = course.video_type && course.video_url;

  // Check if arrays exist
  const learningOutcomes = Array.isArray(course.learning_outcomes) ? course.learning_outcomes : [];
  const modules = Array.isArray(course.modules) ? course.modules : [];
  const materials = Array.isArray(course.materials) ? course.materials : [];

  return (
    <>
      {/* <Head>
        <title>{course.title} - Certification Course</title>
        <meta name="description" content={course.meta_description || course.short_description} />
        <meta name="keywords" content={course.meta_keywords} />
      </Head> */}

      {/* Hero Section */}
      <div className="bg-gradient-to-r from-blue-900 to-blue-700 text-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
          <div className="flex flex-col lg:flex-row gap-8 items-center">
            {/* Course Image */}
            <div className="lg:w-2/5">
              <div className="relative rounded-2xl overflow-hidden shadow-2xl">
                <img 
                  src={course.image_url || '/images/fallback-course.jpg'} 
                  alt={course.title}
                  className="w-full h-64 lg:h-80 object-cover"
                />
                {hasVideo && (
                  <div className="absolute inset-0 flex items-center justify-center">
                    <button 
                      onClick={() => window.open(course.video_embed_url || course.video_url, '_blank')}
                      className="bg-white/90 hover:bg-white p-4 rounded-full transition transform hover:scale-110"
                    >
                      <PlayIcon className="h-12 w-12 text-blue-600" />
                    </button>
                  </div>
                )}
              </div>
            </div>

            {/* Course Info */}
            <div className="lg:w-3/5">
              <div className="flex flex-wrap gap-2 mb-4">
                <span className="bg-white/20 px-3 py-1 rounded-full text-sm font-semibold">
                  {course.level || 'All Levels'}
                </span>
                <span className="bg-white/20 px-3 py-1 rounded-full text-sm font-semibold">
                  {course.format || 'Self-Paced'}
                </span>
                {course.is_featured && (
                  <span className="bg-yellow-500/20 px-3 py-1 rounded-full text-sm font-semibold flex items-center">
                    <StarIcon className="h-4 w-4 mr-1" /> Featured
                  </span>
                )}
                {course.is_popular && (
                  <span className="bg-red-500/20 px-3 py-1 rounded-full text-sm font-semibold">
                    🔥 Popular
                  </span>
                )}
              </div>

              <h1 className="text-3xl lg:text-4xl font-bold mb-4">{course.title}</h1>
              <p className="text-lg text-blue-100 mb-6">{course.short_description}</p>

              <div className="flex flex-wrap gap-6 mb-6">
                <div className="flex items-center">
                  <ClockIcon className="h-5 w-5 mr-2" />
                  <span>{course.duration || 'Flexible'}</span>
                </div>
                <div className="flex items-center">
                  <BookOpenIcon className="h-5 w-5 mr-2" />
                  <span>{(course.total_modules || modules.length || 0)} Modules</span>
                </div>
                <div className="flex items-center">
                  <AcademicCapIcon className="h-5 w-5 mr-2" />
                  <span>{course.certification_name || 'Certificate'}</span>
                </div>
              </div>

              {/* Pricing Section */}
              <div className="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                  <div>
                    <div className="flex items-center">
                      {hasDiscount ? (
                        <>
                          <span className="text-3xl font-bold">${discountPrice.toFixed(2)}</span>
                          <span className="text-lg line-through text-gray-300 ml-2">${price.toFixed(2)}</span>
                          <span className="ml-3 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                            Save {discountPercentage}%
                          </span>
                        </>
                      ) : (
                        <span className="text-3xl font-bold">{formatPrice(price)}</span>
                      )}
                    </div>
                    <p className="text-blue-100 text-sm mt-1">One-time payment • Lifetime access</p>
                  </div>

                  <div className="flex flex-col sm:flex-row gap-3">
                    <button className="bg-white text-blue-900 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition transform hover:-translate-y-1">
                      Enroll Now
                    </button>
                    <button className="bg-transparent border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white/10 transition">
                      Try Free Sample
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="flex flex-col lg:flex-row gap-8">
          {/* Left Column - Main Content */}
          <div className="lg:w-2/3">
            {/* Course Overview */}
            {course.programme_overview && (
              <section className="mb-12">
                <h2 className="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                  <BookOpenIcon className="h-6 w-6 mr-2 text-blue-600" />
                  Programme Overview
                </h2>
                <div 
                  className="prose max-w-none text-gray-700"
                  dangerouslySetInnerHTML={createMarkup(course.programme_overview)}
                />
              </section>
            )}

            {/* Learning Outcomes */}
            {learningOutcomes.length > 0 && (
              <section className="mb-12">
                <h2 className="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                  <CheckCircleIcon className="h-6 w-6 mr-2 text-green-600" />
                  What You'll Learn
                </h2>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  {learningOutcomes.map((outcome, index) => (
                    <div key={index} className="flex items-start p-4 bg-gray-50 rounded-lg">
                      <CheckCircleIcon className="h-5 w-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" />
                      <span className="text-gray-700">{outcome}</span>
                    </div>
                  ))}
                </div>
              </section>
            )}

            {/* Course Modules */}
            {modules.length > 0 && (
              <section className="mb-12">
                <h2 className="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                  <DocumentTextIcon className="h-6 w-6 mr-2 text-purple-600" />
                  Course Curriculum
                </h2>
                <div className="space-y-4">
                  {modules.map((module, index) => (
                    <motion.div
                      key={module.id || index}
                      initial={{ opacity: 0, y: 20 }}
                      animate={{ opacity: 1, y: 0 }}
                      transition={{ delay: index * 0.1 }}
                      className="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition"
                    >
                      <div className="p-6">
                        <div className="flex items-center justify-between mb-4">
                          <div className="flex items-center">
                            <div className="bg-blue-100 text-blue-800 font-bold rounded-full h-10 w-10 flex items-center justify-center mr-4">
                              {module.module_number || index + 1}
                            </div>
                            <h3 className="text-lg font-semibold text-gray-900">{module.title || `Module ${index + 1}`}</h3>
                          </div>
                          {module.duration && (
                            <span className="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                              {module.duration}
                            </span>
                          )}
                        </div>
                        <p className="text-gray-600">{module.description || 'No description available'}</p>
                      </div>
                    </motion.div>
                  ))}
                </div>
              </section>
            )}

            {/* Target Audience */}
            {course.target_audience && (
              <section className="mb-12">
                <h2 className="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                  <UsersIcon className="h-6 w-6 mr-2 text-orange-600" />
                  Who Should Enroll
                </h2>
                <div className="bg-orange-50 border border-orange-100 rounded-xl p-6">
                  <div 
                    className="prose max-w-none text-gray-700"
                    dangerouslySetInnerHTML={createMarkup(course.target_audience)}
                  />
                </div>
              </section>
            )}

            {/* Certification Details */}
            {course.certification_name && (
              <section className="mb-12">
                <h2 className="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                  <AcademicCapIcon className="h-6 w-6 mr-2 text-blue-600" />
                  Certification
                </h2>
                <div className="bg-blue-50 border border-blue-100 rounded-xl p-6">
                  <h3 className="text-xl font-bold text-blue-900 mb-2">{course.certification_name}</h3>
                  {course.certifying_body && (
                    <p className="text-gray-700 mb-4">Issued by: <strong>{course.certifying_body}</strong></p>
                  )}
                  <p className="text-gray-700">
                    Upon successful completion of this course, you will receive a digital certificate
                    that can be shared on LinkedIn and added to your professional portfolio.
                  </p>
                </div>
              </section>
            )}
          </div>

          {/* Right Column - Sidebar */}
          <div className="lg:w-1/3">
            {/* Quick Facts Card */}
            <div className="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-sm">
              <h3 className="text-lg font-bold text-gray-900 mb-4">Course Details</h3>
              <div className="space-y-4">
                <div className="flex justify-between items-center pb-3 border-b border-gray-100">
                  <span className="text-gray-600">Level</span>
                  <span className="font-semibold text-gray-900">{course.level || 'All Levels'}</span>
                </div>
                <div className="flex justify-between items-center pb-3 border-b border-gray-100">
                  <span className="text-gray-600">Format</span>
                  <span className="font-semibold text-gray-900">{course.format || 'Self-Paced'}</span>
                </div>
                <div className="flex justify-between items-center pb-3 border-b border-gray-100">
                  <span className="text-gray-600">Duration</span>
                  <span className="font-semibold text-gray-900">{course.duration || 'Flexible'}</span>
                </div>
                <div className="flex justify-between items-center pb-3 border-b border-gray-100">
                  <span className="text-gray-600">Modules</span>
                  <span className="font-semibold text-gray-900">{course.total_modules || modules.length || 'N/A'}</span>
                </div>
                <div className="flex justify-between items-center pb-3 border-b border-gray-100">
                  <span className="text-gray-600">Total Hours</span>
                  <span className="font-semibold text-gray-900">{course.total_hours || 'N/A'}</span>
                </div>
                <div className="flex justify-between items-center">
                  <span className="text-gray-600">Access</span>
                  <span className="font-semibold text-green-600">Lifetime</span>
                </div>
              </div>
            </div>

            {/* Download Materials */}
            {materials.length > 0 && (
              <div className="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-sm">
                <h3 className="text-lg font-bold text-gray-900 mb-4">Course Materials</h3>
                <div className="space-y-3">
                  {materials.map((material, index) => (
                    <a
                      key={material.id || index}
                      href={material.file_url || '#'}
                      download
                      className="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition"
                    >
                      <div className="flex items-center">
                        <DocumentTextIcon className="h-5 w-5 text-gray-500 mr-3" />
                        <div>
                          <p className="font-medium text-gray-900">{material.title || `Material ${index + 1}`}</p>
                          <p className="text-sm text-gray-500">{material.file_type || 'File'}</p>
                        </div>
                      </div>
                      <ArrowDownTrayIcon className="h-5 w-5 text-blue-600" />
                    </a>
                  ))}
                </div>
              </div>
            )}

            {/* Enroll Now Card */}
            <div className="bg-gradient-to-r from-blue-900 to-blue-700 text-white rounded-xl p-6 shadow-lg">
              <h3 className="text-xl font-bold mb-2">Start Learning Today</h3>
              <p className="text-blue-100 mb-6">Join thousands of professionals who have advanced their careers</p>
              
              <div className="space-y-4 mb-6">
                <div className="flex items-center">
                  <CheckCircleIcon className="h-5 w-5 text-green-300 mr-3" />
                  <span>Full lifetime access</span>
                </div>
                <div className="flex items-center">
                  <CheckCircleIcon className="h-5 w-5 text-green-300 mr-3" />
                  <span>Certificate of completion</span>
                </div>
                <div className="flex items-center">
                  <CheckCircleIcon className="h-5 w-5 text-green-300 mr-3" />
                  <span>Downloadable resources</span>
                </div>
              </div>

              <div className="text-center mb-4">
                {hasDiscount ? (
                  <div>
                    <div className="flex items-center justify-center mb-2">
                      <span className="text-3xl font-bold">${discountPrice.toFixed(2)}</span>
                      <span className="text-lg line-through text-gray-300 ml-2">${price.toFixed(2)}</span>
                    </div>
                    <p className="text-sm text-blue-200">Save {discountPercentage}% • Limited time offer</p>
                  </div>
                ) : (
                  <div className="text-3xl font-bold">{formatPrice(price)}</div>
                )}
              </div>

              <button className="w-full bg-white text-blue-900 font-bold py-4 px-6 rounded-lg hover:bg-gray-100 transition transform hover:-translate-y-1 mb-3">
                Enroll Now
              </button>
              
              <p className="text-center text-blue-200 text-sm">
                30-day money-back guarantee • No questions asked
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* FAQ/Contact Section */}
      <div className="bg-gray-50 border-t border-gray-200">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
          <div className="text-center mb-8">
            <h2 className="text-2xl font-bold text-gray-900 mb-2">Have Questions?</h2>
            <p className="text-gray-600">Get in touch with our admissions team</p>
          </div>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <button className="bg-blue-600 text-white font-semibold py-3 px-8 rounded-lg hover:bg-blue-700 transition">
              Contact Admissions
            </button>
            <Link 
              href="/courses" 
              className="bg-white text-blue-600 font-semibold py-3 px-8 rounded-lg border border-blue-600 hover:bg-blue-50 transition text-center"
            >
              ← Back to All Courses
            </Link>
          </div>
        </div>
      </div>
    </>
  );
}