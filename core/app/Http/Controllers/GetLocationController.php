<?php

namespace App\Http\Controllers;
use App\Area;
use App\City;
use App\Country;
use App\State;
use App\Zipcode;
use App\Vendor;
use App\Shopkeeper;
use App\Lead;
use App\User;
use App\UserGroup;
use App\Models\StaffUser;
use Illuminate\Http\Request;
use Auth;
class GetLocationController extends Controller
{
    public function CountryCodeDropdown(Request $request)
    {
        $html      = '';
        $countries = Country::where('status', 1)->get();
        foreach ($countries as $country) {
            $html .= '<option  value="' . $country->phone_code . '">' . $country->phone_code . '</option>';
        }
        return response()->json(['html' => $html]);
    }


    public function countries(Request $request)
    {
        $selected = "";
        if ($request->input('selected_country') != '') {
            $selected = $request->input('selected_country');
        }

        $html   = '';
        $countries = Country::where('status', 1)->get();
        $html .= '<option  value="">-- Select Country --</option>';
        foreach ($countries as $country) {

            if ($selected == $country->id) {
                $html .= '<option  value="' . $country->id . '" selected>' . $country->name . '</option>';
            } else {
                $html .= '<option  value="' . $country->id . '">' . $country->name . '</option>';
            }

        }
        return response()->json(['html' => $html]);
    }


    // public function states(Request $request)
    // {
    //     $selected = "";
    //     if ($request->input('selected_state') != '') {
    //         $selected = $request->input('selected_state');
    //     }

    //     $html   = '';
    //     $states = State::where('status', 1)->get();
    //     $html .= '<option  value="">-- Select State --</option>';
    //     foreach ($states as $state) {

    //         if ($selected == $state->id) {
    //             $html .= '<option  value="' . $state->id . '" selected>' . $state->name . '</option>';
    //         } else {
    //             $html .= '<option  value="' . $state->id . '">' . $state->name . '</option>';
    //         }

    //     }
    //     return response()->json(['html' => $html]);
    // }

    public function states(Request $request)
    {

        $html     = '';
        $selected = "";
        if ($request->input('selected_state') != '') {
            $selected = $request->input('selected_state');
        }

        $states = State::where(['status' => 1, 'country_id' => $request->input('country_id')])->get();
        $html .= '<option  value="">-- Select State --</option>';
        foreach ($states as $state) {
            if ($selected == $state->id) {
                $html .= '<option  value="' . $state->id . '" selected>' . ucwords($state->name) . '</option>';
            } else {
                $html .= '<option  value="' . $state->id . '">' . ucwords($state->name) . '</option>';
            }
        }
        return response()->json(['html' => $html]);
    }


    public function cities(Request $request)
    {
        $html     = '';
        $selected = "";
        if ($request->input('selected_city') != '') {
            $selected = $request->input('selected_city');
        }
        if($request->input('state_id') !=''){
            $cities = City::where(['status' => 1, 'state_id' => $request->input('state_id')])->get();
        }else {
            $cities = City::where(['status' => 1])->get();
        }
        if(isset($cities)){
            $html .= '<option  value="">-- Select City --</option>';
            foreach ($cities as $city) {
                if ($selected == $city->id) {
                    $html .= '<option  value="' . $city->id . '" selected>' . $city->name . '</option>';
                } else {
                    $html .= '<option  value="' . $city->id . '">' . $city->name . '</option>';
                }
            }
        }else{
            $html .= '<option  value="">-- No City Found --</option>';
        }
        return response()->json(['html' => $html]);
    }

    public function zipcode(Request $request)
    {
        $html     = '';
        $selected = "";
        if ($request->input('selected_city') != '') {
            $selected = $request->input('selected_city');
        }
        $zipcodes = Zipcode::where(['status' => 1, 'city_id' => $request->input('city_id')])->get();
        $html .= '<option  value="">-- Select Area --</option>';
        foreach ($zipcodes as $zipcode) {
            if ($selected == $zipcode->id) {
                $html .= '<option  value="' . $zipcode->id . '" selected>' . $zipcode->area_name . '</option>';
            } else {
                $html .= '<option  value="' . $zipcode->id . '">' . $zipcode->area_name . '</option>';
            }
        }
        return response()->json(['html' => $html]);
    }

