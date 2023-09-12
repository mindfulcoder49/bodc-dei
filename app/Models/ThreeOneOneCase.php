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
        'id', 'case_enquiry_id', 'open_dt', 'target_dt', 'closed_dt', 'ontime', 'case_status', 'closure_reason', 'case_title', 'subject', 'reason', 'type', 'queue', 'department', 'submittedphoto', 'closedphoto', 'location', 'fire_district', 'pwd_district', 'city_council_district', 'police_district', 'neighborhood', 'neighborhood_services_district', 'ward', 'precinct', 'location_street_name', 'location_zipcode', 'latitude', 'longitude', 'source', 'survival_time', 'event', 'ward_number', 'survival_time_hours', 'created_at', 'updated_at',
    ];
    
}
