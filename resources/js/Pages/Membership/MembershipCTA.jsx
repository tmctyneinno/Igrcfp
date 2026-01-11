import { Link } from '@inertiajs/react';

export default function MembershipCTA() {
    return (
        <section className="bg-white py-24">
            <div className="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                {/* LEFT CONTENT */}
                <div className="max-w-xl">
                    <p className="text-gray-700 text-lg leading-relaxed mb-10">
                        Join a trusted professional community advancing excellence in
                        governance, compliance, and financial crime prevention.
                        Membership at IGRCFP connects you with peers across the world,
                        gives you access to exclusive resources, and provides professional
                        recognition that sets you apart.
                    </p>

                    <Link
                        href="/login"
                        className="inline-flex items-center gap-3 bg-blue-950 text-white px-7 py-4 rounded-lg font-medium hover:bg-blue-800 transition"
                    >
                        Become a Member Today
                        <span className="text-xl">→</span>
                    </Link>
                </div>

                {/* RIGHT IMAGE COLLAGE */}
                <div className=" h-10 md:h-16 object-contain w-full">
                    <motion.div
                    initial={{ opacity: 0, scale: 0.95 }}
                    whileInView={{ opacity: 1, scale: 1 }}
                    transition={{ duration: 0.6, ease: "easeOut" }}
                    viewport={{ once: true }}
                    className="rounded-3xl overflow-hidden"
                >
                    <img
                        src="/assets/images/home-three/gallery/membership-img2.png"
                        alt="Members celebrating"
                        className="max-w-2xl w-full pt-24"
                    />
                </motion.div>
                    {/* Top images */}
                    <img
                        src="assets/images/home-three/gallery/membership.png"
                        alt=""
                        className=" h-10 md:h-16 object-contain rounded-md"
                    />


                </div>

            </div>
        </section>
    );
}
