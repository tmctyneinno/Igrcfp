import React, { useEffect, useState } from "react";
import { useForm } from "@inertiajs/react";
import { AnimatePresence, motion } from "framer-motion";

const LEVEL_OPTIONS = [
    "Not sure yet",
    "Professional Development & CPD",
    "Professional Qualification Certificate",
    "Diploma",
    "Advanced Diploma",
    "Professional Postgraduate Diploma",
];

const DISCIPLINE_OPTIONS = [
    "Governance, Risk & Compliance",
    "Anti-Money Laundering / CFT",
    "Financial Crime Prevention",
    "Cyber Risk & Digital Governance",
    "AI & Emerging Technology Governance",
    "ESG Risk",
    "RegTech",
    "Other / Not sure yet",
];

function FieldLabel({ children, required }) {
    return (
        <label className="block text-sm font-medium text-gray-700 mb-1.5">
            {children}
            {required && <span className="text-red-500 ml-0.5">*</span>}
        </label>
    );
}

function FieldError({ message }) {
    if (!message) return null;
    return <p className="text-xs text-red-600 mt-1">{message}</p>;
}

export default function ApplyModal({ isOpen, onClose, cohort = "October 2026" }) {
    const [submissionComplete, setSubmissionComplete] = useState(false);

    const { data, setData, post, processing, errors, reset } = useForm({
        full_name: "",
        email: "",
        phone: "",
        country: "",
        level: "",
        discipline: "",
        message: "",
        cohort,
    });

    // Lock body scroll while the modal is open
    useEffect(() => {
        document.body.style.overflow = isOpen ? "hidden" : "unset";
        return () => {
            document.body.style.overflow = "unset";
        };
    }, [isOpen]);

    // Close on Escape
    useEffect(() => {
        const handleEsc = (e) => {
            if (e.key === "Escape") onClose();
        };
        if (isOpen) document.addEventListener("keydown", handleEsc);
        return () => document.removeEventListener("keydown", handleEsc);
    }, [isOpen, onClose]);

    const handleSubmit = (e) => {
        e.preventDefault();
        setSubmissionComplete(false);

        post(route("cohort-applications.store"), {
            preserveScroll: true,
            onSuccess: () => {
                reset("full_name", "email", "phone", "country", "level", "discipline", "message");
                setSubmissionComplete(true);
            },
            onError: () => {
                setSubmissionComplete(false);
            },
        });
    };

    const handleClose = () => {
        setSubmissionComplete(false);
        reset("full_name", "email", "phone", "country", "level", "discipline", "message");
        onClose();
    };

    return (
        <AnimatePresence>
            {isOpen && (
                <div className="fixed inset-0 z-[100] flex items-center justify-center p-4">
                    {/* Backdrop */}
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        transition={{ duration: 0.2 }}
                        className="absolute inset-0 bg-[#0A1A2F]/70 backdrop-blur-sm"
                        onClick={handleClose}
                    />

                    {/* Modal panel */}
                    <motion.div
                        initial={{ opacity: 0, y: 24, scale: 0.98 }}
                        animate={{ opacity: 1, y: 0, scale: 1 }}
                        exit={{ opacity: 0, y: 16, scale: 0.98 }}
                        transition={{ duration: 0.22, ease: "easeOut" }}
                        role="dialog"
                        aria-modal="true"
                        aria-labelledby="apply-modal-title"
                        className="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto"
                    >
                        {/* Header */}
                        <div className="sticky top-0 bg-blue-900 text-white px-6 md:px-8 py-6 rounded-t-2xl flex items-start justify-between">
                            <div>
                                <span className="text-xs font-bold tracking-widest text-amber-400 uppercase mb-1 block">
                                    Global Professional Cohort
                                </span>
                                <h2 id="apply-modal-title" className="text-xl md:text-2xl font-bold">
                                    Apply for {cohort}
                                </h2>
                            </div>
                            <button
                                onClick={handleClose}
                                aria-label="Close"
                                className="text-blue-200 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition flex-shrink-0"
                            >
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {/* Body */}
                        {submissionComplete ? (
                            <div className="px-6 md:px-8 py-12 text-center">
                                <div className="w-16 h-16 mx-auto mb-5 rounded-full bg-emerald-100 flex items-center justify-center shadow-sm">
                                    <svg className="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <h3 className="text-xl font-bold text-blue-900 mb-2">Application received</h3>
                                <p className="text-sm text-gray-600 max-w-sm mx-auto mb-6 leading-6">
                                    Thank you for applying to the {cohort} Global Professional Cohort. A member of our admissions team will be in touch by email shortly with the next steps.
                                </p>
                                <button
                                    onClick={handleClose}
                                    className="inline-flex items-center justify-center bg-gradient-to-r from-blue-900 to-blue-700 text-white text-sm font-semibold px-6 py-2.75 rounded-full shadow-lg shadow-blue-900/20 hover:from-blue-800 hover:to-blue-600 transition-all duration-200"
                                >
                                    Done
                                </button>
                            </div>
                        ) : (
                            <form onSubmit={handleSubmit} className="px-6 md:px-8 py-6 space-y-5">
                                <p className="text-sm text-gray-500">
                                    Tell us a little about you and we'll get your application moving. Fields marked * are required.
                                </p>

                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <FieldLabel required>Full name</FieldLabel>
                                        <input
                                            type="text"
                                            value={data.full_name}
                                            onChange={(e) => setData("full_name", e.target.value)}
                                            className="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900"
                                            placeholder="Jane Doe"
                                        />
                                        <FieldError message={errors.full_name} />
                                    </div>
                                    <div>
                                        <FieldLabel required>Email address</FieldLabel>
                                        <input
                                            type="email"
                                            value={data.email}
                                            onChange={(e) => setData("email", e.target.value)}
                                            className="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900"
                                            placeholder="jane@example.com"
                                        />
                                        <FieldError message={errors.email} />
                                    </div>
                                </div>

                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <FieldLabel>Phone number</FieldLabel>
                                        <input
                                            type="tel"
                                            value={data.phone}
                                            onChange={(e) => setData("phone", e.target.value)}
                                            className="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900"
                                            placeholder="+234 800 000 0000"
                                        />
                                        <FieldError message={errors.phone} />
                                    </div>
                                    <div>
                                        <FieldLabel required>Country</FieldLabel>
                                        <input
                                            type="text"
                                            value={data.country}
                                            onChange={(e) => setData("country", e.target.value)}
                                            className="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900"
                                            placeholder="Nigeria"
                                        />
                                        <FieldError message={errors.country} />
                                    </div>
                                </div>

                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <FieldLabel required>Level you're applying for</FieldLabel>
                                        <select
                                            value={data.level}
                                            onChange={(e) => setData("level", e.target.value)}
                                            className="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 bg-white"
                                        >
                                            <option value="" disabled>Select a level</option>
                                            {LEVEL_OPTIONS.map((lvl) => (
                                                <option key={lvl} value={lvl}>{lvl}</option>
                                            ))}
                                        </select>
                                        <FieldError message={errors.level} />
                                    </div>
                                    <div>
                                        <FieldLabel>Area of interest</FieldLabel>
                                        <select
                                            value={data.discipline}
                                            onChange={(e) => setData("discipline", e.target.value)}
                                            className="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 bg-white"
                                        >
                                            <option value="" disabled>Select a discipline</option>
                                            {DISCIPLINE_OPTIONS.map((d) => (
                                                <option key={d} value={d}>{d}</option>
                                            ))}
                                        </select>
                                        <FieldError message={errors.discipline} />
                                    </div>
                                </div>

                                <div>
                                    <FieldLabel>Anything else we should know?</FieldLabel>
                                    <textarea
                                        value={data.message}
                                        onChange={(e) => setData("message", e.target.value)}
                                        rows={3}
                                        className="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 resize-none"
                                        placeholder="Optional — your background, goals, or any questions for admissions."
                                    />
                                    <FieldError message={errors.message} />
                                </div>

                                <div className="flex flex-col sm:flex-row items-center gap-3 pt-2">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 text-white text-sm font-semibold px-6 py-2.75 rounded-full shadow-lg shadow-blue-900/20 hover:shadow-blue-800/30 hover:scale-[1.01] transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:scale-100"
                                    >
                                        {processing ? (
                                            <>
                                                <svg className="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="12" cy="12" r="9" stroke="currentColor" strokeWidth="2" strokeOpacity="0.25" />
                                                    <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
                                                </svg>
                                                Submitting…
                                            </>
                                        ) : (
                                            <>
                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 19l9-11 0 0M3 8l9 11 9-11" />
                                                </svg>
                                                Submit Application
                                            </>
                                        )}
                                    </button>
                                    <p className="text-xs text-gray-400 text-center sm:text-left">
                                        By applying you agree to be contacted by IGRCFP admissions regarding your application.
                                    </p>
                                </div>
                            </form>
                        )}
                    </motion.div>
                </div>
            )}
        </AnimatePresence>
    );
}