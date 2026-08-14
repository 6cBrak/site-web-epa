<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'role_fr', 'role_en', 'bio_fr', 'bio_en',
        'photo', 'order', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
