import React from 'react';

const regionsData = [
  {
    country: 'United Kingdom',
    description: 'Our primary regulatory and operational base',
    flag: '🇬🇧',
  },
  {
    country: 'Nigeria and West Africa',
    description: 'A major centre of GRC and financial crime activity',
    flag: '🇳🇬',
  },
  {
    country: 'East Africa',
    description: 'Including Kenya, where financial crime prevention is a regulatory priority',
    flag: '🇰🇪',
  },
  {
    country: 'Ghana and the ECOWAS region',
    description: 'Growing compliance education demand',
    flag: '🇬🇭',
  },
  {
    country: 'The Caribbean',
    description: 'Subject to FATF oversight and active AML/CFT reform',
    flag: '🌴',
  },
  {
    country: 'The Middle East and Gulf States',
    description: 'Expanding financial regulation and GRC frameworks',
    flag: '🇦🇪',
  },
  {
    country: 'Malta and the European Union',
    description: 'A key hub for financial services regulation',
    flag: '🇪🇺',
  },
  {
    country: 'International',
    description: 'Serving members wherever regulated financial activity occurs',
    flag: '🌍',
  },
];

export default function InternationalReach() {
  return (
    <section className="py-20 bg-gray-50">
      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Section Header */}
        <div className="text-center mb-12">
          <h2 className="text-3xl md:text-4xl font-medium text-gray-900 mb-4">
            Our International Reach
          </h2>
          <p className="text-lg text-gray-600 max-w-3xl mx-auto">
            IGRCFP operates across multiple jurisdictions, with a particular focus on:
          </p>
        </div>

        {/* Regions Grid */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {regionsData.map((region, index) => (
            <div
              key={index}
              className="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 p-6 border border-gray-100 hover:border-blue-200 group"
            >
              <div className="flex items-center gap-3 mb-4">
                <span className="text-4xl">{region.flag}</span>
                <h3 className="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                  {region.country}
                </h3>
              </div>
              <p className="text-gray-600 text-sm leading-relaxed">
                {region.description}
              </p>
            </div>
          ))}
        </div>

        {/* Optional: Decorative world map hint or callout */}
        <div className="mt-12 text-center">
          <div className="inline-flex items-center gap-2 text-sm text-gray-500 bg-white px-4 py-2 rounded-full shadow-sm">
            <span>🗺️</span>
            <span>Global presence across 4 continents</span>
          </div>
        </div>
      </div>
    </section>
  );
}