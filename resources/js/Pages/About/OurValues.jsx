import React from 'react';
import BackgroundVideo from '@/Pages/About/BackgroundVideo';

const valuesData = [
  {
    title: 'Integrity',
    description:
      'We hold ourselves and our members to the highest standards of professional and ethical conduct. In a field devoted to preventing financial crime, integrity is not optional.',
  },
  {
    title: 'Excellence',
    description:
      'We pursue rigorous standards in everything we produce from qualifications to publications because the professionals we serve deserve nothing less.',
  },
  {
    title: 'Independence',
    description:
      'Our standards and positions are determined by professional merit and practitioner insight, free from commercial or political influence.',
  },
  {
    title: 'Global Reach',
    description:
      'Financial crime knows no borders. IGRCFP serves members across the UK, Africa, the Caribbean, the Middle East, and beyond.',
  },
  {
    title: 'Inclusion',
    description:
      'We are committed to widening access to professional qualifications and membership across all backgrounds and regions.',
  },
  {
    title: 'Collaboration',
    description:
      'We work alongside universities, regulators, employers and professional organisations to advance the discipline.',
  },
];

export default function OurValues() {
  return (
    <section className="py-10 bg-white">
      <div className="max-w-6xl mx-auto px-2 sm:px-6 lg:px-8">
        {/* Section Title */}
        <div className="text-center mb-12">
          <h2 className="text-3xl md:text-4xl font-medium text-gray-900">Our Values</h2>
        </div>

        {/* Flexbox layout: Left - Values, Right - Image */}
        <div className="flex flex-col lg:flex-row items-center justify-between gap-10">
            {/* Left Side - Values Section - 60% */}
            <div className="flex-[6] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 h-full">
                {valuesData.map((value, index) => (
                    <div
                        key={index}
                        className="bg-gray-100 p-2.5 rounded-lg shadow-md text-center flex flex-col justify-between h-full"
                    >
                        <div className="mb-0">
                            <div className="w-16 h-16 bg-gray-300 mx-auto mb-0 rounded-full flex items-center justify-center">
                                <span className="text-3xl text-gray-700">📦</span>
                            </div>
                        </div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">{value.title}</h3>
                        <p className="text-gray-600">{value.description}</p>
                    </div>
                ))}
            </div>

            {/* Right Side - Image - 40% */}
            <div className="flex-[4] h-full">
                <BackgroundVideo />
            </div>
        </div>
      </div>
    </section>
  );
}