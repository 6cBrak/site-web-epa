<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actualite extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_fr', 'title_en', 'slug', 'image',
        'excerpt_fr', 'excerpt_en', 'content_fr', 'content_en',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
