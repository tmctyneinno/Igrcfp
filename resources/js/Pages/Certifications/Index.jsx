import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Certifications({ auth, title, description }) {

    const programs = [
    {
        title: 'Certified GRC & Financial Crime Specialist (CGFCS)',
        description:
            'Our flagship certification equipping professionals with practical skills in governance, risk, compliance, and financial crime prevention. Globally benchmarked and CPD-accredited.',
        image: '/images/programs/grc.jpg',
    },
    {
        title: 'Advanced Diploma in GRC & Financial Crime Prevention',
        description:
            'A deep-dive, multi-disciplinary programme covering advanced governance, risk, compliance, and financial crime prevention with real-world case projects.',
        image: '/images/programs/diploma.jpg',
    },
    {
        title: 'Cybersecurity & Data Security for Financial Institutions',
        description:
            'Focused training on cyber resilience, data protection, and information governance in the digital-first financial sector.',
        image: '/images/programs/cyber.jpg',
    },
    {
        title: 'Monitoring, Reporting & Risk Analytics',
        description:
            'Equips compliance teams with tools to design monitoring frameworks, track key risk indicators, and use analytics for regulatory reporting.',
        image: '/images/programs/analytics.jpg',
    },
    {
        title: 'Regulatory Compliance & Supervisory Engagement',
        description:
            'Covers global regulatory requirements, reporting obligations, and effective engagement with supervisors including mock interviews.',
        image: '/images/programs/regulatory.jpg',
    },
    {
        title: 'RegTech, SupTech & Innovation in Compliance',
        description:
            'Explores cutting-edge compliance technologies including AI, blockchain, real-time monitoring, and data-driven oversight.',
        image: '/images/programs/regtech.jpg',
    },
    {
        title: 'InsurTech, FinTech & Emerging Market Compliance',
        description:
            'Addresses compliance challenges for FinTech and InsurTech models in Africa and global markets, including mobile money and crypto.',
        image: '/images/programs/fintech.jpg',
    },
    {
        title: 'Executive Masterclasses & Short Courses',
        description:
            'Focused electives on emerging topics including AI, ESG, forensic investigations, sanctions, and leadership in governance.',
        image: '/images/programs/executive.jpg',
    },
];

    return (
        <GuestLayout auth={auth}>
            <Head title={title} />
            
            {/* Hero Section */}
            <section className="w-full bg-gradient-to-r from-blue-200 via-white to-blue-200 py-28">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                            {title}
                        </h1> 
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            {description}
                        </p>
                    </div>
                </div>
            </section>

            {/* Main Content Section */}
            <section className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                        <div>
                            <h2 className="text-4xl font-semibold text-gray-800">
                                Advance Your Career. Build Institutional Resilience.
                            </h2>
                            <p className="mt-4 text-gray-600 text-2xl">
                                Our professional certifications are designed to equip individuals and institutions with globally relevant skills to tackle financial crime and compliance risks.
                            </p>
                            
                            <div className="mb-10 mt-16">
                                <h3 className="text-start text-lg font-semibold text-gray-500 mb-8 uppercase tracking-wider">
                                    TRUSTED BY
                                </h3>
                                
                                {/* Stats Grid */}
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-2xl mx-auto">
                                    {/* Learners Stat */}
                                    <div className="bg-gray-50 rounded-xl p-3 text-center border border-gray-200">
                                        <div className="text-4xl md:text-5xl font-bold text-blue-600 mb-2">
                                            40k+
                                        </div>
                                        <div className="text-lg font-medium text-gray-700">
                                            learners
                                        </div>
                                        <p className="text-gray-500 mt-2 text-sm">
                                            Professionals trained worldwide
                                        </p>
                                    </div>
                                    
                                    {/* Experts Stat */}
                                    <div className="bg-gray-50 rounded-xl p-3 text-center border border-gray-200">
                                        <div className="text-4xl md:text-5xl font-bold text-green-600 mb-2">
                                            100+
                                        </div>
                                        <div className="text-lg font-medium text-gray-700">
                                            experts
                                        </div>
                                        <p className="text-gray-500 mt-2 text-sm">
                                            Industry-leading instructors
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {/* Image Section */}
                        <div className="relative">
                            <img
                                src="assets/images/certification.png" // Replace with your actual image path
                                alt="Certification"
                                className="rounded-lg shadow-lg object-cover w-full h-full"
                            />
                        </div>
                    </div>
                </div>
            </section>

            {/* Section */}
            <section className="py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    {/* Header */}
                    <div className="text-center max-w-4xl mx-auto">
                        <h2 className="text-3xl md:text-4xl font-semibold text-blue-900">
                            Industry-Relevant Programs to Set you Apart
                        </h2>
                        <p className="mt-4 text-lg text-gray-600">
                            At IGRCFP, we provide practical training and globally recognized certifications
                            in governance, risk, compliance, and financial crime prevention. Learn online,
                            hybrid, or in-person — all with real-world case studies and expert trainers.
                        </p>

                        <div className="mt-8">
                            <Link
                                href="/training-calendar"
                                className="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-blue-900 rounded-md hover:bg-blue-800 transition"
                            >
                                View Training Calendar
                            </Link>
                        </div>
                    </div>

                    {/* Cards Grid */}
                    <div className="mt-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
                        {programs.map((program, index) => (
                            <div
                                key={index}
                                className="group bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden"
                            >
                                <div className="h-48 overflow-hidden">
                                    <img
                                        src={program.image}
                                        alt={program.title}
                                        className="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    />
                                </div>

                                <div className="p-6">
                                    <h3 className="text-lg font-semibold text-gray-900">
                                        {program.title}
                                    </h3>
                                    <p className="mt-3 text-sm text-gray-600 leading-relaxed">
                                        {program.description}
                                    </p>

                                    <div className="mt-5">
                                        <Link
                                            href="#"
                                            className="text-sm font-medium text-blue-800 hover:underline"
                                        >
                                            Learn More
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>

                </div>
            </section>


        </GuestLayout>
    );
}
