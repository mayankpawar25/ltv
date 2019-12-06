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
use App\Country,App\State,App\City;
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
        // salesmanNotification(Auth::id(),$title,$message); 
        $salesman = StaffUser::find(Auth::id());
        // $this->duplicatenotification($request->assigned_to,'Payment Collection','Collection Feedback submitted successfully');
        // $this->sendNotification($salesman->fcm_id,$title,$message);
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
                $collection->counter = (auth()->user()->level == 1)?($collection->counter+1):1;
                $collection->save();

                $data['msg'] = 'Feedback submitted successfully';
                $data['status'] = true;
                $status = $this-> successStatus;
                $this->duplicatenotification($request->assigned_to,'Payment Collection','Collection Feedback submitted successfully');

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
        $collections = DB::table('payment_collections As t')
                    ->leftjoin('staff_users', 't.staff_user_id', '=', 'staff_users.id')
                    ->where('staff_users.id',Auth::id())
                    ->where('t.new_date',(new Carbon(now()))->format('Y-m-d'))
                    ->select('t.id','t.name','t.mobile_no','t.staff_user_id as assigned_to','t.alternate_no','t.collection_date','t.new_date','t.amount','t.status','t.collected_amount','t.balance_amount','t.country_id','t.state_id','t.city_id','t.address','staff_users.first_name as salesman_first_name','staff_users.last_name as salesman_last_name','t.counter')
                    ->get();
        foreach ($collections as $key => $collection) {
            $collection->country = ($collection->country_id!='')?Country::find($collection->country_id)->name:'';
            $collection->state = ($collection->state_id!='')?State::find($collection->state_id)->name:'';
            $collection->city = ($collection->city_id!='')?City::find($collection->city_id)->name:'';
        }
        // $collection = PaymentCollection::where('new_date',(new Carbon(now()))->format('Y-m-d'))->where('staff_user_id',auth::id())->get();
        if(!$collections->isEmpty()){
            $data['collection_list'] = $collections;
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
           
        $threads = PaymentCollectionDescription::where('payment_collection_id',$request->payment_collection_id)->get();
        foreach($threads as $key => $thread){
            if($key > 1){
                $created_by = StaffUser::find($threads[$key-1]->assigned_to);
                $thread->created_by_name = $created_by->first_name.' '.$created_by->last_name;
            }else{
                $created_by = StaffUser::find($threads[$key]->assigned_to);
                $thread->created_by_name = $created_by->first_name.' '.$created_by->last_name;
            }

            $assigned_to = StaffUser::find($threads[$key]->assigned_to);
            $thread->salesman = $assigned_to->first_name.' '.$assigned_to->last_name;
            $thread->country;
            $thread->state;
            $thread->city;
            // $thread->salesman = $thread->assigned->first_name.' '.$thread->assigned->last_name;
        }
        // exit;
        $salesman = StaffUser::where('role_id',1);
        if($paymentcollection->assigned->level == 2){
            $salesman = $salesman->whereNotNull('level')->get();
        }else{
            if($paymentcollection->counter % \Config::get('constants.THREAD_COUNT') == 0){
              $salesman = $salesman->where('level',2)->get();
            }else{
              $salesman = $salesman->where('level',1)->get();
            }
        }

        $datas['threads'] = $threads;
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

    public function duplicatenotification($salesman_id,$title="LTV",$message="Payment Collection Module"){
        $regId = StaffUser::find($salesman_id)->fcm_id;
        // $regId = 'eP835HRmBi8:APA91bEqO9kBmd6raR0Wf6h3rqtzfGmZXAfqpCkS1xCJr6n3HaqlFwwZXazC83ceUGN0G1qCCyMbE7lhTc85pOjEkchPVCIC-MNTN8cM0Ux39ol5FRZo3ahwwOfyYUgBi7WxSABlXpMH';
        $this->sendNotification($regId,$title,$message);
    }
    
    /* Send Firebase Notification */
    public function sendNotification($regId,$title,$message){

        // define('FIREBASE_API_KEY', 'AAAAUG7Snkg:APA91bFdUnrMQwY_hJ3mD0MLj_vjCpvlXFBQbuRykSIaSwFnyxv7dd-PNKsIUhWnSX8dxj_zmCgPaG06oqTWms0PtEKX01h5ulNeDB71iqX9HiabOWfA64jlYp5Eq8sMMXm9UfOjKFkN');

        $message = strip_tags($message);        
        $title = strip_tags($title);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\r\n \"to\" : \"$regId\",\r\n \"collapse_key\" : \"type_a\",\r\n \"notification\" : {\r\n \"body\" : \"$message\",\r\n \"title\": \"$title\"\r\n },\r\n \"data\" : {\r\n \"body\" : \"$message\",\r\n \"title\": \"$title\",\r\n \"key_1\" : \"\" }\r\n}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: key=".FIREBASE_API_KEY,
                "Cache-Control: no-cache",
                "Content-Type: application/json",
                "Postman-Token: 17dca3af-6994-4fe7-b8ec-68f99d13cfe8"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        // echo $response;
        // exit;
        return true;
    }
    /* Send Firebase Notification */


}
