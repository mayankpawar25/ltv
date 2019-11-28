<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentCollection extends Model
{
	public function assigned()
    {
        return $this->belongsTo('App\Models\StaffUser' ,'staff_user_id','id');
    }
}
