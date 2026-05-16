// resources/js/Pages/News/FormShow.jsx

import React, { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function FormShow({ post, programmes = [] }) {
    const { data, setData, post: submitForm, processing, errors, reset } = useForm({
        full_name: '',
        nationality: '',
        country_of_residence: '',
        email: '',
        phone_number: '',
        academic_background: '',
        highest_qualification: '',
        institution: '',
        year_completed: '',
        current_role: '',
        organisation: '',
        preferred_programmes: [],
        personal_statement: '',
        declaration: false,
        post_id: post.id,
    });

    const [submitted, setSubmitted] = useState(false);

    const handleProgrammeChange = (programme) => {
        const current = [...data.preferred_programmes];
        if (current.includes(programme)) {
            setData('preferred_programmes', current.filter(p => p !== programme));
        } else {
            if (current.length < 3) {
                setData('preferred_programmes', [...current, programme]);
            }
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        submitForm(route('scholarship.apply'), {
            onSuccess: () => {
                setSubmitted(true);
                reset();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },
        });
    };

    // Default programmes if none provided
    const programmeList = programmes.length > 0 ? programmes : [
        'Certificate in Governance, Risk & Compliance',
        'Certificate in Financial Crime Prevention',
        'Certificate in AML & CFT',
        'Certificate in Cybersecurity & Digital Risk',
        'Certificate in Fraud Risk Management',
        'Certificate in Data Protection & Privacy',
        'Certificate in Blockchain & Crypto Financial Crime',
        'Certificate in ESG & Sustainability Governance',
    ];

    return (
        <GuestLayout>
            <Head title={post.meta_title || post.title} />

            {/* Success Message */}
            {submitted && (
                <div className="bg-green-500 text-white">
                    <div className="max-w-4xl mx-auto px-4 py-4 text-center">
                        <p className="text-lg font-semibold">
                            🎉 Application submitted successfully! Check your email for confirmation.
                        </p>
                    </div>
                </div>
            )}


            {/* Application Form Section */}
        
            <section className="bg-gray-50 py-16" id="apply-form">
                <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white rounded-2xl shadow-xl p-8 md:p-12">
                        <div className="text-center mb-8">
                            <h2 className="text-3xl font-bold text-gray-900 mb-2">
                                📄 Scholarship Application Form
                            </h2>
                            <p className="text-gray-600">
                                Fill in the form below to apply for this scholarship programme.
                            </p>
                        </div>

                        <form onSubmit={handleSubmit} className="space-y-8">
                            {/* Applicant Information */}
                            <div>
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">
                                    Applicant Information
                                </h3>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Full Name <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            value={data.full_name}
                                            onChange={e => setData('full_name', e.target.value)}
                                            className="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                            placeholder="Your full name"
                                            required
                                        />
                                        {errors.full_name && <p className="text-red-500 text-sm mt-1">{errors.full_name}</p>}
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Nationality <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            value={data.nationality}
                                            onChange={e => setData('nationality', e.target.value)}
                                            className="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                            placeholder="Your nationality"
                                            required
                                        />
                                        {errors.nationality && <p className="text-red-500 text-sm mt-1">{errors.nationality}</p>}
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Country of Residence <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            value={data.country_of_residence}
                                            onChange={e => setData('country_of_residence', e.target.value)}
                                            className="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                            placeholder="Country of residence"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Email Address <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="email"
                                            value={data.email}
                                            onChange={e => setData('email', e.target.value)}
                                            className="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                            placeholder="your.email@example.com"
                                            required
                                        />
                                        {errors.email && <p className="text-red-500 text-sm mt-1">{errors.email}</p>}
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Phone Number <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="tel"
                                            value={data.phone_number}
                                            onChange={e => setData('phone_number', e.target.value)}
                                            className="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                            placeholder="+1234567890"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>

                            {/* Academic Background */}
                            <div>
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">
                                    Academic Background
                                </h3>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div className="md:col-span-2">
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Academic Background
                                        </label>
                                        <textarea
                                            value={data.academic_background}
                                            onChange={e => setData('academic_background', e.target.value)}
                                            rows={2}
                                            className="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                            placeholder="Brief description of your academic background"
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Highest Qualification <span className="text-red-500">*</span>
                                        </label>
                                        <select
                                            value={data.highest_qualification}
                                            onChange={e => setData('highest_qualification', e.target.value)}
                                            className="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                            required
                                        >
                                            <option value="">Select qualification</option>
                                            <option value="High School">High School</option>
                                            <option value="Diploma">Diploma</option>
                                            <option value="Bachelor's Degree">Bachelor's Degree</option>
                                            <option value="Master's Degree">Master's Degree</option>
                                            <option value="Doctorate">Doctorate</option>
                                            <option value="Professional Certification">Professional Certification</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Institution <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            value={data.institution}
                                            onChange={e => setData('institution', e.target.value)}
                                            className="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                            placeholder="Name of institution"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Year Completed <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            value={data.year_completed}
                                            onChange={e => setData('year_completed', e.target.value)}
                                            className="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                            placeholder="e.g. 2024"
                                            maxLength={4}
                                            required
                                        />
                                    </div>
                                </div>
                            </div>

                            {/* Professional Information */}
                            <div>
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">
                                    Professional Information
                                </h3>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Current Role / Status
                                        </label>
                                        <input
                                            type="text"
                                            value={data.current_role}
                                            onChange={e => setData('current_role', e.target.value)}
                                            className="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                            placeholder="e.g. Student, Junior Analyst"
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Organisation (if applicable)
                                        </label>
                                        <input
                                            type="text"
                                            value={data.organisation}
                                            onChange={e => setData('organisation', e.target.value)}
                                            className="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                            placeholder="Your organisation"
                                        />
                                    </div>
                                </div>
                            </div>

                            {/* Programme Selection */}
                            <div>
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">
                                    Programme Selection 
                                    <span className="text-sm font-normal text-gray-500">(Select up to 3)</span>
                                </h3>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    {programmeList.map((programme, index) => (
                                        <label 
                                            key={index}
                                            className={`flex items-center gap-3 p-3 rounded-lg border-2 cursor-pointer transition-all ${
                                                data.preferred_programmes.includes(programme)
                                                    ? 'border-blue-500 bg-blue-50 shadow-sm'
                                                    : 'border-gray-200 hover:border-gray-300'
                                            }`}
                                        >
                                            <input
                                                type="checkbox"
                                                checked={data.preferred_programmes.includes(programme)}
                                                onChange={() => handleProgrammeChange(programme)}
                                                className="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                            />
                                            <span className="text-sm text-gray-700">{programme}</span>
                                        </label>
                                    ))}
                                </div>
                                {errors.preferred_programmes && (
                                    <p className="text-red-500 text-sm mt-2">{errors.preferred_programmes}</p>
                                )}
                            </div>

                            {/* Personal Statement */}
                            <div>
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">
                                    Personal Statement <span className="text-red-500">*</span>
                                </h3>
                                <p className="text-sm text-gray-600 mb-3">
                                    Please provide a short statement (maximum 500 words) explaining:
                                </p>
                                <ul className="list-disc list-inside text-sm text-gray-600 mb-4 space-y-1">
                                    <li>Why you are applying</li>
                                    <li>Your career aspirations</li>
                                    <li>How the scholarship will support your professional development</li>
                                </ul>
                                <textarea
                                    value={data.personal_statement}
                                    onChange={e => setData('personal_statement', e.target.value)}
                                    rows={8}
                                    className="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                    placeholder="Write your personal statement here..."
                                    required
                                />
                                <div className="flex justify-between text-sm mt-1">
                                    <span className={data.personal_statement.length < 100 ? 'text-red-500' : 'text-gray-400'}>
                                        Min 100 characters
                                    </span>
                                    <span className="text-gray-400">
                                        {data.personal_statement.length} characters
                                    </span>
                                </div>
                                {errors.personal_statement && (
                                    <p className="text-red-500 text-sm mt-1">{errors.personal_statement}</p>
                                )}
                            </div>

                            {/* Declaration */}
                            <div>
                                <label className="flex items-start gap-3 cursor-pointer bg-gray-50 p-4 rounded-lg">
                                    <input
                                        type="checkbox"
                                        checked={data.declaration}
                                        onChange={e => setData('declaration', e.target.checked)}
                                        className="w-5 h-5 text-blue-600 rounded focus:ring-blue-500 mt-0.5"
                                        required
                                    />
                                    <span className="text-sm text-gray-700">
                                        I confirm that the information provided is accurate and complete.
                                    </span>
                                </label>
                                {errors.declaration && (
                                    <p className="text-red-500 text-sm mt-1">{errors.declaration}</p>
                                )}
                            </div>

                            {/* Submit Button */}
                            <div className="pt-4">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="w-full bg-blue-900 text-white py-4 px-6 rounded-xl font-semibold text-lg hover:bg-blue-800 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 shadow-lg hover:shadow-xl"
                                >
                                    {processing ? (
                                        <>
                                            <svg className="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                            Submitting Application...
                                        </>
                                    ) : (
                                        'Submit Application'
                                    )}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
           
        </GuestLayout>
    );
}