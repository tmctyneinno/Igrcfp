import React from "react";
import { motion } from "framer-motion";
import { fadeIn } from "@/utils/motionPresets";

// Define the component with dynamic props
export default function HeroSection({
  title = "Courses",
  description = "Browse our latest professional learning programmes.",
}) { 
  return (
    <section className="w-full bg-[#0A1A2F] text-white pt-28 pb-10 relative overflow-hidden">
      {/* ===== HERO SECTION ===== */}
      <div className="max-w-7xl mx-auto px-6 lg:px-8">
        <motion.div
          variants={fadeIn}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true }}
          className="text-left"
        >
          {/* Top line text */}
          <div className="flex items-center gap-4 mb-4">
            <motion.span
              initial={{ width: 0 }}
              whileInView={{ width: 48 }}
              transition={{ duration: 0.5, ease: "easeOut" }}
              viewport={{ once: true }}
              className="h-px bg-gray-300"
            />
            <span className="text-sm tracking-widest text-gray-300 uppercase">
              Professional Body . Global Standards . London, UK
            </span>
          </div>

          {/* Main Heading - Dynamic */}
          <h2 className="text-4xl md:text-5xl lg:text-5xl font-bold text-white mb-4">
            {title}
          </h2>

          {/* Subtitle - Dynamic */}
          <p className="text-lg md:text-xl text-gray-300 max-w-6xl">
            {description}
          </p>
        </motion.div>

        {/* Bottom Tagline Bar */}
        <motion.div
          variants={fadeIn}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true }}
          transition={{ delay: 0.2 }}
          className="mt-12 pt-4 border-t border-gray-700 flex flex-wrap gap-x-2 gap-y-2 text-xs uppercase tracking-wider text-gray-300"
        >
          <span>Terrorism Financing</span>
          <span>•</span>
          <span>KYC & CDD</span>
          <span>•</span>
          <span>Sanctions Compliance</span>
          <span>•</span>
          <span>Enterprise Risk Management</span>
          <span>•</span>
          <span>Regulatory Frameworks</span>
          <span>•</span>
          <span>ESG Sustainable Finance</span>
          <span>•</span>
          <span>AI in Compliance</span>
        </motion.div>
      </div>
    </section>
  );
}