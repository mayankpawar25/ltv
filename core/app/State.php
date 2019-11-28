<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class State extends Model
{
   	use SoftDeletes;
   	protected $dates = ['deleted_at'];
   	public function country(){
      return $this->belongsTo('App\Country');
    }
}
