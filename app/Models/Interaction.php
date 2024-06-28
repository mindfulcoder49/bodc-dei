<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'prompt', 'completion', 'prompt_tokens', 'completion_tokens', 'prompt_token_price', 'completion_token_price', 'total_cost', 'model_name'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
