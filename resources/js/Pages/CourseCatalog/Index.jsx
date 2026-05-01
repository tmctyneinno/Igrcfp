import { Link } from "@inertiajs/react";
import React, { useState, useMemo } from "react";

const categories = [
    { id: "all", label: "All Courses", count: 62 },
    { id: "grc", label: "Core GRC & Governance", count: 10 },
    { id: "financial-crime", label: "Financial Crime & AML", count: 12 },
    { id: "cyber", label: "Cybersecurity & Digital Risk", count: 10 },
    { id: "data", label: "Data, Privacy & Technology", count: 8 },
    { id: "audit", label: "Audit, Control & Assurance", count: 8 },
    { id: "esg", label: "ESG, Ethics & Sustainability", count: 6 },
    { id: "specialist", label: "Specialist & Emerging Risk", count: 8 },
];

const courses = [
    // Core GRC & Governance
    { id: 1, cat: "grc", title: "Certificate in Governance, Risk & Compliance (GRC)", pathway: ["Foundation"] },
    { id: 2, cat: "grc", title: "Certificate in Corporate Governance", pathway: [] },
    { id: 3, cat: "grc", title: "Certificate in Board Governance & Oversight", pathway: ["Leadership"] },
    { id: 4, cat: "grc", title: "Certificate in Enterprise Risk Management (ERM)", pathway: ["Leadership"] },
    { id: 5, cat: "grc", title: "Certificate in Operational Risk Management", pathway: [] },
    { id: 6, cat: "grc", title: "Certificate in Strategic Risk & Decision Making", pathway: [] },
    { id: 7, cat: "grc", title: "Certificate in Compliance & Regulatory Frameworks", pathway: ["Foundation"] },
    { id: 8, cat: "grc", title: "Certificate in Regulatory Compliance (Global Frameworks)", pathway: [] },
    { id: 9, cat: "grc", title: "Certificate in Ethics, Conduct & Culture", pathway: [] },
    { id: 10, cat: "grc", title: "Certificate in Integrated GRC Frameworks", pathway: ["Leadership"] },

    // Financial Crime & AML
    { id: 11, cat: "financial-crime", title: "Certificate in Financial Crime Prevention", pathway: ["Foundation"] },
    { id: 12, cat: "financial-crime", title: "Certificate in Anti-Money Laundering (AML & CFT)", pathway: ["Specialist"] },
    { id: 13, cat: "financial-crime", title: "Certificate in Know Your Customer (KYC & CDD)", pathway: [] },
    { id: 14, cat: "financial-crime", title: "Certificate in Sanctions & Financial Crime Compliance", pathway: [] },
    { id: 15, cat: "financial-crime", title: "Certificate in Transaction Monitoring & Suspicious Activity Reporting", pathway: [] },
    { id: 16, cat: "financial-crime", title: "Certificate in Fraud Risk Management", pathway: [] },
    { id: 17, cat: "financial-crime", title: "Certificate in Anti-Bribery & Corruption", pathway: [] },
    { id: 18, cat: "financial-crime", title: "Certificate in Counter Terrorist Financing (CTF)", pathway: [] },
    { id: 19, cat: "financial-crime", title: "Certificate in Financial Intelligence & Investigations", pathway: [] },
    { id: 20, cat: "financial-crime", title: "Certificate in Trade-Based Money Laundering (TBML)", pathway: ["Specialist"] },
    { id: 21, cat: "financial-crime", title: "Certificate in Politically Exposed Persons (PEPs) Risk Management", pathway: [] },
    { id: 22, cat: "financial-crime", title: "Certificate in Financial Crime Risk Assessment", pathway: [] },

    // Cybersecurity & Digital Risk
    { id: 23, cat: "cyber", title: "Certificate in Cybersecurity & Digital Risk", pathway: ["Specialist"] },
    { id: 24, cat: "cyber", title: "Certificate in Information Security Management", pathway: [] },
    { id: 25, cat: "cyber", title: "Certificate in Cyber Risk Governance", pathway: [] },
    { id: 26, cat: "cyber", title: "Certificate in Cyber Threat Intelligence", pathway: [] },
    { id: 27, cat: "cyber", title: "Certificate in Incident Response & Cyber Crisis Management", pathway: [] },
    { id: 28, cat: "cyber", title: "Certificate in Digital Forensics & Investigation", pathway: [] },
    { id: 29, cat: "cyber", title: "Certificate in Cloud Security & Risk", pathway: [] },
    { id: 30, cat: "cyber", title: "Certificate in Network Security Fundamentals", pathway: [] },
    { id: 31, cat: "cyber", title: "Certificate in Cybersecurity for Financial Institutions", pathway: [] },
    { id: 32, cat: "cyber", title: "Certificate in Operational Resilience & Cyber Risk", pathway: ["Leadership"] },

    // Data, Privacy & Technology
    { id: 33, cat: "data", title: "Certificate in Data Protection & Privacy (GDPR)", pathway: ["Foundation"] },
    { id: 34, cat: "data", title: "Certificate in Data Governance & Data Risk", pathway: [] },
    { id: 35, cat: "data", title: "Certificate in Information Governance", pathway: [] },
    { id: 36, cat: "data", title: "Certificate in Data Ethics & Responsible AI", pathway: [] },
    { id: 37, cat: "data", title: "Certificate in Artificial Intelligence & Digital Compliance", pathway: [] },
    { id: 38, cat: "data", title: "Certificate in RegTech & SupTech", pathway: [] },
    { id: 39, cat: "data", title: "Certificate in Blockchain & Cryptocurrency Risk", pathway: ["Specialist"] },
    { id: 40, cat: "data", title: "Certificate in Digital Identity & Verification", pathway: [] },

    // Audit, Control & Assurance
    { id: 41, cat: "audit", title: "Certificate in Internal Audit & Assurance", pathway: [] },
    { id: 42, cat: "audit", title: "Certificate in Risk-Based Internal Audit", pathway: [] },
    { id: 43, cat: "audit", title: "Certificate in Compliance Monitoring & Testing", pathway: [] },
    { id: 44, cat: "audit", title: "Certificate in Controls & Assurance Frameworks", pathway: [] },
    { id: 45, cat: "audit", title: "Certificate in Combined Assurance & Three Lines Model", pathway: [] },
    { id: 46, cat: "audit", title: "Certificate in Audit Analytics & Data-Driven Assurance", pathway: [] },
    { id: 47, cat: "audit", title: "Certificate in Investigations & Case Management", pathway: [] },
    { id: 48, cat: "audit", title: "Certificate in Forensic Audit & Fraud Investigation", pathway: [] },

    // ESG, Ethics & Sustainability
    { id: 49, cat: "esg", title: "Certificate in ESG (Environmental, Social & Governance)", pathway: [] },
    { id: 50, cat: "esg", title: "Certificate in Sustainability & Risk Management", pathway: [] },
    { id: 51, cat: "esg", title: "Certificate in Climate Risk & ESG Reporting", pathway: [] },
    { id: 52, cat: "esg", title: "Certificate in Corporate Social Responsibility (CSR)", pathway: [] },
    { id: 53, cat: "esg", title: "Certificate in Ethical Leadership & Governance", pathway: [] },
    { id: 54, cat: "esg", title: "Certificate in Sustainable Finance", pathway: [] },

    // Specialist & Emerging Risk
    { id: 55, cat: "specialist", title: "Certificate in FinTech Risk & Compliance", pathway: [] },
    { id: 56, cat: "specialist", title: "Certificate in Digital Banking & Financial Crime", pathway: [] },
    { id: 57, cat: "specialist", title: "Certificate in Payments Fraud & Risk", pathway: [] },
    { id: 58, cat: "specialist", title: "Certificate in Crypto Compliance & AML", pathway: [] },
    { id: 59, cat: "specialist", title: "Certificate in Supply Chain Risk & Compliance", pathway: [] },
    { id: 60, cat: "specialist", title: "Certificate in Third-Party Risk Management", pathway: [] },
    { id: 61, cat: "specialist", title: "Certificate in Operational Resilience (Advanced)", pathway: [] },
    { id: 62, cat: "specialist", title: "Certificate in Global Risk & Regulatory Landscape", pathway: [] },
];

