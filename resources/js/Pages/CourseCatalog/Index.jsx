import React, { useMemo, useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import {
    ArrowRight,
    Award,
    BookOpen,
    BriefcaseBusiness,
    Building2,
    CheckCircle2,
    ClipboardCheck,
    Database,
    GraduationCap,
    Landmark,
    Leaf,
    Mail,
    MonitorPlay,
    Radar,
    Search,
    ShieldCheck,
    Sparkles,
    Users,
    X,
} from 'lucide-react';

const courseGroups = [
    {
        id: 'grc',
        title: 'Core GRC & Governance',
        summary: 'Governance, risk, compliance, conduct, culture, and board oversight foundations.',
        accent: 'blue',
        icon: Landmark,
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
        id: 'financial-crime',
        title: 'Financial Crime & AML',
        summary: 'AML, CFT, sanctions, KYC, investigations, fraud, and financial intelligence.',
        accent: 'red',
        icon: ShieldCheck,
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
        id: 'cyber',
        title: 'Cybersecurity & Digital Risk',
        summary: 'Cyber governance, incident response, cloud security, resilience, and investigations.',
        accent: 'violet',
        icon: Radar,
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
        id: 'data',
        title: 'Data, Privacy & Technology',
        summary: 'Data protection, AI, RegTech, SupTech, blockchain risk, and identity verification.',
        accent: 'cyan',
        icon: Database,
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
        id: 'audit',
        title: 'Audit, Control & Assurance',
        summary: 'Internal audit, assurance frameworks, monitoring, testing, analytics, and investigations.',
        accent: 'amber',
        icon: ClipboardCheck,
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
        id: 'esg',
        title: 'ESG, Ethics & Sustainability',
        summary: 'ESG, climate risk, sustainability, CSR, ethical leadership, and sustainable finance.',
        accent: 'emerald',
        icon: Leaf,
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
        id: 'specialist',
        title: 'Specialist & Emerging Risk Areas',
        summary: 'FinTech, digital banking, crypto compliance, third-party risk, and global regulation.',
        accent: 'slate',
        icon: Sparkles,
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
        audience: 'Ideal for professionals entering GRC, compliance, financial crime, or cyber risk roles.',
        icon: GraduationCap,
        recommended: [
            'Certificate in Governance, Risk & Compliance',
            'Certificate in Financial Crime Prevention',
            'Certificate in Compliance & Regulatory Frameworks',
            'Certificate in Data Protection & Privacy',
        ],
    },
    {
        title: 'Specialist Pathway',
        audience: 'Designed for practitioners who want deeper technical capability.',
        icon: BriefcaseBusiness,
        recommended: [
            'Certificate in AML & CFT',
            'Certificate in Trade-Based Money Laundering',
            'Certificate in Cybersecurity & Digital Risk',
            'Certificate in Blockchain & Cryptocurrency Risk',
        ],
    },
    {
        title: 'Leadership Pathway',
        audience: 'Designed for senior professionals, executives, board members, and advisors.',
        icon: Users,
        recommended: [
            'Certificate in Board Governance & Oversight',
            'Certificate in Enterprise Risk Management',
            'Certificate in Integrated GRC Frameworks',
            'Certificate in Operational Resilience',
        ],
    },
];

const deliveryOptions = [
    'Self-paced online learning',
    'Live virtual classes',
    'In-person workshops',
    'Corporate in-house training',
    'Blended learning programmes',
];

const studyReasons = [
    'Globally relevant',
    'Practitioner-led',
    'Case-study driven',
    'Designed for regulated and high-risk environments',
    'Aligned with recognised GRC, cybersecurity, and financial crime prevention principles',
];

const accentClasses = {
    blue: {
        border: 'border-blue-200',
        bg: 'bg-blue-50',
        icon: 'bg-blue-900 text-white',
        pill: 'bg-blue-100 text-blue-800',
    },
    red: {
        border: 'border-red-200',
        bg: 'bg-red-50',
        icon: 'bg-red-700 text-white',
        pill: 'bg-red-100 text-red-800',
    },
    violet: {
        border: 'border-violet-200',
        bg: 'bg-violet-50',
        icon: 'bg-violet-700 text-white',
        pill: 'bg-violet-100 text-violet-800',
    },
    cyan: {
        border: 'border-cyan-200',
        bg: 'bg-cyan-50',
        icon: 'bg-cyan-700 text-white',
        pill: 'bg-cyan-100 text-cyan-800',
    },
    amber: {
        border: 'border-amber-200',
        bg: 'bg-amber-50',
        icon: 'bg-amber-700 text-white',
        pill: 'bg-amber-100 text-amber-800',
    },
    emerald: {
        border: 'border-emerald-200',
        bg: 'bg-emerald-50',
        icon: 'bg-emerald-700 text-white',
        pill: 'bg-emerald-100 text-emerald-800',
    },
    slate: {
        border: 'border-slate-200',
        bg: 'bg-slate-50',
        icon: 'bg-slate-800 text-white',
        pill: 'bg-slate-100 text-slate-800',
    },
};

const allCourses = courseGroups.flatMap((group) =>
    group.courses.map((title, index) => ({
        number: courseGroups
            .slice(0, courseGroups.findIndex((item) => item.id === group.id))
            .reduce((total, item) => total + item.courses.length, 0) + index + 1,
        title,
        groupId: group.id,
    }))
);

export default function CourseCatalog({ auth }) {
    const [search, setSearch] = useState('');
    const [activeGroup, setActiveGroup] = useState('all');

    const filteredGroups = useMemo(() => {
        const term = search.trim().toLowerCase();

        return courseGroups
            .filter((group) => activeGroup === 'all' || group.id === activeGroup)
            .map((group) => ({
                ...group,
                courses: group.courses
                    .map((title, index) => ({
                        title,
                        number: allCourses.find((course) => course.title === title)?.number || index + 1,
                    }))
                    .filter((course) => {
                        if (!term) return true;

                        return (
                            course.title.toLowerCase().includes(term) ||
                            group.title.toLowerCase().includes(term) ||
                            group.summary.toLowerCase().includes(term)
                        );
                    }),
            }))
            .filter((group) => group.courses.length > 0);
    }, [activeGroup, search]);

    const filteredCount = filteredGroups.reduce((total, group) => total + group.courses.length, 0);

    return (
        <GuestLayout auth={auth}>
            <Head title="IGRCFP | Course Catalogue" />

            <main className="bg-slate-50 text-slate-900">
                <section className="relative overflow-hidden bg-slate-950 text-white">
                    <div className="absolute inset-0 bg-[linear-gradient(120deg,rgba(30,64,175,0.35),transparent_38%,rgba(20,184,166,0.18))]" />
                    <div className="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-slate-50 to-transparent" />

                    <div className="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                        <div className="grid gap-12 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                            <div>
                                <div className="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-cyan-100">
                                    <Award className="h-4 w-4" />
                                    Professional Certificate Portfolio
                                </div>

                                <h1 className="mt-6 max-w-4xl text-4xl font-bold leading-tight md:text-5xl">
                                    IGRCFP Course Catalogue
                                </h1>

                                <p className="mt-5 max-w-3xl text-lg leading-8 text-slate-200">
                                    Professional certificates in governance, risk, compliance, cybersecurity, and financial crime prevention for modern regulated environments.
                                </p>

                                <p className="mt-5 max-w-3xl text-base leading-7 text-slate-300">
                                    IGRCFP offers a structured portfolio of specialist certificate programmes for professionals across governance, risk, compliance, financial crime prevention, cybersecurity, digital risk, audit, assurance, ESG, and emerging regulatory environments.
                                </p>

                                <div className="mt-8 flex flex-wrap gap-3">
                                    <a
                                        href="#catalogue"
                                        className="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-semibold text-slate-950 shadow-sm transition hover:bg-cyan-50"
                                    >
                                        Browse Courses
                                        <ArrowRight className="h-4 w-4" />
                                    </a>
                                    <a
                                        href="mailto:training@igrcfp.org"
                                        className="inline-flex items-center gap-2 rounded-lg border border-white/20 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10"
                                    >
                                        Training Enquiries
                                    </a>
                                </div>
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                {[
                                    ['62', 'Certificate Courses'],
                                    ['7', 'Specialist Areas'],
                                    ['3', 'Learning Pathways'],
                                    ['5', 'Delivery Options'],
                                ].map(([value, label]) => (
                                    <div key={label} className="rounded-lg border border-white/10 bg-white/10 p-5 shadow-xl backdrop-blur">
                                        <div className="text-3xl font-bold text-white">{value}</div>
                                        <div className="mt-2 text-sm font-medium text-slate-300">{label}</div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </section>

                <section className="border-b border-slate-200 bg-white">
                    <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                        <div className="grid gap-4 lg:grid-cols-[1fr_auto] lg:items-center">
                            <div className="relative">
                                <Search className="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                                <input
                                    value={search}
                                    onChange={(event) => setSearch(event.target.value)}
                                    type="search"
                                    placeholder="Search course titles, risk areas, or subject groups"
                                    className="h-12 w-full rounded-lg border-slate-300 pl-12 pr-12 text-sm shadow-sm focus:border-blue-600 focus:ring-blue-600"
                                />
                                {search && (
                                    <button
                                        type="button"
                                        onClick={() => setSearch('')}
                                        className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 transition hover:text-slate-700"
                                        aria-label="Clear search"
                                    >
                                        <X className="h-5 w-5" />
                                    </button>
                                )}
                            </div>

                            <div className="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700">
                                Showing {filteredCount} of {allCourses.length} courses
                            </div>
                        </div>

                        <div className="mt-5 flex gap-2 overflow-x-auto pb-1">
                            <button
                                type="button"
                                onClick={() => setActiveGroup('all')}
                                className={`shrink-0 rounded-full px-4 py-2 text-sm font-semibold transition ${
                                    activeGroup === 'all'
                                        ? 'bg-slate-950 text-white'
                                        : 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
                                }`}
                            >
                                All Courses
                            </button>
                            {courseGroups.map((group) => (
                                <button
                                    key={group.id}
                                    type="button"
                                    onClick={() => setActiveGroup(group.id)}
                                    className={`shrink-0 rounded-full px-4 py-2 text-sm font-semibold transition ${
                                        activeGroup === group.id
                                            ? 'bg-slate-950 text-white'
                                            : 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
                                    }`}
                                >
                                    {group.title}
                                </button>
                            ))}
                        </div>
                    </div>
                </section>

                <section id="catalogue" className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                    <div className="grid gap-6 lg:grid-cols-3">
                        {filteredGroups.map((group) => {
                            const Icon = group.icon;
                            const classes = accentClasses[group.accent];

                            return (
                                <article
                                    key={group.id}
                                    className={`overflow-hidden rounded-lg border bg-white shadow-sm ${classes.border}`}
                                >
                                    <div className={`border-b px-5 py-5 ${classes.bg} ${classes.border}`}>
                                        <div className="flex items-start gap-4">
                                            <div className={`flex h-11 w-11 shrink-0 items-center justify-center rounded-lg ${classes.icon}`}>
                                                <Icon className="h-5 w-5" />
                                            </div>
                                            <div>
                                                <span className={`inline-flex rounded-full px-3 py-1 text-xs font-semibold ${classes.pill}`}>
                                                    {group.courses.length} courses
                                                </span>
                                                <h2 className="mt-3 text-xl font-bold text-slate-950">{group.title}</h2>
                                                <p className="mt-2 text-sm leading-6 text-slate-600">{group.summary}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="divide-y divide-slate-100">
                                        {group.courses.map((course) => (
                                            <div key={course.number} className="grid grid-cols-[3rem_1fr] gap-3 px-5 py-4">
                                                <div className="text-sm font-bold text-slate-400">
                                                    {String(course.number).padStart(2, '0')}
                                                </div>
                                                <div className="text-sm font-semibold leading-6 text-slate-800">
                                                    {course.title}
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </article>
                            );
                        })}
                    </div>

                    {filteredGroups.length === 0 && (
                        <div className="rounded-lg border border-slate-200 bg-white px-6 py-16 text-center shadow-sm">
                            <BookOpen className="mx-auto h-10 w-10 text-slate-400" />
                            <h2 className="mt-4 text-xl font-bold text-slate-950">No matching courses found</h2>
                            <p className="mt-2 text-slate-600">Try a different keyword or view all subject areas.</p>
                            <button
                                type="button"
                                onClick={() => {
                                    setSearch('');
                                    setActiveGroup('all');
                                }}
                                className="mt-6 rounded-lg bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
                            >
                                Reset Catalogue
                            </button>
                        </div>
                    )}
                </section>

                <section className="bg-white py-14">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="max-w-3xl">
                            <p className="text-sm font-semibold uppercase tracking-[0.18em] text-blue-700">Learning Pathways</p>
                            <h2 className="mt-3 text-3xl font-bold text-slate-950">Choose a pathway that matches your career stage</h2>
                            <p className="mt-3 leading-7 text-slate-600">
                                Each pathway gives learners a practical route through the catalogue, from foundational capability to specialist depth and senior leadership readiness.
                            </p>
                        </div>

                        <div className="mt-8 grid gap-6 lg:grid-cols-3">
                            {pathways.map((pathway) => {
                                const Icon = pathway.icon;

                                return (
                                    <article key={pathway.title} className="rounded-lg border border-slate-200 bg-slate-50 p-6">
                                        <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-900 text-white">
                                            <Icon className="h-6 w-6" />
                                        </div>
                                        <h3 className="mt-5 text-xl font-bold text-slate-950">{pathway.title}</h3>
                                        <p className="mt-3 text-sm leading-6 text-slate-600">{pathway.audience}</p>

                                        <div className="mt-5 space-y-3">
                                            <p className="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Recommended courses</p>
                                            {pathway.recommended.map((course) => (
                                                <div key={course} className="flex gap-3 text-sm leading-6 text-slate-700">
                                                    <CheckCircle2 className="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" />
                                                    <span>{course}</span>
                                                </div>
                                            ))}
                                        </div>
                                    </article>
                                );
                            })}
                        </div>
                    </div>
                </section>

                <section className="mx-auto grid max-w-7xl gap-6 px-4 py-14 sm:px-6 lg:grid-cols-2 lg:px-8">
                    <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                        <div className="flex items-center gap-3">
                            <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-700 text-white">
                                <CheckCircle2 className="h-6 w-6" />
                            </div>
                            <h2 className="text-2xl font-bold text-slate-950">Why Study with IGRCFP?</h2>
                        </div>
                        <div className="mt-6 grid gap-3">
                            {studyReasons.map((reason) => (
                                <div key={reason} className="flex gap-3 rounded-lg bg-slate-50 p-4 text-sm font-semibold text-slate-700">
                                    <CheckCircle2 className="h-5 w-5 shrink-0 text-emerald-600" />
                                    {reason}
                                </div>
                            ))}
                        </div>
                    </div>

                    <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                        <div className="flex items-center gap-3">
                            <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-cyan-700 text-white">
                                <MonitorPlay className="h-6 w-6" />
                            </div>
                            <h2 className="text-2xl font-bold text-slate-950">Delivery Options</h2>
                        </div>
                        <div className="mt-6 grid gap-3 sm:grid-cols-2">
                            {deliveryOptions.map((option) => (
                                <div key={option} className="rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm font-semibold text-slate-700">
                                    {option}
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                <section className="bg-slate-950 py-14 text-white">
                    <div className="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8 lg:items-center">
                        <div>
                            <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-white text-slate-950">
                                <Award className="h-6 w-6" />
                            </div>
                            <h2 className="mt-5 text-3xl font-bold">Certification</h2>
                            <p className="mt-4 max-w-2xl leading-7 text-slate-300">
                                Participants who successfully complete the course requirements will receive an IGRCFP Professional Certificate in the relevant subject area.
                            </p>
                        </div>

                        <div className="grid gap-4 sm:grid-cols-3">
                            {[
                                ['Professional', 'Focused on practical professional capability'],
                                ['Relevant', 'Built for regulated and complex environments'],
                                ['Recognised', 'Issued by IGRCFP on successful completion'],
                            ].map(([title, text]) => (
                                <div key={title} className="rounded-lg border border-white/10 bg-white/10 p-5">
                                    <h3 className="font-bold text-white">{title}</h3>
                                    <p className="mt-2 text-sm leading-6 text-slate-300">{text}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                <section className="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                    <div className="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
                        <div className="grid lg:grid-cols-[1fr_1.2fr]">
                            <div className="bg-blue-900 p-8 text-white">
                                <Building2 className="h-9 w-9" />
                                <h2 className="mt-5 text-3xl font-bold">Enrolment and Partnerships</h2>
                                <p className="mt-4 leading-7 text-blue-100">
                                    Register for a course or discuss corporate training, in-house delivery, and partnership opportunities with IGRCFP.
                                </p>
                                <Link
                                    href="/contact"
                                    className="mt-7 inline-flex items-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-semibold text-blue-950 transition hover:bg-blue-50"
                                >
                                    Contact IGRCFP
                                    <ArrowRight className="h-4 w-4" />
                                </Link>
                            </div>

                            <div className="grid gap-4 p-8 sm:grid-cols-3">
                                <a
                                    href="https://www.igrcfp.org"
                                    className="rounded-lg border border-slate-200 bg-slate-50 p-5 transition hover:border-blue-200 hover:bg-blue-50"
                                >
                                    <BookOpen className="h-6 w-6 text-blue-900" />
                                    <div className="mt-4 text-sm font-bold text-slate-950">Website</div>
                                    <div className="mt-1 text-sm text-slate-600">www.igrcfp.org</div>
                                </a>

                                <a
                                    href="mailto:training@igrcfp.org"
                                    className="rounded-lg border border-slate-200 bg-slate-50 p-5 transition hover:border-blue-200 hover:bg-blue-50"
                                >
                                    <Mail className="h-6 w-6 text-blue-900" />
                                    <div className="mt-4 text-sm font-bold text-slate-950">Training Enquiries</div>
                                    <div className="mt-1 text-sm text-slate-600">training@igrcfp.org</div>
                                </a>

                                <a
                                    href="mailto:partnerships@igrcfp.org"
                                    className="rounded-lg border border-slate-200 bg-slate-50 p-5 transition hover:border-blue-200 hover:bg-blue-50"
                                >
                                    <Users className="h-6 w-6 text-blue-900" />
                                    <div className="mt-4 text-sm font-bold text-slate-950">Partnerships</div>
                                    <div className="mt-1 text-sm text-slate-600">partnerships@igrcfp.org</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </GuestLayout>
    );
}
