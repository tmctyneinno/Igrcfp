import React from "react";
import { Head, Link, usePage } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { 
  CalendarDaysIcon, 
  UserIcon, 
  ArrowRightIcon,
  MagnifyingGlassIcon,
  FunnelIcon,
  TagIcon,
  ChevronLeftIcon,
  ChevronRightIcon
} from '@heroicons/react/24/outline';

export default function News({ 
  auth, 
  title, 
  description, 
  featuredArticles = [], 
  latestArticles = { data: [] }, // Changed to object with data property
  categories = [],
  popularTags = [],
  filters = {}
}) {
  
  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  // Access pagination links from latestArticles
  const { data: articles, links, meta } = latestArticles;

  return (
    <GuestLayout auth={auth}>
      <Head title={title} />
      
      {/* Hero Section */}
      <section className="w-full bg-gradient-to-r from-blue-50 via-white to-blue-50 py-16 md:py-28 border-b">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center max-w-4xl mx-auto">
            <div className="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-sm font-medium mb-6">
              <TagIcon className="w-4 h-4 mr-2" />
              Industry Insights & Updates
            </div>
            
            <h1 className="text-3xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
              {title}
            </h1>
            
            <p className="text-xl text-gray-600 max-w-3xl mx-auto mb-10 leading-relaxed">
              {description}
            </p>
            
            {/* Search Bar */}
            <form method="GET" action="/news" className="max-w-2xl mx-auto mb-8">
              <div className="relative">
                <MagnifyingGlassIcon className="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                <input
                  type="search"
                  name="search"
                  defaultValue={filters.search || ''}
                  placeholder="Search articles, topics, or keywords..."
                  className="w-full pl-12 pr-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                />
                <button 
                  type="submit"
                  className="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition"
                >
                  Search
                </button>
              </div>
            </form>
          </div>
        </div>
      </section>

      {/* Featured Articles Section */}
      {featuredArticles.length > 0 && (
        <section className="py-16 bg-white">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex justify-between items-center mb-12">
              <div>
                <h2 className="text-3xl font-bold text-gray-900">Featured Insights</h2>
                <p className="text-gray-600 mt-2">In-depth analysis and expert commentary</p>
              </div>
              <Link 
                href="/news?filter=featured" 
                className="text-blue-600 hover:text-blue-800 font-medium flex items-center"
              >
                View all featured
                <ArrowRightIcon className="w-4 h-4 ml-2" />
              </Link>
            </div>
            
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
              {featuredArticles.slice(0, 2).map((article, index) => (
                <div 
                  key={article.id} 
                  className={`bg-white rounded-2xl overflow-hidden border border-gray-200 hover:shadow-xl transition-shadow duration-300 ${
                    index === 0 ? 'lg:col-span-2' : ''
                  }`}
                >
                  <div className={`flex flex-col ${index === 0 ? 'lg:flex-row' : ''}`}>
                    <div className={`relative ${index === 0 ? 'lg:w-1/2' : 'h-48'}`}>
                      <img 
                        src={article.image} 
                        alt={article.title}
                        className="w-full h-full object-cover"
                      />
                      <div className="absolute top-4 left-4">
                        <span className="px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-sm font-medium">
                          {article.category}
                        </span>
                      </div>
                    </div>
                    
                    <div className={`p-8 ${index === 0 ? 'lg:w-1/2 lg:flex lg:flex-col lg:justify-center' : ''}`}>
                      <div className="flex items-center text-sm text-gray-500 mb-4">
                        <CalendarDaysIcon className="w-4 h-4 mr-1" />
                        {formatDate(article.published_at)}
                        <span className="mx-2">•</span>
                        <UserIcon className="w-4 h-4 mr-1" />
                        {article.author}
                        <span className="mx-2">•</span>
                        <span className="text-blue-600">{article.read_time} min read</span>
                      </div>
                      
                      <h3 className="text-2xl font-bold text-gray-900 mb-4 hover:text-blue-600 transition">
                        <Link href={`/news/${article.slug}`}>
                          {article.title}
                        </Link>
                      </h3>
                      
                      <p className="text-gray-600 mb-6 line-clamp-3">
                        {article.excerpt}
                      </p>
                      
                      <div className="flex flex-wrap gap-2 mb-6">
                        {article.tags?.slice(0, 3).map(tag => (
                          <span 
                            key={tag} 
                            className="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm"
                          >
                            {tag}
                          </span>
                        ))}
                      </div>
                      
                      <Link 
                        href={`/news/${article.slug}`}
                        className="inline-flex items-center text-blue-600 font-medium hover:text-blue-800"
                      >
                        Read full analysis
                        <ArrowRightIcon className="w-4 h-4 ml-2" />
                      </Link>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* Main Content with Sidebar */}
      <section className="py-16 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {/* Main Content */}
            <div className="lg:col-span-3">
              {/* Filters */}
              <div className="flex flex-col md:flex-row md:items-center justify-between mb-8 bg-white p-6 rounded-xl border">
                <div className="mb-4 md:mb-0">
                  <h3 className="text-lg font-semibold text-gray-900">Latest Updates</h3>
                  <p className="text-gray-600 text-sm">Showing {meta?.total || 0} articles</p>
                </div>
                
                <div className="flex flex-wrap gap-4">
                  <form method="GET" action="/news" className="flex items-center gap-4">
                    <select 
                      name="category" 
                      defaultValue={filters.category || ''}
                      className="px-4 py-2 border border-gray-300 rounded-lg bg-white"
                      onChange={(e) => e.target.form.submit()}
                    >
                      <option value="">All Categories</option>
                      {categories.map(cat => (
                        <option key={cat.id} value={cat.slug}>{cat.name} ({cat.count})</option>
                      ))}
                    </select>
                  </form>
                </div>
              </div>

              {/* Articles Grid */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                {articles.length > 0 ? (
                  articles.map(article => (
                    <article key={article.id} className="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                      <div className="relative h-48">
                        <img 
                          src={article.image} 
                          alt={article.title}
                          className="w-full h-full object-cover"
                          onError={(e) => {
                            e.target.src = 'https://via.placeholder.com/400x200?text=Article+Image';
                          }}
                        />
                        <div className="absolute top-4 left-4">
                          <span className="px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-sm font-medium">
                            {article.category}
                          </span>
                        </div>
                      </div>
                      
                      <div className="p-6">
                        <div className="flex items-center text-sm text-gray-500 mb-3">
                          <CalendarDaysIcon className="w-4 h-4 mr-1" />
                          {formatDate(article.published_at)}
                        </div>
                        
                        <h4 className="text-xl font-bold text-gray-900 mb-3 hover:text-blue-600 transition">
                          <Link href={`/news/${article.slug}`}>
                            {article.title}
                          </Link>
                        </h4>
                        
                        <p className="text-gray-600 mb-4 line-clamp-2">
                          {article.excerpt}
                        </p>
                        
                        <div className="flex items-center justify-between">
                          <div className="flex items-center">
                            <div className="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2">
                              <UserIcon className="w-4 h-4 text-gray-500" />
                            </div>
                            <span className="text-sm text-gray-700">{article.author}</span>
                          </div>
                          
                          <Link 
                            href={`/news/${article.slug}`}
                            className="text-blue-600 text-sm font-medium hover:text-blue-800"
                          >
                            Read more →
                          </Link>
                        </div>
                      </div>
                    </article>
                  ))
                ) : (
                  <div className="col-span-2 text-center py-12">
                    <p className="text-gray-500 text-lg">No articles found. Try a different search or filter.</p>
                  </div>
                )}
              </div>

              {/* Pagination */}
              {meta && meta.total > 0 && (
                <div className="mt-12">
                  <nav className="flex items-center justify-between border-t border-gray-200 px-4 py-6">
                    <div className="flex flex-1 justify-between sm:justify-end">
                      {links.prev && (
                        <Link
                          href={links.prev}
                          className="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                          <ChevronLeftIcon className="h-5 w-5 mr-2" />
                          Previous
                        </Link>
                      )}
                      
                      <div className="hidden sm:flex items-center space-x-2">
                        {Array.from({ length: meta.last_page }, (_, i) => i + 1).map(page => (
                          <Link
                            key={page}
                            href={`/news?page=${page}`}
                            className={`relative inline-flex items-center px-4 py-2 text-sm font-medium ${
                              meta.current_page === page
                                ? 'z-10 bg-blue-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600'
                                : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0'
                            } rounded-md`}
                          >
                            {page}
                          </Link>
                        ))}
                      </div>
                      
                      {links.next && (
                        <Link
                          href={links.next}
                          className="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                          Next
                          <ChevronRightIcon className="h-5 w-5 ml-2" />
                        </Link>
                      )}
                    </div>
                  </nav>
                </div>
              )}
            </div>

            {/* Sidebar */}
            <div className="lg:col-span-1">
              {/* Categories */}
              <div className="bg-white rounded-xl border p-6 mb-8">
                <h4 className="text-lg font-semibold text-gray-900 mb-4">Browse by Category</h4>
                <ul className="space-y-3">
                  {categories.map(category => (
                    <li key={category.id}>
                      <Link 
                        href={`/news?category=${category.slug}`}
                        className="flex items-center justify-between py-2 hover:text-blue-600 transition"
                      >
                        <span>{category.name}</span>
                        <span className="text-gray-400 text-sm">({category.count})</span>
                      </Link>
                    </li>
                  ))}
                </ul>
              </div>

              {/* Popular Tags */}
              <div className="bg-white rounded-xl border p-6 mb-8">
                <h4 className="text-lg font-semibold text-gray-900 mb-4">Popular Topics</h4>
                <div className="flex flex-wrap gap-2">
                  {popularTags.map(tag => (
                    <Link
                      key={tag}
                      href={`/news?search=${encodeURIComponent(tag)}`}
                      className="px-3 py-1.5 bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 rounded-lg text-sm transition"
                    >
                      {tag}
                    </Link>
                  ))}
                </div>
              </div>

              {/* Newsletter */}
              <div className="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100 p-6">
                <h4 className="text-lg font-semibold text-gray-900 mb-3">Stay Updated</h4>
                <p className="text-gray-600 text-sm mb-4">
                  Get weekly insights on regulatory changes and industry trends.
                </p>
                <form method="POST" action="/newsletter/subscribe" className="space-y-3">
                  <input
                    type="email"
                    name="email"
                    placeholder="Your email address"
                    className="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                    required
                  />
                  <button 
                    type="submit"
                    className="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition"
                  >
                    Subscribe
                  </button>
                </form>
                <p className="text-gray-500 text-xs mt-3">
                  No spam. Unsubscribe anytime.
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-16 bg-gradient-to-r from-blue-600 to-indigo-700">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-3xl font-bold text-white mb-6">
            Need Expert Analysis for Your Organization?
          </h2>
          <p className="text-blue-100 text-xl mb-8 max-w-3xl mx-auto">
            Our team provides customized briefings and regulatory impact assessments tailored to your specific needs.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              href="/contact"
              className="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition"
            >
              Request a Consultation
            </Link>
            <Link
              href="/services/advisory"
              className="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white/10 transition"
            >
              View Advisory Services
            </Link>
          </div>
        </div>
      </section>
    </GuestLayout>
  );
}