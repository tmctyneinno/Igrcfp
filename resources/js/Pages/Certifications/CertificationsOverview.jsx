import React from 'react';
import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { 
    ShieldCheckIcon, 
    AcademicCapIcon, 
    DocumentTextIcon,
    GlobeAltIcon,
    UserGroupIcon,
    ArrowRightIcon,
    CheckCircleIcon
} from '@heroicons/react/24/outline';

export default function CertificationsOverview({ auth, courses = [] }) {
    const certificationPrograms = [
        {
            id: 'cgfcs',
            title: 'Certified GRC & Financial Crime Specialist (CGFCS)',
            description: 'IGRCFP\'s flagship professional certification developing advanced knowledge across governance, risk management, compliance, and financial crime prevention.',
            icon: ShieldCheckIcon,
            color: 'bg-blue-900',
            href: route('cgfcs.specialist'),
            featured: true
        },
        {
            id: 'advanced-diploma',
            title: 'IGRCFP Advanced Diploma',
            description: 'Comprehensive professional education covering the full IGRCFP framework: governance, risk, compliance, and financial crime prevention.',
            icon: AcademicCapIcon,
            color: 'bg-indigo-700',
            href: route('certifications.pathway'),
            featured: true
        },
        {
            id: 'tbml',
            title: 'Certificate in Trade-Based Money Laundering (TBML)',
            description: 'Specialist training in identifying, analysing, and mitigating TBML risks within global trade systems.',
            icon: DocumentTextIcon,
            color: 'bg-emerald-700',
            href: route('certifications.pathway'),
            featured: false
        }
    ];
 
    const specialistAreas = [
        {
            title: 'Financial Crime & Regulatory Compliance',
            topics: ['Anti-Money Laundering (AML)', 'Fraud prevention and investigations', 'Sanctions risk management', 'Cross-border financial crime']
        },
        {
            title: 'Crypto, Digital Assets & Blockchain Risk',
            topics: ['Crypto-asset regulation', 'Financial crime risks in digital assets', 'Blockchain governance', 'VASP compliance']
        },
        {
            title: 'Cybersecurity & Digital Risk Governance',
            topics: ['Cyber risk management', 'Cybercrime and digital fraud', 'Data protection compliance', 'Operational resilience']
        },
        {
            title: 'AI, Data & Emerging Technology Governance',
            topics: ['AI governance', 'Algorithmic risk and bias', 'RegTech applications', 'Data governance and ethics']
        }
    ];

    const targetAudience = [
        'Compliance officers',
        'Risk managers',
        'AML and financial crime specialists',
        'Regulatory professionals',
        'Trade finance and banking professionals',
        'Investigators and financial intelligence analysts',
        'Corporate governance professionals',
        'Technology risk and cybersecurity specialists',
        'Consultants and advisors'
    ];

    return (
        <GuestLayout auth={auth}>
            <Head title="Professional Certifications | IGRCFP" />
            
            {/* Hero Section */}
            
             <section className="w-full bg-gradient-to-r from-blue-200 via-white to-blue-200 py-28">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 className="text-4xl md:text-5xl font-bold mb-4"> IGRCFP Certifications</h1>
                    <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                        IGRCFP credentials: from specialist certificates to advanced diplomas and the flagship CGFCS designation
                    </p>
                    <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                        The Institute of GRC & Financial Crime Prevention (IGRCFP) offers a portfolio of 
                        professional certifications designed to develop advanced capability in governance, 
                        risk management, regulatory compliance, and financial crime prevention.
                    </p>
                </div>
            </section>

            {/* Why Certifications Matter */}
            <section className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Why IGRCFP Certifications Matter
                        </h2>
                        <p className="text-xl text-gray-600 max-w-4xl mx-auto">
                            Modern organisations operate within an increasingly complex risk environment shaped by:
                        </p>
                    </div>
                    
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        {[
                            { title: 'Rapid Regulatory Change', icon: '📋' },
                            { title: 'Cross-border Financial Activity', icon: '🌍' },
                            { title: 'Digital Transformation', icon: '💻' },
                            { title: 'Sophisticated Financial Crime', icon: '🔒' }
                        ].map((item, index) => (
                            <div key={index} className="bg-gray-50 rounded-xl p-6 text-center">
                                <div className="text-4xl mb-3">{item.icon}</div>
                                <h3 className="font-semibold text-gray-900">{item.title}</h3>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Certification Philosophy */}
            <section className="py-16 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Certification Philosophy
                        </h2>
                        <p className="text-lg text-gray-600 max-w-3xl mx-auto">
                            IGRCFP programmes are designed around three core principles:
                        </p>
                    </div>
                    
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div className="bg-white rounded-xl p-8 shadow-sm">
                            <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                                <GlobeAltIcon className="w-6 h-6 text-blue-900" />
                            </div>
                            <h3 className="text-xl font-bold text-gray-900 mb-3">Integrated Systems Thinking</h3>
                            <p className="text-gray-600">
                                Governance, risk, compliance, and financial crime prevention are interdependent disciplines. 
                                IGRCFP certifications teach professionals how these systems operate together rather than in isolation.
                            </p>
                        </div>
                        
                        <div className="bg-white rounded-xl p-8 shadow-sm">
                            <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                                <CheckCircleIcon className="w-6 h-6 text-blue-900" />
                            </div>
                            <h3 className="text-xl font-bold text-gray-900 mb-3">Practical Professional Capability</h3>
                            <p className="text-gray-600">
                                Courses focus on real-world scenarios, regulatory expectations, operational frameworks, 
                                and investigative techniques used by professionals in practice.
                            </p>
                        </div>
                        
                        <div className="bg-white rounded-xl p-8 shadow-sm">
                            <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                                <UserGroupIcon className="w-6 h-6 text-blue-900" />
                            </div>
                            <h3 className="text-xl font-bold text-gray-900 mb-3">Global Relevance</h3>
                            <p className="text-gray-600">
                                IGRCFP programmes reflect international standards, cross-border regulatory environments, 
                                and global financial systems.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* Certification Portfolio */}
            <section className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            IGRCFP Certification Portfolio
                        </h2>
                        <p className="text-lg text-gray-600 max-w-3xl mx-auto">
                            IGRCFP certifications support professional development across multiple areas of 
                            governance, regulatory compliance, risk management, and financial crime prevention.
                        </p>
                    </div>
                    
                    <div className="space-y-6">
                        {/* Featured Programs */}
                        {certificationPrograms.filter(p => p.featured).map((program, index) => {
                            const Icon = program.icon;
                            return (
                                <Link 
                                    key={program.id}
                                    href={program.href}
                                    className="block bg-gradient-to-r from-gray-50 to-white rounded-2xl p-8 border border-gray-200 hover:shadow-lg transition-all hover:border-blue-300"
                                >
                                    <div className="flex items-start gap-6">
                                        <div className={`${program.color} p-4 rounded-xl flex-shrink-0`}>
                                            <Icon className="w-8 h-8 text-white" />
                                        </div>
                                        <div className="flex-1">
                                            <h3 className="text-2xl font-bold text-gray-900 mb-3">
                                                {program.title}
                                            </h3>
                                            <p className="text-gray-600 mb-4">
                                                {program.description}
                                            </p>
                                            <span className="inline-flex items-center text-blue-900 font-medium">
                                                Learn more <ArrowRightIcon className="w-4 h-4 ml-2" />
                                            </span>
                                        </div>
                                    </div>
                                </Link>
                            );
                        })}
                        
                        {/* TBML Program */}
                        <Link 
                            href={route('certifications.pathway')}
                            className="block bg-white rounded-2xl p-8 border border-gray-200 hover:shadow-lg transition-all hover:border-blue-300"
                        >
                            <div className="flex items-start gap-6">
                                <div className="bg-emerald-700 p-4 rounded-xl flex-shrink-0">
                                    <DocumentTextIcon className="w-8 h-8 text-white" />
                                </div>
                                <div className="flex-1">
                                    <h3 className="text-2xl font-bold text-gray-900 mb-3">
                                        IGRCFP Certificate in Trade-Based Money Laundering (TBML)
                                    </h3>
                                    <p className="text-gray-600 mb-4">
                                        The IGRCFP Certificate in Trade-Based Money Laundering provides specialist training 
                                        in identifying, analysing, and mitigating TBML risks within global trade systems.
                                    </p>
                                    <p className="text-gray-500 text-sm mb-3">
                                        Topics include: Trade finance mechanisms, TBML typologies and red flags, 
                                        trade documentation analysis, sanctions and proliferation risks.
                                    </p>
                                    <span className="inline-flex items-center text-blue-900 font-medium">
                                        Learn more <ArrowRightIcon className="w-4 h-4 ml-2" />
                                    </span>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </section>

            {/* Specialist Areas */}
            <section className="py-16 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Specialist Certification Areas
                        </h2>
                        <p className="text-lg text-gray-600 max-w-3xl mx-auto">
                            In addition to flagship programmes, IGRCFP offers specialist certification courses 
                            addressing emerging regulatory and financial crime risks.
                        </p>
                    </div>
                    
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {specialistAreas.map((area, index) => (
                            <div key={index} className="bg-white rounded-xl p-6 shadow-sm">
                                <h3 className="text-xl font-bold text-gray-900 mb-4">{area.title}</h3>
                                <ul className="space-y-2">
                                    {area.topics.map((topic, idx) => (
                                        <li key={idx} className="flex items-start gap-2">
                                            <CheckCircleIcon className="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" />
                                            <span className="text-gray-600">{topic}</span>
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Who Should Pursue */}
            <section className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Who Should Pursue IGRCFP Certifications
                        </h2>
                        <p className="text-lg text-gray-600 max-w-3xl mx-auto">
                            IGRCFP certifications are designed for professionals working in roles such as:
                        </p>
                    </div>
                    
                    <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        {targetAudience.map((role, index) => (
                            <div key={index} className="bg-blue-50 rounded-lg p-4 text-center">
                                <span className="text-gray-800 font-medium">{role}</span>
                            </div>
                        ))}
                    </div>
                    
                    <p className="text-center text-gray-600 mt-8">
                        The programmes are also suitable for senior professionals seeking to strengthen strategic 
                        oversight of risk, compliance, and financial crime exposure.
                    </p>
                </div>
            </section>

            {/* Learning Approach */}
            <section className="py-16 bg-gradient-to-r from-blue-900 to-indigo-900 text-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl md:text-4xl font-bold mb-4">Learning Approach</h2>
                        <p className="text-xl text-blue-100 max-w-3xl mx-auto">
                            IGRCFP certification programmes combine:
                        </p>
                    </div>
                    
                    <div className="grid grid-cols-2 md:grid-cols-5 gap-4 text-center">
                        {[
                            'Instructor-led teaching',
                            'Case study analysis',
                            'Real-world scenarios',
                            'Document analysis',
                            'Applied exercises'
                        ].map((item, index) => (
                            <div key={index} className="bg-white/10 backdrop-blur rounded-lg p-4">
                                <span className="text-white">{item}</span>
                            </div>
                        ))}
                    </div>
                    
                    <p className="text-center text-blue-100 mt-8 text-lg">
                        Learners develop both technical knowledge and practical decision-making capability.
                    </p>
                </div>
            </section>

            {/* Professional Recognition & Pathway */}
            <section className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div className="bg-gray-50 rounded-2xl p-8">
                            <ShieldCheckIcon className="w-12 h-12 text-blue-900 mb-4" />
                            <h3 className="text-2xl font-bold text-gray-900 mb-4">Professional Recognition</h3>
                            <p className="text-gray-600 mb-4">
                                IGRCFP certifications are issued as independent professional credentials recognising 
                                competence and capability in governance, risk, compliance, and financial crime prevention.
                            </p>
                            <p className="text-gray-600">
                                These credentials demonstrate that holders have developed knowledge and skills aligned 
                                with global best practices and professional expectations in the field.
                            </p>
                        </div>
                        
                        <div className="bg-gray-50 rounded-2xl p-8">
                            <AcademicCapIcon className="w-12 h-12 text-blue-900 mb-4" />
                            <h3 className="text-2xl font-bold text-gray-900 mb-4">Pathway to IGRCFP Fellowship</h3>
                            <p className="text-gray-600 mb-4">
                                Professionals who complete advanced certifications and demonstrate significant 
                                professional experience may become eligible for IGRCFP Fellowship.
                            </p>
                            <p className="text-gray-600">
                                Fellowship recognises leadership and contribution to the GRC and financial crime 
                                prevention profession.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* Community CTA */}
            <section className="py-16 bg-blue-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <GlobeAltIcon className="w-16 h-16 text-blue-900 mx-auto mb-6" />
                    <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        A Global Community of Professionals
                    </h2>
                    <p className="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                        IGRCFP certification holders become part of a growing international professional community 
                        committed to strengthening governance systems, protecting financial integrity, and preventing 
                        financial crime.
                    </p>
                    <Link
                        href={route('membership')}
                        className="inline-flex items-center px-8 py-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 transition"
                    >
                        Explore Membership
                        <ArrowRightIcon className="w-5 h-5 ml-2" />
                    </Link>
                </div>
            </section>
        </GuestLayout>
    );
}