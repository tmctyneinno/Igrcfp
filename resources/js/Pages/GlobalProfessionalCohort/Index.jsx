import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { motion } from "framer-motion";
import { fadeIn } from "@/utils/motionPresets";
import CallToAction from "@/Pages/components/CallToAction";
import ApplyModalCohort from "@/Pages/components/ApplyModalCohort";

const keyDates = [
    {
        date: "30 September 2026",
        what: "Admission decisions issued",
        todo: "Check your email, including junk folders. Complete enrolment promptly — orientation is two days later.",
    },
    {
        date: "2 October 2026",
        what: "Global Student Orientation",
        todo: "Attend live if you possibly can. Joining details are sent by email. Orientation may be compulsory at Diploma level and above.",
    },
    {
        date: "5 October 2026",
        what: "Structured learning commences",
        todo: "Have your access working and your study time already blocked out in your calendar.",
    },
    {
        date: "TBC",
        what: "First assessment point",
        todo: "Confirmed in the assessment calendar issued at orientation.",
    },
];

const pathwayLevels = [
    { level: "Professional Development / CPD", duration: "Varies; available year-round", who: "Focused development in a specific professional area." },
    { level: "Professional Qualification Certificate", duration: "8–12 weeks", who: "Establishing or strengthening specialist knowledge in a discipline." },
    { level: "Diploma", duration: "16–20 weeks", who: "Broader capability and deeper applied knowledge across a discipline." },
    { level: "Advanced Diploma", duration: "20–24 weeks", who: "Experienced practitioners, managers and specialists." },
    { level: "Professional Postgraduate Diploma", duration: "6–9 months", who: "The highest level of the current taught pathway; advanced applied practice.", highlight: true },
];

const expectFromInstitute = [
    "Published criteria, so you always know how you are being marked.",
    "Written examiner comment on your work, not just a mark.",
    "A named contact who replies, and enquiries@igrcfp.org behind them.",
    "Decisions made under a policy you can read.",
    "Confidential treatment of anything personal you disclose.",
];

const expectFromCandidate = [
    "Work that is your own, every time, without exception.",
    "Submissions on time, in the format asked for.",
    "That you tell us early when something is going wrong, rather than late.",
    "Professional conduct towards your fellow candidates and the faculty.",
    "Engagement with the cohort — this works better when you are not doing it alone.",
];

const studyTips = [
    "Block your study time in your calendar now, before the programme starts, and treat those blocks as you would a client meeting.",
    "Two shorter sessions a week beat one long one. Judgement questions need thinking time between sittings.",
    "Start Part B answers early enough to leave them and come back. First drafts are rarely defensible; second drafts usually are.",
    "Tell whoever needs to know at work and at home what you have committed to. Support you have not asked for tends not to arrive.",
    "If you fall behind, contact us in the week it happens. Almost everything is recoverable early and very little is recoverable late.",
];

const checklist = [
    "Attend the Global Student Orientation on 2 October, or watch the recording",
    "Confirm you can log in and reach your programme materials",
    "Confirm you know where and how to submit work",
    "Read the criterion table and AI rules in your assessment brief",
    "Block your weekly study time in your calendar",
    "Save enquiries@igrcfp.org as your contact for the programme",
    "Tell us about any reasonable adjustment you may need",
    "Send us any question you are still unsure about",
];

const contacts = [
    { topic: "Anything about your studies", contact: "enquiries@igrcfp.org" },
    { topic: "Access and technical problems", contact: "enquiries@igrcfp.org" },
    { topic: "Assessment and results", contact: "enquiries@igrcfp.org" },
    { topic: "Academic integrity questions", contact: "enquiries@igrcfp.org" },
    { topic: "Reasonable adjustments", contact: "enquiries@igrcfp.org" },
    { topic: "Appeals, complaints, withdrawal or deferral", contact: "enquiries@igrcfp.org" },
    { topic: "Regional Chapters", contact: "www.igrcfp.org/chapters" },
    { topic: "The Institute", contact: "www.igrcfp.org" },
];

