import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import CourseCard from '@/components/Courses/CourseCard';

export default function Certifications({ auth, title, description, courses }) {
 
    // Handle both paginated and non-paginated data
    const courseData = courses.data || courses;
    const hasCourses = Array.isArray(courseData) ? courseData.length > 0 : false;

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
                            <p className="mt-4 text-gray-600 text-2xl">
                                Our professional certifications are designed to equip individuals and institutions with globally relevant skills to tackle financial crime and compliance risks.
                            </p>
                            
                            <div className="mb-10 mt-16">
                                <h3 className="text-start text-lg font-semibold text-gray-500 mb-8 uppercase tracking-wider">
                                    TRUSTED BY
                                </h3>
                                
                                {/* Stats Grid */}
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-2xl mx-auto">
                                    {/* Learners Stat */}
                                    <div className="bg-gray-50 rounded-xl p-3 text-center border border-gray-200">
                                        <div className="text-3xl md:text-4xl font-bold text-blue-600 mb-2">
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
                                    <div className="bg-gray-50 rounded-xl p-3 text-center border border-gray-200">
                                        <div className="text-3xl md:text-4xl font-bold text-green-600 mb-2">
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

            {/* Section */}
            <section className="py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    {/* Header */}
                    <div className="text-center max-w-4xl mx-auto">
                        <h2 className="text-3xl md:text-4xl font-semibold text-blue-900">
                            Industry-Relevant Programs to Set you Apart
                        </h2>
                        <p className="mt-4 text-lg text-gray-600">
                            At IGRCFP, we provide practical training and globally recognized certifications
                            in governance, risk, compliance, and financial crime prevention. Learn online,
                            hybrid, or in-person — all with real-world case studies and expert trainers.
                        </p>

                        <div className="mt-8">
                            <Link
                                href="/training-calendar"
                                className="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-blue-900 rounded-md hover:bg-blue-800 transition"
                            >
                                View Training Calendar
                            </Link>
                        </div>
                    </div>

                    {/* Courses */}
                    {courses.data && courses.data.length > 0 ? (
                        <div className={`grid gap-6 ${
                            showFilters 
                                ? 'grid-cols-1 md:grid-cols-2 xl:grid-cols-3' 
                                : 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4'
                        }`}>
                            {courses.data.map((course, index) => (
                                <motion.div
                                    key={course.id}
                                    initial={{ opacity: 0, y: 20 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ duration: 0.3, delay: index * 0.05 }}
                                >
                                    <CourseCard course={course} />
                                </motion.div>
                            ))}
                        </div>
                    ) : (
                        <div className="text-center py-16 bg-white rounded-xl">
                            <svg className="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 className="text-xl font-semibold text-gray-900 mb-2">
                                No courses found
                            </h3>
                            <p className="text-gray-600 mb-6">
                                Try adjusting your search or filter criteria
                            </p>
                        </div>
                    )}

                </div>
            </section>


        </GuestLayout>
    );
}
