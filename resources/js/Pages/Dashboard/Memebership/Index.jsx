import React from "react";
import { Head, Link, router } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import {
    CheckBadgeIcon,
    ClockIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon,
    UserGroupIcon,
    SparklesIcon,
    ArrowRightIcon,
} from "@heroicons/react/24/outline";

export default function MembershipDashboard({
    auth,
    membership,
    membershipState,
    mentorAccess,
    tiers = [],
    featuredMentors = [],
    communityMembers = [],
}) {
    const hasActiveMembership = Boolean(membershipState?.has_active_membership);
    const hasMentorMembership = Boolean(membershipState?.has_mentor_membership);
    const isPendingApproval = Boolean(membershipState?.is_pending_approval);
    const isPendingPayment = Boolean(membershipState?.is_pending_payment);
    const mentorApplicationStatus = mentorAccess?.application_status ?? null;
    const hasMentorProfile = Boolean(mentorAccess?.has_mentor_profile);
    const mentorApplicationFeedback = mentorAccess?.application_feedback;
    const mentorApplicationProcessedAt = mentorAccess?.application_processed_at;

    const shouldShowPlans = !hasActiveMembership && !isPendingApproval;
    const shouldShowCommunity = hasActiveMembership;

    const addToCart = (planId) => {
        router.post(route("dashboard.memberships.add-to-cart", planId));
    };

    const initials = (name = "") => {
        return name
            .split(" ")
            .map((part) => part[0])
            .join("")
            .slice(0, 2)
            .toUpperCase();
    };

    const mentorCardState = (() => {
        if (!hasMentorMembership) {
            return {
                tone: "slate",
                title: "Mentor Membership Access",
                message: "You can apply to become a mentor after upgrading to an active mentor membership plan.",
                actionLabel: "Upgrade to Mentor Plan",
                actionHref: route("dashboard.memberships.index"),
                icon: ExclamationTriangleIcon,
            };
        }

        if (hasMentorProfile || mentorApplicationStatus === "approved") {
            return {
                tone: "emerald",
                title: "Mentor Application Approved",
                message: "Great news. Your mentor profile is approved and you can now manage mentor/mentee activities.",
                actionLabel: "Open Mentorship Dashboard",
                actionHref: route("dashboard.mentorships.index"),
                icon: CheckCircleIcon,
            };
        }

        if (mentorApplicationStatus === "pending") {
            return {
                tone: "amber",
                title: "Mentor Application Under Review",
                message: "Your mentor application is pending admin approval. We'll notify you once it is reviewed.",
                actionLabel: "View Application Status",
                actionHref: route("dashboard.mentors.apply-to-become"),
                icon: ClockIcon,
            };
        }

        if (mentorApplicationStatus === "declined") {
            return {
                tone: "rose",
                title: "Mentor Application Needs Update",
                message: "Your last mentor application was not approved. Please review feedback and submit again.",
                actionLabel: "Update & Reapply",
                actionHref: route("dashboard.mentors.apply-to-become"),
                icon: ExclamationTriangleIcon,
            };
        }

        return {
            tone: "blue",
            title: "Start Your Mentor Application",
            message: "You have mentor membership access. Complete your mentor profile application to get approved.",
            actionLabel: "Apply as Mentor",
            actionHref: route("dashboard.mentors.apply-to-become"),
            icon: CheckBadgeIcon,
        };
    })();

    const mentorToneClasses = {
        slate: "border-slate-200 bg-slate-50 text-slate-900",
        emerald: "border-emerald-200 bg-emerald-50 text-emerald-900",
        amber: "border-amber-200 bg-amber-50 text-amber-900",
        rose: "border-rose-200 bg-rose-50 text-rose-900",
        blue: "border-blue-200 bg-blue-50 text-blue-900",
    };

    const MentorStateIcon = mentorCardState.icon;

    return (
        <AuthenticatedLayout auth={auth}>
            <Head title="Membership & Mentorship" />

            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div className="relative overflow-hidden rounded-3xl border border-sky-200 bg-gradient-to-br from-sky-50 via-white to-cyan-100 p-6 sm:p-10 shadow-sm">
                    <div className="absolute -top-24 -right-20 h-64 w-64 rounded-full bg-cyan-200/40 blur-3xl" />
                    <div className="absolute -bottom-24 -left-20 h-64 w-64 rounded-full bg-sky-200/40 blur-3xl" />
                    <div className="relative">
                        <div className="mb-3 inline-flex items-center gap-2 rounded-full bg-white/80 px-3 py-1 text-xs font-semibold text-sky-700">
                            <SparklesIcon className="h-4 w-4" />
                            Membership Hub
                        </div>
                        <h1 className="text-3xl sm:text-4xl font-bold text-slate-900">
                            Membership & Mentorship
                        </h1>
                        <p className="mt-3 max-w-3xl text-slate-600">
                            We tailored this page to your membership status so you can quickly choose a plan, connect with mentors,
                            and grow your network.
                        </p>
                    </div>
                </div>

                {isPendingApproval && (
                    <div className="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-5 text-amber-900">
                        <div className="flex items-start gap-3">
                            <ClockIcon className="h-6 w-6 flex-shrink-0" />
                            <div>
                                <h2 className="font-semibold text-lg">Subscription Under Review</h2>
                                <p className="mt-1 text-sm">
                                    Your membership is currently waiting for admin approval. We will notify you once it is approved.
                                </p>
                                {membership?.plan_name && (
                                    <p className="mt-2 text-sm font-medium">
                                        Plan: {membership.plan_name} {membership?.tier_name ? `(${membership.tier_name})` : ""}
                                    </p>
                                )}
                            </div>
                        </div>
                    </div>
                )}

                {isPendingPayment && shouldShowPlans && (
                    <div className="mt-6 rounded-2xl border border-rose-200 bg-rose-50 p-5 text-rose-900">
                        <h2 className="font-semibold text-lg">Payment Not Completed</h2>
                        <p className="mt-1 text-sm">Complete your checkout to activate your membership and unlock mentor access.</p>
                        <Link
                            href={route("dashboard.cart.index")}
                            className="mt-4 inline-flex items-center gap-2 rounded-lg bg-rose-700 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-600"
                        >
                            Go to Cart <ArrowRightIcon className="h-4 w-4" />
                        </Link>
                    </div>
                )}

                {shouldShowPlans && (
                    <div className="mt-8 space-y-8">
                        <div className="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                            <div>
                                <h2 className="text-2xl font-bold text-slate-900">Choose Your Membership Plan</h2>
                                <p className="text-slate-600 text-sm mt-1">
                                    Subscribe first to unlock mentor discovery and community connection.
                                </p>
                            </div>
                            <Link
                                href={route("dashboard.memberships.status")}
                                className="inline-flex items-center gap-2 text-sm font-semibold text-sky-700 hover:text-sky-800"
                            >
                                View Membership Status <ArrowRightIcon className="h-4 w-4" />
                            </Link>
                        </div>

                        {tiers.map((tier) => (
                            <div key={tier.id}>
                                <div className="mb-4">
                                    <h3 className="text-lg font-semibold text-slate-900">{tier.name}</h3>
                                    {tier.description && <p className="text-sm text-slate-600 mt-1">{tier.description}</p>}
                                </div>
                                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                                    {tier.plans.map((plan) => (
                                        <div
                                            key={plan.id}
                                            className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition"
                                        >
                                            <p className="text-sm font-semibold text-slate-700">{plan.name}</p>
                                            <p className="mt-2 text-2xl font-bold text-slate-900">
                                                {plan.currency} {Number(plan.price).toFixed(2)}
                                                <span className="text-sm font-medium text-slate-500"> / {plan.billing_interval}</span>
                                            </p>
                                            <ul className="mt-4 space-y-2 text-sm text-slate-600">
                                                {(plan.benefits?.length ? plan.benefits : tier.benefits || [])
                                                    .slice(0, 4)
                                                    .map((benefit, index) => (
                                                        <li key={index} className="flex items-start gap-2">
                                                            <CheckBadgeIcon className="h-4 w-4 mt-0.5 text-emerald-600 flex-shrink-0" />
                                                            <span>{benefit}</span>
                                                        </li>
                                                    ))}
                                            </ul>
                                            <button
                                                onClick={() => addToCart(plan.id)}
                                                className="mt-5 w-full rounded-xl bg-sky-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-sky-600 transition"
                                            >
                                                Select Plan
                                            </button>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        ))}
                    </div>
                )}

                {shouldShowCommunity && (
                    <div className="mt-8 space-y-8">
                        <div className="grid grid-cols-1 xl:grid-cols-3 gap-6">
                            <div className="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                                <div className="mb-4 flex items-center justify-between">
                                    <h2 className="text-xl font-bold text-slate-900">Mentors You Can Connect With</h2>
                                    <Link
                                        href={route("dashboard.mentors.index")}
                                        className="text-sm font-semibold text-sky-700 hover:text-sky-800"
                                    >
                                        View all
                                    </Link>
                                </div>

                                {featuredMentors.length > 0 ? (
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {featuredMentors.map((mentor) => (
                                            <div
                                                key={mentor.id}
                                                className="rounded-xl border border-slate-200 bg-slate-50 p-4"
                                            >
                                                <p className="font-semibold text-slate-900">{mentor.name}</p>
                                                <p className="text-sm text-slate-600">{mentor.title || mentor.domain || "Mentor"}</p>
                                                <p className="text-xs text-slate-500 mt-1">
                                                    {mentor.country || mentor.region || "Global"} - Rating {Number(mentor.rating).toFixed(1)}
                                                </p>
                                                <Link
                                                    href={route("dashboard.mentors.show", mentor.id)}
                                                    className="mt-3 inline-flex items-center text-sm font-semibold text-sky-700 hover:text-sky-800"
                                                >
                                                    View profile <ArrowRightIcon className="ml-1 h-4 w-4" />
                                                </Link>
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <p className="text-sm text-slate-600">No mentors are available right now. Please check again soon.</p>
                                )}
                            </div>

                            <div className={`rounded-2xl border p-6 shadow-sm ${mentorToneClasses[mentorCardState.tone]}`}>
                                <div className="flex items-start gap-3">
                                    <MentorStateIcon className="h-6 w-6 flex-shrink-0" />
                                    <div>
                                        <h2 className="text-xl font-bold">{mentorCardState.title}</h2>
                                        <p className="mt-2 text-sm opacity-90">{mentorCardState.message}</p>
                                    </div>
                                </div>

                                {mentorApplicationFeedback && (
                                    <p className="mt-3 rounded-lg bg-white/70 px-3 py-2 text-xs">
                                        Admin feedback: {mentorApplicationFeedback}
                                    </p>
                                )}

                                {mentorApplicationProcessedAt && (
                                    <p className="mt-2 text-xs opacity-80">
                                        Last reviewed: {mentorApplicationProcessedAt}
                                    </p>
                                )}

                                <Link
                                    href={mentorCardState.actionHref}
                                    className="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800"
                                >
                                    {mentorCardState.actionLabel}
                                </Link>
                            </div>
                        </div>

                        <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div className="mb-4 flex items-center gap-2">
                                <UserGroupIcon className="h-5 w-5 text-sky-700" />
                                <h2 className="text-xl font-bold text-slate-900">Subscribed Members You Can Connect With</h2>
                            </div>
                            {communityMembers.length > 0 ? (
                                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    {communityMembers.map((member) => (
                                        <div key={member.id} className="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                            <div className="flex items-center gap-3">
                                                <div className="flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 text-sm font-bold text-sky-800">
                                                    {initials(member.name)}
                                                </div>
                                                <div className="min-w-0">
                                                    <p className="truncate font-semibold text-slate-900">{member.name}</p>
                                                    <p className="truncate text-xs text-slate-500">{member.job_title || "Member"}</p>
                                                </div>
                                            </div>
                                            <p className="mt-2 text-xs text-slate-500">{member.country || "Global"}</p>
                                            <a
                                                href={`mailto:${member.email}`}
                                                className="mt-3 inline-flex items-center text-xs font-semibold text-sky-700 hover:text-sky-800"
                                            >
                                                Connect via email
                                            </a>
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <p className="text-sm text-slate-600">
                                    We will show subscribed members here as your community grows.
                                </p>
                            )}
                        </div>
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
