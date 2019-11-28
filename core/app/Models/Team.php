<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Team extends Model
{
    public $timestamps = false;

    function leader()
    {
        return $this->belongsTo('App\User' ,'leader_user_id','id');
    }

    function members()
    {
        return $this->belongsToMany('App\User', 'user_teams', 'team_id', 'user_id');
    }

    static function dropdown()
    {

        $select = __('form.dropdown_select_text');

        $data['users_list'] = 'App\Models\StaffUser'::activeUsers()
                ->select(
                    DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')->pluck('name', 'id')->toArray();


        return $data;
    }

}
