<?php

namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $category = $request->get('category');
        $perPage = $request->get('per_page', 10);

        $query = Article::with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc');

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%")
                ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($status && in_array($status, ['draft', 'published', 'archived'])) {
            $query->where('status', $status);
        }

        // Apply category filter
        if ($category) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('id', $category);
            });
        }

        $articles = $query->paginate($perPage);

        // Get categories for filter dropdown
        $categories = ArticleCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.articles.index', compact(
            'articles', 
            'categories',
            'search', 
            'status', 
            'category',
            'perPage'
        ));
    }

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

    public function store(Request $request)
    {
        \Log::info('IP request:', [$request->all()]);
        $slug = Str::slug($request->title);
        
        // Ensure slug is unique
        $originalSlug = $slug;
        $counter = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Add slug to request data
        $request->merge(['slug' => $slug]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug',
            'excerpt' => 'required|string|max:300',
            'content' => 'required|string',
            'article_category_id' => 'required|exists:article_categories,id',
            'author_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'read_time' => 'required|integer|min:1|max:60',
            'tags' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $validated['image_path'] = $path;
        }

        // Process tags (remove extra spaces)
        if ($request->has('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $validated['tags'] = implode(',', $tags);
        }

        // Set default values
        $validated['is_featured'] = $request->boolean('is_featured');

        // If published status but no published_at date, set to now
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Set views to 0 for new article
        $validated['views'] = 0;

        Article::create($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        $categories = ArticleCategory::all(); 
        $authors = User::where('role', 'learner')->get();
        
        return view('admin.articles.edit', compact('article', 'categories', 'authors'));
    }

    public function update(Request $request, Article $article)
    {
        $slug = Str::slug($request->title);
        $request->merge(['slug' => $slug]);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string',
            'excerpt' => 'required|string|max:300',
            'content' => 'required|string',
            'article_category_id' => 'required|exists:article_categories,id',
            'author_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'read_time' => 'required|integer|min:1|max:60',
            'tags' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        // Handle image upload if new image provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($article->image_path) {
                Storage::delete($article->image_path);
            }
            
            $imagePath = $request->file('image')->store('articles', 'public');
            $validated['image_path'] = $imagePath;
        }
        
        $article->update($validated);
        
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article updated successfully.');
    }

    public function storeCategory(Request $request)
    {
        \Log::info(' storeCategory:', [$request]);
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:article_categories,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Generate slug from name
        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (ArticleCategory::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;
        $validated['is_active'] = $request->boolean('is_active', true);

        $category = ArticleCategory::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                ],
                'message' => 'Category created successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Category created successfully');
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

     public function news(Request $request)
    {
        // Get filter parameters
        $search = $request->input('search');
        $categorySlug = $request->input('category');
        $tag = $request->input('tag');
        $year = $request->input('year');

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

        // Build query for latest articles with pagination
        $articlesQuery = Article::with(['category', 'author'])
            ->where('status', 'published')
            ->where('published_at', '<=', now());

        // Apply search filter
        if ($search) {
            $articlesQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($categorySlug) {
            $articlesQuery->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            });
        }

        // Apply tag filter
        if ($tag) {
            $articlesQuery->where('tags', 'like', "%{$tag}%");
        }

        // Apply year filter
        if ($year) {
            $articlesQuery->whereYear('published_at', $year);
        }

        // Get available years for filter (for sidebar)
        $availableYears = Cache::remember('article_years', 7200, function () {
            return Article::selectRaw('YEAR(published_at) as year')
                ->where('status', 'published')
                ->where('published_at', '<=', now())
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->filter()
                ->values();
        });

        // Paginate results
        $perPage = $request->input('per_page', 9); // Changed to 9 for better grid layout
        $latestArticles = $articlesQuery
            ->orderBy('published_at', 'desc')
            ->paginate($perPage)
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
                    'author_avatar' => $article->author->avatar_url ?? null,
                    'author_title' => $article->author->title ?? null,
                    'published_at' => $article->published_at->toISOString(),
                    'read_time' => $article->read_time,
                    'tags' => $article->tags ? array_map('trim', explode(',', $article->tags)) : [],
                    'is_featured' => $article->is_featured,
                    'views' => $article->views,
                ];
            });

        // Categories with article count (considering current filters)
        $categoriesQuery = ArticleCategory::withCount(['articles' => function ($query) use ($search, $year) {
            $query->where('status', 'published')
                ->where('published_at', '<=', now());
            
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
                });
            }
            
            if ($year) {
                $query->whereYear('published_at', $year);
            }
        }]);

        $categories = $categoriesQuery
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

        // Popular tags (considering current filters)
        $tagsQuery = Article::where('status', 'published')
            ->where('published_at', '<=', now())
            ->whereNotNull('tags');

        if ($search) {
            $tagsQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($categorySlug) {
            $tagsQuery->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            });
        }

        if ($year) {
            $tagsQuery->whereYear('published_at', $year);
        }

        $popularTags = $tagsQuery
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

        // Most read articles (for sidebar)
        $mostReadArticles = Cache::remember('most_read_articles', 1800, function () {
            return Article::with(['category'])
                ->where('status', 'published')
                ->where('published_at', '<=', now())
                ->orderBy('views', 'desc')
                ->take(5)
                ->get()
                ->map(function ($article) {
                    return [
                        'id' => $article->id,
                        'title' => $article->title,
                        'slug' => $article->slug,
                        'category' => $article->category->name,
                        'views' => $article->views,
                        'published_at' => $article->published_at->toISOString(),
                    ];
                });
        });

        return Inertia::render('News/Index', [
            'title' => 'Industry News & Insights',
            'description' => 'Stay updated with the latest regulatory developments, industry trends, and thought leadership in governance, risk, compliance, and financial crime prevention. Our insights help professionals anticipate change, manage risk, and lead with confidence.',
            'featuredArticles' => $featuredArticles,
            'latestArticles' => $latestArticles,
            'categories' => $categories,
            'popularTags' => $popularTags,
            'mostReadArticles' => $mostReadArticles,
            'availableYears' => $availableYears,
            'filters' => $request->only(['category', 'search', 'tag', 'year', 'per_page']),
            'meta' => [
                'current_page' => $latestArticles->currentPage(),
                'last_page' => $latestArticles->lastPage(),
                'per_page' => $latestArticles->perPage(),
                'total' => $latestArticles->total(),
                'from' => $latestArticles->firstItem(),
                'to' => $latestArticles->lastItem(),
            ],
            'links' => [
                'first' => $latestArticles->url(1),
                'last' => $latestArticles->url($latestArticles->lastPage()),
                'prev' => $latestArticles->previousPageUrl(),
                'next' => $latestArticles->nextPageUrl(),
            ],
        ]);
    }

    public function showNews(Request $request, $slug)
    {
        $article = Article::with(['category', 'author'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        // Increment views
        $article->increment('views');

        // Get related articles (same category, exclude current)
        $relatedArticles = Cache::remember("related_articles_{$article->id}", 3600, function () use ($article) {
            return Article::with(['category'])
                ->where('article_category_id', $article->article_category_id)
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
                        'category_slug' => $related->category->slug,
                        'published_at' => $related->published_at->toISOString(),
                        'read_time' => $related->read_time,
                    ];
                });
        });

        // Get next article (newer)
        $nextArticle = Article::with(['category'])
            ->where('published_at', '>', $article->published_at)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'asc')
            ->first();

        // Get previous article (older)
        $prevArticle = Article::with(['category'])
            ->where('published_at', '<', $article->published_at)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->first();

        // Prepare article data for frontend
        $articleData = [
            'id' => $article->id,
            'title' => $article->title,
            'slug' => $article->slug,
            'content' => $article->content,
            'excerpt' => $article->excerpt,
            'image' => $article->image_url,
            'image_path' => $article->image_path,
            'category' => [
                'id' => $article->category->id,
                'name' => $article->category->name,
                'slug' => $article->category->slug,
            ],
            'author' => [
                'id' => $article->author->id,
                'name' => $article->author->name,
                'title' => $article->author->title,
                'bio' => $article->author->bio,
                'avatar' => $article->author->avatar_url,
                'social_links' => $article->author->social_links ?? [],
            ],
            'meta' => [
                'title' => $article->meta_title,
                'description' => $article->meta_description,
                'keywords' => $article->tags,
            ],
            'published_at' => $article->published_at->toISOString(),
            'updated_at' => $article->updated_at->toISOString(),
            'read_time' => $article->read_time,
            'tags' => $article->tags ? array_map('trim', explode(',', $article->tags)) : [],
            'views' => $article->views,
            'is_featured' => $article->is_featured,
            'status' => $article->status,
        ];

        // Navigation articles
        $navigation = [
            'next' => $nextArticle ? [
                'title' => $nextArticle->title,
                'slug' => $nextArticle->slug,
                'category' => $nextArticle->category->name,
            ] : null,
            'prev' => $prevArticle ? [
                'title' => $prevArticle->title,
                'slug' => $prevArticle->slug,
                'category' => $prevArticle->category->name,
            ] : null,
        ];

        // Get popular articles for sidebar
        $popularArticles = Cache::remember('popular_articles_sidebar', 1800, function () {
            return Article::with(['category'])
                ->where('status', 'published')
                ->where('published_at', '<=', now())
                ->orderBy('views', 'desc')
                ->take(5)
                ->get()
                ->map(function ($article) {
                    return [
                        'id' => $article->id,
                        'title' => $article->title,
                        'slug' => $article->slug,
                        'image' => $article->image_url,
                        'category' => $article->category->name,
                        'views' => $article->views,
                        'published_at' => $article->published_at->toISOString(),
                    ];
                });
        });

        // Get recent articles for sidebar
        $recentArticles = Cache::remember('recent_articles_sidebar', 1800, function () {
            return Article::with(['category'])
                ->where('status', 'published')
                ->where('published_at', '<=', now())
                ->orderBy('published_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($article) {
                    return [
                        'id' => $article->id,
                        'title' => $article->title,
                        'slug' => $article->slug,
                        'image' => $article->image_url,
                        'category' => $article->category->name,
                        'published_at' => $article->published_at->toISOString(),
                    ];
                });
        });

        return Inertia::render('News/Show', [
            'title' => $article->meta_title ?: $article->title,
            'description' => $article->meta_description ?: $article->excerpt,
            'keywords' => $article->tags,
            'article' => $articleData,
            'relatedArticles' => $relatedArticles,
            'popularArticles' => $popularArticles,
            'recentArticles' => $recentArticles,
            'navigation' => $navigation,
            'canonicalUrl' => route('news.show', $article->slug),
        ]);
    }

    /**
     * API endpoint to increment views (for real-time updates)
     */
    public function incrementViews($id)
    {
        $article = Article::findOrFail($id);
        $article->increment('views');
        
        return response()->json([
            'success' => true,
            'views' => $article->views,
        ]);
    }

    /**
     * Search articles for autocomplete
     */
    public function search(Request $request)
    {
        $search = $request->input('q', '');
        
        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $articles = Article::with(['category'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('excerpt', 'like', "%{$search}%")
                      ->orWhere('tags', 'like', "%{$search}%");
            })
            ->orderBy('published_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'excerpt' => $article->excerpt,
                    'category' => $article->category->name,
                    'published_at' => $article->published_at->toISOString(),
                ];
            });

        return response()->json($articles);
    }

    
}