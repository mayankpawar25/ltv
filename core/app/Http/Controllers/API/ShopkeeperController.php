<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use App\City;
use App\Country;
use App\State;
// use App\User;
use App\Vendor;
use App\Category,App\Subcategory;
use App\Shopkeeper;
use App\ProductAttribute;
use App\Option;
use App\Models\DeliveryBoy,App\OrderRequestForDeliveryBoy;
use App\Orderedproduct;
use App\Order;
use App\Product;
use App\Gateway;
use App\Orderpayment;
use Validator;
use Hash;
use DB,Image;
class ShopkeeperController extends Controller 
{
  public $successStatus = 200;
  /** 
   * login API 
   * 
   * @return \Illuminate\Http\Response 
   */ 
  public function login(Request $request){
    if(is_numeric(request('email'))){
  	 	if(Auth::guard('shopkeeper')->attempt(['mobile' => $request->email, 'password' => $request->password])){
          $user = Auth::guard('shopkeeper')->user();
          $success['token'] =  $user->createToken('MyApp')-> accessToken;
          if($user->status == '0' || $user->status == ''){
            if($user->sms_verified == '0' || $user->sms_verified == ''){
              $success['status'] = false;
              $success['msg'] = 'Unverified Mobile number';
              $success['verified_status'] = 'sms_not_verified';
            }else if($user->is_verified == '0' || $user->is_verified == ''){
              $success['status'] = false;
              $success['msg'] = 'Account is Under Review';
              $success['verified_status'] = 'under_review';
            }else{
              $success['status'] = false;
              $success['msg'] = 'Account is Under Review';
              $success['verified_status'] = 'under_review';
            }
          }else {
            $user = Auth::guard('shopkeeper')->user();
            $fcm_id = request('fcm_id'); 
            $success['user_id'] =  $user->id;
            $success['owner_name'] =  $user->name;
            $success['shop_name'] =  $user->shopname;
            $success['approval_status'] =  $user->status;
            if(isset($success['token'])){
              Shopkeeper::where('id', $user->id)->update(['fcm_id' => $fcm_id]);
            }
            $success['status'] = true;
            $success['msg'] = 'Log in success';
            $success['verified_status'] = 'verified';
          }
          return response()->json($success, $this-> successStatus); 
        }else{
          $error['status'] = false;
          $error['msg'] = 'Unauthorised Phone or Password';
          return response()->json($error, 401); 
        }
    }elseif (filter_var(request('email'), FILTER_VALIDATE_EMAIL)) {
        if(Auth::guard('shopkeeper')->attempt(['email' => $request->email, 'password' => $request->password])){
          $user = Auth::guard('shopkeeper')->user();
          $success['token'] =  $user->createToken('MyApp')-> accessToken;
          if($user->status == '0' || $user->status == ''){
            if($user->sms_verified == '0' || $user->sms_verified == ''){
              $success['status'] = false;
              $success['msg'] = 'Unverified Mobile number';
              $success['verified_status'] = 'sms_not_verified';
            }else if($user->is_verified == '0' || $user->is_verified == ''){
              $success['status'] = false;
              $success['msg'] = 'Account is Under Review';
              $success['verified_status'] = 'under_review';
            }else{
              $success['status'] = false;
              $success['msg'] = 'Account is Under Review';
              $success['verified_status'] = 'under_review';
            }
          }else {
            $fcm_id = request('fcm_id');  
            $success['user_id'] =  $user->id;
            $success['owner_name'] =  $user->name;
            $success['shop_name'] =  $user->shopname;
            $success['approval_status'] =  $user->status;
            if(isset($success['token'])){
              Shopkeeper::where('id', $user->id)->update(['fcm_id' => $fcm_id]);
            }
            $success['verified_status'] = 'verified';
            $success['status'] = true;
            $success['msg'] = 'Log in success';
          }
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
    $user = Auth::guard('shopkeeper')->user();
    $user = Shopkeeper::where('id', Auth::id())->first();
    $user->images = json_decode($user->images);
    foreach($user->images as $key => $value){
      $user->images->$key = asset('assets/shopkeeper/'.$user->folder.'/'.$value);
    }
    $user->documents = json_decode($user->documents);
    foreach($user->documents as $key => $value){
      $value->image_name = asset('assets/shopkeeper/'.$user->folder.'/'.$value->image_name);
    }
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
    $user = Shopkeeper::find(Auth::user()->id);
    $user->password = bcrypt($request->password);
    $user->save();
    //$success['status'] = true;
    $success['user_id'] = $user->id;
    $success['name'] = $user->name;
    $success['msg'] =  'Password changed successfully!';
    return response()->json($success, $this-> successStatus); 
  }

  public function register(Request $request){
    $validator = Validator::make($request->all(), [ 
         'owner_name' => 'required',
        'shop_name' => 'required',
        'email'=> 'required|email|max:255|unique:shopkeepers',
        'mobile' => 'required|unique:shopkeepers',
        'area'=> 'required',
        'city' => 'required',
        'state' => 'required',
        'country' => 'required',
        'address' => 'required',
        'password' => 'required',
    ]);
    
    if ($validator->fails()) { 
        return response()->json($validator->errors(), 401);            
    }

    $current_time = time();
    $path = 'assets/shopkeeper/'.$current_time;
    $owner_pic = '';
    $shop_pic = '';
    $logo = '';
    $banner = '';
  
    $shopkeeper = new Shopkeeper;
    $shopkeeper->name = $request->owner_name;
    $shopkeeper->shopname = $request->shop_name;
    $shopkeeper->email = $request->email;;
    $shopkeeper->password = Hash::make($request->password);
    $shopkeeper->mobile = $request->mobile;
    $shopkeeper->zipcode_id = $request->area;
    $shopkeeper->city_id = $request->city;
    $shopkeeper->state_id = $request->state;
    $shopkeeper->country_id = $request->country;
    $shopkeeper->address = $request->address;
    $shopkeeper->latitude = $request->latitude;
    $shopkeeper->longitude = $request->longitude;
    $shopkeeper->status = 0;
    $shopkeeper->images = '[]';
    $shopkeeper->documents = '[]';
    $shopkeeper->user_role = 'shopkeeper';
    $shopkeeper->qr_code = 'qr_code';
    $shopkeeper->is_verified = 0;
    $shopkeeper->email_verified = 0;
    $shopkeeper->sms_verified = 0;
    $shopkeeper->sms_ver_code = rand(1 , 99999);
    $shopkeeper->email_ver_code = rand(1, 99999);
    $shopkeeper->folder = $current_time;
    $shopkeeper->fcm_id = $request->fcm_id;
    $shopkeeper->token =  $shopkeeper->createToken('MyApp')-> accessToken; 
    $shopkeeper->save();
    
    $success['token'] =  $shopkeeper->createToken('MyApp')-> accessToken; 
    $success['name'] =  $shopkeeper->name;
    $success['shop_name'] =  $shopkeeper->shopname;
    $success['user_id'] =  $shopkeeper->id;
    
    /* Generate Qr-Code */
    $url = route('admin.shopkeeper.show',$shopkeeper->id);
    $image = \QrCode::format('png')
                        ->size(500)->errorCorrection('H')
                        ->generate($shopkeeper->id);
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
    $update = Shopkeeper::find($shopkeeper->id);
    $update->qr_code = $location;
    $update->save();
    /* Generate Qr-Code */

    return response()->json($success, $this-> successStatus); 
  }

  // emailVerification
  public function emailVerification(Request $request){
    // $verified = Shopkeeper::where('email',$request->email)->first();
    $verified = auth::user();
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
  }
  
  // phoneVerification
  public function phoneVerification(Request $request){
    // $verified = Shopkeeper::where('mobile',$request->phone)->first();
    $verified = auth::user();
    if(!empty($verified)){
      if($verified->sms_verified == 1){
          $data['msg'] = 'Phone Number Already Verified ';
          $data['status'] = true;
          $data['verified_status'] = 'verified';
          $status = $this-> successStatus;
          return response()->json($data, $status); 
      }

      if($request->otp!=''){
        if($request->otp == $verified->sms_ver_code){
          $verified->sms_ver_code = 0;
          $verified->sms_verified = 1;
          $verified->save();
          if($verified->status == '0' || $verified->status == ''){
            $data['verified_status'] = 'under_review';
          }else{
            $data['verified_status'] = 'verified';
          }
          $data['msg'] = 'Phone number Verified Successfully';
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
        $data['msg'] = 'Dealer Not Available';
        $data['status'] = false;
        $status = 401;
    }
    return response()->json($data, $status); 
  }

  // Resend OTP
  public function resendOTP(Request $request){
    // Type : sms / email
    $user = Shopkeeper::find(auth::id());
    if(!empty($user)){
      if($request->type = 'sms'){
        $user->sms_ver_code = rand(11111,99999);
        $user->sms_sent = 1;
        $user->sms_verified = 0;
        $user->save();

        $resp['msg'] = 'OTP sent';
        $resp['status'] = true;
        $status = $this-> successStatus;

      }else if($request->type = 'email'){
        $user->email_ver_code = rand(11111,99999);
        $user->email_sent = 1;
        $user->email_verified = 0;
        $user->save();
        $status = $this-> successStatus;

        $resp['msg'] = 'OTP sent';
        $resp['status'] = true;
      }
    }else{
      $resp['msg'] = 'Invalid User';
      $resp['status'] = false;
      $status = 401;
    }
    return response()->json($resp, $status); 
  }

  public function forgotPassword(Request $request){
    if(is_numeric(request('q'))){
      $resp = Shopkeeper::where('mobile',$request->q)->get();
      echo json_encode(['phone'=>$resp]);
    }elseif (filter_var(request('q'), FILTER_VALIDATE_EMAIL)) {
      $resp = Shopkeeper::where('email',$request->q)->get();
      echo json_encode(['email'=>$resp]);

    }else{
      echo "not Found";
    }   
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

  /* Fetch Shopkeeper Qr Code */
  public function myQrcode(Request $request){
    $user = Shopkeeper::find(Auth::id());
    if($user->qr_code != ''){
      $user->qr_code = asset($user->qr_code);
      $data['qrcode'] = $user->qr_code;
      $data['status'] = true;
      $data['msg'] = 'My Qr-Code';
      $status = $this-> successStatus;
    }else{
      $data['qrcode'] = '';
      $data['status'] = false;
      $data['msg'] = 'Qr-Code not available';
      $status = $this-> successStatus;
    }
    return response()->json($data, $status);
  }
  /* Fetch Shopkeeper Qr Code */

  /* Task Client User type */
  public function userType(){
    $data = [['id'=>1,'name'=>'Dealers'],['id'=>2,'name'=>'leads'],['id'=>3,'name'=>'customers']];
    return response()->json($data, $this-> successStatus);
  }
  /* Task Client User type */

}