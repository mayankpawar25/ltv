<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Order extends Model
{
    protected $guarded = [];

    public function orderpayment() {
      return $this->hasOne('App\Orderpayment');
    }

    public function orderedproducts() {
      return $this->hasMany('App\Orderedproduct');
    }

    public function user() {
      return $this->belongsTo('App\User');
    }

    /*public function user(){
      if($this->user_type == 1){
          return $this->belongsTo('App\Shopkeeper','user_id','id');
      }elseif ($this->user_type == 2){
          return $this->belongsTo('App\User','user_id','id')->withTrashed();
      }else{
        return 'false';
      }
    }*/

    public function staff() {
      return $this->belongsTo('App\Models\StaffUser');
    }

    static function sales_agent_dropdown()
    {        

        return Models\StaffUser::activeUsers()
                ->select(
                    DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')->pluck('name', 'id')->toArray();
    }

    static function dropdown_for_filtering()
    {
        $data['assigned_to_list']    = array('unassigned' => __('form.not_assigned')) +  self::sales_agent_dropdown();

        $data['order_status'] = [
            '0'  => __('form.pending'), 
            '1'  => __('form.accepted'), 
            '-1' => __('form.rejected'), 
        ];

        $data['delivery_status'] = [
            '0'  => __('form.delivery_pending'), 
            '1'  => __('form.delivery_inprocess'), 
            '2'  => __('form.delivered'), 
        ];

        $data['payment_method'] = [
            '1'  => __('form.cash_on_delivery'),
            '2'  => __('form.advance_paid'),
        ];

        $data['payment_status'] = [
            '0'  => __('form.unpaid'),
            '1'  => __('form.paid'),
        ];

        return $data;
    }
}
