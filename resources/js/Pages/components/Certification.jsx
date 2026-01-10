import { Link } from "@inertiajs/react";

export default function Certification({ courses = [] }) {
    return (
        <section className="py-24 bg-gray-50">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                {/* HEADER */}
                <div
                    className="flex flex-col md:flex-row md:justify-between md:items-center mb-12"
                    data-aos="fade-up"
                >
                    <div>
                        <span className="text-sm uppercase tracking-widest text-gray-400">
                            Certifications & Trainings
                        </span>
                        <h2 className="text-3xl font-bold text-gray-900 mt-2">
                            Our Programmes
                        </h2>
                        <p className="text-gray-600 mt-3 max-w-2xl">
                            Our professional certifications are designed to equip individuals
                            and institutions with globally relevant skills to tackle financial
                            crime and compliance risks.
                        </p>
                    </div>

                    <Link
                        href="/courses"
                        className="mt-6 md:mt-0 text-blue-950 font-semibold hover:text-blue-700 transition"
                    >
                        View All Courses →
                    </Link>
                </div>

                {/* COURSES */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {courses.map((course, index) => (
                        <div
                            key={course.id}
                            className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-2"
                            data-aos="fade-up"
                            data-aos-delay={index * 150}
                        >
                            {/* IMAGE */}
                            <div className="h-48 overflow-hidden">
                                <img
                                    src={course.image}
                                    alt={course.title}
                                    className="w-full h-full object-cover"
                                />
                            </div>

                            {/* CONTENT */}
                            <div className="p-6">
                                <div className="flex items-center justify-between mb-4">
                                    <span className="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                                        {course.level}
                                    </span>
                                    <span className="text-gray-500 text-sm">
                                        {course.rating} ★
                                    </span>
                                </div>

                                <h3 className="text-lg font-semibold text-gray-900 mb-2">
                                    {course.title}
                                </h3>

                                <p className="text-gray-600 text-sm mb-4">
                                    {course.short_description}
                                </p>

                                <div className="flex items-center justify-between">
                                    <span className="text-sm text-gray-500">
                                        {course.instructor}
                                    </span>

                                    <span className="text-blue-700 font-bold">
                                        ₦{Number(course.price).toLocaleString()}
                                    </span>
                                </div>
                            </div>
                        </div>
                    ))}

                    {courses.length === 0 && (
                        <p className="col-span-3 text-center text-gray-500">
                            No courses available at the moment.
                        </p>
                    )}
                </div>
            </div>
        </section>
    );
}
