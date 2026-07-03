import React from "react";
import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeIn, scaleIn, staggerContainer } from "@/utils/motionPresets";

export default function AuthenticatedFooter() {
    const currentYear = new Date().getFullYear();
     
    return (  
       
        <footer className=" bg-white mt-auto">
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
              
                {/* Bottom Bar */}
                <div className="mt-8 pt-8 border-t border-gray-200">
                    <div className="flex flex-col md:flex-row justify-between items-center">
                        <p className="text-sm text-gray-500">
                            &copy; {new Date().getFullYear()} IGRCFP. All rights reserved.
                        </p>
                        <div className="flex space-x-6 mt-4 md:mt-0">
                            <span className="text-sm text-gray-500">
                                Version 1.0.0
                            </span>
                            <span className="text-sm text-gray-500">
                                <a href="https://tynesideinnovation.com/">
                                Built by Tyneside Innivation
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    );
}