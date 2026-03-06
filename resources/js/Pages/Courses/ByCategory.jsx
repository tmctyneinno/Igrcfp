import { Head, Link, usePage } from '@inertiajs/react';
import React from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import CourseCard from '@/components/Courses/CourseCard'; // Make sure to import CourseCard

export default function Index({ auth, courses, filters, filterOptions, category }) {
    const { url } = usePage();

    // Get category info from props (passed from controller)
    const currentCategory = category || null;
    const categoryName = currentCategory?.name || 'All';

    // Category-specific content mapping
    const categoryContent = {
        1: { // GRC
            title: "Governance, Risk & Compliance (GRC)",
            description: "Designed for professionals responsible for organisational oversight, risk management, and regulatory compliance",
            overview: "The GRC pathway provides comprehensive education for professionals managing governance structures, risk frameworks, and compliance obligations in today's complex regulatory environment.",
            targetAudience: [
                "Risk Managers",
                "Compliance Officers",
                "Board Advisors",
                "Internal Auditors",
                "Executives",
                "Public Sector Leaders"
            ],
            features: [
                {
                    title: "Integrated Approach",
                    description: "Holistic view of governance, risk, and compliance as interconnected disciplines"
                },
                {
                    title: "Practical Implementation",
                    description: "Real-world case studies and implementation frameworks"
                },
                {
                    title: "Global Standards",
                    description: "Based on international best practices and regulatory frameworks"
                }
            ]
        },
        2: { // Financial Crime Prevention
            title: "Financial Crime Prevention",
            description: "Comprehensive training in anti-money laundering, fraud detection, and financial crime compliance",
            overview: "Our Financial Crime Prevention pathway equips professionals with the skills to detect, prevent, and report financial crimes in today's complex global financial system.",
            targetAudience: [
                "AML Analysts",
                "Fraud Investigators",
                "Compliance Officers",
                "Banking Professionals",
                "Law Enforcement",
                "Financial Regulators"
            ],
            features: [
                {
                    title: "Advanced Detection",
                    description: "Cutting-edge techniques for identifying suspicious activities"
                },
                {
                    title: "Regulatory Compliance",
                    description: "Stay current with global AML/CFT regulations"
                },
                {
                    title: "Practical Case Studies",
                    description: "Learn from real-world financial crime scenarios"
                }
            ]
        },
        3: { // Crypto & Digital Assets
            title: "Crypto & Digital Assets",
            description: "Specialized education in blockchain technology, cryptocurrency regulation, and digital asset management",
            overview: "Navigate the evolving landscape of digital assets with our comprehensive crypto and blockchain education program.",
            targetAudience: [
                "Crypto Compliance Officers",
                "Blockchain Developers",
                "Investment Professionals",
                "Regulatory Specialists",
                "Fintech Innovators",
                "Digital Asset Managers"
            ],
            features: [
                {
                    title: "Blockchain Fundamentals",
                    description: "Deep understanding of blockchain technology and applications"
                },
                {
                    title: "Regulatory Framework",
                    description: "Global crypto regulations and compliance requirements"
                },
                {
                    title: "Risk Management",
                    description: "Identify and mitigate risks in digital asset investments"
                }
            ]
        },
        4: { // Cybersecurity & Digital Risk
            title: "Cybersecurity & Digital Risk",
            description: "Comprehensive training in cybersecurity frameworks, threat detection, and digital risk management",
            overview: "Protect your organization from cyber threats with our industry-leading cybersecurity education program.",
            targetAudience: [
                "Security Analysts",
                "IT Managers",
                "Risk Officers",
                "Security Architects",
                "Compliance Professionals",
                "Business Leaders"
            ],
            features: [
                {
                    title: "Threat Detection",
                    description: "Advanced techniques for identifying security threats"
                },
                {
                    title: "Risk Frameworks",
                    description: "Implement NIST, ISO 27001, and other security standards"
                },
                {
                    title: "Incident Response",
                    description: "Develop effective security incident response plans"
                }
            ]
        },
        5: { // AI & Emerging Technology
            title: "AI & Emerging Technology",
            description: "Cutting-edge education in artificial intelligence, machine learning, and emerging tech governance",
            overview: "Stay ahead of the curve with our AI and emerging technology programs designed for forward-thinking professionals.",
            targetAudience: [
                "AI Ethics Officers",
                "Tech Leaders",
                "Innovation Managers",
                "Policy Makers",
                "Data Scientists",
                "Governance Professionals"
            ],
            features: [
                {
                    title: "AI Governance",
                    description: "Ethical frameworks for AI implementation"
                },
                {
                    title: "Emerging Tech Trends",
                    description: "Stay current with latest technology developments"
                },
                {
                    title: "Risk Assessment",
                    description: "Evaluate risks of emerging technologies"
                }
            ]
        }
    };

    // Get content for current category or use default
    const content = currentCategory ? categoryContent[currentCategory.id] || categoryContent[1] : categoryContent[1];

    return (
        <>
        <GuestLayout auth={auth}>
            <Head title={currentCategory ? `${content.title} | IGRCFP` : 'All Courses | IGRCFP'} />
            
            {/* Hero Section */}
            <div className="relative bg-gradient-to-r from-blue-900 to-blue-800 py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <div className="inline-flex items-center justify-center w-20 h-20 bg-white/10 rounded-full mb-6">
                            {currentCategory?.icon ? (
                                <svg className="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d={currentCategory.icon} />
                                </svg>
                            ) : (
                                <svg className="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            )}
                        </div>
                        <h1 className="text-4xl md:text-5xl font-bold text-white mb-6">
                            {content.title}
                        </h1>
                        <p className="text-xl text-blue-100 max-w-3xl mx-auto">
                            {content.description}
                        </p>
                    </div>
                </div>
            </div>

            {/* Overview Section - Only show when a category is selected */}
            {currentCategory && (
                <div className="py-16 bg-white">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            <div>
                                <h2 className="text-3xl font-bold text-gray-900 mb-6">Programme Overview</h2>
                                <p className="text-gray-600 text-lg mb-6">
                                    {content.overview}
                                </p>
                                <p className="text-gray-600 mb-8">
                                    Our courses bridge the gap between theoretical frameworks and practical 
                                    implementation, ensuring professionals can effectively manage organizational 
                                    risks while maintaining regulatory compliance and ethical standards.
                                </p>
                                
                                <div className="bg-blue-50 p-6 rounded-xl">
                                    <h3 className="text-xl font-semibold text-blue-900 mb-4">Who This Programme Is For</h3>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {content.targetAudience.map((audience, index) => (
                                            <li key={index} className="flex items-center">
                                                <svg className="w-5 h-5 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                                </svg>
                                                <span>{audience}</span>
                                            </li>
                                        ))}
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <div className="bg-gray-50 p-8 rounded-2xl border border-gray-200">
                                    <h3 className="text-2xl font-bold text-gray-900 mb-6">Key Programme Features</h3>
                                    <div className="space-y-6">
                                        {content.features.map((feature, index) => (
                                            <div key={index} className="flex items-start">
                                                <div className="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                                    <svg className="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4 className="text-lg font-semibold text-gray-900 mb-2">{feature.title}</h4>
                                                    <p className="text-gray-600">{feature.description}</p>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            )}

            {/* Courses Grid */}
            <div className="py-16 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">
                            {currentCategory ? content.title : 'All'} Courses
                        </h2>
                        <p className="text-gray-600 text-lg">
                            {courses.total > 0 
                                ? `Showing ${courses.total} course${courses.total > 1 ? 's' : ''}`
                                : 'No courses found in this category'}
                        </p>
                    </div>

                    {courses.data && courses.data.length > 0 ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {courses.data.map((course) => (
                                <CourseCard key={course.id} course={course} />
                            ))}
                        </div>
                    ) : (
                        <div className="text-center py-16 bg-white rounded-xl">
                            <svg className="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 className="text-xl font-semibold text-gray-900 mb-2">
                                No courses available
                            </h3>
                            <p className="text-gray-600 mb-6">
                                We're currently updating our {content.title} courses. Please check back soon.
                            </p>
                            <Link
                                href="/courses"
                                className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                            >
                                View All Courses
                            </Link>
                        </div>
                    )}

                    {/* Pagination */}
                    {courses.links && courses.links.length > 3 && (
                        <div className="mt-8 flex justify-center">
                            <nav className="flex items-center gap-2">
                                {courses.links.map((link, index) => (
                                    <Link
                                        key={index}
                                        href={link.url || '#'}
                                        className={`px-4 py-2 rounded-lg transition ${
                                            link.active
                                                ? 'bg-blue-600 text-white'
                                                : link.url
                                                ? 'bg-white text-gray-700 hover:bg-gray-50'
                                                : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                        }`}
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                        preserveState
                                        preserveScroll
                                    />
                                ))}
                            </nav>
                        </div>
                    )}
                </div>
            </div>

            {/* Certification Section */}
            <div className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-gradient-to-r from-blue-900 to-blue-800 rounded-2xl p-8 md:p-12">
                        <div className="text-center">
                            <h2 className="text-3xl font-bold text-white mb-6">Professional Recognition</h2>
                            <p className="text-blue-100 text-lg mb-8 max-w-3xl mx-auto">
                                Completion of our courses may lead to IGRCFP professional certifications and eligibility 
                                for specialized designations in your field of expertise.
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                <Link href="/certifications" className="bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition duration-300">
                                    Explore Certifications
                                </Link>
                                <Link href="/contact" className="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-900 transition duration-300">
                                    Contact Admissions
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </GuestLayout>
        </>
    );
}