import React from "react";
import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Index({ auth, title, description }) {
    return (
        <GuestLayout auth={auth}>
            <Head title={title} />
            
            {/* Hero Section */}
            <section className="w-full bg-gradient-to-r from-blue-200 via-white to-blue-200 py-28">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                            {title}
                        </h1>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                            View upcoming courses, workshops, and programmes at IGRCFP. Choose the format and dates that work for you.
                        </p>
                        <div className="mt-8">
                            <Link
                                href="/training-calendar"
                                className=" bg-white text-blue-900 px-6 py-3 rounded-xl font-semibold text-lg hover:bg-gray-50 transition-all duration-300 border-2 border-blue-900 hover:border-blue-700 transform hover:-translate-y-1 hover:scale-[1.02] shadow-md inline-flex items-center justify-center"
                            >
                                Download Prospectus
                            </Link>
                            
                        </div>
                    </div>
                </div>
            </section>

         

        </GuestLayout>
    );
}
