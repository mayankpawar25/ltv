<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;  
use App\Country;
use Validator;
use Hash;

use \Yajra\Datatables\Datatables;
class CountryController extends Controller
{
     protected function validator(array $data,array $rules){
        return Validator::make($data, $rules);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id="")
    {   
        $data['country'] = [];
        if($id!='')
            $data['country']  = Country::find($id);

        if($request->ajax()){
           return datatables()->of(Country::all())   
            ->addColumn('action', function($data){
                $button = '<a href="'.route('countries.index',$data->id).'" name="edit" id="'.$data->id.'" class="editbtn btn-info btn-sm " data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                return $button;
            })
             ->addColumn('status', function($data){
                $checked = ($data->status==1)?'checked':'';
                $button2 = ' <input '.$checked.' data-id="'.$data->id.'" class="tgl tgl-ios status" id="cb'.$data->id.'" data-status="'.$data->status.'" type="checkbox"/><label class="tgl-btn" for="cb'.$data->id.'"></label>';
                return $button2;
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }
        
        return view('admin.country.index',$data);
     
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $rules = array(
        'name' => 'required',

        );
        $this->validator($request->all(),$rules)->validate();
        $flag = [];
        $data = array( 
        'states' => array(
              'name' => strip_tags($request->input('name')),
              //'iso_code' => strip_tags($request->input('iso_code')),
              //'phone_code' => strip_tags($request->input('phone_code')),
               //'country_flag'=>json_encode($flag),
               'status'=> 1,
             ) 
        );
        $resp = DB::table('countries')->insert($data);
        return redirect('admin/countries')->with('success', 'Country Add Successfully!');
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        // Edit Country 
        $data['country']  = Country::find($id);
        return view('admin.country.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){

        //Country Update
        $rules = array(
            'name'  => 'required',
        );
        $flag = [];
        $dt = $this->validator($request->all(),$rules)->validate();
        $country = Country::find($request->id);
        $country->name = strip_tags($request->input('name'));
        if($country->save()){
            return redirect('admin/countries')->with('success','Country Updated Succesfully');
        }else{
            return redirect('admin/countries')->with('error','Something went wrong!!');
        }
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
    /*Update Country Status (Active / Inactive)*/
     public function updatestatus($id,$status)
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
        Country::where('id',$id)->update($data);
    }
}