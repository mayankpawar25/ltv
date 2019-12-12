<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'description', 'task_date','task_status_id'];

    public function zipcode() {
      return $this->hasMany('App\Zipcode');
    }

    function taskstatus() {
      return $this->belongsTo('App\TaskStatus','task_status_id','id');
    }

    public function salesman() {
      return $this->belongsTo('App\Models\StaffUser');
    }


}
