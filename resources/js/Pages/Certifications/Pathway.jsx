import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Pathway({ auth, title, description }) {
    return (
        <GuestLayout auth={auth}>
            <Head title={title} />

            {/* Hero section */}
            <section className="w-full bg-gradient-to-r from-blue-900 to-blue-800 text-white py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 className="text-4xl md:text-5xl font-bold mb-4">{title}</h1>
                    <p className="text-xl text-blue-100 max-w-2xl mx-auto">
                        {description || 'Your path from foundational knowledge to senior leadership recognition'}
                    </p>
                </div>
            </section>

            {/* Main pathway diagram and explanation */}
            <section className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    {/* heading */}
                    <h2 className="text-3xl md:text-4xl font-semibold text-center text-gray-900 mb-4">
                        IGRCFP PROFESSIONAL PATHWAY
                    </h2>
                    <p className="text-center text-gray-600 max-w-2xl mx-auto mb-16">
                        Structured progression from specialist certificates to Fellowship — 
                        each stage builds operational, strategic, and leadership capability.
                    </p>

                    {/* visual diagram (text-based ascii style, but with modern card layout) */}
                    <div className="relative flex flex-col items-center mb-16">
                        
                        {/* STEP 5: Fellowship */}
                        <div className="w-full max-w-2xl bg-gradient-to-r from-purple-700 to-purple-600 text-white rounded-2xl shadow-xl p-8 text-center border-4 border-purple-300 relative z-10">
                            <div className="text-2xl font-bold tracking-wide">IGRCFP FELLOWSHIP</div>
                            <div className="text-xl mt-1">(F-IGRCFP – Senior Leaders)</div>
                            <p className="text-purple-100 mt-3 max-w-md mx-auto">
                                Recognition based on experience & contribution
                            </p>
                        </div>

                        {/* connector line (↓) */}
                        <div className="my-2 text-3xl text-gray-400">↓</div>

                        {/* STEP 4: CGFCS */}
                        <div className="w-full max-w-2xl bg-gradient-to-r from-blue-800 to-blue-700 text-white rounded-2xl shadow-xl p-8 text-center border-4 border-blue-300">
                            <div className="text-2xl font-bold tracking-wide">Certified GRC & Financial Crime Specialist</div>
                            <div className="text-xl mt-1">(CGFCS)</div>
                            <p className="text-blue-100 mt-3">Advanced Professional Status</p>
                        </div>

                        <div className="my-2 text-3xl text-gray-400">↓</div>

                        {/* STEP 3: Advanced Diploma */}
                        <div className="w-full max-w-2xl bg-gradient-to-r from-indigo-700 to-indigo-600 text-white rounded-2xl shadow-xl p-8 text-center border-4 border-indigo-300">
                            <div className="text-2xl font-bold tracking-wide">IGRCFP ADVANCED DIPLOMA</div>
                            <p className="text-indigo-100 mt-2">Governance • Risk • Compliance • FinCrime</p>
                            <p className="text-white mt-2">Strategic & leadership capability</p>
                        </div>

                        <div className="my-2 text-3xl text-gray-400">↓</div>

                        {/* STEP 2: Diploma */}
                        <div className="w-full max-w-2xl bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-2xl shadow-xl p-8 text-center border-4 border-blue-200">
                            <div className="text-2xl font-bold tracking-wide">IGRCFP DIPLOMA</div>
                            <p className="text-blue-100 mt-2">Governance • Risk • Compliance • FinCrime</p>
                            <p className="text-white mt-2">Operational and practitioner level</p>
                        </div>

                        <div className="my-2 text-3xl text-gray-400">↓</div>

                        {/* STEP 1: Certificates */}
                        <div className="w-full max-w-2xl bg-gradient-to-r from-emerald-600 to-emerald-500 text-white rounded-2xl shadow-xl p-8 text-center border-4 border-emerald-200">
                            <div className="text-2xl font-bold tracking-wide">IGRCFP CERTIFICATES</div>
                            <p className="text-emerald-100 mt-3 text-lg">Specialist programmes such as:</p>
                            <div className="flex flex-wrap justify-center gap-x-4 gap-y-1 mt-2 text-sm font-medium">
                                <span>• Trade Based Money Laundering (TBML)</span>
                                <span>• Crypto & Digital Asset Risk</span>
                                <span>• Cybersecurity & Digital Risk</span>
                                <span>• Blockchain Governance</span>
                                <span>• AML & Financial Crime Foundations</span>
                            </div>
                        </div>
                    </div>

                    {/* simple explanation cards */}
                    <div className="grid md:grid-cols-5 gap-4 mt-20">
                        <div className="bg-emerald-50 p-5 rounded-xl border border-emerald-200">
                            <div className="font-bold text-emerald-800 text-lg">Certificates</div>
                            <p className="text-gray-700 text-sm mt-1">specialist entry programmes</p>
                        </div>
                        <div className="bg-blue-50 p-5 rounded-xl border border-blue-200">
                            <div className="font-bold text-blue-800 text-lg">Diploma</div>
                            <p className="text-gray-700 text-sm mt-1">operational GRC and financial crime capability</p>
                        </div>
                        <div className="bg-indigo-50 p-5 rounded-xl border border-indigo-200">
                            <div className="font-bold text-indigo-800 text-lg">Advanced Diploma</div>
                            <p className="text-gray-700 text-sm mt-1">strategic leadership capability</p>
                        </div>
                        <div className="bg-blue-50 p-5 rounded-xl border border-blue-300">
                            <div className="font-bold text-blue-900 text-lg">CGFCS</div>
                            <p className="text-gray-700 text-sm mt-1">professional designation</p>
                        </div>
                        <div className="bg-purple-50 p-5 rounded-xl border border-purple-300">
                            <div className="font-bold text-purple-900 text-lg">Fellowship</div>
                            <p className="text-gray-700 text-sm mt-1">senior recognition based on experience</p>
                        </div>
                    </div>

                    {/* optional pathway image (if you have a graphic, uncomment below) */}
                    <div className="mt-20 text-center">
                        <img src="/assets/images/certification-pathway.jpg" alt="IGRCFP certification pathway visual" className="max-w-full mx-auto rounded-lg shadow-lg" />
                    </div>

                    {/* back / navigation link similar to original CTA */}
                    <div className="mt-20 text-center">
                        <Link
                            href="/certifications"
                            className="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-blue-900 rounded-md hover:bg-blue-800 transition"
                        >
                            ← Back to Certification overview
                        </Link>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}