function SectionHeading({ eyebrow, title, lead }) {
    return (
        <motion.div
            variants={fadeIn}
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true }}
            className="mb-10 max-w-3xl"
        >
            {eyebrow && (
                <span className="text-xs font-bold tracking-widest text-amber-600 uppercase mb-2 block">
                    {eyebrow}
                </span>
            )}
            <h2 className="text-2xl md:text-3xl font-bold text-blue-900 mb-3">{title}</h2>
            {lead && <p className="text-gray-600 leading-relaxed">{lead}</p>}
        </motion.div>
    );
}

function CheckIcon({ className = "w-4 h-4" }) {
    return (
        <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
        </svg>
    );
}

export default function CandidateWelcomePack({ auth, title }) {
    const [isApplyOpen, setIsApplyOpen] = useState(false);

    return (
        <GuestLayout auth={auth}>
            <Head title="Candidate Welcome Pack — Global Professional Cohort | IGRCFP" />

            {/* Hero Section */}
            <section className="w-full bg-[#0A1A2F] text-white pt-28 pb-10 relative overflow-hidden">
                <div
                    className="absolute inset-0 bg-cover bg-center opacity-20"
                    style={{ backgroundImage: "url('/assets/images/professionals-global-team.jpg')" }}
                />
                <div className="absolute inset-0 bg-gradient-to-b from-[#0A1A2F]/60 via-[#0A1A2F]/90 to-[#0A1A2F]" />

                <div className="max-w-7xl mx-auto px-6 lg:px-8 relative">
                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="text-left"
                    >
                        <div className="flex items-center gap-4 mb-4">
                            <motion.span
                                initial={{ width: 0 }}
                                whileInView={{ width: 48 }}
                                transition={{ duration: 0.5, ease: "easeOut" }}
                                viewport={{ once: true }}
                                className="h-px bg-gray-300"
                            />
                            <span className="text-sm tracking-widest text-gray-300 uppercase">
                                Candidate Welcome Pack . Cohort October 2026
                            </span>
                        </div>

                        <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">
                            {title || "Global Professional Cohort"}
                        </h1>

                        <p className="text-lg md:text-xl text-gray-300 max-w-3xl">
                            Please read this before the Global Student Orientation on Friday 2 October 2026.
                        </p>
                    </motion.div>

                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        transition={{ delay: 0.2 }}
                        className="mt-12 pt-4 border-t border-gray-700 flex flex-wrap gap-x-2 gap-y-2 text-xs uppercase tracking-wider text-gray-300"
                    >
                        <span>Admissions</span>
                        <span>•</span>
                        <span>Orientation</span>
                        <span>•</span>
                        <span>Assessment</span>
                        <span>•</span>
                        <span>Academic Integrity</span>
                        <span>•</span>
                        <span>Membership</span>
                        <span>•</span>
                        <span>Regional Chapters</span>
                    </motion.div>
                </div>
            </section>

            {/* Next Intake */}
            <section className="bg-white py-14 border-b border-gray-100">
                <div className="max-w-6xl mx-auto px-6 lg:px-8">
                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="bg-gray-50 rounded-2xl border border-gray-100 shadow-sm p-8 md:p-10"
                    >
                        <div className="flex flex-col md:flex-row md:items-start md:justify-between gap-6 mb-8">
                            <div>
                                <span className="text-xs font-bold tracking-widest text-amber-600 uppercase mb-2 block">
                                    Next Intake
                                </span>
                                <h2 className="text-2xl md:text-3xl font-bold text-blue-900">October 2026</h2>
                                <p className="text-sm text-gray-600 mt-1">
                                    Applications are open for the next Global Professional Cohort.
                                </p>
                            </div>
                            <div className="flex flex-col sm:flex-row gap-3 flex-shrink-0">
                                <button
                                    type="button"
                                    onClick={() => setIsApplyOpen(true)}
                                    className="inline-flex items-center justify-center bg-blue-900 text-white text-sm font-medium px-5 py-2.5 rounded-full hover:bg-blue-800 transition whitespace-nowrap"
                                >
                                    Apply for October 2026
                                </button>
                                <a
                                    href="mailto:enquiries@igrcfp.org?subject=Help%20choosing%20a%20level"
                                    className="inline-flex items-center justify-center border border-blue-900 text-blue-900 text-sm font-medium px-5 py-2.5 rounded-full hover:bg-blue-50 transition whitespace-nowrap"
                                >
                                    Which level is right for me?
                                </a>
                            </div>
                        </div>

                        {/* Milestone timeline */}
                        <div className="relative">
                            <div className="hidden md:block absolute top-[15px] left-0 right-0 h-px bg-blue-100" />
                            <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6 md:gap-4">
                                {[
                                    { date: "7 Sep", label: "Applications open" },
                                    { date: "25 Sep", label: "Application deadline" },
                                    { date: "30 Sep", label: "Admission decisions" },
                                    { date: "2 Oct", label: "Global Student Orientation" },
                                    { date: "5 Oct", label: "Learning commences" },
                                ].map((m) => (
                                    <div key={m.date} className="relative flex flex-col items-start md:items-center md:text-center">
                                        <span className="hidden md:block w-[10px] h-[10px] rounded-full bg-blue-900 mb-3 relative z-10" />
                                        <p className="text-sm font-bold text-blue-900">{m.date}</p>
                                        <p className="text-xs text-gray-600 mt-1 leading-snug">{m.label}</p>
                                    </div>
                                ))}
                            </div>
                        </div>

                        <p className="text-sm text-gray-600 leading-relaxed mt-8 pt-6 border-t border-gray-200">
                            Whether you are beginning your career, strengthening specialist knowledge, preparing for greater responsibility or progressing towards senior leadership, the pathway provides a structured route for continued development.
                        </p>
                    </motion.div>
                </div>
            </section>

            {/* Welcome letter */}
            <section className="bg-white py-16">
                <div className="max-w-6xl mx-auto px-6 lg:px-8">
                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="bg-gray-50 rounded-2xl border border-gray-100 shadow-sm p-8 md:p-12 relative"
                    >
                        <span className="absolute top-6 right-8 text-6xl text-blue-100 font-serif leading-none select-none">
                            "
                        </span>
                        <span className="text-xs font-bold tracking-widest text-amber-600 uppercase mb-4 block">
                            Overview
                        </span>
                        <h2 className="text-2xl md:text-3xl font-bold text-blue-900 mb-4">What the Cohort Programme is</h2>

                        <p className="text-gray-700 leading-relaxed mb-4">
                            You have been admitted to the Global Professional Cohort for October 2026. That is worth pausing on: you are joining a group of practitioners who have decided that doing this work well matters enough to be examined on it.
                        </p>
                        <p className="text-gray-700 leading-relaxed mb-4">
                            I want to be straightforward with you about what the next months will ask of you. The programmes are not difficult because the material is obscure. They are difficult because we assess judgement, and judgement cannot be memorised. In Part B you will be asked what you would do, in a situation with no clean answer, and then asked to justify it. Candidates who do well are not the ones who have read the most. They are the ones who can hold a position and evidence it.
                        </p>
                        <p className="text-gray-700 leading-relaxed mb-4">
                            You will get more out of this if you treat it as professional practice rather than as study. Bring your own cases. Argue with the material. Ask the question you think you should already know the answer to nobody has ever been marked down for asking it.
                        </p>
                        <p className="text-gray-700 leading-relaxed mb-8">
                            I look forward to meeting you at orientation.
                        </p>
                    </motion.div>
                </div>
            </section>

            {/* Key dates */}
            <section className="bg-gray-50 py-16">
                <div className="max-w-6xl mx-auto px-6 lg:px-8">
                    <SectionHeading
                        eyebrow="Timeline"
                        title="October 2026"
                        lead="All deadlines are enforced in a single reference time zone, which will be stated at orientation. Check what that means for your local time before your first submission, not on the day of it."
                    />
                    <div className="relative">
                        <div className="hidden md:block absolute left-[27px] top-2 bottom-2 w-px bg-blue-100" />
                        <div className="space-y-4">
                            {keyDates.map((d, i) => (
                                <motion.div
                                    key={d.date + i}
                                    variants={fadeIn}
                                    initial="hidden"
                                    whileInView="visible"
                                    viewport={{ once: true }}
                                    transition={{ delay: i * 0.05 }}
                                    className="relative flex flex-col md:flex-row gap-4 md:gap-8 bg-white rounded-xl border border-gray-100 shadow-sm p-6 md:pl-16"
                                >
                                    <div className="hidden md:flex absolute left-5 top-6 w-4 h-4 rounded-full bg-blue-900 border-4 border-blue-100" />
                                    <div className="md:w-48 flex-shrink-0">
                                        <p className="text-sm font-bold text-blue-900">{d.date}</p>
                                    </div>
                                    <div className="md:w-56 flex-shrink-0">
                                        <p className="text-sm font-semibold text-gray-800">{d.what}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm text-gray-600 leading-relaxed">{d.todo}</p>
                                    </div>
                                </motion.div>
                            ))}
                        </div>
                    </div>
                </div>
            </section>

            {/* Qualification pathway */}
            <section className="bg-white py-16">
                <div className="max-w-6xl mx-auto px-6 lg:px-8">
                    <SectionHeading
                        eyebrow="Your Programme"
                        title="The qualification pathway"
                        lead="You have been admitted at one level of a progressive pathway. The cohort defines when your structured journey begins; your qualification determines how long it lasts, so candidates entering the same intake do not all finish together."
                    />
                    <div className="overflow-x-auto rounded-2xl border border-gray-100 shadow-sm">
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="bg-blue-900 text-white text-left">
                                    <th className="px-5 py-3 font-semibold">Level</th>
                                    <th className="px-5 py-3 font-semibold">Typical duration</th>
                                    <th className="px-5 py-3 font-semibold">Who it is for</th>
                                </tr>
                            </thead>
                            <tbody>
                                {pathwayLevels.map((row, i) => (
                                    <tr
                                        key={row.level}
                                        className={`border-t border-gray-100 ${row.highlight ? "bg-amber-50" : i % 2 === 0 ? "bg-white" : "bg-gray-50"}`}
                                    >
                                        <td className="px-5 py-4 font-medium text-gray-800">
                                            {row.level}
                                            {row.highlight && (
                                                <span className="ml-2 text-[10px] font-bold uppercase tracking-wide text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full align-middle">
                                                    Highest level
                                                </span>
                                            )}
                                        </td>
                                        <td className="px-5 py-4 text-gray-600 whitespace-nowrap">{row.duration}</td>
                                        <td className="px-5 py-4 text-gray-600">{row.who}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <p className="text-sm text-gray-500 mt-4">
                        Programmes are offered across the Institute's principal disciplines: Governance, Risk & Compliance; Anti-Money Laundering; Financial Crime Prevention; Cybersecurity & Digital Risk; AI & Emerging Technology; Crypto & Digital Assets; ESG Risk; and RegTech. Completing one level does not automatically admit you to the next — progression is subject to successful completion and any applicable entry requirements.
                    </p>
                </div>
            </section>

            {/* Assessment */}
            <section className="bg-blue-900 py-16">
                <div className="max-w-6xl mx-auto px-6 lg:px-8">
                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="mb-10 max-w-3xl"
                    >
                        <span className="text-xs font-bold tracking-widest text-amber-400 uppercase mb-2 block">
                            Assessment
                        </span>
                        <h2 className="text-2xl md:text-3xl font-bold text-white mb-3">How you will be assessed</h2>
                        <p className="text-blue-100 leading-relaxed">
                            Assessment method reflects the level and learning outcomes of your qualification. Many programmes use the Institute's two-part house format below; your assessment brief is authoritative for your programme.
                        </p>
                    </motion.div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <motion.div
                            variants={fadeIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            className="bg-white/5 border border-white/10 rounded-2xl p-8"
                        >
                            <span className="inline-flex w-10 h-10 rounded-full bg-amber-400 text-blue-900 items-center justify-center font-bold mb-4">A</span>
                            <h3 className="text-lg font-bold text-white mb-3">Part A — Knowledge check</h3>
                            <p className="text-sm text-blue-100 leading-relaxed">
                                A quiz component testing whether you have the working knowledge the material assumes. It is closed, timed, and it is the part candidates most often underestimate. Do not leave it to the end of your revision.
                            </p>
                        </motion.div>
                        <motion.div
                            variants={fadeIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            transition={{ delay: 0.1 }}
                            className="bg-white/5 border border-white/10 rounded-2xl p-8"
                        >
                            <span className="inline-flex w-10 h-10 rounded-full bg-amber-400 text-blue-900 items-center justify-center font-bold mb-4">B</span>
                            <h3 className="text-lg font-bold text-white mb-3">Part B — Applied essay</h3>
                            <p className="text-sm text-blue-100 leading-relaxed">
                                This is where the marks and the difficulty live. You will be asked to apply a framework to a situation, take a position, and defend it. Answers that summarise what the framework says, without applying it, score poorly however accurate they are.
                            </p>
                        </motion.div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <motion.div
                            variants={fadeIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            className="bg-white/5 border border-white/10 rounded-2xl p-6"
                        >
                            <h4 className="text-sm font-bold text-amber-400 uppercase tracking-wide mb-3">Results and awards</h4>
                            <ul className="space-y-2 text-sm text-blue-100">
                                <li>Where classification applies, awards are classified Pass, Merit or Distinction.</li>
                                <li>Diploma and higher-level awards may include a professional transcript showing modules and results.</li>
                                <li>Resit and reassessment arrangements depend on your programme and the applicable academic regulations.</li>
                            </ul>
                        </motion.div>
                        <motion.div
                            variants={fadeIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            transition={{ delay: 0.1 }}
                            className="bg-white/5 border border-white/10 rounded-2xl p-6"
                        >
                            <h4 className="text-sm font-bold text-amber-400 uppercase tracking-wide mb-3">Marking</h4>
                            <ul className="space-y-2 text-sm text-blue-100">
                                <li>Your work is marked against a published criterion table, with a written examiner comment per criterion.</li>
                                <li>You receive a marking sheet showing the mark, result, comment and academic integrity status.</li>
                                <li>Results are issued under seal by the IGRCFP Examiner Board.</li>
                            </ul>
                        </motion.div>
                    </div>
                    <p className="text-xs text-blue-200 mt-6 italic">
                        The criterion table will be shown at orientation. Read it before you write, not after you get your mark back — it tells you exactly what the examiner is looking for.
                    </p>
                </div>
            </section>

            {/* Academic integrity & AI */}
            <section className="bg-white py-16">
                <div className="max-w-6xl mx-auto px-6 lg:px-8">
                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="mb-10 max-w-3xl"
                    >
                        <span className="text-xs font-bold tracking-widest text-red-600 uppercase mb-2 block">
                            Read carefully
                        </span>
                        <h2 className="text-2xl md:text-3xl font-bold text-blue-900 mb-3">Academic integrity and the use of AI</h2>
                        <p className="text-gray-600 leading-relaxed">
                            This section matters more than any other in this pack. Please read it carefully, and ask us if any part of it is unclear.
                        </p>
                    </motion.div>

                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="bg-amber-50 border border-amber-200 rounded-2xl p-8 mb-8"
                    >
                        <h3 className="text-sm font-bold text-amber-800 uppercase tracking-wide mb-3">The position</h3>
                        <p className="text-gray-700 leading-relaxed mb-3">
                            The work you submit must be your own. The Institute recognises that AI is now a professional tool, and permits its use for learning support, research planning, organising ideas and proofreading where your programme allows. It must never be used to present work that is not yours as evidence of your own professional competence.
                        </p>
                        <p className="text-gray-700 leading-relaxed">
                            Where a numerical AI-assistance threshold applies to your programme it is stated in your assessment brief; the Institute's current published threshold is <strong>20 per cent</strong>. Your assessment brief is authoritative. If it and this pack ever appear to differ, follow the brief and tell us so we can correct the discrepancy.
                        </p>
                    </motion.div>

                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <motion.div
                            variants={fadeIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            className="bg-gray-50 rounded-2xl border border-gray-100 p-6"
                        >
                            <h4 className="text-sm font-bold text-blue-900 uppercase tracking-wide mb-3">What counts as academic misconduct</h4>
                            <p className="text-sm text-gray-700 leading-relaxed">
                                Plagiarism; contract cheating; impersonation; unauthorised collaboration; falsification of information; fabricated sources or evidence; examination misconduct; and inappropriate or undisclosed use of artificial intelligence in assessed work.
                            </p>
                        </motion.div>
                        <motion.div
                            variants={fadeIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            transition={{ delay: 0.1 }}
                            className="bg-gray-50 rounded-2xl border border-gray-100 p-6"
                        >
                            <h4 className="text-sm font-bold text-blue-900 uppercase tracking-wide mb-3">How this is handled in practice</h4>
                            <ul className="space-y-2 text-sm text-gray-700">
                                <li>Detection indicators can trigger authorship testing under IGRCFP/AQ/PRO/004. A trigger is an enquiry, not a finding.</li>
                                <li>Disclosing permitted AI use never counts against you; undisclosed use where disclosure was required does.</li>
                                <li>Under IGRCFP/AQ/POL/003 §6, no numerical AI score may be cited as a ground of decision.</li>
                                <li>Assessment records are retained for six years under IGRCFP/AQ/POL/003 §10.</li>
                            </ul>
                        </motion.div>
                    </div>

                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="bg-blue-900 rounded-2xl p-8"
                    >
                        <h4 className="text-sm font-bold text-amber-400 uppercase tracking-wide mb-4">What we would ask of you</h4>
                        <ul className="space-y-3">
                            {[
                                "Use tools to understand material, not to produce your answer.",
                                "Keep your notes, drafts and sources — the easiest evidence of your own authorship, and they cost you nothing to retain.",
                                "If you are unsure whether a particular use is acceptable, ask before you submit. Asking will never be held against you. Submitting and hoping might be.",
                            ].map((item) => (
                                <li key={item} className="flex items-start gap-3 text-sm text-blue-100">
                                    <span className="text-amber-400 mt-0.5 flex-shrink-0"><CheckIcon /></span>
                                    {item}
                                </li>
                            ))}
                        </ul>
                    </motion.div>
                </div>
            </section>

            {/* What to expect */}
            <section className="bg-gray-50 py-16">
                <div className="max-w-6xl mx-auto px-6 lg:px-8">
                    <SectionHeading eyebrow="Mutual commitment" title="What to expect, and what we expect" />
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <motion.div
                            variants={fadeIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            className="bg-white rounded-2xl border border-gray-100 shadow-sm p-8"
                        >
                            <h3 className="text-sm font-bold text-blue-900 uppercase tracking-wide mb-4">You can expect from the Institute</h3>
                            <ul className="space-y-3">
                                {expectFromInstitute.map((item) => (
                                    <li key={item} className="flex items-start gap-3 text-sm text-gray-700">
                                        <span className="text-green-600 mt-0.5 flex-shrink-0"><CheckIcon /></span>
                                        {item}
                                    </li>
                                ))}
                            </ul>
                        </motion.div>
                        <motion.div
                            variants={fadeIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            transition={{ delay: 0.1 }}
                            className="bg-white rounded-2xl border border-gray-100 shadow-sm p-8"
                        >
                            <h3 className="text-sm font-bold text-blue-900 uppercase tracking-wide mb-4">We expect from you</h3>
                            <ul className="space-y-3">
                                {expectFromCandidate.map((item) => (
                                    <li key={item} className="flex items-start gap-3 text-sm text-gray-700">
                                        <span className="text-blue-900 mt-0.5 flex-shrink-0"><CheckIcon /></span>
                                        {item}
                                    </li>
                                ))}
                            </ul>
                        </motion.div>
                    </div>
                </div>
            </section>

            {/* Making the time */}
            <section className="relative py-16 overflow-hidden">
                <div
                    className="absolute inset-0 bg-cover bg-center"
                    style={{ backgroundImage: "url('/assets/images/professionals-silhouette.avif')" }}
                />
                <div className="absolute inset-0 bg-[#0A1A2F]/95" />

                <div className="relative max-w-5xl mx-auto px-6 lg:px-8">
                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="mb-10"
                    >
                        <span className="text-xs font-bold tracking-widest text-amber-400 uppercase mb-2 block">
                            Study strategy
                        </span>
                        <h2 className="text-2xl md:text-3xl font-bold text-white mb-3">Making the time</h2>
                        <p className="text-blue-100 leading-relaxed">
                            The most common reason capable candidates struggle is not difficulty. It is that the study time was never actually protected, and the programme lost every collision with work.
                        </p>
                    </motion.div>
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {studyTips.map((tip, i) => (
                            <motion.div
                                key={tip}
                                variants={fadeIn}
                                initial="hidden"
                                whileInView="visible"
                                viewport={{ once: true }}
                                transition={{ delay: i * 0.05 }}
                                className="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-5"
                            >
                                <p className="text-sm text-blue-100 leading-relaxed">{tip}</p>
                            </motion.div>
                        ))}
                    </div>
                </div>
            </section>

            {/* If something goes wrong */}
            <section className="bg-white py-16">
                <div className="max-w-6xl mx-auto px-6 lg:px-8">
                    <SectionHeading eyebrow="Support" title="If something goes wrong" />
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <motion.div
                            variants={fadeIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            className="bg-gray-50 rounded-2xl border border-gray-100 p-6"
                        >
                            <h3 className="text-base font-bold text-blue-900 mb-3">Reasonable adjustments</h3>
                            <p className="text-sm text-gray-600 leading-relaxed">
                                If a disability, health condition, caring responsibility or other circumstance affects how you can study or be assessed, tell us as early as you can — ideally at enrolment and before your first assessment. Adjustments are considered on their merits and handled confidentially, and have no bearing on how your work is marked.
                            </p>
                        </motion.div>
                        <motion.div
                            variants={fadeIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            transition={{ delay: 0.1 }}
                            className="bg-gray-50 rounded-2xl border border-gray-100 p-6"
                        >
                            <h3 className="text-base font-bold text-blue-900 mb-3">Appeals and complaints</h3>
                            <p className="text-sm text-gray-600 leading-relaxed">
                                You may ask for an assessment decision to be reviewed under the applicable academic regulations. An appeal is a review of whether the decision was properly reached, not a re-marking on request. Concerns about service rather than a result follow a separate complaints route. Both start at enquiries@igrcfp.org and neither prejudices your standing on the programme.
                            </p>
                        </motion.div>
                        <motion.div
                            variants={fadeIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            transition={{ delay: 0.2 }}
                            className="bg-gray-50 rounded-2xl border border-gray-100 p-6"
                        >
                            <h3 className="text-base font-bold text-blue-900 mb-3">Withdrawal and deferral</h3>
                            <p className="text-sm text-gray-600 leading-relaxed">
                                If you cannot continue, contact us rather than simply stopping. Because the Institute runs three intakes a year — February, June and October — deferring to the next cohort is often possible and usually a better outcome than withdrawing. Fee, refund and deferral terms are set out in your enrolment documentation.
                            </p>
                        </motion.div>
                    </div>
                </div>
            </section>

            {/* Beyond your qualification */}
            <section className="bg-blue-900 py-16">
                <div className="max-w-6xl mx-auto px-6 lg:px-8">
                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="mb-10 max-w-3xl"
                    >
                        <span className="text-xs font-bold tracking-widest text-amber-400 uppercase mb-2 block">
                            Your ongoing journey
                        </span>
                        <h2 className="text-2xl md:text-3xl font-bold text-white mb-3">Beyond your qualification</h2>
                        <p className="text-blue-100 leading-relaxed">
                            Your relationship with the Institute need not end when your programme does.
                        </p>
                    </motion.div>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <motion.div
                            variants={fadeIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            className="bg-white/5 border border-white/10 rounded-2xl p-6"
                        >
                            <h3 className="text-base font-bold text-white mb-2">Professional membership</h3>
                            <p className="text-sm text-blue-100 leading-relaxed mb-4">
                                The membership pathway runs Associate Member (AIGRCFP), Member (MIGRCFP) and Fellow (FIGRCFP). Completing a qualification does not by itself confer a membership grade — professional experience, standing and other criteria are also considered.
                            </p>
                            <Link href={route('membership')} className="text-sm font-medium text-amber-400 hover:text-amber-300">
                                Explore membership →
                            </Link>
                        </motion.div>
                        <motion.div
                            variants={fadeIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            transition={{ delay: 0.1 }}
                            className="bg-white/5 border border-white/10 rounded-2xl p-6"
                        >
                            <h3 className="text-base font-bold text-white mb-2">Regional Chapters</h3>
                            <p className="text-sm text-blue-100 leading-relaxed mb-4">
                                Candidates are encouraged to engage with the Regional Chapter for their country or region, for networking, CPD, mentoring, events and research participation.
                            </p>
                            <Link href={route('chapters.index')} className="text-sm font-medium text-amber-400 hover:text-amber-300">
                                Find your Chapter →
                            </Link>
                        </motion.div>
                        <motion.div
                            variants={fadeIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            transition={{ delay: 0.2 }}
                            className="bg-white/5 border border-white/10 rounded-2xl p-6"
                        >
                            <h3 className="text-base font-bold text-white mb-2">Progression</h3>
                            <p className="text-sm text-blue-100 leading-relaxed">
                                Candidates completing one level are encouraged to consider the next at a subsequent intake — a Certificate completed in the October cohort can lead into a Diploma in February, and onward from there where entry requirements are met.
                            </p>
                        </motion.div>
                    </div>
                </div>
            </section>

            {/* Before 5 October checklist */}
            <section className="bg-gray-50 py-16">
                <div className="max-w-4xl mx-auto px-6 lg:px-8">
                    <SectionHeading eyebrow="Do this now" title="Before 5 October" />
                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="bg-white rounded-2xl border border-gray-100 shadow-sm divide-y divide-gray-100"
                    >
                        {checklist.map((item) => (
                            <div key={item} className="flex items-center gap-4 px-6 py-4">
                                <span className="w-5 h-5 rounded border-2 border-blue-200 flex-shrink-0" />
                                <p className="text-sm text-gray-700">{item}</p>
                            </div>
                        ))}
                    </motion.div>
                </div>
            </section>

            {/* Contacts */}
            <section className="bg-white py-16">
                <div className="max-w-4xl mx-auto px-6 lg:px-8">
                    <SectionHeading eyebrow="Get in touch" title="Contacts" />
                    <div className="overflow-hidden rounded-2xl border border-gray-100 shadow-sm">
                        <table className="w-full text-sm">
                            <tbody>
                                {contacts.map((c, i) => (
                                    <tr key={c.topic} className={i % 2 === 0 ? "bg-white" : "bg-gray-50"}>
                                        <td className="px-5 py-3.5 text-gray-700 border-t border-gray-100">{c.topic}</td>
                                        <td className="px-5 py-3.5 font-medium text-blue-900 border-t border-gray-100 whitespace-nowrap">
                                            {c.contact}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <p className="text-xs text-gray-400 mt-6 text-center">
                        The Institute of Governance, Risk, Compliance & Financial Crime Prevention · enquiries@igrcfp.org · www.igrcfp.org
                    </p>
                </div>
            </section>

            {/* CTA */}
            <CallToAction />

            {/* Apply modal */}
            <ApplyModalCohort
                isOpen={isApplyOpen}
                onClose={() => setIsApplyOpen(false)}
                cohort="October 2026"
            />
        </GuestLayout>
    );
}