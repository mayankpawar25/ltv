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
    public function index(Request $request)
    {
        
        if($request->ajax()){
           return datatables()->of(Country::all())   
            ->addColumn('action', function($data){
                $button = '<a href="'.route('countries.edit',$data->id).'" name="edit" id="'.$data->id.'" class="editbtn btn-info btn-sm " data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>';

                $button .= '&nbsp;&nbsp;';
                /* $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';*/

                $button .= '&nbsp;&nbsp;';
                if($data->status == 0){
                    $button .= '<button type="button" name="status" id="'.$data->id.'" class="status  btn-sm btn btn-danger btn-sm" data-status="'.$data->status.'">Inactive</button>';
                }else {
                    $button .= '<button type="button" name="status" id="'.$data->id.'" class="status btn btn-success btn-sm" data-status="'.$data->status.'">Active</button>';
                }
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        
        return view('admin.country.index');
     
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
              $rules = array(
                'name' => 'required',
                //'iso_code' => 'required',
                //'phone_code' => 'required',
                //'country_flag' => 'required|mimes:jpeg,jpg,png,pdf|max:2000',
               
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
    public function edit($id)
    {
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
    public function update(Request $request, $id)
    {

        //Country Update
         $rules = array(
            'name'  => 'required',
            //'iso_code' => 'required',
            //'phone_code' => 'required',
            //'country_flag' => 'required|mimes:jpeg,jpg,png,pdf|max:2000',
           );
          $flag = [];
            $dt = $this->validator($request->all(),$rules)->validate();
        
               $data = array( 
                   'name' => strip_tags($request->input('name')),
                   //'iso_code' => strip_tags($request->input('iso_code')),
                   //'phone_code' => strip_tags($request->input('phone_code')),
                    //'country_flag'=>json_encode($flag),
                );
     
        //dd($data);
        $resp = Country::where('id',$id)->update($data);
        if($resp == 1){
            return redirect('admin/countries')->with('success','Country updated Succesfully');
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