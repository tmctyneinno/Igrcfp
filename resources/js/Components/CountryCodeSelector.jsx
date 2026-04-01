// resources/js/Components/CountryCodeSelector.jsx

import React, { useState, useRef, useEffect } from 'react';

const COUNTRIES = [
    { code: '+234', flag: '🇳🇬', name: 'Nigeria',              short: 'NG' },
    { code: '+1',   flag: '🇺🇸', name: 'United States',        short: 'US' },
    { code: '+44',  flag: '🇬🇧', name: 'United Kingdom',       short: 'GB' },
    { code: '+1',   flag: '🇨🇦', name: 'Canada',               short: 'CA' },
    { code: '+27',  flag: '🇿🇦', name: 'South Africa',         short: 'ZA' },
    { code: '+233', flag: '🇬🇭', name: 'Ghana',                short: 'GH' },
    { code: '+254', flag: '🇰🇪', name: 'Kenya',                short: 'KE' },
    { code: '+256', flag: '🇺🇬', name: 'Uganda',               short: 'UG' },
    { code: '+255', flag: '🇹🇿', name: 'Tanzania',             short: 'TZ' },
    { code: '+251', flag: '🇪🇹', name: 'Ethiopia',             short: 'ET' },
    { code: '+20',  flag: '🇪🇬', name: 'Egypt',                short: 'EG' },
    { code: '+212', flag: '🇲🇦', name: 'Morocco',              short: 'MA' },
    { code: '+225', flag: '🇨🇮', name: "Côte d'Ivoire",        short: 'CI' },
    { code: '+221', flag: '🇸🇳', name: 'Senegal',              short: 'SN' },
    { code: '+237', flag: '🇨🇲', name: 'Cameroon',             short: 'CM' },
    { code: '+49',  flag: '🇩🇪', name: 'Germany',              short: 'DE' },
    { code: '+33',  flag: '🇫🇷', name: 'France',               short: 'FR' },
    { code: '+39',  flag: '🇮🇹', name: 'Italy',                short: 'IT' },
    { code: '+34',  flag: '🇪🇸', name: 'Spain',                short: 'ES' },
    { code: '+31',  flag: '🇳🇱', name: 'Netherlands',          short: 'NL' },
    { code: '+46',  flag: '🇸🇪', name: 'Sweden',               short: 'SE' },
    { code: '+47',  flag: '🇳🇴', name: 'Norway',               short: 'NO' },
    { code: '+45',  flag: '🇩🇰', name: 'Denmark',              short: 'DK' },
    { code: '+41',  flag: '🇨🇭', name: 'Switzerland',          short: 'CH' },
    { code: '+32',  flag: '🇧🇪', name: 'Belgium',              short: 'BE' },
    { code: '+91',  flag: '🇮🇳', name: 'India',                short: 'IN' },
    { code: '+86',  flag: '🇨🇳', name: 'China',                short: 'CN' },
    { code: '+81',  flag: '🇯🇵', name: 'Japan',                short: 'JP' },
    { code: '+82',  flag: '🇰🇷', name: 'South Korea',          short: 'KR' },
    { code: '+65',  flag: '🇸🇬', name: 'Singapore',            short: 'SG' },
    { code: '+971', flag: '🇦🇪', name: 'UAE',                  short: 'AE' },
    { code: '+966', flag: '🇸🇦', name: 'Saudi Arabia',         short: 'SA' },
    { code: '+974', flag: '🇶🇦', name: 'Qatar',                short: 'QA' },
    { code: '+55',  flag: '🇧🇷', name: 'Brazil',               short: 'BR' },
    { code: '+52',  flag: '🇲🇽', name: 'Mexico',               short: 'MX' },
    { code: '+54',  flag: '🇦🇷', name: 'Argentina',            short: 'AR' },
    { code: '+61',  flag: '🇦🇺', name: 'Australia',            short: 'AU' },
    { code: '+64',  flag: '🇳🇿', name: 'New Zealand',          short: 'NZ' },
];

export default function CountryCodeSelector({ value, onChange }) {
    const [open, setOpen]       = useState(false);
    const [search, setSearch]   = useState('');
    const dropdownRef           = useRef(null);
    const searchRef             = useRef(null);

    const selected = COUNTRIES.find(c => c.code === value && 
        (value !== '+1' || c.short === 'US')) || COUNTRIES[0];

    const filtered = COUNTRIES.filter(c =>
        c.name.toLowerCase().includes(search.toLowerCase()) ||
        c.code.includes(search) ||
        c.short.toLowerCase().includes(search.toLowerCase())
    );

    // Close on outside click
    useEffect(() => {
        const handler = (e) => {
            if (dropdownRef.current && !dropdownRef.current.contains(e.target)) {
                setOpen(false);
                setSearch('');
            }
        };
        document.addEventListener('mousedown', handler);
        return () => document.removeEventListener('mousedown', handler);
    }, []);

    // Focus search input when dropdown opens
    useEffect(() => {
        if (open && searchRef.current) {
            searchRef.current.focus();
        }
    }, [open]);

    const select = (country) => {
        onChange(country.code);
        setOpen(false);
        setSearch('');
    };

    return (
        <div className="relative" ref={dropdownRef}>
            {/* Trigger Button */}
            <button
                type="button"
                onClick={() => setOpen(!open)}
                className="flex items-center gap-1.5 px-3 h-full border border-gray-300 rounded-l-md bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors min-w-[90px]"
            >
                <span className="text-lg leading-none">{selected.flag}</span>
                <span className="text-sm font-medium text-gray-700">{selected.code}</span>
                <svg
                    className={`h-3.5 w-3.5 text-gray-500 transition-transform ${open ? 'rotate-180' : ''}`}
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                >
                    <path fillRule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clipRule="evenodd" />
                </svg>
            </button>

            {/* Dropdown */}
            {open && (
                <div className="absolute z-50 top-full left-0 mt-1 w-72 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">
                    {/* Search */}
                    <div className="p-2 border-b border-gray-100">
                        <div className="relative">
                            <svg className="absolute left-2.5 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fillRule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clipRule="evenodd" />
                            </svg>
                            <input
                                ref={searchRef}
                                type="text"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Search country..."
                                className="w-full pl-8 pr-3 py-1.5 text-sm border border-gray-200 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"
                            />
                        </div>
                    </div>

                    {/* Country List */}
                    <ul className="max-h-52 overflow-y-auto py-1">
                        {filtered.length > 0 ? (
                            filtered.map((country, i) => (
                                <li key={`${country.short}-${i}`}>
                                    <button
                                        type="button"
                                        onClick={() => select(country)}
                                        className={`w-full flex items-center gap-3 px-3 py-2 text-sm hover:bg-indigo-50 transition-colors ${
                                            selected.short === country.short ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700'
                                        }`}
                                    >
                                        <span className="text-base">{country.flag}</span>
                                        <span className="flex-1 text-left">{country.name}</span>
                                        <span className="text-gray-400 text-xs font-mono">{country.code}</span>
                                    </button>
                                </li>
                            ))
                        ) : (
                            <li className="px-3 py-4 text-sm text-gray-500 text-center">
                                No country found
                            </li>
                        )}
                    </ul>
                </div>
            )}
        </div>
    );
}