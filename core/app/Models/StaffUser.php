<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
// use Spatie\Activitylog\Traits\CausesActivity;

class StaffUser extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    use SoftDeletes;
    // use CausesActivity;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    public function role()
    {
        return $this->belongsTo('App\Models\Role'::class);
    }

    public function gender()
    {
        return $this->belongsTo('App\Models\Gender'::class);
    }

    public function boss()
    {
        return $this->belongsTo('App\Models\StaffUser'::class, 'reporting_boss', 'id');
    }

    public function assigned_to_leads()
    {
        return $this->hasMany('App\Models\Lead'::class, 'assigned_to', 'id');
    }   

    public function sub_ordinates()
    {
        return $this->hasMany('App\Models\StaffUser'::class, 'reporting_boss', 'id');
    }

    public function salesman()
    {
        return $this->hasMany('App\Shopkeeper'::class, 'salesman_id', 'id');
    }

    public function teams()
    {
        return $this->belongsToMany('App\Models\Team'::class, 'user_teams', 'user_id', 'team_id');
    }

    public function leader_of_teams()
    {
        return $this->hasMany('App\Models\Team'::class, 'leader_user_id', 'id');
    }    

    public function skills()
    {
        return $this->belongsToMany('App\Models\Skill'::class, 'user_skills', 'user_id', 'skill_id');
    }

    function departments()
    {
         return $this->belongsToMany('App\Models\Department'::class, 'user_departments', 'user_id', 'department_id');
    }


    public function comments()
    {
        return $this->hasMany('App\Models\Comment'::class,'user_id','id')->where('comments.user_type', USER_TYPE_TEAM_MEMBER);
    }

    public function attachments()
    {
        return $this->hasMany('App\Models\Attachment'::class,'created_by','id')->where('attachments.user_type', USER_TYPE_TEAM_MEMBER);
    }

    public function created_tasks()
    {
        return $this->hasMany('App\Models\Task'::class,'created_by','id')->where('tasks.user_type', USER_TYPE_TEAM_MEMBER);
    }

    public function assigned_tasks()
    {
        return $this->hasMany('App\Models\Task'::class,'assigned_to','id');
    }

    public function created_tickets()
    {
        return $this->hasMany('App\Models\Ticket'::class,'created_by','id')->where('tickets.user_type', USER_TYPE_TEAM_MEMBER);
    }

    public function assigned_tickets()
    {
        return $this->hasMany('App\Models\Ticket'::class,'assigned_to','id');
    }

    public function part_of_projects()
    {
        return $this->belongsToMany('App\Models\Project'::class ,'project_members','user_id','project_id');
    }


    function get_skills_as_badges($with_line_break = NULL)
    {
        $tags = $this->skills;
        $line_break = ($with_line_break) ? '<br>' : '';
        if(count($tags) > 0)
        {
            $tag_list = array_column($tags->toArray(), 'name');
            $str = "";
            foreach ($tag_list as $item)
            {
                $str .='<span class="badge badge-light">'.$item.'</span>'.$line_break;
            }
            return $str;
        }


    }

    static function dropdowns($user_id = NULL)
    {
        $select = __('form.dropdown_select_text');

        $data['user_roles_id_list']     = array('' => $select ) + 'App\Models\Role'::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        $data['gender_id_list']         = array('' => $select ) + 'App\Models\Gender'::orderBy('id', 'ASC')->pluck('name', 'id')->toArray();
        $data['team_id_list']           = 'App\Models\Team'::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        $data['skill_id_list']          = 'App\Models\Skill'::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        $data['department_id_list']     = 'App\Models\Department'::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();

        
        // Reporting Boss Dropdown List
        $reporting_boss_obj             = 'App\Models\StaffUser'::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id');

            if($user_id)
            {
                $reporting_boss_obj->where('id', '<>', $user_id);
            }

        $data['reporting_boss_id_list'] = array('' => __('form.not_applicable')) + $reporting_boss_obj->pluck('name', 'id')->toArray();
        // End of Reporting Boss

        return $data;
    }

    public static function activeUsers()
    {
        return self::whereNull('inactive');
    }


    function action_upon_deleting_a_team_member($assigned_to)
    {
        $assigned_user = 'App\Models\StaffUser'::find($assigned_to);

        $sub_ordinates = $this->sub_ordinates;

        if(count($sub_ordinates) > 0)
        {
            foreach ($sub_ordinates as $sub_ordinate) 
            {
                $sub_ordinate->reporting_boss = ($sub_ordinate->id ==  $assigned_to) ? NULL : $assigned_to;
                $sub_ordinate->save();
            };    
        }

        // Removing the user from the all the teams;
        $this->teams()->sync([]);

        
        $leader_of_teams = $this->leader_of_teams;

        if(count($leader_of_teams) > 0)
        {            

            foreach ($leader_of_teams as $team) 
            {
                // Assigning the new user as the team lead
                $team->leader_user_id = $assigned_user->id;
                $team->save();
                // In case the new assigned user is not a part of the team, include him.
                $assigned_user->teams()->sync($team->id);
            };    
        }

        // Assigning incomplete tasks to the new user 
        $assigned_tasks = $this->assigned_tasks()->where('status_id','<>', TASK_STATUS_COMPLETE)->get();

        if(count($assigned_tasks) > 0)
        {
            foreach ($assigned_tasks as $task) 
            {                          
                $task->assigned_to = $assigned_to;
                $task->save();                
            };    
        }

        // Assigning unclosed tickets to the new user 
        $assigned_tickets = $this->assigned_tickets()->where('ticket_status_id','<>', TICKET_STATUS_CLOSED)->get();

        if(count($assigned_tickets) > 0)
        {
            foreach ($assigned_tickets as $ticket) 
            {                          
                $ticket->assigned_to = $assigned_to;
                $ticket->save();                
            };    
        }

        // Unattach from projects        
        $this->part_of_projects()->sync([]);

        // Assign Lead that have not been converted to customer to the new user.
        $assigned_to_leads = $this->assigned_to_leads()->where('lead_status_id', '<>', LEAD_STATUS_CUSTOMER)->get();
        if(count($assigned_to_leads) > 0)
        {
            foreach ($assigned_to_leads as $lead) 
            {                          
                $lead->assigned_to = $assigned_to;
                $lead->save();                
            };    
        }
        
    }

    function scopeNames_for_dropdown($query)
    {
        return $query->whereNull('inactive')->select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')->pluck('name', 'id')->toArray();
                                        
    }

    function scopeName($query)
    {
        return $query->select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id');
    }

    function expenses()
    {
        return $this->hasMany(Expense::class);
    }

}
