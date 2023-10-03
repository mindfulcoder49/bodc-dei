<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Prediction;
use App\Models\ThreeOneOneCase;

class MlModel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'ml_model_name', 'ml_model_type', 'ml_model_date',
    ];

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class, 'ml_model_id', 'id');
    }

    public function getPredictionCountAttribute(): int
    {
        return $this->predictions()->count();
    }

    //function to check model accuracy
    public function getAccuracyAttribute(): array
    {
        $predictions = $this->predictions()
        ->select(['id', 'case_enquiry_id', 'prediction', 'prediction_date', 'prediction', 'three_one_one_case_id'])
        ->with(['threeoneonecase' => function ($query) {
            $query->select(['id', 'open_dt', 'closed_dt']);
        }])
        ->get();

        $csvRow = "open_dt,closed_dt,prediction,survival_time,prediction_timespan_start,prediction_timespan_end\n";
        $firstcorrect = 0;
        $secondcorrect = 0;
        $total = 0; 
        $caseList = [];
        foreach ($predictions as $prediction) {
            $case = $prediction->threeoneonecase;
            if ($case->closed_dt == null) {
                continue;
            } elseif (in_array($prediction->three_one_one_case_id, $caseList)) {
                continue;
            }
            $survivalTime = $prediction->threeoneonecase->getSurvivalTimeAttribute();
            $predictionTimespans = $prediction->getPredictionTimespanAttribute();

            $top_span = $predictionTimespans[0];
            $second_span = $predictionTimespans[1];

            if ($survivalTime >= $top_span[0] && $survivalTime < $top_span[1]) {
                
                $firstcorrect++;
            } 
            elseif ($survivalTime >= $second_span[0] && $survivalTime < $second_span[1]) {
                   $secondcorrect++;
            }
             else 
            {
                $csvRow .= implode(' ', [$case->open_dt, $case->closed_dt, $prediction->prediction, $survivalTime, $top_span[0], $top_span[1], $second_span[0], $second_span[1]]);
                $csvRow .= "\n";

            }
            $caseList[] = $prediction->three_one_one_case_id;
            $total++;
        }
        return $total === 0 ? ['firstcorrect' => 0, 'secondcorrect' => 0, 'total' => 0, 'firstaccuracy' => 0, 'secondaccuracy' => 0] : ['firstcorrect' => $firstcorrect, 'secondcorrect' => $secondcorrect, 'total' => $total, 'firstaccuracy' => $firstcorrect / $total, 'secondaccuracy' => $secondcorrect / $total];
    }
}
