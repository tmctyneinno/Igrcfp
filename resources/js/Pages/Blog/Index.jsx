import React, { useMemo, useState } from "react";
import { Head, Link } from '@inertiajs/react';
import { motion } from "framer-motion";
import { fadeIn } from "@/utils/motionPresets";
import GuestLayout from '@/Layouts/GuestLayout';
import HeroSection from '@/Layouts/HeroSection';
import CallToAction from "@/Pages/components/CallToAction";
import { MagnifyingGlassIcon } from '@heroicons/react/24/outline';

export default function Blog({ auth, title, description, blogs }) {
    const [searchTerm, setSearchTerm] = useState('');
    const blogItems = blogs?.data || [];
 
    const filteredBlogs = useMemo(() => {
        const term = searchTerm.trim().toLowerCase();

        if (!term) {
            return blogItems;
        }

        return blogItems.filter((blog) => {
            return [blog.title, blog.excerpt, blog.content, blog.author]
                .filter(Boolean)
                .some((value) => stripHtml(value).toLowerCase().includes(term));
        });
    }, [blogItems, searchTerm]);

    const featuredBlogs = filteredBlogs.slice(0, 4);
    const latestBlogs = filteredBlogs.slice(4);

    return (
        <GuestLayout auth={auth}>
            <Head title={title} />
            
            <HeroSection 
                title = {title}
                description= "The Institute of Governance , Risk, Compliance & Financial Crime Prevention"
            />

            <section className="bg-white py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="max-w-lg mx-auto">
                        <div className="relative">
                            <input
                                type="text"
                                value={searchTerm}
                                onChange={(event) => setSearchTerm(event.target.value)}
                                placeholder="Search insights, news, or updates..."
                                className="w-full py-3 pl-10 pr-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            />
                            <MagnifyingGlassIcon className="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
                        </div>
                    </div>
                </div>
            </section>


            {featuredBlogs.length > 0 && (
                <section className="bg-white py-10">
                    <div className="max-w-7xl  px-4 sm:px-6 lg:px-8">
                        <div className="flex items-center gap-4 mb-4">
                            <motion.span
                                initial={{ width: 0 }}
                                whileInView={{ width: 48 }}
                                transition={{ duration: 0.5, ease: "easeOut" }}
                                viewport={{ once: true }}
                                className="h-px bg-[#0A1A2F]"
                            />
                            <span className="text-sm tracking-widest text-[#0A1A2F] uppercase">
                                Blog
                            </span>
                        </div>
                        <h1 className="text-3xl font-semibold text-[#0A1A2F] text-left mb-8 uppercase">Featured</h1>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                            {featuredBlogs.map((blog) => (
                                <BlogCard key={blog.id} blog={blog} />
                            ))}
                        </div>
                    </div>
                </section>
            )}
 
            <section className="bg-gray-50 py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center gap-4 mb-4">
                        <motion.span
                            initial={{ width: 0 }}
                            whileInView={{ width: 48 }}
                            transition={{ duration: 0.5, ease: "easeOut" }}
                            viewport={{ once: true }}
                            className="h-px bg-[#0A1A2F]"
                        />
                        <span className="text-sm tracking-widest text-[#0A1A2F] uppercase">
                            Blog
                        </span>
                    </div>
                    <h2 className="text-3xl font-semibold text-gray-900 text-left mb-8 uppercase">Latest Insights</h2>

                    {filteredBlogs.length === 0 ? (
                        <div className="bg-white border border-gray-200 rounded-lg p-8 text-center">
                            <p className="text-gray-600">No blog posts found.</p>
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
                            {(latestBlogs.length > 0 ? latestBlogs : filteredBlogs).map((blog) => (
                                <BlogCard key={blog.id} blog={blog} />
                            ))}
                        </div>
                    )}

                    {!searchTerm && blogs?.links?.length > 3 && (
                        <div className="flex flex-wrap justify-center gap-2 mt-8">
                            {blogs.links.map((link, index) => (
                                <Link
                                    key={`${link.label}-${index}`}
                                    href={link.url || '#'}
                                    preserveScroll
                                    className={`px-4 py-2 text-sm font-medium border rounded-md ${
                                        link.active
                                            ? 'bg-blue-600 text-white border-blue-600'
                                            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                                    } ${!link.url ? 'pointer-events-none opacity-50' : ''}`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ))}
                        </div>
                    )}
                </div>
            </section>

             <CallToAction />
        </GuestLayout>
    );
}

function BlogCard({ blog }) {
    return (
        <article className="relative rounded-[30px] overflow-hidden h-[406px] group">
            {/* Background Image */}
            <img
                src={blog.image}
                alt={blog.title}
                className="w-full h-full object-cover"
                onError={(event) => {
                    event.currentTarget.src = "/assets/images/innerpage/blog/blog-grid1.jpg";
                }}
            />

            {/* Dark Overlay */}
            <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>

            {/* Content */}
            <div className="absolute bottom-0 left-0 right-0 p-2 text-white">
                <h3 className="text-lg md:text-xl font-bold mb-2 leading-snug">
                    {blog.title}
                </h3>

                <p className="text-sm text-gray-200 mb-3 line-clamp-2">
                    {blog.excerpt || truncate(stripHtml(blog.content), 100)}
                </p>

                <div className="flex items-right justify-between pb-2">
                    <span className="text-xs text-gray-300">
                        {/* Written by IGRCFP Team */}
                    </span>
                    <Link
                        href={`/blog/${blog.slug}`}
                        className="px-3 py-1 bg-white/20 backdrop-blur-sm rounded text-sm font-medium hover:bg-white/30 transition-colors"
                    >
                        Read more
                    </Link>
                </div>
            </div>
        </article>
    );
} 

function stripHtml(value = '') {
    return value.replace(/<[^>]*>/g, ' ').replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').trim();
}

function truncate(value = '', length = 160) {
    return value.length > length ? `${value.slice(0, length).trim()}...` : value;
}

function formatDate(dateString) {
    if (!dateString) {
        return 'Recently published';
    }

    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
}
