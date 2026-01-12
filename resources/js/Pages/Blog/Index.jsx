import React from "react";
import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Blog({ auth, title, description }) {
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
                            Stay updated with the latest regulatory developments, industry trends, and thought leadership in governance, risk, compliance, and financial crime prevention. Our insights help professionals anticipate change, manage risk, and lead with confidence.
                        </p>
                    </div>
                </div>
            </section>

            {/* Search Input Field Above Featured Insights */}
            <section className="bg-white py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="max-w-lg mx-auto">
                        <div className="relative">
                            <input
                                type="text"
                                placeholder="Search insights, news, or updates..."
                                className="w-full py-3 pl-10 pr-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            />
                            <span className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                🔍
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            {/* Featured Insights */}
            <section className="bg-white py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 className="text-3xl font-semibold text-gray-900 text-center mb-8">Featured Insights</h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {/* Featured Insight Cards */}
                        <div className="bg-gray-100 rounded-lg shadow-lg overflow-hidden">
                            <img src="/path-to-image" alt="AI and Compliance" className="w-full h-56 object-cover"/>
                            <div className="p-6">
                                <h3 className="text-xl font-bold text-gray-900 mb-4">AI and Compliance: Balancing Innovation with Risk</h3>
                                <p className="text-gray-600">AI is reshaping compliance. Learn how to use it responsibly without increasing risk.</p>
                                <Link href="#" className="text-blue-500 hover:text-blue-700 mt-4 block">Read More →</Link>
                            </div>
                        </div>
                        {/* Repeat this block for other featured insights */}
                    </div>
                </div>
            </section>

            {/* Latest Insights */}
            <section className="bg-gray-50 py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 className="text-3xl font-semibold text-gray-900 text-center mb-8">Latest Insights</h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {/* Latest Insight Cards */}
                        <div className="bg-white rounded-lg shadow-lg overflow-hidden">
                            <img src="/path-to-image" alt="Cybersecurity Trends" className="w-full h-56 object-cover"/>
                            <div className="p-6">
                                <h3 className="text-xl font-bold text-gray-900 mb-4">Cybersecurity Trends: Staying Ahead of Threats</h3>
                                <p className="text-gray-600">Examine the latest cybersecurity threats and strategies to protect your organization.</p>
                                <Link href="#" className="text-blue-500 hover:text-blue-700 mt-4 block">Read More →</Link>
                            </div>
                        </div>
                        {/* Repeat this block for other latest insights */}
                    </div>

                    {/* Pagination */}
                    <div className="flex justify-center mt-8">
                        <nav className="inline-flex rounded-md shadow-sm">
                            <Link href="#" className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
                                Previous
                            </Link>
                            <Link href="#" className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border-t border-b border-gray-300 hover:bg-gray-50">
                                1
                            </Link>
                            <Link href="#" className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border-t border-b border-gray-300 hover:bg-gray-50">
                                2
                            </Link>
                            <Link href="#" className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border-t border-b border-gray-300 hover:bg-gray-50">
                                Next
                            </Link>
                        </nav>
                    </div>
                </div>
            </section>

            {/* Newsletter Subscription Section */}
            <section className="max-w-7xl mx-auto px-4 rounded-lg  bg-gradient-to-r from-blue-600 via-blue-00 to-green-500 ">
                <div className="mx-6 md:mx-12 lg:mx-16 xl:mx-24"> {/* Increased margins */}
                    <div className="max-w-7xl mx-auto text-white text-center py-8 md:py-12 lg:py-16">
                        <h3 className="text-3xl font-semibold mb-4">Never miss an update.</h3>
                        <p className="text-lg mb-8">Get insights delivered to your inbox.</p>

                        <div className="flex justify-center mb-4 px-4">
                            <input
                                type="email"
                                placeholder="Enter your email"
                                className="w-full max-w-md py-3 px-4 border border-white rounded-md text-black"
                            />
                            <button className="ml-4 py-3 px-6 bg-white text-blue-600 font-semibold rounded-md hover:bg-gray-200">
                                Subscribe
                            </button>
                        </div>

                        <p className="text-sm">We care about your data in our <Link href="#" className="text-blue-200 hover:text-blue-100">privacy policy</Link>.</p>
                    </div>
                </div>
            </section>

            


        </GuestLayout>
    );
}
