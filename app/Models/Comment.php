<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'actualite_id', 'author_name', 'author_email', 'body', 'approved',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];

    public function actualite(): BelongsTo
    {
        return $this->belongsTo(Actualite::class);
    }
}
