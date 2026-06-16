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
import Articles from "@/Pages/components/Articles";
import JoinIGRCFP from "@/Pages/components/JoinIGRCFP";
import WhatWeOffer from "@/Pages/components/WhatWeOffer";
import CourseCatalogue from "@/Pages/components/CourseCatalogue";import PartnersSlider from '@/Pages/About/PartnersSlider'; 
   
 
export default function Welcome({ auth, courses, latestArticles = [], featuredArticles = [], homepageEvents = [], latestBlogs = [] }) {
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const [openDropdown, setOpenDropdown] = useState(null);
    const mobileMenuRef = useRef(null);

    // Course catalogue preview data - MOVED INSIDE THE COMPONENT
    const courseCategories = [
        {
            name: "Core GRC & Governance",
            icon: "🏛️",
            count: 10,
            color: "blue",
            sampleCourse: "Certificate in Governance, Risk & Compliance (GRC)"
        },
        {
            name: "Financial Crime & AML",
            icon: "🔍",
            count: 12,
            color: "red",
            sampleCourse: "Certificate in Anti-Money Laundering (AML & CFT)"
        },
        {
            name: "Cybersecurity & Digital Risk",
            icon: "🛡️",
            count: 10,
            color: "purple",
            sampleCourse: "Certificate in Cybersecurity & Digital Risk"
        },
        {
            name: "Data, Privacy & Technology",
            icon: "🔐",
            count: 8,
            color: "green",
            sampleCourse: "Certificate in Data Protection & Privacy (GDPR)"
        },
        {
            name: "Audit, Control & Assurance",
            icon: "📊",
            count: 8,
            color: "indigo",
            sampleCourse: "Certificate in Internal Audit & Assurance"
        },
        {
            name: "ESG, Ethics & Sustainability",
            icon: "🌱",
            count: 6,
            color: "teal",
            sampleCourse: "Certificate in ESG (Environmental, Social & Governance)"
        }
    ];


    return (
         <GuestLayout auth={auth} forceWhiteNavbar>
            <Head title="IGRCFP - Professional Learning Platform" />
            
            {/* Hero Section with AOS effects */}
            <SplitHeroSlider auth={auth} />
            

            {/* Scholarship Banner - Add this right after the hero */}
            <section className="bg-gradient-to-r from-yellow-400 via-amber-500 to-orange-500" data-aos="fade-up">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div className="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div className="flex items-center gap-4">
                            <span className="text-4xl">🎓</span>
                            <div className="text-white">
                                <h3 className="text-xl font-bold">Emerging Professionals Scholarship 2026</h3>
                                <p className="text-yellow-100">25 slots available globally. Apply from 1st to June 30, 2026.</p>
                            </div>
                        </div>
                        <Link
                            href="/scholarship/igrcfp-emerging-professionals-scholarship-programme-2026?show=full"
                            className="bg-white text-yellow-700 px-8 py-3 rounded-lg font-bold hover:bg-yellow-50 transition whitespace-nowrap shadow-lg"
                        >
                            Apply Now →
                        </Link>
                    </div>
                </div>
            </section>

          
 
            <section className="bg-white py-24 overflow-hidden">
               <WhoAreWe auth={auth} />
            </section>

            
            <WhatWeOffer auth={auth} />

            {/* Course Catalogue Preview Section */}
            <CourseCatalogue courseCategories={courseCategories}/>
 
            <Certification courses={courses} />

            <Articles latestArticles={latestArticles} featuredArticles={featuredArticles} latestBlogs={latestBlogs} />
            
           
            <GlobalEvents events={homepageEvents} />
  
            <BecomeMember /> 

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
            <PartnersSlider />
        </GuestLayout>
    );
}
