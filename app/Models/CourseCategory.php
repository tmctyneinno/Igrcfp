<?php

// app/Models/CourseCategory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon', 'sort_order', 'is_active'];
    
   
    public function courses()
    {
        return $this->hasMany(Course::class, 'category_id'); // Specify the foreign key
    }
}