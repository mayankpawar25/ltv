<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function orderpayment() {
      return $this->hasOne('App\Orderpayment');
    }

    public function orderedproducts() {
      return $this->hasMany('App\Orderedproduct');
    }

    /*public function user() {
      return $this->belongsTo('App\User');
    }*/

    public function user(){
      if($this->user_type == 1){
          return $this->belongsTo('App\Shopkeeper','user_id','id');
      }elseif ($this->user_type == 2){
          return $this->belongsTo('App\User','user_id','id')->withTrashed();
      }else{
        return 'false';
      }
    }

    public function staff() {
      return $this->belongsTo('App\Models\StaffUser');
    }
}
