import React from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head } from '@inertiajs/react';
import { 
  DocumentTextIcon,
  ShieldCheckIcon,
  AcademicCapIcon,
  ScaleIcon,
  ExclamationTriangleIcon,
  LinkIcon,
  ScaleIcon as LawIcon,
  ClockIcon,
  CheckCircleIcon,
  XCircleIcon,
  LockClosedIcon,
  GlobeAltIcon
} from '@heroicons/react/24/outline';

export default function TermsAndConditions({ auth }) {
  const sections = [
    {
      id: 'use-of-website',
      title: '1. Use of Website',
      icon: GlobeAltIcon,
      content: 'You agree to use the website lawfully and not to:',
      items: [
        'Misuse content or intellectual property',
        'Attempt unauthorised access to systems',
        'Distribute harmful or misleading material'
      ],
      warning: 'Violation may result in termination of access and legal action.'
    },
    {
      id: 'membership-certification',
      title: '2. Membership & Certification',
      icon: AcademicCapIcon,
      items: [
        'Membership is granted at IGRCFP\'s discretion',
        'Certification and credentials are subject to eligibility, assessment, and ongoing compliance'
      ],
      warning: {
        icon: XCircleIcon,
        content: 'IGRCFP reserves the right to suspend or revoke membership or certification for misconduct, misrepresentation, or breach of professional standards'
      }
    },
    {
      id: 'training-courses',
      title: '3. Training & Courses',
      icon: DocumentTextIcon,
      content: 'Course content is for personal professional development',
      items: [
        'Materials may not be reproduced or resold without written permission',
        'Fees are payable as stated and are generally non-refundable unless otherwise specified'
      ],
      note: 'All courses are subject to availability and minimum enrolment requirements.'
    },
    {
      id: 'intellectual-property',
      title: '4. Intellectual Property',
      icon: LockClosedIcon,
      content: 'All content, materials, frameworks, logos, and publications are the intellectual property of IGRCFP unless stated otherwise.',
      important: 'Unauthorized use may result in legal proceedings.'
    },
    {
      id: 'limitation-liability',
      title: '5. Limitation of Liability',
      icon: ScaleIcon,
      content: 'IGRCFP provides education and professional guidance.',
      disclaimer: {
        icon: ExclamationTriangleIcon,
        title: 'Important Disclaimer',
        content: 'We do not provide legal, regulatory, or financial advice. Use of information is at your own professional discretion.'
      }
    },
    {
      id: 'third-party-links',
      title: '6. Third-Party Links',
      icon: LinkIcon,
      content: 'Our website may include links to third-party sites. IGRCFP is not responsible for their content or practices.',
      note: 'We recommend reviewing third-party privacy policies and terms before engaging.'
    },
    {
      id: 'governing-law',
      title: '7. Governing Law',
      icon: LawIcon,
      content: 'These terms are governed by the laws of England and Wales.',
      jurisdiction: {
        title: 'Jurisdiction',
        content: 'Any disputes shall be subject to the exclusive jurisdiction of the courts of England and Wales.'
      }
    },
    {
      id: 'changes',
      title: '8. Changes',
      icon: ClockIcon,
      content: 'We may amend these terms from time to time.',
      note: 'Continued use indicates acceptance of the updated terms.',
      review: 'Members and users are responsible for reviewing terms periodically.'
    }
  ];

  return (
    <GuestLayout auth={auth}>
      <Head title="Terms & Conditions | IGRCFP" />

      <section className="w-full bg-gradient-to-r from-blue-50 via-white to-blue-50 py-16 md:py-28 border-b">
              <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="text-center max-w-4xl mx-auto">
                   <div className="inline-flex items-center justify-center p-3 bg-blue-100 rounded-xl mb-6">
                    <ShieldCheckIcon className="h-8 w-8 text-blue-600" />
                  </div>
                  <h1 className="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                    Privacy Policy
                  </h1>
                  <div className="inline-flex items-center bg-white px-4 py-2 rounded-full shadow-sm mb-6">
                    <ClockIcon className="h-4 w-4 text-gray-500 mr-2" />
                    <span className="text-sm font-medium text-gray-700">Effective Date: 26/01/2026</span>
                  </div>
                  <p className="text-xl text-gray-600 max-w-3xl mx-auto mb-10 leading-relaxed">
                      At IGRCFP, your privacy matters. We collect information because it helps us deliver education, 
                    professional recognition, and a credible global GRC ecosystem. Not because we're interested in your personal life.
                  
                  </p>
                </div>
              </div>
            </section>
      
      {/* Hero Section */}
      <div className="relative bg-gradient-to-br from-gray-900 to-blue-900 text-white py-20">
        <div className="absolute inset-0 bg-[url('/images/pattern-grid.svg')] opacity-10"></div>
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center">
            <div className="inline-flex items-center justify-center p-4 bg-white/10 backdrop-blur-sm rounded-2xl mb-8">
              <ScaleIcon className="h-12 w-12 text-white" />
            </div>
            <h1 className="text-4xl md:text-5xl font-bold mb-6">Terms & Conditions</h1>
            
            <div className="max-w-3xl mx-auto mb-10">
              <div className="inline-flex items-center bg-white/10 px-5 py-3 rounded-full backdrop-blur-sm mb-6">
                <ClockIcon className="h-5 w-5 mr-3" />
                <span className="font-medium">Effective Date: 26/01/2026</span>
              </div>
              
              <p className="text-xl text-gray-200 leading-relaxed">
                By accessing the IGRCFP website, enrolling in programmes, or becoming a member, 
                you agree to the following terms and conditions.
              </p>
              
              <div className="mt-8 flex flex-wrap justify-center gap-3">
                <div className="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-lg">
                  <span className="text-sm font-medium">Members & Students</span>
                </div>
                <div className="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-lg">
                  <span className="text-sm font-medium">Website Users</span>
                </div>
                <div className="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-lg">
                  <span className="text-sm font-medium">Partners & Affiliates</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <div className="py-16 bg-gray-50">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="bg-white rounded-2xl shadow-xl overflow-hidden">
            {/* Quick Summary */}
            <div className="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6">
              <div className="flex items-center justify-between">
                <div>
                  <h2 className="text-white text-xl font-bold mb-2">Summary of Key Terms</h2>
                  <p className="text-blue-100 text-sm">Important points to note</p>
                </div>
                <ShieldCheckIcon className="h-8 w-8 text-white opacity-80" />
              </div>
              <div className="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div className="bg-white/10 p-4 rounded-lg backdrop-blur-sm">
                  <p className="text-white font-medium mb-2">Membership Discretion</p>
                  <p className="text-blue-100 text-sm">Granted at IGRCFP's discretion</p>
                </div>
                <div className="bg-white/10 p-4 rounded-lg backdrop-blur-sm">
                  <p className="text-white font-medium mb-2">IP Protection</p>
                  <p className="text-blue-100 text-sm">All content is IGRCFP property</p>
                </div>
                <div className="bg-white/10 p-4 rounded-lg backdrop-blur-sm">
                  <p className="text-white font-medium mb-2">Liability Limitation</p>
                  <p className="text-blue-100 text-sm">Educational guidance only</p>
                </div>
              </div>
            </div>

            {/* Table of Contents */}
            <div className="border-b border-gray-200">
              <div className="px-8 py-6">
                <h3 className="text-lg font-semibold text-gray-900 mb-4">Table of Contents</h3>
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                  {sections.map((section, index) => (
                    <a
                      key={section.id}
                      href={`#${section.id}`}
                      className="group flex items-start p-3 bg-gray-50 hover:bg-blue-50 rounded-lg transition-colors"
                    >
                      <span className="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-700 rounded-md flex items-center justify-center font-bold text-sm mr-3">
                        {index + 1}
                      </span>
                      <span className="text-sm font-medium text-gray-700 group-hover:text-blue-700">
                        {section.title.replace(/^\d+\.\s*/, '')}
                      </span>
                    </a>
                  ))}
                </div>
              </div>
            </div>

            {/* Detailed Terms */}
            <div className="p-8 md:p-12">
              <div className="space-y-16">
                {sections.map((section, index) => {
                  const Icon = section.icon;
                  return (
                    <div key={section.id} id={section.id} className="scroll-mt-24">
                      <div className="flex items-start">
                        <div className="flex-shrink-0">
                          <div className="w-14 h-14 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl flex items-center justify-center shadow-sm">
                            <Icon className="h-7 w-7 text-blue-700" />
                          </div>
                        </div>
                        
                        <div className="ml-6 flex-1">
                          <h3 className="text-2xl font-bold text-gray-900 mb-4">
                            {section.title}
                          </h3>
                          
                          {section.content && (
                            <p className="text-gray-700 text-lg leading-relaxed mb-6">{section.content}</p>
                          )}
                          
                          {section.items && (
                            <ul className="space-y-3 mb-6">
                              {section.items.map((item, itemIndex) => (
                                <li key={itemIndex} className="flex items-start">
                                  <div className="flex-shrink-0 mt-1">
                                    <div className="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                      <CheckCircleIcon className="h-4 w-4 text-blue-600" />
                                    </div>
                                  </div>
                                  <span className="ml-3 text-gray-700">{item}</span>
                                </li>
                              ))}
                            </ul>
                          )}
                          
                          {/* Warning Box */}
                          {section.warning && typeof section.warning === 'object' ? (
                            <div className="mt-6 p-5 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                              <div className="flex items-start">
                                <section.warning.icon className="h-6 w-6 text-red-600 mt-0.5 mr-3 flex-shrink-0" />
                                <div>
                                  <p className="font-semibold text-red-800">{section.warning.content}</p>
                                </div>
                              </div>
                            </div>
                          ) : section.warning && (
                            <div className="mt-6 p-5 bg-yellow-50 border-l-4 border-yellow-500 rounded-r-lg">
                              <div className="flex items-start">
                                <ExclamationTriangleIcon className="h-6 w-6 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" />
                                <p className="font-semibold text-yellow-800">{section.warning}</p>
                              </div>
                            </div>
                          )}
                          
                          {/* Disclaimer Box */}
                          {section.disclaimer && (
                            <div className="mt-6 p-5 bg-orange-50 border border-orange-200 rounded-xl">
                              <div className="flex items-start">
                                <section.disclaimer.icon className="h-6 w-6 text-orange-600 mt-0.5 mr-3 flex-shrink-0" />
                                <div>
                                  <h4 className="font-bold text-orange-800 mb-2">{section.disclaimer.title}</h4>
                                  <p className="text-orange-700">{section.disclaimer.content}</p>
                                </div>
                              </div>
                            </div>
                          )}
                          
                          {/* Important Box */}
                          {section.important && (
                            <div className="mt-6 p-5 bg-blue-50 border border-blue-200 rounded-xl">
                              <div className="flex items-center">
                                <LockClosedIcon className="h-5 w-5 text-blue-600 mr-3" />
                                <p className="font-semibold text-blue-800">{section.important}</p>
                              </div>
                            </div>
                          )}
                          
                          {/* Jurisdiction Box */}
                          {section.jurisdiction && (
                            <div className="mt-6 p-5 bg-gray-50 border border-gray-200 rounded-xl">
                              <h4 className="font-bold text-gray-900 mb-2">{section.jurisdiction.title}</h4>
                              <p className="text-gray-700">{section.jurisdiction.content}</p>
                            </div>
                          )}
                          
                          {/* Note */}
                          {section.note && (
                            <div className="mt-6">
                              <p className="text-gray-600 italic">{section.note}</p>
                            </div>
                          )}
                          
                          {/* Review Note */}
                          {section.review && (
                            <div className="mt-4 p-4 bg-gray-50 rounded-lg">
                              <p className="text-gray-700 text-sm">{section.review}</p>
                            </div>
                          )}
                        </div>
                      </div>
                      
                      {/* Separator */}
                      {index < sections.length - 1 && (
                        <div className="mt-16 pt-16 border-t border-gray-100">
                          <div className="flex items-center justify-center">
                            <div className="h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent flex-1"></div>
                            <div className="px-4">
                              <div className="w-2 h-2 bg-blue-600 rounded-full"></div>
                            </div>
                            <div className="h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent flex-1"></div>
                          </div>
                        </div>
                      )}
                    </div>
                  );
                })}
              </div>
              
              {/* Acceptance Section */}
              <div className="mt-16 p-8 bg-gradient-to-r from-blue-50 to-gray-50 rounded-2xl border border-blue-200">
                <div className="text-center">
                  <ShieldCheckIcon className="h-12 w-12 text-blue-600 mx-auto mb-4" />
                  <h4 className="text-xl font-bold text-gray-900 mb-3">Acceptance of Terms</h4>
                  <p className="text-gray-700 max-w-2xl mx-auto">
                    By continuing to use our website, enrolling in programmes, or maintaining membership, 
                    you acknowledge that you have read, understood, and agreed to these Terms & Conditions.
                  </p>
                  <div className="mt-6 flex flex-col sm:flex-row gap-4 justify-center">
                    <button className="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                      I Understand & Accept
                    </button>
                    <button className="px-6 py-3 border border-gray-300 hover:border-gray-400 text-gray-700 font-medium rounded-lg transition-colors">
                      Download PDF Version
                    </button>
                  </div>
                </div>
              </div>
              
              {/* Contact for Clarification */}
              <div className="mt-12 p-6 bg-white border border-gray-200 rounded-xl">
                <h4 className="font-semibold text-gray-900 mb-3">Need Clarification?</h4>
                <p className="text-gray-700 mb-4">
                  If you have questions about these terms, please contact our legal team before proceeding.
                </p>
                <div className="flex flex-col sm:flex-row gap-4">
                  <a 
                    href="mailto:legal@igrcfp.org" 
                    className="text-blue-600 hover:text-blue-700 font-medium"
                  >
                    legal@igrcfp.org
                  </a>
                  <span className="text-gray-400 hidden sm:block">•</span>
                  <a 
                    href="tel:+442012345678" 
                    className="text-blue-600 hover:text-blue-700 font-medium"
                  >
                    +44 (0)20 1234 5678
                  </a>
                </div>
              </div>
              
              {/* Last Updated */}
              <div className="mt-12 pt-8 border-t border-gray-200">
                <div className="flex flex-col sm:flex-row sm:items-center justify-between">
                  <div>
                    <div className="flex items-center text-sm text-gray-600">
                      <ClockIcon className="h-4 w-4 mr-2" />
                      <span>Last Updated: 26 January 2026</span>
                    </div>
                    <p className="text-sm text-gray-600 mt-2">
                      Version 2.1 • Next review scheduled: January 2027
                    </p>
                  </div>
                  <a
                    href="#"
                    className="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium mt-4 sm:mt-0"
                    onClick={(e) => {
                      e.preventDefault();
                      window.scrollTo({ top: 0, behavior: 'smooth' });
                    }}
                  >
                    <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    Back to Top
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </GuestLayout>
  );
}