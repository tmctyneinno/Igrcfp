import { useState, useEffect } from 'react';

export default function CoreServices() {
    const services = [
        {
            title: 'Education & Certification',
            description:
                'We deliver CPD-accredited certifications, diplomas, and specialised training programmes that equip professionals and institutions with the skills to meet global compliance and financial crime prevention standards.',
            icon: '🎓', // You can replace this with an actual icon or SVG
        },
        {
            title: 'Membership Community',
            description:
                'We provide a professional network that connects students, practitioners, and organisations worldwide, offering exclusive benefits, recognition, and career development opportunities.',
            icon: '🧑‍🤝‍🧑', // You can replace this with an actual icon or SVG
        },
        {
            title: 'Advocacy & Research',
            description:
                'Through policy engagement, research collaborations, and publications, we shape industry standards and provide thought leadership on governance, compliance, and financial crime prevention.',
            icon: '📣', // You can replace this with an actual icon or SVG
        },
        {
            title: 'Global Events & Summits',
            description:
                'We organise international conferences, workshops, webinars, and forums that bring together regulators, professionals, and innovators to exchange knowledge, network, and set future directions.',
            icon: '🌍', // You can replace this with an actual icon or SVG
        },
        {
            title: 'Regulation & Enforcement Support',
            description:
                'Working closely with regulatory bodies, law enforcement agencies, and financial institutions to support the development and enforcement of anti-fraud measures.',
            icon: '⚖️', // You can replace this with an actual icon or SVG
        },
        {
            title: 'Consultancy Services',
            description:
                'Offering expert consultancy in financial crime prevention strategies, compliance frameworks, and risk assessment to organizations seeking to enhance their security and regulatory measures.',
            icon: '💼', // You can replace this with an actual icon or SVG
        },
    ];

    return (
        <section className="py-20 bg-white">
            <div className="max-w-6xl mx-auto px-4 text-center">
                <h1 className="text-3xl font-bold text-gray-900 mb-10">Our Core Services</h1>
                <p className='mb-12 te'>
                    At IGRCFP, we empower professionals and institutions through education, membership, research, and global events. Our core services are designed to strengthen governance, compliance, and financial crime prevention worldwide
                </p>
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12">
                    {services.map((service, index) => (
                        <div
                            key={index}
                            className="p-6 bg-gray-100 rounded-lg shadow-md hover:bg-gray-200 transition-all"
                        >
                            <div className="text-4xl mb-4 text-blue-500">{service.icon}</div>
                            <h2 className="text-xl font-semibold text-gray-900 mb-4">{service.title}</h2>
                            <p className="text-gray-700">{service.description}</p>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
