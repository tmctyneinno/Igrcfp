import React, { useState, useRef } from 'react';

export default function TranslateSelector({ pageLanguage = 'en' }) {
  const [targetLang, setTargetLang] = useState(pageLanguage);
  const [loading, setLoading] = useState(false);
  const snapshotRef = useRef(null); // [{ node, original }]

  const languages = [
    { code: 'en', label: 'English' },
    { code: 'zh-CN', label: 'China' },
    { code: 'es', label: 'Español' },
    { code: 'ar', label: 'العربية' },
    { code: 'fr', label: 'Français' },
  ];

  const snapshot = () => {
    const results = [];
    const walker = document.createTreeWalker(
      document.body,
      NodeFilter.SHOW_TEXT,
      {
        acceptNode: (node) => {
          const parent = node.parentElement;
          if (!parent) return NodeFilter.FILTER_REJECT;

          const tag = parent.tagName.toLowerCase();
          const text = node.nodeValue.trim();

          if (
            text.length < 2 ||
            text.length > 750 ||
            /^[\d\s\-.,:()/+%@#]+$/.test(text) ||
            ['script', 'style', 'noscript', 'iframe', 'code', 'pre'].includes(tag) ||
            parent.closest('.translate-selector') ||
            parent.closest('[data-no-translate]')
          ) {
            return NodeFilter.FILTER_REJECT;
          }
          return NodeFilter.FILTER_ACCEPT;
        }
      }
    );

    let node;
    while ((node = walker.nextNode())) {
      results.push({ node, original: node.nodeValue.trim() });
    }
    return results;
  };

  const chunk = (arr, size) => {
    const out = [];
    for (let i = 0; i < arr.length; i += size) out.push(arr.slice(i, i + size));
    return out;
  };

  const applyTranslation = async (entries, lang) => {
    // Smaller chunks = less likely to hit size limits
    const CHUNK_SIZE = 25;
    const chunks = chunk(entries, CHUNK_SIZE);

    for (const c of chunks) {
      const texts = c.map(e => e.original);

      let translated = null;

      // Try up to 2 times
      for (let attempt = 0; attempt < 2; attempt++) {
        try {
          const res = await fetch('/api/translate', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
            },
            body: JSON.stringify({
              texts,
              source: pageLanguage,
              target: lang,
            })
          });

          if (res.ok) {
            const data = await res.json();
            if (data.success && Array.isArray(data.translated)) {
              translated = data.translated;
              break;
            }
          } else {
            const errData = await res.json().catch(() => ({}));
            console.warn(`Translate attempt ${attempt + 1} failed:`, res.status, errData);
            // Wait before retry
            await new Promise(r => setTimeout(r, 600));
          }
        } catch (err) {
          console.warn(`Translate attempt ${attempt + 1} error:`, err.message);
          await new Promise(r => setTimeout(r, 600));
        }
      }

      if (translated) {
        c.forEach(({ node }, i) => {
          if (translated[i] && translated[i] !== texts[i]) {
            node.nodeValue = translated[i];
          }
        });
      }
      // If both attempts fail, leave text as-is (page stays readable)

      // Small delay between chunks to avoid rate limiting
      await new Promise(r => setTimeout(r, 150));
    }
  };

  const translateEntirePage = async (newLang) => {
    if (newLang === targetLang || loading) return;

    setLoading(true);

    try {
      // Take snapshot on first translation
      if (!snapshotRef.current) {
        snapshotRef.current = snapshot();
      }

      const entries = snapshotRef.current;

      // Restore originals when switching back to base language
      if (newLang === pageLanguage) {
        entries.forEach(({ node, original }) => {
          node.nodeValue = original;
        });
        setTargetLang(newLang);
        return;
      }

      // Always restore originals first before re-translating
      entries.forEach(({ node, original }) => {
        node.nodeValue = original;
      });

      await applyTranslation(entries, newLang);
      setTargetLang(newLang);

    } catch (err) {
      console.error('Page translation error:', err);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="relative group translate-selector">
      <button
        disabled={loading}
        className="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-black hover:text-blue-900 hover:bg-blue-50 rounded-lg transition duration-200 disabled:opacity-60"
        aria-label="Select language"
      >
        <svg xmlns="http://www.w3.org/2000/svg" className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
        </svg>
        <span className="hidden xl:inline">
          {loading ? 'Translating...' : 'Translate'}
        </span>
        <span className="text-xs text-gray-500">
          ({languages.find(l => l.code === targetLang)?.label ?? targetLang})
        </span>
        <svg className="w-3 h-3 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      <div className="absolute right-0 mt-1 w-44 bg-white rounded-lg shadow-lg border border-gray-100 py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 max-h-64 overflow-y-auto">
        {languages.map((lang) => (
          <button
            key={lang.code}
            onClick={() => translateEntirePage(lang.code)}
            disabled={loading}
            className={`w-full text-left px-4 py-2 text-sm transition duration-150 disabled:opacity-50 ${
              targetLang === lang.code
                ? 'bg-blue-50 text-blue-900 font-medium'
                : 'text-gray-700 hover:bg-blue-50 hover:text-blue-900'
            }`}
          >
            {lang.label}
          </button>
        ))}
      </div>
    </div>
  );
}