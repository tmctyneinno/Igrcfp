import { Head } from '@inertiajs/react';
import React, { useState } from 'react';

export default function AllCourses() {
    const [activeCategory, setActiveCategory] = useState('all');
    const [searchTerm, setSearchTerm] = useState('');

    const allCourses = [
        // GRC Courses
        {
            id: 1,
            title: "Advanced Diploma in Governance, Risk, Compliance & Financial Crime Prevention",
            category: 'grc',
            duration: "12 months",
            level: "Advanced",
            description: "Comprehensive programme covering integrated GRC frameworks and financial crime prevention",
            pathway: "GRC & Risk Management"
        },
        {
            id: 2,
            title: "Enterprise Risk Management for Senior Leaders",
            category: 'grc',
            duration: "3 months",
            level: "Executive",
            description: "Strategic risk management for board members and C-suite executives",
            pathway: "GRC & Risk Management"
        },
        {
            id: 3,
            title: "Board Governance, Accountability & Ethical Leadership",
            category: 'grc',
            duration: "2 months",
            level: "Board Level",
            description: "Governance frameworks and ethical leadership for board directors",
            pathway: "GRC & Risk Management"
        },
        {
            id: 4,
            title: "Compliance Management Systems (CMS) Design & Implementation",
            category: 'grc',
            duration: "4 months",
            level: "Professional",
            description: "Practical course on designing and implementing effective compliance systems",
            pathway: "GRC & Risk Management"
        },
        {
            id: 5,
            title: "Regulatory Change & Horizon Scanning",
            category: 'grc',
            duration: "2 months",
            level: "Professional",
            description: "Proactive approach to regulatory changes and emerging risks",
            pathway: "GRC & Risk Management"
        },
        {
            id: 6,
            title: "ESG, Sustainability & Conduct Risk",
            category: 'grc',
            duration: "3 months",
            level: "Advanced",
            description: "Integrating ESG factors and conduct risk into GRC frameworks",
            pathway: "GRC & Risk Management"
        },

        // Financial Crime Courses
        {
            id: 7,
            title: "Advanced AML & Financial Intelligence",
            category: 'financial-crime',
            duration: "4 months",
            level: "Advanced",
            description: "Comprehensive anti-money laundering frameworks and financial intelligence gathering",
            pathway: "Financial Crime Prevention"
        },
        {
            id: 8,
            title: "Fraud, Corruption & Integrity Systems",
            category: 'financial-crime',
            duration: "3 months",
            level: "Professional",
            description: "Detection, prevention, and investigation of fraud, bribery, and corruption",
            pathway: "Financial Crime Prevention"
        },
        {
            id: 9,
            title: "Sanctions Risk, Screening & Geopolitical Exposure",
            category: 'financial-crime',
            duration: "2 months",
            level: "Professional",
            description: "Global sanctions compliance and geopolitical risk management",
            pathway: "Financial Crime Prevention"
        },
        {
            id: 10,
            title: "Investigations, Enforcement & Cross-Border Cooperation",
            category: 'financial-crime',
            duration: "3 months",
            level: "Advanced",
            description: "Financial crime investigation techniques and international cooperation",
            pathway: "Financial Crime Prevention"
        },
        {
            id: 11,
            title: "Financial Crime Risk Assessment & Controls",
            category: 'financial-crime',
            duration: "3 months",
            level: "Professional",
            description: "Risk assessment methodologies and control frameworks",
            pathway: "Financial Crime Prevention"
        },
        {
            id: 12,
            title: "Emerging Financial Crime Threats",
            category: 'financial-crime',
            duration: "2 months",
            level: "Advanced",
            description: "New and evolving financial crime typologies including digital assets",
            pathway: "Financial Crime Prevention"
        },

        // Crypto & Digital Assets Courses
        {
            id: 13,
            title: "Crypto-Asset Regulation & Compliance",
            category: 'crypto',
            duration: "3 months",
            level: "Professional",
            description: "Regulatory frameworks for crypto-assets across jurisdictions",
            pathway: "Crypto & Digital Assets"
        },
        {
            id: 14,
            title: "AML, Sanctions & Financial Crime Risks in Crypto",
            category: 'crypto',
            duration: "3 months",
            level: "Advanced",
            description: "Financial crime risks specific to crypto-assets and preventive measures",
            pathway: "Crypto & Digital Assets"
        },
        {
            id: 15,
            title: "Blockchain Technology: Governance, Risk & Controls",
            category: 'crypto',
            duration: "4 months",
            level: "Professional",
            description: "Understanding blockchain technology and implementing governance frameworks",
            pathway: "Crypto & Digital Assets"
        },
        {
            id: 16,
            title: "Decentralized Finance (DeFi) Risk & Oversight",
            category: 'crypto',
            duration: "3 months",
            level: "Advanced",
            description: "Risk management and oversight frameworks for DeFi protocols",
            pathway: "Crypto & Digital Assets"
        },
        {
            id: 17,
            title: "Virtual Asset Service Provider (VASP) Compliance",
            category: 'crypto',
            duration: "3 months",
            level: "Professional",
            description: "Compliance requirements and frameworks for VASPs",
            pathway: "Crypto & Digital Assets"
        },
        {
            id: 18,
            title: "Crypto Custody & Security Solutions",
            category: 'crypto',
            duration: "2 months",
            level: "Professional",
            description: "Custody solutions and security frameworks for crypto assets",
            pathway: "Crypto & Digital Assets"
        },

        // Cybersecurity Courses
        {
            id: 19,
            title: "Cyber Risk Governance for Boards & Executives",
            category: 'cybersecurity',
            duration: "2 months",
            level: "Executive",
            description: "Strategic cyber risk oversight and governance for senior leadership",
            pathway: "Cybersecurity & Digital Risk"
        },
        {
            id: 20,
            title: "Cybercrime, Fraud & Digital Threats",
            category: 'cybersecurity',
            duration: "3 months",
            level: "Professional",
            description: "Understanding and mitigating cyber-enabled financial crime and fraud",
            pathway: "Cybersecurity & Digital Risk"
        },
        {
            id: 21,
            title: "Data Protection, Privacy & Regulatory Compliance",
            category: 'cybersecurity',
            duration: "4 months",
            level: "Professional",
            description: "Global data protection regulations and privacy compliance frameworks",
            pathway: "Cybersecurity & Digital Risk"
        },
        {
            id: 22,
            title: "Technology Risk Management & Operational Resilience",
            category: 'cybersecurity',
            duration: "3 months",
            level: "Advanced",
            description: "Managing technology risks and building operational resilience",
            pathway: "Cybersecurity & Digital Risk"
        },
        {
            id: 23,
            title: "Incident Response, Breach Management & Regulatory Reporting",
            category: 'cybersecurity',
            duration: "3 months",
            level: "Professional",
            description: "Effective incident response and regulatory compliance during cyber incidents",
            pathway: "Cybersecurity & Digital Risk"
        },
        {
            id: 24,
            title: "Third-Party & Supply Chain Cyber Risk",
            category: 'cybersecurity',
            duration: "2 months",
            level: "Professional",
            description: "Managing cyber risks across supply chains and third-party relationships",
            pathway: "Cybersecurity & Digital Risk"
        },

        // AI & Emerging Technology Courses
        {
            id: 25,
            title: "AI Governance, Ethics & Accountability",
            category: 'ai',
            duration: "3 months",
            level: "Executive",
            description: "Governance frameworks and ethical considerations for AI deployment",
            pathway: "AI & Emerging Technology"
        },
        {
            id: 26,
            title: "Algorithmic Risk, Bias & Explainability",
            category: 'ai',
            duration: "3 months",
            level: "Advanced",
            description: "Identifying and mitigating risks in algorithmic decision-making",
            pathway: "AI & Emerging Technology"
        },
        {
            id: 27,
            title: "RegTech & SupTech Applications",
            category: 'ai',
            duration: "3 months",
            level: "Professional",
            description: "Regulatory technology applications and supervisory technology implementation",
            pathway: "AI & Emerging Technology"
        },
        {
            id: 28,
            title: "Data Governance, Ownership & Protection",
            category: 'ai',
            duration: "4 months",
            level: "Professional",
            description: "Comprehensive data governance frameworks for AI and advanced analytics",
            pathway: "AI & Emerging Technology"
        },
        {
            id: 29,
            title: "Technology Ethics & Responsible Innovation",
            category: 'ai',
            duration: "2 months",
            level: "Executive",
            description: "Ethical frameworks for technology development and deployment",
            pathway: "AI & Emerging Technology"
        },
        {
            id: 30,
            title: "AI Regulatory Compliance & Risk Management",
            category: 'ai',
            duration: "3 months",
            level: "Professional",
            description: "Regulatory requirements and risk management for AI systems",
            pathway: "AI & Emerging Technology"
        }
    ];

    const categories = [
        { id: 'all', name: 'All Courses', count: allCourses.length },
        { id: 'grc', name: 'GRC & Risk Management', count: allCourses.filter(c => c.category === 'grc').length },
        { id: 'financial-crime', name: 'Financial Crime Prevention', count: allCourses.filter(c => c.category === 'financial-crime').length },
        { id: 'crypto', name: 'Crypto & Digital Assets', count: allCourses.filter(c => c.category === 'crypto').length },
        { id: 'cybersecurity', name: 'Cybersecurity & Digital Risk', count: allCourses.filter(c => c.category === 'cybersecurity').length },
        { id: 'ai', name: 'AI & Emerging Technology', count: allCourses.filter(c => c.category === 'ai').length }
    ];

    const filteredCourses = allCourses.filter(course => {
        const matchesCategory = activeCategory === 'all' || course.category === activeCategory;
        const matchesSearch = searchTerm === '' || 
            course.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
            course.description.toLowerCase().includes(searchTerm.toLowerCase()) ||
            course.pathway.toLowerCase().includes(searchTerm.toLowerCase());
        
        return matchesCategory && matchesSearch;
    });

    const getCategoryColor = (category) => {
        switch(category) {
            case 'grc': return 'bg-blue-100 text-blue-800 border-blue-200';
            case 'financial-crime': return 'bg-red-100 text-red-800 border-red-200';
            case 'crypto': return 'bg-purple-100 text-purple-800 border-purple-200';
            case 'cybersecurity': return 'bg-green-100 text-green-800 border-green-200';
            case 'ai': return 'bg-yellow-100 text-yellow-800 border-yellow-200';
            default: return 'bg-gray-100 text-gray-800 border-gray-200';
        }
    };

    const getLevelColor = (level) => {
        switch(level) {
            case 'Executive': return 'bg-purple-100 text-purple-800';
            case 'Board Level': return 'bg-yellow-100 text-yellow-800';
            case 'Advanced': return 'bg-red-100 text-red-800';
            case 'Professional': return 'bg-blue-100 text-blue-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    };

    return (
        <>
            <Head title="All Courses | IGRCFP Programmes" />
            
            {/* Hero Section */}
            <div className="relative bg-gradient-to-r from-blue-900 to-blue-800 py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <h1 className="text-4xl md:text-5xl font-bold text-white mb-6">
                            Complete Course Catalog
                        </h1>
                        <p className="text-xl text-blue-100 max-w-3xl mx-auto">
                            Browse our comprehensive collection of professional programmes in risk, regulation, and technology
                        </p>
                    </div>
                </div>
            </div>

            {/* Search and Filter Section */}
            <div className="py-8 bg-white border-b border-gray-200">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div className="flex-1">
                            <div className="relative">
                                <input
                                    type="text"
                                    placeholder="Search courses by title, description, or pathway..."
                                    className="w-full px-4 py-3 pl-12 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    value={searchTerm}
                                    onChange={(e) => setSearchTerm(e.target.value)}
                                />
                                <svg className="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <div className="text-sm text-gray-600">
                            Showing {filteredCourses.length} of {allCourses.length} courses
                        </div>
                    </div>
                </div>
            </div>

            {/* Category Filter */}
            <div className="py-6 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-wrap gap-2">
                        {categories.map((category) => (
                            <button
                                key={category.id}
                                onClick={() => setActiveCategory(category.id)}
                                className={`px-4 py-2 rounded-full text-sm font-medium transition-colors ${
                                    activeCategory === category.id
                                        ? 'bg-blue-900 text-white'
                                        : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'
                                }`}
                            >
                                {category.name} ({category.count})
                            </button>
                        ))}
                    </div>
                </div>
            </div>

            {/* Courses Grid */}
            <div className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {filteredCourses.length === 0 ? (
                        <div className="text-center py-12">
                            <svg className="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 className="text-xl font-semibold text-gray-900 mb-2">No courses found</h3>
                            <p className="text-gray-600">Try adjusting your search or filter criteria</p>
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            {filteredCourses.map((course) => (
                                <div key={course.id} className="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                    <div className="p-6">
                                        <div className="flex justify-between items-start mb-4">
                                            <span className={`px-3 py-1 rounded-full text-xs font-semibold ${getLevelColor(course.level)}`}>
                                                {course.level}
                                            </span>
                                            <span className="text-sm text-gray-500">{course.duration}</span>
                                        </div>
                                        
                                        <h3 className="text-xl font-bold text-gray-900 mb-3">{course.title}</h3>
                                        
                                        <div className="mb-4">
                                            <span className={`inline-block px-3 py-1 rounded-full text-xs font-semibold border ${getCategoryColor(course.category)}`}>
                                                {course.pathway}
                                            </span>
                                        </div>
                                        
                                        <p className="text-gray-600 mb-6">{course.description}</p>
                                        
                                        <div className="flex items-center justify-between">
                                            <a 
                                                href={`/programmes/${course.category}`}
                                                className="text-blue-900 font-medium hover:text-blue-700 inline-flex items-center"
                                            >
                                                View Pathway
                                                <svg className="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                            <button className="bg-blue-900 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-800 transition duration-300">
                                                Course Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>

            {/* How Courses Are Delivered */}
            <div className="py-16 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">How Our Courses Are Delivered</h2>
                        <p className="text-gray-600 text-lg">Flexible learning options to suit your needs</p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                        {[
                            {
                                title: "Live Online Sessions",
                                description: "Interactive virtual classrooms with expert instructors",
                                icon: "💻"
                            },
                            {
                                title: "Classroom Delivery",
                                description: "In-person sessions at our training centers",
                                icon: "🏫"
                            },
                            {
                                title: "Blended Learning",
                                description: "Combination of online and in-person instruction",
                                icon: "🔄"
                            },
                            {
                                title: "Organizational Training",
                                description: "Custom programmes for corporate teams",
                                icon: "🏢"
                            }
                        ].map((method, index) => (
                            <div key={index} className="bg-white p-8 rounded-xl shadow-lg border border-gray-200 text-center">
                                <div className="text-4xl mb-4">{method.icon}</div>
                                <h3 className="text-xl font-semibold text-gray-900 mb-3">{method.title}</h3>
                                <p className="text-gray-600">{method.description}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* Why These Courses Matter */}
            <div className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-gradient-to-r from-blue-900 to-blue-800 rounded-2xl p-8 md:p-12">
                        <div className="text-center">
                            <h2 className="text-3xl font-bold text-white mb-6">Why These Courses Matter</h2>
                            <p className="text-blue-100 text-lg mb-8 max-w-3xl mx-auto">
                                Regulation, risk, and financial crime are no longer siloed. Crypto, cybercrime, AI, 
                                sanctions, and data misuse now intersect with governance failures, regulatory breaches, 
                                financial crime exposure, and reputational risk.
                            </p>
                            <p className="text-blue-100 text-lg mb-8 max-w-3xl mx-auto">
                                IGRCFP courses are designed to help professionals understand these intersections, 
                                not just individual rules.
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="/contact" className="bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition duration-300">
                                    Contact Admissions
                                </a>
                                <a href="/programmes" className="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-900 transition duration-300">
                                    View Programme Pathways
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}