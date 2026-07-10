import React from "react";
import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import MentorshipProgramme from "@/Pages/Membership/components/MentorshipProgramme";
import HowMembershipWorks from "@/Pages/Membership/components/HowMembershipWorks";
import MembershipTable from "@/Pages/Membership/components/MembershipTable";
import WhyJoinIGRCFP from '@/Pages/Membership/components/WhyJoinIGRCFP';
import MembershipOptions from "@/Pages/components/MembershipOptions";
import CallToAction from "@/Pages/components/CallToAction";
import HeroSection from '@/Layouts/HeroSection';

export default function Membership({ auth, title, description }) {
    return (
        <GuestLayout auth={auth}>
            <Head title={title} />
            {/* Hero Section */}
            <HeroSection 
                title = {title}
                description= " The Institute of Governance, Risk, Compliance & Financial Crime Prevention "
            />

            <WhyJoinIGRCFP />
            {/* <JoinIGRCFP /> */}
            <MembershipOptions />
            <MembershipTable/>
            <MentorshipProgramme />
            <HowMembershipWorks />
            <CallToAction />
        </GuestLayout>
    );
}