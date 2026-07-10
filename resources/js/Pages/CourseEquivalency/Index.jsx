import React from 'react';
import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { motion } from "framer-motion";
import { fadeIn } from "@/utils/motionPresets";
import { 
    AcademicCapIcon,
    ArrowsUpDownIcon,
    ArrowRightIcon,
    ArrowTrendingUpIcon,
    BookOpenIcon,
    BriefcaseIcon,
    BuildingLibraryIcon,
    ChartBarIcon,
    CheckCircleIcon,
    ChevronDownIcon,
    ClockIcon,
    DocumentTextIcon,
    GlobeAltIcon,
    LightBulbIcon,
    ShieldCheckIcon,
    SparklesIcon,
    StarIcon,
    TrophyIcon,
    UserGroupIcon
} from '@heroicons/react/24/outline';
import { useState } from 'react';

export default function CourseEquivalency({ auth }) {
    
    const [activeTab, setActiveTab] = useState('mapping');

    const qualificationMapping = [
        {
            qualification: 'Certificate Courses',
            academic: 'Undergraduate Level (Year 1 / Foundation)',
            rqf: 'Level 4–5',
            entryLevel: 'Entry / Junior',
            cpd: '20–40 Hours',
            opportunities: 'Entry into Diploma / Professional roles',
            prerequisites: 'Basic education or relevant work experience',
            color: 'blue'
        },
        {
            qualification: 'Diploma (GRC / Financial Crime)',
            academic: 'Undergraduate Diploma (Year 2 Equivalent)',
            rqf: 'Level 5–6',
            entryLevel: 'Intermediate',
            cpd: '60–100 Hours',
            opportunities: 'Direct entry into Advanced Diploma or final year degree (top-up possible)',
            prerequisites: 'Prior certificate or 1–3 years experience',
            color: 'green'
        },
        {
            qualification: 'Advanced Diploma (GRC / Financial Crime)',
            academic: 'Final Year Undergraduate / Graduate Diploma',
            rqf: 'Level 6–7',
            entryLevel: 'Advanced / Senior',
            cpd: '100–150 Hours',
            opportunities: 'Direct entry into Postgraduate Diploma / MBA / MSc (subject to institution)',
            prerequisites: 'Diploma or 3–5 years professional experience',
            color: 'indigo'
        },
        {
            qualification: 'Postgraduate Diploma',
            academic: "Postgraduate Diploma / Master's Level (PGDip)",
            rqf: 'Level 7',
            entryLevel: 'Senior / Leadership',
            cpd: '150–200 Hours',
            opportunities: 'Direct entry into MSc / MBA dissertation stage (top-up)',
            prerequisites: 'Degree or significant professional experience',
            color: 'rose'
        },
        {
            qualification: 'Integrated GRC Postgraduate Diploma',
            academic: 'Executive Postgraduate Diploma / Pre-MBA',
            rqf: 'Level 7+',
            entryLevel: 'Executive',
            cpd: '200+ Hours',
            opportunities: 'Executive MBA / Doctorate pathway (case-by-case)',
            prerequisites: 'Senior leadership experience',
            color: 'violet'
        }
    ];

    const colorMap = {
        blue: {
            badge: 'bg-blue-100 text-blue-700 border-blue-200',
            icon: 'bg-blue-600',
            dot: 'bg-blue-500',
            light: 'bg-blue-50',
            border: 'border-l-blue-500'
        },
        green: {
            badge: 'bg-green-100 text-green-700 border-green-200',
            icon: 'bg-green-600',
            dot: 'bg-green-500',
            light: 'bg-green-50',
            border: 'border-l-green-500'
        },
        indigo: {
            badge: 'bg-indigo-100 text-indigo-700 border-indigo-200',
            icon: 'bg-indigo-600',
            dot: 'bg-indigo-500',
            light: 'bg-indigo-50',
            border: 'border-l-indigo-500'
        },
        rose: {
            badge: 'bg-rose-100 text-rose-700 border-rose-200',
            icon: 'bg-rose-600',
            dot: 'bg-rose-500',
            light: 'bg-rose-50',
            border: 'border-l-rose-500'
        },
        violet: {
            badge: 'bg-violet-100 text-violet-700 border-violet-200',
            icon: 'bg-violet-600',
            dot: 'bg-violet-500',
            light: 'bg-violet-50',
            border: 'border-l-violet-500'
        }
    };

    const entryPathways = [
        {
            title: 'Academic Pathway',
            icon: AcademicCapIcon,
            color: 'bg-blue-50 border-blue-200',
            iconColor: 'text-blue-700',
            routes: [
                'Degree → Postgraduate Diploma',
                'Diploma → Advanced Diploma → Postgraduate Diploma'
            ]
        },
        {
            title: 'Professional Experience Route',
            icon: BriefcaseIcon,
            color: 'bg-green-50 border-green-200',
            iconColor: 'text-green-700',
            routes: [
                '3–5 years experience → Diploma',
                '5–10 years experience → Advanced Diploma',
                '10+ years experience → Postgraduate Diploma'
            ]
        },
        {
            title: 'Fast-Track Route',
            icon: SparklesIcon,
            color: 'bg-amber-50 border-amber-200',
            iconColor: 'text-amber-700',
            routes: [
                'Direct entry into Advanced Diploma',
                'Direct entry into Postgraduate Diploma (via RPL)',
                'Recognition of Prior Learning'
            ]
        }
    ];

    const cpdData = [
        { qualification: 'Certificate', hours: '20–40', recognition: 'Entry-level professional CPD', color: 'blue' },
        { qualification: 'Diploma', hours: '60–100', recognition: 'Recognised CPD for practitioners', color: 'green' },
        { qualification: 'Advanced Diploma', hours: '100–150', recognition: 'Senior-level CPD', color: 'indigo' },
        { qualification: 'Postgraduate Diploma', hours: '150–200+', recognition: 'Executive / leadership CPD', color: 'rose' }
    ];

    const globalAlignments = [
        { title: 'UK Regulated Qualifications Framework (RQF)', icon: ShieldCheckIcon },
        { title: 'European Qualifications Framework (EQF)', icon: GlobeAltIcon },
        { title: 'Professional Certification Bodies (ICA, ACAMS equivalent)', icon: StarIcon },
        { title: 'Global Regulatory Expectations (FATF, ISO, Basel)', icon: BuildingLibraryIcon }
    ];

    const universityOpportunities = [
        {
            category: 'Direct Entry / Advanced Standing',
            icon: ArrowTrendingUpIcon,
            color: 'bg-blue-600',
            items: [
                'Entry into final year undergraduate programmes',
                'Entry into postgraduate diploma programmes',
                'Entry into MSc / MBA (with top-up modules)'
            ]
        },
        {
            category: 'Credit Transfer (Subject to Institution)',
            icon: ArrowsUpDownIcon,
            color: 'bg-green-600',
            items: [
                'Diploma → 60–120 credits equivalent',
                'Advanced Diploma → 120–180 credits equivalent',
                'Postgraduate Diploma → 180 credits (Master\'s equivalent stage)'
            ]
        },
        {
            category: 'Professional Recognition',
            icon: TrophyIcon,
            color: 'bg-indigo-600',
            items: [
                'Suitable for credit exemption discussions',
                'Supports university partnership agreements',
                'Aligns with executive education frameworks'
            ]
        }
    ];

    const prerequisites = [
        { level: 'Certificate', academic: 'Basic education / A-Level equivalent', experience: 'Optional' },
        { level: 'Diploma', academic: 'Certificate or equivalent', experience: '1–3 years' },
        { level: 'Advanced Diploma', academic: 'Diploma or degree', experience: '3–5 years' },
        { level: 'Postgraduate Diploma', academic: 'Degree or equivalent', experience: '5–10+ years' }
    ];

    return (
        <GuestLayout auth={auth}>
            <Head title="Course Equivalency & Academic Progression | IGRCFP" />
            
            {/* Hero Section */}
            <section className="w-full bg-[#0A1A2F] pt-24 pb-10  lg:pt-28 lg:pb-10">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid lg:grid-cols-2 gap-12 items-center">
                        <div>
                            
                            <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight text-white">
                                Course Equivalency & Academic Progression
                            </h1>
                            <p className="text-xl text-white-600 mb-8 leading-relaxed">
                                Understanding how IGRCFP qualifications align with international higher education 
                                frameworks, credit systems, and professional standards worldwide.
                            </p>
                            
                        </div>
                        <div className="hidden lg:block">
                            <div className="bg-white rounded-2xl p-8 border border-blue-100 shadow-xl">
                                <h3 className="text-sm font-bold text-blue-900 uppercase tracking-wider mb-6">
                                    Framework Alignment Summary
                                </h3>
                                <div className="space-y-4">
                                    {qualificationMapping.slice(0, 4).map((q, idx) => (
                                        <div key={idx} className="flex items-center gap-4">
                                            <div className={`w-3 h-3 rounded-full ${
                                                idx === 0 ? 'bg-blue-500' :
                                                idx === 1 ? 'bg-green-500' :
                                                idx === 2 ? 'bg-indigo-500' :
                                                'bg-rose-500'
                                            }`}></div>
                                            <div className="flex-1">
                                                <p className="text-sm font-semibold text-gray-900">{q.qualification}</p>
                                                <p className="text-xs text-gray-500">{q.rqf} • {q.cpd}</p>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                        {/* Bottom Tagline Bar */}
        
                    </div>
                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        transition={{ delay: 0.2 }}
                        className="mt-12 pt-4 border-t border-gray-700 flex flex-wrap gap-x-2 gap-y-2 text-xs uppercase tracking-wider text-gray-300"
                        >
                        <span>Terrorism Financing</span>
                        <span>•</span>
                        <span>KYC & CDD</span>
                        <span>•</span>
                        <span>Sanctions Compliance</span>
                        <span>•</span>
                        <span>Enterprise Risk Management</span>
                        <span>•</span>
                        <span>Regulatory Frameworks</span>
                        <span>•</span>
                        <span>ESG Sustainable Finance</span>
                        <span>•</span>
                        <span>AI in Compliance</span>
                    </motion.div>
                </div>
            </section>

            {/* Qualification Mapping Table - Compact Horizontal */}
            <section id="mapping-table" className="py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium mb-4">
                            <AcademicCapIcon className="w-4 h-4 mr-2" />
                            Qualification Mapping
                        </div>
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            IGRCFP Qualification Mapping Table
                        </h2>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            Complete alignment of IGRCFP qualifications with global academic frameworks and professional standards
                        </p>
                    </div>

                    <div className="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <table className="w-full">
                            <thead>
                                <tr className="bg-gradient-to-r from-blue-900 to-indigo-900 text-white">
                                    <th className="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider w-[18%]">
                                        Qualification
                                    </th>
                                    <th className="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider w-[18%]">
                                        Academic Equivalent
                                    </th>
                                    <th className="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider w-[10%]">
                                        RQF
                                    </th>
                                    <th className="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider w-[10%]">
                                        Level
                                    </th>
                                    <th className="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider w-[10%]">
                                        CPD
                                    </th>
                                    <th className="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider w-[20%]">
                                        Progression
                                    </th>
                                    <th className="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider w-[14%]">
                                        Prerequisites
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {qualificationMapping.map((row, index) => (
                                    <tr key={index} className={`hover:bg-gray-50 transition ${
                                        index % 2 === 0 ? 'bg-white' : 'bg-gray-50/30'
                                    }`}>
                                        <td className="px-4 py-4">
                                            <div className="flex items-center gap-2">
                                                <div className={`w-2 h-2 rounded-full flex-shrink-0 ${
                                                    index === 0 ? 'bg-blue-500' :
                                                    index === 1 ? 'bg-green-500' :
                                                    index === 2 ? 'bg-indigo-500' :
                                                    index === 3 ? 'bg-rose-500' :
                                                    'bg-violet-500'
                                                }`}></div>
                                                <span className="text-sm font-semibold text-gray-900">{row.qualification}</span>
                                            </div>
                                        </td>
                                        <td className="px-4 py-4 text-sm text-gray-700">{row.academic}</td>
                                        <td className="px-4 py-4 text-center">
                                            <span className={`inline-block text-xs font-bold px-2 py-1 rounded-full ${
                                                index === 0 ? 'bg-blue-50 text-blue-700' :
                                                index === 1 ? 'bg-green-50 text-green-700' :
                                                index === 2 ? 'bg-indigo-50 text-indigo-700' :
                                                index === 3 ? 'bg-rose-50 text-rose-700' :
                                                'bg-violet-50 text-violet-700'
                                            }`}>
                                                {row.rqf}
                                            </span>
                                        </td>
                                        <td className="px-4 py-4 text-center text-sm text-gray-700">{row.entryLevel}</td>
                                        <td className="px-4 py-4 text-center text-sm font-semibold text-gray-900">{row.cpd}</td>
                                        <td className="px-4 py-4 text-sm text-gray-700">{row.opportunities}</td>
                                        <td className="px-4 py-4 text-sm text-gray-700">{row.prerequisites}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            {/* Entry Pathways */}
            <section id="entry-pathways" className="py-20 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-medium mb-4">
                            <ArrowTrendingUpIcon className="w-4 h-4 mr-2" />
                            Entry Pathways
                        </div>
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            How Learners Progress
                        </h2>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            Multiple flexible pathways designed to accommodate diverse professional backgrounds
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {entryPathways.map((pathway, index) => {
                            const Icon = pathway.icon;
                            return (
                                <div key={index} className={`${pathway.color} rounded-2xl p-8 border-2 relative overflow-hidden`}>
                                    <div className="absolute top-0 right-0 w-32 h-32 bg-white/30 rounded-bl-full -mr-10 -mt-10"></div>
                                    <div className="relative">
                                        <div className={`w-14 h-14 bg-white rounded-xl flex items-center justify-center mb-6 shadow-sm`}>
                                            <Icon className={`w-7 h-7 ${pathway.iconColor}`} />
                                        </div>
                                        <h3 className="text-xl font-bold text-gray-900 mb-4">{pathway.title}</h3>
                                        <ul className="space-y-3">
                                            {pathway.routes.map((route, idx) => (
                                                <li key={idx} className="flex items-start gap-3">
                                                    <CheckCircleIcon className="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" />
                                                    <span className="text-gray-700 text-sm">{route}</span>
                                                </li>
                                            ))}
                                        </ul>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                </div>
            </section>

            {/* CPD Alignment */}
            <section className="py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-700 rounded-full text-sm font-medium mb-4">
                            <ClockIcon className="w-4 h-4 mr-2" />
                            CPD Alignment
                        </div>
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Continuing Professional Development
                        </h2>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        {cpdData.map((cpd, index) => (
                            <div key={index} className={`${
                                index === 0 ? 'bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200' :
                                index === 1 ? 'bg-gradient-to-br from-green-50 to-green-100 border-green-200' :
                                index === 2 ? 'bg-gradient-to-br from-indigo-50 to-indigo-100 border-indigo-200' :
                                'bg-gradient-to-br from-rose-50 to-rose-100 border-rose-200'
                            } rounded-2xl p-8 border text-center relative overflow-hidden`}>
                                <div className="absolute top-0 right-0 w-20 h-20 bg-white/40 rounded-bl-full -mr-8 -mt-8"></div>
                                <div className="relative">
                                    <p className="text-4xl md:text-5xl font-bold text-gray-900 mb-3">{cpd.hours}</p>
                                    <p className="text-lg font-semibold text-gray-800 mb-2">{cpd.qualification}</p>
                                    <div className="w-12 h-1 bg-gray-300 mx-auto mb-3 rounded-full"></div>
                                    <p className="text-sm text-gray-600">{cpd.recognition}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>
            

            {/* Global Recognition */}
            <section className="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium mb-4">
                            <GlobeAltIcon className="w-4 h-4 mr-2" />
                            Global Recognition
                        </div>
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Global Recognition Positioning
                        </h2>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            IGRCFP qualifications are designed to align with the world's most respected frameworks
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        {globalAlignments.map((alignment, index) => {
                            const Icon = alignment.icon;
                            return (
                                <div key={index} className="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-md transition text-center">
                                    <div className="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                                        <Icon className="w-8 h-8 text-blue-700" />
                                    </div>
                                    <p className="text-sm font-semibold text-gray-900 leading-relaxed">{alignment.title}</p>
                                </div>
                            );
                        })}
                    </div>
                </div>
            </section>

            {/* University Progression Opportunities */}
            <section className="py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full text-sm font-medium mb-4">
                            <BuildingLibraryIcon className="w-4 h-4 mr-2" />
                            University Opportunities
                        </div>
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            University & College Progression
                        </h2>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            IGRCFP qualifications support multiple progression routes into higher education
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {universityOpportunities.map((opportunity, index) => {
                            const Icon = opportunity.icon;
                            return (
                                <div key={index} className="bg-white rounded-2xl border-2 border-gray-100 p-8 hover:shadow-xl hover:border-blue-200 transition-all duration-300">
                                    <div className={`${opportunity.color} w-14 h-14 rounded-xl flex items-center justify-center mb-6`}>
                                        <Icon className="w-7 h-7 text-white" />
                                    </div>
                                    <h3 className="text-xl font-bold text-gray-900 mb-5">{opportunity.category}</h3>
                                    <ul className="space-y-3">
                                        {opportunity.items.map((item, idx) => (
                                            <li key={idx} className="flex items-start gap-3">
                                                <CheckCircleIcon className="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" />
                                                <span className="text-gray-600 text-sm">{item}</span>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            );
                        })}
                    </div>
                </div>
            </section>

            {/* Prerequisite Framework */}
            <section className="py-20 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-medium mb-4">
                            <DocumentTextIcon className="w-4 h-4 mr-2" />
                            Entry Requirements
                        </div>
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Prerequisite Framework
                        </h2>
                    </div>

                    <div className="max-w-3xl mx-auto">
                        <div className="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                            <div className="overflow-x-auto">
                                <table className="w-full">
                                    <thead>
                                        <tr className="bg-gray-50 border-b border-gray-200">
                                            <th className="px-6 py-4 text-left text-sm font-semibold text-gray-900">Level</th>
                                            <th className="px-6 py-4 text-left text-sm font-semibold text-gray-900">Academic Requirement</th>
                                            <th className="px-6 py-4 text-left text-sm font-semibold text-gray-900">Professional Experience</th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-gray-100">
                                        {prerequisites.map((row, index) => (
                                            <tr key={index} className="hover:bg-gray-50 transition">
                                                <td className="px-6 py-5">
                                                    <span className="font-semibold text-gray-900">{row.level}</span>
                                                </td>
                                                <td className="px-6 py-5 text-gray-700">{row.academic}</td>
                                                <td className="px-6 py-5">
                                                    <span className={`inline-block px-3 py-1 rounded-full text-sm font-medium ${
                                                        row.experience === 'Optional' ? 'bg-gray-100 text-gray-700' :
                                                        'bg-blue-50 text-blue-700'
                                                    }`}>
                                                        {row.experience}
                                                    </span>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div className="mt-8 bg-blue-50 rounded-2xl p-6 border border-blue-100">
                            <div className="flex items-start gap-4">
                                <div className="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <LightBulbIcon className="w-5 h-5 text-white" />
                                </div>
                                <div>
                                    <h4 className="font-semibold text-gray-900 mb-2">Recognition of Prior Learning (RPL)</h4>
                                    <p className="text-gray-600 text-sm">
                                        Experienced professionals may qualify for advanced entry through our RPL process, 
                                        which assesses professional experience, previous qualifications, and demonstrated 
                                        competence against IGRCFP learning outcomes.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Bottom CTA */}
            <section className="py-16 bg-blue-900">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl font-bold text-white mb-4">
                        Explore Your Progression Pathway
                    </h2>
                    <p className="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                        Find the right entry point for your professional background and career goals
                    </p>
                    <div className="flex flex-wrap justify-center gap-4">
                        <Link
                            href={route('qualifications.pack')}
                            className="inline-flex items-center px-8 py-4 bg-white text-blue-900 font-semibold rounded-xl hover:bg-blue-50 transition shadow-lg"
                        >
                            View Qualification Framework
                            <ArrowRightIcon className="w-5 h-5 ml-2" />
                        </Link>
                        <Link
                            href="/contact"
                            className="inline-flex items-center px-8 py-4 border-2 border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition"
                        >
                            Speak to an Advisor
                            <ArrowRightIcon className="w-5 h-5 ml-2" />
                        </Link>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}