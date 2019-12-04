<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;  
use App\City;
use  App\State;
use Validator;
use Hash;
use Datatables;

class CityController extends Controller
{

      protected function validator(array $data,array $rules){
        return Validator::make($data, $rules);
    }
    /*Show States List*/
	public function index(Request $request){
            if($request->ajax()){
	        return datatables()->of(DB::table('cities As t')
                        ->leftjoin('states', 't.state_id', '=', 'states.id')
                        ->select('t.id', 't.name','states.name as state_name','t.status')
                        ->get())   
	                ->addColumn('action', function($data){
                   
	               
	                $button = '<a href="'.route('cities.edit',$data->id).'" name="edit" id="'.$data->id.'" class="edit btn btn-info btn-sm " data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
	                $button .= '&nbsp;&nbsp;';

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
        //return view('admin.dashboard.pages.city.citylist');
          return view('admin.city.index');
     }

     /*Add City*/
	public function AddCity()
    {
     
        return view('admin.city.cityregister');
    }

    /*City DropDown*/
    public function CityDropdownList(Request $request){
        $html = '';
        $states = State::where('status',1)->get();
        foreach ($states as $state) {
            $html .= '<option  value="'.$state->id.'">'.$state->name.'</option>';
        }
     	return response()->json(['html' => $html]);
	}

	/*Add City*/
     public function store(Request $request){
          $rules = array(
                'name' => 'required',
               'state_id' => 'required',
                );
        	$this->validator($request->all(),$rules)->validate();
     	     $data = array( 
	            'states' => array(
	            	  'name' => strip_tags($request->input('name')),
	            	  'state_id' => strip_tags($request->input('state_id')),
	            	   'status'=> 0,
	            	 ) 
	        	);
        	  $resp = DB::table('cities')->insert($data);
	       return redirect('admin/cities')->with('message', 'City Add Successfully!');

     }

     /*Update City Status (Active / Inactive)*/
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
        City::where('id',$id)->update($data);
    }
     /*Edit City*/

      public function edit(Request $request,$token){
        $data['city']  = city::find($token);
         return view('admin.city.edit',$data);
    }

    /*Edit City DropDown*/
    public function EditCityDropdownList(Request $request,$token){
        $html = '';
        $city_id = City::find($token);
        $states = State::where('status',1)->get();
          foreach ($states as $state) {
       	$selected = ($city_id->state_id == $state->id) ? 'selected' : '';
        	$html .= '<option  value="'.$state->id.' " '.$selected.'>'.$state->name.'</option>';
        }
     	return response()->json(['html' => $html]);
	}

	/*Update City*/
    public function update(Request $request,$token){

        $rules = array(
            'name'  => 'required',
            'state_id'  => 'required',
           );

        $dt = $this->validator($request->all(),$rules)->validate();
        
               $data = array( 
                   'name' => strip_tags($request->input('name')),
                   'state_id' => strip_tags($request->input('state_id')),
                   
                 );
     
        //dd($data);
        $resp = City::where('id',$token)->update($data);
        if($resp == 1){
            return redirect('admin/cities')->with('success','City updated Succesfully');
        }else{
            return redirect('admin/cities')->with('error','Something went wrong!!');
        }
    }
}
