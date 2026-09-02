import React from "react";
import { Link } from "@inertiajs/react";

const milestones = [
    { date: "7 Sep", label: "Applications open" },
    { date: "25 Sep", label: "Application deadline" },
    { date: "30 Sep", label: "Admission decisions" },
    { date: "2 Oct", label: "Orientation" },
    { date: "5 Oct", label: "Learning commences" },
];

export default function CohortBanner() {
    return (
        <section className="bg-blue-900 relative overflow-hidden">
            <div
                className="absolute inset-0 bg-cover bg-center opacity-10"
                style={{ backgroundImage: "url('/assets/images/professionals-global-team.jpg')" }}
            />
            <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div className="flex flex-col lg:flex-row items-center justify-between gap-6">
                    <div className="flex flex-col sm:flex-row items-center gap-4 sm:gap-6 text-center sm:text-left">
                        <span className="inline-flex items-center gap-2 bg-amber-400 text-blue-900 text-xs font-bold uppercase tracking-wide px-3 py-1.5 rounded-full whitespace-nowrap">
                            Next Cohort
                        </span>
                        <div>
                            <h3 className="text-lg sm:text-xl font-bold text-white">
                                Global Professional Cohort — October 2026
                            </h3>
                            <p className="text-blue-100 text-sm mt-0.5">
                                Applications are open. Three intakes a year — this is your next window in.
                            </p>
                        </div>
                    </div>

                    {/* Compact milestone strip — desktop only */}
                    <div className="hidden xl:flex items-center gap-4 text-center px-4">
                        {milestones.map((m, i) => (
                            <React.Fragment key={m.date}>
                                <div>
                                    <p className="text-sm font-bold text-amber-400">{m.date}</p>
                                    <p className="text-[11px] text-blue-200 whitespace-nowrap">{m.label}</p>
                                </div>
                                {i < milestones.length - 1 && (
                                    <span className="w-6 h-px bg-white/20" />
                                )}
                            </React.Fragment>
                        ))}
                    </div>

                    <Link
                        href={route('global-professional-cohort.index')}
                        className="inline-flex items-center justify-center bg-white text-blue-900 text-sm font-bold px-6 py-3 rounded-full hover:bg-amber-400 transition whitespace-nowrap shadow-lg"
                    >
                        View Cohort Details →
                    </Link>
                </div>
            </div>
        </section>
    );
}