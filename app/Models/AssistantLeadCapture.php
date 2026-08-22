<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AssistantLeadCapture extends Model
{
    protected $table = 'assistant_leads_captures';

    public $timestamps = false;

    protected $fillable = [
        'conversation_id', 'name', 'contact', 'formation_interest', 'notes', 'priority', 'captured_at', 'status',
    ];

    protected $casts = [
        'captured_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(AssistantConversation::class, 'conversation_id');
    }

    public function candidature(): HasOne
    {
        return $this->hasOne(Candidature::class);
    }
}
