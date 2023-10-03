<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThreeOneOneCase extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the primary key is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The data type of the auto-incrementing primary key.
     *
     * @var string
     */
    protected $keyType = 'int';

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    const SEARCHABLE_COLUMNS = [
        'id', 'case_enquiry_id', 'open_dt', 'sla_target_dt', 'closed_dt', 'on_time', 'case_status', 'closure_reason', 'case_title', 'subject', 'reason', 'type', 'queue', 'department', 'submitted_photo', 'closed_photo', 'location', 'fire_district', 'pwd_district', 'city_council_district', 'police_district', 'neighborhood', 'neighborhood_services_district', 'ward', 'precinct', 'location_street_name', 'location_zipcode', 'latitude', 'longitude', 'source', 'ward_number',
    ];
    
    //function to check case survival time
    public function getSurvivalTimeAttribute(): float
    {
        //get case open date
        $openDate = $this->open_dt;
        //get case close date
        $closeDate = $this->closed_dt;
        //check if closed date is null
        if ($closeDate == null) {
            //set to tomorrow
            $closeDate = date('Y-m-d', strtotime('+1 day'));
        }
        //calculate difference between open and close date
        $diff = abs(strtotime($closeDate) - strtotime($openDate));
        
        //return in hours
        return $diff / (60 * 60);
    }
}
