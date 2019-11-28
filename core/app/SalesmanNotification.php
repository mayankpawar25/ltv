<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesmanNotification extends Model
{
    public function shopkeeper(){
      return $this->belongsTo('App\StaffUser');
    }
}
