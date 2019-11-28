<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

// use App\PaymentMode,App\Transaction,App\Models\StaffUser,Validator,App\PaymentCollectionDescription;
use App\PaymentMode,App\Transaction,App\Models\StaffUser,Validator;
use App\PaymentCollectionDescription,App\PaymentCollection;
use Illuminate\Support\Facades\DB;  
use Carbon\Carbon;
class CollectPaymentController extends Controller
{
    /*Collect Payment Management*/

    public $successStatus = 200;
    /**
     * Display a listing Leads.
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentMode(Request $request){
        $payment_mode = PaymentMode::whereNull('inactive')->get();
        if(!$payment_mode->isEmpty()){
            $data['payment_mode'] = $payment_mode;
            $data['msg'] = 'Payment Mode List';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['payment_mode'] = [];
            $data['msg'] = 'No Payment Mode Found Contact to Admin';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status); 
    }

    /*[Salesman] Add Payment Or Collect Payment*/
    public function AddPayment(Request $request){
        $user = Auth::guard('salesman')->user();
        $validator = Validator::make($request->all(), [ 
            'amount'        => 'required',
            'payment_mode'  => 'required',
            'remarks'       => 'required',
            'client_id'     => 'required',
            'client_type_id'=> 'required',
        ]);
        
        if ($validator->fails()) { 
            return response()->json($validator->errors(), 401);            
        }

        $last_balance_amount = Transaction::where('client_id',$request->client_id)->where('client_type_id',$request->client_type_id)->orderby('id','DESC')->first();

        $after_balance = (isset($last_balance_amount) && !empty($last_balance_amount))?$last_balance_amount->after_balance:'0.00';

        $transaction                    = new Transaction;
        $transaction->amount            = $request->amount;
        $transaction->debit             = $request->amount;
        $transaction->client_id         = $request->client_id;
        $transaction->client_type_id    = $request->client_type_id;
        $transaction->staff_user_id     = Auth::id();
        $transaction->payment_mode      = $request->payment_mode;
        $transaction->trx_id            = $request->transaction_id;
        $transaction->staff_user_remark = $request->remarks;
        $transaction->after_balance     = $after_balance - $request->amount;
        $transaction->save();

        /*Notification*/
        $title = sprintf(__('Collect Payment'));
        $message = sprintf(__('Payment Collect Successfully'), __('From Client'));
        salesmanNotification(Auth::id(),$title,$message); 
        $salesman = StaffUser::find(Auth::id());
        sendNotification($salesman->fcm_id,$title,$message);
        /*Notification*/

        if($transaction->id!=''){
            $data['msg'] = 'Payment Collect Successfully';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['msg'] = 'No Payment Collect Detail Added';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status); 
    }

