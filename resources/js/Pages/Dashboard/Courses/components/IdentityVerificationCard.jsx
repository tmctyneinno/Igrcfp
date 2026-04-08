import React from 'react';
import { Link } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { CameraIcon, CheckBadgeIcon } from '@heroicons/react/24/outline';

export default function IdentityVerificationCard({ enrollment, isIdentityVerified }) {
    return (
        <motion.div
            initial={{ opacity: 0, x: 20 }}
            animate={{ opacity: 1, x: 0 }}
            className="bg-white rounded-xl shadow-sm p-6 border-2 border-indigo-100"
            id="identity-verification-section"
        >
            <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <CameraIcon className="w-5 h-5 text-indigo-600" />
                Identity Verification
            </h3>
            
            {isIdentityVerified ? (
                <div className="bg-green-50 rounded-lg p-4 flex items-center gap-3">
                    <CheckBadgeIcon className="w-8 h-8 text-green-600 flex-shrink-0" />
                    <div>
                        <p className="font-medium text-green-800">Identity Verified</p>
                        <p className="text-xs text-green-600">
                            Verified on {enrollment?.verified_at ? new Date(enrollment.verified_at).toLocaleDateString() : 'N/A'}
                        </p>
                    </div>
                </div>
            ) : (
                <div className="space-y-4">
                    <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p className="text-sm text-yellow-800">
                            <strong>Required for final exams and diploma assessments.</strong>
                        </p>
                    </div>
                    
                    <Link
                        href={`/identity/verify/${enrollment?.id}`}
                        className="w-full flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                    >
                        <CameraIcon className="w-5 h-5" />
                        Verify Identity Now
                    </Link>
                </div>
            )}
        </motion.div>
    );
}