import { Head } from '@inertiajs/react';
import React from 'react';

export default function CybersecurityPathway() {
    const courses = [
        {
            title: "Cyber Risk Governance for Boards & Executives",
            duration: "2 months",
            level: "Executive",
            description: "Strategic cyber risk oversight and governance for senior leadership",
            outcomes: [
                "Develop cyber governance frameworks",
                "Lead cyber risk discussions",
                "Make informed investment decisions"
            ]
        },
        {
            title: "Cybercrime, Fraud & Digital Threats",
            duration: "3 months",
            level: "Professional",
            description: "Understanding and mitigating cyber-enabled financial crime and fraud",
            outcomes: [
                "Identify cybercrime typologies",
                "Implement fraud prevention measures",
                "Coordinate incident response"
            ]
        },
        {
            title: "Data Protection, Privacy & Regulatory Compliance",
            duration: "4 months",
            level: "Professional",
            description: "Global data protection regulations and privacy compliance frameworks",
            outcomes: [
                "Implement data protection programmes",
                "Navigate global privacy regulations",
                "Manage data breach responses"
            ]
        },
        {
            title: "Technology Risk Management & Operational Resilience",
            duration: "3 months",
            level: "Advanced",
            description: "Managing technology risks and building operational resilience",
            outcomes: [
                "Implement risk management frameworks",
                "Design resilient operations",
                "Conduct business continuity planning"
            ]
        },
        {
            title: "Incident Response, Breach Management & Regulatory Reporting",
            duration: "3 months",
            level: "Professional",
            description: "Effective incident response and regulatory compliance during cyber incidents",
            outcomes: [
                "Lead incident response teams",
                "Manage breach notifications",
                "Prepare regulatory reports"
            ]
        },
        {
            title: "Third-Party & Supply Chain Cyber Risk",
            duration: "2 months",
            level: "Professional",
            description: "Managing cyber risks across supply chains and third-party relationships",
            outcomes: [
                "Assess third-party cyber risks",
                "Implement supply chain controls",
                "Manage vendor relationships"
            ]
        }
    ];

    return (
        <>
            <Head title="Cybersecurity & Digital Risk | IGRCFP Programmes" />
            
            {/* Hero Section */}
            <div className="relative bg-gradient-to-r from-green-900 to-green-800 py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <div className="inline-flex items-center justify-center w-20 h-20 bg-white/10 rounded-full mb-6">
                            <svg className="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h1 className="text-4xl md:text-5xl font-bold text-white mb-6">
                            Cybersecurity, Technology & Digital Risk
                        </h1>
                        <p className="text-xl text-green-100 max-w-3xl mx-auto">
                            Focused on cyber risk as a governance, regulatory, and financial crime issue — not just an IT problem
                        </p>
                    </div>
                </div>
            </div>

            {/* Core Themes */}
            <div className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Core Programme Themes</h2>
                        <p className="text-gray-600 text-lg">Addressing cyber risk from multiple organizational perspectives</p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {[
                            {
                                title: "Enterprise Risk",
                                description: "Cybersecurity as an organizational-wide risk management issue",
                                icon: "🏢"
                            },
                            {
                                title: "Regulatory Compliance",
                                description: "Meeting regulatory expectations around resilience and reporting",
                                icon: "📋"
                            },
                            {
                                title: "Financial Crime",
                                description: "Addressing cyber-enabled fraud and financial crime",
                                icon: "💰"
                            }
                        ].map((theme, index) => (
                            <div key={index} className="bg-green-50 p-8 rounded-xl border border-green-100 hover:shadow-lg transition-shadow duration-300">
                                <div className="text-4xl mb-4">{theme.icon}</div>
                                <h3 className="text-xl font-semibold text-gray-900 mb-3">{theme.title}</h3>
                                <p className="text-gray-