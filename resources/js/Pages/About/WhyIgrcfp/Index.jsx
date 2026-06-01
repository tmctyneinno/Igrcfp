import React from "react";
import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import BoardOfTrustees from '@/Pages/About/OurStructure/BoardOfTrustees';

export default function Index({ auth, title, description }) {
    return (
        <GuestLayout auth={auth}>
            <Head title={title} />
            {/* Hero Section */}
            <section className="w-full bg-gradient-to-r from-blue-200 via-white to-blue-200 py-28">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                   <div className="text-center">
                        <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                           Why IGRCFP
                        </h1>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            The Institute of Governance, Risk, Compliance & Financial Crime Prevention 
                        </p>
                    </div>
                </div>
            </section>

           {/* Professional Education Section */}
            <section className="py-16 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-6">Comprehensive Professional Education</h2>
                            <p className="text-gray-600 mb-6">
                                IGRCFP delivers advanced professional programmes designed for today's complex regulatory, 
                                digital, and financial crime landscape. Our courses sit at the intersection of regulation, 
                                risk, technology, and financial crime prevention.
                            </p>
                            <p className="text-gray-600 mb-8">
                                All programmes are framework-led, practitioner-focused, and globally relevant, ensuring 
                                professionals are equipped to handle emerging challenges in their respective fields.
                            </p>
                            
                            <div className="space-y-4">
                                <div className="flex items-start">
                                    <svg className="w-6 h-6 text-green-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    <div>
                                        <h4 className="font-semibold text-gray-900">Core Programme Pathways</h4>
                                        <p className="text-gray-600 text-sm">Five specialized pathways covering GRC, financial crime, crypto, cybersecurity, and AI governance</p>
                                    </div>
                                </div>
                                <div className="flex items-start">
                                    <svg className="w-6 h-6 text-green-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    <div>
                                        <h4 className="font-semibold text-gray-900">Practical Focus</h4>
                                        <p className="text-gray-600 text-sm">Real-world case studies and implementation frameworks</p>
                                    </div>
                                </div>
                                <div className="flex items-start">
                                    <svg className="w-6 h-6 text-green-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    <div>
                                        <h4 className="font-semibold text-gray-900">Global Recognition</h4>
                                        <p className="text-gray-600 text-sm">Professional certifications aligned with international standards</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div className="mt-8">
                                <a href="/programmes" className="inline-flex items-center text-blue-900 font-semibold hover:text-blue-700">
                                    Explore Our Programmes
                                    <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        
                        <div className="bg-white p-8 rounded-2xl shadow-lg border border-gray-200">
                            <h3 className="text-2xl font-bold text-gray-900 mb-6">Our Programme Pathways</h3>
                            <div className="space-y-4">
                                {[
                                    { title: "Governance, Risk & Compliance", color: "blue", link: "/programmes/grc" },
                                    { title: "Financial Crime Prevention", color: "red", link: "/programmes/financial-crime" },
                                    { title: "Crypto & Digital Assets", color: "purple", link: "/programmes/crypto" },
                                    { title: "Cybersecurity & Digital Risk", color: "green", link: "/programmes/cybersecurity" },
                                    { title: "AI & Emerging Technology", color: "yellow", link: "/programmes/ai" }
                                ].map((pathway, index) => (
                                    <a 
                                        key={index} 
                                        href={pathway.link}
                                        className="flex items-center justify-between p-4 rounded-lg border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition duration-200"
                                    >
                                        <div className="flex items-center">
                                            <div className={`w-3 h-3 rounded-full mr-3 bg-${pathway.color}-500`}></div>
                                            <span className="font-medium text-gray-900">{pathway.title}</span>
                                        </div>
                                        <svg className="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                ))}
                            </div>
                            <div className="mt-6 pt-6 border-t border-gray-200">
                                <a href="/course-catalogue" className="block text-center bg-blue-900 text-white py-3 rounded-lg font-medium hover:bg-blue-800 transition duration-300">
                                    View Complete Course Catalog
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
 
           
        </GuestLayout>
    );
}