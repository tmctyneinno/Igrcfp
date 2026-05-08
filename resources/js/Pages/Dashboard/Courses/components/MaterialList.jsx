import React from 'react';
import { motion } from 'framer-motion';
import { ArrowDownTrayIcon, DocumentTextIcon } from '@heroicons/react/24/outline';

export default function MaterialList({ materials, title = 'Materials' }) {
    if (!materials || materials.length === 0) return null;

    return (
          <motion.div
            initial={{ opacity: 0, x: 20 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ delay: 0.5 }}
            className="bg-white rounded-xl shadow-sm p-6 border-2 border-indigo-100"
        >
        <div className="mb-6">
            <h4 className="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">
                {title}
            </h4> 
            <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                {materials.map((material) => (
                    <a
                        key={material.id}
                        href={material.download_url || material.file_url}
                        download
                        className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition group"
                    >
                        <DocumentTextIcon className="w-5 h-5 text-blue-600 flex-shrink-0" />
                        <span className="min-w-0 flex-1">
                            <span className="block text-sm text-gray-900 truncate">{material.title}</span>
                            {(material.file_type || material.file_size) && (
                                <span className="block text-xs text-gray-500">
                                    {[material.file_type, material.file_size].filter(Boolean).join(' • ')}
                                </span>
                            )}
                        </span>
                        <ArrowDownTrayIcon className="w-4 h-4 text-gray-400 group-hover:text-blue-600 flex-shrink-0" />
                    </a>
                ))}
            </div>
        </div>
        </motion.div>
    );
}
