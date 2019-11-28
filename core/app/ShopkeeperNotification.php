<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopkeeperNotification extends Model
{
    public function shopkeeper(){
      return $this->belongsTo('App\Shopkeeper');
    }
}
