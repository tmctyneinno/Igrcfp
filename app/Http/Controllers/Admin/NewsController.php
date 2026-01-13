<?php

namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    public function create()
    {
        $categories = ArticleCategory::where('is_active', true)->orderBy('name')->get();
        $authors = User::where('is_active', true)->orderBy('name')->get();
        $recentArticles = Article::with(['category', 'author'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.articles.create', compact('categories', 'authors', 'recentArticles'));
    }

    public function news(Request $request)
    {
        // Featured articles (promoted ones)
        $featuredArticles = Cache::remember('featured_articles', 3600, function () {
            return Article::with(['category', 'author'])
                ->where('is_featured', true)
                ->where('status', 'published')
                ->where('published_at', '<=', now())
                ->orderBy('published_at', 'desc')
                ->take(3)
                ->get()
                ->map(function ($article) {
                    return [
                        'id' => $article->id,
                        'title' => $article->title,
                        'slug' => $article->slug,
                        'excerpt' => $article->excerpt,
                        'image' => $article->image_url,
                        'category' => $article->category->name,
                        'category_slug' => $article->category->slug,
                        'author' => $article->author->name,
                        'author_title' => $article->author->title,
                        'published_at' => $article->published_at->toISOString(),
                        'read_time' => $article->read_time,
                        'tags' => $article->tags ? explode(',', $article->tags) : [],
                    ];
                });
        });

        // Latest articles with pagination
        $latestArticles = Article::with(['category', 'author'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(10)
            ->through(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'excerpt' => $article->excerpt,
                    'image' => $article->image_url,
                    'category' => $article->category->name,
                    'category_slug' => $article->category->slug,
                    'author' => $article->author->name,
                    'author_avatar' => $article->author->avatar_url,
                    'published_at' => $article->published_at->toISOString(),
                ];
            });

        // Categories with article count
        $categories = Cache::remember('categories_with_count', 7200, function () {
            return ArticleCategory::withCount(['articles' => function ($query) {
                $query->where('status', 'published')
                      ->where('published_at', '<=', now());
            }])
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'count' => $category->articles_count,
                ];
            });
        });

        // Popular tags
        $popularTags = Cache::remember('popular_tags', 10800, function () {
            return Article::where('status', 'published')
                ->where('published_at', '<=', now())
                ->whereNotNull('tags')
                ->pluck('tags')
                ->flatMap(function ($tags) {
                    return explode(',', $tags);
                })
                ->filter()
                ->map(fn($tag) => trim($tag))
                ->countBy()
                ->sortDesc()
                ->take(15)
                ->keys()
                ->toArray();
        });

        return Inertia::render('News/Index', [
            'title' => 'Industry News & Insights',
            'description' => 'Stay updated with the latest regulatory developments, industry trends, and thought leadership in governance, risk, compliance, and financial crime prevention. Our insights help professionals anticipate change, manage risk, and lead with confidence.',
            'featuredArticles' => $featuredArticles,
            'latestArticles' => $latestArticles,
            'categories' => $categories,
            'popularTags' => $popularTags,
            'filters' => $request->only(['category', 'search', 'year']),
        ]);
    }

    public function show(string $slug)
    {
        $article = Article::with(['category', 'author'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        // Increment view count
        $article->increment('views');

        // Related articles
        $relatedArticles = Article::with(['category', 'author'])
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get()
            ->map(function ($related) {
                return [
                    'id' => $related->id,
                    'title' => $related->title,
                    'slug' => $related->slug,
                    'excerpt' => $related->excerpt,
                    'image' => $related->image_url,
                    'category' => $related->category->name,
                    'published_at' => $related->published_at->toISOString(),
                    'read_time' => $related->read_time,
                ];
            });

        return Inertia::render('News/Show', [
            'title' => $article->title,
            'article' => [
                'id' => $article->id,
                'title' => $article->title,
                'content' => $article->content,
                'excerpt' => $article->excerpt,
                'image' => $article->image_url,
                'category' => $article->category->name,
                'category_slug' => $article->category->slug,
                'author' => [
                    'name' => $article->author->name,
                    'title' => $article->author->title,
                    'bio' => $article->author->bio,
                    'avatar' => $article->author->avatar_url,
                    'linkedin' => $article->author->linkedin_url,
                ],
                'published_at' => $article->published_at->toISOString(),
                'updated_at' => $article->updated_at->toISOString(),
                'read_time' => $article->read_time,
                'views' => $article->views,
                'tags' => $article->tags ? explode(',', $article->tags) : [],
                'meta_title' => $article->meta_title,
                'meta_description' => $article->meta_description,
            ],
            'relatedArticles' => $relatedArticles,
        ]);
    }

    public function category(string $slug)
    {
        $category = ArticleCategory::where('slug', $slug)->firstOrFail();

        $articles = Article::with(['category', 'author'])
            ->where('category_id', $category->id)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(12)
            ->through(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'excerpt' => $article->excerpt,
                    'image' => $article->image_url,
                    'category' => $article->category->name,
                    'author' => $article->author->name,
                    'published_at' => $article->published_at->toISOString(),
                    'read_time' => $article->read_time,
                ];
            });

        return Inertia::render('News/Category', [
            'title' => $category->name . ' News & Insights',
            'description' => $category->description,
            'category' => [
                'name' => $category->name,
                'description' => $category->description,
            ],
            'articles' => $articles,
        ]);
    }

    
}