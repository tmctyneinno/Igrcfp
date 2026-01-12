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
                            <h2 className="text-3xl font-semibold text-gray-800">
                                Advance Your Career. Build Institutional Resilience.
                            </h2>
                            <p className="mt-4 text-gray-600">
                                Our professional certifications are designed to equip individuals and institutions with globally relevant skills to tackle financial crime and compliance risks.
                            </p>
                            <div className="mt-8 flex items-center justify-start">
                                <div className="text-lg font-semibold text-blue-600">Trusted by</div>
                                <div className="ml-4 flex space-x-6">
                                    <div className="text-center">
                                        <p className="text-xl text-blue-600 font-bold">40k+</p>
                                        <p className="text-sm text-gray-500">learners</p>
                                    </div>
                                    <div className="text-center">
                                        <p className="text-xl text-blue-600 font-bold">100+</p>
                                        <p className="text-sm text-gray-500">experts</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Image Section */}
                        <div className="relative">
                            <img
                                src="assets/images/home-three/gallery/certification.png" // Replace with your actual image path
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
