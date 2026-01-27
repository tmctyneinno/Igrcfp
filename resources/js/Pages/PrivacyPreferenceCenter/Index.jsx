import React, { useState } from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head } from '@inertiajs/react';
import { 
  CogIcon,
  ShieldCheckIcon,
  EnvelopeIcon,
  BellIcon,
  AcademicCapIcon,
  CalendarIcon,
  DocumentTextIcon,
  UserGroupIcon,
  ChartBarIcon,
  TrashIcon,
  EyeIcon,
  CheckCircleIcon,
  XCircleIcon,
  ClockIcon,
  ArrowPathIcon
} from '@heroicons/react/24/outline';

export default function Index({ auth }) {
  const [preferences, setPreferences] = useState({
    membershipUpdates: true,
    trainingAnnouncements: true,
    eventsConferences: true,
    researchPublications: true,
    partnerOpportunities: false,
    surveysConsultations: false,
    marketingConsent: false
  });

  const handleToggle = (key) => {
    setPreferences(prev => ({
      ...prev,
      [key]: !prev[key]
    }));
  };

  const handleSave = () => {
    // In a real app, this would send to your backend
    console.log('Saving preferences:', preferences);
    alert('Preferences saved successfully!');
  };

  const handleReset = () => {
    setPreferences({
      membershipUpdates: true,
      trainingAnnouncements: true,
      eventsConferences: true,
      researchPublications: true,
      partnerOpportunities: false,
      surveysConsultations: false,
      marketingConsent: false
    });
  };

  const communicationOptions = [
    {
      key: 'membershipUpdates',
      label: 'Membership updates',
      description: 'Important membership news, renewals, and benefits',
      icon: UserGroupIcon,
      recommended: true
    },
    {
      key: 'trainingAnnouncements',
      label: 'Training & certification announcements',
      description: 'New courses, certifications, and training opportunities',
      icon: AcademicCapIcon,
      recommended: true
    },
    {
      key: 'eventsConferences',
      label: 'Events & conferences',
      description: 'Upcoming events, webinars, and conferences',
      icon: CalendarIcon,
      recommended: true
    },
    {
      key: 'researchPublications',
      label: 'Research, insights & publications',
      description: 'Latest research findings, whitepapers, and publications',
      icon: DocumentTextIcon,
      recommended: true
    },
    {
      key: 'partnerOpportunities',
      label: 'Partner opportunities',
      description: 'Collaboration and partnership opportunities',
      icon: ChartBarIcon,
      recommended: false
    },
    {
      key: 'surveysConsultations',
      label: 'Surveys and consultations',
      description: 'Participate in industry surveys and consultations',
      icon: BellIcon,
      recommended: false
    }
  ];

  return (
    <GuestLayout auth={auth}>
      <Head title="Privacy Preference Center | IGRCFP" />
      
      {/* Hero Section */}
      <div className="relative bg-gradient-to-br from-green-50 to-blue-50 py-16">
        <div className="absolute inset-0 bg-[url('/images/pattern-grid.svg')] opacity-10"></div>
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center">
            <div className="inline-flex items-center justify-center p-4 bg-gradient-to-br from-green-500 to-blue-500 rounded-2xl mb-8 shadow-lg">
              <ShieldCheckIcon className="h-12 w-12 text-white" />
            </div>
            <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
              Privacy Preference Center
            </h1>
            <p className="text-xl text-gray-700 max-w-3xl mx-auto leading-relaxed mb-8">
              Your data. Your choice. At IGRCFP, you are always in control of how we communicate with you.
            </p>
            <div className="inline-flex items-center px-6 py-3 bg-white rounded-full shadow-sm border border-gray-200">
              <CogIcon className="h-5 w-5 text-green-600 mr-3" />
              <span className="font-medium text-gray-700">Manage your preferences in real-time</span>
            </div>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <div className="py-16 bg-gray-50">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="bg-white rounded-2xl shadow-xl overflow-hidden">
            {/* Progress Bar */}
            <div className="px-8 py-6 border-b border-gray-200">
              <div className="flex items-center justify-between mb-2">
                <h3 className="text-lg font-semibold text-gray-900">Your Preferences</h3>
                <span className="text-sm font-medium text-green-600">
                  {Object.values(preferences).filter(Boolean).length} of 7 enabled
                </span>
              </div>
              <div className="w-full bg-gray-200 rounded-full h-2">
                <div 
                  className="bg-gradient-to-r from-green-500 to-blue-500 h-2 rounded-full transition-all duration-300"
                  style={{ 
                    width: `${(Object.values(preferences).filter(Boolean).length / 7) * 100}%` 
                  }}
                ></div>
              </div>
            </div>

            <div className="p-8 md:p-12">
              {/* Communication Preferences */}
              <div className="mb-16">
                <div className="flex items-center mb-8">
                  <EnvelopeIcon className="h-8 w-8 text-blue-600 mr-3" />
                  <div>
                    <h2 className="text-2xl font-bold text-gray-900">Communication Preferences</h2>
                    <p className="text-gray-600 mt-1">Choose what you'd like to receive from IGRCFP</p>
                  </div>
                </div>

                <div className="space-y-4">
                  {communicationOptions.map((option) => {
                    const Icon = option.icon;
                    return (
                      <div key={option.key} className="p-5 border border-gray-200 rounded-xl hover:border-blue-300 transition-colors">
                        <div className="flex items-start">
                          <div className="flex-shrink-0 mt-1">
                            <Icon className="h-6 w-6 text-blue-600" />
                          </div>
                          <div className="ml-4 flex-1">
                            <div className="flex items-center justify-between">
                              <div>
                                <h4 className="font-semibold text-gray-900">{option.label}</h4>
                                <p className="text-sm text-gray-600 mt-1">{option.description}</p>
                              </div>
                              <div className="flex items-center">
                                {option.recommended && (
                                  <span className="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-4">
                                    <CheckCircleIcon className="h-3 w-3 mr-1" />
                                    Recommended
                                  </span>
                                )}
                                <button
                                  onClick={() => handleToggle(option.key)}
                                  className={`relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 ${preferences[option.key] ? 'bg-blue-600' : 'bg-gray-200'}`}
                                >
                                  <span
                                    className={`pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out ${preferences[option.key] ? 'translate-x-5' : 'translate-x-0'}`}
                                  />
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    );
                  })}
                </div>
              </div>

              {/* Marketing Consent */}
              <div className="mb-16">
                <div className="flex items-center mb-6">
                  <BellIcon className="h-8 w-8 text-purple-600 mr-3" />
                  <div>
                    <h2 className="text-2xl font-bold text-gray-900">Marketing Consent</h2>
                    <p className="text-gray-600 mt-1">Control promotional communications</p>
                  </div>
                </div>

                <div className="p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                  <div className="flex items-start">
                    <div className="flex-1">
                      <div className="flex items-center justify-between mb-4">
                        <div>
                          <h4 className="font-semibold text-gray-900">Marketing Communications</h4>
                          <p className="text-sm text-gray-600 mt-1">
                            You may opt in or out of marketing communications at any time
                          </p>
                        </div>
                        <button
                          onClick={() => handleToggle('marketingConsent')}
                          className={`relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 ${preferences.marketingConsent ? 'bg-purple-600' : 'bg-gray-200'}`}
                        >
                          <span
                            className={`pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out ${preferences.marketingConsent ? 'translate-x-5' : 'translate-x-0'}`}
                          />
                        </button>
                      </div>
                      
                      <div className="p-4 bg-white rounded-lg border border-gray-200">
                        <div className="flex items-start">
                          <div className="flex-shrink-0">
                            <div className="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                              <EnvelopeIcon className="h-4 w-4 text-blue-600" />
                            </div>
                          </div>
                          <div className="ml-3">
                            <p className="text-sm text-gray-700">
                              <span className="font-medium">Note:</span> Transactional or essential service communications will still be sent regardless of your marketing preferences.
                            </p>
                            <p className="text-xs text-gray-500 mt-2">
                              This includes membership renewals, course confirmations, and important service updates.
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              {/* Data Requests */}
              <div>
                <div className="flex items-center mb-8">
                  <EyeIcon className="h-8 w-8 text-amber-600 mr-3" />
                  <div>
                    <h2 className="text-2xl font-bold text-gray-900">Data Requests</h2>
                    <p className="text-gray-600 mt-1">Exercise your data rights</p>
                  </div>
                </div>

                <div className="grid md:grid-cols-3 gap-6 mb-8">
                  <div className="p-6 bg-gradient-to-br from-blue-50 to-white rounded-xl border border-blue-200">
                    <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                      <EyeIcon className="h-6 w-6 text-blue-600" />
                    </div>
                    <h4 className="font-semibold text-gray-900 mb-2">View Your Data</h4>
                    <p className="text-sm text-gray-600 mb-4">
                      Request access to your stored personal information
                    </p>
                    <button className="text-sm font-medium text-blue-600 hover:text-blue-700">
                      Request Access →
                    </button>
                  </div>

                  <div className="p-6 bg-gradient-to-br from-green-50 to-white rounded-xl border border-green-200">
                    <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                      <ArrowPathIcon className="h-6 w-6 text-green-600" />
                    </div>
                    <h4 className="font-semibold text-gray-900 mb-2">Update Information</h4>
                    <p className="text-sm text-gray-600 mb-4">
                      Correct or update your personal details
                    </p>
                    <button className="text-sm font-medium text-green-600 hover:text-green-700">
                      Update Details →
                    </button>
                  </div>

                  <div className="p-6 bg-gradient-to-br from-red-50 to-white rounded-xl border border-red-200">
                    <div className="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                      <TrashIcon className="h-6 w-6 text-red-600" />
                    </div>
                    <h4 className="font-semibold text-gray-900 mb-2">Delete Account</h4>
                    <p className="text-sm text-gray-600 mb-4">
                      Request account deletion (subject to legal requirements)
                    </p>
                    <button className="text-sm font-medium text-red-600 hover:text-red-700">
                      Request Deletion →
                    </button>
                  </div>
                </div>

                <div className="p-6 bg-amber-50 border-l-4 border-amber-500 rounded-r-lg">
                  <div className="flex items-start">
                    <ClockIcon className="h-6 w-6 text-amber-600 mt-0.5 mr-3 flex-shrink-0" />
                    <div>
                      <h4 className="font-semibold text-amber-900 mb-2">How to Submit Requests</h4>
                      <p className="text-amber-800 mb-3">
                        All data requests can be made by emailing:
                      </p>
                      <a 
                        href="mailto:enquiries@igrcfp.org?subject=Data%20Request"
                        className="text-lg font-bold text-amber-700 hover:text-amber-800"
                      >
                        enquiries@igrcfp.org
                      </a>
                      <div className="mt-4 p-3 bg-amber-100 rounded-lg">
                        <p className="text-sm font-medium text-amber-800">
                          <ClockIcon className="h-4 w-4 inline mr-2" />
                          We aim to respond within 30 days
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              {/* Action Buttons */}
              <div className="mt-16 pt-8 border-t border-gray-200">
                <div className="flex flex-col sm:flex-row justify-between items-center gap-6">
                  <div>
                    <p className="text-gray-600">
                      Your preferences will be updated immediately upon saving.
                    </p>
                  </div>
                  <div className="flex gap-4">
                    <button
                      onClick={handleReset}
                      className="px-6 py-3 border border-gray-300 hover:border-gray-400 text-gray-700 font-medium rounded-lg transition-colors flex items-center"
                    >
                      <ArrowPathIcon className="h-5 w-5 mr-2" />
                      Reset to Defaults
                    </button>
                    <button
                      onClick={handleSave}
                      className="px-8 py-3 bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white font-bold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center"
                    >
                      <ShieldCheckIcon className="h-5 w-5 mr-2" />
                      Save Preferences
                    </button>
                  </div>
                </div>
              </div>

              {/* Information Footer */}
              <div className="mt-12 pt-8 border-t border-gray-200">
                <div className="grid md:grid-cols-2 gap-8">
                  <div>
                    <h4 className="font-semibold text-gray-900 mb-3">Your Privacy Rights</h4>
                    <ul className="space-y-2 text-sm text-gray-600">
                      <li className="flex items-start">
                        <CheckCircleIcon className="h-4 w-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" />
                        <span>Right to access your personal data</span>
                      </li>
                      <li className="flex items-start">
                        <CheckCircleIcon className="h-4 w-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" />
                        <span>Right to rectify inaccurate data</span>
                      </li>
                      <li className="flex items-start">
                        <CheckCircleIcon className="h-4 w-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" />
                        <span>Right to erasure (under certain conditions)</span>
                      </li>
                      <li className="flex items-start">
                        <CheckCircleIcon className="h-4 w-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" />
                        <span>Right to restrict processing</span>
                      </li>
                    </ul>
                  </div>
                  <div>
                    <h4 className="font-semibold text-gray-900 mb-3">Need Help?</h4>
                    <p className="text-sm text-gray-600 mb-4">
                      If you need assistance with your privacy preferences or have questions about your data rights:
                    </p>
                    <div className="space-y-2">
                      <a 
                        href="/privacy-policy" 
                        className="text-blue-600 hover:text-blue-700 text-sm font-medium block"
                      >
                        Read our full Privacy Policy →
                      </a>
                      <a 
                        href="/contact" 
                        className="text-blue-600 hover:text-blue-700 text-sm font-medium block"
                      >
                        Contact our Data Protection Officer →
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </GuestLayout>
  );
}