import React from 'react';
import { Link } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { CheckBadgeIcon, QrCodeIcon, DocumentTextIcon, GlobeAltIcon } from '@heroicons/react/24/outline';

export default function CertificationCard({ 
    enrollment, 
    hasCertificate, 
    certificateNumber, 
    isIdentityVerified, 
    examResults, 
    progress 
}) {
    const certificateEligible = examResults?.certificate_eligible === true;
    const canDisplayCertificate = hasCertificate || certificateEligible;
    const certificateActionRoute = hasCertificate
        ? route('dashboard.certificates.download', enrollment?.id)
        : route('dashboard.certificates.generate', enrollment?.id);

    return (
        <motion.div
            initial={{ opacity: 0, x: 20 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ delay: 0.5 }}
            className="bg-white rounded-xl shadow-sm p-6 border-2 border-indigo-100"
        >
            <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <CheckBadgeIcon className="w-5 h-5 text-indigo-600" />
                Digital Certification
            </h3>

            {canDisplayCertificate ? (
                <div className="space-y-4">
                    <div className="bg-indigo-50 rounded-lg p-4">
                        <div className="flex items-center gap-3 mb-3">
                            <QrCodeIcon className="w-10 h-10 text-indigo-600" />
                            <div>
                                <p className="text-xs text-indigo-600">
                                    {hasCertificate ? 'Certificate Number' : 'Certificate Ready'}
                                </p>
                                <p className="font-mono font-bold text-indigo-900">
                                    {hasCertificate ? certificateNumber : 'Quiz and assessments passed'}
                                </p>
                            </div>
                        </div>
                        
                        <div className="flex gap-2">
                            <Link
                                href={certificateActionRoute}
                                className="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm"
                            >
                                <DocumentTextIcon className="w-4 h-4" />
                                {hasCertificate ? 'Download' : 'Generate'}
                            </Link>
                            {hasCertificate && (
                                <Link
                                    href={route('dashboard.certificates.preview', enrollment?.id)}
                                    className="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-white border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50 transition text-sm"
                                >
                                    <GlobeAltIcon className="w-4 h-4" />
                                    Preview
                                </Link>
                            )}
                        </div>
                    </div>
                </div>
            ) : (
                <div className="space-y-4">
                    <p className="text-gray-600 text-sm">
                        Complete all assessments to earn your digital certificate.
                    </p>
                    
                    <div className="bg-gray-50 rounded-lg p-4">
                        <h4 className="font-medium text-gray-900 mb-2">Requirements:</h4>
                        <ul className="text-sm text-gray-600 space-y-1">
                            <li className="flex items-center gap-2">
                                <span className={`w-4 h-4 rounded-full ${examResults?.quiz_passed ? 'bg-green-500' : 'bg-gray-300'}`} />
                                Pass course quiz
                            </li>
                            <li className="flex items-center gap-2">
                                <span className={`w-4 h-4 rounded-full ${examResults?.assessments_passed ? 'bg-green-500' : 'bg-gray-300'}`} />
                                Pass assessment
                            </li>
                            <li className="flex items-center gap-2">
                                <span className={`w-4 h-4 rounded-full ${certificateEligible ? 'bg-green-500' : 'bg-gray-300'}`} />
                                Certificate eligibility
                            </li>
                        </ul>
                    </div>
                </div>
            )}
        </motion.div>
    );
}
