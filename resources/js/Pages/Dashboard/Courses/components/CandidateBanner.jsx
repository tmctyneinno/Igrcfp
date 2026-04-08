import React from 'react';
import { Link } from '@inertiajs/react';
import { motion } from 'framer-motion';
import toast from 'react-hot-toast';
import { IdentificationIcon, DocumentDuplicateIcon, ShieldCheckIcon } from '@heroicons/react/24/outline';

export default function CandidateBanner({ candidate, enrollment }) {
    const formatCandidateId = (id) => {
        if (!id) return 'IGRCFP-' + enrollment?.id?.toString().padStart(6, '0');
        return id;
    };

    const candidateId = formatCandidateId(candidate.certificate_id);

    return (
        <motion.div 
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            className="mb-6 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg overflow-hidden"
        >
            <div className="px-6 py-4 flex flex-wrap items-center justify-between">
                <div className="flex items-center gap-4">
                    <div className="bg-white/20 rounded-lg p-3">
                        <IdentificationIcon className="w-8 h-8 text-white" />
                    </div>
                    <div>
                        <p className="text-indigo-100 text-sm">Your Candidate ID</p>
                        <p className="text-2xl font-mono font-bold text-white tracking-wider">
                            {candidateId}
                        </p>
                    </div>
                </div>
                <div className="flex gap-3 mt-3 sm:mt-0">
                    <button 
                        onClick={() => {
                            navigator.clipboard.writeText(candidateId);
                            toast.success('Candidate ID copied to clipboard!');
                        }}
                        className="flex items-center gap-2 px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition"
                    >
                        <DocumentDuplicateIcon className="w-4 h-4" />
                        Copy ID
                    </button>
                    <Link
                        href={`/certificate/verify/${candidate.certificate_id}`}
                        className="flex items-center gap-2 px-4 py-2 bg-white text-indigo-600 rounded-lg hover:bg-gray-100 transition"
                    >
                        <ShieldCheckIcon className="w-4 h-4" />
                        Verify
                    </Link>
                </div>
            </div>
        </motion.div>
    );
}