import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";
import React from "react";

export default function WhatWeOffer() {
    return (
        <section className="py-20 bg-white">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="text-center mb-16">
                    <h2 className="text-3xl font-bold text-gray-900 mb-4">
                        What We Offer
                    </h2>
                    <p className="text-gray-600 text-lg max-w-3xl mx-auto">
                        Advanced professional programmes at the intersection of regulation, risk, and technology
                    </p>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div className="bg-blue-50 p-8 rounded-2xl border border-blue-100 hover:shadow-xl transition-shadow duration-300">
                        <div className="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center mb-6">
                            <svg className="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 className="text-xl font-bold text-gray-900 mb-3">GRC & Risk Management</h3>
                        <p className="text-gray-600 mb-4">
                            Comprehensive programmes for governance, risk, and compliance professionals
                        </p>
                        <a href="/programmes/grc" className="text-blue-900 font-medium hover:text-blue-700 inline-flex items-center">
                            Learn More
                            <svg className="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <div className="bg-red-50 p-8 rounded-2xl border border-red-100 hover:shadow-xl transition-shadow duration-300">
                        <div className="w-16 h-16 bg-red-600 rounded-xl flex items-center justify-center mb-6">
                            <svg className="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 className="text-xl font-bold text-gray-900 mb-3">Financial Crime Prevention</h3>
                        <p className="text-gray-600 mb-4">
                            Specialised courses in AML, fraud prevention, sanctions, and investigations
                        </p>
                        <a href="/programmes/financial-crime" className="text-red-900 font-medium hover:text-red-700 inline-flex items-center">
                            Learn More
                            <svg className="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <div className="bg-purple-50 p-8 rounded-2xl border border-purple-100 hover:shadow-xl transition-shadow duration-300">
                        <div className="w-16 h-16 bg-purple-600 rounded-xl flex items-center justify-center mb-6">
                            <svg className="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <h3 className="text-xl font-bold text-gray-900 mb-3">Technology & Innovation</h3>
                        <p className="text-gray-600 mb-4">
                            Crypto, cybersecurity, AI governance, and RegTech solutions
                        </p>
                        <a href="/programmes" className="text-purple-900 font-medium hover:text-purple-700 inline-flex items-center">
                            View All Programmes
                            <svg className="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div className="text-center mt-12">
                    <a href="/programmes" className="inline-flex items-center justify-center bg-blue-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-800 transition duration-300">
                        Explore All Programmes
                        <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    );
}
