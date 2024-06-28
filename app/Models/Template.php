<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Template extends Model
{
    use HasFactory;

    protected $fillable = ['template', 'name']; 

    protected $casts = [
        'template' => 'array', 
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
