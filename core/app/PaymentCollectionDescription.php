<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentCollectionDescription extends Model
{
    public function assigned()
    {
        return $this->belongsTo('App\Models\StaffUser' ,'assigned_to','id');
    }
}
