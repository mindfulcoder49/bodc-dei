<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buildingpermits extends Model
{
    use HasFactory;

    protected $table = 'BuildingPermits';

    protected $fillable = [
        'permitnumber', 'worktype', 'permittypedescr', 'description', 'comments', 'applicant', 'declared_valuation', 'total_fees', 'issued_date', 'expiration_date', 'status', 'occupancytype', 'sq_feet', 'address', 'city', 'state', 'zip', 'property_id', 'parcel_id', 'gpsy', 'gpsx', 'y_latitude', 'x_longitude', 
    ];
}
