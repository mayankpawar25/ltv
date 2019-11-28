<?php

namespace App\Http\Controllers;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //

     public function login(Request $request){ 
        if(is_numeric(request('email'))){
            if(Auth::guard('shopekeeper')->attempt(['phone' => $request->email, 'password' => $request->password])){
                $user = Auth::guard('shopekeeper')->user();
                $fcm_id = request('fcm_id'); 
                $success['token'] =  $user->createToken('MyApp')-> accessToken; 
                $success['user_id'] =  $user->id;
                $success['owner_name'] =  $user->owner_name;
                $success['shop_name'] =  $user->shop_name;
                if(isset($success['token'])){
                    Vendor::where('id', $user->id)->update(['fcm_id' => $fcm_id]);
                }
                $success['status'] = true;
                $success['msg'] = 'Log in success';
                return response()->json($success, $this-> successStatus); 
            } 
            else{ 
                $error['status'] = false;
                $error['msg'] = 'Unauthorised Phone or Password';
                return response()->json($error, 401); 
            } 
        }
        elseif (filter_var(request('email'), FILTER_VALIDATE_EMAIL)) {
            if(Auth::guard('shopekeeper')->attempt(['email' => $request->email, 'password' => $request->password])){
                $user = Auth::guard('shopekeeper')->user();
                $fcm_id = request('fcm_id');  
                $success['token'] =  $user->createToken('MyApp')-> accessToken;
                $success['user_id'] =  $user->id;
                $success['owner_name'] =  $user->owner_name;
                $success['shop_name'] =  $user->shop_name;
                if(isset($success['token'])){
                    Vendor::where('id', $user->id)->update(['fcm_id' => $fcm_id]);
                } 
                $success['status'] = true;
                $success['msg'] = 'Log in success';
                return response()->json($success, $this-> successStatus); 
            } 
            else{ 
                $error['status'] = false;
                $error['msg'] = 'Unauthorised Email or Password';
                return response()->json($error, 401);
            } 
        }
        else{ 
            $error['status'] = false;
                $error['msg'] = 'Unauthorised Username or Password else no ';
            return response()->json($error, 401); 
        }
    }


}
