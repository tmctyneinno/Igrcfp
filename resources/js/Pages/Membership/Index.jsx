import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import MentorshipProgramme from "@/Pages/Membership/components/MentorshipProgramme";
import MembershipTable from "@/Pages/Membership/components/MembershipTable";
import MembershipCTA from '@/Pages/Membership/components/MembershipCTA';
import MembershipOptions from "@/Pages/components/MembershipOptions";
import JoinIGRCFP from "@/Pages/components/JoinIGRCFP";

export default function Membership({ auth, title, description }) {
    return (
        <GuestLayout auth={auth}>
            <Head title={title} />
            {/* Hero Section */}
            <section className="w-full bg-gradient-to-r from-blue-200 via-white to-blue-200 py-28">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                   <div className="text-center">
                        <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                            {title}
                        </h1>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            The Institute of Governance, Risk, Compliance & Financial Crime Prevention 
                        </p>
                    </div>
                </div>
            </section>

            <MembershipCTA />
            <JoinIGRCFP />
            <MembershipOptions />
            <MembershipTable/>
            <MentorshipProgramme />
           
        </GuestLayout>
    );
}