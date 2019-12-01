<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use App\Models\StaffUser;
use App\Gateway;
use App\Orderpayment;
use App\Shopkeeper;
use App\SalesmanNotification;
use Spatie\Activitylog\Models\Activity;
use App\Coupon,App\Order,App\Transaction,App\PaymentMode;
use Image;
use Validator;
use Hash;
use DB;
class SalesmanController extends Controller 
{
  public $successStatus = 200;
  /** 
   * login API 
   * 
   * @return \Illuminate\Http\Response 
   */ 
  public function login(Request $request){
   if(is_numeric(request('email'))){
  	 	if(Auth::guard('salesman')->attempt(['phone' => $request->email, 'password' => $request->password])){
        
            $user = Auth::guard('salesman')->user();
            $fcm_id = request('fcm_id'); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            $success['user_id'] =  $user->id;
            if(isset($success['token'])){
              StaffUser::where('id', $user->id)->update(['fcm_id' => $fcm_id]);
            }
            $success['status'] = true;
            $success['msg'] = 'Log in success';
         
          return response()->json($success, $this-> successStatus); 
        }else{
          $error['status'] = false;
          $error['msg'] = 'Unauthorised Phone or Password';
          return response()->json($error, 401); 
        }
      }elseif (filter_var(request('email'), FILTER_VALIDATE_EMAIL)) {
      
        if(Auth::guard('salesman')->attempt(['email' => $request->email, 'password' => $request->password])){
          $user = Auth::guard('salesman')->user();
        
            $fcm_id = request('fcm_id');  
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            $success['user_id'] =  $user->id;
            if(isset($success['token'])){
              StaffUser::where('id', $user->id)->update(['fcm_id' => $fcm_id]);
            } 
            $success['status'] = true;
            $success['msg'] = 'Log in success';
        
          return response()->json($success, $this-> successStatus); 
        }else{
          $error['status'] = false;
          $error['msg'] = 'Unauthorised Email or Password';
          return response()->json($error, 401);
        }
    }else{
      $error['status'] = false;
      $error['msg'] = 'Unauthorised Username or Password else no ';
      return response()->json($error, 401); 
    }
  }
  
  public function details(){
    
    $user = Auth::guard('salesman')->user();
    $user = StaffUser::where('id', Auth::id())->first();
    return response()->json($user, $this-> successStatus); 
  } 
      
  /** 
   * Change Password API 
   * 
   * @return \Illuminate\Http\Response 
  */
  public function updatePassword(Request $request) {

    $messages = [
        'password.required' => 'The new password field is required',
        'password.confirmed' => "Password does'nt match"
    ];

    $validator = Validator::make($request->all(), [
        'old_password' => 'required',
        'password' => 'required'
    ], $messages);

    if ($validator->fails()) { 
          $error['status'] = false;
          $error['msg'] =$validator->errors(); 
          return response()->json($error, 401);      
    }

    // if given old password matches with the password of this authenticated user...
    if(Hash::check($request->old_password, Auth::user()->password)) {
        $oldPassMatch = 'matched';
    } else {
        $oldPassMatch = 'not_matched';
    }
    if ($validator->fails() || $oldPassMatch=='not_matched') {
        if($oldPassMatch == 'not_matched') {
           //$error['status'] = false;
           $error['msg'] = "Old Password does'nt match";
           return response()->json($error, 401);  
        }
         //$error['status'] = false;
         //$error['msg'] =$validator->errors(); 
         return response()->json($validator->errors(), 401);      
        
    }
    // updating password in database...
    $user = StaffUser::find(Auth::user()->id);
    $user->password = bcrypt($request->password);
    $user->save();
    //$success['status'] = true;
    /*Notification*/
      $title = sprintf(__('Password Update'));
      $message = sprintf(__('You Change Your Password Successfully'));
      log_activity($order, $description, anchor_link('#'.$order->unique_id.')', '#')  );
      salesmanNotification(Auth::id(),$title,$message); 
      $salesman = StaffUser::find(Auth::id());
      sendNotification($salesman->fcm_id,$title,$message);
      /*Notification*/
    $success['user_id'] = $user->id;
    $success['msg'] =  'Password changed successfully!';
    return response()->json($success, $this-> successStatus); 
  }

  // emailVerification
  // phoneVerification
  
