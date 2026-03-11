import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { motion } from 'framer-motion';
import CourseCard from '@/components/Courses/CourseCard';

export default function Certifications({ auth, title, description, courses }) {
 
    // Handle both paginated and non-paginated data
    const courseData = courses.data || courses;
    const hasCourses = Array.isArray(courseData) ? courseData.length > 0 : false;

    return ( 
        <GuestLayout auth={auth}>
            <Head title={title} />
             
            {/* ===== HERO SECTION ===== */}
            <section className="w-full bg-gradient-to-r from-blue-200 via-white to-blue-200 py-28">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                            IGRCFP
                        </h1> 
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            Professional body and certification authority
                        </p>
                    </div>
                </div>
            </section>

            {/* ===== PROFESSIONAL BODY & CORE AREAS ===== */}
            <section className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                        {/* left: descriptor */}
                        <div>
                            <h2 className="text-3xl font-semibold text-gray-800 mb-6">
                                Professional body and certification authority for:
                            </h2>
                            <div className="space-y-3 text-lg text-gray-700">
                                <p className="flex items-center"><span className="w-2 h-2 bg-blue-600 rounded-full mr-3"></span>GRC – Governance, Risk, Compliance</p>
                                <p className="flex items-center"><span className="w-2 h-2 bg-blue-600 rounded-full mr-3"></span>AML – Anti Money Laundering</p>
                                <p className="flex items-center"><span className="w-2 h-2 bg-blue-600 rounded-full mr-3"></span>Financial Crime</p>
                                <p className="flex items-center"><span className="w-2 h-2 bg-blue-600 rounded-full mr-3"></span>RegTech</p>
                                <p className="flex items-center"><span className="w-2 h-2 bg-blue-600 rounded-full mr-3"></span>ESG Risk</p>
                            </div>
                        </div>
                        {/* right: stats / image placeholder */}
                        <div className="bg-gray-50 rounded-2xl p-8 border border-gray-200">
                            <div className="grid grid-cols-2 gap-6">
                                <div className="text-center">
                                    <div className="text-4xl font-bold text-blue-600">40k+</div>
                                    <p className="text-gray-600">learners</p>
                                </div>
                                <div className="text-center">
                                    <div className="text-4xl font-bold text-green-600">100+</div>
                                    <p className="text-gray-600">experts</p>
                                </div>
                            </div>
                            <p className="text-sm text-gray-500 mt-4 text-center">Trusted by professionals worldwide</p>
                        </div>
                    </div>
                </div>
            </section>

            {/* ===== OUR PROGRAMMES & COURSES ===== */}
            <section className="py-20 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center max-w-3xl mx-auto mb-16">
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">OUR PROGRAMMES & COURSES</h2>
                        <p className="text-xl text-blue-800 font-medium">Professional Education for Modern Risk, Regulation & Technology</p>
                    </div>

                    <div className="grid md:grid-cols-2 gap-10 items-center">
                        <div className="prose prose-lg max-w-none">
                            <p className="text-gray-700 leading-relaxed">
                                <span className="font-bold text-gray-900">IGRCFP</span> delivers advanced professional programmes designed for today’s complex regulatory, digital, and financial crime landscape.
                            </p>
                            <p className="text-gray-700 mt-4 font-medium">Our courses sit at the intersection of:</p>
                            <ul className="list-disc pl-5 space-y-1 text-gray-700">
                                <li>Regulation and compliance</li>
                                <li>Enterprise and emerging risk</li>
                                <li>Financial crime prevention</li>
                                <li>Digital assets, crypto, and blockchain</li>
                                <li>Cybersecurity and technology governance</li>
                            </ul>
                            <p className="mt-4 italic text-gray-600 border-l-4 border-blue-300 pl-4">All programmes are framework-led, practitioner-focused, and globally relevant.</p>
                        </div>
                        <div className="bg-white p-8 rounded-xl shadow-sm">
                            <img src="/assets/images/certification.png" alt="IGRCFP programmes" className="rounded-lg w-full object-cover" onError={(e) => e.target.style.display='none'} />
                        </div>
                    </div>
                </div>
            </section>

            {/* ===== CORE PROGRAMME PATHWAYS (ALL 5) ===== */}
            <section className="py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 className="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-4">CORE PROGRAMME PATHWAYS</h2>
                    <div className="w-24 h-1 bg-blue-600 mx-auto mb-16"></div>

                    {/* 1. GRC */}
                    <div className="mb-20 last:mb-0">
                        <h3 className="text-2xl font-bold text-blue-900 mb-3">1. Governance, Risk & Compliance (GRC)</h3>
                        <p className="text-lg text-gray-700 mb-4">Designed for professionals responsible for organisational oversight, risk management, and regulatory compliance.</p>
                        <div className="grid md:grid-cols-2 gap-8">
                            <div>
                                <h4 className="font-semibold text-gray-800 mb-2">Key courses include:</h4>
                                <ul className="list-disc pl-5 space-y-1 text-gray-700">
                                    <li>Advanced Diploma in Governance, Risk, Compliance & Financial Crime Prevention</li>
                                    <li>Enterprise Risk Management for Senior Leaders</li>
                                    <li>Board Governance, Accountability & Ethical Leadership</li>
                                    <li>Compliance Management Systems (CMS) Design & Implementation</li>
                                    <li>Regulatory Change & Horizon Scanning</li>
                                    <li>ESG, Sustainability & Conduct Risk</li>
                                </ul>
                            </div>
                            <div className="bg-gray-50 p-6 rounded-lg">
                                <p className="font-semibold text-gray-800">Who it’s for:</p>
                                <p className="text-gray-700">Risk managers, compliance officers, board advisors, internal audit, executives, consultants, and public sector leaders.</p>
                            </div>
                        </div>
                    </div>

                    {/* 2. Financial Crime Prevention */}
                    <div className="mb-20">
                        <h3 className="text-2xl font-bold text-blue-900 mb-3">2. Financial Crime Prevention & Regulatory Compliance</h3>
                        <p className="text-lg text-gray-700 mb-4">Focused on preventing, detecting, and responding to economic and financial crime across industries.</p>
                        <div className="grid md:grid-cols-2 gap-8">
                            <div>
                                <h4 className="font-semibold text-gray-800 mb-2">Key courses include:</h4>
                                <ul className="list-disc pl-5 space-y-1 text-gray-700">
                                    <li>Advanced AML & Financial Intelligence</li>
                                    <li>Fraud, Corruption & Integrity Systems</li>
                                    <li>Sanctions Risk, Screening & Geopolitical Exposure</li>
                                    <li>Investigations, Enforcement & Cross-Border Cooperation</li>
                                    <li>Financial Crime Risk Assessment & Controls</li>
                                </ul>
                            </div>
                            <div className="bg-gray-50 p-6 rounded-lg">
                                <p className="font-semibold text-gray-800">Who it’s for:</p>
                                <p className="text-gray-700">AML professionals, fraud specialists, investigators, compliance teams, regulators, law enforcement partners, and financial institutions.</p>
                            </div>
                        </div>
                    </div>

                    {/* 3. Crypto, Digital Assets & Blockchain Risk */}
                    <div className="mb-20">
                        <h3 className="text-2xl font-bold text-blue-900 mb-3">3. Crypto, Digital Assets & Blockchain Risk</h3>
                        <p className="text-lg text-gray-700 mb-4">Built for organisations and professionals navigating crypto-assets, blockchain platforms, and decentralised systems.</p>
                        <div className="grid md:grid-cols-2 gap-8">
                            <div>
                                <h4 className="font-semibold text-gray-800 mb-2">Key courses include:</h4>
                                <ul className="list-disc pl-5 space-y-1 text-gray-700">
                                    <li>Crypto-Asset Regulation & Compliance</li>
                                    <li>AML, Sanctions & Financial Crime Risks in Crypto</li>
                                    <li>Blockchain Technology: Governance, Risk & Controls</li>
                                    <li>Decentralised Finance (DeFi) Risk & Oversight</li>
                                    <li>Virtual Asset Service Provider (VASP) Compliance Frameworks</li>
                                </ul>
                                <div className="mt-4 p-4 bg-blue-50 rounded">
                                    <p className="font-semibold">What learners gain:</p>
                                    <ul className="list-disc pl-5 text-sm">
                                        <li>Understanding of how blockchain works (without needing to code)</li>
                                        <li>Regulatory and compliance obligations across jurisdictions</li>
                                        <li>Risk and control design for crypto-related activities</li>
                                        <li>Financial crime typologies unique to digital assets</li>
                                    </ul>
                                </div>
                            </div>
                            <div className="bg-gray-50 p-6 rounded-lg">
                                <p className="font-semibold text-gray-800">Who it’s for:</p>
                                <p className="text-gray-700">Fintech professionals, compliance teams, regulators, policy makers, crypto businesses, and risk leaders.</p>
                            </div>
                        </div>
                    </div>

                    {/* 4. Cybersecurity, Technology & Digital Risk */}
                    <div className="mb-20">
                        <h3 className="text-2xl font-bold text-blue-900 mb-3">4. Cybersecurity, Technology & Digital Risk</h3>
                        <p className="text-lg text-gray-700 mb-4">Focused on cyber risk as a governance, regulatory, and financial crime issue — not just an IT problem.</p>
                        <div className="grid md:grid-cols-2 gap-8">
                            <div>
                                <h4 className="font-semibold text-gray-800 mb-2">Key courses include:</h4>
                                <ul className="list-disc pl-5 space-y-1 text-gray-700">
                                    <li>Cyber Risk Governance for Boards & Executives</li>
                                    <li>Cybercrime, Fraud & Digital Threats</li>
                                    <li>Data Protection, Privacy & Regulatory Compliance</li>
                                    <li>Technology Risk Management & Operational Resilience</li>
                                    <li>Incident Response, Breach Management & Regulatory Reporting</li>
                                </ul>
                                <div className="mt-4 p-4 bg-blue-50 rounded">
                                    <p className="font-semibold">Core themes:</p>
                                    <ul className="list-disc pl-5 text-sm">
                                        <li>Cybersecurity as an enterprise risk</li>
                                        <li>Regulatory expectations around resilience and reporting</li>
                                        <li>Financial crime enabled by digital systems</li>
                                        <li>Accountability and decision-making during incidents</li>
                                    </ul>
                                </div>
                            </div>
                            <div className="bg-gray-50 p-6 rounded-lg">
                                <p className="font-semibold text-gray-800">Who it’s for:</p>
                                <p className="text-gray-700">Executives, CISOs, risk and compliance teams, digital leaders, regulators, and technology organisations.</p>
                            </div>
                        </div>
                    </div>

                    {/* 5. AI, Data & Emerging Technology Governance */}
                    <div>
                        <h3 className="text-2xl font-bold text-blue-900 mb-3">5. AI, Data & Emerging Technology Governance</h3>
                        <p className="text-lg text-gray-700 mb-4">Courses addressing the governance and compliance challenges of advanced technologies.</p>
                        <div className="grid md:grid-cols-2 gap-8">
                            <div>
                                <h4 className="font-semibold text-gray-800 mb-2">Key courses include:</h4>
                                <ul className="list-disc pl-5 space-y-1 text-gray-700">
                                    <li>AI Governance, Ethics & Accountability</li>
                                    <li>Algorithmic Risk, Bias & Explainability</li>
                                    <li>RegTech & SupTech Applications</li>
                                    <li>Data Governance, Ownership & Protection</li>
                                    <li>Technology Ethics & Responsible Innovation</li>
                                </ul>
                            </div>
                            <div className="bg-gray-50 p-6 rounded-lg">
                                <p className="font-semibold text-gray-800">Who it’s for:</p>
                                <p className="text-gray-700">Technology leaders, compliance and risk professionals, regulators, policy makers, and organisations deploying AI or advanced analytics.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* ===== HOW COURSES ARE DESIGNED + DELIVERY ===== */}
            <section className="py-16 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid md:grid-cols-2 gap-12">
                        <div className="bg-white p-8 rounded-xl shadow-sm">
                            <h3 className="text-2xl font-bold text-gray-900 mb-4">HOW OUR COURSES ARE DESIGNED</h3>
                            <p className="text-gray-700 mb-4">All IGRCFP courses are built around:</p>
                            <ul className="list-disc pl-5 space-y-1 text-gray-700">
                                <li>Global regulatory principles and best practices</li>
                                <li>Real-world case studies and current developments</li>
                                <li>Systems thinking rather than rule memorisation</li>
                                <li>Ethical decision-making and accountability</li>
                                <li>Cross-border and multi-industry applicability</li>
                            </ul>
                        </div>
                        <div className="bg-white p-8 rounded-xl shadow-sm">
                            <h3 className="text-2xl font-bold text-gray-900 mb-4">COURSES ARE DELIVERED VIA</h3>
                            <ul className="list-disc pl-5 space-y-1 text-gray-700">
                                <li>Live online sessions</li>
                                <li>Classroom delivery</li>
                                <li>Blended learning</li>
                                <li>Organisational training programmes</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            {/* ===== WHY THESE COURSES MATTER ===== */}
            <section className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 className="text-3xl font-bold text-center text-gray-900 mb-8">WHY THESE COURSES MATTER</h2>
                    <p className="text-xl text-center text-gray-700 max-w-3xl mx-auto mb-8">Regulation, risk, and financial crime are no longer siloed.</p>
                    <p className="text-lg text-center text-gray-600 mb-6">Crypto, cybercrime, AI, sanctions, and data misuse now intersect with:</p>
                    <div className="flex flex-wrap justify-center gap-4 max-w-3xl mx-auto">
                        <span className="px-5 py-2 bg-red-50 text-red-800 rounded-full font-medium">Governance failures</span>
                        <span className="px-5 py-2 bg-amber-50 text-amber-800 rounded-full font-medium">Regulatory breaches</span>
                        <span className="px-5 py-2 bg-purple-50 text-purple-800 rounded-full font-medium">Financial crime exposure</span>
                        <span className="px-5 py-2 bg-indigo-50 text-indigo-800 rounded-full font-medium">Reputational and systemic risk</span>
                    </div>
                    <p className="text-center text-gray-700 mt-8 text-lg max-w-4xl mx-auto">IGRCFP courses are designed to help professionals understand these intersections, not just individual rules.</p>
                </div>
            </section>

            {/* ===== PROFESSIONAL RECOGNITION ===== */}
            <section className="py-16 bg-gradient-to-br from-blue-900 to-blue-800 text-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl md:text-4xl font-bold mb-6">PROFESSIONAL RECOGNITION</h2>
                    <p className="text-xl opacity-90 max-w-3xl mx-auto mb-8">Completion of IGRCFP programmes may lead to:</p>
                    <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-4xl mx-auto">
                        <div className="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20">
                            <div className="text-2xl font-semibold">IGRCFP</div>
                            <div className="text-lg">Professional Certificates</div>
                        </div>
                        <div className="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20">
                            <div className="text-2xl font-semibold">Advanced</div>
                            <div className="text-lg">Diplomas</div>
                        </div>
                        <div className="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20">
                            <div className="text-xl font-semibold">CGFCS</div>
                            <div className="text-lg">Certified GRC & Financial Crime Specialist</div>
                        </div>
                        <div className="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20">
                            <div className="text-2xl font-semibold">Fellowship</div>
                            <div className="text-lg">eligibility (subject to experience)</div>
                        </div>
                    </div>
                    <p className="mt-10 text-white/80 max-w-2xl mx-auto">All awards are issued as independent professional credentials, aligned with global standards and practitioner expectations.</p>
                </div>
            </section>

           

        </GuestLayout>
    );
}