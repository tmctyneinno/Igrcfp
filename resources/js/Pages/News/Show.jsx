import React, { useEffect } from "react";
import { Head, Link, usePage } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { 
  CalendarDaysIcon, 
  UserIcon, 
  ArrowLeftIcon,
  ArrowRightIcon,
  ClockIcon,
  EyeIcon,
  ShareIcon,
  BookmarkIcon,
  ChatBubbleLeftRightIcon,
  TagIcon,
  MagnifyingGlassIcon
} from '@heroicons/react/24/outline';
import { 
  FacebookIcon, 
  TwitterIcon, 
  LinkedinIcon, 
  LinkIcon 
} from '@/Components/Icons/SocialIcons'; 

export default function ArticleShow({ 
  auth, 
  title, 
  description, 
  article,
  relatedArticles = [],
  popularArticles = [],
  recentArticles = [],
  navigation = {},
}) {
  
  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  const formatDateTime = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  // Function to share article
  const shareArticle = (platform) => {
    const url = window.location.href;
    const text = article.title;
    const hashtags = article.tags ? article.tags.join(',') : '';

    switch (platform) {
      case 'facebook':
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
        break;
      case 'twitter':
        window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}&hashtags=${encodeURIComponent(hashtags)}`, '_blank');
        break;
      case 'linkedin':
        window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`, '_blank');
        break;
      case 'copy':
        navigator.clipboard.writeText(url);
        alert('Link copied to clipboard!');
        break;
    }
  };

  // Function to handle reading time
  const getReadTimeText = (minutes) => {
    if (minutes === 1) return '1 min read';
    return `${minutes} min read`;
  };

  // Parse and render content HTML safely
  const renderContent = () => {
    return { __html: article.content || '' };
  };

  // Handle table of contents generation
  useEffect(() => {
    // Add table of contents if there are headings
    const contentElement = document.querySelector('.article-content');
    if (contentElement) {
      const headings = contentElement.querySelectorAll('h2, h3');
      if (headings.length > 2) {
        generateTableOfContents(headings);
      }
    }
  }, []);

  const generateTableOfContents = (headings) => {
    const tocContainer = document.getElementById('table-of-contents');
    if (!tocContainer) return;

    let tocHTML = '<h4 class="text-lg font-semibold mb-4">Table of Contents</h4><ul class="space-y-2">';
    
    headings.forEach((heading, index) => {
      const id = heading.id || `section-${index}`;
      heading.id = id;
      
      const level = heading.tagName === 'H2' ? 2 : 3;
      const indent = level === 3 ? 'ml-4' : '';
      
      tocHTML += `
        <li class="${indent}">
          <a href="#${id}" class="text-blue-600 hover:text-blue-800 hover:underline text-sm">
            ${heading.textContent}
          </a>
        </li>
      `;
    });
    
    tocHTML += '</ul>';
    tocContainer.innerHTML = tocHTML;
  };

  return (
    <GuestLayout auth={auth} forceWhiteNavbar>
      <Head title={title}>
        <meta name="description" content={description} />
        <meta name="keywords" content={article.tags?.join(', ')} />
        <meta property="og:title" content={title} />
        <meta property="og:description" content={description} />
        <meta property="og:image" content={article.image} />
        <meta property="og:type" content="article" />
        <meta property="og:url" content={window.location.href} />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content={title} />
        <meta name="twitter:description" content={description} />
        <meta name="twitter:image" content={article.image} />
        <link rel="canonical" href={usePage().props.canonicalUrl} />
      </Head>
      
      {/* Article Header */}
      <section className="w-full bg-gradient-to-r from-blue-50 via-white to-blue-50 py-20 md:py-16 border-b">
        <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="mb-6">
            <Link 
              href="/news"
              className="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium"
            >
              <ArrowLeftIcon className="w-4 h-4 mr-2" />
              Back to all articles
            </Link>
          </div>
          
          <div className="mb-6">
            <Link 
              href={`/news?category=${article.category.slug}`}
              className="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium hover:bg-blue-200 transition"
            >
              <TagIcon className="w-4 h-4 mr-2" />
              {article.category.name}
            </Link>
            {article.is_featured && (
              <span className="ml-3 inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium">
                Featured
              </span>
            )}
          </div>
          
          <h1 className="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight">
            {article.title}
          </h1>
          
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div className="flex items-center">
              {article.author.avatar ? (
                <img 
                  src={article.author.avatar} 
                  alt={article.author.name}
                  className="w-12 h-12 rounded-full mr-4 border-2 border-white shadow-sm"
                />
              ) : (
                <div className="w-12 h-12 rounded-full bg-blue-100 border-2 border-white flex items-center justify-center mr-4 shadow-sm">
                  <UserIcon className="w-6 h-6 text-blue-600" />
                </div>
              )}
              <div>
                {/* <h3 className="font-semibold text-gray-900">{article.author.name}</h3> */}
                {article.author.title && (
                  <p className="text-sm text-gray-600">{article.author.title}</p>
                )}
                <div className="flex items-center text-sm text-gray-500 mt-1">
                  <CalendarDaysIcon className="w-4 h-4 mr-1" />
                  {formatDate(article.published_at)}
                  <span className="mx-2">•</span>
                  <ClockIcon className="w-4 h-4 mr-1" />
                  {getReadTimeText(article.read_time)}
                  <span className="mx-2">•</span>
                  <EyeIcon className="w-4 h-4 mr-1" />
                  {article.views?.toLocaleString() || 0} views
                </div>
              </div>
            </div>
            
            <div className="flex items-center space-x-3">
              <button 
                onClick={() => shareArticle('facebook')}
                className="p-2 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition"
                title="Share on Facebook"
              >
                <FacebookIcon className="w-5 h-5" />
              </button>
              <button 
                onClick={() => shareArticle('twitter')}
                className="p-2 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition"
                title="Share on Twitter"
              >
                <TwitterIcon className="w-5 h-5" />
              </button>
              <button 
                onClick={() => shareArticle('linkedin')}
                className="p-2 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition"
                title="Share on LinkedIn"
              >
                <LinkedinIcon className="w-5 h-5" />
              </button>
              <button 
                onClick={() => shareArticle('copy')}
                className="p-2 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition"
                title="Copy link"
              >
                <LinkIcon className="w-5 h-5" />
              </button>
              <button 
                className="p-2 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition"
                title="Save for later"
              >
                <BookmarkIcon className="w-5 h-5" />
              </button>
            </div>
          </div>
        </div>
      </section>

      {/* Main Content */}
      <section className="py-12 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {/* Article Content */}
            <div className="lg:col-span-3">
              {/* Featured Image */}
              <div className="mb-8 rounded-xl overflow-hidden shadow-lg">
                <img 
                  src={article.image || '/images/article-placeholder.jpg'} 
                  alt={article.title}
                  className="w-full h-auto max-h-[500px] object-cover"
                  onError={(e) => {
                    e.target.src = '/images/article-placeholder.jpg';
                  }}
                />
                {article.image_path && (
                  <div className="text-center py-2 bg-gray-50 text-xs text-gray-500">
                    Featured image for illustration purposes
                  </div>
                )}
              </div>
              
              {/* Article Content */}
              <div className="prose prose-lg max-w-none mb-12 article-content">
                <div dangerouslySetInnerHTML={renderContent()} />
              </div>
              
              {/* Tags */}
              {article.tags && article.tags.length > 0 && (
                <div className="mb-12 pt-8 border-t">
                  <h3 className="text-lg font-semibold text-gray-900 mb-4">Topics covered</h3>
                  <div className="flex flex-wrap gap-2">
                    {article.tags.map(tag => (
                      <Link
                        key={tag}
                        href={`/news?search=${encodeURIComponent(tag)}`}
                        className="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition"
                      >
                        #{tag.trim()}
                      </Link>
                    ))}
                  </div>
                </div>
              )}
              
              {/* Author Bio */}
              <div className="mb-12 p-6 bg-gray-50 rounded-xl">
                <div className="flex items-start">
                  
                  <div className="flex-1">
                   
                    {article.author.social_links && Object.keys(article.author.social_links).length > 0 && (
                      <div className="flex space-x-3">
                        {article.author.social_links.twitter && (
                          <a 
                            href={article.author.social_links.twitter} 
                            target="_blank" 
                            rel="noopener noreferrer"
                            className="text-gray-400 hover:text-blue-400"
                          >
                            <TwitterIcon className="w-5 h-5" />
                          </a>
                        )}
                        {article.author.social_links.linkedin && (
                          <a 
                            href={article.author.social_links.linkedin} 
                            target="_blank" 
                            rel="noopener noreferrer"
                            className="text-gray-400 hover:text-blue-700"
                          >
                            <LinkedinIcon className="w-5 h-5" />
                          </a>
                        )}
                      </div>
                    )}
                  </div>
                </div>
              </div>
              
              {/* Share Section */}
              <div className="mb-12 p-6 bg-blue-50 rounded-xl">
                <div className="flex flex-col md:flex-row md:items-center justify-between">
                  <div className="mb-4 md:mb-0">
                    <h3 className="text-lg font-semibold text-gray-900 mb-2">Found this article helpful?</h3>
                    <p className="text-gray-600">Share it with your network</p>
                  </div>
                  <div className="flex space-x-3">
                    <button 
                      onClick={() => shareArticle('facebook')}
                      className="flex items-center px-4 py-2 bg-[#1877F2] text-white rounded-lg hover:bg-[#166FE5] transition"
                    >
                      <FacebookIcon className="w-5 h-5 mr-2" />
                      Share
                    </button>
                    <button 
                      onClick={() => shareArticle('twitter')}
                      className="flex items-center px-4 py-2 bg-[#1DA1F2] text-white rounded-lg hover:bg-[#1A91DA] transition"
                    >
                      <TwitterIcon className="w-5 h-5 mr-2" />
                      Tweet
                    </button>
                    <button 
                      onClick={() => shareArticle('linkedin')}
                      className="flex items-center px-4 py-2 bg-[#0077B5] text-white rounded-lg hover:bg-[#006699] transition"
                    >
                      <LinkedinIcon className="w-5 h-5 mr-2" />
                      Share
                    </button>
                  </div>
                </div>
              </div>
              
              {/* Article Navigation */}
              <div className="border-t pt-8 mb-12">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  {navigation.prev && (
                    <Link 
                      href={`/news/${navigation.prev.slug}`}
                      className="group p-6 border rounded-xl hover:border-blue-300 hover:bg-blue-50 transition"
                    >
                      <div className="flex items-center text-blue-600 text-sm font-medium mb-2">
                        <ArrowLeftIcon className="w-4 h-4 mr-2" />
                        Previous Article
                      </div>
                      <h4 className="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition mb-2">
                        {navigation.prev.title}
                      </h4>
                      <p className="text-sm text-gray-500">{navigation.prev.category}</p>
                    </Link>
                  )}
                  
                  {navigation.next && (
                    <Link 
                      href={`/news/${navigation.next.slug}`}
                      className="group p-6 border rounded-xl hover:border-blue-300 hover:bg-blue-50 transition text-right"
                    >
                      <div className="flex items-center justify-end text-blue-600 text-sm font-medium mb-2">
                        Next Article
                        <ArrowRightIcon className="w-4 h-4 ml-2" />
                      </div>
                      <h4 className="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition mb-2">
                        {navigation.next.title}
                      </h4>
                      <p className="text-sm text-gray-500">{navigation.next.category}</p>
                    </Link>
                  )}
                </div>
              </div>
              
              {/* Related Articles */}
              {relatedArticles.length > 0 && (
                <div className="mb-12">
                  <h2 className="text-2xl font-bold text-gray-900 mb-6">Related Articles</h2>
                  <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {relatedArticles.map(related => (
                      <Link 
                        key={related.id}
                        href={`/news/${related.slug}`}
                        className="group border rounded-xl overflow-hidden hover:shadow-lg transition"
                      >
                        <div className="relative h-48">
                          <img 
                            src={related.image || '/images/article-placeholder.jpg'} 
                            alt={related.title}
                            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                          />
                          <div className="absolute top-4 left-4">
                            <span className="px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-sm font-medium">
                              {related.category}
                            </span>
                          </div>
                        </div>
                        <div className="p-6">
                          <div className="flex items-center text-sm text-gray-500 mb-3">
                            <CalendarDaysIcon className="w-4 h-4 mr-1" />
                            {formatDate(related.published_at)}
                          </div>
                          <h4 className="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition mb-3 line-clamp-2">
                            {related.title}
                          </h4>
                          <p className="text-gray-600 text-sm line-clamp-2 mb-4">
                            {related.excerpt}
                          </p>
                          <span className="text-blue-600 text-sm font-medium group-hover:text-blue-800">
                            Read more →
                          </span>
                        </div>
                      </Link>
                    ))}
                  </div>
                </div>
              )}
            </div>

            {/* Sidebar */}
            <div className="lg:col-span-1">
              {/* Table of Contents (dynamically generated) */}
              <div className="bg-white rounded-xl border p-6 mb-8 sticky top-6">
                <div id="table-of-contents"></div>
              </div>

              {/* Popular Articles */}
              {popularArticles.length > 0 && (
                <div className="bg-white rounded-xl border p-6 mb-8">
                  <h4 className="text-lg font-semibold text-gray-900 mb-4">Most Read</h4>
                  <div className="space-y-4">
                    {popularArticles.map((popular, index) => (
                      <div key={popular.id} className="flex items-start space-x-3">
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
                            href={`/news/${popular.slug}`}
                            className="text-sm font-medium text-gray-900 hover:text-blue-600 line-clamp-2"
                          >
                            {popular.title}
                          </Link>
                          <div className="flex items-center text-xs text-gray-500 mt-1">
                            <EyeIcon className="w-3 h-3 mr-1" />
                            {popular.views?.toLocaleString() || 0} views
                          </div>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {/* Recent Articles */}
              {recentArticles.length > 0 && (
                <div className="bg-white rounded-xl border p-6 mb-8">
                  <h4 className="text-lg font-semibold text-gray-900 mb-4">Recent Articles</h4>
                  <div className="space-y-4">
                    {recentArticles.map(recent => (
                      <div key={recent.id} className="flex items-start space-x-3">
                        <div className="flex-shrink-0">
                          <img 
                            src={recent.image || '/images/article-placeholder.jpg'} 
                            alt={recent.title}
                            className="w-12 h-12 rounded object-cover"
                          />
                        </div>
                        <div>
                          <Link 
                            href={`/news/${recent.slug}`}
                            className="text-sm font-medium text-gray-900 hover:text-blue-600 line-clamp-2"
                          >
                            {recent.title}
                          </Link>
                          <div className="flex items-center text-xs text-gray-500 mt-1">
                            <CalendarDaysIcon className="w-3 h-3 mr-1" />
                            {formatDate(recent.published_at)}
                          </div>
                        </div>
                      </div>
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