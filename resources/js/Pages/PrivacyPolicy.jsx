 import React from 'react';
import { ShieldCheckIcon, LockClosedIcon, DocumentTextIcon, UserGroupIcon, DevicePhoneMobileIcon, ChartBarIcon } from '@heroicons/react/24/outline';

const PrivacyPolicy = () => {
  const sections = [
    {
      id: 'introduction',
      title: 'Introduction',
      icon: ShieldCheckIcon,
      content: (
        <>
          <p className="text-gray-700">
            <strong>Effective Date: 26/01/2026</strong>
          </p>
          <p className="mt-4 text-lg text-gray-800 italic">
            At IGRCFP, your privacy matters. We collect information because it helps us deliver education, 
            professional recognition, and a credible global GRC ecosystem. Not because we're interested in your personal life.
          </p>
        </>
      )
    },
    {
      id: 'who-we-are',
      title: 'Who We Are',
      icon: UserGroupIcon,
      content: (
        <p className="text-gray-700">
          The Institute of GRC and Financial Crime Prevention (IGRCFP) is a professional institute providing 
          education, training, research, certification, and events in governance, risk, compliance, and financial crime prevention.
        </p>
      )
    },
    {
      id: 'information-we-collect',
      title: 'Information We Collect',
      icon: DocumentTextIcon,
      content: (
        <div className="space-y-4">
          <p className="text-gray-700">
            We may collect the following information to serve you better:
          </p>
          <ul className="grid grid-cols-1 md:grid-cols-2 gap-3">
            {[
              'Personal details (name, email, job title, organisation)',
              'Membership and certification information',
              'Training and course enrolment data',
              'Payment and billing information',
              'Website usage data (cookies, analytics)',
              'Communication preferences'
            ].map((item, index) => (
              <li key={index} className="flex items-start">
                <div className="flex-shrink-0 mt-1">
                  <div className="w-2 h-2 bg-purple-500 rounded-full"></div>
                </div>
                <span className="ml-3 text-gray-700">{item}</span>
              </li>
            ))}
          </ul>
          <div className="mt-4 p-4 bg-blue-50 border border-blue-100 rounded-lg">
            <p className="text-blue-800 font-medium">
              <span className="font-bold">Important:</span> We only collect what is necessary. Nothing more.
            </p>
          </div>
        </div>
      )
    },
    {
      id: 'how-we-use',
      title: 'How We Use Your Information',
      icon: ChartBarIcon,
      content: (
        <div className="space-y-4">
          <p className="text-gray-700">
            Your data is used exclusively for professional purposes:
          </p>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            {[
              'Process memberships, applications, and certifications',
              'Deliver courses, events, and publications',
              'Communicate important updates and opportunities',
              'Maintain professional records and credentials',
              'Improve our services and website functionality',
              'Meet legal, regulatory, and accreditation obligations'
            ].map((use, index) => (
              <div key={index} className="flex items-start p-3 bg-gray-50 rounded-lg">
                <div className="flex-shrink-0 mt-0.5">
                  <svg className="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <span className="ml-3 text-gray-700">{use}</span>
              </div>
            ))}
          </div>
          <div className="mt-6 p-4 bg-green-50 border border-green-100 rounded-lg">
            <div className="flex items-center">
              <svg className="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
              <p className="text-green-800 font-semibold text-lg">
                We do not sell your data. <span className="text-green-600">Ever.</span>
              </p>
            </div>
          </div>
        </div>
      )
    },
    {
      id: 'legal-basis',
      title: 'Legal Basis for Processing',
      content: (
        <div className="space-y-3">
          <p className="text-gray-700">
            We process data based on the following legal grounds:
          </p>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
            {[
              { title: 'Your Consent', color: 'bg-blue-100 text-blue-800' },
              { title: 'Contractual Necessity', desc: '(e.g., membership or training enrolment)', color: 'bg-purple-100 text-purple-800' },
              { title: 'Legal Obligations', color: 'bg-amber-100 text-amber-800' },
              { title: 'Legitimate Interests', desc: 'aligned with IGRCFP\'s professional mission', color: 'bg-green-100 text-green-800' }
            ].map((basis, index) => (
              <div key={index} className={`p-4 rounded-lg ${basis.color}`}>
                <p className="font-semibold">{basis.title}</p>
                {basis.desc && <p className="text-sm mt-1 opacity-90">{basis.desc}</p>}
              </div>
            ))}
          </div>
        </div>
      )
    },
    {
      id: 'data-sharing',
      title: 'Data Sharing',
      content: (
        <div className="space-y-4">
          <p className="text-gray-700">
            We may share limited data with trusted partners for specific purposes:
          </p>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            {[
              'Accredited training partners',
              'Examination and certification bodies',
              'Event platforms and payment processors',
              'Legal or regulatory authorities where required by law'
            ].map((partner, index) => (
              <div key={index} className="flex items-center p-3 border border-gray-200 rounded-lg">
                <div className="flex-shrink-0 mr-3">
                  <div className="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg className="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                  </div>
                </div>
                <span className="text-gray-700">{partner}</span>
              </div>
            ))}
          </div>
          <div className="mt-4 p-4 bg-gray-50 rounded-lg">
            <p className="text-gray-700 font-medium">
              All partners are required to uphold appropriate data-protection standards.
            </p>
          </div>
        </div>
      )
    },
    {
      id: 'data-retention',
      title: 'Data Retention',
      content: (
        <div className="space-y-4">
          <p className="text-gray-700">
            We retain data only for as long as necessary for legitimate purposes:
          </p>
          <div className="space-y-3">
            {[
              {
                title: 'Membership and Certification Records',
                duration: 'Retained for audit and verification purposes',
                color: 'border-l-4 border-blue-500'
              },
              {
                title: 'Marketing Data',
                duration: 'Retained until you withdraw consent',
                color: 'border-l-4 border-purple-500'
              },
              {
                title: 'Financial Records',
                duration: 'Retained in line with statutory requirements',
                color: 'border-l-4 border-green-500'
              }
            ].map((item, index) => (
              <div key={index} className={`${item.color} pl-4 py-2`}>
                <p className="font-semibold text-gray-900">{item.title}</p>
                <p className="text-gray-600 text-sm">{item.duration}</p>
              </div>
            ))}
          </div>
        </div>
      )
    },
    {
      id: 'your-rights',
      title: 'Your Rights',
      content: (
        <div className="space-y-4">
          <p className="text-gray-700">
            Under data protection laws, you have the right to:
          </p>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            {[
              'Access your data',
              'Correct inaccurate information',
              'Request deletion (where legally possible)',
              'Restrict or object to processing',
              'Withdraw consent at any time',
              'Lodge a complaint with the relevant supervisory authority'
            ].map((right, index) => (
              <div key={index} className="flex items-start p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div className="flex-shrink-0 mt-0.5">
                  <div className="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center">
                    <span className="text-purple-600 font-bold text-sm">{index + 1}</span>
                  </div>
                </div>
                <span className="ml-3 text-gray-700">{right}</span>
              </div>
            ))}
          </div>
        </div>
      )
    },
    {
      id: 'data-security',
      title: 'Data Security',
      icon: LockClosedIcon,
      content: (
        <div className="space-y-4">
          <p className="text-gray-700">
            We apply appropriate technical and organisational measures to protect your data from loss, misuse, or unauthorised access.
          </p>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            {[
              { title: 'Encryption', icon: '🔒' },
              { title: 'Access Controls', icon: '👤' },
              { title: 'Regular Audits', icon: '📋' },
              { title: 'Staff Training', icon: '🎓' }
            ].map((measure, index) => (
              <div key={index} className="text-center p-4 border border-gray-200 rounded-lg">
                <div className="text-2xl mb-2">{measure.icon}</div>
                <p className="text-sm font-medium text-gray-700">{measure.title}</p>
              </div>
            ))}
          </div>
        </div>
      )
    },
    {
      id: 'international-transfers',
      title: 'International Transfers',
      content: (
        <div className="space-y-3">
          <p className="text-gray-700">
            Where data is transferred outside the UK or EEA, we ensure appropriate safeguards are in place.
          </p>
          <div className="flex items-center p-4 bg-blue-50 rounded-lg">
            <svg className="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4 4 0 003 15z" />
            </svg>
            <p className="text-blue-800">
              We comply with international data transfer regulations including Standard Contractual Clauses (SCCs) where applicable.
            </p>
          </div>
        </div>
      )
    },
    {
      id: 'updates',
      title: 'Updates to This Policy',
      content: (
        <div className="space-y-4">
          <p className="text-gray-700">
            We may update this policy occasionally to reflect changes in our practices or legal requirements.
          </p>
          <div className="p-4 bg-amber-50 border border-amber-100 rounded-lg">
            <div className="flex items-start">
              <svg className="w-6 h-6 text-amber-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div>
                <p className="text-amber-800 font-semibold">Important Notice</p>
                <p className="text-amber-700 mt-1">
                  The latest version will always be available on our website. We encourage you to review this policy periodically.
                </p>
              </div>
            </div>
          </div>
        </div>
      )
    }
  ];

  return (
    <div className="min-h-screen bg-gray-50 py-12">
      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="text-center mb-12">
          <div className="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-4">
            <ShieldCheckIcon className="h-8 w-8 text-purple-600" />
          </div>
          <h1 className="text-4xl font-bold text-gray-900 mb-4">Privacy Policy</h1>
          <p className="text-lg text-gray-600 max-w-3xl mx-auto">
            Protecting your privacy while delivering exceptional GRC education and certification
          </p>
          <div className="mt-6 inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-full text-sm font-medium">
            <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Effective Date: 26/01/2026
          </div>
        </div>

        {/* Quick Navigation */}
        <div className="mb-10">
          <div className="bg-white rounded-xl shadow-sm p-6">
            <h2 className="text-lg font-semibold text-gray-900 mb-4">Quick Navigation</h2>
            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
              {sections.map((section) => (
                <a
                  key={section.id}
                  href={`#${section.id}`}
                  className="block p-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 rounded-lg transition-colors"
                >
                  {section.title}
                </a>
              ))}
            </div>
          </div>
        </div>

        {/* Main Content */}
        <div className="space-y-8">
          {sections.map((section, index) => {
            const Icon = section.icon;
            return (
              <section
                key={section.id}
                id={section.id}
                className="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200"
              >
                <div className="p-6">
                  <div className="flex items-start mb-6">
                    {Icon && (
                      <div className="flex-shrink-0 mr-4">
                        <div className="w-12 h-12 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg flex items-center justify-center">
                          <Icon className="h-6 w-6 text-purple-600" />
                        </div>
                      </div>
                    )}
                    <div>
                      <div className="flex items-center">
                        <span className="text-sm font-medium text-purple-600 bg-purple-50 px-3 py-1 rounded-full">
                          Section {index + 1}
                        </span>
                      </div>
                      <h2 className="text-2xl font-bold text-gray-900 mt-2">{section.title}</h2>
                    </div>
                  </div>
                  <div className="prose prose-lg max-w-none">
                    {section.content}
                  </div>
                </div>
              </section>
            );
          })}
        </div>

        {/* Footer Note */}
        <div className="mt-12 text-center">
          <div className="bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl p-8">
            <h3 className="text-xl font-semibold text-gray-900 mb-3">Questions About Our Privacy Policy?</h3>
            <p className="text-gray-600 mb-6 max-w-2xl mx-auto">
              If you have any questions or concerns about how we handle your data, please contact our Data Protection Officer.
            </p>
            <a
              href="mailto:privacy@igrcfp.org"
              className="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors"
            >
              <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              Contact Privacy Team
            </a>
          </div>
          
          <p className="mt-8 text-sm text-gray-500">
            © {new Date().getFullYear()} Institute of GRC and Financial Crime Prevention (IGRCFP). All rights reserved.
          </p>
        </div>
      </div>
    </div>
  );
};

export default PrivacyPolicy;