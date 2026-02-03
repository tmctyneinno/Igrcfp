import { Head } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import React from 'react';

export default function CryptoPathway({auth}) {
    const courses = [
        {
            title: "Crypto-Asset Regulation & Compliance",
            duration: "3 months",
            level: "Professional",
            description: "Regulatory frameworks for crypto-assets across jurisdictions and compliance obligations",
            outcomes: [
                "Navigate global crypto regulations",
                "Implement compliance programmes",
                "Manage regulatory reporting"
            ]
        },
        {
            title: "AML, Sanctions & Financial Crime Risks in Crypto",
            duration: "3 months",
            level: "Advanced",
            description: "Financial crime risks specific to crypto-assets and preventive measures",
            outcomes: [
                "Identify crypto-related financial crime risks",
                "Implement AML controls for crypto",
                "Conduct crypto transaction monitoring"
            ]
        },
        {
            title: "Blockchain Technology: Governance, Risk & Controls",
            duration: "4 months",
            level: "Professional",
            description: "Understanding blockchain technology and implementing governance frameworks",
            outcomes: [
                "Understand blockchain fundamentals",
                "Design governance frameworks",
                "Implement risk controls"
            ]
        },
        {
            title: "Decentralized Finance (DeFi) Risk & Oversight",
            duration: "3 months",
            level: "Advanced",
            description: "Risk management and oversight frameworks for DeFi protocols and platforms",
            outcomes: [
                "Assess DeFi protocol risks",
                "Implement oversight frameworks",
                "Navigate regulatory considerations"
            ]
        },
        {
            title: "Virtual Asset Service Provider (VASP) Compliance",
            duration: "3 months",
            level: "Professional",
            description: "Compliance requirements and frameworks for VASPs under FATF standards",
            outcomes: [
                "Implement VASP compliance programmes",
                "Conduct customer due diligence",
                "Manage travel rule compliance"
            ]
        },
        {
            title: "Crypto Custody & Security Solutions",
            duration: "2 months",
            level: "Professional",
            description: "Custody solutions, security frameworks, and asset protection for crypto",
            outcomes: [
                "Design custody solutions",
                "Implement security frameworks",
                "Manage private key security"
            ]
        }
    ];

    return (
        <>
        <GuestLayout auth={auth}>
            <Head title="Crypto & Digital Assets | IGRCFP Programmes" />
            
            {/* Hero Section */}
            <div className="relative bg-gradient-to-r from-purple-900 to-purple-800 py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <div className="inline-flex items-center justify-center w-20 h-20 bg-white/10 rounded-full mb-6">
                            <svg className="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <h1 className="text-4xl md:text-5xl font-bold text-white mb-6">
                            Crypto, Digital Assets & Blockchain Risk
                        </h1>
                        <p className="text-xl text-purple-100 max-w-3xl mx-auto">
                            Built for organisations and professionals navigating crypto-assets, blockchain platforms, and decentralized systems
                        </p>
                    </div>
                </div>
            </div>

            {/* What Learners Gain */}
            <div className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">What Learners Gain</h2>
                        <p className="text-gray-600 text-lg">Essential knowledge for navigating the digital asset landscape</p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        {[
                            {
                                title: "Blockchain Understanding",
                                description: "Comprehend how blockchain works without needing to code",
                                icon: "🔗"
                            },
                            {
                                title: "Regulatory Compliance",
                                description: "Navigate obligations across multiple jurisdictions",
                                icon: "⚖️"
                            },
                            {
                                title: "Risk & Control Design",
                                description: "Implement controls for crypto-related activities",
                                icon: "🛡️"
                            },
                            {
                                title: "Financial Crime Typologies",
                                description: "Identify risks unique to digital assets",
                                icon: "🔍"
                            }
                        ].map((item, index) => (
                            <div key={index} className="bg-purple-50 p-8 rounded-xl border border-purple-100">
                                <div className="text-4xl mb-4">{item.icon}</div>
                                <h3 className="text-xl font-semibold text-gray-900 mb-3">{item.title}</h3>
                                <p className="text-gray-600">{item.description}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* Programme Overview */}
            <div className="py-16 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-6">Programme Overview</h2>
                            <p className="text-gray-600 text-lg mb-6">
                                This pathway provides comprehensive education on the regulatory, compliance, 
                                and risk management aspects of crypto-assets and blockchain technology.
                            </p>
                            <p className="text-gray-600 mb-8">
                                Designed for professionals who need to understand and manage the unique risks 
                                associated with digital assets while ensuring regulatory compliance.
                            </p>
                            
                            <div className="bg-purple-50 p-6 rounded-xl">
                                <h3 className="text-xl font-semibold text-purple-900 mb-4">Who This Programme Is For</h3>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <ul className="space-y-2">
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-purple-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Fintech Professionals</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-purple-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Compliance Teams</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-purple-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Crypto Business Leaders</span>
                                        </li>
                                    </ul>
                                    <ul className="space-y-2">
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-purple-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Regulators & Policy Makers</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-purple-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Risk Management Leaders</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-purple-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Financial Institution Executives</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <div className="bg-white p-8 rounded-2xl shadow-lg border border-gray-200">
                                <h3 className="text-2xl font-bold text-gray-900 mb-6">Programme Focus Areas</h3>
                                <div className="space-y-4">
                                    {[
                                        "Regulatory compliance for digital assets",
                                        "AML/CFT obligations for VASPs",
                                        "Risk assessment for blockchain platforms",
                                        "Security and custody solutions",
                                        "DeFi protocol oversight",
                                        "Cross-border regulatory considerations"
                                    ].map((area, index) => (
                                        <div key={index} className="flex items-start">
                                            <svg className="w-5 h-5 text-purple-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                            </svg>
                                            <span className="text-gray-700">{area}</span>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Courses Grid */}
            <div className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Crypto & Digital Assets Courses</h2>
                        <p className="text-gray-600 text-lg">Specialized courses for the digital asset ecosystem</p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {courses.map((course, index) => (
                            <div key={index} className="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                <div className="p-6">
                                    <div className="flex justify-between items-start mb-4">
                                        <span className={`px-3 py-1 rounded-full text-xs font-semibold ${
                                            course.level === 'Advanced' ? 'bg-purple-100 text-purple-800' :
                                            'bg-blue-100 text-blue-800'
                                        }`}>
                                            {course.level}
                                        </span>
                                        <span className="text-sm text-gray-500">{course.duration}</span>
                                    </div>
                                    <h3 className="text-xl font-bold text-gray-900 mb-3">{course.title}</h3>
                                    <p className="text-gray-600 mb-4">{course.description}</p>
                                    <div className="space-y-2 mb-6">
                                        <h4 className="font-semibold text-gray-900">Learning Outcomes:</h4>
                                        <ul className="space-y-1">
                                            {course.outcomes.map((outcome, idx) => (
                                                <li key={idx} className="flex items-start text-sm text-gray-600">
                                                    <svg className="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    {outcome}
                                                </li>
                                            ))}
                                        </ul>
                                    </div>
                                    <button className="w-full bg-purple-900 text-white py-3 rounded-lg font-medium hover:bg-purple-800 transition duration-300">
                                        View Course Details
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* CTA Section */}
            <div className="py-16 bg-gradient-to-r from-purple-900 to-purple-800">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl font-bold text-white mb-6">Navigate the Future of Finance</h2>
                    <p className="text-purple-100 text-lg mb-8 max-w-2xl mx-auto">
                        Gain the knowledge and skills needed to succeed in the rapidly evolving digital asset landscape
                    </p>
                    <div className="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="/programmes/all-courses" className="bg-white text-purple-900 px-8 py-3 rounded-lg font-semibold hover:bg-purple-50 transition duration-300">
                            View All Courses
                        </a>
                        <a href="/contact" className="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-purple-900 transition duration-300">
                            Contact Admissions
                        </a>
                    </div>
                </div>
            </div>
        </GuestLayout>
        </>
    );
}