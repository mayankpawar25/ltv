<?php

namespace App\Http\Controllers\Admin;

use App\Models\Salesman;
use App\Order;
use App\Lead;
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
        /*print_r($data['taxes']);
        die;*/
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
}
