<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'prediction', 'prediction_date',
    ];

    public function threeoneonecase(): BelongsTo
    {
        return $this->belongsTo(ThreeOneOneCase::class, 'case_enquiry_id', 'case_enquiry_id');
    }

    public function mlmodel(): BelongsTo
    {
        return $this->belongsTo(MlModel::class, 'ml_model_id', 'id');
    }
}
