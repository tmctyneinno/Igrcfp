import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function CGFCSSpecialist({ auth }) {
    return (
        <GuestLayout auth={auth}>
            <Head title="Certification programmes | IGRCFP" />

            {/* Header */}
            <section className="w-full bg-gradient-to-r from-blue-900 to-blue-800 text-white py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 className="text-4xl md:text-5xl font-bold mb-4">Professional certifications & diplomas</h1>
                    <p className="text-xl text-blue-100 max-w-3xl mx-auto">
                        IGRCFP credentials: from specialist certificates to advanced diplomas and the flagship CGFCS designation
                    </p>
                </div>
            </section>

            {/* Main content */}
            <section className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20">
                    
                    {/* 1. CGFCS */}
                    <div className="bg-gray-50 rounded-2xl p-8 md:p-10 shadow-sm border border-gray-200">
                        <h2 className="text-3xl font-bold text-blue-900 mb-4">1. Certified GRC & Financial Crime Specialist (CGFCS)</h2>
                        <p className="text-lg text-gray-700 mb-6 font-medium">Overview</p>
                        <p className="text-gray-700 mb-4">
                            The Certified GRC & Financial Crime Specialist (CGFCS) is the flagship professional certification offered by the Institute of GRC & Financial Crime Prevention (IGRCFP).
                        </p>
                        <p className="text-gray-700 mb-4">
                            This certification is designed to equip professionals with advanced knowledge and practical capability across the integrated disciplines of:
                        </p>
                        <ul className="list-disc pl-6 mb-6 text-gray-700 space-y-1">
                            <li>Governance</li>
                            <li>Risk Management</li>
                            <li>Regulatory Compliance</li>
                            <li>Financial Crime Prevention</li>
                        </ul>
                        <p className="text-gray-700 mb-4">
                            The programme reflects the increasing demand for professionals who can operate effectively across regulatory environments, financial systems, digital technologies, and global markets.
                        </p>
                        <p className="text-gray-700 mb-6">
                            The CGFCS certification focuses on developing systems-level understanding, enabling professionals to design and manage integrated governance and compliance frameworks that protect organisations from operational, regulatory, and financial crime risks.
                        </p>

                        <h3 className="text-xl font-semibold text-blue-800 mt-8 mb-3">Key Learning Outcomes</h3>
                        <p className="text-gray-700 mb-2">Participants completing this certification will be able to:</p>
                        <ul className="list-disc pl-6 mb-6 text-gray-700 space-y-1">
                            <li>Understand and apply governance and accountability frameworks</li>
                            <li>Identify, assess, and manage enterprise risk</li>
                            <li>Design and implement compliance management systems</li>
                            <li>Analyse financial crime typologies and risk exposure</li>
                            <li>Develop financial crime prevention controls</li>
                            <li>Understand regulatory expectations across jurisdictions</li>
                            <li>Manage emerging risks including cybercrime, digital assets, and technology-enabled financial crime</li>
                        </ul>

                        <h3 className="text-xl font-semibold text-blue-800 mt-8 mb-3">Who Should Enrol</h3>
                        <p className="text-gray-700 mb-2">This certification is suitable for professionals working in:</p>
                        <ul className="list-disc pl-6 mb-6 text-gray-700 columns-2 gap-4">
                            <li>Compliance</li>
                            <li>Risk management</li>
                            <li>Financial crime prevention</li>
                            <li>Banking and financial services</li>
                            <li>Public sector regulation</li>
                            <li>Internal audit</li>
                            <li>Corporate governance</li>
                            <li>Consulting and advisory roles</li>
                        </ul>

                        <h3 className="text-xl font-semibold text-blue-800 mt-8 mb-3">Programme Curriculum</h3>
                        <ul className="list-disc pl-6 mb-6 text-gray-700">
                            <li>Governance and board accountability</li>
                            <li>Enterprise risk architecture</li>
                            <li>Compliance management systems</li>
                            <li>Financial crime prevention frameworks</li>
                            <li>Cyber and digital risk governance</li>
                            <li>Crypto-assets and emerging financial crime risks</li>
                            <li>Investigations and enforcement processes</li>
                            <li>Assurance, reporting, and organisational oversight</li>
                        </ul>

                        <h3 className="text-xl font-semibold text-blue-800 mt-8 mb-3">Delivery Format</h3>
                        <p className="text-gray-700 mb-2">The certification is delivered through:</p>
                        <ul className="list-disc pl-6 mb-2 text-gray-700">
                            <li>Instructor-led sessions</li>
                            <li>Case study discussions</li>
                            <li>Practical exercises</li>
                            <li>Applied frameworks</li>
                            <li>Interactive workshops</li>
                        </ul>
                        <p className="text-gray-700 mb-4">Delivery formats may include:</p>
                        <ul className="list-disc pl-6 mb-6 text-gray-700">
                            <li>Virtual live classes</li>
                            <li>Classroom-based training</li>
                            <li>Blended learning programmes</li>
                        </ul>

                        <h3 className="text-xl font-semibold text-blue-800 mt-8 mb-3">Assessment</h3>
                        <p className="text-gray-700 mb-2">Participants are assessed through a combination of:</p>
                        <ul className="list-disc pl-6 mb-4 text-gray-700">
                            <li>Knowledge-based examination</li>
                            <li>Case study analysis</li>
                            <li>Practical scenario exercises</li>
                            <li>Applied project work</li>
                        </ul>
                        <p className="text-gray-700 font-medium">Successful candidates receive the Certified GRC & Financial Crime Specialist (CGFCS) designation.</p>
                    </div>

                    {/* 2. Advanced Diploma */}
                    <div className="bg-gray-50 rounded-2xl p-8 md:p-10 shadow-sm border border-gray-200">
                        <h2 className="text-3xl font-bold text-blue-900 mb-4">2. IGRCFP Advanced Diploma in Governance, Risk, Compliance & Financial Crime Prevention</h2>
                        <h3 className="text-xl font-semibold text-blue-800 mt-4 mb-3">Overview</h3>
                        <p className="text-gray-700 mb-4">
                            The IGRCFP Advanced Diploma in Governance, Risk, Compliance & Financial Crime Prevention provides comprehensive professional education covering the full spectrum of governance, regulatory compliance, enterprise risk management, and financial crime prevention.
                        </p>
                        <p className="text-gray-700 mb-4">
                            The programme is structured around the IGRCFP Framework, which integrates governance, risk, compliance, financial crime prevention, technology governance, and assurance into a single management system.
                        </p>
                        <p className="text-gray-700 mb-6">
                            The Advanced Diploma is designed for professionals seeking a deep understanding of how these disciplines interact within complex organisations and regulatory environments.
                        </p>

                        <h3 className="text-xl font-semibold text-blue-800 mt-8 mb-3">Key Learning Outcomes</h3>
                        <p className="text-gray-700 mb-2">Learners will develop the ability to:</p>
                        <ul className="list-disc pl-6 mb-6 text-gray-700">
                            <li>Design governance and accountability structures</li>
                            <li>Build enterprise risk management frameworks</li>
                            <li>Implement compliance management systems</li>
                            <li>Prevent and detect financial crime</li>
                            <li>Analyse regulatory developments and enforcement trends</li>
                            <li>Understand emerging risks such as cybercrime and digital finance</li>
                            <li>Strengthen organisational oversight and assurance mechanisms</li>
                        </ul>

                        <h3 className="text-xl font-semibold text-blue-800 mt-8 mb-3">Who Should Enrol</h3>
                        <p className="text-gray-700 mb-2">The programme is designed for:</p>
                        <ul className="list-disc pl-6 mb-6 text-gray-700">
                            <li>Risk and compliance professionals</li>
                            <li>Financial crime specialists</li>
                            <li>Corporate governance practitioners</li>
                            <li>Regulatory professionals</li>
                            <li>Consultants and advisors</li>
                            <li>Senior managers responsible for risk and compliance oversight</li>
                        </ul>

                        <h3 className="text-xl font-semibold text-blue-800 mt-8 mb-3">Programme Modules</h3>
                        <ul className="list-disc pl-6 mb-6 text-gray-700">
                            <li>Governance and organisational accountability</li>
                            <li>Enterprise risk management</li>
                            <li>Regulatory compliance systems</li>
                            <li>Financial crime prevention and AML frameworks</li>
                            <li>Cybersecurity and technology risk</li>
                            <li>Crypto-assets and blockchain risk</li>
                            <li>Investigations and enforcement</li>
                            <li>Assurance and organisational oversight</li>
                        </ul>

                        <h3 className="text-xl font-semibold text-blue-800 mt-8 mb-3">Learning Format</h3>
                        <p className="text-gray-700 mb-2">The programme combines:</p>
                        <ul className="list-disc pl-6 mb-6 text-gray-700">
                            <li>Lectures and facilitated discussion</li>
                            <li>Case study analysis</li>
                            <li>Applied exercises</li>
                            <li>Professional simulations</li>
                            <li>Practical frameworks and tools</li>
                        </ul>

                        <h3 className="text-xl font-semibold text-blue-800 mt-8 mb-3">Assessment</h3>
                        <p className="text-gray-700 mb-2">Assessment includes:</p>
                        <ul className="list-disc pl-6 mb-4 text-gray-700">
                            <li>Written assignments</li>
                            <li>Case study analysis</li>
                            <li>Applied exercises</li>
                            <li>Capstone project</li>
                        </ul>
                        <p className="text-gray-700 font-medium">Graduates receive the IGRCFP Advanced Diploma and may progress toward IGRCFP professional designations.</p>
                    </div>

                    {/* 3. TBML Certificate */}
                    <div className="bg-gray-50 rounded-2xl p-8 md:p-10 shadow-sm border border-gray-200">
                        <h2 className="text-3xl font-bold text-blue-900 mb-4">3. IGRCFP Certificate in Trade-Based Money Laundering (TBML)</h2>
                        <h3 className="text-xl font-semibold text-blue-800 mt-4 mb-3">Overview</h3>
                        <p className="text-gray-700 mb-4">
                            The IGRCFP Certificate in Trade-Based Money Laundering (TBML) provides specialised training in detecting and mitigating financial crime risks within international trade systems.
                        </p>
                        <p className="text-gray-700 mb-4">
                            Trade-based money laundering is one of the most complex forms of financial crime, involving the manipulation of trade transactions to move illicit value across borders.
                        </p>
                        <p className="text-gray-700 mb-6">
                            This programme provides participants with the knowledge and practical skills needed to identify TBML typologies, analyse trade documentation, and implement effective risk controls.
                        </p>

                        <h3 className="text-xl font-semibold text-blue-800 mt-8 mb-3">Key Learning Outcomes</h3>
                        <p className="text-gray-700 mb-2">Participants will learn to:</p>
                        <ul className="list-disc pl-6 mb-6 text-gray-700">
                            <li>Understand international trade finance mechanisms</li>
                            <li>Identify common TBML typologies</li>
                            <li>Analyse trade documentation for anomalies</li>
                            <li>Detect over-invoicing, under-invoicing, and phantom shipments</li>
                            <li>Assess sanctions and proliferation risks in trade transactions</li>
                            <li>Support TBML investigations and reporting processes</li>
                        </ul>

                        <h3 className="text-xl font-semibold text-blue-800 mt-8 mb-3">Who Should Enrol</h3>
                        <p className="text-gray-700 mb-2">The programme is relevant for professionals working in:</p>
                        <ul className="list-disc pl-6 mb-6 text-gray-700">
                            <li>Trade finance</li>
                            <li>Banking and correspondent banking</li>
                            <li>AML and financial crime prevention</li>
                            <li>Customs and border agencies</li>
                            <li>Regulatory and financial intelligence units</li>
                            <li>Corporate supply chain and logistics roles</li>
                        </ul>

                        <h3 className="text-xl font-semibold text-blue-800 mt-8 mb-3">Programme Curriculum</h3>
                        <ul className="list-disc pl-6 mb-6 text-gray-700">
                            <li>International trade and trade finance systems</li>
                            <li>Trade-based money laundering typologies</li>
                            <li>Trade documentation analysis</li>
                            <li>Sanctions and dual-use goods risk</li>
                            <li>Detection and monitoring techniques</li>
                            <li>Investigations and financial intelligence</li>
                            <li>Governance and regulatory expectations</li>
                        </ul>

                        <h3 className="text-xl font-semibold text-blue-800 mt-8 mb-3">Delivery Approach</h3>
                        <p className="text-gray-700 mb-2">The programme includes:</p>
                        <ul className="list-disc pl-6 mb-6 text-gray-700">
                            <li>Instructor-led training</li>
                            <li>Trade document analysis</li>
                            <li>Practical case studies</li>
                            <li>Investigation simulations</li>
                            <li>Applied compliance frameworks</li>
                        </ul>

                        <h3 className="text-xl font-semibold text-blue-800 mt-8 mb-3">Assessment</h3>
                        <p className="text-gray-700 mb-2">Participants are assessed through:</p>
                        <ul className="list-disc pl-6 mb-4 text-gray-700">
                            <li>Knowledge-based examinations</li>
                            <li>Case study analysis</li>
                            <li>Trade transaction review exercises</li>
                            <li>Applied investigative simulation</li>
                        </ul>
                        <p className="text-gray-700 font-medium">Successful participants receive the IGRCFP Certificate in Trade-Based Money Laundering.</p>
                    </div>

                    {/* 4. Specialist Certifications in Emerging Risk Areas */}
                    <div className="bg-gray-50 rounded-2xl p-8 md:p-10 shadow-sm border border-gray-200">
                        <h2 className="text-3xl font-bold text-blue-900 mb-6">4. Specialist Certifications in Emerging Risk Areas</h2>
                        <p className="text-gray-700 mb-6 text-lg">
                            IGRCFP also offers specialised certification programmes in areas where regulatory and financial crime risks are rapidly evolving.
                            These programmes focus on emerging challenges affecting governance, compliance, and financial systems.
                        </p>

                        {/* Crypto */}
                        <div className="mb-10 pb-6 border-b border-gray-300">
                            <h3 className="text-2xl font-semibold text-blue-800 mb-3">Crypto, Digital Assets & Blockchain Risk Certification</h3>
                            <p className="text-gray-700 mb-3">This programme addresses regulatory, compliance, and financial crime risks associated with digital assets and decentralised technologies.</p>
                            <p className="font-medium text-gray-800 mb-2">Key topics include:</p>
                            <ul className="list-disc pl-6 text-gray-700">
                                <li>Crypto-asset regulatory frameworks</li>
                                <li>AML and sanctions risk in digital assets</li>
                                <li>Blockchain governance and risk</li>
                                <li>DeFi and emerging financial crime typologies</li>
                            </ul>
                        </div>

                        {/* Cybersecurity */}
                        <div className="mb-10 pb-6 border-b border-gray-300">
                            <h3 className="text-2xl font-semibold text-blue-800 mb-3">Cybersecurity & Digital Risk Governance Certification</h3>
                            <p className="text-gray-700 mb-3">This programme focuses on cybersecurity risk from a governance and compliance perspective.</p>
                            <p className="font-medium text-gray-800 mb-2">Key topics include:</p>
                            <ul className="list-disc pl-6 text-gray-700">
                                <li>Cyber risk governance</li>
                                <li>Digital fraud and cybercrime</li>
                                <li>Data protection and privacy compliance</li>
                                <li>Operational resilience and incident management</li>
                            </ul>
                        </div>

                        {/* AI & data */}
                        <div>
                            <h3 className="text-2xl font-semibold text-blue-800 mb-3">AI, Data & Technology Governance Certification</h3>
                            <p className="text-gray-700 mb-3">This programme explores governance and regulatory issues associated with artificial intelligence and data-driven systems.</p>
                            <p className="font-medium text-gray-800 mb-2">Topics include:</p>
                            <ul className="list-disc pl-6 text-gray-700">
                                <li>AI governance frameworks</li>
                                <li>Algorithmic risk and bias</li>
                                <li>Data governance and protection</li>
                                <li>Ethical and regulatory implications of emerging technologies</li>
                            </ul>
                        </div>
                    </div>

                    {/* Back link */}
                    <div className="text-center pt-10">
                        <Link
                            href={route('certifications')}
                            className="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-blue-900 rounded-md hover:bg-blue-800 transition"
                        >
                            ← Back to certification overview
                        </Link>
                    </div>

                </div>
            </section>
        </GuestLayout>
    );
}