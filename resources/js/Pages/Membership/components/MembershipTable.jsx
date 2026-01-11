import React from 'react';
import { motion } from 'framer-motion';
import { usePage } from '@inertiajs/react';
import { fadeLeft, scaleIn } from '@/utils/motionPresets';

export default function MembershipTable() {
  const { membershipData } = usePage().props; // Fetching data passed from the server-side

  return (
    <div className="max-w-7xl mx-auto px-6 py-20">
      {/* SECTION HEADER */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start mb-15">
        {/* LEFT CONTENT */}
        <motion.div
          variants={fadeLeft}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true }}
          className="mb-16"
        >
          <div className="relative inline-flex items-center mb-4">
            <span className="absolute left-0 top-1/2 w-14 h-px bg-gray-300"></span>
            <span className="pl-20 text-sm tracking-widest uppercase text-gray-400">
              Membership Categories
            </span>
          </div>
          <h2 className="text-4xl xl:text-4xl font-bold text-blue-900">Membership Categories</h2>
        </motion.div>

        {/* RIGHT CONTENT */}
        <motion.div
          initial={{ opacity: 0, x: 40 }}
          whileInView={{ opacity: 1, x: 0 }}
          transition={{ duration: 0.6, ease: 'easeOut' }}
          viewport={{ once: true }}
        >
          <p className="text-gray-600 leading-relaxed mb-8">
            Join our professional community and enjoy a wide range of benefits tailored to different membership categories.
          </p>
        </motion.div>
      </div>

      {/* MEMBERSHIP TABLE */}
      <motion.div
        variants={scaleIn}
        initial="hidden"
        whileInView="visible"
        viewport={{ once: true }}
        className="overflow-x-auto"
      >
        <table className="min-w-full table-auto border-collapse border border-gray-300">
          <thead>
            <tr>
              <th className="px-6 py-3 text-left text-sm font-medium text-gray-600 bg-gray-100">Category</th>
              <th className="px-6 py-3 text-left text-sm font-medium text-gray-600 bg-gray-100">Annual Fee</th>
              <th className="px-6 py-3 text-left text-sm font-medium text-gray-600 bg-gray-100">Benefits</th>
            </tr>
          </thead>
          <tbody>
            {membershipData.map((row, index) => (
              <tr
                key={index}
                className={`${
                  index % 2 === 0 ? 'bg-gray-50' : 'bg-white'
                } hover:bg-gray-100 transition duration-300`}
              >
                <td className="px-6 py-4 text-sm text-gray-800">{row.category}</td>
                <td className="px-6 py-4 text-sm text-gray-800">{row.annualFee}</td>
                <td className="px-6 py-4 text-sm text-gray-600">{row.benefits}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </motion.div>
    </div>
  );
}
