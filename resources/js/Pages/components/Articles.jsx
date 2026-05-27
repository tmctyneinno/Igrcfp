import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";
import React from "react";
import { ArrowRight, BookOpen, Clock3, UserRound } from "lucide-react";

export default function Articles({ latestArticles = [], featuredArticles = [], latestBlogs = [] }) {

    const formatDate = (dateString) => {
        if (!dateString) {
            return 'Recently published';
        }

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

            {latestBlogs.length > 0 && (
                <section className="bg-blue-950 py-20 overflow-hidden" data-aos="fade-up" data-aos-duration="1000">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-12">
                            <div className="max-w-2xl">
                                <div className="inline-flex items-center gap-2 text-sm tracking-widest text-emerald-300 uppercase mb-3">
                                    <BookOpen className="h-4 w-4" aria-hidden="true" />
                                    From the Blog
                                </div>
                                <h2 className="text-3xl md:text-4xl font-bold text-white">
                                    Practical thinking for governance and compliance leaders
                                </h2>
                                <p className="text-slate-300 mt-3 leading-relaxed">
                                    Explore expert perspectives, institutional updates, and field notes from the IGRCFP team.
                                </p>
                            </div>

                            <Link
                                href="/blog"
                                className="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white text-slate-950 rounded-full font-semibold hover:bg-emerald-100 transition w-fit"
                            >
                                View all blog posts
                                <ArrowRight className="h-4 w-4" aria-hidden="true" />
                            </Link>
                        </div>

                        <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
                            {latestBlogs.map((blog, index) => (
                                <motion.article
                                    key={blog.id}
                                    variants={index === 0 ? scaleIn : fadeLeft}
                                    initial="hidden"
                                    whileInView="visible"
                                    viewport={{ once: true }}
                                    className="group bg-white rounded-lg overflow-hidden border border-white/10 shadow-xl"
                                >
                                    <Link href={`/blog/${blog.slug}`} className="block">
                                        <div className="relative h-56 overflow-hidden bg-slate-800">
                                            <img
                                                src={blog.image || '/assets/images/innerpage/blog/blog-grid1.jpg'}
                                                alt={blog.title}
                                                className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                                onError={(event) => {
                                                    event.currentTarget.src = '/assets/images/innerpage/blog/blog-grid1.jpg';
                                                }}
                                            />
                                            <div className="absolute inset-0 bg-gradient-to-t from-slate-950/55 to-transparent opacity-80" />
                                            <span className="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-xs font-bold text-slate-900">
                                                Blog
                                            </span>
                                        </div>

                                        <div className="p-2">
                                            <div className="flex flex-wrap items-center gap-3 text-xs text-slate-500 mb-3">
                                                
                                                <span className="inline-flex items-center gap-1.5">
                                                    <Clock3 className="h-3.5 w-3.5" aria-hidden="true" />
                                                    {blog.reading_time}
                                                </span>
                                            </div>

                                            <h3 className="text-xl font-bold text-slate-950 leading-snug mb-3 line-clamp-2 group-hover:text-emerald-700 transition">
                                                {blog.title}
                                            </h3>
                                            <p className="text-slate-600 text-sm leading-relaxed mb-3 line-clamp-3">
                                                {blog.excerpt}
                                            </p>

                                            <div className="flex items-center justify-between gap-4">
                                                <span className="text-xs text-slate-400">{formatDate(blog.published_at)}</span>
                                                <span className="inline-flex items-center gap-1 text-sm font-bold text-emerald-700 group-hover:gap-2 transition-all">
                                                    Read article
                                                    <ArrowRight className="h-4 w-4" aria-hidden="true" />
                                                </span>
                                            </div>
                                        </div>
                                    </Link>
                                </motion.article>
                            ))}
                        </div>
                    </div>
                </section>
            )}
            
        </div>
    );
}