    public function shop(Request $request)
    {
        $html     = '';
        $selected = "";
        if ($request->input('selected_zipcode') != '') {
            $selected = $request->input('selected_zipcode');
        }
        $shops = Vendor::where(['status' => 'active', 'area_id' => $request->input('zipcode_id')])->get();
        $html .= '<option  value="">-- Select Shop --</option>';
        foreach ($shops as $shop) {
            if ($selected == $shop->id) {
                $html .= '<option  value="' . $shop->id . '" selected>' . $shop->shop_name . '</option>';
            } else {
                $html .= '<option  value="' . $shop->id . '">' . $shop->shop_name . '</option>';
            }
        }
        return response()->json(['html' => $html]);
    }

    public function units(Request $request)
    {
        $land_unit_id = "";
        if ($request->input('land_id') != '') {
            $land_unit_id = $request->input('land_id');
        }
        $html  = '';
        $areas = Area::where('status', 1)->get();
        $html .= '<option  value="">-- Select Land Area Unit --</option>';
        foreach ($areas as $area) {
            if ($land_unit_id == $area->id) {
                $html .= '<option  value="' . $area->id . '" selected>' . $area->area_name . '</option>';
            } else {
                $html .= '<option  value="' . $area->id . '">' . $area->area_name . '</option>';
            }
        }
        return response()->json(['html' => $html]);
    }

    public function getLatLong(Request $request){
      // "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&key=YOUR_API_KEY"
      $address = $request->address;
      if(!empty($address)){
            //Formatted address
            $formattedAddr = str_replace(' ','+',$address);
            //Send request and receive json data by address
            $geocodeFromAddr = $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=true_or_false&key=AIzaSyBrmhSdmVkbchPsUoguIFdOyChSpqfckts');
            $output = json_decode($geocodeFromAddr);
            //Get latitude and longitute from json data
            if(isset($output->results) && !empty($output->results)){
                $data['latitude']  = $output->results[0]->geometry->location->lat; 
                $data['longitude'] = $output->results[0]->geometry->location->lng;
            }
            //Return latitude and longitude of the given address
            if(!empty($data)){
                return json_encode($data);
            }else{
                return false;
            }
        }else{
            return false;   
        }
    }

    /*Get Salesman List */
    public function getSalesman(Request $request){
        $html     = '';
        $selected = "";
        if ($request->input('selected_salesman') != '') {
            $selected = $request->input('selected_salesman');
        }

        //$salesmans = StaffUser::where(['status' => 1, 'country_id' => $request->input('country_id')])->get();
        if(!empty(auth()->user()->is_administrator)){
            /*Salesman Listing for Admin*/
            $salesmans = StaffUser::whereNULL('inactive')->where('role_id',1)->whereNULL('is_administrator')->get();
            if($salesmans->isEmpty()){
                $html .= '<option  value="">-- No Salesman Found  --</option>';
                return response()->json(['html' => $html]);       
            }else {
                 $html .= '<option  value="">-- Select Salesman --</option>';
                foreach ($salesmans as $salesman) {
                    if ($selected == $salesman->id) {
                        $html .= '<option  value="' . $salesman->id . '" selected>' . ucwords($salesman->first_name).' '.$salesman->last_name . '</option>';
                    } else {
                        $html .= '<option  value="' . $salesman->id . '">' . ucwords($salesman->first_name).' '.$salesman->last_name  . '</option>';
                    }
                }
                return response()->json(['html' => $html]);            
            }
        }else {
            /*Salesman Listing by Role Permission*/

            $salesmans = StaffUser::where('id',auth()->user()->id)->where('role_id',1)->get();

            if($salesmans->isEmpty()){
                $html .= '<option  value="">-- No Salesman Found  --</option>';
                return response()->json(['html' => $html]);       
            }else {
                 $html .= '<option  value="">-- Select Salesman --</option>';
                foreach ($salesmans as $salesman) {
                    if ($selected == $salesman->id) {
                        $html .= '<option  value="' . $salesman->id . '" selected>' . ucwords($salesman->first_name).' '.$salesman->last_name . '</option>';
                    } else {
                        $html .= '<option  value="' . $salesman->id . '" selected>' . ucwords($salesman->first_name).' '.$salesman->last_name  . '</option>';
                    }
                }
                return response()->json(['html' => $html]);            
            }
        }
    }