    public function duplicatenotification(){
        $regId = 'cRW8Ybb7IfY:APA91bGB8qZddycgOey-bHhhGTjTW0W74X4j5Dcy_CHlq3EQGl_uhF0lDdQF_FkO-4n8iKGHZ4iKRHJA-BCb55kRSlhsHV0uzzEJ6oMN-7n7DFGnXxV6lcwrTbLkms33_ZTUnI7ctSLk';
        $title = 'Hello World';
        $message = 'Laptop True Value Order Placed';
        sendNotification($regId,$title,$message);
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
    public function store(Request $request){

      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /* Collection Feedback */
    public function addfeedback(Request $request,$collection_payment_id=""){
        if($collection_payment_id!=''){
            $check_collection_id = PaymentCollection::find($collection_payment_id);
            if(empty($check_collection_id)){
                $data['msg'] = 'Check Collection Id';
                $data['status'] = false;
                $status = 401;
                return response()->json($data, $status);
            }

            /*
            if($check_collection_id->staff_user_id != auth()->user()->id){
                $data['msg'] = 'You are not assigned for this task';
                $data['status'] = false;
                $status = 401;
                return response()->json($data, $status);   
            }

            $threads = PaymentCollectionDescription::where('payment_collection_id',$collection_payment_id)->get();

            if(auth()->user()->level == 1 && auth()->user()->id == $check_collection_id->staff_user_id && count($threads) % (\Config::get('constants.THREAD_COUNT')-1) == 0){

                $salesman = StaffUser::where('role_id',1)->where('level',2)->get();

                if($request->payment_type == ''){
                    $data['msg'] = 'Please Assign to Level 2 Salesman';
                    $data['level_two'] = $salesman;
                    $data['count'] = count($threads);
                    $data['status'] = false;
                    $status = 401;
                    return response()->json($data, $status);   
                }
            }
            */

            $feedback = new PaymentCollectionDescription;
            $feedback->feedback = $request->feedback;
            $feedback->calling_date = $request->calling_date;
            $feedback->payment_type = $request->payment_type;
            $feedback->collect_amount = $request->collect_amount;
            $feedback->balance_amount = $request->balance_amount;
            $feedback->assigned_to = $request->assigned_to;
            $feedback->status = $request->status;
            $feedback->payment_collection_id = $collection_payment_id;
            if($feedback->save()){

                $collection = PaymentCollection::find($collection_payment_id);
                $collection->collected_amount = $request->collect_amount;
                $collection->balance_amount = $request->balance_amount;
                $collection->status = $request->status;
                $collection->staff_user_id = $request->assigned_to;
                $collection->save();

                $data['msg'] = 'Feedback submitted successfully';
                $data['status'] = true;
                $status = $this-> successStatus;
            }
        }else{
            $data['msg'] = 'Please check collection payment Id';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status);
    }
    /* Collection Feedback */

    /*Todays Salesman Collection List*/
    public function todaySalesmanCollectionList(Request $request){
        $collection = DB::table('payment_collections As t')
                    ->leftjoin('staff_users', 't.staff_user_id', '=', 'staff_users.id')
                    ->where('staff_users.id',Auth::id())
                    ->where('t.new_date',(new Carbon(now()))->format('Y-m-d'))
                    ->select('t.id','t.name','t.mobile_no','t.alternate_no','t.collection_date','t.new_date','t.amount','t.status','t.collected_amount','t.balance_amount','staff_users.first_name as salesman_first_name','staff_users.last_name as salesman_last_name')
                    ->get();
        if(!$collection->isEmpty()){
            $data['collection_list'] = $collection;
            $data['msg'] = 'Collection List';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['collection_list'] = [];
            $data['msg'] = 'No Payment Collection List Found';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status); 
    }

    public function salesmanCollectionThred(Request $request){
       /* $threads  = PaymentCollectionDescription::where('payment_collection_id',$request->payment_collection_id)->get();
        if(!$threads->isEmpty()){
            $data['collection_thread_list'] = $threads;
            $data['msg'] = 'Collection Thread List';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['collection_thread_list'] = [];
            $data['msg'] = 'No Payment List Found';
            $data['status'] = false;
            $status = 401;
        }*/
        $paymentcollection  = PaymentCollection::find($request->payment_collection_id);
        $datas['threads']  = PaymentCollectionDescription::where('payment_collection_id',$paymentcollection->id)->get();
       

        $salesman = StaffUser::where('role_id',1);
        if($paymentcollection->assigned->level == 2){
            $salesman = $salesman->whereNotNull('level')->get();
        }else{
          if(count($datas['threads']) > 0){
            if(count($datas['threads']) % \Config::get('constants.THREAD_COUNT')-1 == 0){
              $salesman = $salesman->where('level',2)->get();
            }else{
              $salesman = $salesman->where('level',1)->get();
            }
          }else{
            $salesman = $salesman->where('level',1)->get();
          }
        }

        $datas['salesmans'] = $salesman;
        //$data['collections'] = $paymentcollection;
        if(!empty($datas['threads'])){
            $data['collection_thread_list'] = $datas['threads'];
            $data['salesman'] = $datas['salesmans'];
            $data['msg'] = 'Collection Thread List';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['collection_thread_list'] = [];
            $data['salesman'] = $datas['salesmans'];
            $data['msg'] = 'No Payment List Found';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status); 
    }

}
