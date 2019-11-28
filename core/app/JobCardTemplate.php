<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCardTemplate extends Model
{
    public function templatevalues() {
      return $this->hasMany('App\JobCardFeildValue');
    }
}
