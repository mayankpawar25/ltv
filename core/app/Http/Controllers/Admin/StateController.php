<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;  
use  App\State;
use App\Country;
use Validator;
use Hash;
use Datatables;
class StateController extends Controller
{

	protected function validator(array $data,array $rules)
    {
        return Validator::make($data, $rules);
    }
    
	/*Show States List*/
	public function index(Request $request){
            if($request->ajax()){
	        return datatables()->of(DB::table('states As t')
                        ->leftjoin('countries', 't.country_id', '=', 'countries.id')
                        ->select('t.id', 't.name','countries.name as country_name','t.status')
                        ->get())   
	                ->addColumn('action', function($data){
                    $button = '<a href="'.route('states.edit',$data->id).'" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</a>';
	               
	                $button .= '&nbsp;&nbsp;';
	               /* $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';*/

                     $button .= '&nbsp;&nbsp;';
                     if($data->status == 0){
                        $button .= '<button type="button" name="status" id="'.$data->id.'" class="status btn btn-danger btn-sm" data-status="'.$data->status.'">Inactive</button>';
                       }else {
                           $button .= '<button type="button" name="status" id="'.$data->id.'" class="status btn btn-success btn-sm" data-status="'.$data->status.'">Active</button>';
                       }
	                return $button;
	                })
	                ->rawColumns(['action'])
	                ->make(true);
	     }
        //return view('admin.dashboard.pages.admins.index');
         return view('admin.state.index');
        
     }

     /*Add States*/
	public function AddState()
    {
     
        return view('admin.state.stateregister');
    }

    /*Add State*/
     public function store(Request $request){
          $rules = array(
                'name' => 'required',
                'country_id' => 'required',
                );
        	$this->validator($request->all(),$rules)->validate();
     	     $data = array( 
	            'states' => array(
	            	  'name' => strtoupper(strip_tags($request->input('name'))),
                      'country_id' => strip_tags($request->input('country_id')),
	            	   'status'=> 0,
	            	 ) 
	        	);
        	  $resp = DB::table('states')->insert($data);
	       return redirect('admin/states')->with('message', 'State Add Successfully!');
     }

     /*Edit State*/

      public function edit(Request $request,$token){
        $data['state']  = State::find($token);
        
        return view('admin.state.edit',$data);
    }

     /*Update State*/
    public function update(Request $request,$token){

        $rules = array(
            'name'  => 'required',
             'country_id'  => 'required',
           );

        $dt = $this->validator($request->all(),$rules)->validate();
        
               $data = array( 
                   'name' => strtoupper(strip_tags($request->input('name'))),
                   'country_id' => strip_tags($request->input('country_id')),
                   
                    //'brand_logo' => $logo_name, 
               );
     
        //dd($data);
        $resp = State::where('id',$token)->update($data);
        if($resp == 1){
            return redirect('admin/states')->with('success','State updated Succesfully');
        }else{
            return redirect('admin/states')->with('error','Something went wrong!!');
        }
    }

    /*Update State status*/
     public function UpdateStatus($id,$status)
    {
        if($status == 0)
        {
            $data = array( 
                   'status' => 1, 
               );
        }else {
             $data = array( 
                   'status' => 0, 
               );
        }
        State::where('id',$id)->update($data);
    }

    /*Country DropDown*/
    public function CountryDropdownList(Request $request){
        $html = '';
        $countries = Country::where('status',1)->get();
        foreach ($countries as $country) {
            $html .= '<option  value="'.$country->id.'">'.$country->name.'</option>';
        }
        return response()->json(['html' => $html]);
    }

    /*Edit Country DropDown*/
    public function EditCountryDropdownList(Request $request,$token){

        $html = '';
        $state_id = State::find($token);
        $countries = Country::where('status',1)->get();
          foreach ($countries as $country) {
        $selected = ($state_id->country_id == $country->id) ? 'selected' : '';
            $html .= '<option  value="'.$country->id.' " '.$selected.'>'.$country->name.'</option>';
        }
        return response()->json(['html' => $html]);
    }
}
