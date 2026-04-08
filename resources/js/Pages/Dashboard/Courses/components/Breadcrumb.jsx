import React from 'react';
import { Link } from '@inertiajs/react';

export default function Breadcrumb({ courseTitle }) {
    return (
        <nav className="mb-6 flex items-center text-sm" aria-label="Breadcrumb">
            <Link href={route('dashboard.index')} className="text-gray-500 hover:text-gray-700 transition">
                Dashboard
            </Link>
            <span className="mx-2 text-gray-400">/</span>
            <Link href={route('dashboard.my-courses')} className="text-gray-500 hover:text-gray-700 transition">
                My Courses
            </Link>
            <span className="mx-2 text-gray-400">/</span>
            <span className="text-gray-900 font-medium truncate max-w-xs">{courseTitle}</span>
        </nav>
    );
}