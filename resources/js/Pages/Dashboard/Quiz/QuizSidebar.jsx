import React from 'react';
import { BookOpenIcon, CheckCircleIcon, FlagIcon } from '@heroicons/react/24/outline';

export default function QuizSidebar({ 
    questions, 
    essayQuestions, 
    currentQuestionIndex, 
    setCurrentQuestionIndex, 
    answers, 
    flaggedQuestions, 
    toggleFlag, 
    partASubmitted, 
    canAccessPartB,
    essayAnswers
}) {
    const answeredCount = questions.filter(q => Boolean(answers[q.id])).length;
    const essayCompletedCount = essayQuestions.filter(q => essayAnswers[q.id] && essayAnswers[q.id].length > 10).length;

    return (
        <div className="w-80 bg-white border-r border-gray-200 min-h-[calc(100vh-120px)] sticky top-[120px] overflow-y-auto">
            <div className="p-4">
                <h3 className="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <BookOpenIcon className="w-4 h-4" />
                    Course Quiz
                </h3>

                {/* Summary Card */}
                <div className="rounded-xl border border-gray-200 bg-gray-50 p-4 mb-4">
                    <p className="text-sm font-medium text-gray-900">Assessment Overview</p>
                    <div className="grid grid-cols-2 gap-3 mt-4 text-center">
                        <div className="bg-white rounded-lg p-3 border border-gray-100">
                            <p className="text-lg font-bold text-gray-900">{questions.length}</p>
                            <p className="text-xs text-gray-500">Part A (MCQ)</p>
                        </div>
                        <div className="bg-white rounded-lg p-3 border border-gray-100">
                            <p className="text-lg font-bold text-gray-900">{essayQuestions.length}</p>
                            <p className="text-xs text-gray-500">Part B (Essay)</p>
                        </div>
                    </div>
                </div>

                {/* Part A Navigator */}
                {questions.length > 0 && (
                    <>
                        <div className="mb-3 flex items-center justify-between">
                            <span className="text-sm font-medium text-gray-700">Part A Navigator</span>
                            <span className="text-xs text-gray-500">
                                {answeredCount}/{questions.length} answered
                            </span>
                        </div>

                        <div className="grid grid-cols-5 gap-2 mb-6">
                            {questions.map((question, index) => {
                                const isActive = index === currentQuestionIndex;
                                const isAnswered = Boolean(answers[question.id]);
                                const isFlagged = flaggedQuestions.has(question.id);

                                return (
                                    <button
                                        key={question.id}
                                        type="button"
                                        onClick={() => setCurrentQuestionIndex(index)}
                                        className={`relative h-10 rounded-lg text-sm font-semibold border transition ${
                                            isActive
                                                ? 'border-blue-600 bg-blue-600 text-white shadow-sm'
                                                : isAnswered
                                                    ? 'border-green-200 bg-green-50 text-green-700 hover:bg-green-100'
                                                    : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50'
                                        }`}
                                        title={`Question ${index + 1}`}
                                    >
                                        {index + 1}
                                        {isFlagged && (
                                            <span className={`absolute -top-1 -right-1 h-3 w-3 rounded-full ${
                                                isActive ? 'bg-amber-300' : 'bg-amber-500'
                                            }`} />
                                        )}
                                    </button>
                                );
                            })}
                        </div>
                    </>
                )}

                {/* Part B Status */}
                {essayQuestions.length > 0 && (
                    <div className={`mt-4 rounded-xl border p-4 transition-colors ${
                        canAccessPartB ? 'border-indigo-100 bg-indigo-50' : 'border-gray-200 bg-gray-50 opacity-60'
                    }`}>
                        <div className="flex items-center justify-between mb-2">
                            <p className="text-sm font-semibold text-indigo-900">Part B Essay</p>
                            {!canAccessPartB && <span className="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded">Locked</span>}
                        </div>
                        <p className="text-xs text-indigo-700">
                            {canAccessPartB
                                ? `${essayCompletedCount}/${essayQuestions.length} drafts saved`
                                : 'Requires 50%+ in Part A'}
                        </p>
                    </div>
                )}

                {/* Legend */}
                <div className="mt-6 pt-4 border-t border-gray-200">
                    <div className="grid grid-cols-2 gap-2 text-xs">
                        <div className="flex items-center gap-2 text-gray-600">
                            <span className="h-3 w-3 rounded bg-green-50 border border-green-200" />
                            Answered
                        </div>
                        <div className="flex items-center gap-2 text-gray-600">
                            <span className="h-3 w-3 rounded bg-white border border-gray-200" />
                            Unanswered
                        </div>
                        <div className="flex items-center gap-2 text-gray-600">
                            <span className="h-3 w-3 rounded bg-blue-600" />
                            Current
                        </div>
                        <div className="flex items-center gap-2 text-gray-600">
                            <span className="h-3 w-3 rounded-full bg-amber-500" />
                            Flagged
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}