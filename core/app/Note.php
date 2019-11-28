<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
   /**
     * Get all of the owning commentable models.
     */
    public function noteable()
    {
        return $this->morphTo();
    }

    public function person_created()
    {
    	return $this->belongsTo(Models\StaffUser::class, 'user_id', 'id');
    }

}
