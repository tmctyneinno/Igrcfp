import { motion } from 'framer-motion';
import { usePage } from '@inertiajs/react';
import { fadeLeft, scaleIn } from '@/utils/motionPresets';

export default function MembershipTable({ membershipData = [] }) {


  return (
    <div className="max-w-7xl mx-auto px-6 py-20">
    
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
