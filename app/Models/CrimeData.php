<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrimeData extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow Laravel's naming conventions
    protected $table = 'crime_data';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'incident_number',
        'offense_code',
        'offense_code_group',
        'offense_description',
        'district',
        'reporting_area',
        'shooting',
        'occurred_on_date',
        'year',
        'month',
        'day_of_week',
        'hour',
        'ucr_part',
        'street',
        'lat',
        'long',
        'location',
        'offense_category',
    ];

    // Cast the occurred_on_date to a date type
    protected $casts = [
        'occurred_on_date' => 'datetime',
    ];
    
}