  /*public function emailVerification(Request $request){
    $verified = Shopkeeper::where('email',$request->email)->first();
    if(!empty($verified)){
      if($request->otp!=''){
        if($request->otp == $verified->email_ver_code){
          $verified->email_ver_code = 0;
          $verified->email_verified = 1;
          $verified->save();
          $data['msg'] = 'Email Verified Successfully';
          $data['status'] = true;
          $status = $this-> successStatus;
        }else{
          $data['msg'] = 'Invalid OTP.Please check OTP';
          $data['status'] = false;
          $status = 401;
        }
      }else{
          $data['msg'] = 'Please Enter OTP';
          $data['status'] = false;
          $status = 401;
      }
    }else{
        $data['msg'] = 'Invalid Phone Number';
        $data['status'] = false;
        $status = 401;
    }
    return response()->json($data, $status); 
  }*/

  
  public function uploadImage(Request $request){  

    if($request->shopkeeper_id !=''){
      $shopkeeper = Shopkeeper::find($request->shopkeeper_id);
      if(empty($shopkeeper)){
        $data['msg'] = 'Shopkeeper not registered';
        $data['status'] = false;
        $status = 401;
      }else{
        $current_time = $shopkeeper->folder;
        $path = 'assets/shopkeeper/'.$current_time;
        /* Path Create for Uploading */
        if (!file_exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        /* Path Create for Uploading */

        $i = 0;
        $gumasta = $request->old_gumasta;
        $license = $request->old_license;
        $address_proof = $request->old_address_proof;
        $pan_card = $request->old_pan_card;
        $shop_license = $request->old_shop_license;
        $gst_document = $request->old_gst_document;
        $documents = [];

        if($request->hasFile('gumasta')){
          $gumasta = 'gumasta'.uniqid().rand(1111,9999) . '.jpg';
          $gumasta_location =  $path.'/'.$gumasta;
          $background = Image::canvas(570, 570);
          $resizedImage = Image::make($request->gumasta)->resize(570, 570, function ($c) {
              $c->aspectRatio();
          });
          // insert resized image centered into background
          $background->insert($resizedImage, 'center');
          // save or do whatever you like
          $background->save($gumasta_location);
          $documents[$i]['document_type'] = 'gumasta';
          $documents[$i]['image_name'] = $gumasta;
          $documents[$i]['is_verified'] = '0';
          $i++;
        }else{
          $documents[$i]['document_type'] = 'gumasta';
          $documents[$i]['image_name'] = $gumasta;
          $documents[$i]['is_verified'] = '0';
          $i++;
        }

        if($request->hasFile('license')){
          $license = 'license'.uniqid().rand(1111,9999) . '.jpg';
          $license_location =  $path.'/'.$license;
          $background = Image::canvas(570, 570);
          $resizedImage = Image::make($request->license)->resize(570, 570, function ($c) {
              $c->aspectRatio();
          });
          // insert resized image centered into background
          $background->insert($resizedImage, 'center');
          // save or do whatever you like
          $background->save($license_location);
          
          $documents[$i]['document_type'] = 'license';
          $documents[$i]['image_name'] = $license;
          $documents[$i]['is_verified'] = '0';
          $i++;
        }else{
          $documents[$i]['document_type'] = 'license';
          $documents[$i]['image_name'] = $license;
          $documents[$i]['is_verified'] = '0';
          $i++;
        }

        if($request->hasFile('address_proof')){
          $address_proof = 'address_proof'.uniqid().rand(1111,9999) . '.jpg';
          $address_proof_location =  $path.'/'.$address_proof;
          $background = Image::canvas(570, 570);
          $resizedImage = Image::make($request->address_proof)->resize(570, 570, function ($c) {
              $c->aspectRatio();
          });
          // insert resized image centered into background
          $background->insert($resizedImage, 'center');
          // save or do whatever you like
          $background->save($address_proof_location);

          $documents[$i]['document_type'] = 'address_proof';
          $documents[$i]['image_name'] = $address_proof;
          $documents[$i]['is_verified'] = '0';
          $i++;
        }else{
          $documents[$i]['document_type'] = 'address_proof';
          $documents[$i]['image_name'] = $address_proof;
          $documents[$i]['is_verified'] = '0';
          $i++;
        }

        if($request->hasFile('pan_card')){
          $pan_card = 'pan_card'.uniqid().rand(1111,9999) . '.jpg';
          $pan_card_location =  $path.'/'.$pan_card;
          $background = Image::canvas(570, 570);
          $resizedImage = Image::make($request->pan_card)->resize(570, 570, function ($c) {
              $c->aspectRatio();
          });
          // insert resized image centered into background
          $background->insert($resizedImage, 'center');
          // save or do whatever you like
          $background->save($pan_card_location);

          $documents[$i]['document_type'] = 'pan_card';
          $documents[$i]['image_name'] = $pan_card;
          $documents[$i]['is_verified'] = '0';
          $i++;
        }else{
          $documents[$i]['document_type'] = 'pan_card';
          $documents[$i]['image_name'] = $pan_card;
          $documents[$i]['is_verified'] = '0';
          $i++;
        }

        if($request->hasFile('shop_license')){
          $shop_license = 'shop_license'.uniqid().rand(1111,9999) . '.jpg';
          $shop_license_location =  $path.'/'.$shop_license;
          $background = Image::canvas(570, 570);
          $resizedImage = Image::make($request->shop_license)->resize(570, 570, function ($c) {
              $c->aspectRatio();
          });
          // insert resized image centered into background
          $background->insert($resizedImage, 'center');
          // save or do whatever you like
          $background->save($shop_license_location);

          $documents[$i]['document_type'] = 'shop_license';
          $documents[$i]['image_name'] = $shop_license;
          $documents[$i]['is_verified'] = '0';
          $i++;
        }else{
          $documents[$i]['document_type'] = 'shop_license';
          $documents[$i]['image_name'] = $shop_license;
          $documents[$i]['is_verified'] = '0';
          $i++;
        }

        if($request->hasFile('gst_document')){
          $gst_document = 'gst_document'.uniqid().rand(1111,9999) . '.jpg';
          $gst_document_location =  $path.'/'.$gst_document;
          $background = Image::canvas(570, 570);
          $resizedImage = Image::make($request->gst_document)->resize(570, 570, function ($c) {
              $c->aspectRatio();
          });
          // insert resized image centered into background
          $background->insert($resizedImage, 'center');
          // save or do whatever you like
          $background->save($gst_document_location);

          $documents[$i]['document_type'] = 'gst_document';
          $documents[$i]['image_name'] = $gst_document;
          $documents[$i]['is_verified'] = '0';
          $i++;
        }else{
          $documents[$i]['document_type'] = 'gst_document';
          $documents[$i]['image_name'] = $gst_document;
          $documents[$i]['is_verified'] = '0';
          $i++;
        }

        $shopkeeper->documents = json_encode($documents);
        $shopkeeper->save();

        if(!$request->file()){
          $data['documents'] = $documents;
          $data['msg'] = 'No Documents Uploaded';
          $data['status'] = false;
          $status = 401;
        }else{
            /*Notification*/
            $title = sprintf(__('Shopkeeper Documents Uploaded'));
            $message = 'For Shopkeeper '. $shopkeeper->name.' (Shop Name :'.$shopkeeper->shopname.') '.'All Documents Uploaded Successfully';
            salesmanNotification(Auth::id(),$title,$message); 
            $salesman = StaffUser::find(Auth::id());
            sendNotification($salesman->fcm_id,$title,$message);
            /*Notification*/
          $data['documents'] = $documents;
          $data['msg'] = 'Documents Uploaded Successfully';
          $data['status'] = true;
          $status = $this-> successStatus;
        }
      }
    }else{
      $data['msg'] = 'Please check Shopkeeper';
      $data['status'] = false;
      $status = 401;
    }
    return response()->json($data, $status);


    // $shopkeeper = Shopkeeper::find($request->shopkeeper_id);

    // echo json_encode(['filename' => $filename]);
  }

  public function salesmanRegistration(Request $request){

    $validator = Validator::make($request->all(), [ 
        'name' => 'required',
        'shop_name' => 'required',
        'email'=> 'required|email|max:255|unique:shopkeepers',
        'mobile' => 'required|unique:shopkeepers',
        'country' => 'required',
        'state' => 'required',
        'city' => 'required',
        'area'=> 'required',
        'latitude' => 'required',
        'longitude' => 'required',
        'address' => 'required',
        'password' => 'required',
    ]);

    if ($validator->fails()) { 
        return response()->json($validator->errors(), 401);            
    }

    $shopkeeper = new Shopkeeper;
    $shopkeeper->name = $request->name;
    $shopkeeper->shopname = $request->shop_name;
    $shopkeeper->email = $request->email;
    $shopkeeper->mobile = $request->mobile;
    $shopkeeper->password = Hash::make($request->password);
    $shopkeeper->country_id = $request->country;
    $shopkeeper->state_id = $request->state;
    $shopkeeper->city_id = $request->city;
    $shopkeeper->zipcode_id = $request->area;
    $shopkeeper->address = $request->address;
    $shopkeeper->latitude = $request->latitude;
    $shopkeeper->longitude = $request->longitude;
    $shopkeeper->status = '0';
    $shopkeeper->usergroup_id = $request->usergroup_id;
    $shopkeeper->user_role = $request->user_role;
    $shopkeeper->salesman_id = Auth::id();
    $shopkeeper->is_verified = 0;
    $shopkeeper->email_verified = 0;
    $shopkeeper->sms_verified = 0;
    $shopkeeper->sms_ver_code = rand(1 , 99999);
    $shopkeeper->email_ver_code = rand(1, 99999);
    $shopkeeper->folder = time();
    $shopkeeper->save();

    /* Generate Qr-Code */
    $url = route('admin.shopkeeper.show',$shopkeeper->id);
    $image = \QrCode::format('png')->merge(asset('assets/img/ss.png'), 0.3, true)
                        ->size(500)->errorCorrection('H')
                        ->generate($url);
    // $images = response($image)->header('Content-type','image/png');
    // $output_file = '/img/qr-code/img-' . time() . '.png';
    // Storage::disk('local')->put($output_file, $image);

    $filename = uniqid() . '.jpg';
    $location = 'assets/qrcode/' . $filename;

    $background = Image::canvas(570, 570);
    // insert resized image centered into background
    $background->insert($image, 'center');
    // save or do whatever you like
    $background->save($location);

    /* Generate Qr-Code */

    $update = Shopkeeper::find($shopkeeper->id);
    $update->qr_code = $location;
    $update->save();

    return response()->json($update, $this-> successStatus); 
  }

  public function shopkeepersList(Request $request,$shopkeeper_id=""){

    $sort = (isset($request->sort) && $request->sort!='') ? $request->sort : 'ASC';
    if($shopkeeper_id!=''){
      $shopkeepers = Shopkeeper::where('id',$shopkeeper_id)->where('salesman_id',Auth::id())->get();
    }else{
      $shopkeepers = Shopkeeper::where('salesman_id',Auth::id())->orderby('id',$sort)->get();
    }

    foreach ($shopkeepers as $key => $shopkeeper) {
      $shopkeeper->images = json_decode($shopkeeper->images);
      if(!empty($shopkeeper->images)){
        foreach ($shopkeeper->images as $image_key => $image) {
          $shopkeeper->images->$image_key = asset('assets/shopkeeper/'.$shopkeeper->folder.'/'.$image);
        }
      }

      $shopkeeper->documents = (!empty($shopkeeper->documents))?json_decode($shopkeeper->documents):'';
      if(!empty($shopkeeper->documents)){
        foreach($shopkeeper->documents as $key => $value){
          $value->image_name = asset('assets/shopkeeper/'.$shopkeeper->folder.'/'.$value->image_name);
        }
      }
    }

    $data = [];
    $data['sort'] = 'ASC';
    $data['shopkeepers'] = $shopkeepers;
    echo json_encode($data);
  }

  /*Get Valid Coupons and Offers*/
  public function getValidCoupons(){

    $coupons = Coupon::where('valid_till','>=',date_format(NOW(),"m/d/Y"))->orderby('id','DESC')->get();
  
        if(!$coupons->isEmpty()){
            $data['coupons'] = $coupons;
            $data['msg'] = 'Valid Coupons List';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['coupons'] = [];
            $data['msg'] = 'No Coupons Found';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status); 
  }

  public function orderHistory(Request $request,$shopkeeper_id="",$order_id=""){
    if($order_id!=''){
      $order_history = Order::where(['user_id' => $shopkeeper_id])->where('id',$order_id)->orderby('id','DESC')->get();
    }else{
      $order_history = Order::where(['user_id' => $shopkeeper_id])->orderby('id','DESC')->get();
    }
    
    if($order_history->isEmpty()){
      $data['order_history'] = [];
      $data['msg'] = "No Order Available";
      $data['status'] = false;
      $status = 401;
    }else{
      foreach ($order_history as $key => $value) {
        $value->orderedproducts;
        $value->total_products = count($value->orderedproducts);
        
        /* Status */
        if($value->approve == '1'){
          if($value->shipping_status == '0'){
            $value->order_status = 'Pending';
          }elseif($value->shipping_status == '1'){
            $value->order_status = 'In-Progress';
          }elseif($value->shipping_status == '2'){
            $value->order_status = 'Completed';
          }
        }else if($value->approve == '-1'){
          $value->order_status = 'Rejected';
        }else{
          $value->order_status = 'Pending';
        }
        /* Status */
      }

      $data['order_history'] = $order_history;
      $data['msg'] = "Order History";
      $data['status'] = true;
      $status = $this-> successStatus;
    }
    return response()->json($data, $status);
  }

  /*Salesman Sales Score List*/
  public function salesScore(Request $request){
    
    $user = Auth::guard('salesman')->user();
    
    $f_date = (isset($request->from_date) && !empty($request->from_date))?date('Y-m-d 00:00:00',strtotime($request->from_date)):date('Y-m-d H:i:s');
    
    $t_date = (isset($request->to_date) && !empty($request->to_date))?date('Y-m-d 23:59:59',strtotime($request->to_date)):date('Y-m-d H:i:s');
      
    $salesOrder = Order::where('staff_user_id', Auth::id())->whereBetween('updated_at', [$f_date, $t_date]);

    $total = 0;

    if(!empty($request->price_asc)){
      /*Price Low to High*/
      $salesOrder->orderby('total','asc');
    }else if(!empty($request->price_dsc)){
      /*Price High to Low*/
      $salesOrder->orderby('total','desc');
    }else{
      $salesOrder->orderby('total','desc');
    }
    $resp = $salesOrder->get();
    foreach($resp as $key => $resp_val){
      $total += $resp_val->total;
    }
    $data['order'] = $resp;
    $data['total'] = number_format($total,2);
    if(!$resp->isEmpty()){
        $data['msg'] = 'Salesman Sales Score List';
        $data['status'] = true;
        $status = $this-> successStatus;
    }else{
        $data['msg'] = 'No Sales Score Found';
        $data['status'] = false;
        $status = 401;
    }
    return response()->json($data, $status); 
  }


  public function paymentDetail(Request $request , $client_id="" , $client_type_id=""){

    $last_credit_amount = Transaction::where('client_id',$client_id)->where('credit','!=','')->where('client_type_id',$client_type_id)->orderby('id','DESC')->first();
    
    $last_debit_amount = Transaction::where('client_id',$client_id)->where('debit','!=','')->where('client_type_id',$client_type_id)->orderby('id','DESC')->first();

    $last_balance_amount = Transaction::where('client_id',$client_id)->where('client_type_id',$client_type_id)->orderby('id','DESC')->first();

    $data['data']['last_invoice_amount'] = (isset($last_credit_amount) && !empty($last_credit_amount))?$last_credit_amount->credit:'0.00';
    $data['data']['last_payment'] = (isset($last_debit_amount) && !empty($last_debit_amount))?$last_debit_amount->debit:'0.00';
    $data['data']['closing_balance'] = (isset($last_balance_amount) && !empty($last_balance_amount))?$last_balance_amount->after_balance:'0.00';
    // $data['transaction'] = $transactions;
    $data['msg'] = 'Last Transactions Detail';
    $data['status'] = true;
    $status = $this-> successStatus;
    return response()->json($data, $status);
  }

  public function paymentDetailList(Request $request , $shopkeeper_id="",$client_type_id=""){

    if(isset($request->year)){
        $datequery['year'] = "YEAR(created_at) = '".$request->year."'";
    }
    if(isset($request->month)){
        $datequery['month'] = "MONTH(created_at) = '".$request->month."'";
    }

    $date_where = '';
    if(!empty($datequery)){
        $date_where = ' && '.implode(' && ', $datequery);
    }

    $transactions = DB::select("SELECT * FROM `transactions` WHERE `client_id`='".$shopkeeper_id."' && `client_type_id`='".$client_type_id."' ".$date_where." ORDER BY id DESC");
    
    // $transactions = Transaction::where('client_id',$shopkeeper_id)->where('client_type_id','1')->get();
    
    if(!empty($transactions)){
      
      $last_balance_amount = Transaction::where('client_id',$shopkeeper_id)->where('client_type_id',$client_type_id)->orderby('id','DESC')->first();

      foreach($transactions as $transaction){
        $transaction->payment_name = (isset($transaction->payment_mode) && ($transaction->payment_mode!=''))?PaymentMode::find($transaction->payment_mode)->name:'-';
      }

      $data['transaction'] = $transactions;
      
      $data['closing_balance'] = (isset($last_balance_amount) && !empty($last_balance_amount))?$last_balance_amount->after_balance:'0.00';
      
      $data['msg'] = 'Transaction Details';
      $data['status'] = true;
      $status = $this-> successStatus;
    }else{
      $data['transaction'] = [];
      $data['msg'] = 'No Transaction';
      $data['status'] = false;
      $status = 401;
    }
   
    return response()->json($data, $status);
  }

  /*Add Sales Score[Salesman]*/

  public function addSalesScore(Request $request){
    $user = Auth::guard('salesman')->user();
    if(!empty($request->unique_id)){
      $order = Order::where('unique_id',$request->unique_id)->first();
      $order->staff_user_id = Auth::id();
      $order->staff_user_remarks = $request->remarks;
      $order->save();
      if(!empty($order)){
        /*Notification*/
        $title = sprintf(__('Sales Score Added'));
        $description = sprintf(__('Sales Score Added Successfully (Order Id '));

        // salesmanNotification(Auth::id(),$title,$message);
        // $description = sprintf(__('form.act_created') , __('form.proposal'));
        log_activity($order, $description, anchor_link('#'.$order->unique_id.')', '#')  );

        $salesman = StaffUser::find(Auth::id());
        sendNotification($salesman->fcm_id,$title,$description);
          /*Notification*/
        $data['msg'] = 'Sales Score Add successfully';
        $data['status'] = true;
        $status = $this-> successStatus;
      }else{
          $data['msg'] = 'No Sales Score Added';
          $data['status'] = false;
          $status = 401;
      }
    }else {
      $data['order'] = [];
      $data['msg'] = 'Please Enter Valid Order Id';
      $data['status'] = false;
      $status = 401;
    }
    return response()->json($data, $status); 
  }

  public function NotificationList(Request $request){

   /* if($request->q == 'last'){
        $resp = SalesmanNotification::where('salesman_id',Auth::id())->orderby('id','DESC')->first();
    }else{
      $resp = SalesmanNotification::where('salesman_id',Auth::id())->orderby('id','DESC')->get();
    }
    $data['notifications'] = $resp;
    $data['msg'] = 'Notification List';
    $data['status'] = true;
    return response()->json($data, $this-> successStatus);*/
      if($request->q == 'last'){
          $query    = Activity::where('causer_id',Auth::user()->id)->orderBy('id', 'DESC')->limit(1);
          $data = $query->get();
      }else{
         $query    = Activity::where('causer_id',Auth::user()->id)->orderBy('id', 'DESC');
         $data = $query->get();
      }
        
        $rec = array();
        if (count($data) > 0)
        {
            foreach ($data as $key => $row)
            {
                $causer = $row->causer()->withTrashed()->get()->first();
                $rec[$key]['time'] =    \Carbon\Carbon::parse($row->created_at)->diffForHumans();
                // $rec[ $key]['time'] =    \Carbon\Carbon::parse($row->created_at)->diffForHumans();
                //$rec[ $key]['name'] =    $causer->first_name . " " . $causer->last_name ;
                $rec[ $key]['description'] =   strip_tags($row->description) . " ". strip_tags($row->getExtraProperty('item'));
                $rec[ $key]['created_at'] =  data('Y-m-d H:i:s:u',strtotime($row->created_at));
            }
            $output = array(
                "notificationlist" => $rec,
                "msg"=>'Notification List',
                "status" => true
            );
            $status = $this-> successStatus;
        }else{
             $output = array(
                "notificationlist" => $rec,
                "msg"=>'No Notification Found',
                "status" => false
            );
            $status = 401;
        }
        return response()->json($output,$status);
  }


}