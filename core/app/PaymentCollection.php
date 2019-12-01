<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentCollection extends Model
{

	protected $fillable = ['name','mobile_no','alternate_no','collection_date','new_date','amount','collected_amount','balance_amount','status','staff_user_id'];
	
	public function assigned()
    {
        return $this->belongsTo('App\Models\StaffUser' ,'staff_user_id','id');
    }

    static function column_sequence_for_import(){
        return [
            // Contact
           'A' => 'name', 
           'B' => 'mobile', 
           'C' => 'alternate_number', 
           'D' => 'collection_date', 
           'E' => 'calling_date',
           'F' => 'amount', 
           'G' => 'collected_amount',
           'H' => 'balance_amount',
           'I' => 'status',
        ];
    }
}
