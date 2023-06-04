<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteProject extends Model
{
    protected $fillable = [
        'creation_date',
        'update_date',
        'title',
        'source'
    ];
}
