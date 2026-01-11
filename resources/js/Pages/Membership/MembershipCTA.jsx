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
                        href="/membership"
                        className="inline-flex items-center gap-3 bg-blue-900 text-white px-7 py-4 rounded-lg font-medium hover:bg-blue-800 transition"
                    >
                        Become a Member Today
                        <span className="text-xl">→</span>
                    </Link>
                </div>

                {/* RIGHT IMAGE COLLAGE */}
                <div className="relative h-[420px] w-full">

                    {/* Top images */}
                    <img
                        src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1"
                        alt=""
                        className="absolute top-0 left-20 w-36 h-44 object-cover rounded-md"
                    />

                    <img
                        src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e"
                        alt=""
                        className="absolute top-0 right-10 w-36 h-44 object-cover rounded-md"
                    />

                    {/* Middle images */}
                    <img
                        src="https://images.unsplash.com/photo-1520813792240-56fc4a3765a7"
                        alt=""
                        className="absolute top-32 left-0 w-44 h-52 object-cover rounded-md"
                    />

                    <img
                        src="https://images.unsplash.com/photo-1544005313-94ddf0286df2"
                        alt=""
                        className="absolute top-32 right-24 w-44 h-52 object-cover rounded-md"
                    />

                    {/* Bottom image */}
                    <img
                        src="https://images.unsplash.com/photo-1527980965255-d3b416303d12"
                        alt=""
                        className="absolute bottom-0 right-0 w-44 h-52 object-cover rounded-md"
                    />
                </div>

            </div>
        </section>
    );
}
