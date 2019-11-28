<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zipcode extends Model
{
  use SoftDeletes;
 	protected $dates = ['deleted_at'];

  protected $fillable = ['area_name','country_id','state_id','city_id','status','approved'];

 	public function task() {
    return $this->belongsTo('App\Task');
  }
  
	static function column_sequence_for_import(){
        return [
            // Area
           'A' => 'area_name', 
           'B' => 'country', 
           'C' => 'state', 
           'D' => 'city',            
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
