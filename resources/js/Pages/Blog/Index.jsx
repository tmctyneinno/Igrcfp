import React, { useMemo, useState } from "react";
import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
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

    const featuredBlogs = filteredBlogs.slice(0, 3);
    const latestBlogs = filteredBlogs.slice(3);

    return (
        <GuestLayout auth={auth}>
            <Head title={title} />
            
            <section className="w-full bg-gradient-to-r from-blue-50 via-white to-blue-50 py-20 md:py-28 border-b">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center max-w-4xl mx-auto">
                        <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                            {title}
                        </h1>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                            {description}
                        </p>
                    </div>
                </div>
            </section>

            <section className="bg-white py-10">
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
                <section className="bg-white py-12">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <h2 className="text-3xl font-semibold text-gray-900 text-center mb-8">Featured Insights</h2>
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
                    <h2 className="text-3xl font-semibold text-gray-900 text-center mb-8">Latest Insights</h2>

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

            <section className="max-w-7xl mx-auto px-4 rounded-lg bg-gradient-to-r from-blue-600 via-blue-700 to-green-500">
                <div className="mx-6 md:mx-12 lg:mx-16 xl:mx-24">
                    <div className="max-w-7xl mx-auto text-white text-center py-8 md:py-12 lg:py-16">
                        <h3 className="text-3xl font-semibold mb-4">Never miss an update.</h3>
                        <p className="text-lg mb-8">Get insights delivered to your inbox.</p>

                        <div className="flex flex-col sm:flex-row justify-center gap-4 mb-4 px-4">
                            <input
                                type="email"
                                placeholder="Enter your email"
                                className="w-full max-w-md py-3 px-4 border border-white rounded-md text-black"
                            />
                            <button className="py-3 px-6 bg-white text-blue-600 font-semibold rounded-md hover:bg-gray-200">
                                Subscribe
                            </button>
                        </div>

                        <p className="text-sm">We care about your data in <Link href="/privacy-policy" className="text-blue-200 hover:text-blue-100">our privacy policy</Link>.</p>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}

function BlogCard({ blog }) {
    return (
        <article className="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100 h-full flex flex-col">
            <img
                src={blog.image}
                alt={blog.title}
                className="w-full h-56 object-cover"
                onError={(event) => {
                    event.currentTarget.src = '/assets/images/innerpage/blog/blog-grid1.jpg';
                }}
            />
            <div className="p-6 flex flex-col flex-1">
                <div className="text-sm text-gray-500 mb-3">
                    {formatDate(blog.published_at || blog.created_at)} · {blog.author} · {blog.reading_time}
                </div>
                <h3 className="text-xl font-bold text-gray-900 mb-4">{blog.title}</h3>
                <p className="text-gray-600 line-clamp-3 flex-1">
                    {blog.excerpt || truncate(stripHtml(blog.content), 160)}
                </p>
                <Link
                    href={`/blog/${blog.slug}`}
                    className="mt-5 inline-flex font-semibold text-blue-600 hover:text-blue-800"
                >
                    Read More
                </Link>
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
