<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image_path',
        'article_category_id',
        'author_id',
        'is_featured',
        'status',
        'published_at',
        'read_time',
        'tags',
        'meta_title',
        'meta_description',
        'views',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        // Make sure this matches your foreign key
        return $this->belongsTo(ArticleCategory::class, 'article_category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path 
            ? asset('storage/' . $this->image_path)
            : asset('images/default-article.jpg');
    }

    public function getTagsArrayAttribute()
    {
        return $this->tags ? explode(',', $this->tags) : [];
    }
}

