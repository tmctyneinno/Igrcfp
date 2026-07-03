import React, { useState } from "react";
import { Head } from "@inertiajs/react";
import GuestLayout from "@/Layouts/GuestLayout";

export default function CodeOfConduct({ auth }) {
    const title = "Code of Professional Conduct";

    return (
        <GuestLayout auth={auth}>
            <Head title={title}>
                <meta 
                    name="description" 
                    content="IGRCFP Code of Professional Conduct - The ethical principles and standards that bind all IGRCFP members in their professional practice." 
                /> 
            </Head>
            
            {/* Hero Banner */}
            <section className="w-full bg-gradient-to-r from-blue-200 via-white to-blue-200 py-28">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <div className="inline-flex items-center bg-blue-100 px-3 py-1 justify-center space-x-2 mb-6 rounded-full">
                            <div className="w-3 h-3 bg-blue-400 rounded-full animate-pulse"></div>
                            <span className="font-medium text-sm tracking-wider">{title}</span>
                        </div>
                        <h1 className="text-3xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                            Code of Professional Conduct
                        </h1>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            Upholding the highest standards of professional behaviour, ethical conduct, and personal integrity across the global GRC and financial crime prevention profession.
                        </p>
                    </div>
                </div>
            </section>

            {/* Main Content */}
            <section className="py-16 lg:py-20 bg-white">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    {/* Preamble */}
                    <div className="mb-16">
                        <div className="flex items-center gap-3 mb-6">
                            <div className="w-12 h-1 bg-blue-600 rounded-full"></div>
                            <h2 className="text-3xl font-bold text-gray-900">Preamble</h2>
                        </div>
                        <div className="prose prose-lg max-w-none text-gray-600 space-y-4">
                            <p>
                                The Institute of Governance, Risk, Compliance and Financial Crime Prevention (IGRCFP) exists to advance the standards of the profession. Central to that mission is the expectation that every IGRCFP member upholds the highest standards of professional behaviour, ethical conduct and personal integrity.
                            </p>
                            <p>
                                This Code of Professional Conduct sets out the principles and obligations that bind all IGRCFP members — regardless of their membership grade, jurisdiction or employment context. Membership of IGRCFP constitutes acceptance of this Code in full.
                            </p>
                            <p>
                                The Code does not exist to restrict members. It exists to protect them, their clients, their employers, the public and the integrity of the profession they have chosen to serve.
                            </p>
                        </div>
                    </div>

                    {/* The Seven Principles */}
                    <div className="mb-16">
                        <div className="flex items-center gap-3 mb-10">
                            <div className="w-12 h-1 bg-blue-600 rounded-full"></div>
                            <h2 className="text-3xl font-bold text-gray-900">The Seven Principles of Professional Conduct</h2>
                        </div>
                        <p className="text-lg text-gray-600 mb-10">
                            All IGRCFP members are required to embody and apply the following seven principles in their professional practice:
                        </p>

                        {/* Principle Cards */}
                        <div className="space-y-8">
                            {/* 1. Integrity */}
                            <div className="bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                                <div className="flex items-start gap-4">
                                    <div className="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <div className="flex-1">
                                        <h3 className="text-2xl font-bold text-gray-900 mb-4">1. Integrity</h3>
                                        <p className="text-gray-600 mb-4">
                                            Members shall act with honesty, transparency and moral courage in all professional activities. They shall not engage in, facilitate, conceal or assist with any conduct that is dishonest, deceptive or contrary to the interests of justice.
                                        </p>
                                        <div className="bg-white rounded-xl p-6 border border-gray-200">
                                            <h4 className="font-semibold text-gray-900 mb-3">Members shall:</h4>
                                            <ul className="space-y-2">
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Be truthful and transparent in all professional communications
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Decline to participate in or assist with activities that are dishonest or unlawful
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Disclose conflicts of interest promptly and manage them appropriately
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Never misrepresent their qualifications, experience or professional standing
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* 2. Objectivity */}
                            <div className="bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                                <div className="flex items-start gap-4">
                                    <div className="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                        </svg>
                                    </div>
                                    <div className="flex-1">
                                        <h3 className="text-2xl font-bold text-gray-900 mb-4">2. Objectivity</h3>
                                        <p className="text-gray-600 mb-4">
                                            Members shall form and express professional judgements that are impartial, evidence-based and free from bias, undue influence or conflicts of interest.
                                        </p>
                                        <div className="bg-white rounded-xl p-6 border border-gray-200">
                                            <h4 className="font-semibold text-gray-900 mb-3">Members shall:</h4>
                                            <ul className="space-y-2">
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Base advice and decisions on facts, evidence and sound professional judgement
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Declare and manage any personal, financial or professional interests that may compromise objectivity
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Resist pressure from employers, clients or others to compromise professional judgement
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Acknowledge the limits of their knowledge and seek appropriate expertise when required
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* 3. Confidentiality */}
                            <div className="bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                                <div className="flex items-start gap-4">
                                    <div className="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <div className="flex-1">
                                        <h3 className="text-2xl font-bold text-gray-900 mb-4">3. Confidentiality</h3>
                                        <p className="text-gray-600 mb-4">
                                            Members shall respect and protect the confidentiality of information received in the course of their professional duties, disclosing it only where required by law, regulatory obligation or with explicit consent.
                                        </p>
                                        <div className="bg-white rounded-xl p-6 border border-gray-200">
                                            <h4 className="font-semibold text-gray-900 mb-3">Members shall:</h4>
                                            <ul className="space-y-2">
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Treat all client, employer and third-party information as confidential
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Not use confidential information for personal gain or to benefit third parties
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Be aware of and comply with applicable data protection legislation
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Report suspicions of financial crime in accordance with legal requirements, balancing confidentiality with statutory obligations (e.g. Suspicious Activity Reporting)
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* 4. Professional Competence */}
                            <div className="bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                                <div className="flex items-start gap-4">
                                    <div className="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 14l9-5-9-5-9 5 9 5z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 14l9-5-9-5-9 5 9 5z" opacity="0.5" />
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 14v6l-9-5" />
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 14v6l9-5" />
                                        </svg>
                                    </div>
                                    <div className="flex-1">
                                        <h3 className="text-2xl font-bold text-gray-900 mb-4">4. Professional Competence</h3>
                                        <p className="text-gray-600 mb-4">
                                            Members shall maintain the professional knowledge, skills and competence required to serve their clients and employers effectively, and shall only undertake work for which they are competent.
                                        </p>
                                        <div className="bg-white rounded-xl p-6 border border-gray-200">
                                            <h4 className="font-semibold text-gray-900 mb-3">Members shall:</h4>
                                            <ul className="space-y-2">
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Complete the minimum CPD hours required for their membership grade each year
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Keep up to date with relevant regulatory, legislative and best practice developments
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Seek supervision or decline work that falls outside their competence
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Support the learning and development of colleagues and the wider profession
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* 5. Compliance with Laws and Regulations */}
                            <div className="bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                                <div className="flex items-start gap-4">
                                    <div className="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                        </svg>
                                    </div>
                                    <div className="flex-1">
                                        <h3 className="text-2xl font-bold text-gray-900 mb-4">5. Compliance with Laws and Regulations</h3>
                                        <p className="text-gray-600 mb-4">
                                            Members shall comply fully with applicable laws, regulations and professional standards in all jurisdictions in which they operate, and shall actively support and promote a culture of compliance within their organisations.
                                        </p>
                                        <div className="bg-white rounded-xl p-6 border border-gray-200">
                                            <h4 className="font-semibold text-gray-900 mb-3">Members shall:</h4>
                                            <ul className="space-y-2">
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Comply with all applicable anti-money laundering, financial crime, data protection and professional regulations
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Report breaches of law or regulation through appropriate internal and external channels
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Not facilitate, directly or indirectly, any unlawful activity
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Maintain awareness of the regulatory requirements of their jurisdiction and sector
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* 6. Respect and Professional Relationships */}
                            <div className="bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                                <div className="flex items-start gap-4">
                                    <div className="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div className="flex-1">
                                        <h3 className="text-2xl font-bold text-gray-900 mb-4">6. Respect and Professional Relationships</h3>
                                        <p className="text-gray-600 mb-4">
                                            Members shall treat all individuals — colleagues, clients, regulators, counterparties and others — with dignity, fairness and respect, and shall not engage in discriminatory, harassing or abusive conduct.
                                        </p>
                                        <div className="bg-white rounded-xl p-6 border border-gray-200">
                                            <h4 className="font-semibold text-gray-900 mb-3">Members shall:</h4>
                                            <ul className="space-y-2">
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Treat all individuals with respect, courtesy and professionalism
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Not discriminate on grounds of race, ethnicity, gender, religion, disability, sexual orientation, age or any other protected characteristic
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Challenge inappropriate conduct in the workplace in a responsible and constructive manner
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Uphold the reputation of the profession in all public and professional activities
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* 7. Responsibility to the Profession and Society */}
                            <div className="bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                                <div className="flex items-start gap-4">
                                    <div className="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div className="flex-1">
                                        <h3 className="text-2xl font-bold text-gray-900 mb-4">7. Responsibility to the Profession and Society</h3>
                                        <p className="text-gray-600 mb-4">
                                            Members recognise that their work exists within a broader societal context and that the prevention of financial crime serves the public interest. They shall act as ambassadors for the profession and shall contribute, where possible, to its advancement.
                                        </p>
                                        <div className="bg-white rounded-xl p-6 border border-gray-200">
                                            <h4 className="font-semibold text-gray-900 mb-3">Members shall:</h4>
                                            <ul className="space-y-2">
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Uphold and promote the reputation of IGRCFP and the profession
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Not bring IGRCFP, the profession or their employer into disrepute
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Support efforts to widen access to the profession and to professional development
                                                </li>
                                                <li className="flex items-start gap-2 text-gray-600">
                                                    <svg className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    Contribute to the advancement of knowledge and best practice in GRC and financial crime prevention
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Reporting, Investigation and Sanctions */}
                    <div className="mb-16">
                        <div className="flex items-center gap-3 mb-10">
                            <div className="w-12 h-1 bg-blue-600 rounded-full"></div>
                            <h2 className="text-3xl font-bold text-gray-900">Reporting, Investigation and Sanctions</h2>
                        </div>
                        
                        <p className="text-lg text-gray-600 mb-8">
                            IGRCFP takes breaches of this Code seriously. Any member, employer or member of the public who believes that an IGRCFP member has breached this Code may submit a formal complaint to IGRCFP.
                        </p>

                        {/* Complaints Process */}
                        <div className="bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl p-8 border border-gray-100 mb-8">
                            <h3 className="text-2xl font-bold text-gray-900 mb-6">Complaints Process:</h3>
                            <div className="space-y-4">
                                {[
                                    "Complaints should be submitted in writing to conduct@igrcfp.org, setting out the nature of the alleged breach and any supporting evidence",
                                    "IGRCFP will acknowledge receipt within 5 working days and appoint a Case Officer to review the complaint",
                                    "The subject of the complaint will be notified and given the opportunity to respond",
                                    "The Case Officer will investigate and produce a report within 30 working days (subject to complexity)",
                                    "Cases will be referred to IGRCFP's Conduct and Disciplinary Committee for determination",
                                    "Both parties will be notified of the outcome in writing"
                                ].map((step, index) => (
                                    <div key={index} className="flex items-start gap-4 bg-white rounded-xl p-4 border border-gray-200">
                                        <div className="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span className="text-white font-bold text-sm">{index + 1}</span>
                                        </div>
                                        <p className="text-gray-700 pt-1">{step}</p>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Available Sanctions */}
                        <div className="bg-gradient-to-br from-red-50 to-orange-50 rounded-2xl p-8 border border-red-100">
                            <h3 className="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <svg className="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                Available Sanctions <span className="text-lg font-normal text-gray-600">(in order of seriousness):</span>
                            </h3>
                            <div className="space-y-3">
                                {[
                                    "Formal written warning",
                                    "Required completion of additional CPD or ethics training",
                                    "Suspension of IGRCFP membership for a defined period",
                                    "Removal of designation",
                                    "Expulsion from IGRCFP membership"
                                ].map((sanction, index) => (
                                    <div key={index} className="flex items-center gap-3 bg-white rounded-xl p-4 border border-red-200">
                                        <div className="w-2 h-2 bg-red-500 rounded-full flex-shrink-0"></div>
                                        <p className="text-gray-700 font-medium">{sanction}</p>
                                    </div>
                                ))}
                            </div>
                            <p className="mt-6 text-gray-600 bg-white rounded-xl p-4 border border-gray-200">
                                Members subject to formal regulatory or criminal proceedings in connection with their professional activities are required to notify IGRCFP promptly. IGRCFP may suspend membership pending the outcome of such proceedings.
                            </p>
                        </div>
                    </div>

                    {/* Declaration */}
                    {/* <div className="mb-16">
                        <div className="flex items-center gap-3 mb-10">
                            <div className="w-12 h-1 bg-blue-600 rounded-full"></div>
                            <h2 className="text-3xl font-bold text-gray-900">Declaration</h2>
                        </div>
                        
                        <div className="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-8 text-white">
                            <h3 className="text-2xl font-bold mb-6">Member Declaration</h3>
                            <p className="mb-6 text-blue-100 leading-relaxed">
                                By accepting IGRCFP membership, I confirm that I have read, understood and agree to be bound by the IGRCFP Code of Professional Conduct. I understand that a breach of this Code may result in disciplinary action, including the removal of my membership and designation.
                            </p>
                            <p className="text-blue-100 leading-relaxed">
                                I commit to upholding the seven principles of this Code in all my professional activities and to maintaining the standards expected of an IGRCFP member.
                            </p>
                            <div className="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4 text-blue-200">
                                <div className="border-b-2 border-blue-400 pb-2">
                                    <span className="text-sm">Name: ___________________________________</span>
                                </div>
                                <div className="border-b-2 border-blue-400 pb-2">
                                    <span className="text-sm">Designation: _______________</span>
                                </div>
                                <div className="border-b-2 border-blue-400 pb-2">
                                    <span className="text-sm">Signature: ______________________________</span>
                                </div>
                                <div className="border-b-2 border-blue-400 pb-2">
                                    <span className="text-sm">Date: ______________________</span>
                                </div>
                            </div>
                        </div>
                    </div> */}

                    {/* Footer Note */}
                    <div className="bg-gray-50 rounded-2xl p-8 border border-gray-200">
                        <p className="text-gray-600 leading-relaxed">
                            This Code of Professional Conduct was adopted by IGRCFP and applies to all grades of membership. It is reviewed annually and updated as required to reflect developments in the profession and the regulatory environment.
                        </p>
                        <div className="mt-6 pt-6 border-t border-gray-200 text-center">
                            <p className="text-gray-500 text-sm">
                                © IGRCFP — Institute of Governance, Risk, Compliance and Financial Crime Prevention | www.igrcfp.org
                            </p>
                        </div>
                    </div>

                </div>
            </section>
        </GuestLayout>
    );
}