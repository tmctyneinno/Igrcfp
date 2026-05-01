<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseCatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query()
            ->with('category')
            ->withCount('modules')
            ->published();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($courseQuery) use ($search) {
                $courseQuery
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('short_title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('full_description', 'like', "%{$search}%")
                    ->orWhere('igrcfp_category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        if ($request->filled('level')) {
            $query->where('level', $request->string('level')->toString());
        }

        if ($request->filled('price_type')) {
            match ($request->string('price_type')->toString()) {
                'free' => $query->where('price', 0),
                'paid' => $query->where('price', '>', 0),
                'discounted' => $query->whereNotNull('discount_price')->where('discount_price', '>', 0),
                default => null,
            };
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->boolean('popular')) {
            $query->where('is_popular', true);
        }

        $sort = $request->string('sort', 'newest')->toString();

        match ($sort) {
            'title_asc' => $query->orderBy('title'),
            'title_desc' => $query->orderByDesc('title'),
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'oldest' => $query->orderBy('created_at'),
            default => $query->orderByDesc('created_at'),
        };

        $courses = $query
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Course $course) => [
                'id' => $course->id,
                'title' => $course->title,
                'short_title' => $course->short_title,
                'slug' => $course->slug,
                'description' => $course->short_description ?: $course->full_description,
                'short_description' => $course->short_description,
                'level' => $course->level,
                'format' => $course->format,
                'duration' => $course->duration,
                'modules_count' => $course->modules_count,
                'price' => $course->price,
                'discount_price' => $course->discount_price,
                'image_url' => $course->image_url,
                'igrcfp_category' => $course->igrcfp_category,
                'category' => $course->category ? [
                    'id' => $course->category->id,
                    'name' => $course->category->name,
                    'slug' => $course->category->slug,
                ] : null,
            ]);

        $categories = CourseCategory::active()
            ->ordered()
            ->get(['id', 'name', 'slug']);

        return Inertia::render('CourseCatalog/Index', [
            'courses' => $courses,
            'categories' => $categories,
            'filters' => $request->only([
                'search',
                'category',
                'level',
                'price_type',
                'featured',
                'popular',
                'sort',
            ]),
            'filterOptions' => [
                'levels' => ['Beginner', 'Intermediate', 'Advanced', 'Expert'],
                'priceTypes' => [
                    ['value' => 'free', 'label' => 'Free'],
                    ['value' => 'paid', 'label' => 'Paid'],
                    ['value' => 'discounted', 'label' => 'Discounted'],
                ],
                'sortOptions' => [
                    ['value' => 'newest', 'label' => 'Newest First'],
                    ['value' => 'oldest', 'label' => 'Oldest First'],
                    ['value' => 'title_asc', 'label' => 'Title: A to Z'],
                    ['value' => 'title_desc', 'label' => 'Title: Z to A'],
                    ['value' => 'price_asc', 'label' => 'Price: Low to High'],
                    ['value' => 'price_desc', 'label' => 'Price: High to Low'],
                ],
            ],
        ]);
    }
}
