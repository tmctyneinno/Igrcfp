import React from 'react';
import { Head } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

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

function CourseTable({ section }) {
    return (
        <div className="mb-12">
            <h2 className="text-2xl font-bold text-blue-900 mb-4">{section.title}</h2>
            <div className="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div className="grid grid-cols-[5rem_1fr] bg-gray-50 border-b border-gray-200 text-sm font-semibold text-gray-700">
                    <div className="px-4 py-3">No.</div>
                    <div className="px-4 py-3">Course</div>
                </div>
                <div className="divide-y divide-gray-100">
                    {section.courses.map((course, index) => (
                        <div key={course} className="grid grid-cols-[5rem_1fr]">
                            <div className="px-4 py-3 text-gray-600">{section.start + index}</div>
                            <div className="px-4 py-3 text-gray-800">{course}</div>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}

export default function CourseCatalog({ auth }) {
    return (
        <GuestLayout auth={auth}>
            <Head title="IGRCFP | Course Catalogue" />

            <section className="w-full bg-gradient-to-r from-blue-200 via-white to-blue-200 py-28">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                            IGRCFP Course Catalogue
                        </h1>
                        <p className="text-xl text-gray-600 max-w-4xl mx-auto">
                            Professional Certificates in Governance, Risk, Compliance, Cybersecurity & Financial Crime Prevention
                        </p>
                    </div>
                </div>
            </section>

            <section className="bg-white py-16">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="space-y-5 text-lg leading-8 text-gray-700">
                        <p>
                            IGRCFP offers a structured portfolio of specialist certificate programmes designed for professionals working across governance, risk, compliance, financial crime prevention, cybersecurity, digital risk, audit, assurance, ESG, and emerging regulatory environments.
                        </p>
                        <p>
                            Our courses are developed for professionals who need practical, globally relevant knowledge that can be applied immediately in regulated, complex, and fast-changing organisational settings.
                        </p>
                    </div>
                </div>
            </section>

            <section className="bg-gray-50 py-16">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    {courseSections.map((section) => (
                        <CourseTable key={section.title} section={section} />
                    ))}
                </div>
            </section>

            <section className="bg-white py-16">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 className="text-3xl font-bold text-gray-900 mb-8">Learning Pathways</h2>

                    <div className="space-y-10">
                        {pathways.map((pathway) => (
                            <div key={pathway.title}>
                                <h3 className="text-2xl font-bold text-blue-900 mb-3">{pathway.title}</h3>
                                <p className="text-gray-700 mb-4">{pathway.description}</p>
                                <p className="font-semibold text-gray-900 mb-3">Recommended courses:</p>
                                <ul className="list-disc pl-6 space-y-2 text-gray-700">
                                    {pathway.courses.map((course) => (
                                        <li key={course}>{course}</li>
                                    ))}
                                </ul>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            <section className="bg-gray-50 py-16">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid md:grid-cols-2 gap-10">
                        <div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-5">Why Study with IGRCFP?</h2>
                            <p className="text-gray-700 mb-4">IGRCFP programmes are:</p>
                            <ul className="list-disc pl-6 space-y-2 text-gray-700">
                                {studyReasons.map((reason) => (
                                    <li key={reason}>{reason}</li>
                                ))}
                            </ul>
                        </div>

                        <div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-5">Delivery Options</h2>
                            <p className="text-gray-700 mb-4">Courses may be delivered through:</p>
                            <ul className="list-disc pl-6 space-y-2 text-gray-700">
                                {deliveryOptions.map((option) => (
                                    <li key={option}>{option}</li>
                                ))}
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <section className="bg-white py-16">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="space-y-12">
                        <div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-5">Certification</h2>
                            <p className="text-gray-700 leading-8">
                                Participants who successfully complete the course requirements will receive an IGRCFP Professional Certificate in the relevant subject area.
                            </p>
                        </div>

                        <div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-5">Enrolment and Partnerships</h2>
                            <p className="text-gray-700 mb-4">To register for a course or discuss corporate training:</p>
                            <div className="space-y-2 text-gray-700">
                                <p><span className="font-semibold">Website:</span> www.igrcfp.org</p>
                                <p><span className="font-semibold">Training enquiries:</span> training@igrcfp.org</p>
                                <p><span className="font-semibold">Partnership enquiries:</span> partnerships@igrcfp.org</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}
