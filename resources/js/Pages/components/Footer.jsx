import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

export default function Footer() {
    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div data-aos="fade-up" data-aos-delay="100" data-aos-duration="1400">
                    <div className="flex items-center mb-4">
                        <img 
                            src="/assets/images/home-three/logo/logo-main.png" 
                            alt="Logo" 
                            className="h-8 w-auto"
                        />
                        <span className="ml-2 text-xl font-bold">IGRCFP</span>
                    </div>
                    <p className="text-gray-400">
                        Transforming education through personalized learning experiences.
                    </p>
                </div>
                <div data-aos="fade-up" data-aos-delay="200" data-aos-duration="1400">
                    <h4 className="font-semibold mb-4">Quick Links</h4>
                    <ul className="space-y-2">
                        <li><Link href="/" className="text-gray-400 hover:text-white">Home</Link></li>
                        <li><Link href="/courses" className="text-gray-400 hover:text-white">Courses</Link></li>
                        <li><Link href="/tutors" className="text-gray-400 hover:text-white">Tutors</Link></li>
                        <li><Link href="/about" className="text-gray-400 hover:text-white">About Us</Link></li>
                    </ul>
                </div>
                <div data-aos="fade-up" data-aos-delay="300" data-aos-duration="1400">
                    <h4 className="font-semibold mb-4">Legal</h4>
                    <ul className="space-y-2">
                        <li><Link href="/terms" className="text-gray-400 hover:text-white">Terms of Service</Link></li>
                        <li><Link href="/privacy" className="text-gray-400 hover:text-white">Privacy Policy</Link></li>
                        <li><Link href="/cookies" className="text-gray-400 hover:text-white">Cookie Policy</Link></li>
                    </ul>
                </div>
                <div data-aos="fade-up" data-aos-delay="400" data-aos-duration="1400">
                    <h4 className="font-semibold mb-4">Contact Us</h4>
                    <ul className="space-y-2 text-gray-400">
                        <li>Email: enquires@igrcfp.org</li>
                        {/* <li>Phone: +1 (555) 123-4567</li> */}
                        <li>Address: 85, Great Portland Street First Floor W1W 7LT, London, United Kingdom</li>
                    </ul>
                </div>
            </div>
            <div 
                className="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400"
                data-aos="fade-up"
                data-aos-delay="500"
                data-aos-duration="1400"
            >
                <p>&copy; {new Date().getFullYear()} IGRCFP. All rights reserved.</p>
            </div>
        </div>
    );
}
