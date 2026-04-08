import React from "react";
import { Head, Link, usePage } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { 
  CalendarDaysIcon, 
  UserIcon, 
  ArrowRightIcon,
  MagnifyingGlassIcon,
  TagIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  EyeIcon,
  ClockIcon
} from '@heroicons/react/24/outline';

export default function News({ 
  auth, 
  title, 
  description, 
  featuredArticles = [], 
  latestArticles, 
  categories = [],
  popularTags = [],
  mostReadArticles = [],
  availableYears = [],
  filters = {},
  meta = {},
  links = {}
}) {
  
  // Extract articles data from paginator
  const articles = latestArticles?.data || [];
  
  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  // Function to build query string with current filters
  const buildQueryString = (newFilters = {}) => {
    const allFilters = { ...filters, ...newFilters };
    const queryParams = new URLSearchParams();
    
    Object.entries(allFilters).forEach(([key, value]) => {
      if (value && value !== '') {
        queryParams.append(key, value);
      }
    });
    
    const queryString = queryParams.toString();
    return queryString ? `?${queryString}` : '';
  };

  // Calculate reading time text
  const getReadTimeText = (minutes) => {
    if (minutes === 1) return '1 min read';
    return `${minutes} min read`;
  };

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
            
            <h1 className="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight">
              {title}
            </h1>
            
            <p className="text-xl text-gray-600 max-w-3xl mx-auto mb-10 leading-relaxed">
              {description}
            </p>
            
           
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
                        src={article.image || '/images/article-placeholder.jpg'} 
                        alt={article.title}
                        className="w-full h-full object-cover"
                        onError={(e) => {
                          e.target.src = '/images/article-placeholder.jpg';
                        }}
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
                        <span className="text-blue-600 flex items-center">
                          <ClockIcon className="w-4 h-4 mr-1" />
                          {getReadTimeText(article.read_time)}
                        </span>
                      </div>
                      
                      <h3 className="text-2xl font-bold text-gray-900 mb-4 hover:text-blue-600 transition">
                        <Link href={`/news/${article.slug}`}>
                          {article.title}
                        </Link>
                      </h3>
                      
                      <p className="text-gray-600 mb-6 line-clamp-3">
                        {article.excerpt}
                      </p>
                      
                      {article.tags && article.tags.length > 0 && (
                        <div className="flex flex-wrap gap-2 mb-6">
                          {article.tags.slice(0, 3).map(tag => (
                            <span 
                              key={tag} 
                              className="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm"
                            >
                              {tag.trim()}
                            </span>
                          ))}
                        </div>
                      )}
                      
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
               {/* Search Bar */}
            <form method="GET" action="/news" className="max-w-4xl mx-auto mb-8">
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
                  className="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-900 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition"
                >
                  Search
                </button>
              </div>
            </form>
              {/* Filters */}
              <div className="flex flex-col md:flex-row md:items-center justify-between mb-8 bg-white p-6 rounded-xl border">
                <div className="mb-4 md:mb-0">
                  <h3 className="text-lg font-semibold text-gray-900">Latest Updates</h3>
                  <p className="text-gray-600 text-sm">
                    Showing {meta.from || 0} - {meta.to || 0} of {meta.total || 0} articles
                    {filters.search && (
                      <span className="ml-2">
                        for "<span className="font-medium">{filters.search}</span>"
                      </span>
                    )}
                    {filters.category && categories.find(c => c.slug === filters.category) && (
                      <span className="ml-2">
                        in <span className="font-medium">
                          {categories.find(c => c.slug === filters.category).name}
                        </span>
                      </span>
                    )}
                  </p>
                </div>
                
                <div className="flex flex-wrap gap-4">
                  <form method="GET" action="/news" className="flex items-center gap-4">
                    <select 
                      name="category" 
                      defaultValue={filters.category || ''}
                      className="px-4 py-2 border border-gray-300 rounded-lg bg-white"
                      onChange={(e) => {
                        const form = e.target.form;
                        const searchInput = form.querySelector('input[name="search"]');
                        if (searchInput && !searchInput.value) {
                          form.submit();
                        } else {
                          // Remove search if category is selected
                          if (searchInput) searchInput.value = '';
                          form.submit();
                        }
                      }}
                    >
                      <option value="">All Categories</option>
                      {categories.map(cat => (
                        <option key={cat.id} value={cat.slug}>
                          {cat.name} ({cat.count})
                        </option>
                      ))}
                    </select>
                    
                    <select 
                      name="per_page" 
                      defaultValue={filters.per_page || '9'}
                      className="px-4 py-2 border border-gray-300 rounded-lg bg-white"
                      onChange={(e) => e.target.form.submit()}
                    >
                      <option value="6">6 per page</option>
                      <option value="9">9 per page</option>
                      <option value="12">12 per page</option>
                      <option value="24">24 per page</option>
                    </select>
                  </form>
                </div>
              </div>

              {/* Articles Grid */}
              {articles.length > 0 ? (
                <>
                  <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {articles.map(article => (
                      <article key={article.id} className="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div className="relative h-48">
                          <img 
                            src={article.image || '/images/article-placeholder.jpg'} 
                            alt={article.title}
                            className="w-full h-full object-cover"
                            onError={(e) => {
                              e.target.src = '/images/article-placeholder.jpg';
                            }}
                          />
                          <div className="absolute top-4 left-4">
                            <span className="px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-sm font-medium">
                              {article.category}
                            </span>
                          </div>
                          {article.is_featured && (
                            <div className="absolute top-4 right-4">
                              <span className="px-3 py-1 bg-yellow-500 text-white rounded-full text-xs font-medium">
                                Featured
                              </span>
                            </div>
                          )}
                        </div>
                        
                        <div className="p-6">
                          <div className="flex items-center justify-between text-sm text-gray-500 mb-3">
                            <div className="flex items-center">
                              <CalendarDaysIcon className="w-4 h-4 mr-1" />
                              {formatDate(article.published_at)}
                            </div>
                            <div className="flex items-center">
                              <EyeIcon className="w-4 h-4 mr-1" />
                              <span>{article.views?.toLocaleString() || 0}</span>
                            </div>
                          </div>
                          
                          <h4 className="text-xl font-bold text-gray-900 mb-3 hover:text-blue-600 transition line-clamp-2">
                            <Link href={`/news/${article.slug}/show`}>
                              {article.title}
                            </Link>
                          </h4>
                          
                          <p className="text-gray-600 mb-4 line-clamp-3">
                            {article.excerpt}
                          </p>
                          
                          <div className="flex items-center justify-between">
                            <div className="flex items-center">
                              {article.author_avatar ? (
                                <img 
                                  src={article.author_avatar} 
                                  alt={article.author}
                                  className="w-8 h-8 rounded-full mr-2"
                                />
                              ) : (
                                <div className="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-2">
                                  <UserIcon className="w-4 h-4 text-blue-600" />
                                </div>
                              )}
                              {/* <div>
                                <span className="text-sm font-medium text-gray-700 block">
                                  {article.author}
                                </span>
                                {article.author_title && (
                                  <span className="text-xs text-gray-500 block">
                                    {article.author_title}
                                  </span>
                                )}
                              </div> */}
                            </div>
                            
                            <Link  
                              href={`/news/${article.slug}/show`}
                              className="text-blue-900 text-sm font-medium hover:text-blue-800"
                            >
                              Read →
                            </Link>
                          </div>
                        </div>
                      </article>
                    ))}
                  </div>
 
                  {/* Pagination */}
                  {meta.total > meta.per_page && (
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
                                href={`/news${buildQueryString({ page })}`}
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
                </>
              ) : (
                <div className="text-center py-12 bg-white rounded-xl border">
                  <div className="max-w-md mx-auto">
                    <div className="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                      <MagnifyingGlassIcon className="w-8 h-8 text-gray-400" />
                    </div>
                    <h3 className="text-lg font-semibold text-gray-900 mb-2">No articles found</h3>
                    <p className="text-gray-600 mb-6">
                      {filters.search 
                        ? `No results found for "${filters.search}". Try different keywords.`
                        : 'No articles available at the moment. Please check back later.'}
                    </p>
                    {filters.search || filters.category || filters.year ? (
                      <Link
                        href="/news"
                        className="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                      >
                        Clear filters
                      </Link>
                    ) : null}
                  </div>
                </div>
              )}
            </div>

            {/* Sidebar */}
            <div className="lg:col-span-1">
              {/* Categories */}
              <div className="bg-white rounded-xl border p-6 mb-8">
                <h4 className="text-lg font-semibold text-gray-900 mb-4">Browse by Category</h4>
                <ul className="space-y-3">
                  <li>
                    <Link 
                      href="/news"
                      className={`flex items-center justify-between py-2 transition ${
                        !filters.category 
                          ? 'text-blue-600 font-medium' 
                          : 'text-gray-700 hover:text-blue-600'
                      }`}
                    >
                      <span>All Categories</span>
                      <span className="text-gray-400 text-sm">({meta.total || 0})</span>
                    </Link>
                  </li>
                  {categories.map(category => (
                    <li key={category.id}>
                      <Link 
                        href={`/news${buildQueryString({ category: category.slug, page: 1 })}`}
                        className={`flex items-center justify-between py-2 transition ${
                          filters.category === category.slug 
                            ? 'text-blue-600 font-medium' 
                            : 'text-gray-700 hover:text-blue-600'
                        }`}
                      >
                        <span>{category.name}</span>
                        <span className="text-gray-400 text-sm">({category.count})</span>
                      </Link>
                    </li>
                  ))}
                </ul>
              </div>

              {/* Year Filter */}
              {availableYears.length > 0 && (
                <div className="bg-white rounded-xl border p-6 mb-8">
                  <h4 className="text-lg font-semibold text-gray-900 mb-4">Filter by Year</h4>
                  <div className="flex flex-wrap gap-2">
                    <Link
                      href="/news"
                      className={`px-3 py-1.5 rounded-lg text-sm transition ${
                        !filters.year 
                          ? 'bg-blue-100 text-blue-700 font-medium' 
                          : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                      }`}
                    >
                      All Years
                    </Link>
                    {availableYears.map(year => (
                      <Link
                        key={year}
                        href={`/news${buildQueryString({ year, page: 1 })}`}
                        className={`px-3 py-1.5 rounded-lg text-sm transition ${
                          filters.year == year 
                            ? 'bg-blue-100 text-blue-700 font-medium' 
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                        }`}
                      >
                        {year}
                      </Link>
                    ))}
                  </div>
                </div>
              )}

              {/* Most Read Articles */}
              {mostReadArticles.length > 0 && (
                <div className="bg-white rounded-xl border p-6 mb-8">
                  <h4 className="text-lg font-semibold text-gray-900 mb-4">Most Read</h4>
                  <div className="space-y-4">
                    {mostReadArticles.map((article, index) => (
                      <div key={article.id} className="flex items-start space-x-3">
                        <div className="flex-shrink-0">
                          <div className={`w-8 h-8 rounded-full flex items-center justify-center font-bold ${
                            index === 0 
                              ? 'bg-red-100 text-red-600' 
                              : index === 1 
                                ? 'bg-orange-100 text-orange-600' 
                                : index === 2 
                                  ? 'bg-yellow-100 text-yellow-600' 
                                  : 'bg-gray-100 text-gray-600'
                          }`}>
                            {index + 1}
                          </div>
                        </div>
                        <div>
                          <Link 
                            href={`/news/${article.slug}`}
                            className="text-sm font-medium text-gray-900 hover:text-blue-600 line-clamp-2"
                          >
                            {article.title}
                          </Link>
                          <div className="flex items-center text-xs text-gray-500 mt-1">
                            <CalendarDaysIcon className="w-3 h-3 mr-1" />
                            {formatDate(article.published_at)}
                            <span className="mx-2">•</span>
                            <EyeIcon className="w-3 h-3 mr-1" />
                            {article.views?.toLocaleString() || 0} views
                          </div>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {/* Popular Tags */}
              {popularTags.length > 0 && (
                <div className="bg-white rounded-xl border p-6 mb-8">
                  <h4 className="text-lg font-semibold text-gray-900 mb-4">Popular Topics</h4>
                  <div className="flex flex-wrap gap-2">
                    {popularTags.map(tag => (
                      <Link
                        key={tag}
                        href={`/news${buildQueryString({ search: tag, page: 1 })}`}
                        className="px-3 py-1.5 bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 rounded-lg text-sm transition"
                      >
                        {tag}
                      </Link>
                    ))}
                  </div>
                </div>
              )}

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
                    className="w-full bg-blue-900 text-white py-2 rounded-lg hover:bg-blue-700 transition"
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
            {/* <Link
              href="/services/advisory"
              className="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white/10 transition"
            >
              View Advisory Services
            </Link> */}
          </div>
        </div>
      </section>
    </GuestLayout>
  );
}