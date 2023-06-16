<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThreeOneOneCase extends Model
{
    

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class, 'case_enquiry_id', 'case_enquiry_id');
    }
    
}
