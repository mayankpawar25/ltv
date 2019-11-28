<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User,App\Models\StaffUser,Validator;

class CustomerController extends Controller
{
    /*Lead Management*/

    public $successStatus = 200;
    /**
     * Display a listing Leads.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $user = Auth::guard('salesman')->user();
        $salesman = StaffUser::where('id', Auth::id())->first();
        $customer = User::where('assigned_to',$salesman->id )->orderby('id','DESC')->get();
        if(!$customer->isEmpty()){
            $data['customer'] = $customer;
            $data['msg'] = 'Salesman Customer List';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['customer'] = [];
            $data['msg'] = 'No Customer Found';
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

    public function expenseCategoryList(Request $request){
      
    }

}
