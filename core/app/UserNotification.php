<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    public function customer(){
      return $this->belongsTo('App\Customer');
    }
}
