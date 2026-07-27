import React from 'react';
import { Head } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import HeroSection from '@/Layouts/HeroSection';
import CallToAction from "@/Pages/components/CallToAction";

const courseSections = [
    {
        title: 'Core GRC & Governance',
        start: 1,
        courses: [
            'Certificate in Governance, Risk & Compliance (GRC)',
            'Certificate in Corporate Governance',
            'Certificate in Board Governance & Oversight',
            'Certificate in Enterprise Risk Management (ERM)',
            'Certificate in Operational Risk Management',
            'Certificate in Strategic Risk & Decision Making',
            'Certificate in Compliance & Regulatory Frameworks',
            'Certificate in Regulatory Compliance (Global Frameworks)',
            'Certificate in Ethics, Conduct & Culture',
            'Certificate in Integrated GRC Frameworks',
        ],
    }, 
    {
        title: 'Financial Crime & AML',
        start: 11,
        courses: [
            'Certificate in Financial Crime Prevention',
            'Certificate in Anti-Money Laundering (AML & CFT)',
            'Certificate in Know Your Customer (KYC & CDD)',
            'Certificate in Sanctions & Financial Crime Compliance',
            'Certificate in Transaction Monitoring & Suspicious Activity Reporting',
            'Certificate in Fraud Risk Management',
            'Certificate in Anti-Bribery & Corruption',
            'Certificate in Counter Terrorist Financing (CTF)',
            'Certificate in Financial Intelligence & Investigations',
            'Certificate in Trade-Based Money Laundering (TBML)',
            'Certificate in Politically Exposed Persons (PEPs) Risk Management',
            'Certificate in Financial Crime Risk Assessment',
        ],
    },
    {
        title: 'Cybersecurity & Digital Risk',
        start: 23,
        courses: [
            'Certificate in Cybersecurity & Digital Risk',
            'Certificate in Information Security Management',
            'Certificate in Cyber Risk Governance',
            'Certificate in Cyber Threat Intelligence',
            'Certificate in Incident Response & Cyber Crisis Management',
            'Certificate in Digital Forensics & Investigation',
            'Certificate in Cloud Security & Risk',
            'Certificate in Network Security Fundamentals',
            'Certificate in Cybersecurity for Financial Institutions',
            'Certificate in Operational Resilience & Cyber Risk',
        ],
    },
    {
        title: 'Data, Privacy & Technology',
        start: 33,
        courses: [
            'Certificate in Data Protection & Privacy (GDPR)',
            'Certificate in Data Governance & Data Risk',
            'Certificate in Information Governance',
            'Certificate in Data Ethics & Responsible AI',
            'Certificate in Artificial Intelligence & Digital Compliance',
            'Certificate in RegTech & SupTech',
            'Certificate in Blockchain & Cryptocurrency Risk',
            'Certificate in Digital Identity & Verification',
        ],
    },
    {
        title: 'Audit, Control & Assurance',
        start: 41,
        courses: [
            'Certificate in Internal Audit & Assurance',
            'Certificate in Risk-Based Internal Audit',
            'Certificate in Compliance Monitoring & Testing',
            'Certificate in Controls & Assurance Frameworks',
            'Certificate in Combined Assurance & Three Lines Model',
            'Certificate in Audit Analytics & Data-Driven Assurance',
            'Certificate in Investigations & Case Management',
            'Certificate in Forensic Audit & Fraud Investigation',
        ],
    },
    {
        title: 'ESG, Ethics & Sustainability',
        start: 49,
        courses: [
            'Certificate in ESG (Environmental, Social & Governance)',
            'Certificate in Sustainability & Risk Management',
            'Certificate in Climate Risk & ESG Reporting',
            'Certificate in Corporate Social Responsibility (CSR)',
            'Certificate in Ethical Leadership & Governance',
            'Certificate in Sustainable Finance',
        ],
    },
    {
        title: 'Specialist & Emerging Risk Areas',
        start: 55,
        courses: [
            'Certificate in FinTech Risk & Compliance',
            'Certificate in Digital Banking & Financial Crime',
            'Certificate in Payments Fraud & Risk',
            'Certificate in Crypto Compliance & AML',
            'Certificate in Supply Chain Risk & Compliance',
            'Certificate in Third-Party Risk Management',
            'Certificate in Operational Resilience (Advanced)',
            'Certificate in Global Risk & Regulatory Landscape',
        ],
    },
];

const pathways = [
    {
        title: 'Foundation Pathway',
        description: 'Ideal for professionals entering GRC, compliance, financial crime, or cyber risk roles.',
        courses: [
            'Certificate in Governance, Risk & Compliance',
            'Certificate in Financial Crime Prevention',
            'Certificate in Compliance & Regulatory Frameworks',
            'Certificate in Data Protection & Privacy',
        ],
    },
    {
        title: 'Specialist Pathway',
        description: 'Designed for practitioners who want deeper technical capability.',
        courses: [
            'Certificate in AML & CFT',
            'Certificate in Trade-Based Money Laundering',
            'Certificate in Cybersecurity & Digital Risk',
            'Certificate in Blockchain & Cryptocurrency Risk',
        ],
    },
    {
        title: 'Leadership Pathway',
        description: 'Designed for senior professionals, executives, board members, and advisors.',
        courses: [
            'Certificate in Board Governance & Oversight',
            'Certificate in Enterprise Risk Management',
            'Certificate in Integrated GRC Frameworks',
            'Certificate in Operational Resilience',
        ],
    },
];

