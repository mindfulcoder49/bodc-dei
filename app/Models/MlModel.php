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

    public function getAccuracyAttribute(): array
    {
        $predictions = $this->predictions()
            ->select(['id', 'case_enquiry_id', 'prediction', 'prediction_date', 'prediction', 'ml_model_name', 'three_one_one_case_id'])
            ->with(['threeoneonecase' => function ($query) {
                $query->select(['id', 'open_dt', 'closed_dt']);
            }])
            ->get();

        $csvRow = "open_dt,closed_dt,prediction,survival_time,prediction_timespan_start,prediction_timespan_end\n";
        $firstcorrect = 0;
        $secondcorrect = 0;
        $thirdcorrect = 0;
        $total = 0; 
        $caseList = [];

        // Initialize empty array for accuracyspread
        $accuracyspread = [];

        foreach ($predictions as $prediction) {
            $case = $prediction->threeoneonecase;
            if (in_array($prediction->three_one_one_case_id, $caseList)) {
                continue;
            }
            $survivalTime = $prediction->threeoneonecase->getSurvivalTimeAttribute();
            $predictionTimespans = $prediction->getPredictionTimespanAttribute();

            $top_span = $predictionTimespans[0];
            $top_span_key = $top_span[0]/24 . '-' . floor($top_span[1]/24) . ' days';

            if (!isset($accuracyspread[$top_span_key])) {
                $accuracyspread[$top_span_key] = ['correct' => 0, 'total' => 0, 'maybe' => 0];
            }

            $accuracyspread[$top_span_key]['total']++;

            $second_span = $predictionTimespans[1];
            $third_span = $predictionTimespans[2];

            // Your existing conditions for checking correctness
            if ($case->closed_dt != null) {
                if ($survivalTime >= $top_span[0] && $survivalTime < $top_span[1]) {
                    $firstcorrect++;
                    $accuracyspread[$top_span_key]['correct']++;
                } 
                elseif ($survivalTime >= $second_span[0] && $survivalTime < $second_span[1]) {
                    $secondcorrect++;
                }
                elseif ($survivalTime >= $third_span[0] && $survivalTime < $third_span[1]) {
                    $thirdcorrect++;
                }
                else {
                    $csvRow .= implode(' ', [$case->open_dt, $case->closed_dt, $prediction->prediction, $survivalTime, $top_span[0], $top_span[1], $second_span[0], $second_span[1]]);
                    $csvRow .= "\n";
                }
            } else {
                if ($survivalTime < $top_span[1]) {
                    $firstcorrect++;
                    $accuracyspread[$top_span_key]['maybe']++;
                } elseif ($survivalTime < $second_span[1]) {
                    $secondcorrect++;
                }
                elseif ($survivalTime < $third_span[1]) {
                    $thirdcorrect++;
                }
                else  {
                $csvRow .= implode(' ', [$case->open_dt, $case->closed_dt, $prediction->prediction, $survivalTime, $top_span[0], $top_span[1], $second_span[0], $second_span[1]]);
                $csvRow .= "\n";
                }
            }

            $caseList[] = $prediction->three_one_one_case_id;
            $total++;
        }

        // Calculate accuracy for each span
        foreach ($accuracyspread as $key => $data) {
            $accuracyspread[$key]['accuracy'] = $data['total'] == 0 ? 0 : ($data['correct'] + $data['maybe']) / $data['total'];
        }

        return [
            'firstcorrect' => $firstcorrect,
            'secondcorrect' => $secondcorrect,
            'thirdcorrect' => $thirdcorrect,
            'total' => $total,
            'firstaccuracy' => $firstcorrect / $total,
            'secondaccuracy' => $secondcorrect / $total,
            'thirdaccuracy' => $thirdcorrect / $total,
            'accuracyspread' => $accuracyspread
        ];
    }
}
