import React from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head } from '@inertiajs/react';
import { 
  ShieldCheckIcon, 
  DocumentTextIcon, 
  LockClosedIcon,
  UserGroupIcon,
  CogIcon,
  EyeIcon,
  GlobeAltIcon,
  ClockIcon,
  CheckCircleIcon
} from '@heroicons/react/24/outline';

export default function PrivacyPolicy({ auth }) {
  const sections = [
    {
      id: 'who-we-are',
      title: '1. Who We Are',
      icon: UserGroupIcon,
      content: 'The Institute of GRC and Financial Crime Prevention (IGRCFP) is a professional institute providing education, training, research, certification, and events in governance, risk, compliance, and financial crime prevention.'
    },
    {
      id: 'information-collected',
      title: '2. Information We Collect',
      icon: DocumentTextIcon,
      content: 'We may collect:',
      items: [
        'Personal details (name, email, job title, organisation)',
        'Membership and certification information',
        'Training and course enrolment data',
        'Payment and billing information',
        'Website usage data (cookies, analytics)',
        'Communication preferences'
      ],
      note: 'We only collect what is necessary. Nothing more.'
    },
    {
      id: 'how-we-use',
      title: '3. How We Use Your Information',
      icon: CogIcon,
      content: 'Your data is used to:',
      items: [
        'Process memberships, applications, and certifications',
        'Deliver courses, events, and publications',
        'Communicate important updates and opportunities',
        'Maintain professional records and credentials',
        'Improve our services and website functionality',
        'Meet legal, regulatory, and accreditation obligations'
      ],
      note: 'We do not sell your data. Ever.'
    },
    {
      id: 'legal-basis',
      title: '4. Legal Basis for Processing',
      icon: ShieldCheckIcon,
      items: [
        'Your consent',
        'Contractual necessity (e.g. membership or training enrolment)',
        'Legal obligations',
        'Legitimate interests aligned with IGRCFP\'s professional mission'
      ]
    },
    {
      id: 'data-sharing',
      title: '5. Data Sharing',
      icon: UserGroupIcon,
      content: 'We may share limited data with:',
      items: [
        'Accredited training partners',
        'Examination and certification bodies',
        'Event platforms and payment processors',
        'Legal or regulatory authorities where required by law'
      ],
      note: 'All partners are required to uphold appropriate data-protection standards.'
    },
    {
      id: 'data-retention',
      title: '6. Data Retention',
      icon: ClockIcon,
      content: 'We retain data only for as long as necessary:',
      items: [
        'Membership and certification records may be retained for audit and verification purposes',
        'Marketing data is retained until you withdraw consent',
        'Financial records are retained in line with statutory requirements'
      ]
    },
    {
      id: 'your-rights',
      title: '7. Your Rights',
      icon: EyeIcon,
      content: 'You have the right to:',
      items: [
        'Access your data',
        'Correct inaccurate information',
        'Request deletion (where legally possible)',
        'Restrict or object to processing',
        'Withdraw consent at any time',
        'Lodge a complaint with the relevant supervisory authority'
      ]
    },
    {
      id: 'data-security',
      title: '8. Data Security',
      icon: LockClosedIcon,
      content: 'We apply appropriate technical and organisational measures to protect your data from loss, misuse, or unauthorised access.'
    },
    {
      id: 'international-transfers',
      title: '9. International Transfers',
      icon: GlobeAltIcon,
      content: 'Where data is transferred outside the UK or EEA, we ensure appropriate safeguards are in place.'
    },
    {
      id: 'policy-updates',
      title: '10. Updates to This Policy',
      icon: DocumentTextIcon,
      content: 'We may update this policy occasionally. The latest version will always be available on our website.'
    }
  ];

  return (
    <GuestLayout auth={auth}>
      <Head title="Privacy Policy | IGRCFP" />

      {/* Hero Section */}
      <section className="w-full bg-gradient-to-r from-blue-50 via-white to-blue-50 py-16 md:py-28 border-b">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center max-w-4xl mx-auto">
            
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
      
      
      {/* Main Content */}
      <div className="py-16 bg-gray-50">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="bg-white rounded-2xl shadow-xl overflow-hidden">
            {/* Table of Contents */}
            <div className="bg-gradient-to-r from-blue-500 to-purple-600 px-8 py-6">
              <h2 className="text-white text-xl font-bold mb-4">Table of Contents</h2>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-2">
                {sections.map((section, index) => (
                  <a
                    key={section.id}
                    href={`#${section.id}`}
                    className="flex items-center text-white hover:text-blue-100 group"
                  >
                    <span className="text-sm bg-white/20 px-2 py-1 rounded mr-3 group-hover:bg-white/30">
                      {index + 1}
                    </span>
                    <span className="text-sm">{section.title.replace(/^\d+\.\s*/, '')}</span>
                  </a>
                ))}
              </div>
            </div>

            {/* Policy Sections */}
            <div className="p-6 md:p-12">
              <div className="space-y-12">
                {sections.map((section, index) => {
                  const Icon = section.icon;
                  return (
                    <div key={section.id} id={section.id} className="scroll-mt-24">
                      <div className="flex items-start mb-4">
                        <div className="flex-shrink-0">
                          <div className="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-50 rounded-xl flex items-center justify-center">
                            <Icon className="h-6 w-6 text-blue-600" />
                          </div>
                        </div>
                        <div className="ml-4 flex-1">
                          <h3 className="text-xl font-bold text-gray-900">{section.title}</h3>
                          
                          {section.content && (
                            <p className="mt-3 text-gray-700 leading-relaxed">{section.content}</p>
                          )}
                          
                          {section.items && (
                            <ul className="mt-4 space-y-2">
                              {section.items.map((item, itemIndex) => (
                                <li key={itemIndex} className="flex items-start">
                                  <CheckCircleIcon className="h-5 w-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" />
                                  <span className="text-gray-700">{item}</span>
                                </li>
                              ))}
                            </ul>
                          )}
                          
                          {section.note && (
                            <div className="mt-6 p-4 bg-blue-50 border border-blue-100 rounded-lg">
                              <p className="text-blue-800 font-medium">{section.note}</p>
                            </div>
                          )}
                        </div>
                      </div>
                      
                      {index < sections.length - 1 && (
                        <div className="mt-12 pt-12 border-t border-gray-100"></div>
                      )}
                    </div>
                  );
                })}
              </div>
              
              {/* Contact Information */}
              <div className="mt-16 p-6 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border border-gray-200">
                <h4 className="text-lg font-semibold text-gray-900 mb-4">Questions About Our Privacy Policy?</h4>
                <p className="text-gray-700 mb-4">
                  If you have any questions about how we handle your data or about this privacy policy, 
                  please contact our Data Protection Officer.
                </p>
                <div className="grid md:grid-cols-2 gap-6">
                  <div className="p-4 bg-white rounded-lg border border-gray-200">
                    <h5 className="font-medium text-gray-900 mb-2">Email</h5>
                    <a 
                      href="mailto:privacy@igrcfp.org" 
                      className="text-blue-600 hover:text-blue-700 font-medium"
                    >
                      privacy@igrcfp.org
                    </a>
                  </div>
                  <div className="p-4 bg-white rounded-lg border border-gray-200">
                    <h5 className="font-medium text-gray-900 mb-2">Postal Address</h5>
                    <address className="not-italic text-gray-700">
                      Data Protection Officer<br/>
                      85 Great Portland Street<br/>
                      London W1W 7LT, UK
                    </address>
                  </div>
                </div>
              </div>
              
              {/* Last Updated */}
              <div className="mt-8 pt-8 border-t border-gray-200">
                <div className="flex items-center text-sm text-gray-600">
                  <ClockIcon className="h-4 w-4 mr-2" />
                  <span>Last reviewed and updated: 26 January 2026</span>
                </div>
                <p className="text-sm text-gray-600 mt-2">
                  This document will be reviewed annually or as required by changes in legislation.
                </p>
              </div>
            </div>
          </div>
          
          {/* Back to Top */}
          <div className="mt-8 text-center">
            <a
              href="#"
              className="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium"
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
    </GuestLayout>
  );
}