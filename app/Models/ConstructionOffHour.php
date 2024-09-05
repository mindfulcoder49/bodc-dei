<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstructionOffHour extends Model
{
    use HasFactory;

    protected $table = 'construction_off_hours';

    protected $fillable = [
        'app_no',
        'start_datetime',
        'stop_datetime',
        'address',
        'ward',
    ];
}
