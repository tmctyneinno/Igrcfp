import React from "react";
import { motion } from "framer-motion";
import { fadeLeft } from "@/utils/motionPresets";

export default function GoverningCouncil() {
  return (
    <section className="py-16 bg-white">
      <div className="max-w-6xl mx-auto px-6">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">

          {/* --- THE GOVERNING COUNCIL --- */}
          <motion.div
            variants={fadeLeft}
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true }}
          >
            <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
              THE <span className="text-[#0A2463]">GOVERNING COUNCIL</span>
            </h2>

            <p className="text-gray-700 leading-relaxed mb-6">
              The Governing Council is responsible for the formulation and implementation of the Institute's policies, providing direction for the activities of the Executive Management, and overseeing the Institute's core functions, including membership, education, certification, and research.
            </p>

            <div className="relative inline-flex items-center mb-3">
              <motion.span
                initial={{ width: 0 }}
                whileInView={{ width: 48 }}
                transition={{ duration: 0.5, ease: "easeOut" }}
                viewport={{ once: true }}
                className="absolute left-0 top-1/2 h-px bg-[#0A2463]"
              />
              <span className="text-sm tracking-widest text-gray-500 pl-14 uppercase">
                KEY RESPONSIBILITIES
              </span>
            </div>

            <ul className="list-disc list-inside text-gray-700 space-y-2 pl-2">
              <li>Developing and reviewing policies to ensure alignment with the Institute's objectives.</li>
              <li>Approving the Institute's code of conduct, certification requirements, and membership policies.</li>
              <li>Advising on matters related to education, certification, and professional development.</li>
              <li>Overseeing disciplinary matters and upholding ethical standards.</li>
              <li>Providing guidance on partnerships and collaboration with regulatory bodies.</li>
            </ul>
          </motion.div>

          {/* --- ADVISORY COMMITTEES --- */}
          <motion.div
            variants={fadeLeft}
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true }}
            transition={{ delay: 0.2 }}
          >
            <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
              <span className="text-[#0A2463]">ADVISORY</span> COMMITTEES
            </h2>

            <p className="text-gray-700 leading-relaxed">
              Specialised working groups focused on Education, Research, Policy, and Ethics, fostering collaboration among professionals to shape standards, influence regulatory frameworks, and promote ethical excellence within the industry.
            </p>
          </motion.div>

        </div>
      </div>
    </section>
  );
}