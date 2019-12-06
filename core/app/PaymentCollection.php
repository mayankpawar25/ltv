<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentCollection extends Model
{

	protected $fillable = ['name','mobile_no','alternate_no','collection_date','new_date','amount','collected_amount','balance_amount','status','staff_user_id','country_id','state_id','city_id','address'];

	public function assigned()
    {
        return $this->belongsTo('App\Models\StaffUser' ,'staff_user_id','id');
    }

    static function column_sequence_for_import(){
        return [
            // Contact
           'A' => 'name', 
           'B' => 'shop_name', 
           'C' => 'mobile', 
           'D' => 'alternate_number', 
           'E' => 'collection_date', 
           'F' => 'calling_date',
           'G' => 'amount', 
           'H' => 'collected_amount',
           'I' => 'balance_amount',
           'J' => 'status',
           'K' => 'country',
           'L' => 'state',
           'M' => 'city',
           'N' => 'address',
        ];
    }
    public function country(){
      return $this->belongsTo('App\Country');
    }

    public function state(){
      return $this->belongsTo('App\State');
    }

    public function city(){
      return $this->belongsTo('App\City');
    }
}
