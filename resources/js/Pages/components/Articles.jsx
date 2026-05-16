import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";
import React from "react";

export default function Articles({latestArticles, featuredArticles}) {

    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

    return ( 
        <div>
           {/* Latest News Section */}
            {latestArticles.length > 0 && (
                <section className="bg-white py-20" data-aos="fade-up" data-aos-duration="1000">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between items-center mb-12">
                            <div>
                                <div className="relative inline-flex items-center mb-3">
                                    <span className="text-sm tracking-widest text-gray-400 uppercase">
                                        Latest News & Insights
                                    </span>
                                </div>
                                <h2 className="text-3xl md:text-4xl font-bold text-gray-900">
                                    Stay Updated
                                </h2>
                                <p className="text-gray-600 mt-2">
                                    Regulatory developments, industry trends, and expert analysis
                                </p>
                            </div>
                            <Link 
                                href="/news" 
                                className="hidden md:inline-flex items-center text-blue-900 font-semibold hover:text-blue-700 transition"
                            >
                                View All News
                                <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </Link>
                        </div>

                        {/* Article Grid */}
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            {latestArticles.slice(0, 3).map(article => (
                                <Link 
                                    key={article.id}
                                    href={`/news/${article.slug}/show`}
                                    className="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300"
                                >
                                    <div className="h-48">
                                        <img 
                                            src={article.image || '/images/article-placeholder.jpg'} 
                                            alt={article.title}
                                            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                            onError={(e) => {
                                                e.target.src = '/images/article-placeholder.jpg';
                                            }}
                                        />
                                    </div>
                                    <div className="p-3">
                                        <div className="flex items-center gap-2 mb-3">
                                            <span className="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-medium">
                                                {article.category || 'News'}
                                            </span>
                                            <span className="text-xs text-gray-400">{article.read_time} min read</span>
                                        </div>
                                        <h4 className="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition line-clamp-2">
                                            {article.title}
                                        </h4>
                                        <p className="text-gray-500 text-sm mb-3 line-clamp-2">
                                            {article.excerpt}
                                        </p>
                                        <div className="flex items-center justify-between">
                                            <span className="text-xs text-gray-400">{formatDate(article.published_at)}</span>
                                            <span className="text-blue-600 text-sm font-medium group-hover:translate-x-1 transition-transform inline-flex items-center">
                                                Read More →
                                            </span>
                                        </div>
                                    </div>
                                </Link>
                            ))}
                        </div>

                        {/* Mobile View All Link */}
                        <div className="text-center mt-8 md:hidden">
                            <Link 
                                href="/news" 
                                className="inline-flex items-center text-blue-900 font-semibold hover:text-blue-700 transition"
                            >
                                View All News
                                <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </section>
            )}
            
        </div>
    );
}
