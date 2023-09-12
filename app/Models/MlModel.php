<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MlModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'ml_model_name', 'ml_model_type', 'ml_model_date',
    ];

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class, 'ml_model_id', 'id');
    }
}
