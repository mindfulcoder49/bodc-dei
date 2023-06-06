<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MlModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
    ];

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }
}
