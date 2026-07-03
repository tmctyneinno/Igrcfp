import { Link } from "@inertiajs/react";
import React from "react";

export default function MembershipPathways() {
    const membershipPlans = [
        {
            type: "AFFILIATE",
            title: "Associate",
            description: "For students, early career professionals, and those exploring the GRC and financial crime space.",
            features: [
                "Access to member resources library",
                "Monthly newsletter & digest",
                "Community forum access",
                "Discounted event rates"
            ],
            isPopular: false
        },
        {
            type: "FULL MEMBER",
            title: "Member IGRCFP",
            description: "For qualified professionals with relevant experience seeking full designation and recognition.",
            features: [
                "Post-nominal designation (MIGRCFP)",
                "CPD framework & tracking",
                "Technical guidance papers",
                "Peer network & mentoring",
                "Priority event access"
            ],
            isPopular: true
        },
        {
            type: "SENIOR GRADE",
            title: "Fellow",
            description: "Recognising exceptional contribution to the profession at a senior or leadership level.",
            features: [
                "Fellowship designation (FIGRCFP)",
                "Governance & advisory roles",
                "Speaking & thought leadership",
                "Exclusive senior roundtables",
                "Global partner network"
            ],
            isPopular: false
        },
        {
            type: "CORPORATE",
            title: "Institutional",
            description: "For organisations seeking to align their teams with professional standards and ongoing education.",
            features: [
                "Unlimited team enrolment",
                "Bespoke training programmes",
                "Brand visibility & partnership",
                "Regulatory briefings",
                "Dedicated account support"
            ],
            isPopular: false
        }
    ];

    return (
        <section className="py-16 bg-gray-50">
            <div className="max-w-7xl mx-auto px-6">
                {/* Header Section */}
                <div className="mb-12">
                    <div className="relative inline-flex items-center mb-3">
                        <span className="absolute left-0 top-1/2 w-12 h-px bg-slate-300 -z-10"></span>
                        <span className="text-sm tracking-widest text-slate-500 pl-16 uppercase font-medium">
                            Our Packages
                        </span>
                    </div>
                    <h2 className="text-4xl lg:text-5xl font-bold text-slate-900 leading-tight">
                        EXPLORE <span className="text-[#0A2463]">MEMBERSHIP</span><br />
                        PATHWAYS
                    </h2>
                    <p className="mt-4 text-slate-600 max-w-3xl">
                        Take your professional journey to the next level by joining IGRCFP. Membership gives you exclusive access to a global network of governance, risk, compliance, and financial crime professionals, cutting-edge research, specialized training, and career-boosting opportunities. Enjoy discounts on courses and events, CPD credits, and the use of prestigious post-nominals that highlight your expertise. Whether you're in banking, fintech, insurance, or regulatory roles, IGRCFP membership equips you with the knowledge, recognition, and connections to excel and make an impact in your field.
                    </p>
                </div>

                {/* Membership Cards Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {membershipPlans.map((plan, index) => (
                        <div 
                            key={index}
                            className={`relative bg-white rounded-lg border ${
                                plan.isPopular 
                                    ? "border-[#0A2463] shadow-md" 
                                    : "border-slate-200"
                            } p-6 h-full flex flex-col`}
                        >
                            {/* Popular Badge */}
                            {plan.isPopular && (
                                <div className="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-[#0A2463] text-white text-xs font-semibold px-4 py-1 rounded-full uppercase tracking-wider">
                                    Popular
                                </div>
                            )}

                            <div className="mb-4">
                                <span className="text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    {plan.type}
                                </span>
                                <h3 className="text-2xl font-bold text-slate-900 mt-1">
                                    {plan.title}
                                </h3>
                                <p className="text-sm text-slate-600 mt-3 leading-relaxed">
                                    {plan.description}
                                </p>
                            </div>

                            <ul className="space-y-2 mt-4 flex-grow">
                                {plan.features.map((feature, idx) => (
                                    <li key={idx} className="flex items-start text-sm text-slate-700">
                                        <span className="mr-2 text-[#0A2463]">—</span>
                                        {feature}
                                    </li>
                                ))}
                            </ul>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}