const studyReasons = [
    'Globally relevant',
    'Practitioner-led',
    'Case-study driven',
    'Designed for regulated and high-risk environments',
    'Aligned with recognised governance, risk, compliance, cybersecurity, and financial crime prevention principles',
];

const deliveryOptions = [
    'Self-paced online learning',
    'Live virtual classes',
    'In-person workshops',
    'Corporate in-house training',
    'Blended learning programmes',
];

const CourseSection = ({ section }) => (
    <div className=" border border-gray-200 rounded-lg overflow-hidden mb-8 shadow-sm">
        <div className="bg-gray-200 px-4 py-3 flex items-center justify-between">
            <h3 className="font-semibold text-gray-800">{section.title}</h3>
            <span className="text-xs font-medium text-white bg-[#143576] px-2 py-1 rounded border border-gray-300">
                {section.courses.length} Courses
            </span>
        </div>
        <div className="divide-y divide-gray-100 bg-white">
            {section.courses.map((course, idx) => (
                <div key={course} className="px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <span className="text-gray-400 mr-3 font-medium">{section.start + idx}.</span>
                    {course}
                </div>
            ))}
        </div>
    </div>
);

export default function CourseCatalog({ auth }) {
    return ( 
        <GuestLayout auth={auth}>
            <Head title="IGRCFP | Course Catalogue" />
            
            <HeroSection 
                title="IGRCFP Course Catalog"
                description="The Institute of Governance, Risk, Compliance & Financial Crime Prevention"
            />

            <header className="py-12 px-4 sm:px-6 lg:px-8 bg-white border-b border-gray-100">
                <div className="max-w-5xl mx-auto">
                    <h1 className="text-2xl font-bold uppercase tracking-wider text-gray-900 mb-3">Course Catalogue</h1>
                    <p className="text-md text-gray-600 leading-relaxed mb-2">
                        IGRCFP offers a structured portfolio of specialist certificate programmes designed for professionals working across governance, risk, compliance, financial crime prevention, cybersecurity, digital risk, audit, assurance, ESG, and emerging regulatory environments.
                    </p>
                    <p className="text-md text-gray-500 max-w-4xl">
                        Our courses are developed for professionals who need practical, globally relevant knowledge that can be applied immediately in regulated, complex, and fast-changing organisational settings.
                    </p>
                </div>
            </header>

            {/* ✅ Fixed: removed extra space, mx-auto properly centers the container */}
            <main className="max-w-6xl mx-auto px-4 sm:px-6 py-10 pl-8">
                {courseSections.map((section) => (
                    <CourseSection key={section.title} section={section} />
                ))}

                <section className="mt-16 mb-12">
                    <h2 className="text-xl font-bold uppercase tracking-wider text-gray-900 mb-6">Learning Pathways</h2>
                    <div className="grid sm:grid-cols-3 gap-6">
                        {pathways.map((pathway) => (
                            <div key={pathway.title} className="border border-gray-200 rounded-lg p-5 bg-white shadow-sm">
                                <h3 className="font-semibold text-gray-800 mb-2">{pathway.title}</h3>
                                <p className="text-xs text-gray-600 mb-4">{pathway.description}</p>
                                <ul className="space-y-1.5 text-xs text-gray-700">
                                    {pathway.courses.map((c) => (
                                        <li key={c} className="flex gap-2">
                                            <span className="text-gray-400">•</span>
                                            {c}
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        ))}
                    </div>
                </section>

                <section className="grid sm:grid-cols-2 gap-6 mb-12">
                    <div className="border border-gray-200 rounded-lg p-5 bg-white shadow-sm">
                        <h3 className="font-semibold text-gray-800 mb-3">Why Study with IGRCFP?</h3>
                        <ul className="space-y-2 text-xs text-gray-700">
                            {studyReasons.map((r) => (
                                <li key={r} className="flex gap-2">
                                    <span className="text-gray-400">•</span>
                                    {r}
                                </li>
                            ))}
                        </ul>
                    </div>
                    <div className="border border-gray-200 rounded-lg p-5 bg-white shadow-sm">
                        <h3 className="font-semibold text-gray-800 mb-3">Delivery Options</h3>
                        <ul className="space-y-2 text-xs text-gray-700">
                            {deliveryOptions.map((o) => (
                                <li key={o} className="flex gap-2">
                                    <span className="text-gray-400">•</span>
                                    {o}
                                </li>
                            ))}
                        </ul>
                    </div>
                </section>

                <section className="space-y-6">
                    <div className="border border-gray-200 rounded-lg p-5 bg-white shadow-sm">
                        <h3 className="font-semibold text-gray-800 mb-3">Certification</h3>
                        <p className="text-md text-gray-700 leading-relaxed">
                            Participants who successfully complete the course requirements will receive an IGRCFP Professional Certificate in the relevant subject area.
                        </p>
                    </div>
                    <div className="rounded-lg p-5 bg-[#0A1A2F] text-white shadow-sm">
                        <h3 className="font-semibold mb-3">Enrolment and Partnerships</h3>
                        <p className="text-md text-gray-200 mb-3">To register for a course or discuss corporate training:</p>
                        <div className="space-y-1.5 text-md text-gray-200">
                            <p><strong>Website:</strong> www.igrcfp.org</p>
                            <p><strong>Training enquiries:</strong> training@igrcfp.org</p>
                            <p><strong>Partnership enquiries:</strong> partnerships@igrcfp.org</p>
                        </div>
                    </div>
                </section>
            </main>

            <CallToAction />
        </GuestLayout>
    );
}