const catMeta = {
    grc:              { color: "#3B82F6", bg: "rgba(59,130,246,0.08)",  label: "GRC" },
    "financial-crime":{ color: "#EF4444", bg: "rgba(239,68,68,0.08)",   label: "Fin Crime" },
    cyber:            { color: "#8B5CF6", bg: "rgba(139,92,246,0.08)",  label: "Cyber" },
    data:             { color: "#06B6D4", bg: "rgba(6,182,212,0.08)",   label: "Data & Tech" },
    audit:            { color: "#F59E0B", bg: "rgba(245,158,11,0.08)",  label: "Audit" },
    esg:              { color: "#10B981", bg: "rgba(16,185,129,0.08)",  label: "ESG" },
    specialist:       { color: "#F97316", bg: "rgba(249,115,22,0.08)",  label: "Specialist" },
};

const pathwayColors = {
    Foundation: { bg: "#EFF6FF", text: "#1D4ED8", border: "#BFDBFE" },
    Specialist:  { bg: "#F5F3FF", text: "#6D28D9", border: "#DDD6FE" },
    Leadership:  { bg: "#FFF7ED", text: "#C2410C", border: "#FDBA74" },
};

export default function CourseCatalogue({ auth }) {
    const [activeCategory, setActiveCategory] = useState("all");
    const [searchQuery, setSearchQuery]         = useState("");
    const [activePathway, setActivePathway]     = useState("all");
    const [viewMode, setViewMode]               = useState("grid"); // grid | list

    const filtered = useMemo(() => {
        return courses.filter(c => {
            const matchesCat     = activeCategory === "all" || c.cat === activeCategory;
            const matchesSearch  = c.title.toLowerCase().includes(searchQuery.toLowerCase());
            const matchesPathway = activePathway === "all" || c.pathway.includes(activePathway);
            return matchesCat && matchesSearch && matchesPathway;
        });
    }, [activeCategory, searchQuery, activePathway]);

    return (
        <>
            <style>{`
                @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap');

                .catalogue-root {
                    --navy: #0B1B3E;
                    --navy-mid: #162850;
                    --gold: #C8933A;
                    --gold-light: #E8B96A;
                    --cream: #FAF7F2;
                    --ink: #1A1A2E;
                    font-family: 'DM Sans', sans-serif;
                    background: var(--cream);
                    min-height: 100vh;
                }

                .catalogue-hero {
                    background: linear-gradient(135deg, var(--navy) 0%, #1A2F5E 60%, #0D2244 100%);
                    position: relative;
                    overflow: hidden;
                    padding: 80px 0 60px;
                }
                .catalogue-hero::before {
                    content: '';
                    position: absolute;
                    inset: 0;
                    background-image: radial-gradient(circle at 20% 50%, rgba(200,147,58,0.12) 0%, transparent 50%),
                                      radial-gradient(circle at 80% 20%, rgba(200,147,58,0.08) 0%, transparent 40%);
                }
                .catalogue-hero::after {
                    content: '';
                    position: absolute;
                    bottom: 0; left: 0; right: 0;
                    height: 2px;
                    background: linear-gradient(90deg, transparent, var(--gold), transparent);
                }
                .hero-grid-lines {
                    position: absolute;
                    inset: 0;
                    background-image:
                        linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
                    background-size: 60px 60px;
                }

                .hero-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    background: rgba(200,147,58,0.15);
                    border: 1px solid rgba(200,147,58,0.35);
                    color: var(--gold-light);
                    padding: 6px 16px;
                    border-radius: 100px;
                    font-size: 12px;
                    font-weight: 600;
                    letter-spacing: 0.08em;
                    text-transform: uppercase;
                    margin-bottom: 20px;
                }
                .hero-title {
                    font-family: 'Playfair Display', Georgia, serif;
                    font-size: clamp(2.2rem, 5vw, 3.5rem);
                    font-weight: 700;
                    color: #fff;
                    line-height: 1.15;
                    margin: 0 0 16px;
                }
                .hero-title span {
                    color: var(--gold-light);
                }
                .hero-subtitle {
                    color: rgba(255,255,255,0.65);
                    font-size: 1.05rem;
                    font-weight: 300;
                    max-width: 560px;
                    line-height: 1.7;
                    margin: 0 0 36px;
                }
                .hero-stats {
                    display: flex;
                    gap: 36px;
                    flex-wrap: wrap;
                }
                .hero-stat {
                    display: flex;
                    flex-direction: column;
                }
                .hero-stat-num {
                    font-family: 'Playfair Display', serif;
                    font-size: 2rem;
                    font-weight: 700;
                    color: var(--gold-light);
                    line-height: 1;
                }
                .hero-stat-label {
                    font-size: 0.75rem;
                    color: rgba(255,255,255,0.5);
                    text-transform: uppercase;
                    letter-spacing: 0.07em;
                    margin-top: 4px;
                }

                /* Search & Filters */
                .controls-bar {
                    background: #fff;
                    border-bottom: 1px solid #E8E4DE;
                    position: sticky;
                    top: 0;
                    z-index: 40;
                    box-shadow: 0 2px 12px rgba(11,27,62,0.06);
                }
                .search-wrap {
                    position: relative;
                }
                .search-input {
                    width: 100%;
                    padding: 12px 16px 12px 44px;
                    border: 1.5px solid #E8E4DE;
                    border-radius: 10px;
                    font-family: 'DM Sans', sans-serif;
                    font-size: 0.95rem;
                    color: var(--ink);
                    background: #FAFAFA;
                    outline: none;
                    transition: border-color 0.2s, box-shadow 0.2s;
                }
                .search-input:focus {
                    border-color: var(--gold);
                    box-shadow: 0 0 0 3px rgba(200,147,58,0.12);
                    background: #fff;
                }
                .search-icon {
                    position: absolute;
                    left: 14px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #9CA3AF;
                }

                /* Category Tabs */
                .cat-tabs {
                    display: flex;
                    gap: 6px;
                    overflow-x: auto;
                    scrollbar-width: none;
                    padding-bottom: 2px;
                }
                .cat-tabs::-webkit-scrollbar { display: none; }
                .cat-tab {
                    white-space: nowrap;
                    padding: 8px 16px;
                    border-radius: 8px;
                    font-size: 0.85rem;
                    font-weight: 500;
                    cursor: pointer;
                    border: 1.5px solid transparent;
                    transition: all 0.18s;
                    background: transparent;
                    color: #6B7280;
                    font-family: 'DM Sans', sans-serif;
                }
                .cat-tab:hover { background: #F3F4F6; color: var(--ink); }
                .cat-tab.active {
                    background: var(--navy);
                    color: #fff;
                    border-color: var(--navy);
                }

                /* Pathway Filter Pills */
                .pathway-pill {
                    padding: 5px 14px;
                    border-radius: 100px;
                    font-size: 0.8rem;
                    font-weight: 500;
                    cursor: pointer;
                    border: 1.5px solid #E5E7EB;
                    background: transparent;
                    color: #6B7280;
                    font-family: 'DM Sans', sans-serif;
                    transition: all 0.18s;
                }
                .pathway-pill:hover { border-color: var(--gold); color: var(--gold); }
                .pathway-pill.active {
                    background: var(--gold);
                    border-color: var(--gold);
                    color: #fff;
                }

                /* View Toggle */
                .view-toggle button {
                    padding: 7px 10px;
                    border: 1.5px solid #E5E7EB;
                    background: transparent;
                    cursor: pointer;
                    color: #9CA3AF;
                    transition: all 0.15s;
                }
                .view-toggle button:first-child { border-radius: 8px 0 0 8px; border-right: none; }
                .view-toggle button:last-child  { border-radius: 0 8px 8px 0; }
                .view-toggle button.active { background: var(--navy); border-color: var(--navy); color: #fff; }

                /* Course Grid */
                .courses-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                    gap: 20px;
                }
                .course-card {
                    background: #fff;
                    border: 1px solid #EDE9E3;
                    border-radius: 14px;
                    padding: 24px;
                    position: relative;
                    overflow: hidden;
                    transition: transform 0.22s, box-shadow 0.22s, border-color 0.22s;
                    cursor: pointer;
                    display: flex;
                    flex-direction: column;
                    gap: 14px;
                }
                .course-card:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 12px 36px rgba(11,27,62,0.1);
                    border-color: rgba(200,147,58,0.3);
                }
                .course-card::before {
                    content: '';
                    position: absolute;
                    top: 0; left: 0; right: 0;
                    height: 3px;
                    background: var(--card-accent, #3B82F6);
                    opacity: 0;
                    transition: opacity 0.22s;
                }
                .course-card:hover::before { opacity: 1; }

                .course-num {
                    font-family: 'Playfair Display', serif;
                    font-size: 2rem;
                    font-weight: 700;
                    color: #EDE9E3;
                    line-height: 1;
                    position: absolute;
                    top: 16px;
                    right: 20px;
                    transition: color 0.22s;
                }
                .course-card:hover .course-num { color: rgba(200,147,58,0.2); }

                .course-cat-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 4px 10px;
                    border-radius: 6px;
                    font-size: 0.72rem;
                    font-weight: 600;
                    letter-spacing: 0.04em;
                    text-transform: uppercase;
                    width: fit-content;
                }
                .course-title {
                    font-family: 'DM Sans', sans-serif;
                    font-size: 0.95rem;
                    font-weight: 600;
                    color: var(--ink);
                    line-height: 1.45;
                    flex: 1;
                }
                .course-footer {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-top: auto;
                    padding-top: 12px;
                    border-top: 1px solid #F3F0EB;
                }
                .pathway-tag {
                    display: inline-flex;
                    align-items: center;
                    gap: 4px;
                    padding: 3px 10px;
                    border-radius: 100px;
                    font-size: 0.72rem;
                    font-weight: 600;
                    border: 1px solid;
                }
                .enrol-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    color: var(--navy);
                    font-size: 0.82rem;
                    font-weight: 600;
                    text-decoration: none;
                    transition: color 0.15s, gap 0.15s;
                }
                .course-card:hover .enrol-btn { color: var(--gold); gap: 10px; }

                /* List View */
                .courses-list {
                    display: flex;
                    flex-direction: column;
                    gap: 0;
                    border: 1px solid #EDE9E3;
                    border-radius: 14px;
                    overflow: hidden;
                    background: #fff;
                }
                .list-item {
                    display: flex;
                    align-items: center;
                    gap: 20px;
                    padding: 18px 24px;
                    border-bottom: 1px solid #F3F0EB;
                    transition: background 0.15s;
                    cursor: pointer;
                }
                .list-item:last-child { border-bottom: none; }
                .list-item:hover { background: #FAF7F2; }
                .list-num {
                    font-family: 'Playfair Display', serif;
                    font-size: 1rem;
                    color: #C9C5BE;
                    min-width: 32px;
                    font-weight: 700;
                }
                .list-title {
                    flex: 1;
                    font-size: 0.92rem;
                    font-weight: 500;
                    color: var(--ink);
                }
                .list-actions {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }

                /* Results count */
                .results-bar {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }
                .results-count {
                    font-size: 0.88rem;
                    color: #6B7280;
                }
                .results-count strong {
                    color: var(--ink);
                    font-weight: 600;
                }

                /* Empty State */
                .empty-state {
                    text-align: center;
                    padding: 80px 20px;
                    color: #9CA3AF;
                }
                .empty-state svg { margin: 0 auto 16px; display: block; }

                /* Delivery badges */
                .delivery-strip {
                    background: var(--navy);
                    color: rgba(255,255,255,0.7);
                    font-size: 0.82rem;
                    text-align: center;
                    padding: 10px;
                    letter-spacing: 0.03em;
                }
                .delivery-strip span {
                    color: var(--gold-light);
                    font-weight: 600;
                }

                @media (max-width: 640px) {
                    .courses-grid { grid-template-columns: 1fr; }
                    .hero-stats { gap: 20px; }
                    .list-item { flex-wrap: wrap; }
                }
            `}</style>

            <div className="catalogue-root">

                {/* Delivery Strip */}
                <div className="delivery-strip">
                    Available via <span>self-paced online</span> · <span>live virtual</span> · <span>in-person workshops</span> · <span>corporate in-house</span>
                </div>

                {/* Hero */}
                <section className="catalogue-hero">
                    <div className="hero-grid-lines" />
                    <div style={{ maxWidth: 1200, margin: "0 auto", padding: "0 24px", position: "relative" }}>
                        <div className="hero-badge">
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                <circle cx="6" cy="6" r="5" stroke="currentColor" strokeWidth="1.5"/>
                                <path d="M4 6l1.5 1.5L8 4" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                            </svg>
                            IGRCFP · Professional Certificate Portfolio
                        </div>
                        <h1 className="hero-title">
                            Course <span>Catalogue</span>
                        </h1>
                        <p className="hero-subtitle">
                            Specialist programmes in Governance, Risk, Compliance, Financial Crime Prevention, Cybersecurity, and Emerging Regulatory Environments — built for professionals in complex, regulated settings.
                        </p>
                        <div className="hero-stats">
                            <div className="hero-stat">
                                <span className="hero-stat-num">62</span>
                                <span className="hero-stat-label">Specialist Courses</span>
                            </div>
                            <div className="hero-stat">
                                <span className="hero-stat-num">7</span>
                                <span className="hero-stat-label">Subject Areas</span>
                            </div>
                            <div className="hero-stat">
                                <span className="hero-stat-num">3</span>
                                <span className="hero-stat-label">Learning Pathways</span>
                            </div>
                            <div className="hero-stat">
                                <span className="hero-stat-num">5</span>
                                <span className="hero-stat-label">Delivery Modes</span>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Sticky Controls */}
                <div className="controls-bar">
                    <div style={{ maxWidth: 1200, margin: "0 auto", padding: "14px 24px" }}>
                        {/* Row 1: Search + View Toggle */}
                        <div style={{ display: "flex", gap: 12, alignItems: "center", marginBottom: 12 }}>
                            <div className="search-wrap" style={{ flex: 1 }}>
                                <svg className="search-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35" strokeLinecap="round"/>
                                </svg>
                                <input
                                    className="search-input"
                                    type="text"
                                    placeholder="Search courses…"
                                    value={searchQuery}
                                    onChange={e => setSearchQuery(e.target.value)}
                                />
                            </div>
                            <div className="view-toggle" style={{ display: "flex" }}>
                                <button className={viewMode === "grid" ? "active" : ""} onClick={() => setViewMode("grid")} title="Grid view">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                                        <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                                    </svg>
                                </button>
                                <button className={viewMode === "list" ? "active" : ""} onClick={() => setViewMode("list")} title="List view">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" strokeLinecap="round"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {/* Row 2: Category Tabs */}
                        <div className="cat-tabs">
                            {categories.map(cat => (
                                <button
                                    key={cat.id}
                                    className={`cat-tab ${activeCategory === cat.id ? "active" : ""}`}
                                    onClick={() => setActiveCategory(cat.id)}
                                >
                                    {cat.label}
                                    <span style={{ marginLeft: 6, opacity: 0.65, fontSize: "0.78rem" }}>({cat.count})</span>
                                </button>
                            ))}
                        </div>

                        {/* Row 3: Pathway Filters */}
                        <div style={{ display: "flex", gap: 8, alignItems: "center", marginTop: 10, flexWrap: "wrap" }}>
                            <span style={{ fontSize: "0.78rem", color: "#9CA3AF", fontWeight: 500, textTransform: "uppercase", letterSpacing: "0.07em" }}>Pathway:</span>
                            {["all", "Foundation", "Specialist", "Leadership"].map(p => (
                                <button
                                    key={p}
                                    className={`pathway-pill ${activePathway === p ? "active" : ""}`}
                                    onClick={() => setActivePathway(p)}
                                >
                                    {p === "all" ? "All Pathways" : p}
                                </button>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Main Content */}
                <div style={{ maxWidth: 1200, margin: "0 auto", padding: "36px 24px 80px" }}>
                    <div className="results-bar">
                        <p className="results-count">
                            Showing <strong>{filtered.length}</strong> of <strong>62</strong> courses
                            {searchQuery && <> matching <strong>"{searchQuery}"</strong></>}
                        </p>
                        {(searchQuery || activeCategory !== "all" || activePathway !== "all") && (
                            <button
                                onClick={() => { setSearchQuery(""); setActiveCategory("all"); setActivePathway("all"); }}
                                style={{ fontSize: "0.82rem", color: "#EF4444", fontWeight: 500, background: "none", border: "none", cursor: "pointer", fontFamily: "DM Sans, sans-serif" }}
                            >
                                Clear filters ×
                            </button>
                        )}
                    </div>

                    {filtered.length === 0 ? (
                        <div className="empty-state">
                            <svg width="48" height="48" fill="none" stroke="#D1D5DB" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35" strokeLinecap="round" strokeWidth="1.5"/>
                            </svg>
                            <p style={{ fontSize: "1rem", fontWeight: 500 }}>No courses found</p>
                            <p style={{ fontSize: "0.88rem", marginTop: 6 }}>Try adjusting your filters or search query.</p>
                        </div>
                    ) : viewMode === "grid" ? (
                        <div className="courses-grid">
                            {filtered.map(course => {
                                const meta = catMeta[course.cat];
                                return (
                                    <div
                                        key={course.id}
                                        className="course-card"
                                        style={{ "--card-accent": meta.color }}
                                    >
                                        <span className="course-num">{String(course.id).padStart(2, "0")}</span>
                                        <div
                                            className="course-cat-badge"
                                            style={{ background: meta.bg, color: meta.color }}
                                        >
                                            <span style={{ width: 6, height: 6, borderRadius: "50%", background: meta.color, display: "inline-block" }}></span>
                                            {meta.label}
                                        </div>
                                        <p className="course-title">{course.title}</p>
                                        <div className="course-footer">
                                            <div style={{ display: "flex", gap: 6, flexWrap: "wrap" }}>
                                                {course.pathway.length > 0 ? course.pathway.map(p => (
                                                    <span
                                                        key={p}
                                                        className="pathway-tag"
                                                        style={{ background: pathwayColors[p].bg, color: pathwayColors[p].text, borderColor: pathwayColors[p].border }}
                                                    >
                                                        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path d="M9 12l2 2 4-4" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5"/>
                                                        </svg>
                                                        {p}
                                                    </span>
                                                )) : <span style={{ fontSize: "0.75rem", color: "#C9C5BE" }}>Open Enrolment</span>}
                                            </div>
                                            <a href="#enrol" className="enrol-btn">
                                                Enrol
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M5 12h14M12 5l7 7-7 7" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    ) : (
                        <div className="courses-list">
                            {filtered.map(course => {
                                const meta = catMeta[course.cat];
                                return (
                                    <div key={course.id} className="list-item">
                                        <span className="list-num">{String(course.id).padStart(2, "0")}</span>
                                        <div style={{ width: 10, height: 10, borderRadius: "50%", background: meta.color, flexShrink: 0 }}></div>
                                        <span className="list-title">{course.title}</span>
                                        <div className="list-actions">
                                            {course.pathway.map(p => (
                                                <span
                                                    key={p}
                                                    className="pathway-tag"
                                                    style={{ background: pathwayColors[p].bg, color: pathwayColors[p].text, borderColor: pathwayColors[p].border }}
                                                >
                                                    {p}
                                                </span>
                                            ))}
                                            <span
                                                className="course-cat-badge"
                                                style={{ background: meta.bg, color: meta.color, fontSize: "0.7rem" }}
                                            >
                                                {meta.label}
                                            </span>
                                            <a href="#enrol" className="enrol-btn" style={{ fontSize: "0.8rem" }}>
                                                Enrol →
                                            </a>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    )}

                    {/* Enrolment CTA */}
                    <div id="enrol" style={{
                        marginTop: 64,
                        background: "linear-gradient(135deg, #0B1B3E 0%, #162850 100%)",
                        borderRadius: 20,
                        padding: "48px 40px",
                        display: "flex",
                        alignItems: "center",
                        justifyContent: "space-between",
                        gap: 32,
                        flexWrap: "wrap",
                        position: "relative",
                        overflow: "hidden"
                    }}>
                        <div style={{
                            position: "absolute", inset: 0,
                            background: "radial-gradient(circle at 90% 50%, rgba(200,147,58,0.15) 0%, transparent 60%)"
                        }}/>
                        <div style={{ position: "relative" }}>
                            <p style={{ fontFamily: "'Playfair Display', serif", fontSize: "1.6rem", color: "#fff", fontWeight: 700, margin: "0 0 8px" }}>
                                Ready to Enrol?
                            </p>
                            <p style={{ color: "rgba(255,255,255,0.6)", fontSize: "0.95rem", margin: 0, maxWidth: 440 }}>
                                Contact our training team to register for a course, discuss corporate training packages, or explore partnership opportunities.
                            </p>
                        </div>
                        <div style={{ display: "flex", gap: 12, flexWrap: "wrap", position: "relative" }}>
                            <a
                                href="mailto:training@igrcfp.org"
                                style={{
                                    background: "#C8933A", color: "#fff",
                                    padding: "12px 28px", borderRadius: 10,
                                    fontWeight: 600, fontSize: "0.92rem",
                                    textDecoration: "none", display: "inline-flex",
                                    alignItems: "center", gap: 8,
                                    fontFamily: "'DM Sans', sans-serif",
                                    transition: "background 0.2s"
                                }}
                            >
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8"/>
                                </svg>
                                training@igrcfp.org
                            </a>
                            <a
                                href="https://www.igrcfp.org"
                                target="_blank"
                                rel="noreferrer"
                                style={{
                                    background: "rgba(255,255,255,0.1)", color: "#fff",
                                    padding: "12px 28px", borderRadius: 10,
                                    fontWeight: 600, fontSize: "0.92rem",
                                    textDecoration: "none", display: "inline-flex",
                                    alignItems: "center", gap: 8,
                                    border: "1px solid rgba(255,255,255,0.2)",
                                    fontFamily: "'DM Sans', sans-serif"
                                }}
                            >
                                Visit Website →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}