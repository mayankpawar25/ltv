<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use Hash;
use DB;
use App\GeneralSetting as GS;
use Carbon\Carbon;
use App\Cart;
use App\PlacePayment;
use Session;
use App\Order;
use App\Coupon;
use App\Product,App\Option;
use App\Orderedproduct;
use App\Orderpayment;
use App\User;
use App\Vendor;
use App\Category,App\Subcategory,App\OrderRequestForDeliveryBoy;
use App\Models\Tags;
use App\Country;
use App\City;
use App\State;
use App\Slider;
use App\Zipcode;
use App\Gateway as Gateway;
use App\UserNotification;
class UserController extends Controller 
{
    public $successStatus = 200;
    /** 
     * login API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(){ 

    	if(is_numeric(request('email'))){
    	 	if(Auth::attempt(['phone' => request('email'), 'password' => request('password')])){ 
	            $user = Auth::user();
              if($user->sms_verified!=1){
                $error['token'] = $user->createToken('MyApp')-> accessToken;
                $error['status'] = false;
                $error['msg'] = 'Mobile OTP not Verified';
                $error['sms_otp'] = $user->sms_ver_code;
                $error['verified'] = false;
                return response()->json($error, 401);
              }
	            $fcm_id = request('fcm_id'); 
	            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
	            $success['user_id'] =  $user->id;
              $success['name'] =  $user->first_name.' '.$user->last_name;
	            if(isset($success['token'])){
	            	User::where('id', $user->id)->update(['fcm_id' => $fcm_id]);
	            }
              $success['status'] = true;
              $success['verified'] = true;
              $success['msg'] = 'Log in success';
	            return response()->json($success, $this-> successStatus); 
	        } 
	        else{ 
                $error['status'] = false;
                $error['msg'] = 'Unauthorised Username or Password';
	            return response()->json($error, 401); 
	        } 
        }
        elseif (filter_var(request('email'), FILTER_VALIDATE_EMAIL)) {
	        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
	            $user = Auth::user();
              if($user->sms_verified!=1){
                $error['token'] = $user->createToken('MyApp')-> accessToken;
                $error['status'] = false;
                $error['msg'] = 'Mobile OTP not Verified';
                $error['sms_otp'] = $user->sms_ver_code;
                $error['verified'] = false;
                return response()->json($error, 401);
              }
	            $fcm_id = request('fcm_id');  
	            $success['token'] =  $user->createToken('MyApp')-> accessToken;
	            $success['user_id'] =  $user->id;
              $success['name'] =  $user->first_name.' '.$user->last_name;
	            if(isset($success['token'])){
	            	User::where('id', $user->id)->update(['fcm_id' => $fcm_id]);
	            } 
              $success['status'] = true;
              $success['msg'] = 'Log in success';
              $success['verified'] = true;
	            return response()->json($success, $this-> successStatus); 
	        } 
	        else{ 
              $error['status'] = false;
              $error['msg'] = 'Unauthorised Username or Password';
	            return response()->json($error, 401);
	        } 
        }
        else{ 
            $error['status'] = false;
                $error['msg'] = 'Unauthorised Username or Password';
            return response()->json($error, 401); 
        }
        
        /*if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['success' => $success], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } */
    }
     /** 
     * Register API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) { 
        $validator = Validator::make($request->all(), [ 
            'email'    => 'required|email|max:255|unique:users', 
            'password' => 'required',
            'phone'    => 'required', 
            'fcm_id'   => 'required', 
            'first_name' => 'required',
            'username' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json($validator->errors(), 401);            
        }
        /*$input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); */

        $user             = new User;
        $user->name       = $request->username;
        $user->username   = $request->username;
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;;
        $user->phone      = $request->phone;
        $user->password   = Hash::make($request->password);
        $user->fcm_id     = $request->fcm_id;
        $user->sms_ver_code = '1234';
        $user->token      =  $user->createToken('MyApp')-> accessToken; 
        $user->save(); 

        $success['token'] =  $user->createToken('MyApp')-> accessToken; 
        $success['first_name'] =  $user->first_name;
        $success['sms_otp'] =  $user->sms_ver_code;
        $success['user_id'] =  $user->id;
        return response()->json($success, $this-> successStatus); 
    }
     /** 
     * details API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() { 
        $user = Auth::user();
        $user['status'] = true; 
        return response()->json($user, $this-> successStatus); 
    } 

    /** 
     * Update Profile API 
     * 
     * @return \Illuminate\Http\Response 
     */ 

    public function infoupdate(Request $request){
	    $validator = Validator::make($request->all(),[
	        'first_name' => 'required',
	        'last_name' => 'required',
	        'gender' => 'required',
	        'date_of_birth' => 'required',
	        'phone' => 'required',
	        'address' => 'required',
	        'country' => 'required',
	        'state' => 'required',
	        'city' => 'required',
	        'zip_code' => 'required',
	    ]);
	    	if ($validator->fails()) { 
                 //$error['status'] = false;
                 //$error['msg'] =$validator->errors(); 
                return response()->json($validator->errors(), 401);                
	        }
	       
	       /* $in = $request->except('_token');
	        $user = User::find(Auth::user()->id);
		    if (empty($user->shipping_first_name)) {
		        $in['shipping_first_name'] = $request->first_name;
		    }
		    if (empty($user->shipping_last_name)) {
		        $in['shipping_last_name'] = $request->last_name;
		    }
		    if (empty($user->shipping_phone)) {
		        $in['shipping_phone'] = $request->phone;
		    }
  		$user->fill($in)->save();
          //$success['status'] = true;*/
        $user = User::find(Auth::user()->id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->gender = $request->gender;
        $user->date_of_birth = $request->date_of_birth;
        $user->phone = $request->phone;
        $user->latitude = $request->latitude;
        $user->longitude = $request->longitude;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->country_id = $request->country_id;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->zip_code = $request->zip_code;

        $user->billing_first_name = $request->billing_first_name;
        $user->billing_last_name  = $request->billing_last_name;
        $user->billing_email      = $request->billing_email;
        $user->billing_phone      = $request->billing_phone;
        $user->billing_address= $request->billing_address;
        $user->billing_country= $request->billing_country;
        $user->billing_state = $request->billing_state;
        $user->billing_city       = $request->billing_city;
        $user->billing_zip_code    = $request->billing_zip_code;

        $user->shipping_first_name = $request->shipping_first_name;
        $user->shipping_last_name = $request->shipping_last_name;
        $user->shipping_email = $request->shipping_email;
        $user->shipping_phone = $request->shipping_phone;
        $user->shipping_address = $request->shipping_address;
        $user->shipping_city = $request->shipping_city;
        $user->shipping_state  = $request->shipping_state;  
        $user->shipping_zip_code  = $request->shipping_zip_code;   
        $user->shipping_country_id = $request->shipping_country_id;
        $user->shipping_is_same_as_billing = $request->shipping_is_same_as_billing;
        $user->save();

  		$success['user_id'] = $user->id;
  		$success['msg'] =  'Informations updated successfully';
  		return response()->json($success, $this-> successStatus); 
    }

    /** 
     * Forgot Password API 
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
      $user = User::find(Auth::user()->id);
      $user->password = bcrypt($request->password);
      $user->save();
      //$success['status'] = true;
      $success['user_id'] = $user->id;
  		$success['msg'] =  'Password changed successfully!';
      return response()->json($success, $this-> successStatus); 
    }


   /** 
     * Instamojo Success URL API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function success($order_id="",Request $request){
      $instamojo = Gateway::find(100);
      try {
          $api = new \Instamojo\Instamojo(
              $instamojo->val1, //API Key
              $instamojo->val2, //Auth Key
              $instamojo->val3 // Instamojo url
          );
          $response = $api->paymentRequestStatus(request('payment_request_id'));

          if( (!isset($response['payments'][0]['status'])) && ($response['payments'][0]['status'] != 'Credit') && ($request->status != 'Completed') ) {
             /*Payment failed*/
             $user           = Orderpayment::where('order_id', $order_id)->first();
             $user->trx      = $response['payments'][0]['payment_id'];
             $user->status   =$status = ($response['status'] =='Completed') ? 1 : 0;
             $user->save();  
             $success['msg'] = "Faild!";
             //return response()->json($success);
             return view('payment_fail');
          } else {  
          
            /*Payment Success*/
             //print_r($response);
             $user           = Orderpayment::where('order_id', $order_id)->first();
             $user->trx      = $response['payments'][0]['payment_id'];
             $user->status   =$status = ($response['status'] =='Completed') ? 1 : 0;
             $user->save();  
              $success['payment_id'] = $response['payments'][0]['payment_id'];
              $success['msg'] = "successfully!";
             //return response()->json($success);

              return view('payment_success',$success);

          } 
        }catch (\Exception $e) {
          /*Payment failed*/
             $user           = Orderpayment::where('order_id', $order_id)->first();
             $user->trx      = $response['payments'][0]['payment_id'];
             $user->status   =$status = ($response['status'] =='Completed') ? 1 : 0;
             $user->save();  
             $success['msg'] = "Faild!";
             //return response()->json($success);
             return view('payment_fail');
           //dd('payment failed');
       }
       
    }
 
    /* Function For Testing Purpose*/

     /** 
     * Instamojo Create Payment API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function pay(Request $request){
      $instamojo = Gateway::find(100);
      $api = new \Instamojo\Instamojo(
      $instamojo->val1, //API Key
      $instamojo->val2, //Auth Key
      $instamojo->val3 // Instamojo url
      );
      try {
        $response = $api->paymentRequestCreate(array(
          "purpose" => "FIFA 16",
          "amount" => $request->amount,
          "buyer_name" => "$request->name",
          "send_email" => true,
          "email" => "$request->email",
          "phone" => "$request->mobile_number",
          "redirect_url" => "http://localhost:8080/computer_shoppie/api/pay-success/21/"
        ));

        header('Location: ' . $response['longurl']);
        exit();
      }catch (Exception $e) {
        print('Error: ' . $e->getMessage());
      }
    }
  /* Function For Testing Purpose*/

  /** 
   * Instamojo Instamojo Credentials (API key , Auth token, Instamojo URL) API 
   * 
   * @return \Illuminate\Http\Response 
   */ 
  public function instamojoCredentials(Request $request){
    $data = array();
    $instamojo = Gateway::find(100);
    $data['paymentgateway_id']   = $instamojo->id;
    $data['paymentgateway_name'] = $instamojo->name;
    $data['api_key']             = $instamojo->val1;
    $data['auth_token']          = $instamojo->val2;
    $data['instamojo_url']       = $instamojo->val3;
    $data['status']              = true;
    return response()->json($data, $this-> successStatus);
  }

 /** 
   * User Order History API 
   * 
   * @return \Illuminate\Http\Response 
   */ 
  public function orderHistory(Request $request,$order_id=""){

    if($order_id!=''){
      $order_history = Order::where(['user_id' => Auth::id()])->where('id',$order_id)->orderby('id','DESC')->get();
    }else{
      $order_history = Order::where(['user_id' => Auth::id()])->orderby('id','DESC')->get();
    }
    
    if($order_history->isEmpty()){
      $data['order_history'] = [];
      $data['msg'] = "No Order Found";
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

   /*{ User : Customer} Cancel Order */
  public function orderCancel(Request $request){
    $order = Order::find($request->order_id);
    if(!empty($order)){
      $order->approve = '-1';
      $order->save();

      $user = User::find(Auth::id());
      $title = 'Order Status';
      $message = 'Order Id #'.$request->order_id.' is Cancelled';
      
      /* Save Notification */
      /*$saveNotification = new ShopkeeperNotification();
      $saveNotification->shopkeeper_id = $user->id;
      $saveNotification->title = $title;
      $saveNotification->message = $message;
      $saveNotification->is_viewed = '0';
      $saveNotification->save();*/
      /* Save Notification */

      $this->sendNotification($user->fcm_id,$title,$message);
      
      $data['msg'] = $message;
      $data['status'] = true;

    }else{
      $data['msg'] = 'Order Id not available';
      $data['status'] = false;
    }

    return response()->json($data, $this-> successStatus);

  }

  public function sendNotification($regId,$orderId){

    define('FIREBASE_API_KEY', 'AAAArRMA6iM:APA91bFI6jvM167-lG9DkRX8Pnp0YDuJNjcSlYFDL4V2sBxY9oTtEAV-bqH1iX5NC7QhjuV2Jb7pZd5wctUkzzYOMxvNtqDibMBGKJFKTT2TaBZuiEebMBbBPdqShoG7-BVi_nB3tnWh');

    $title="Order Id #".$orderId;
    $message="New Order Check It";

    $message = strip_tags($message);        
    $title = strip_tags($title);
    $orderId = strip_tags($orderId);

    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\r\n \"to\" : \"$regId\",\r\n \"collapse_key\" : \"type_a\",\r\n \"notification\" : {\r\n \"body\" : \"$message\",\r\n \"title\": \"$title\"\r\n },\r\n \"data\" : {\r\n \"body\" : \"$message\",\r\n \"title\": \"$title\",\r\n \"key_1\" : \"$orderId\" }\r\n}",
    CURLOPT_HTTPHEADER => array(
    "Authorization: key=".FIREBASE_API_KEY,
    "Cache-Control: no-cache",
    "Content-Type: application/json",
    "Postman-Token: 17dca3af-6994-4fe7-b8ec-68f99d13cfe8"
    ),
    ));

    // echo $curl;

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);



    /*echo $response;
    exit();*/
    return $orderId;
  }

  /*User Cart Details*/
  public function cartDetail(Request $request){

    $gs = GS::first();
    $product_details = json_decode($request->product_detail);
    $data = [];
    $subtotal = 0;
    $total = 0;
    $tax_percentage = 0;
    $tax_amount = 0;
    $total = 0;
    $total_items = 0;

    $group_disc = '0';
    $user = User::find(Auth::id());
    /*$user_group = UserGroup::find($user->usergroup_id);
    if(!empty($user_group)){
      $group_disc = $user_group->percentage;
    }*/

    foreach ($product_details as $key => $product) {
      $products = Product::find($product->product_id);

      $product_price = $products->price;
      if($group_disc !='0'){
        $group_disc_amount = ($products->price*$group_disc)/100;
        $product_price = $product_price - $group_disc_amount;
      }
      $products->price = $product_price;
      $products->description = strip_tags($products->description);
      $products->attributes = $product->attributes;
      $products->cart_quantity = $product->quantity;
      $products->cart_amount = $product->quantity*$product_price;
      foreach($products->previewimages as $images){
          $images->image = asset('assets/user/img/products/'.$images->image);
          $images->big_image = asset('assets/user/img/products/'.$images->big_image);
        }
      $data['products'][] = $products;
      $subtotal += $products->cart_amount;
      $total_items += $product->quantity;
    }
    $tax_percentage = $gs->tax;
    $tax_amount = ( $subtotal * ( $tax_percentage / 100 ) );
    $total = $subtotal+$tax_amount;
    $data['total_items'] = $total_items;
    $data['subtotal'] = number_format($subtotal,2);
    $data['tax_percentage'] = $tax_percentage;
    $data['tax_amount'] = number_format($tax_amount,2);
    $data['total'] = number_format($total,2);
    return response()->json($data, $this->successStatus);
  }

  /*User Place Order*/
  public function placeOrder(Request $request){

    $validator = Validator::make($request->all(),[
        'product_detail' => 'required',
        'sub_total' => 'required',
        'total' => 'required',
        'payment_method' => 'required',
        'payment_method' => 'required',
        ],['product_detail.required'=>'Add Product in Cart']);

        if ($validator->fails()) { 
          return response()->json($validator->errors(), 401);                
        }

    $gs = GS::first();
    // store in order table
    // $in = $request->except('_token', 'coupon_code', 'terms', 'terms_helper');
    $user = User::find(Auth::id());

    /*$group_disc = '0';
    $user_group = UserGroup::find($user->usergroup_id);
    if(!empty($user_group)){
      $group_disc = $user_group->percentage;
    }*/
    $in['user_id']     = Auth::id();
    $in['first_name']  = $request->name;
    $in['last_name']   = $request->last_name;
    $in['phone']       = $request->phone;
    $in['email']       = $request->email;
    $in['address']     = $request->address;
    $in['country']     = $request->country;
    $in['state']       = $request->state;
    $in['city']        = $request->city;
    $in['zip_code']    = $request->zip_code;
    $in['order_notes'] = $request->order_notes;
    $in['subtotal']    = $this->getSubTotal($request->product_detail,$request->coupon_code);
    $in['total']       = $this->getTotal($request->product_detail,$request->payment_method,$request->place,$request->shipping_charge,$request->coupon_code);
    $in['place'] = 'in';
    $pm = $request->payment_method;
    $place = $request->place;
    
    

    // if payment method is cash on delivery
    if ($pm == 1) {
      if ($place == 'in') {
        $scharge = $gs->in_cash_on_delivery;
      } elseif ($place == 'around') {
        $scharge = $gs->around_cash_on_delivery;
      } else {
        $scharge = $gs->world_cash_on_delivery;
      }
    }
    // if payment method is cash on advance
    else {
      if ($place == 'in') {
        $scharge = $gs->in_advanced;
      } elseif ($place == 'around') {
        $scharge = $gs->around_advanced;
      } else {
        $scharge = $gs->world_advanced;
      }
    }

    $in['shipping_charge'] = $request->shipping_charge;
    $in['tax'] = $request->tax;
    $in['payment_method'] = $pm;
    $in['shipping_method'] = $place;
    $in['user_type']     = $request->user_type;
    $order = Order::create($in);
    $order->unique_id = $order->id + 100000;
    $order->save();

    $carts = Cart::where('cart_id', Auth::user()->id)->get();
    // $carts = json_decode($request->product_detail);

    // store products in orderedproducts table
    foreach($carts as $cart) {

      $op = new Orderedproduct;
      $op->user_id = Auth::id();
      $op->order_id = $order->id;
      $op->vendor_id = 1;
      $op->product_id = $cart->product_id;
      $op->product_name = $cart->title;
      $op->product_price = $cart->price;
      //$op->offered_product_price = $cart->current_price;
      $op->attributes = json_encode($cart->attributes);

      if ($request->coupon_code) {
        //$csession = session('coupon_code');
        $coupon = Coupon::where('coupon_code', $request->coupon_code)->first();
        if ($coupon->coupon_type=='percentage') {
          // if coupon type is percentage
          if (empty($cart->current_price)) {
            // if the product has no offer...
            $cartItemTotal = $cart->quantity*$cart->price;
            $cartItemCoupon = ($cartItemTotal*$coupon->coupon_amount)/100;
            $producttotal = $cartItemTotal - $cartItemCoupon;
          } else {
            // if the product has offer...
            $cartItemTotal = $cart->quantity*$cart->current_price;
            $cartItemCoupon = ($cartItemTotal*$coupon->coupon_amount)/100;
            $producttotal = $cartItemTotal - $cartItemCoupon;
          }
        }else{
          // if coupon type is fixed
          //$cartItems = Cart::where('cart_id', Auth::user()->id)->get();
          $cartItems = json_decode($request->product_detail);
          $amo = 0;
          foreach ($cartItems as $item) {
            if (!empty($item->current_price)) {
              $amo += $item->current_price*$item->quantity;
            } else {
              $amo += $item->price*$item->quantity;
            }
          }

          $charpertaka = $coupon->coupon_amount/$amo;


          if (empty($cart->current_price)) {
            $cartItemTotal = $cart->quantity*$cart->price;
            $cartItemCoupon = $cartItemTotal*$charpertaka;
            $producttotal = $cartItemTotal-$cartItemCoupon;
          } else {
            $cartItemTotal = $cart->quantity*$cart->current_price;
            $cartItemCoupon = $cartItemTotal*$charpertaka;
            $producttotal = $cartItemTotal-$cartItemCoupon;
          }

        }
      } else {
        if (empty($cart->current_price)) {
          // if cart item has no offer
          $producttotal = $cart->price*$cart->quantity;
          $cartItemCoupon = 0;
        } else {
          // if cart item has offer
          $producttotal = $cart->current_price*$cart->quantity;
          $cartItemCoupon = 0;
        }
      }

      $op->quantity = $cart->quantity;
      $op->product_total = $producttotal;
      $op->coupon_amount = $cartItemCoupon;
      $op->save();
    }
    /* $success['status'] = true; */
    /*return response()->json($success, $this-> successStatus); */

    $title = "New Order Placed";
    $message = "Your order has been placed successfully. Order ID:" . $order->unique_id;
    
    //$this->sendNotification($user->fcm_id,$title,$message);
    /* Save Notification */
   /* $saveNotification = new ShopkeeperNotification();
    $saveNotification->shopkeeper_id = $user->id;
    $saveNotification->title = $title;
    $saveNotification->message = $message;
    $saveNotification->is_viewed = '0';
    $saveNotification->save();*/
    /* Save Notification */
    /* Save Notification */
    $saveNotification = new UserNotification();
    $saveNotification->customer_id = $user->id;
    $saveNotification->title = $title;
    $saveNotification->message = $message;
    $saveNotification->is_viewed = '0';
    $saveNotification->save();
    /* Save Notification */

    if ($request->payment_method == 1) {
      $success['status'] = true;
      $success['order_id'] = $order->id;
      $success['invoice_id'] = $order->unique_id;
      $success['msg'] = "Your order has been placed successfully! Our agent will contact with you later. Order ID: " . $order->unique_id;
      return response()->json($success, $this-> successStatus); 
    } elseif ($request->payment_method == 2) {
      $payment['order_id'] = $order->id;
      $payment['user_id'] = Auth::user()->id;
      $payment['gateway_id'] = 100;
      $payment['amount'] =  $op->product_total;
      $payment['btc_amo'] = 0;
      $payment['btc_wallet'] = "";
      $payment['try'] = 0;
      $payment['status'] = 0;
      Orderpayment::create($payment);
      $success['status'] = true;
      $success['order_id'] = $order->id;
      $success['invoice_id'] = $order->unique_id;
      $success['msg'] = "Your order has been placed successfully! Our agent will contact with you later. Order ID: " . $order->unique_id ." Transactions ID:" . $request->trx_id ."";
      return response()->json($success, $this-> successStatus);
    // after payment clear Cart and redirect to success page
    }
  }

  /*Get Valid Coupons and Offers*/
  public function getValidCoupons(Request $request){
    $code = Coupon::where('coupon_code',$request->coupon_code)->get();
    if (!$code->isEmpty()) {
    $coupons = Coupon::where('valid_till','>=',date_format(NOW(),"m/d/Y"))->where('coupon_code',$request->coupon_code)->get();
        if(!$coupons->isEmpty()){
            $data['coupons'] = $coupons;
            $data['msg'] = 'Coupons List';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['coupons'] = [];
            $data['msg'] = 'No Valid Coupon Found.. Please Check Your Coupon Code Or Your Coupon code Already Expired';
            $data['status'] = false;
            $status = 401;
        }
    }else {
      $data['coupons'] = [];
      $data['msg'] = 'No Coupon Found';
      $data['status'] = false;
      $status = 401;
    }
    return response()->json($data, $status); 
  }
  
  public function getSubTotal($cartdetail,$coupon_code=""){

    $cartItems = json_decode($cartdetail);
    $cartItems = Cart::where('cart_id', Auth::user()->id)->get();
    $amo = 0;
    foreach ($cartItems as $item) {
      /*if (!empty($item->current_price)) {
        $amo += $item->current_price*$item->quantity;
      } else {*/
        $amo += $item->price*$item->quantity;
      // }
    }
    $char = 0;
    $coupon = $coupon_code;
    if($coupon!='' && Coupon::where('coupon_code', $coupon)->count() == 1){
      $cdetails = Coupon::where('coupon_code', $coupon)->latest()->first();
      if ($cdetails->coupon_type == 'percentage'){
        $char = ($amo*$cdetails->coupon_amount)/100;
      }else{
        if($cdetails->coupon_min_amount <= $amo){
          $char = $cdetails->coupon_amount;
        }
      }
    }
    $subtotal = $amo - $char;
    return round($subtotal, 2);
  }

  public function getTotal($cartdetail,$pm=1,$place='in',$scharge="0",$coupon_code=""){
    $cartItems = json_decode($cartdetail);
    $cartItems = Cart::where('cart_id', Auth::user()->id)->get();
    $subtotal = $this->getSubTotal($cartdetail,$coupon_code);
    $gs = GS::first();
    if (count($cartItems) > 0) {
      // $pm = $request->payment_method;
      // $place = $request->place;

      /*// if payment method is cash on delivery
      if ($pm == 1) {
        if ($place == 'in') {
          $scharge = $gs->in_cash_on_delivery;
        } elseif ($place == 'around') {
          $scharge = $gs->around_cash_on_delivery;
        } else {
          $scharge = $gs->world_cash_on_delivery;
        }
      }
      // if payment method is cash on advance
      else {
        if ($place == 'in') {
          $scharge = $gs->in_advanced;
        } elseif ($place == 'around') {
          $scharge = $gs->around_advanced;
        } else {
          $scharge = $gs->world_advanced;
        }
      }
    } else {
      $scharge = 0;
    */
    }


    $total = $subtotal + (($gs->tax*$subtotal)/100);
    $total = $total+$scharge;

    // echo json_encode([$total,$total1,$scharge]);
    // exit;
    return round($total, 2);
  }

  public function socialLogin1(Request $request){
      if(!empty($request->email)){
           // check if they're an existing user
          $existingUser = User::where('email', $request->email)->first();
          if($existingUser){
            //Google
              if(($request->provider == 'Google') && ($request->provider_id ==$existingUser->provider_id )){
                $data['token'] =  $existingUser->createToken('MyApp')-> accessToken; 
                $data['user_id'] =  $existingUser->id;
                if(isset($data['token'])){
                  User::where('id', $existingUser->id)->update(['fcm_id' => $request->fcm_id]);
                }
                $data['provider'] = 'Google';
                $data['status'] = true;
                $data['msg'] = 'Log in success';
                $status = $this-> successStatus;
              }elseif (($request->provider == 'Facebook') && ($request->provider_id == $existingUser->fb_provider_id )) {
                //Facebook
                $data['token'] =  $existingUser->createToken('MyApp')-> accessToken; 
                $data['user_id'] =  $existingUser->id;
                if(isset($data['token'])){
                  User::where('id', $existingUser->id)->update(['fcm_id' => $request->fcm_id]);
                }
                $data['provider'] = 'Facebook';
                $data['status'] = true;
                $data['msg'] = 'Log in success';
                $status = $this-> successStatus;
              }else {
                $data['msg'] = "Something went wrong.Please try again after some time";
                $data['status'] = false;
                $status = 401;
              }
              return response()->json($data, $status); 
          } else {
              if($request->provider == 'Google'){
                   // create a new user
                $newUser                  = new User;
                $newUser->name            = $request->name;
                $newUser->email           = $request->email;
                $newUser->provider        = 'Google';
                $newUser->provider_id     = $request->provider_id;
                $newUser->fcm_id          = $request->fcm_id;
                $newUser->token           = $newUser->createToken('MyApp')-> accessToken; 
                $save                     = $newUser->save();
                if($save){
                   $data['token'] =  $newUser->createToken('MyApp')-> accessToken; 
                   $data['user_id'] =  $newUser->id;
                   $data['status'] = true;
                   $data['provider'] = 'Google';
                   $data['msg'] = 'Register successfully';
                   return response()->json($data, $this-> successStatus); 
                }else {
                  $data['status'] = false;
                  $data['msg'] = 'Unauthorised Username or Password';
                return response()->json($data, 401); 
                }
              }else{
                    // create a new user
                $newUser                  = new User;
                $newUser->name            = $request->name;
                $newUser->email           = $request->email;
                $newUser->fb_provider     = 'Facebook';
                $newUser->fb_provider_id  = $request->provider_id;
                $newUser->fcm_id          = $request->fcm_id;
                $newUser->token           = $newUser->createToken('MyApp')-> accessToken; 
                $save                     = $newUser->save();
                if($save){
                   $data['token'] =  $newUser->createToken('MyApp')-> accessToken; 
                   $data['user_id'] =  $newUser->id;
                   $data['status'] = true;
                   $data['provider'] = 'Facebook';
                   $data['msg'] = 'Register successfully';
                   return response()->json($data, $this-> successStatus); 
                }else {
                  $data['status'] = false;
                  $data['msg'] = 'Unauthorised Username or Password';
                return response()->json($data, 401); 
                }
              }
             
          }
      }else{
        $data['msg'] = "Please Check Your Email Id";
        $data['status'] = false;
        $status = 401;
      }
      return response()->json($data, $status);
  }


  public function socialLogin(Request $request){
    if(!empty($request->email)){
         // check if they're an existing user
        $existingUser = User::where('email', $request->email)->first();
        if($existingUser){
          //Google
            if($request->provider == 'Google'){
              if($request->provider_id ==$existingUser->provider_id ){
                $data['token'] =  $existingUser->createToken('MyApp')-> accessToken; 
                $data['user_id'] =  $existingUser->id;
                if(isset($data['token'])){
                  User::where('id', $existingUser->id)->update(['fcm_id' => $request->fcm_id]);
                }
                $data['provider'] = 'Google';
                $data['status'] = true;
                $data['msg'] = 'Log in success';
                $status = $this-> successStatus;
              }else{
                // Update Data
                $user = User::find($existingUser->id);
                $user->name            = $request->name;
                $user->email           = $request->email;
                $user->provider        = 'Google';
                $user->provider_id     = $request->provider_id;
                $save                  = $user->save();
                $data['token'] =  $existingUser->createToken('MyApp')-> accessToken; 
                $data['user_id'] =  $existingUser->id;
                if(isset($data['token'])){
                  User::where('id', $existingUser->id)->update(['fcm_id' => $request->fcm_id]);
                }
                $data['provider'] = 'Google';
                $data['status'] = true;
                $data['msg'] = 'Log in success';
                $status = $this-> successStatus;
              }
            }elseif ($request->provider == 'Facebook'){
              if($request->provider_id == $existingUser->fb_provider_id ){
                 //Facebook
                $data['token'] =  $existingUser->createToken('MyApp')-> accessToken; 
                $data['user_id'] =  $existingUser->id;
                if(isset($data['token'])){
                  User::where('id', $existingUser->id)->update(['fcm_id' => $request->fcm_id]);
                }
                $data['provider'] = 'Facebook';
                $data['status'] = true;
                $data['msg'] = 'Log in success';
                $status = $this-> successStatus;
              }else {
                 // Update Data
                $user = User::find($existingUser->id);
                $user->name            = $request->name;
                $user->email           = $request->email;
                $user->fb_provider        = 'Facebook';
                $user->fb_provider_id     = $request->provider_id;
                $save                  = $user->save();
                $data['token'] =  $existingUser->createToken('MyApp')-> accessToken; 
                $data['user_id'] =  $existingUser->id;
                if(isset($data['token'])){
                  User::where('id', $existingUser->id)->update(['fcm_id' => $request->fcm_id]);
                }
                $data['provider'] = 'Facebook';
                $data['status'] = true;
                $data['msg'] = 'Log in success';
                $status = $this-> successStatus;
              }
            }else {
              $data['msg'] = "Something went wrong.Please try again after some time";
              $data['status'] = false;
              $status = 401;
            }
            return response()->json($data, $status); 
        } else {
            if($request->provider == 'Google'){
                 // create a new user
              $newUser                  = new User;
              $newUser->name            = $request->name;
              $newUser->email           = $request->email;
              $newUser->provider        = 'Google';
              $newUser->provider_id     = $request->provider_id;
              $newUser->fcm_id          = $request->fcm_id;
              $newUser->token           = $newUser->createToken('MyApp')-> accessToken; 
              $save                     = $newUser->save();
              if($save){
                 $data['token'] =  $newUser->createToken('MyApp')-> accessToken; 
                 $data['user_id'] =  $newUser->id;
                 $data['status'] = true;
                 $data['provider'] = 'Google';
                 $data['msg'] = 'Register successfully';
                 return response()->json($data, $this-> successStatus); 
              }else {
                $data['status'] = false;
                $data['msg'] = 'Unauthorised Username or Password';
              return response()->json($data, 401); 
              }
            }else{
                  // create a new user
              $newUser                  = new User;
              $newUser->name            = $request->name;
              $newUser->email           = $request->email;
              $newUser->fb_provider     = 'Facebook';
              $newUser->fb_provider_id  = $request->provider_id;
              $newUser->fcm_id          = $request->fcm_id;
              $newUser->token           = $newUser->createToken('MyApp')-> accessToken; 
              $save                     = $newUser->save();
              if($save){
                 $data['token'] =  $newUser->createToken('MyApp')-> accessToken; 
                 $data['user_id'] =  $newUser->id;
                 $data['status'] = true;
                 $data['provider'] = 'Facebook';
                 $data['msg'] = 'Register successfully';
                 return response()->json($data, $this-> successStatus); 
              }else {
                $data['status'] = false;
                $data['msg'] = 'Unauthorised Username or Password';
              return response()->json($data, 401); 
              }
            }
        }
    }else{
      $data['msg'] = "Please Check Your Email Id";
      $data['status'] = false;
      $status = 401;
    }
    return response()->json($data, $status);
  }

  public function cartList(){
    $cart_details = Cart::where('cart_id',auth()->id())->get();
    $total = 0;
    $total_quantity = 0;
    foreach ($cart_details as $key => $cart){
      $i=0;
      $attr = [];
      $attributes = json_decode($cart->attributes);
      foreach ($attributes as $key => $attribute) {
          $attr[$i]['name'] = $key;
          $attr[$i]['options'] = $attribute;
          $i++;
      }
      $cart->attributes = $attr;
      $total_quantity += $cart->quantity;
      $total += $cart->price*$cart->quantity;
    }
    $data['cart'] = $cart_details;
    $data['quantity'] = $total_quantity;
    $data['total'] = number_format($total,2);
    echo json_encode($data);
  }

  public function cartDelete($product_id=""){
    if($product_id!=''){
      $cart_details = Cart::where('cart_id',auth()->id())->where('product_id',$product_id)->first();
      if(!empty($cart_details)){
        $cart = Cart::find($cart_details->id);
        if($cart->delete()){
          $data['msg'] = 'Cart Item Deleted';
          $data['status'] = true;
        }else{
          $data['msg'] = 'Something went wrong';
          $data['status'] = false;
        }
      }else{
        $data['msg'] = 'Cart Product Not Found';
        $data['status'] = false;
      }
    }else{
      $cart_details = Cart::where('cart_id',auth()->id())->get();
      if(!$cart_details->isEmpty()){
        foreach ($cart_details as $key => $cart) {
          $delete = Cart::find($cart->id)->delete();
        }
        $data['msg'] = 'Cart Deleted';
        $data['status'] = true;
      }else{
        $data['msg'] = 'Empty Cart';
        $data['status'] = false;
      }
    }
    echo json_encode($data);
  }

  public function slider(Request $request){
    $sliders = Slider::all();
    if(!empty($sliders)){
      $arr = array();
        foreach ($sliders as $key => $value) {
          $arr[ $key]['id'] = $value->id;
          $arr[ $key]['small_text'] = $value->small_text;
          $arr[ $key]['bold_text'] = $value->bold_text;
          $arr[ $key]['title'] = $value->title;
          $arr[ $key]['url'] = $value->url;
          $arr[ $key]['image'] = asset('assets/user/interfaceControl/sliders/'.$value->image);
        }
       $data['sliders'] = $arr;
       $data['msg'] = "sliders";
       $data['status'] = true;
       $status = $this-> successStatus;
    }else{
        $data['msg'] = "No Slider Found";
       $data['status'] = false;
       $status = 401;
    }
    return response()->json($data, $status);
  }

  public function verifyOTP(Request $request){
      $user_otp = auth::user()->sms_ver_code;

      $validator = Validator::make($request->all(),[
        'sms_otp' => 'required',
      ],['sms_otp.required'=>'OTP required']);

      if ($validator->fails()) { 
        return response()->json($validator->errors(), 401);                
      }

      if($request->sms_otp == $user_otp){
        $user_detail = User::find(auth::user()->id);
        $user_detail->sms_ver_code = '';
        $user_detail->sms_verified = 1;
        $user_detail->save();
        $success['token'] = auth::user()->token;
        $success['user_id'] = auth::user()->id;
        $success['sms_otp'] = auth::user()->sms_ver_code;
        $success['verified'] = true;
        $success['status'] = true;
        $status = $this-> successStatus;
      }else{
        $success['token'] = auth::user()->token;
        $success['user_id'] = auth::user()->id;
        $success['sms_otp'] = auth::user()->sms_ver_code;
        $success['verified'] = false;
        $success['status'] = false;
        $status = 401;
      }
      return response()->json($success, $status);
      // echo json_encode($success,$status);
  }

  public function resendOTP(Request $request){
    $user_detail = User::find(auth::user()->id);
    $user_detail->sms_ver_code = '1234';
    $user_detail->save();
    $success['token']   = $user_detail->token;
    $success['user_id'] = $user_detail->id;
    $success['sms_otp'] = $user_detail->sms_ver_code;
    $success['status']  = true;
    $status = $this-> successStatus;
    return response()->json($success, $status);
  }

   public function NotificationList(Request $request){
   if($request->q == 'last'){
        $resp = UserNotification::where('customer_id',Auth::id())->orderby('id','DESC')->first();
    }else{
      $resp = UserNotification::where('customer_id',Auth::id())->orderby('id','DESC')->get();
    }
    $data['notifications'] = $resp;
    $data['msg'] = 'Notification List';
    $data['status'] = true;
    return response()->json($data, $this-> successStatus);
  }

}//End