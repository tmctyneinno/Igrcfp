import { Head } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Certifications({ auth, title, description }) {
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
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            {description}
                        </p>
                    </div>
                </div>
            </section>

            {/* Main Content Section */}
            <section className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                        <div>
                            <h2 className="text-4xl font-semibold text-gray-800">
                                Advance Your Career. Build Institutional Resilience.
                            </h2>
                            <p className="mt-4 text-gray-600 text-1xl">
                                Our professional certifications are designed to equip individuals and institutions with globally relevant skills to tackle financial crime and compliance risks.
                            </p>
                            
                            <div className="mb-16 mt-20">
                                <h3 className="text-start text-lg font-semibold text-gray-500 mb-8 uppercase tracking-wider">
                                    TRUSTED BY
                                </h3>
                                
                                {/* Stats Grid */}
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-2xl mx-auto">
                                    {/* Learners Stat */}
                                    <div className="bg-gray-50 rounded-xl p-8 text-center border border-gray-200">
                                        <div className="text-4xl md:text-5xl font-bold text-blue-600 mb-2">
                                            40k+
                                        </div>
                                        <div className="text-lg font-medium text-gray-700">
                                            learners
                                        </div>
                                        <p className="text-gray-500 mt-2 text-sm">
                                            Professionals trained worldwide
                                        </p>
                                    </div>
                                    
                                    {/* Experts Stat */}
                                    <div className="bg-gray-50 rounded-xl p-8 text-center border border-gray-200">
                                        <div className="text-4xl md:text-5xl font-bold text-green-600 mb-2">
                                            100+
                                        </div>
                                        <div className="text-lg font-medium text-gray-700">
                                            experts
                                        </div>
                                        <p className="text-gray-500 mt-2 text-sm">
                                            Industry-leading instructors
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {/* Image Section */}
                        <div className="relative">
                            <img
                                src="assets/images/certification.png" // Replace with your actual image path
                                alt="Certification"
                                className="rounded-lg shadow-lg object-cover w-full h-full"
                            />
                        </div>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}
