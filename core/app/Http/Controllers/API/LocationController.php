<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use App\City;
use App\Country;
use App\State;
use App\Zipcode;
use Validator;
use Hash;
use DB;
class LocationController extends Controller 
{
    public $successStatus = 200;
    /** 
     * Country Dropdown List API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     public function countryDropdown(Request $request){
      if(!empty($request->country_id)){
          $countries['countries_list'] = Country::where(['status' => 1, 'id' => $request->country_id])->get();
          $countries['status'] = true;
      }else {
        $countries['countries_list'] = Country::get();
          $countries['status'] = true;
         
      }
        return response()->json($countries, $this-> successStatus); 
    }

    /** 
     * State Dropdown List API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function statesDropdown(Request $request)
    {
        $states['state_list'] = State::where(['status' => 1, 'country_id' => $request->input('country_id')])->get();
         if(!$states['state_list']->isEmpty()){
            $states['status'] = true;
            $success['msg'] = 'State list';
            return response()->json($states, $this-> successStatus); 
         }else {
           $error['status'] = false;
           $error['msg'] = 'No State Found';
          return response()->json($error, 401); 
         }
    }

    /** 
     * City Dropdown List API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function citiesDropdown(Request $request)
    {
       $cities['city_list'] = City::where(['status' => 1, 'state_id' => $request->input('state_id')])->get();
      if(!$cities['city_list']->isEmpty()){
            $cities['status'] = true;
            $success['msg'] = 'State list';
            return response()->json($cities, $this-> successStatus); 
         }else {
           $error['status'] = false;
           $error['msg'] = 'No City Found';
          return response()->json($error, 401); 
         }
    }

     /** 
     * Zipcode Dropdown List API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function zipcodeDropdown(Request $request)
    {
      $zipcodes['zipcode_list'] = Zipcode::where(['status' => 1, 'city_id' => $request->input('city_id')])->get();
      if(!$zipcodes['zipcode_list']->isEmpty()){
            $zipcodes['status'] = true;
            $success['msg'] = 'State list';
            return response()->json($zipcodes, $this-> successStatus); 
         }else {
           $error['status'] = false;
           $error['msg'] = 'No City Found';
          return response()->json($error, 401); 
         }
    }
}