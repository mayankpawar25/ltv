<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;


class Estimate extends Model
{
    use SoftDeletes;
    use \App\Traits\TagOperation;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'url_slug', 'number', 'reference', 'customer_id', 'project_id', 
        'address', 'city', 'state', 'country_id', 'zip_code',
        'shipping_address', 'shipping_city', 'shipping_state', 'shipping_zip_code', 'shipping_country_id',
        'currency_id', 'discount_type_id', 'status_id', 'sales_agent_id', 
        'admin_note', 'client_note', 'terms_and_condition', 'date', 'expiry_date', 
        'show_quantity_as', 'sub_total', 'discount_method_id', 'discount_rate', 'discount_total', 
        'taxes', 'tax_total', 'adjustment', 'total', 'created_by','component_number','component_id'
        
        ];

    
    
    function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    function get_currency_symbol()
    {
        return ($this->currency_id && isset($this->currency->symbol)) ? $this->currency->symbol : NULL ;
    }
    

    function customer()
    {
        return $this->belongsTo(User::class,'component_number','id')->withTrashed();
    }
    function dealer()
    {
        return $this->belongsTo(Shopkeeper::class,'component_number','id');
    }
    function lead()
    {
        return $this->belongsTo(Lead::class,'component_number','id');
    }

    function project()
    {
        return $this->belongsTo(Project::class);
    }

    function sales_agent()
    {
        return $this->belongsTo(User::class, 'sales_agent_id', 'id')
            ->select(DB::raw("CONCAT(first_name,' ',last_name) AS name"))->withTrashed();
            
    }
    
    function country()
    {
        return $this->belongsTo(Country::class ,'country_id','id');
    }

    function shipping_country()
    {
        return $this->belongsTo(Country::class ,'shipping_country_id','id');
    }



    function status()
    {
        return $this->belongsTo(EstimateStatus::class ,'status_id','id');
    }

    
    function item_line()
    {
        return $this->hasMany(EstimateItem::class);
    }


    static function dropdown()
    {

        $select = __('form.dropdown_select_text');
        $data['component_number_options'] = [];
        $data['customer_id_list']   = [];
        $data['project_id_list']    =   [];

        $data['sales_agent_id_list'] = array('' => $select) + Models\StaffUser::activeUsers()
                ->select(
                    DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')->pluck('name', 'id')->toArray();

        /*$data['sales_agent_id_list'] = array('' => $select) + DB::table('users')
                ->select(
                    DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')->pluck('name', 'id')->toArray();*/


        $data['status_id_list'] = EstimateStatus::orderBy('id','ASC')->pluck('name', 'id')->toArray();


        // Tax Information
        $taxes  = Tax::orderBy('name','ASC')
            ->select(
                DB::raw("CONCAT(name,' ',rate , '%') AS name"),
                'rate', 'display_as', DB::raw("CONCAT(name,' ',rate , '%') AS text") )->get();

        $data['tax_id_list'] = [];

        if(count($taxes) > 0)
        {
            foreach ($taxes as $key=>$r)
            {

                $data['tax_id_list'][$key] = [
                    'id'    => $r->display_as ,
                    'name'  => $r->name,
                    'text'  => $r->text,
                    'rate'  => $r->rate
                ];
            }
        }
        // End of Tax Information




        $data['tag_id_list'] = Tag::orderBy('name','ASC')->pluck('name', 'id')->toArray();



        $data['currency_id_list'] = array('' => $select) + Currency::orderBy('code','ASC')
                ->select(
                    DB::raw("CONCAT(code,' : ',symbol) AS name"),'id')->pluck('name', 'id')->toArray();

        $data['discount_type_id_list'] = array('' => __('form.no_discount'),
            DISCOUNT_TYPE_BEFORE_TAX => __('form.before_tax'),
            DISCOUNT_TYPE_AFTER_TAX => __('form.after_tax'),
        );

        $data['component_id_list'] = array('' => $select) + Component::orderBy('name','ASC')
                ->whereIn('id', [COMPONENT_TYPE_LEAD, COMPONENT_TYPE_CUSTOMER, COMPONENT_TYPE_SHOPKEEPER])->pluck('name', 'id')->toArray();


        $data['country_id_list'] = ["" => __('form.select_country')]  + Country::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();


        return $data;
    }

    /* New Copied From Proposal */
    public function related_to()
    {

        if($this->component_id == COMPONENT_TYPE_LEAD)
        {
            return $this->belongsTo(Lead::class,'component_number','id')->withTrashed();
        }
        elseif ($this->component_id == COMPONENT_TYPE_CUSTOMER)
        {
            return $this->belongsTo(User::class,'component_number','id')->withTrashed();
        }
        elseif ($this->component_id == COMPONENT_TYPE_SHOPKEEPER)
        {
            return $this->belongsTo(Shopkeeper::class,'component_number','id');
        }

    }

    // Need the following function when converting proposal to Estimate or Invoice
    public function customerNew()
    {

        if($this->component_id == COMPONENT_TYPE_LEAD)
        {
            if(isset($this->related_to->customer_id) && $this->related_to->customer_id)
            {
                return $this->belongsTo(Lead::class,'component_number','id')
                    ->leftJoin('customers', 'customers.id', '=', 'leads.customer_id')
                    ->select('customers.id AS id', 'customers.name AS name');
            }


        }
        else
        {
            return $this->belongsTo(Customer::class,'component_number','id');
        }
    }
    /* New Copied From Proposal */

}
