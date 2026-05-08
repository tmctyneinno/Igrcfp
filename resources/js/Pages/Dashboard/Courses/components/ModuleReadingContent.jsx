import React, { useEffect, useRef, useState } from 'react';
import { CheckCircleIcon } from '@heroicons/react/24/outline';

export default function ModuleReadingContent({
    module,
    isRead,
    readingProgress = 0,
    onProgressChange,
    onRead,
}) {
    const contentRef = useRef(null);
    const readingContent = module.reading_content || module.full_content || '';
    const [localProgress, setLocalProgress] = useState(isRead ? 100 : readingProgress);

    useEffect(() => {
        setLocalProgress(isRead ? 100 : readingProgress);
    }, [isRead, readingProgress]);

    useEffect(() => {
        const element = contentRef.current;
        if (!element || isRead) return;

        const updateProgress = () => {
            const scrollableHeight = element.scrollHeight - element.clientHeight;
            const nextProgress = scrollableHeight > 0
                ? Math.min(100, Math.round((element.scrollTop / scrollableHeight) * 100))
                : 100;

            setLocalProgress(nextProgress);
            onProgressChange?.(module.id, nextProgress);

            if (scrollableHeight <= 0 || scrollableHeight - element.scrollTop <= 40) {
                setLocalProgress(100);
                onProgressChange?.(module.id, 100);
            }
        };

        updateProgress();
        element.addEventListener('scroll', updateProgress);

        return () => element.removeEventListener('scroll', updateProgress);
    }, [isRead, module.id, onProgressChange]);

    const displayedProgress = Math.max(localProgress, isRead ? 100 : 0);

    return (
        <div className="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div className="px-4 py-3 border-b border-gray-100">
                <div className="flex items-center justify-between gap-3 mb-2">
                    <h4 className="text-md font-semibold text-gray-900 flex items-center gap-2">
                        📖 Module Content
                    </h4>
                    {isRead ? (
                        <span className="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-50 px-2.5 py-1 rounded-full">
                            <CheckCircleIcon className="w-3.5 h-3.5" />
                            Read
                        </span>
                    ) : (
                        <span className="text-xs font-medium text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full">
                            Read to unlock next module
                        </span>
                    )}
                </div>
                <div className="flex justify-between text-xs text-gray-500 mb-1">
                    <span>Reading Progress</span>
                    <span>{displayedProgress}%</span>
                </div>
                <div className="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                    <div
                        className={`h-full rounded-full transition-all duration-150 ${
                            isRead ? 'bg-green-500' : 'bg-blue-600'
                        }`}
                        style={{ width: `${displayedProgress}%` }}
                    />
                </div>
            </div>

            <div
                ref={contentRef}
                className="prose prose-sm max-w-none text-gray-700 p-4 max-h-96 overflow-y-auto"
                dangerouslySetInnerHTML={{ __html: readingContent }}
            />

            {!isRead && (
                <div className="px-4 py-3 border-t border-gray-100 bg-gray-50 flex items-center justify-between gap-3">
                    <p className="text-xs text-gray-500">
                        Scroll through the module content, then mark it as read to unlock the next module.
                    </p>
                    <button
                        type="button"
                        onClick={() => onRead?.(module.id)}
                        disabled={displayedProgress < 100}
                        className="px-4 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed transition"
                    >
                        Mark as read
                    </button>
                </div>
            )}
        </div>
    );
}
