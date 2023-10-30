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

    // Add this method to your Prediction model
    protected $appends = ['predictionTimespan', 'predictionMaxThree'];

    public function getPredictionMaxThreeAttribute(): array
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
        $prediction[$max2_index] = 0;
        $max3 = max($prediction);
        $max3_index = array_search($max3, $prediction);

        return [$max, $max2, $max3];
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
        $prediction[$max2_index] = 0;
        $max3 = max($prediction);
        $max3_index = array_search($max3, $prediction);

        //get prediction timespans
        $prediction_timespans = [
            "0-2 days" => [0, 72],
            "3-5 days" => [72, 144],
            "6-8 days" => [144, 216],
            "9-11 days" => [216, 288],
            "12-14 days" => [288, 360],
            "15-17 days" => [360, 432],
            "18-20 days" => [432, 504],
            "21-23 days" => [504, 576],
            "24-26 days" => [576, 648],
            "27-29 days" => [648, 720],
            "30-32 days" => [720, 792],
            "33-35 days" => [792, 864],
            "36-38 days" => [864, 936],
            "39-41 days" => [936, 1008],
            "42-44 days" => [1008, 1080],
            "45-47 days" => [1080, 1152],
            "48-50 days" => [1152, 1224],
            "51-53 days" => [1224, 1296],
            "54-56 days" => [1296, 1368],
            "57-59 days" => [1368, 1440],
            "60-62 days" => [1440, 1512],
            "63-65 days" => [1512, 1584],
            "66-68 days" => [1584, 1656],
            "69-71 days" => [1656, 1728],
            "72-74 days" => [1728, 1800],
            "75-77 days" => [1800, 1872],
            "78-80 days" => [1872, 1944],
            "81-83 days" => [1944, 2016],
            "84-86 days" => [2016, 2088],
            "87-89 days" => [2088, 2160],
            "90-92 days" => [2160, 2232],
            "93-95 days" => [2232, 2304],
            "96-98 days" => [2304, 2376],
            "99-101 days" => [2376, 2448],
            "102-104 days" => [2448, 2520],
            "105-107 days" => [2520, 2592],
            "108-110 days" => [2592, 2664],
            "111-113 days" => [2664, 2736],
            "114-116 days" => [2736, 2808],
            "117-119 days" => [2808, 2880],
            "120-122 days" => [2880, 2952],
            "123-125 days" => [2952, 3024],
            "126-128 days" => [3024, 3096],
            "129-131 days" => [3096, 3168],
            "132-134 days" => [3168, 3240],
            "135-137 days" => [3240, 3312],
            "138-140 days" => [3312, 3384],
            "141-143 days" => [3384, 3456],
            "144-146 days" => [3456, 3528],
            "147-149 days" => [3528, 3600],
            "150-152 days" => [3600, 3672],
            "153-155 days" => [3672, 3744],
            "156-158 days" => [3744, 3816],
            "159-161 days" => [3816, 3888],
            "162-164 days" => [3888, 3960],
            "165-167 days" => [3960, 4032],
            "168-170 days" => [4032, 4104],
            "171-173 days" => [4104, 4176],
            "174-176 days" => [4176, 4248],
            "177-179 days" => [4248, 4320],
            "180+ days" => [4320, 1000000],
        ];
        if ($this->ml_model_name == "20231029_200207_Boston311KerasNLP") {
            $prediction_timespans = [
                "0.0-1.0 days" => [0, 24],
                "1.0-2.0 days" => [24, 48],
                "2.0-3.0 days" => [48, 72],
                "3.0-4.0 days" => [72, 96],
                "4.0-5.0 days" => [96, 120],
                "5.0-6.0 days" => [120, 144],
                "6.0-7.0 days" => [144, 168],
                "7.0-8.0 days" => [168, 192],
                "8.0-9.0 days" => [192, 216],
                "9.0-10.0 days" => [216, 240],
                "10.0-11.0 days" => [240, 264],
                "11.0-12.0 days" => [264, 288],
                "12.0-13.0 days" => [288, 312],
                "13.0-14.0 days" => [312, 336],
                "14.0-21.0 days" => [336, 504],
                "21.0-28.0 days" => [504, 672],
                "28.0-35.0 days" => [672, 840],
                "35.0-42.0 days" => [840, 1008],
                "42.0-49.0 days" => [1008, 1176],
                "49.0-56.0 days" => [1176, 1344],
                "56.0-63.0 days" => [1344, 1512],
                "63.0-70.0 days" => [1512, 1680],
                "70.0-80.0 days" => [1680, 1920],
                "80.0-90.0 days" => [1920, 2160],
                "90.0-100.0 days" => [2160, 2400],
                "100.0-120.0 days" => [2400, 2880],
                "120.0-160.0 days" => [2880, 3840],
                "160.0-180.0 days" => [3840, 4320],
                "180.0-365.0 days" => [4320, 8760],
                "365.0-712.0 days" => [8760, 17088],
                "712+ days" => [17088, 1000000],
            ];
        }
        
        
        
        
        $prediction_timespan_array = array_values($prediction_timespans);
        //get prediction timespan
        $prediction_timespan_first = $prediction_timespan_array[$max_index];
        $prediction_timespan_second = $prediction_timespan_array[$max2_index];  
        $prediction_timespan_third = $prediction_timespan_array[$max3_index];
        //return prediction timespan
        return [$prediction_timespan_first, $prediction_timespan_second, $prediction_timespan_third];
    }
}
