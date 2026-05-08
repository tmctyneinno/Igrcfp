import React from "react";
import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import {
    ArrowLeftIcon,
    CalendarDaysIcon,
    ClockIcon,
    UserIcon,
} from '@heroicons/react/24/outline';

export default function BlogShow({ auth, title, description, blog, relatedBlogs = [], canonicalUrl }) {
    return (
        <GuestLayout auth={auth}>
            <Head title={title}>
                <meta name="description" content={description} />
                {blog.meta_keywords && <meta name="keywords" content={blog.meta_keywords} />}
                <meta property="og:title" content={title} />
                <meta property="og:description" content={description} />
                <meta property="og:image" content={blog.image} />
                <meta property="og:type" content="article" />
                {canonicalUrl && <link rel="canonical" href={canonicalUrl} />}
            </Head>

            <section className="bg-gradient-to-r from-blue-50 via-white to-blue-50 py-12 md:py-16 border-b">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <Link href="/blog" className="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium mb-8">
                        <ArrowLeftIcon className="w-4 h-4 mr-2" />
                        Back to Blog
                    </Link>

                    <h1 className="text-3xl md:text-5xl font-bold text-gray-900 leading-tight mb-6">
                        {blog.title}
                    </h1>

                    <div className="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-gray-600">
                        <span className="inline-flex items-center">
                            <UserIcon className="w-4 h-4 mr-1" />
                            {blog.author}
                        </span>
                        <span className="inline-flex items-center">
                            <CalendarDaysIcon className="w-4 h-4 mr-1" />
                            {formatDate(blog.published_at)}
                        </span>
                        <span className="inline-flex items-center">
                            <ClockIcon className="w-4 h-4 mr-1" />
                            {blog.reading_time}
                        </span>
                    </div>
                </div>
            </section>

            <section className="py-12 bg-white">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="rounded-xl overflow-hidden shadow-lg mb-10">
                        <img
                            src={blog.image}
                            alt={blog.title}
                            className="w-full max-h-[520px] object-cover"
                            onError={(event) => {
                                event.currentTarget.src = '/assets/images/innerpage/blog/blog-grid1.jpg';
                            }}
                        />
                    </div>

                    <article
                        className="prose prose-lg max-w-none prose-headings:text-gray-900 prose-p:text-gray-700 prose-a:text-blue-600 prose-img:rounded-lg"
                        dangerouslySetInnerHTML={{ __html: blog.content || '' }}
                    />
                </div>
            </section>

            {relatedBlogs.length > 0 && (
                <section className="py-12 bg-gray-50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <h2 className="text-3xl font-semibold text-gray-900 mb-8">More Insights</h2>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                            {relatedBlogs.map((relatedBlog) => (
                                <Link
                                    key={relatedBlog.id}
                                    href={`/blog/${relatedBlog.slug}`}
                                    className="bg-white rounded-lg shadow overflow-hidden border border-gray-100 hover:shadow-lg transition"
                                >
                                    <img
                                        src={relatedBlog.image}
                                        alt={relatedBlog.title}
                                        className="w-full h-44 object-cover"
                                        onError={(event) => {
                                            event.currentTarget.src = '/assets/images/innerpage/blog/blog-grid1.jpg';
                                        }}
                                    />
                                    <div className="p-5">
                                        <p className="text-sm text-gray-500 mb-2">
                                            {formatDate(relatedBlog.published_at)} · {relatedBlog.reading_time}
                                        </p>
                                        <h3 className="text-lg font-bold text-gray-900 mb-3">
                                            {relatedBlog.title}
                                        </h3>
                                        <p className="text-gray-600 line-clamp-2">
                                            {relatedBlog.excerpt}
                                        </p>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    </div>
                </section>
            )}
        </GuestLayout>
    );
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
