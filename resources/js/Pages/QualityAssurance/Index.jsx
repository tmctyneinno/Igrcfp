import React, { useState } from "react";
import { Head } from "@inertiajs/react";
import GuestLayout from "@/Layouts/GuestLayout";


export default function Index({ auth }) {
    
    const title = "Quality Assurance Framework";

    return (
        <GuestLayout auth={auth}>
            <Head title={title}>
                <meta 
                    name="description" 
                    content="IGRCFP Quality Assurance Framework - Ensuring excellence, integrity, and continuous improvement across all Institute operations and professional standards." 
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
                            Quality Assurance Framework
                        </h1>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            Establishing standards, governance structures, and continuous improvement processes to ensure excellence across all IGRCFP operations.
                        </p>
                    </div>
                </div>
            </section>

            {/* Main Content */}
            <section className="py-16 lg:py-20 bg-white">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    {/* Introduction */}
                    <div className="mb-16">
                        <div className="flex items-center gap-3 mb-6">
                            <div className="w-12 h-1 bg-blue-600 rounded-full"></div>
                            <h2 className="text-3xl font-bold text-gray-900">1. Introduction</h2>
                        </div>
                        <div className="prose prose-lg max-w-none text-gray-600 space-y-4">
                            <p>
                                The Quality Assurance (QA) Framework of the Institute of GRC and Financial Crime Prevention establishes the standards, governance structures, monitoring mechanisms, and continuous improvement processes required to ensure excellence, integrity, credibility, accountability, and regulatory alignment across all Institute operations.
                            </p>
                            <p>The framework is designed to support:</p>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                                {[
                                    "Professional education and certification",
                                    "Training delivery and assessments",
                                    "Membership standards",
                                    "Governance and compliance activities",
                                    "Research and publications",
                                    "Events and conferences",
                                    "Partnerships and accreditation",
                                    "Operational effectiveness",
                                    "Ethical and professional conduct"
                                ].map((item, index) => (
                                    <div key={index} className="flex items-center gap-3 bg-blue-50 rounded-lg p-3">
                                        <svg className="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                        </svg>
                                        <span className="text-gray-700">{item}</span>
                                    </div>
                                ))}
                            </div>
                            <p className="mt-4">
                                This QA framework aligns with international best practices in Governance, Risk & Compliance (GRC), ISO quality principles, professional body governance, adult learning standards, regulatory and compliance expectations, and financial crime prevention integrity frameworks.
                            </p>
                        </div>
                    </div>

                    {/* Purpose */}
                    <div className="mb-16">
                        <div className="flex items-center gap-3 mb-6">
                            <div className="w-12 h-1 bg-blue-600 rounded-full"></div>
                            <h2 className="text-3xl font-bold text-gray-900">2. Purpose of the Quality Assurance Framework</h2>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {[
                                {
                                    title: "Consistency & Quality",
                                    description: "Ensure consistency and quality across all IGRCFP programmes and services."
                                },
                                {
                                    title: "Reputation Protection",
                                    description: "Protect the reputation and credibility of the Institute."
                                },
                                {
                                    title: "Continuous Improvement",
                                    description: "Promote continuous improvement and operational excellence."
                                },
                                {
                                    title: "Regulatory Compliance",
                                    description: "Ensure compliance with applicable laws, regulations, and ethical standards."
                                },
                                {
                                    title: "Stakeholder Assurance",
                                    description: "Provide assurance to stakeholders, regulators, learners, partners, sponsors, and members."
                                },
                                {
                                    title: "Accountability",
                                    description: "Establish measurable standards and accountability mechanisms."
                                },
                                {
                                    title: "Risk Minimisation",
                                    description: "Minimise operational, reputational, compliance, and delivery risks."
                                }
                            ].map((item, index) => (
                                <div key={index} className="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-5 border border-gray-200">
                                    <div className="flex items-start gap-3">
                                        <div className="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 className="font-semibold text-gray-900 mb-1">{item.title}</h4>
                                            <p className="text-sm text-gray-600">{item.description}</p>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* QA Principles */}
                    <div className="mb-16">
                        <div className="flex items-center gap-3 mb-6">
                            <div className="w-12 h-1 bg-blue-600 rounded-full"></div>
                            <h2 className="text-3xl font-bold text-gray-900">3. Quality Assurance Principles</h2>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            {[
                                {
                                    title: "Integrity",
                                    description: "All activities must uphold honesty, fairness, transparency, and ethical professionalism.",
                                    color: "blue"
                                },
                                {
                                    title: "Accountability",
                                    description: "Clear ownership and responsibility shall exist for all quality-related processes.",
                                    color: "green"
                                },
                                {
                                    title: "Consistency",
                                    description: "Standards must be applied consistently across all programmes, departments, and regions.",
                                    color: "purple"
                                },
                                {
                                    title: "Independence",
                                    description: "Quality reviews and assessments should maintain impartiality and objectivity.",
                                    color: "amber"
                                },
                                {
                                    title: "Continuous Improvement",
                                    description: "The Institute shall continuously evaluate and improve its systems, delivery, and stakeholder experience.",
                                    color: "red"
                                },
                                {
                                    title: "Risk-Based Approach",
                                    description: "QA activities shall prioritize areas of highest operational, reputational, compliance, and strategic risk.",
                                    color: "indigo"
                                },
                                {
                                    title: "Stakeholder-Centered",
                                    description: "Feedback from learners, members, partners, regulators, and stakeholders shall inform improvements.",
                                    color: "teal"
                                }
                            ].map((principle, index) => {
                                const colors = {
                                    blue: "border-blue-200 bg-blue-50",
                                    green: "border-green-200 bg-green-50",
                                    purple: "border-purple-200 bg-purple-50",
                                    amber: "border-amber-200 bg-amber-50",
                                    red: "border-red-200 bg-red-50",
                                    indigo: "border-indigo-200 bg-indigo-50",
                                    teal: "border-teal-200 bg-teal-50"
                                };
                                const iconColors = {
                                    blue: "text-blue-600",
                                    green: "text-green-600",
                                    purple: "text-purple-600",
                                    amber: "text-amber-600",
                                    red: "text-red-600",
                                    indigo: "text-indigo-600",
                                    teal: "text-teal-600"
                                };
                                return (
                                    <div key={index} className={`rounded-xl p-5 border ${colors[principle.color]} hover:shadow-md transition-shadow duration-300`}>
                                        <div className="flex items-center gap-3 mb-3">
                                            <div className="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                                <svg className={`w-5 h-5 ${iconColors[principle.color]}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                            </div>
                                            <h4 className="font-bold text-gray-900">{principle.title}</h4>
                                        </div>
                                        <p className="text-sm text-gray-600">{principle.description}</p>
                                    </div>
                                );
                            })}
                        </div>
                    </div>

                    {/* Scope */}
                    <div className="mb-16">
                        <div className="flex items-center gap-3 mb-6">
                            <div className="w-12 h-1 bg-blue-600 rounded-full"></div>
                            <h2 className="text-3xl font-bold text-gray-900">4. Scope of Quality Assurance</h2>
                        </div>
                        <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
                            <table className="w-full">
                                <thead>
                                    <tr className="bg-blue-600 text-white">
                                        <th className="text-left p-4 font-semibold">Area</th>
                                        <th className="text-left p-4 font-semibold">Scope</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {[
                                        ["Governance", "Boards, committees, advisory councils"],
                                        ["Training & Certification", "Curriculum, facilitators, examinations, assessments"],
                                        ["Membership", "Admission standards, ethics, CPD"],
                                        ["Events & Conferences", "Planning, delivery, speaker quality, attendee experience"],
                                        ["Publications", "Research, magazine/editorial quality"],
                                        ["Partnerships", "Accreditation, collaborations, sponsorships"],
                                        ["Technology Platforms", "LMS, websites, data systems"],
                                        ["Financial Management", "Controls, procurement, audit readiness"],
                                        ["Compliance", "GDPR, AML, sanctions, governance"],
                                        ["Operations", "Policies, HR, administration, communication"]
                                    ].map((row, index) => (
                                        <tr key={index} className={index % 2 === 0 ? "bg-white" : "bg-gray-50"}>
                                            <td className="p-4 font-medium text-gray-900 border-t border-gray-200">{row[0]}</td>
                                            <td className="p-4 text-gray-600 border-t border-gray-200">{row[1]}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Governance Structure */}
                    <div className="mb-16">
                        <div className="flex items-center gap-3 mb-6">
                            <div className="w-12 h-1 bg-blue-600 rounded-full"></div>
                            <h2 className="text-3xl font-bold text-gray-900">5. Governance Structure</h2>
                        </div>
                        
                        <div className="space-y-6">
                            {/* Governing Council */}
                            <div className="bg-gradient-to-br from-blue-50 to-white rounded-2xl p-6 border border-blue-100">
                                <h3 className="text-xl font-bold text-gray-900 mb-3">5.1 Governing Council</h3>
                                <div className="space-y-2">
                                    {[
                                        "Approve QA strategy and policies",
                                        "Monitor quality performance",
                                        "Review major risks and compliance matters",
                                        "Oversee institutional integrity",
                                        "Approve annual QA reports"
                                    ].map((item, index) => (
                                        <div key={index} className="flex items-center gap-2 text-gray-700">
                                            <svg className="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                            </svg>
                                            {item}
                                        </div>
                                    ))}
                                </div>
                            </div>

                            {/* QA Committee */}
                            <div className="bg-gradient-to-br from-green-50 to-white rounded-2xl p-6 border border-green-100">
                                <h3 className="text-xl font-bold text-gray-900 mb-3">5.2 Quality Assurance & Standards Committee</h3>
                                <p className="text-gray-600 mb-4">A dedicated QA Committee shall oversee implementation.</p>
                                
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <h4 className="font-semibold text-gray-900 mb-2">Responsibilities:</h4>
                                        <ul className="space-y-1">
                                            {["Monitor quality standards", "Conduct periodic reviews", "Review complaints and escalations", "Track corrective actions", "Ensure accreditation compliance", "Recommend improvements"].map((item, i) => (
                                                <li key={i} className="flex items-center gap-2 text-sm text-gray-600">
                                                    <div className="w-1.5 h-1.5 bg-green-500 rounded-full flex-shrink-0"></div>
                                                    {item}
                                                </li>
                                            ))}
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 className="font-semibold text-gray-900 mb-2">Committee Composition:</h4>
                                        <ul className="space-y-1">
                                            {["Chairperson", "Academic/Training Lead", "Compliance Representative", "Industry Expert", "External Independent Advisor", "Operations Representative"].map((item, i) => (
                                                <li key={i} className="flex items-center gap-2 text-sm text-gray-600">
                                                    <div className="w-1.5 h-1.5 bg-green-500 rounded-full flex-shrink-0"></div>
                                                    {item}
                                                </li>
                                            ))}
                                        </ul>
                                    </div>
                                </div>
                                <p className="text-sm text-gray-500 italic">Meetings should occur quarterly.</p>
                            </div>

                            {/* Internal Audit */}
                            <div className="bg-gradient-to-br from-purple-50 to-white rounded-2xl p-6 border border-purple-100">
                                <h3 className="text-xl font-bold text-gray-900 mb-3">5.3 Internal Audit & Compliance Function</h3>
                                <p className="text-gray-600 mb-3">The Institute should maintain independent internal review capabilities to assess:</p>
                                <div className="grid grid-cols-2 md:grid-cols-3 gap-2">
                                    {["Policy compliance", "Operational effectiveness", "Financial controls", "Data protection compliance", "Ethical conduct", "Risk management effectiveness"].map((item, i) => (
                                        <div key={i} className="flex items-center gap-2 bg-white rounded-lg p-2 border border-purple-100">
                                            <svg className="w-4 h-4 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span className="text-sm text-gray-700">{item}</span>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Key Performance Indicators */}
                    <div className="mb-16">
                        <div className="flex items-center gap-3 mb-6">
                            <div className="w-12 h-1 bg-blue-600 rounded-full"></div>
                            <h2 className="text-3xl font-bold text-gray-900">8. Key Performance Indicators</h2>
                        </div>
                        <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
                            <table className="w-full">
                                <thead>
                                    <tr className="bg-blue-600 text-white">
                                        <th className="text-left p-4 font-semibold">KPI</th>
                                        <th className="text-left p-4 font-semibold">Target</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {[
                                        ["Learner Satisfaction", "≥ 90%"],
                                        ["Event Satisfaction", "≥ 90%"],
                                        ["Assessment Accuracy", "≥ 95%"],
                                        ["Complaint Resolution", "Within 10 working days"],
                                        ["Compliance Breaches", "Zero tolerance"],
                                        ["CPD Compliance", "≥ 95%"],
                                        ["System Availability", "≥ 99%"]
                                    ].map((row, index) => (
                                        <tr key={index} className={index % 2 === 0 ? "bg-white" : "bg-gray-50"}>
                                            <td className="p-4 text-gray-900 border-t border-gray-200">{row[0]}</td>
                                            <td className="p-4 border-t border-gray-200">
                                                <span className="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    {row[1]}
                                                </span>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Continuous Improvement */}
                    <div className="mb-16">
                        <div className="flex items-center gap-3 mb-6">
                            <div className="w-12 h-1 bg-blue-600 rounded-full"></div>
                            <h2 className="text-3xl font-bold text-gray-900">9. Continuous Improvement Framework</h2>
                        </div>
                        <div className="bg-gradient-to-br from-blue-50 to-white rounded-2xl p-8 border border-blue-100">
                            <h3 className="text-xl font-bold text-gray-900 mb-6 text-center">Improvement Cycle</h3>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                {[
                                    { step: "1", title: "Identify", description: "Identify issues through audits, feedback, and reviews" },
                                    { step: "2", title: "Assess", description: "Assess root cause and develop corrective actions" },
                                    { step: "3", title: "Implement", description: "Implement improvements and monitor outcomes" }
                                ].map((item, index) => (
                                    <div key={index} className="text-center">
                                        <div className="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <span className="text-2xl font-bold text-white">{item.step}</span>
                                        </div>
                                        <h4 className="font-bold text-gray-900 mb-2">{item.title}</h4>
                                        <p className="text-sm text-gray-600">{item.description}</p>
                                    </div>
                                ))}
                            </div>
                            <div className="flex justify-center mt-6">
                                <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                            </div>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                {[
                                    { step: "6", title: "Reassess", description: "Reassess effectiveness and adjust as needed" },
                                    { step: "5", title: "Monitor", description: "Monitor outcomes and gather feedback" },
                                    { step: "4", title: "Execute", description: "Execute corrective actions and track progress" }
                                ].map((item, index) => (
                                    <div key={index} className="text-center">
                                        <div className="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <span className="text-2xl font-bold text-white">{item.step}</span>
                                        </div>
                                        <h4 className="font-bold text-gray-900 mb-2">{item.title}</h4>
                                        <p className="text-sm text-gray-600">{item.description}</p>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* Conclusion */}
                    <div className="mb-16">
                        <div className="flex items-center gap-3 mb-6">
                            <div className="w-12 h-1 bg-blue-600 rounded-full"></div>
                            <h2 className="text-3xl font-bold text-gray-900">12. Conclusion</h2>
                        </div>
                        <div className="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-8 text-white">
                            <p className="text-lg leading-relaxed">
                                The IGRCFP Quality Assurance Framework establishes an institutional foundation to ensure excellence, credibility, operational resilience, ethical integrity, and stakeholder confidence across all Institute activities.
                            </p>
                            <p className="text-lg leading-relaxed mt-4">
                                Through strong governance, continuous monitoring, independent oversight, and a culture of continuous improvement, IGRCFP will position itself as a trusted and globally respected institution in Governance, Risk, Compliance, and Financial Crime Prevention.
                            </p>
                        </div>
                    </div>

                    {/* Document Control */}
                    <div className="bg-gray-50 rounded-2xl p-8 border border-gray-200">
                        <h3 className="text-xl font-bold text-gray-900 mb-4">Document Control & Review</h3>
                        <div className="grid grid-cols-2 md:grid-cols-5 gap-4 text-center">
                            {[
                                { label: "Version Control", value: "Mandatory" },
                                { label: "Approval Records", value: "Retained" },
                                { label: "Review Cycle", value: "Annual" },
                                { label: "Access", value: "Role-based" },
                                { label: "Retention", value: "Policy Defined" }
                            ].map((item, index) => (
                                <div key={index} className="bg-white rounded-xl p-4 border border-gray-200">
                                    <p className="text-sm text-gray-500 mb-1">{item.label}</p>
                                    <p className="font-bold text-blue-600">{item.value}</p>
                                </div>
                            ))}
                        </div>
                    </div>

                </div>
            </section>
        </GuestLayout>
    );
}