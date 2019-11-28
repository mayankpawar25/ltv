<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'description', 'task_date'];

    public function zipcode() {
      return $this->hasMany('App\Zipcode');
    }

    public function taskstatus() {
      return $this->belongsTo('App\Product');
    }

    public function salesman() {
      return $this->belongsTo('App\Models\StaffUser');
    }
}
