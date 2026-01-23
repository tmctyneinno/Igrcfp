import React from 'react';
import { BookOpenIcon, ClockIcon, BookmarkIcon, DocumentTextIcon } from '@heroicons/react/24/outline';
import { Link } from '@inertiajs/react';

const FormattedContent = ({ 
  content, 
  title, 
  showBreadcrumb = false,
  showReadingTime = false,
  showActions = false,
  className = ''
}) => {
  
  // Calculate reading time (200 words per minute average)
  const calculateReadingTime = (text) => {
    if (!text) return 0;
    const wordCount = text.split(/\s+/).length;
    return Math.ceil(wordCount / 200);
  };

  // Extract headings for table of contents
  const extractHeadings = (html) => {
    if (!html) return [];
    const regex = /<h[2-3][^>]*>(.*?)<\/h[2-3]>/gi;
    const matches = [];
    let match;
    while ((match = regex.exec(html)) !== null) {
      const text = match[1].replace(/<[^>]*>/g, '');
      const id = text.toLowerCase().replace(/[^a-z0-9]+/g, '-');
      matches.push({ text, id, level: match[0].substring(2, 3) });
    }
    return matches;
  };

  const headings = extractHeadings(content);
  const readingTime = calculateReadingTime(content?.replace(/<[^>]*>/g, ''));

  return (
    <div className={`relative ${className}`}>
      {/* Breadcrumb Navigation */}
      {showBreadcrumb && (
        <nav className="mb-6 text-sm text-gray-500">
          <Link href="/courses" className="hover:text-blue-600 transition">Courses</Link>
          <span className="mx-2">/</span>
          <span className="text-gray-900 font-medium">{title}</span>
        </nav>
      )}

      {/* Reading Time & Actions */}
      {showReadingTime && (
        <div className="mb-8 p-4 bg-blue-50 rounded-xl border border-blue-100">
          <div className="flex items-center justify-between">
            <div className="flex items-center">
              <ClockIcon className="h-5 w-5 text-blue-600 mr-2" />
              <span className="text-gray-700">
                Estimated reading time: {readingTime} minute{readingTime !== 1 ? 's' : ''}
              </span>
            </div>
            {showActions && (
              <div className="flex items-center gap-4">
                <button className="text-sm text-blue-600 hover:text-blue-800 font-medium transition">
                  <BookmarkIcon className="h-5 w-5 inline mr-1" />
                  Save
                </button>
                <button 
                  onClick={() => window.print()}
                  className="text-sm text-blue-600 hover:text-blue-800 font-medium transition"
                >
                  <DocumentTextIcon className="h-5 w-5 inline mr-1" />
                  Print
                </button>
              </div>
            )}
          </div>
        </div>
      )}

      {/* Table of Contents */}
      {headings.length > 0 && (
        <div className="mb-8 bg-gray-50 rounded-xl border border-gray-200 p-6">
          <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <BookOpenIcon className="h-5 w-5 mr-2 text-blue-600" />
            Table of Contents
          </h3>
          <nav className="space-y-2">
            {headings.map((heading, index) => (
              <a
                key={index}
                href={`#${heading.id}`}
                className={`
                  flex items-start text-gray-700 hover:text-blue-600 transition
                  ${heading.level === '3' ? 'pl-4 text-sm' : 'pl-2'}
                `}
              >
                <span className="text-blue-500 mr-2">•</span>
                {heading.text}
              </a>
            ))}
          </nav>
        </div>
      )}

      {/* Formatted Content */}
      <article className="
        prose 
        prose-lg
        prose-headings:scroll-mt-24
        prose-headings:font-bold
        prose-headings:text-gray-900
        prose-headings:tracking-tight
        prose-h1:text-4xl 
        prose-h1:mb-8 
        prose-h1:mt-4
        prose-h1:pb-4
        prose-h1:border-b
        prose-h1:border-gray-200
        prose-h2:text-3xl 
        prose-h2:mb-6 
        prose-h2:mt-10
        prose-h2:text-blue-900
        prose-h3:text-2xl 
        prose-h3:mb-4 
        prose-h3:mt-8
        prose-h3:text-gray-800
        prose-h4:text-xl 
        prose-h4:mb-3 
        prose-h4:mt-6
        prose-p:text-gray-700 
        prose-p:leading-relaxed 
        prose-p:mb-6
        prose-ul:my-6 
        prose-ul:space-y-3
        prose-ol:my-6 
        prose-ol:space-y-3
        prose-li:marker:text-blue-500
        prose-li:marker:font-bold
        prose-li:pl-2
        prose-li:text-gray-700
        prose-strong:text-gray-900 
        prose-strong:font-bold
        prose-em:text-gray-800 
        prose-em:italic
        prose-a:text-blue-600 
        prose-a:no-underline 
        prose-a:font-medium
        prose-a:relative
        prose-a:after:content-['']
        prose-a:after:absolute
        prose-a:after:left-0
        prose-a:after:-bottom-0.5
        prose-a:after:w-0
        prose-a:after:h-0.5
        prose-a:after:bg-blue-600
        prose-a:after:transition-all
        prose-a:after:duration-300
        hover:prose-a:text-blue-800
        hover:prose-a:after:w-full
        prose-blockquote:border-l-4 
        prose-blockquote:border-blue-500 
        prose-blockquote:pl-6 
        prose-blockquote:italic 
        prose-blockquote:text-gray-600 
        prose-blockquote:bg-gradient-to-r 
        prose-blockquote:from-blue-50 
        prose-blockquote:to-transparent
        prose-blockquote:py-4 
        prose-blockquote:px-6 
        prose-blockquote:rounded-r-xl
        prose-blockquote:my-8
        prose-code:bg-gray-100 
        prose-code:px-2 
        prose-code:py-1 
        prose-code:rounded 
        prose-code:font-mono 
        prose-code:text-sm
        prose-code:text-gray-800
        prose-pre:bg-gradient-to-br 
        prose-pre:from-gray-900 
        prose-pre:to-gray-800
        prose-pre:text-gray-100 
        prose-pre:p-6 
        prose-pre:rounded-xl 
        prose-pre:overflow-x-auto
        prose-pre:shadow-lg
        prose-pre:my-8
        prose-table:w-full 
        prose-table:my-8 
        prose-table:border-collapse
        prose-table:rounded-lg
        prose-table:overflow-hidden
        prose-table:shadow-sm
        prose-th:bg-gradient-to-r 
        prose-th:from-gray-100 
        prose-th:to-gray-50
        prose-th:font-bold 
        prose-th:p-4 
        prose-th:border 
        prose-th:border-gray-300 
        prose-th:text-left
        prose-th:text-gray-900
        prose-td:p-4 
        prose-td:border 
        prose-td:border-gray-300
        prose-td:text-gray-700
        prose-img:rounded-2xl 
        prose-img:shadow-lg 
        prose-img:my-8
        prose-img:transition-all
        prose-img:duration-300
        prose-img:hover:shadow-xl
        prose-hr:my-10 
        prose-hr:border-gray-300
        max-w-none
        selection:bg-blue-100 
        selection:text-blue-900
      ">
        <div 
          dangerouslySetInnerHTML={{ __html: content }} 
          className="[&>*:first-child]:mt-0 [&>*:last-child]:mb-0"
        />
      </article>
    </div>
  );
};

export default FormattedContent;