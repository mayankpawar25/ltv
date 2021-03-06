<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class StaffLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:staff', ['except' => ['logout'] ]);
    }


    public function show_login_form()
    {
        // Logging out the other type of user - Team Member (just in case)
        //Auth::logout();

        return view('auth.customer_login');
    }

    public function login(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);


        if(Auth::guard('staff')->attempt([
            'email' => $request->email, 'password' => $request->password ], $request->remember))
        {
             Auth::guard('web')->logout();
            return redirect()->intended(route('customer_dashboard'));
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))
        ->withErrors(['email' => 'These credentials do not match our records.']);

    }

    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();

        $request->session()->invalidate();

        return redirect()->route('customer_login_page');
    }
}
