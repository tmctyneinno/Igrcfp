// resources/js/Components/SearchBar.jsx

import React, { useState, useEffect, useRef } from 'react';

export default function SearchBar({ value, onChange, placeholder }) {
    const [localValue, setLocalValue] = useState(value);
    const inputRef = useRef(null);

    useEffect(() => {
        setLocalValue(value);
    }, [value]);

    const handleChange = (e) => {
        const newValue = e.target.value;
        setLocalValue(newValue);
        onChange(newValue);
    };

    const handleClear = () => {
        setLocalValue('');
        onChange('');
        inputRef.current?.focus();
    };

    return (
        <div className="relative">
            <input
                ref={inputRef}
                type="text"
                value={localValue}
                onChange={handleChange}
                placeholder={placeholder}
                className="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
            <svg
                className="absolute left-3 top-2.5 h-5 w-5 text-gray-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                />
            </svg>
            {localValue && (
                <button
                    onClick={handleClear}
                    className="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600"
                >
                    <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            )}
        </div>
    );
}