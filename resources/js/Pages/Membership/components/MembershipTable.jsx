import { motion } from "framer-motion";
import { scaleIn } from "@/utils/motionPresets";

export default function MembershipTable() {

    const membershipData = [
    {
        category: "Student Affiliate",
        annualFee: "$50",
        benefits: "Access to online community, selected resources, and discounts on training.",
    },
    {
        category: "Associate Member (A.IGRCFP)",
        annualFee: "$150",
        benefits: "Full access to resources, participation in events, and certification discounts.",
    },
    {
        category: "Professional Member",
        annualFee: "$250",
        benefits: "Full access to resources, participation in events, and certification discounts.",
    },
    {
        category: "Fellow (F.IGRCFP)",
        annualFee: "$350",
        benefits: "Leadership recognition, eligibility for governance roles, and priority speaker slots.",
    },
    {
        category: "Corporate Membership",
        annualFee: "$1500",
        benefits: "Multi-user access, brand recognition, and sponsorship discounts.",
    },
];

    return (
        <div className="max-w-7xl mx-auto px-6 py-20">
            <motion.div
                variants={scaleIn}
                initial="hidden"
                whileInView="visible"
                viewport={{ once: true }}
                className="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm"
            >
                <table className="min-w-full table-auto border-collapse">
                    <thead>
                        <tr className="bg-gray-100">
                            <th className="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                Category
                            </th>
                            <th className="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                Annual Fee
                            </th>
                            <th className="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                Benefits
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        {membershipData.map((row, index) => (
                            <tr
                                key={index}
                                className={`${
                                    index % 2 === 0 ? "bg-gray-50" : "bg-white"
                                } hover:bg-blue-50 transition`}
                            >
                                <td className="px-6 py-4 text-sm text-gray-900 font-medium">
                                    {row.category}
                                </td>
                                <td className="px-6 py-4 text-sm text-gray-900">
                                    {row.annualFee}
                                </td>
                                <td className="px-6 py-4 text-sm text-gray-600 leading-relaxed">
                                    {row.benefits}
                                </td>
                            </tr>
                        ))}

                        {membershipData.length === 0 && (
                            <tr>
                                <td
                                    colSpan={3}
                                    className="px-6 py-8 text-center text-sm text-gray-500"
                                >
                                    No membership data available.
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </motion.div>
        </div>
    );
}
