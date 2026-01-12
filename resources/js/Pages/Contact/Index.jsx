import React, { useState } from 'react';
import { Head } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function ContactUs({ auth, title }) {
    const [formData, setFormData] = useState({
        firstName: '',
        lastName: '',
        email: '',
        phone: '',
        message: '',
        agree: false,
    });

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData((prevState) => ({
            ...prevState,
            [name]: value,
        }));
    };

    const handleCheckboxChange = (e) => {
        setFormData((prevState) => ({
            ...prevState,
            agree: e.target.checked,
        }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        // Add your submit logic here
        console.log('Form Submitted:', formData);
    };

    return (
        <GuestLayout auth={auth}>
            <Head title={title} />
            {/* Hero Section */}
            <section className="w-full bg-gradient-to-r from-blue-200 via-white to-blue-200 py-28">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">{title}</h1>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            The Institute of Governance, Risk, Compliance & Financial Crime Prevention
                        </p>
                    </div>
                </div>
            </section>

            {/* Contact Us Form */}
            <section className="w-full py-12">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-8">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Contact us</h2>
                        <p className="text-lg text-gray-600">Our friendly team would love to hear from you.</p>
                    </div>
                    <form onSubmit={handleSubmit} className="bg-white shadow-lg rounded-lg p-8 space-y-6">
                        {/* Name Fields */}
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label htmlFor="firstName" className="text-sm font-medium text-gray-700">First name</label>
                                <input
                                    type="text"
                                    name="firstName"
                                    id="firstName"
                                    className="mt-2 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    value={formData.firstName}
                                    onChange={handleChange}
                                    required
                                />
                            </div>
                            <div>
                                <label htmlFor="lastName" className="text-sm font-medium text-gray-700">Last name</label>
                                <input
                                    type="text"
                                    name="lastName"
                                    id="lastName"
                                    className="mt-2 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    value={formData.lastName}
                                    onChange={handleChange}
                                    required
                                />
                            </div>
                        </div>

                        {/* Email */}
                        <div>
                            <label htmlFor="email" className="text-sm font-medium text-gray-700">Email</label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                className="mt-2 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                value={formData.email}
                                onChange={handleChange}
                                required
                            />
                        </div>

                        {/* Phone Number */}
                        <div>
                            <label htmlFor="phone" className="text-sm font-medium text-gray-700">Phone number</label>
                            <div className="flex items-center mt-2">
                                <select
                                    name="countryCode"
                                    className="mr-3 p-2 border border-gray-300 rounded-md"
                                >
                                    <option value="NG">NG</option>
                                    {/* Add more country codes as necessary */}
                                </select>
                                <input
                                    type="tel"
                                    name="phone"
                                    id="phone"
                                    className="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    value={formData.phone}
                                    onChange={handleChange}
                                    required
                                />
                            </div>
                        </div>

                        {/* Message */}
                        <div>
                            <label htmlFor="message" className="text-sm font-medium text-gray-700">Message</label>
                            <textarea
                                name="message"
                                id="message"
                                rows="4"
                                className="mt-2 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                value={formData.message}
                                onChange={handleChange}
                                required
                            />
                        </div>

                        {/* Privacy Policy */}
                        <div className="flex items-center">
                            <input
                                type="checkbox"
                                name="agree"
                                id="agree"
                                className="h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                checked={formData.agree}
                                onChange={handleCheckboxChange}
                                required
                            />
                            <label htmlFor="agree" className="ml-2 text-sm text-gray-600">
                                You agree to our <a href="/privacy-policy" className="text-indigo-600">privacy policy</a>.
                            </label>
                        </div>

                        {/* Submit Button */}
                        <div className="text-center">
                            <button
                                type="submit"
                                className="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                Send message
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            {/* Footer Section */}
            <footer className="w-full bg-gradient-to-r from-blue-200 via-white to-blue-200 py-12 mt-12">
                <div className="max-w-7xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                    <p className="text-lg text-gray-900">We would love to hear from you</p>
                    <a href="mailto:info@jgrcfp.org" className="text-indigo-600 hover:text-indigo-700 mt-2 block">
                        Send us an email @ info@jgrcfp.org
                    </a>
                </div>
            </footer>
        </GuestLayout>
    );
}
