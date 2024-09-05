<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrashScheduleByAddress extends Model
{
    use HasFactory;

    protected $table = 'trash_schedules_by_address';

    protected $fillable = [
        'sam_address_id',
        'full_address',
        'mailing_neighborhood',
        'state',
        'zip_code',
        'x_coord',
        'y_coord',
        'recollect',
        'trashday',
        'pwd_district',
    ];
}
