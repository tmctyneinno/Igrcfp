import React from 'react';
import { DocumentTextIcon } from '@heroicons/react/24/outline';

export default function MaterialList({ materials }) {
    if (!materials || materials.length === 0) return null;

    return (
        <div className="mb-6">
            <h4 className="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">
                Materials
            </h4> 
            <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                {materials.map((material) => (
                    <a
                        key={material.id}
                        href={material.file_url}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition group"
                    >
                        <DocumentTextIcon className="w-5 h-5 text-blue-600" />
                        <span className="text-sm text-gray-900">{material.title}</span>
                    </a>
                ))}
            </div>
        </div>
    );
}