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
    protected $primaryKey = 'case_enquiry_id';

    /**
     * Indicates if the primary key is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing primary key.
     *
     * @var string
     */
    protected $keyType = 'int';

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class, 'case_enquiry_id', 'case_enquiry_id');
    }

    const SEARCHABLE_COLUMNS = [
        'case_title', 
        'case_status', 
        'subject', 
        'reason', 
        'case_enquiry_id', 
        'closure_reason', 
    ];
    
}
