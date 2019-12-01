<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class City extends Model
{
    use SoftDeletes;
   	protected $dates = ['deleted_at'];

   	protected $fillable = ['name'];

   	public function state(){
      return $this->belongsTo('App\State');
    }
}
