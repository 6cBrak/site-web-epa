<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssistantConversation extends Model
{
    protected $fillable = ['session_id'];

    public function messages(): HasMany
    {
        return $this->hasMany(AssistantMessage::class, 'conversation_id');
    }

    public function leadsCaptures(): HasMany
    {
        return $this->hasMany(AssistantLeadCapture::class, 'conversation_id');
    }
}
