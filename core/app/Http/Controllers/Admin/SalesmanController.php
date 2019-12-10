<?php

namespace App\Http\Controllers\Admin;

use App\Models\Salesman;
use App\Order;
use App\Lead;
use App\Invoice;
use App\Currency;
use App\Shopkeeper,App\Task;
use App\Models\StaffUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Input;
use Auth;
use Hash;
use Carbon\Carbon;
class SalesmanController extends Controller
{
    private $pre_page;

    public function __construct()
    {
        $this->pre_page = config('constants.RECORD_PER_PAGE');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['taxes'] = Salesman::paginate(10);
        return view('admin.salesman.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         //Get User data
        $user = Auth::guard('admin')->user();

        //Validations
        $validatedRequest = $request->validate([
            'name'         => 'required',
            //'tax_percentage'   => 'required',
        ]);

        //Store Data to database
        $salesman           = new Salesman();
        $salesman->name     = $request->name;
        $salesman->emp_id   = $request->emp_id;
        $salesman->email    = $request->email;
        $salesman->phone    = $request->phone;
        $salesman->password = Hash::make($request->password);
        $salesman->address  = $request->address;
        $salesman->status   = $request->status;
        $saved              = $salesman->save();

        //Success and Error Message 
        $message = [];
        if(!$saved){
            $message = [
                'error' => 'Something is wrong, Salesman could not added.'
            ];
       }else {
             $message = [
                'success' => 'New Salesman added successfully.'
            ];
        }
       
        return redirect()->route('admin.salesman.index')->with($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salesman  $salesman
     * @return \Illuminate\Http\Response
     */
    public function show(Salesman $salesman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salesman  $salesman
     * @return \Illuminate\Http\Response
     */
    public function edit(Salesman $salesman)
    {
         $data['taxes'] = Salesman::paginate($this->pre_page);
        $data['edit'] = Salesman::find($salesman->id);
        return view('admin.salesman.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salesman  $salesman
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      

        //Validations
        $validatedRequest = $request->validate([
            'name'         => 'required',
            
        ]);

        //Update Data to database
        $salesman           = Salesman::find($request->id);
        $salesman->name     = $request->name;
        $salesman->emp_id   = $request->emp_id;
        $salesman->email    = $request->email;
        $salesman->phone    = $request->phone;
        $salesman->password = $request->password;
        $salesman->address  = $request->address;
        $salesman->status   = $request->status;
        $saved              = $salesman->save();

        //Success and Error Message 
        $message = [];
        if(!$saved){
            $message = [
                'error' => 'Something is wrong, Salesman could not update.'
            ];
       }else {
             $message = [
                'success' => 'Salesman updated successfully.'
            ];
        }
       
        return redirect()->route('admin.salesman.index')->with($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salesman  $salesman
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salesman $salesman)
    {
        //
    }

     public function visibility(Request $request, $id)
    {
        $tax             = Salesman::find($id);
        $tax->status     = $request->input('status');
        $tax->save();

        $text    = ($request->input('status') == 0) ? 'Inactive' : 'Active';
        $message = 'The Salesman has been ' . $text . ' successfully.';
        return redirect()->back()->with('success', $message);
    }


     /*Salesman Sales Score List*/
    public function salesscore(Request $request){
        $data['sales_agent_id_list']    =   Invoice::sales_agent_dropdown();

         $data['months'] = [
            'january'                           => __('form.january'),
            'february'                          => __('form.february'),
            'march'                             => __('form.march'),
            'april'                             => __('form.april'),
            'may'                               => __('form.may'),
            'june'                              => __('form.june'),
            'july'                              => __('form.july'),
            'august'                            => __('form.august'),
            'september'                         => __('form.september'),
            'october'                           => __('form.october'),
            'november'                          => __('form.november'),
            'december'                          => __('form.december'),
        ];

             
        $data = $data + $this->get_report_conversion_by_month_for_graph(date("F"));
    
        if(empty(auth()->user()->is_administrator)){
            $data['salesscores'] = Order::where('staff_user_id', Auth::id())->orderby('id','DESC')->paginate($this->pre_page);
            return view('admin.salesman.salesscorelist',compact('data'),$data);
        }else {
            $data['salesscores'] = Order::whereNotNull('staff_user_id')->orderby('id','DESC')->paginate($this->pre_page);
            return view('admin.salesman.salesscorelist',compact('data'),$data);
        }
    }

    function get_report_conversion_by_month_for_graph($month){   
       $start_date = (new Carbon('first day of '.$month))->format('Y-m-d');
       $end_date = (new Carbon('last day of '.$month))->format("Y-m-d");

       $period = \Carbon\CarbonPeriod::create($start_date, $end_date);

        // Iterate over the period
        foreach ($period as $date) 
        {
            $data['conversion_by_month']['labels'][] = $date->format('Y-m-d');

            $data['conversion_by_month']['data'][$date->format('Y-m-d')] = 0;
        }
         if(empty(auth()->user()->is_administrator)){
        $records = \App\Order::select([DB::raw('count(id) as count'),DB::raw('sum(total) as amount'), DB::raw('DATE(updated_at) as day')])
                    ->groupBy('day')->whereBetween('updated_at', [$start_date , $end_date ])->where('staff_user_id', Auth::id())->get();
        }else{
             $records = \App\Order::select([DB::raw('count(id) as count'),DB::raw('sum(total) as amount'), DB::raw('DATE(updated_at) as day')])
                    ->groupBy('day')->whereBetween('updated_at', [$start_date , $end_date ])->whereNotNull('staff_user_id')->get();
        }
  
            
       if(count($records) > 0){
        foreach ($records as $key => $row){
             $data['conversion_by_month']['data'][$row->day] = $row->amount;  
        }
       }
       $data['conversion_by_month']['data'] = array_values($data['conversion_by_month']['data']);
       return $data;
    }

     function get_salesscore_report(Request $request)
    {
        $data = $this->get_report_conversion_by_month_for_graph(Input::get('month'));
        return response()->json($data);
    }


    public function addSalesScore(Request $request){
       /* print_r($request->all());
        die('test');*/


         //Validations
        $validatedRequest = $request->validate([
            'unique_id'         => 'required',
            'staff_user_id'         => 'required',
            'staff_user_remarks'         => 'required',
            
        ]);

        //Update Data to database
       $saved = Order::where('unique_id',$request->unique_id)->update(array('staff_user_id' =>$request->staff_user_id,'staff_user_remarks' => $request->staff_user_remarks));
        //Success and Error Message 
        $message = [];
        if(!$saved){
            $message = [
                'error' => 'Something is wrong, Sales Score could not update.'
            ];
       }else {
             $message = [
                'success' => 'Sales Score Added successfully.'
            ];
        }
        return redirect()->route('admin.salesman.salesscore')->with($message);
    }

    public function checkOrderid(Request $request){
        $order = \App\Order::where('unique_id',$request->unique_id)->orderby('id','DESC')->first();
        //$order = \App\Models\DeliveryBoy::where('email', $request->email)->first();
        if ($order['staff_user_id']) {
            return 'false';
        } else {
            return 'true';
        }
    }

    public function checkOrderidAmount(Request $request){
        $order = \App\Order::where('unique_id',$request->unique_id)->orderby('id','DESC')->first();
        //$order = \App\Models\DeliveryBoy::where('email', $request->email)->first();
        if ($order) {
            echo $order->total;
        } else {
            return '0.00';
        }
    }

    public function salesscore_paginate(){

        $query_key          = Input::get('search');
        $search_key         = $query_key['value'];
        // $status_ids         = Input::get('status_ids');
        $sales_agent_ids    = Input::get('sales_agent_ids');
        $date_range         = Input::get('date_range');
        $currency_id        = Input::get('currency_id');

        $q = Order::orderBy('id','DESC');
        $query = Order::orderBy('id','DESC');

        $date_from          = "";
        $date_to            = "";

        if($date_range)
        {
            list($date_from, $date_to)  = explode("-", $date_range);
            $date_from                  = str_replace('/', '-', trim($date_from) );
            $date_to                    = str_replace('/', '-', trim($date_to));
            $date_from                  = date2sql(trim($date_from));
            $date_to                    = date2sql(trim($date_to));
        }
        
        // $common_query        = Invoice::where('status_id', '<>', INVOICE_STATUS_CANCELED)
                                // ->Where('status_id', '<>', INVOICE_STATUS_DRAFT);


        // $q                  =  $common_query;
        // $query              =  $common_query->with(['status', 'customer']);
                                
        /*if($currency_id)
        {
            $q->where('currency_id', $currency_id);
            $query->where('currency_id', $currency_id);
        }*/
        /*if($status_ids)
        {
            $q->whereIn('status_id', $status_ids);
            $query->whereIn('status_id', $status_ids);
        }*/
        if($sales_agent_ids){
            $q->whereIn('staff_user_id', $sales_agent_ids);
            $query->whereIn('staff_user_id', $sales_agent_ids);
        }

        if(!auth::user()->is_administrator){
            $q->where('staff_user_id', auth::user()->id);
            $query->where('staff_user_id', auth::user()->id);
        }

        $q->whereNotNull('staff_user_id');
        $query->whereNotNull('staff_user_id');

        if($date_from && $date_to)
        {
            $q->whereBetween('created_at', [$date_from, $date_to ]);
            $query->whereBetween('created_at', [$date_from, $date_to ]);
        }

        $number_of_records  = $q->get()->count();

        if ($search_key)
        {
            // $query->orwhere('number', 'like', like_search_wildcard_gen($search_key))
            //     ->orWhere('total', 'like', like_search_wildcard_gen($search_key))
            //     ->orWhere('tax_total', 'like', like_search_wildcard_gen($search_key))
            //     ->orWhere('date', 'like', like_search_wildcard_gen(date2sql($search_key)))
            //     ->orWhere('due_date', 'like', like_search_wildcard_gen(date2sql($search_key)))
            //     ->orWhere('reference', 'like', like_search_wildcard_gen($search_key))
            //     ->orWhereHas('customer', function ($q) use ($search_key) {
            //         $q->where('customers.name', 'like', $search_key . '%');
            //     })
               
            //     ->orWhereHas('status', function ($q) use ($search_key) {
            //         $q->where('name', 'like', $search_key . '%');
            //     });
        }

        $recordsFiltered = $query->get()->count();
        $query->skip(Input::get('start'))->take(Input::get('length'));
        $data = $query->get();

        $rec = [];

        if (count($data) > 0) 
        {   
            $subtotal           = 0;
            $total              = 0;
            $tax_total          = 0;
            $discount_total     = 0;
            $adjustment         = 0;
            $applied_credits    = 0;
            $open_amount        = 0;

            $currency                   = Currency::find($currency_id);
            $currency_symbol            = ($currency) ? $currency->symbol : NULL ;

            foreach ($data as $key => $row) 
            {   
                $name = '-';
                if($row->user_type == 1){
                    // $name = $row->user_id;
                    $name = Shopkeeper::find($row->user_id)->name;
                }else if($row->user_type == 2){
                    $name = User::find($row->user_id)->name;
                }

                $rec[] = array(        
                       
                    $row->unique_id,
                    $name,
                    ($row->staff_user_id!='')?StaffUser::select(DB::raw('CONCAT(first_name," ",last_name) as name'))->find($row->staff_user_id)->name:'',
                    $row->staff_user_remarks,
                    format_currency($row->subtotal,true,$currency_symbol),
                    format_currency($row->total,true,$currency_symbol),
                    date('d-m-Y',strtotime($row->created_at)),
                    // anchor_link( $row->number, route('show_invoice_page', $row->id)),
                    /*anchor_link($row->related_to->first_name .' '. $row->related_to->last_name, route('view_customer_page', $row->customer_id )),*/
                    // ($row->related_to->name)?$row->related_to->name:$row->related_to->first_name.' '.$row->related_to->last_name,
                    // isset(($row->date)) ? sql2date($row->date) : "",
                    // isset(($row->due_date)) ? sql2date($row->due_date) : "",
                    // format_currency($row->total, true, $currency_symbol  ),
                    // format_currency($row->tax_total, true , $currency_symbol ),                    
                    // format_currency($row->discount_total, true , $currency_symbol ),                
                    // format_currency($row->adjustment, true , $currency_symbol ),    
                    // format_currency($row->applied_credits, true , $currency_symbol ), 
                    // format_currency($row->total - ($row->amount_paid + $row->applied_credits), true , $currency_symbol  ), 
                    // $row->status->name,

                );

                $subtotal           += $row->subtotal;
                $total              += $row->total;
                // $tax_total          += ($row->tax!=null)?$row->tax:0.00;
                // $discount_total     += $row->discount_total;
                // $adjustment         += $row->adjustment;
                // $applied_credits    += $row->applied_credits;
                // $open_amount        += $row->total - ($row->amount_paid + $row->applied_credits);
                
               

            }

            array_push($rec, [

                '<b>'. __('form.total_per_page'). '<b>',
                "",
                "",
                "",
                '<b>'. format_currency($subtotal, true , $currency_symbol  ). '<b>',
                '<b>'. format_currency($total, true , $currency_symbol  ). '<b>',
                // '<b>'.format_currency($tax_total, true , $currency_symbol ) . '<b>',                    
                // '<b>'.format_currency($discount_total, true , $currency_symbol ) . '<b>',                
                // '<b>'.format_currency($adjustment, true , $currency_symbol ). '<b>',    
                // '<b>'.format_currency($applied_credits, true , $currency_symbol ). '<b>', 
                // '<b>'.format_currency($open_amount, true , $currency_symbol ). '<b>', 
                '',

            ]);
        }


        $output = array(
            "draw" => intval(Input::get('draw')),
            "recordsTotal" => $number_of_records,
            "recordsFiltered" => $recordsFiltered,
            "data" => $rec
        );
        return response()->json($output);
    }

}
