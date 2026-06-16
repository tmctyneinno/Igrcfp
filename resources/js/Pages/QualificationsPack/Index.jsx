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
    CheckCircleIcon,
    BuildingLibraryIcon,
    BriefcaseIcon,
    ClockIcon,
    TrophyIcon,
    ArrowTrendingUpIcon,
    BookOpenIcon,
    CurrencyDollarIcon,
    BuildingOfficeIcon,
    StarIcon,
    ArrowsUpDownIcon,
    ChevronRightIcon,
    SparklesIcon
} from '@heroicons/react/24/outline';

export default function QualificationPartnershipPack({ auth }) {
    
    const qualificationLevels = [
        {
            level: 'Level 1',
            qualification: 'Certificate',
            academic: 'Undergraduate Foundation',
            rqf: 'Level 4–5',
            positioning: 'Entry / Foundation',
            credits: '20–40 Credits',
            cpdHours: '20–40',
            color: 'blue',
            icon: BookOpenIcon
        },
        {
            level: 'Level 2',
            qualification: 'Diploma',
            academic: 'Undergraduate Diploma',
            rqf: 'Level 5–6',
            positioning: 'Practitioner',
            credits: '60–120 Credits',
            cpdHours: '60–100',
            color: 'green',
            icon: AcademicCapIcon
        },
        {
            level: 'Level 3',
            qualification: 'Advanced Diploma',
            academic: 'Graduate Diploma / Final Year Degree',
            rqf: 'Level 6–7',
            positioning: 'Senior / Specialist',
            credits: '120–180 Credits',
            cpdHours: '100–150',
            color: 'indigo',
            icon: ShieldCheckIcon
        },
        {
            level: 'Level 4',
            qualification: 'Postgraduate Diploma',
            academic: "Master's Level (PGDip)",
            rqf: 'Level 7',
            positioning: 'Executive / Leadership',
            credits: '180 Credits',
            cpdHours: '150–200+',
            color: 'rose',
            icon: TrophyIcon
        }
    ];

    const colorMap = {
        blue: {
            bg: 'bg-blue-50',
            badge: 'bg-blue-100 text-blue-700',
            border: 'border-blue-200',
            icon: 'bg-blue-600',
            hover: 'hover:border-blue-300 hover:shadow-blue-100',
            gradient: 'from-blue-500 to-blue-600',
            light: 'bg-blue-50'
        },
        green: {
            bg: 'bg-green-50',
            badge: 'bg-green-100 text-green-700',
            border: 'border-green-200',
            icon: 'bg-green-600',
            hover: 'hover:border-green-300 hover:shadow-green-100',
            gradient: 'from-green-500 to-green-600',
            light: 'bg-green-50'
        },
        indigo: {
            bg: 'bg-indigo-50',
            badge: 'bg-indigo-100 text-indigo-700',
            border: 'border-indigo-200',
            icon: 'bg-indigo-600',
            hover: 'hover:border-indigo-300 hover:shadow-indigo-100',
            gradient: 'from-indigo-500 to-indigo-600',
            light: 'bg-indigo-50'
        },
        rose: {
            bg: 'bg-rose-50',
            badge: 'bg-rose-100 text-rose-700',
            border: 'border-rose-200',
            icon: 'bg-rose-600',
            hover: 'hover:border-rose-300 hover:shadow-rose-100',
            gradient: 'from-rose-500 to-rose-600',
            light: 'bg-rose-50'
        }
    };

    const progressionSteps = [
        { title: 'Certificate', subtitle: 'Foundation', level: 'L1' },
        { title: 'Diploma', subtitle: 'Practitioner', level: 'L2' },
        { title: 'Advanced Diploma', subtitle: 'Specialist', level: 'L3' },
        { title: 'Postgraduate Diploma', subtitle: 'Executive', level: 'L4' },
        { title: 'Leadership', subtitle: 'Advisory / Board', level: 'L5' }
    ];

    const universityProgression = [
        { igrcfp: 'Certificate', university: 'Year 1 Undergraduate', progression: 'Entry into Diploma / Degree' },
        { igrcfp: 'Diploma', university: 'Year 2 Undergraduate', progression: 'Final Year Degree / Advanced Diploma' },
        { igrcfp: 'Advanced Diploma', university: 'Final Year Degree', progression: 'MSc / MBA Entry' },
        { igrcfp: 'Postgraduate Diploma', university: "Master's Level", progression: 'MSc Dissertation / MBA Top-up' }
    ];

    const partnershipModels = [
        {
            title: 'Credit Recognition Partner',
            items: ['IGRCFP Diploma → university credit exemption', 'Advanced Diploma → direct MSc entry'],
            icon: BuildingLibraryIcon
        },
        {
            title: 'Joint Programme Delivery',
            items: ['Co-branded Diploma / MSc programmes', 'IGRCFP content embedded into degrees'],
            icon: UserGroupIcon
        },
        {
            title: 'Executive Education Partner',
            items: ['Short executive programmes', 'Board-level and regulatory training'],
            icon: BriefcaseIcon
        }
    ];

    const targetOrgansations = [
        { name: 'Banks & Financial Institutions', icon: BuildingOfficeIcon },
        { name: 'Regulators & Government Agencies', icon: ShieldCheckIcon },
        { name: 'Fintech & Digital Platforms', icon: SparklesIcon },
        { name: 'Consulting Firms', icon: BriefcaseIcon }
    ];

    const corporatePrograms = [
        {
            type: 'Certificate Cohort',
            price: '£3,000 – £5,000',
            description: 'Team training',
            color: 'bg-blue-50 border-blue-200'
        },
        {
            type: 'Diploma Programme',
            price: '£8,500 – £12,000',
            description: 'Specialist capability',
            color: 'bg-green-50 border-green-200'
        },
        {
            type: 'Executive Programme',
            price: '£15,000 – £100,000+',
            description: 'Enterprise transformation',
            color: 'bg-indigo-50 border-indigo-200'
        }
    ];

    const globalAlignments = [
        'ISO international standards',
        'FATF recommendations',
        'Global regulatory frameworks',
        'Basel Committee principles',
        'European Qualifications Framework (EQF)'
    ];

    return (
        <GuestLayout auth={auth} forceWhiteNavbar>
            <Head title="IGRCFP Global Qualification & Partnership Framework" />
            
            {/* Hero Section */}
            {/* Hero Section */}
            <section className="w-full bg-gradient-to-r from-blue-200 via-white to-blue-200 py-24 lg:py-32">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <div className="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium mb-6">
                                <GlobeAltIcon className="w-4 h-4 mr-2" />
                                Internationally Aligned Framework
                            </div>
                            <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight text-gray-900">
                                Global Qualification & Partnership Framework
                            </h1>
                            <p className="text-xl text-gray-600 mb-8 leading-relaxed">
                                A comprehensive professional development ecosystem from Certificate to Executive 
                                Leadership, aligned with international standards and university progression pathways.
                            </p>
                            <div className="flex flex-wrap gap-4">
                                <Link
                                    href="#qualification-framework"
                                    className="inline-flex items-center px-6 py-3 bg-blue-900 text-white font-semibold rounded-xl hover:bg-blue-800 transition shadow-lg hover:shadow-xl"
                                >
                                    Explore Framework
                                    <ArrowRightIcon className="w-5 h-5 ml-2" />
                                </Link>
                                <Link
                                    href="#partnerships"
                                    className="inline-flex items-center px-6 py-3 border-2 border-blue-900 text-blue-900 font-semibold rounded-xl hover:bg-blue-50 transition"
                                >
                                    Partnership Models
                                </Link>
                            </div>
                        </div>
                        <div className="hidden lg:block">
                            <div className="bg-white rounded-2xl p-8 border border-blue-100 shadow-xl">
                                <h3 className="text-sm font-bold text-blue-900 uppercase tracking-wider mb-4">
                                    Qualification Pathway
                                </h3>
                                <div className="space-y-4">
                                    {qualificationLevels.map((q, idx) => (
                                        <div key={idx} className="flex items-center gap-4">
                                            <div className={`w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm ${
                                                idx === 0 ? 'bg-blue-100 text-blue-700' :
                                                idx === 1 ? 'bg-green-100 text-green-700' :
                                                idx === 2 ? 'bg-indigo-100 text-indigo-700' :
                                                'bg-rose-100 text-rose-700'
                                            }`}>
                                                L{idx + 1}
                                            </div>
                                            <div>
                                                <p className="font-semibold text-gray-900">{q.qualification}</p>
                                                <p className="text-sm text-gray-500">{q.positioning}</p>
                                            </div>
                                            <ChevronRightIcon className="w-5 h-5 ml-auto text-gray-300" />
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Learning Pathway */}
            <section className="py-12 bg-white border-b">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-wrap items-center justify-center gap-2 md:gap-4">
                        {progressionSteps.map((step, index) => (
                            <React.Fragment key={index}>
                                <div className="text-center">
                                    <div className={`w-16 h-16 rounded-2xl ${
                                        index === 0 ? 'bg-blue-100' :
                                        index === 1 ? 'bg-green-100' :
                                        index === 2 ? 'bg-indigo-100' :
                                        index === 3 ? 'bg-rose-100' :
                                        'bg-violet-100'
                                    } flex items-center justify-center mx-auto mb-2`}>
                                        <span className={`font-bold text-lg ${
                                            index === 0 ? 'text-blue-700' :
                                            index === 1 ? 'text-green-700' :
                                            index === 2 ? 'text-indigo-700' :
                                            index === 3 ? 'text-rose-700' :
                                            'text-violet-700'
                                        }`}>{step.level}</span>
                                    </div>
                                    <p className="text-sm font-semibold text-gray-900">{step.title}</p>
                                    <p className="text-xs text-gray-500">{step.subtitle}</p>
                                </div>
                                {index < progressionSteps.length - 1 && (
                                    <ArrowTrendingUpIcon className="w-6 h-6 text-gray-300 flex-shrink-0" />
                                )}
                            </React.Fragment>
                        ))}
                    </div>
                </div>
            </section>

            {/* Qualification Framework */}
            <section id="qualification-framework" className="py-20 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium mb-4">
                            <AcademicCapIcon className="w-4 h-4 mr-2" />
                            Qualification Framework
                        </div>
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            IGRCFP Qualification Levels
                        </h2>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            Structured progression from foundation to executive leadership, aligned with 
                            the UK Regulated Qualifications Framework (RQF)
                        </p>
                    </div>

                    {/* Qualification Cards */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                        {qualificationLevels.map((q, index) => {
                            const Icon = q.icon;
                            const colors = colorMap[q.color];
                            return (
                                <div 
                                    key={index}
                                    className={`bg-white rounded-2xl border-2 ${colors.border} ${colors.hover} p-6 shadow-sm hover:shadow-xl transition-all duration-300 relative overflow-hidden`}
                                >
                                    <div className={`absolute top-0 right-0 w-24 h-24 ${colors.light} rounded-bl-full -mr-8 -mt-8 opacity-50`}></div>
                                    <div className={`${colors.icon} w-12 h-12 rounded-xl flex items-center justify-center mb-4 relative`}>
                                        <Icon className="w-6 h-6 text-white" />
                                    </div>
                                    <span className={`inline-block ${colors.badge} text-xs font-bold px-3 py-1 rounded-full mb-3`}>
                                        {q.level}
                                    </span>
                                    <h3 className="text-xl font-bold text-gray-900 mb-2">{q.qualification}</h3>
                                    <p className="text-gray-600 text-sm mb-4">{q.academic}</p>
                                    <div className="border-t border-gray-100 pt-4 space-y-2">
                                        <div className="flex items-center text-sm text-gray-500">
                                            <ArrowsUpDownIcon className="w-4 h-4 mr-2 text-gray-400" />
                                            RQF {q.rqf}
                                        </div>
                                        <div className="flex items-center text-sm text-gray-500">
                                            <StarIcon className="w-4 h-4 mr-2 text-gray-400" />
                                            {q.positioning}
                                        </div>
                                        <div className="flex items-center text-sm text-gray-500">
                                            <ClockIcon className="w-4 h-4 mr-2 text-gray-400" />
                                            {q.cpdHours} CPD Hours
                                        </div>
                                        <div className="flex items-center text-sm text-gray-500">
                                            <DocumentTextIcon className="w-4 h-4 mr-2 text-gray-400" />
                                            {q.credits}
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                </div>
            </section>

            {/* University Progression Mapping */}
            <section className="py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-medium mb-4">
                            <BuildingLibraryIcon className="w-4 h-4 mr-2" />
                            Academic Progression
                        </div>
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            University & College Progression Mapping
                        </h2>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            Clear pathways from IGRCFP qualifications to university degrees and postgraduate study
                        </p>
                    </div>

                    {/* Progression Table */}
                    <div className="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm mb-12">
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead>
                                    <tr className="bg-gradient-to-r from-blue-900 to-indigo-900 text-white">
                                        <th className="px-6 py-4 text-left text-sm font-semibold">IGRCFP Qualification</th>
                                        <th className="px-6 py-4 text-left text-sm font-semibold">University Entry Equivalent</th>
                                        <th className="px-6 py-4 text-left text-sm font-semibold">Progression Opportunity</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-100">
                                    {universityProgression.map((row, index) => (
                                        <tr key={index} className="hover:bg-gray-50 transition">
                                            <td className="px-6 py-5">
                                                <span className="font-semibold text-gray-900">{row.igrcfp}</span>
                                            </td>
                                            <td className="px-6 py-5 text-gray-700">{row.university}</td>
                                            <td className="px-6 py-5">
                                                <span className="inline-flex items-center text-green-700 bg-green-50 px-3 py-1 rounded-full text-sm font-medium">
                                                    <CheckCircleIcon className="w-4 h-4 mr-1.5" />
                                                    {row.progression}
                                                </span>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Credit Equivalency Cards */}
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                        {qualificationLevels.map((q, index) => (
                            <div key={index} className="bg-gradient-to-br from-gray-50 to-white rounded-xl p-5 text-center border border-gray-100">
                                <p className="text-xs font-semibold text-gray-500 uppercase mb-1">{q.qualification}</p>
                                <p className="text-2xl font-bold text-blue-900">{q.credits.split(' ')[0]}</p>
                                <p className="text-sm text-gray-500">ECTS Equivalent</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Entry & Prerequisites */}
            <section className="py-20 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-medium mb-4">
                            <ArrowsUpDownIcon className="w-4 h-4 mr-2" />
                            Entry Framework
                        </div>
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Entry & Prerequisites Framework
                        </h2>
                    </div>

                    <div className="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead>
                                    <tr className="bg-gray-50 border-b border-gray-200">
                                        <th className="px-6 py-4 text-left text-sm font-semibold text-gray-900">Level</th>
                                        <th className="px-6 py-4 text-left text-sm font-semibold text-gray-900">Academic Requirement</th>
                                        <th className="px-6 py-4 text-left text-sm font-semibold text-gray-900">Professional Experience</th>
                                        <th className="px-6 py-4 text-left text-sm font-semibold text-gray-900">Alternative Route</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-100">
                                    {[
                                        { level: 'Certificate', academic: 'A-Level / Basic Education', experience: 'Optional', alternative: 'Open Entry' },
                                        { level: 'Diploma', academic: 'Certificate / Equivalent', experience: '1–3 years', alternative: 'Direct Entry (Experience)' },
                                        { level: 'Advanced Diploma', academic: 'Diploma / Degree', experience: '3–5 years', alternative: 'RPL' },
                                        { level: 'Postgraduate Diploma', academic: 'Degree', experience: '5–10+ years', alternative: 'Senior Experience Route' }
                                    ].map((row, index) => (
                                        <tr key={index} className="hover:bg-gray-50 transition">
                                            <td className="px-6 py-5 font-semibold text-gray-900">{row.level}</td>
                                            <td className="px-6 py-5 text-gray-700">{row.academic}</td>
                                            <td className="px-6 py-5 text-gray-700">{row.experience}</td>
                                            <td className="px-6 py-5">
                                                <span className="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                                                    {row.alternative}
                                                </span>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            {/* CPD Framework */}
            <section className="py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-700 rounded-full text-sm font-medium mb-4">
                            <ClockIcon className="w-4 h-4 mr-2" />
                            CPD Framework
                        </div>
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Continuing Professional Development
                        </h2>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        {[
                            { qualification: 'Certificate', hours: '20–40', level: 'Entry', color: 'blue' },
                            { qualification: 'Diploma', hours: '60–100', level: 'Intermediate', color: 'green' },
                            { qualification: 'Advanced Diploma', hours: '100–150', level: 'Advanced', color: 'indigo' },
                            { qualification: 'Postgraduate Diploma', hours: '150–200+', level: 'Executive', color: 'rose' }
                        ].map((cpd, index) => (
                            <div key={index} className={`bg-gradient-to-br ${
                                cpd.color === 'blue' ? 'from-blue-50 to-blue-100' :
                                cpd.color === 'green' ? 'from-green-50 to-green-100' :
                                cpd.color === 'indigo' ? 'from-indigo-50 to-indigo-100' :
                                'from-rose-50 to-rose-100'
                            } rounded-2xl p-6 text-center`}>
                                <p className="text-4xl font-bold text-gray-900 mb-2">{cpd.hours}</p>
                                <p className="text-lg font-semibold text-gray-800">{cpd.qualification}</p>
                                <p className="text-sm text-gray-600 mt-1">{cpd.level} Level</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* University Partnership Pack */}
            <section id="partnerships" className="py-20 bg-gradient-to-br from-blue-900 to-indigo-900 text-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur rounded-full text-sm font-medium mb-4">
                            <BuildingLibraryIcon className="w-4 h-4 mr-2" />
                            University Partnership Pack
                        </div>
                        <h2 className="text-3xl md:text-4xl font-bold mb-4">
                            Partnership Models
                        </h2>
                        <p className="text-xl text-blue-100 max-w-3xl mx-auto">
                            Building academic collaboration between IGRCFP and universities worldwide
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                        {partnershipModels.map((model, index) => {
                            const Icon = model.icon;
                            return (
                                <div key={index} className="bg-white/10 backdrop-blur rounded-2xl p-8 border border-white/10 hover:bg-white/15 transition">
                                    <Icon className="w-12 h-12 text-blue-300 mb-6" />
                                    <h3 className="text-xl font-bold mb-4">{model.title}</h3>
                                    <ul className="space-y-3">
                                        {model.items.map((item, idx) => (
                                            <li key={idx} className="flex items-start gap-3">
                                                <CheckCircleIcon className="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5" />
                                                <span className="text-blue-100">{item}</span>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            );
                        })}
                    </div>

                    {/* Value to Universities */}
                    <div className="bg-white/5 backdrop-blur rounded-2xl p-8 border border-white/10">
                        <h3 className="text-2xl font-bold mb-6 text-center">Value to Universities</h3>
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                            {[
                                'Industry-relevant curriculum',
                                'Access to global GRC expertise',
                                'Professional pathway integration',
                                'Revenue-generating programmes'
                            ].map((value, index) => (
                                <div key={index} className="text-center p-4">
                                    <CheckCircleIcon className="w-8 h-8 text-green-400 mx-auto mb-3" />
                                    <p className="text-blue-100 text-sm">{value}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </section>

            {/* Corporate & Enterprise Training */}
            <section className="py-20 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium mb-4">
                            <BriefcaseIcon className="w-4 h-4 mr-2" />
                            Enterprise Solutions
                        </div>
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Corporate & Enterprise Training
                        </h2>
                    </div>

                    {/* Target Organisations */}
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
                        {targetOrgansations.map((org, index) => {
                            const Icon = org.icon;
                            return (
                                <div key={index} className="bg-white rounded-xl p-6 text-center border border-gray-100 shadow-sm hover:shadow-md transition">
                                    <Icon className="w-10 h-10 text-blue-600 mx-auto mb-3" />
                                    <p className="text-sm font-semibold text-gray-900">{org.name}</p>
                                </div>
                            );
                        })}
                    </div>

                    {/* Pricing Cards */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {corporatePrograms.map((program, index) => (
                            <div key={index} className={`${program.color} rounded-2xl p-8 border-2 relative`}>
                                {index === 2 && (
                                    <div className="absolute -top-4 left-1/2 -translate-x-1/2 bg-indigo-600 text-white px-4 py-1 rounded-full text-sm font-semibold">
                                        Most Comprehensive
                                    </div>
                                )}
                                <h3 className="text-xl font-bold text-gray-900 mb-2">{program.type}</h3>
                                <p className="text-3xl font-bold text-blue-900 mb-4">{program.price}</p>
                                <p className="text-gray-600">{program.description}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Visual Certification Pathway */}
            <section className="py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Visual Certification Pathway
                        </h2>
                    </div>

                    <div className="max-w-2xl mx-auto">
                        {[
                            { title: 'Certificate', subtitle: 'Foundation / Entry Level', color: 'blue' },
                            { title: 'Diploma', subtitle: 'Practitioner Level', color: 'green' },
                            { title: 'Advanced Diploma', subtitle: 'Specialist Level', color: 'indigo' },
                            { title: 'Postgraduate Diploma', subtitle: 'Executive Level', color: 'rose' },
                            { title: 'Leadership / Advisory / Board Level', subtitle: '', color: 'violet' }
                        ].map((step, index) => (
                            <div key={index} className="flex items-center gap-6">
                                <div className={`w-16 h-16 rounded-2xl ${
                                    step.color === 'blue' ? 'bg-blue-100' :
                                    step.color === 'green' ? 'bg-green-100' :
                                    step.color === 'indigo' ? 'bg-indigo-100' :
                                    step.color === 'rose' ? 'bg-rose-100' :
                                    'bg-violet-100'
                                } flex items-center justify-center flex-shrink-0`}>
                                    <span className={`font-bold text-xl ${
                                        step.color === 'blue' ? 'text-blue-700' :
                                        step.color === 'green' ? 'text-green-700' :
                                        step.color === 'indigo' ? 'text-indigo-700' :
                                        step.color === 'rose' ? 'text-rose-700' :
                                        'text-violet-700'
                                    }`}>L{index + 1}</span>
                                </div>
                                <div className="flex-1 bg-gray-50 rounded-xl p-4">
                                    <p className="font-semibold text-gray-900">{step.title}</p>
                                    {step.subtitle && <p className="text-sm text-gray-500">{step.subtitle}</p>}
                                </div>
                                {index < 4 && (
                                    <div className="flex flex-col items-center">
                                        <ArrowTrendingUpIcon className="w-6 h-6 text-gray-300" />
                                    </div>
                                )}
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
                            Internationally Aligned
                        </h2>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            IGRCFP qualifications are aligned with the world's leading regulatory and professional frameworks
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        {globalAlignments.map((alignment, index) => (
                            <div key={index} className="bg-white rounded-xl p-6 text-center border border-gray-100 shadow-sm">
                                <CheckCircleIcon className="w-8 h-8 text-green-500 mx-auto mb-3" />
                                <p className="text-sm font-medium text-gray-900">{alignment}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Quick Links CTA */}
            <section className="py-16 bg-blue-900">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl font-bold text-white mb-8">
                        Ready to Explore Our Qualifications?
                    </h2>
                    <div className="flex flex-wrap justify-center gap-4">
                        <Link
                            href={route('course.catalog.index')}
                            className="inline-flex items-center px-8 py-4 bg-white text-blue-900 font-semibold rounded-xl hover:bg-blue-50 transition"
                        >
                            Browse Course Catalogue
                            <ArrowRightIcon className="w-5 h-5 ml-2" />
                        </Link>
                        <Link
                            href={route('contact')}
                            className="inline-flex items-center px-8 py-4 border-2 border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition"
                        >
                            Partnership Enquiries
                            <ArrowRightIcon className="w-5 h-5 ml-2" />
                        </Link>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}