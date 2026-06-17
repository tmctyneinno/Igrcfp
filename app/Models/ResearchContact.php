<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'title', 'organisation', 'email', 'document_id', 'document_title'
    ];
}