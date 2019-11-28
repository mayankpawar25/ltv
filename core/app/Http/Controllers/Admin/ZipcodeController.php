<?php

namespace App\Http\Controllers\Admin;

use App\Zipcode;
use App\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use Auth;

class ZipcodeController extends Controller
{   
     private $pre_page;

    public function __construct()
    {
        /*$this->pre_page = config('constants.RECORD_PER_PAGE');*/
        $this->pre_page = 5;
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        //$data['staffroles'] = Zipcode::get();
         $data['staffroles'] = DB::table('zipcodes As t')
                        ->leftjoin('states', 't.state_id', '=', 'states.id')
                        ->leftjoin('cities', 't.city_id', '=', 'cities.id')
                        ->select('t.id', 't.area_name','t.zipcode_name','states.name as state_name','cities.name as city_name','t.status')
                        ->paginate($this->pre_page);
        return view('admin/zipcodes/create',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('admin/zipcodes/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $validatedRequest = $request->validate([
            'zipcode_name'       => 'required',
            'area_name'          => 'required',
        ]);
        
        $staffrole = new Zipcode;
        $staffrole->country_id           = $request->country_id;
        $staffrole->state_id             = $request->state_id;
        $staffrole->city_id              = $request->city_id;
        $staffrole->zipcode_name         = $request->zipcode_name;
        $staffrole->area_name            = $request->area_name;
        $staffrole->status               = $request->status;
        $staffrole->save();
        return redirect()->route('admin.zipcodes.index')->with('success','Staff Role Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StaffRole  $staffRole
     * @return \Illuminate\Http\Response
     */
    public function show(StaffRole $staffRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StaffRole  $staffRole
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $data['edit'] = Zipcode::find($id);
        //$data['staffroles'] = Zipcode::get();
         $data['staffroles'] = DB::table('zipcodes As t')
                        ->leftjoin('states', 't.state_id', '=', 'states.id')
                        ->leftjoin('cities', 't.city_id', '=', 'cities.id')
                        ->select('t.id', 't.area_name','t.zipcode_name','states.name as state_name','cities.name as city_name','t.status')
                        ->paginate($this->pre_page);
        return view('admin/zipcodes/create',$data);
    }
   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StaffRole  $staffRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){
        $staffrole = Zipcode::find($request->id);
        $staffrole->country_id           = $request->country_id;
        $staffrole->state_id             = $request->state_id;
        $staffrole->city_id              = $request->city_id;
        $staffrole->zipcode_name         = $request->zipcode_name;
        $staffrole->area_name            = $request->area_name;
        $staffrole->status = $request->status;
        if($staffrole->save()){
            return redirect()->route('admin.zipcodes.index')->with('success','Update Role Successfully');
        }else{
            return redirect()->route('admin.zipcodes.edit',$request->id)->with('danger','Nothing to Update');
        }
    }
    public function update1(Request $request, StaffRole $staffRole)
    {
        $staffrole = Zipcode::find($request->id);
        $staffrole->country_id           = $request->country_id;
        $staffrole->state_id             = $request->state_id;
        $staffrole->city_id              = $request->city_id;
        $staffrole->zipcode_name         = $request->zipcode_name;
        $staffrole->area_name            = $request->area_name;
        $staffrole->status = $request->status;
        if($staffrole->save()){
            return redirect()->route('admin.zipcodes.index')->with('success','Update Role Successfully');
        }else{
            return redirect()->route('admin.zipcodes.edit',$request->id)->with('danger','Nothing to Update');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StaffRole  $staffRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaffRole $staffRole)
    {
        dd($staffRole);
    }

    public function delete($id)
    {
        $staffrole = Zipcode::find($id);
        $staffrole->delete();
        return redirect()->route('admin.zipcodes.index')->with('success','Staff Role deleted!!!');
    }
}
