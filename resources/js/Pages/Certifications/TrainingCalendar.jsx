import React from "react";
import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Index({ auth, title }) {
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
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                            View upcoming courses, workshops, and programmes at IGRCFP. Choose the format and dates that work for you.
                        </p>
                        <Link
                            href="/prospectus"
                            className="bg-white text-blue-900 px-6 py-3 rounded-xl font-semibold text-lg border-2 border-blue-900 hover:bg-gray-50 transition shadow-md inline-flex"
                        >
                            Download Prospectus
                        </Link>
                    </div>
                </div>
            </section>

            {/* Training Calendar */}
            <section className="py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                    <h2 className="text-3xl font-semibold text-gray-900 mb-8">
                        Training Calendar
                    </h2>

                    {/* Table */}
                    <div className="overflow-x-auto">
                        <table className="min-w-full border border-gray-200 rounded-lg">
                            <thead className="bg-gray-100">
                                <tr>
                                    <th className="px-4 py-3 text-left text-sm font-semibold text-gray-700">Quarter</th>
                                    <th className="px-4 py-3 text-left text-sm font-semibold text-gray-700">Course / Programmes</th>
                                    <th className="px-4 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                                    <th className="px-4 py-3 text-left text-sm font-semibold text-gray-700">Delivery / Location</th>
                                    <th className="px-4 py-3 text-left text-sm font-semibold text-gray-700">Audience</th>
                                </tr>
                            </thead>

                            <tbody className="divide-y divide-gray-200 text-sm text-gray-700">
                                <tr>
                                    <td className="px-4 py-4">Q1 2025</td>
                                    <td className="px-4 py-4 font-medium">
                                        Certified GRC & Financial Crime Specialist (CGFCS)
                                    </td>
                                    <td className="px-4 py-4">Jan 20 – Mar 14, 2025</td>
                                    <td className="px-4 py-4">Online (8 weeks)</td>
                                    <td className="px-4 py-4">Compliance officers, AML specialists</td>
                                </tr>

                                <tr>
                                    <td className="px-4 py-4"></td>
                                    <td className="px-4 py-4 font-medium">
                                        Cybersecurity & Data Security for Financial Institutions
                                    </td>
                                    <td className="px-4 py-4">Feb 10 – Feb 14, 2025</td>
                                    <td className="px-4 py-4">In-person (Lagos, Nigeria)</td>
                                    <td className="px-4 py-4">CISOs, IT security & audit leaders</td>
                                </tr>

                                <tr>
                                    <td className="px-4 py-4">Q2 2025</td>
                                    <td className="px-4 py-4 font-medium">
                                        RegTech, SupTech & Innovation in Compliance
                                    </td>
                                    <td className="px-4 py-4">Apr 7 – May 2, 2025</td>
                                    <td className="px-4 py-4">Online / Hybrid</td>
                                    <td className="px-4 py-4">Regulators, fintechs</td>
                                </tr>

                                <tr>
                                    <td className="px-4 py-4"></td>
                                    <td className="px-4 py-4 font-medium">
                                        Monitoring, Reporting & Risk Analytics
                                    </td>
                                    <td className="px-4 py-4">Jun 9 – Jun 11, 2025</td>
                                    <td className="px-4 py-4">Virtual Masterclass</td>
                                    <td className="px-4 py-4">Risk managers, reporting officers</td>
                                </tr>
                                <tr>
                                    <td className="px-4 py-4"></td>
                                    <td className="px-4 py-4 font-medium">
                                        Executive Short Course: ESG & Sustainable Finance Compliance
                                    </td>
                                    <td className="px-4 py-4">Jun 9 – Jun 11, 2025</td>
                                    <td className="px-4 py-4">Virtual Masterclass</td>
                                    <td className="px-4 py-4">Risk managers, reporting officers</td>
                                </tr>
                                {/* Q3 2026 */}
                                <tr>
                                    <td className="px-4 py-4">Q3 2026</td>
                                    <td className="px-4 py-4 font-medium">
                                        Advanced Diploma in GRC & Financial Crime Prevention
                                    </td>
                                    <td className="px-4 py-4">Jul 7 – Dec 12, 2026</td>
                                    <td className="px-4 py-4">Hybrid (6 months)</td>
                                    <td className="px-4 py-4">Senior leaders & executives</td>
                                </tr>
                                <tr>
                                    <td className="px-4 py-4"></td>
                                    <td className="px-4 py-4 font-medium">
                                        InsurTech, FinTech & Emerging Market Compliance
                                    </td>
                                    <td className="px-4 py-4">Aug 11 – Aug 15, 2025</td>
                                    <td className="px-4 py-4">In-person (Nairobi, Kenya)</td>
                                    <td className="px-4 py-4">FinTech operators, InsurTech leaders, regulators</td>
                                </tr>
                                <tr>
                                    <td className="px-4 py-4"></td>
                                    <td className="px-4 py-4 font-medium">
                                        InsurTech, FinTech & Emerging Market Compliance
                                    </td>
                                    <td className="px-4 py-4">Aug 11 – Aug 15, 2026</td>
                                    <td className="px-4 py-4">In-person (Nairobi, Kenya)</td>
                                    <td className="px-4 py-4">Compliance leaders, data scientists, risk executives</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {/* Delivery Mix */}
                    <div className="mt-14">
                        <h3 className="text-xl font-semibold text-gray-900 mb-4">
                            Delivery Mix
                        </h3>
                        <ul className="space-y-3 text-gray-700">
                            <li className="flex items-start gap-3">
                                <span className="mt-1 h-2 w-2 rounded-full bg-blue-800"></span>
                                Online / Hybrid Programmes: Accessible globally with live instructor support.
                            </li>
                            <li className="flex items-start gap-3">
                                <span className="mt-1 h-2 w-2 rounded-full bg-blue-800"></span>
                                In-person Programmes: Rotating hubs in Lagos, Johannesburg, Nairobi, Dubai, London.
                            </li>
                            <li className="flex items-start gap-3">
                                <span className="mt-1 h-2 w-2 rounded-full bg-blue-800"></span>
                                Executive Masterclasses: 2–3 day intensive virtual sessions with global faculty.
                            </li>
                        </ul>
                    </div>

                </div>
            </section>

        </GuestLayout>
    );
}
