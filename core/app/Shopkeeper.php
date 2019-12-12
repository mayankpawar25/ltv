<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
class Shopkeeper extends Authenticatable
{
	use HasApiTokens, Notifiable;

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'name', 'email', 'password','shopname','mobile','phone','salesman_id','status','usergroup_id','country_id','state_id','city_id','is_verified','folder','address','sms_verified','email_verified','sms_sent','sms_ver_code','email_sent','email_ver_code'
	];
	/**
	* The attributes that should be hidden for arrays.
	*
	* @var array
	*/
	protected $hidden = [
		'password', 'remember_token',
	];

	public function notifications() {
      return $this->hasMany('App\ShopkeeperNotification');
    }

    public function available_credits()
    {
        return $this->credit_notes()
        ->select(DB::raw('IFNULL(sum(total - IFNULL(amount_credited, 0)), 0) AS amount'))
        ->where('status_id', CREDIT_NOTE_STATUS_OPEN)->get()->first();
    }

    public function credit_notes()
    {
        return $this->hasMany(CreditNote::class);
    }

    public function usergroup(){
    	return $this->belongsTo('App\UserGroup');
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

    public function zipcode(){
      return $this->belongsTo('App\Zipcode');
    }

    public function area(){
        return $this->belongsTo('App\Zipcode' ,'zipcode_id','id');
    }

     public function salesman(){
      return $this->belongsTo('App\Models\StaffUser');
    }

    static function column_sequence_for_import()
    {
        return [

            // Contact
           'A' => 'name', 
           'B' => 'shop_name', 
           'C' => 'email', 
           'D' => 'mobile', 
           'E' => 'alternate_number',

           // Address
           'F' => 'address', 
           'G' => 'country',
           'H' => 'state', 
           'I' => 'city',
           'J' => 'area',
           'K' => 'status',
           'L' => 'password',
           'M' => 'employer_name',
           'N' => 'employer_contactno',

           
        ];
    }

    static function sales_agent_dropdown()
    {       

        return  DB::table('staff_users')
                ->select(
                    DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')->pluck('name', 'id')->toArray();
    }

    static function dropdown(){
        $select = __('form.dropdown_select_text');
        $data['currency_id_list'] = ["" => __('form.system_default')]  + Currency::orderBy('code', 'ASC')->pluck('code', 'id')->toArray();
        $data['default_language_id_list'] = ["" => __('form.system_default')]  +  get_languges() ;
        $data['group_id_list']      = CustomerGroup::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        $data['country_id_list'] = ["" => __('form.nothing_selected')]  + Country::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        $data['assigned_to_list'] = array('' => $select) +  self::sales_agent_dropdown();
        $data['status'] = array(''=>$select)+ ['0'=>'Inactive','1' => 'Active'];
        $data['usergroup'] = array(''=>$select)+ UserGroup::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        return $data;
    }

    function assigned()
    {
        return $this->belongsTo('App\Models\StaffUser' ,'salesman_id','id');
    }

}
