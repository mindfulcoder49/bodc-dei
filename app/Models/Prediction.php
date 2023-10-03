<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\MlModel;
use App\Models\ThreeOneOneCase;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_enquiry_id', 'ml_model_id', 'ml_model_name', 'prediction', 'prediction_date',
    ];

    public function threeoneonecase(): BelongsTo
    {
        return $this->belongsTo(ThreeOneOneCase::class, 'three_one_one_case_id', 'id');
    }

    public function mlmodel(): BelongsTo
    {
        return $this->belongsTo(MlModel::class, 'ml_model_id', 'id');
    }

    //define function to return prediction timespan
    public function getPredictionTimespanAttribute(): array
    {
        //explode on space
        $prediction = explode(" ", $this->prediction);
        //convert each element to a float
        $prediction = array_map('floatval', $prediction);

        //find the index of the max value and 2nd max value
        $max = max($prediction);
        $max_index = array_search($max, $prediction);
        $prediction[$max_index] = 0;
        $max2 = max($prediction);
        $max2_index = array_search($max2, $prediction);

        //get prediction timespans
        $prediction_timespans = [
            "0-12 hours" => [0, 12],
            "12-24 hours" => [12, 24],
            "1-3 days" => [24, 72],
            "4-7 days" => [96, 168],
            "1-2 weeks" => [168, 336],
            "2-4 weeks" => [336, 672],
            "1-2 months" => [672, 1344],
            "2-4 months" => [1344, 2688],
            "4+ months" => [2688, 1000000]
        ];
        
        $prediction_timespan_array = array_values($prediction_timespans);
        //get prediction timespan
        $prediction_timespan_first = $prediction_timespan_array[$max_index];
        $prediction_timespan_second = $prediction_timespan_array[$max2_index];
        //return prediction timespan
        return [$prediction_timespan_first, $prediction_timespan_second];
    }
}
