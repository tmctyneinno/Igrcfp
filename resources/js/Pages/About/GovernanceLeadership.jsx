import React from 'react';

export default function GovernanceLeadership() {
  return (
    <section className="py-10 bg-white">
      <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="text-center mb-16">
          <h1 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Governance & Leadership
          </h1>
          <div className="w-16 h-1 bg-blue-500 mx-auto rounded-full"></div>
        </div>

        {/* Content Container */}
        <div className="space-y-8">
          {/* Card 1: Leadership */}
          <div className="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow duration-300">
            <div className="flex items-start gap-4">
              <div className="flex-shrink-0">
                <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                  <span className="text-2xl">👤</span>
                </div>
              </div>
              <div>
                <h3 className="text-xl font-semibold text-gray-900 mb-2">Governance Structure</h3>
                <p className="text-gray-700 leading-relaxed">
                  IGRCFP is governed by the <span className="font-medium text-blue-600">President, Advisory Board </span> and guided by a 
                  professional standards framework that underpins the integrity of our qualifications, 
                  membership and designations.
                </p>
              </div>
            </div>
          </div>

          {/* Card 2: Principles */}
          <div className="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow duration-300">
            <div className="flex items-start gap-4">
              <div className="flex-shrink-0">
                <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                  <span className="text-2xl">⚙️</span>
                </div>
              </div>
              <div>
                <h3 className="text-xl font-semibold text-gray-900 mb-2">Core Principles</h3>
                <p className="text-gray-700 leading-relaxed">
                  Our governance structure is designed to ensure that IGRCFP operates with full 
                  <span className="font-medium text-blue-600"> transparency, independence and accountability</span> 
                  {' '}to its members and the wider professional community.
                </p>
              </div>
            </div>
          </div>

          {/* Callout Box */}
          <div className="border-l-4 border-blue-500 bg-blue-50 p-6 rounded-r-xl">
            <p className="text-gray-700">
              <span className="font-semibold">Our Commitment:</span> Every decision, qualification, 
              and membership designation is backed by a governance framework that prioritizes 
              professional integrity above all else.
            </p>
          </div>
        </div>
      </div>
    </section>
  );
}