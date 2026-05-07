import { Head, Link } from "@inertiajs/react";
import { useEffect, useState, useRef } from "react";
import React from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import MembershipOptions from "@/Pages/components/MembershipOptions";
import SplitHeroSlider from "@/Pages/components/SplitHeroSlider";
import Certification from "@/Pages/components/Certification";
import GlobalEvents from "@/Pages/components/GlobalEvents";
import BecomeMember from "@/Pages/components/BecomeMember";
import Testimonials from "@/Pages/components/Testimonials";
import FAQSection from "@/Pages/components/FAQSection";
import WhoAreWe from "@/Pages/components/WhoAreWe";
import JoinIGRCFP from "@/Pages/components/JoinIGRCFP";
import WhatWeOffer from "@/Pages/components/WhatWeOffer";
 
   
 
export default function Welcome({ auth, courses }) {
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const [openDropdown, setOpenDropdown] = useState(null);
    const mobileMenuRef = useRef(null);
   

    return (
        <GuestLayout auth={auth}> 
            <Head title="IGRCFP - Professional Learning Platform" />
            
            {/* Hero Section with AOS effects */}
            <div className="pt-0">
                <SplitHeroSlider auth={auth} />
            </div>
 
            <section className="bg-white py-24 overflow-hidden">
               <WhoAreWe auth={auth} />
            </section>

            
            <WhatWeOffer auth={auth} />

            {/* Course Catalogue Preview Section */}
            <section className="bg-gray-50 py-20 overflow-hidden" data-aos="fade-up" data-aos-duration="1000">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Section Header */}
                    <div className="text-center mb-12">
                        <div className="relative inline-flex items-center mb-3">
                            <span className="text-sm tracking-widest text-gray-400 uppercase">
                                Course Catalogue
                            </span>
                        </div>
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Professional Certificates in GRC, Compliance & Financial Crime Prevention
                        </h2>
                        <p className="text-gray-600 text-lg max-w-3xl mx-auto mb-8">
                            IGRCFP offers a structured portfolio of specialist certificate programmes designed for professionals working across governance, risk, compliance, financial crime prevention, cybersecurity, and emerging regulatory environments.
                        </p>
                        <Link
                            href={route('course.catalog.index')}
                            className="inline-flex items-center justify-center bg-blue-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-800 transition duration-300 transform hover:scale-105"
                        >
                            View Full Course Catalogue
                            <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </Link>
                    </div>

                    {/* Category Grid */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
                        {courseCategories.map((category, index) => (
                            <Link
                                key={index}
                                href={route('course.catalog.index')}
                                className="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-blue-200 group transform hover:-translate-y-1"
                                data-aos="fade-up"
                                data-aos-delay={index * 50}
                                data-aos-duration="800"
                            >
                                <div className="flex items-start justify-between mb-4">
                                    <span className="text-3xl">{category.icon}</span>
                                    <span className="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">
                                        {category.count} Courses
                                    </span>
                                </div>
                                <h3 className="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-900 transition-colors">
                                    {category.name}
                                </h3>
                                <p className="text-sm text-gray-500 mb-3">
                                    e.g. {category.sampleCourse}
                                </p>
                                <span className="text-blue-600 font-medium text-sm inline-flex items-center group-hover:translate-x-1 transition-transform">
                                    Explore Courses →
                                </span>
                            </Link>
                        ))}
                    </div>

                    {/* Stats Bar */}
                    <div className="bg-white rounded-2xl shadow-lg p-8 mb-16" data-aos="zoom-in" data-aos-duration="1000">
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                            <div>
                                <div className="text-3xl font-bold text-blue-900 mb-1">62+</div>
                                <div className="text-sm text-gray-600">Certificate Programmes</div>
                            </div>
                            <div>
                                <div className="text-3xl font-bold text-blue-900 mb-1">7</div>
                                <div className="text-sm text-gray-600">Knowledge Domains</div>
                            </div>
                            <div>
                                <div className="text-3xl font-bold text-blue-900 mb-1">3</div>
                                <div className="text-sm text-gray-600">Learning Pathways</div>
                            </div>
                            <div>
                                <div className="text-3xl font-bold text-blue-900 mb-1">5</div>
                                <div className="text-sm text-gray-600">Delivery Methods</div>
                            </div>
                        </div>
                    </div>

                    {/* Learning Pathways Preview */}
                    <div className="mb-12" data-aos="fade-up" data-aos-duration="1000">
                        <h3 className="text-2xl font-bold text-gray-900 text-center mb-2">Learning Pathways</h3>
                        <p className="text-gray-600 text-center mb-8">Structured routes to build expertise based on your career stage</p>
                        
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {learningPathways.map((pathway, index) => (
                                <div
                                    key={index}
                                    className="bg-white rounded-xl p-6 border-l-4 border-blue-600 shadow-md hover:shadow-lg transition-all duration-300"
                                    data-aos="fade-up"
                                    data-aos-delay={index * 100}
                                    data-aos-duration="800"
                                >
                                    <h4 className="text-lg font-bold text-gray-900 mb-2">{pathway.name}</h4>
                                    <p className="text-sm text-gray-600 mb-4">{pathway.description}</p>
                                    <div className="flex items-center justify-between">
                                        <span className="text-sm font-medium text-blue-600">{pathway.courses} recommended courses</span>
                                        <Link
                                            href={route('course.catalog.index')}
                                            className="text-blue-600 hover:text-blue-800"
                                        >
                                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                            </svg>
                                        </Link>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Bottom CTA */}
                    <div className="text-center" data-aos="fade-up" data-aos-duration="1000">
                        <div className="bg-gradient-to-r from-blue-900 to-blue-800 rounded-2xl p-8 md:p-12 text-white">
                            <h3 className="text-2xl md:text-3xl font-bold mb-4">Ready to advance your career?</h3>
                            <p className="text-blue-100 mb-6 max-w-2xl mx-auto">
                                Explore our full catalogue of 62+ professional certificate programmes and find the right path for your career goals.
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                <Link
                                    href={route('course.catalog.index')}
                                    className="inline-flex items-center justify-center bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300"
                                >
                                    Browse Full Catalogue
                                    <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                    </svg>
                                </Link>
                                <Link
                                    href={route('contact')}
                                    className="inline-flex items-center justify-center bg-transparent text-white px-8 py-3 rounded-lg font-semibold border-2 border-white hover:bg-white hover:text-blue-900 transition duration-300"
                                >
                                    Request Information
                                    <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
           
           

            <section className="bg-gray-50 py-24 overflow-hidden" data-aos="zoom-in" data-aos-duration="1000">
               <Certification courses={courses} />
            </section>  

            <section className="bg-white py-24 overflow-hidden" data-aos="zoom-in" data-aos-duration="1200">
                <GlobalEvents />
            </section>
  
            <section className="bg-gray py-24 overflow-hidden" data-aos="zoom-in" data-aos-duration="1200">
                <BecomeMember /> 
            </section> 

            <section className="bg-white py-0 overflow-hidden" data-aos="zoom-in" data-aos-duration="1200">
                <MembershipOptions />
                <JoinIGRCFP />
            </section>

            <section className="bg-white py-0 overflow-hidden" data-aos="zoom-in" data-aos-duration="1200">
                <FAQSection />
            </section>

            <section className="bg-white py-0 overflow-hidden" data-aos="zoom-in" data-aos-duration="1200">
                <Testimonials />
            </section>

            {/* CTA Section with AOS zoom effect */}
            <section 
                className="py-20 bg-gradient-to-r from-blue-950 to-blue-900"
                data-aos="zoom-in"
                data-aos-duration="1400"
            >
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl font-bold text-white mb-6">Ready to Start Your Learning Journey?</h2>
                    <p className="text-blue-100 mb-8 text-lg">
                        Join thousands of successful learners who have transformed their careers with our platform.
                    </p>
                    <Link
                        href={auth.user ? route('dashboard.index') : route('register')}
                        className="inline-block bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition duration-300 shadow-lg transform hover:scale-105"
                        data-aos="fade-up"
                        data-aos-delay="300"
                        data-aos-duration="1400"
                    >
                        {auth.user ? 'Continue Learning' : 'Get Started for Free'}
                    </Link>
                </div>
            </section>
  
        </GuestLayout>
    );
}