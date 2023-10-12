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

        return [$max_index, $max2_index, $max3_index];
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
            "0-24 hours" => [0, 24],
            "1-2 days" => [24, 48],
            "2-3 days" => [48, 72],
            "3-4 days" => [72, 96],
            "4-5 days" => [96, 120],
            "5-6 days" => [120, 144],
            "6-7 days" => [144, 168],
            "7-8 days" => [168, 192],
            "8-9 days" => [192, 216],
            "9-10 days" => [216, 240],
            "10-11 days" => [240, 264],
            "11-12 days" => [264, 288],
            "12-13 days" => [288, 312],
            "13-14 days" => [312, 336],
            "14-15 days" => [336, 360],
            "15-16 days" => [360, 384],
            "16-17 days" => [384, 408],
            "17-18 days" => [408, 432],
            "18-19 days" => [432, 456],
            "19-20 days" => [456, 480],
            "20-21 days" => [480, 504],
            "21-22 days" => [504, 528],
            "22-23 days" => [528, 552],
            "23-24 days" => [552, 576],
            "24-25 days" => [576, 600],
            "25-26 days" => [600, 624],
            "26-27 days" => [624, 648],
            "27-28 days" => [648, 672],
            "28-29 days" => [672, 696],
            "29-30 days" => [696, 720],
            "30-31 days" => [720, 744],
            "31-32 days" => [744, 768],
            "32-33 days" => [768, 792],
            "33-34 days" => [792, 816],
            "34-35 days" => [816, 840],
            "35-36 days" => [840, 864],
            "36-37 days" => [864, 888],
            "37-38 days" => [888, 912],
            "38-39 days" => [912, 936],
            "39-40 days" => [936, 960],
            "40-41 days" => [960, 984],
            "41-42 days" => [984, 1008],
            "42-43 days" => [1008, 1032],
            "43-44 days" => [1032, 1056],
            "44-45 days" => [1056, 1080],
            "45-46 days" => [1080, 1104],
            "46-47 days" => [1104, 1128],
            "47-48 days" => [1128, 1152],
            "48-49 days" => [1152, 1176],
            "49-50 days" => [1176, 1200],
            "50-51 days" => [1200, 1224],
            "51-52 days" => [1224, 1248],
            "52-53 days" => [1248, 1272],
            "53-54 days" => [1272, 1296],
            "54-55 days" => [1296, 1320],
            "55-56 days" => [1320, 1344],
            "56-57 days" => [1344, 1368],
            "57-58 days" => [1368, 1392],
            "58-59 days" => [1392, 1416],
            "59-60 days" => [1416, 1440],
            "60-61 days" => [1440, 1464],
            "61-62 days" => [1464, 1488],
            "62-63 days" => [1488, 1512],
            "63-64 days" => [1512, 1536],
            "64-65 days" => [1536, 1560],
            "65-66 days" => [1560, 1584],
            "66-67 days" => [1584, 1608],
            "67-68 days" => [1608, 1632],
            "68-69 days" => [1632, 1656],
            "69-70 days" => [1656, 1680],
            "70-71 days" => [1680, 1704],
            "71-72 days" => [1704, 1728],
            "72-73 days" => [1728, 1752],
            "73-74 days" => [1752, 1776],
            "74-75 days" => [1776, 1800],
            "75-76 days" => [1800, 1824],
            "76-77 days" => [1824, 1848],
            "77-78 days" => [1848, 1872],
            "78-79 days" => [1872, 1896],
            "79-80 days" => [1896, 1920],
            "80-81 days" => [1920, 1944],
            "81-82 days" => [1944, 1968],
            "82-83 days" => [1968, 1992],
            "83-84 days" => [1992, 2016],
            "84-85 days" => [2016, 2040],
            "85-86 days" => [2040, 2064],
            "86-87 days" => [2064, 2088],
            "87-88 days" => [2088, 2112],
            "88-89 days" => [2112, 2136],
            "89-90 days" => [2136, 2160],
            "90-91 days" => [2160, 2184],
            "91-92 days" => [2184, 2208],
            "92-93 days" => [2208, 2232],
            "93-94 days" => [2232, 2256],
            "94-95 days" => [2256, 2280],
            "95-96 days" => [2280, 2304],
            "96-97 days" => [2304, 2328],
            "97-98 days" => [2328, 2352],
            "98-99 days" => [2352, 2376],
            "99-100 days" => [2376, 2400],
            "100-101 days" => [2400, 2424],
            "101-102 days" => [2424, 2448],
            "102-103 days" => [2448, 2472],
            "103-104 days" => [2472, 2496],
            "104-105 days" => [2496, 2520],
            "105-106 days" => [2520, 2544],
            "106-107 days" => [2544, 2568],
            "107-108 days" => [2568, 2592],
            "108-109 days" => [2592, 2616],
            "109-110 days" => [2616, 2640],
            "110-111 days" => [2640, 2664],
            "111-112 days" => [2664, 2688],
            "112-113 days" => [2688, 2712],
            "113-114 days" => [2712, 2736],
            "114-115 days" => [2736, 2760],
            "115-116 days" => [2760, 2784],
            "116-117 days" => [2784, 2808],
            "117-118 days" => [2808, 2832],
            "118-119 days" => [2832, 2856],
            "119-120 days" => [2856, 2880],
            "120-121 days" => [2880, 2904],
            "121-122 days" => [2904, 2928],
            "122-123 days" => [2928, 2952],
            "123-124 days" => [2952, 2976],
            "124-125 days" => [2976, 3000],
            "125-126 days" => [3000, 3024],
            "126-127 days" => [3024, 3048],
            "127-128 days" => [3048, 3072],
            "128-129 days" => [3072, 3096],
            "129-130 days" => [3096, 3120],
            "130-131 days" => [3120, 3144],
            "131-132 days" => [3144, 3168],
            "132-133 days" => [3168, 3192],
            "133-134 days" => [3192, 3216],
            "134-135 days" => [3216, 3240],
            "135-136 days" => [3240, 3264],
            "136-137 days" => [3264, 3288],
            "137-138 days" => [3288, 3312],
            "138-139 days" => [3312, 3336],
            "139-140 days" => [3336, 3360],
            "140-141 days" => [3360, 3384],
            "141-142 days" => [3384, 3408],
            "142-143 days" => [3408, 3432],
            "143-144 days" => [3432, 3456],
            "144-145 days" => [3456, 3480],
            "145-146 days" => [3480, 3504],
            "146-147 days" => [3504, 3528],
            "147-148 days" => [3528, 3552],
            "148-149 days" => [3552, 3576],
            "149-150 days" => [3576, 3600],
            "150-151 days" => [3600, 3624],
            "151-152 days" => [3624, 3648],
            "152-153 days" => [3648, 3672],
            "153-154 days" => [3672, 3696],
            "154-155 days" => [3696, 3720],
            "155-156 days" => [3720, 3744],
            "156-157 days" => [3744, 3768],
            "157-158 days" => [3768, 3792],
            "158-159 days" => [3792, 3816],
            "159-160 days" => [3816, 3840],
            "160-161 days" => [3840, 3864],
            "161-162 days" => [3864, 3888],
            "162-163 days" => [3888, 3912],
            "163-164 days" => [3912, 3936],
            "164-165 days" => [3936, 3960],
            "165-166 days" => [3960, 3984],
            "166-167 days" => [3984, 4008],
            "167-168 days" => [4008, 4032],
            "168-169 days" => [4032, 4056],
            "169-170 days" => [4056, 4080],
            "170-171 days" => [4080, 4104],
            "171-172 days" => [4104, 4128],
            "172-173 days" => [4128, 4152],
            "173-174 days" => [4152, 4176],
            "174-175 days" => [4176, 4200],
            "175-176 days" => [4200, 4224],
            "176-177 days" => [4224, 4248],
            "177-178 days" => [4248, 4272],
            "178-179 days" => [4272, 4296],
            "179-180 days" => [4296, 4320],
            "180+ days" => [4320, 1000000],
        ];
        
        
        $prediction_timespan_array = array_values($prediction_timespans);
        //get prediction timespan
        $prediction_timespan_first = $prediction_timespan_array[$max_index];
        $prediction_timespan_second = $prediction_timespan_array[$max2_index];  
        $prediction_timespan_third = $prediction_timespan_array[$max3_index];
        //return prediction timespan
        return [$prediction_timespan_first, $prediction_timespan_second, $prediction_timespan_third];
    }
}