    /*Get Salesman Data */
    public function getSalesmanData(Request $request){
        $html     = '';
        $selected = "";
        if ($request->input('selected_client') != '') {
            $selected = $request->input('selected_client');
        }
        /*client_id :- 1 == Shopkeeper*/
        if($request->input('client_id') == 1){
            $datas = Shopkeeper::where(['is_verified' => 1, 'salesman_id' => $request->input('salesman_id')])->get();
            $html .= '<option  value="">-- Select Shopkeeper --</option>';
            foreach ($datas as $shopkeeper) {
                $sel = ($selected==$shopkeeper->id)?'selected':'';
                $html .= '<option  value="' . $shopkeeper->id . '" '.$sel.'>' . ucwords($shopkeeper->name). '</option>';
                }
        }
        /*client_id :-  2 == Lead*/
        else if($request->input('client_id') == 2){
            $datas = Lead::where(['assigned_to' => $request->input('salesman_id')])->get();
            $html .= '<option  value="">-- Select Leads --</option>';
            foreach ($datas as $lead) {
                $sel = ($selected==$lead->id)?'selected':'';
                $html .= '<option  value="' . $lead->id . '" '.$sel.'>' . ucwords($lead->first_name).$lead->last_name. '</option>';
                }
        }else if($request->input('client_id') == 3){
            $datas = User::where(['assigned_to' => $request->input('salesman_id')])->get();
            $html .= '<option  value="">-- Select Customers --</option>';
            foreach ($datas as $lead) {
                $sel = ($selected==$lead->id)?'selected':'';
                $html .= '<option  value="' . $lead->id . '" '.$sel.'>' . ucwords($lead->first_name).$lead->last_name. '</option>';
            }
        }else{
            $html .= '<option  value="">-- No Shopkeeper / Lead / Customers --</option>';
        }
        return response()->json(['html' => $html]);            
    }

     /*Get User Group List*/
    public function getUsergroup(Request $request){
        $html     = '';
        $selected = "";
        if ($request->input('selected_group') != '') {
            $selected = $request->input('selected_group');
        }
        $usergroups = UserGroup::where('status',1)->get();
        if($usergroups->isEmpty()){
            $html .= '<option  value="">-- No User Group Found  --</option>';
            return response()->json(['html' => $html]);       
        }else {
             $html .= '<option  value="">-- Select User Group --</option>';
            foreach ($usergroups as $usergroup) {
                if ($selected == $usergroup->id) {
                    $html .= '<option  value="' . $usergroup->id . '" selected>' . ucwords($usergroup->name) .'</option>';
                } else {
                    $html .= '<option  value="' . $usergroup->id . '">' . ucwords($usergroup->name) . '</option>';
                }
            }
            return response()->json(['html' => $html]);            
        }
    }


     /*Get Salesman List (Lavel 1) */
    public function getSalesmanLavel(Request $request){
        $html     = '';
        $selected = "";
        $salesmans = StaffUser::whereNULL('inactive')->where('role_id',1)->where('level',1)->get();
            if($salesmans->isEmpty()){
                $html .= '<option  value="">-- No Salesman Found  --</option>';
                return response()->json(['html' => $html]);       
            }else {
                 $html .= '<option  value="">-- Select Salesman --</option>';
                foreach ($salesmans as $salesman) {
                    if ($selected == $salesman->id) {
                        $html .= '<option  value="' . $salesman->id . '" selected>' . ucwords($salesman->first_name).' '.$salesman->last_name .'<sup> ( Level : '.$salesman->level .')<sup></option>';
                        
                    } else {
                        $html .= '<option  value="' . $salesman->id . '">' . ucwords($salesman->first_name).' '.$salesman->last_name  . '<sup> ( Level : '.$salesman->level .')<sup></option>';
                    }
                }
                return response()->json(['html' => $html]);            
        }
         
    }
}
