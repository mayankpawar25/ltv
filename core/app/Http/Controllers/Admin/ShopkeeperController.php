<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Input;
use Mail;

use App\GeneralSetting as GS;
use App\Http\Controllers\Controller;
use App\Country,App\State,App\City,App\Zipcode,App\Shopkeeper,App\UserGroup;
use App\Models\StaffUser,App\Transaction,App\PaymentMode,App\Lead,App\CustomerGroup;
use DB,Auth,Image,Validator,Artisan,Session;
class ShopkeeperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        if(auth()->user()->is_administrator){
            $data['shopkeeper'] = Shopkeeper::paginate(20);
        }else{
            $data['shopkeeper'] = Shopkeeper::where('salesman_id',auth()->user()->id)->paginate(20);
        }
        $data['is_verified'] = ['0'=>'Not Verified','1'=>'Verified',2=>'Not Interested'];
        $data['status'] = ['0'=>'Inactive','1'=>'Active'];
        $data['group'] = UserGroup::get()->pluck('name', 'id')->toArray();
        // dd($data);
        $data['user_role'] = 'shopkeeper';
        return view('admin.shopkeeper.index',$data);
    }

    public function paginate(){

        $order       = Input::get('order');
        $columns     = Input::get('columns');
        $query_key   = Input::get('search');
        $search_key  = $query_key['value'];
        $customer_id = Input::get('customer_id');
        $status_id   = Input::get('status_id');
        $is_verified = Input::get('is_verified');
        $groups      = Input::get('groups');
        $q           = Shopkeeper::query();
        $query       = Shopkeeper::orderBy($columns[$order[0]['column']]['name'], $order[0]['dir']);

        // Filtering Data
        if($status_id!=''){
            $query->where('status', $status_id );
            $q->whereIn('status', $status_id );
        }

        if($is_verified!=''){
            $q->whereIn('is_verified', $is_verified );
            $query->whereIn('is_verified', $is_verified );
        }
        if($groups!=''){
            $q->whereIn('usergroup_id', $groups );
            $query->whereIn('usergroup_id', $groups );
        }
        

        // If the user has permission to view only the ones that are created by himself;
        if(!check_perm('shopkeepers_view') && check_perm('shopkeepers_view_own'))
        {
            $q->where(function($k){
                $k->where('salesman_id', auth()->user()->id);
            });
            $query->where(function($k){
                $k->where('salesman_id', auth()->user()->id);
            });                   
            
        }


        /*if($customer_id)
        {
            $q->whereHas('invoice', function ($q) use ($customer_id) {
                $q->where('invoices.customer_id', '=', $customer_id);
            });

            $query->whereHas('invoice', function ($q) use ($customer_id) {
                $q->where('invoices.customer_id', '=', $customer_id);
            });

        }*/

        $number_of_records  = $q->get()->count();

        if($search_key)
        {
            $query->where(function ($k) use ($search_key) {
                $k->where('name', 'like', $search_key.'%')
                ->orWhere('shopname', 'like', $search_key.'%')
                ->orWhere('email', 'like', $search_key.'%')
                ->orWhere('mobile', 'like', $search_key.'%')
                ->orWhere('phone', 'like', $search_key.'%')
                ->orwhereHas('usergroup',function ($q) use ($search_key){
                    $q->where('user_groups.name', 'like', $search_key.'%');
                })
                ->orwhereHas('country',function ($q) use ($search_key){
                    $q->where('countries.name', 'like', $search_key.'%');
                })
                ->orwhereHas('state',function ($q) use ($search_key){
                    $q->where('states.name', 'like', $search_key.'%');
                })
                ->orwhereHas('city',function ($q) use ($search_key){
                    $q->where('cities.name', 'like', $search_key.'%');
                })
                ->orwhereHas('zipcode',function ($q) use ($search_key){
                    $q->where('zipcodes.area_name', 'like', $search_key.'%');
                });



            });
        }

        $recordsFiltered = $query->get()->count();
        $length = Input::get('length');
        if($length != '-1'){
            $query->skip(Input::get('start'))->take(Input::get('length'));
        }
        $data = $query->get();
        //

        $rec = [];

        if (count($data) > 0)
        {
            foreach ($data as $key => $row)
            {   
                $rec[] = array(
                    anchor_link($row->name,route('admin.shopkeeper.show',$row->id)),
                    $row->shopname,
                    $row->email,
                    $row->mobile,
                    $row->phone,
                    $row->address,
                    Country::find($row->country_id)->name,
                    State::find($row->state_id)->name,
                    City::find($row->city_id)->name,
                    (isset($row->zipcode_id)) ? Zipcode::find($row->zipcode_id)->area_name : '',
                    (!empty($row->usergroup))?$row->usergroup->name:'-',
                    ($row->is_verified==0)?'<span class="badge badge-warning">Not Verified</span>':(($row->is_verified==1)?'<span class="badge badge-primary">Verified</span>':'<span class="badge badge-primary">Not Interested</span>'),
                    ($row->status==0)?'<span class="badge badge-warning">Inactive</span>':'<span class="badge badge-success">Active</span>',
                    
				    anchor_link('<button class="btn btn-sm btn-primary pull-right"><span class="icon-eye icons " data-toggle="tooltip" title="View Ledger"></span></button>',route('admin.shopkeeper.transaction',[$row->id,'1'])).' '.
                    anchor_link('<button class="btn btn-sm btn-warning pull-right"><span class="icon-basket" data-toggle="tooltip" title="Orders"></span></button>',route('admin.orders.all',$row->id)).' '.
                    anchor_link('<button class="btn btn-sm btn-success pull-right"><span class="icon-pencil icons" data-toggle="tooltip" title="Edit"></span></button>',route('admin.shopkeeper.edit',$row->id),'','shopkeepers_edit').' '.
                    anchor_link('<button class="btn btn-sm btn-danger pull-right"><span class="icon-trash icons" data-toggle="tooltip" title="Delete"></span></button>',route('admin.shopkeeper.delete',$row->id),'','shopkeepers_delete'),
					  

                    /*a_links('Action',[
                        [
                            'action_link' => route('admin.shopkeeper.edit', $row->id), 
                            'action_text' => __('form.edit'), 'action_class' => '',
                            'permission' => 'shopkeepers_edit',
                        ],
                        [
                            'action_link' => route('admin.shopkeeper.delete', $row->id), 
                            'action_text' => __('form.delete'), 'action_class' => 'delete_item',
                            'permission' => 'shopkeepers_delete',
                        ]
                    ]),*/
                );
            }
        }
        /*class="btn btn-warning btn-sm"  data-toggle="tooltip" title="View Ledger"*/
        $output = array(
            "draw" => intval(Input::get('draw')),
            "recordsTotal" => $number_of_records,
            "recordsFiltered" => $recordsFiltered,
            "data" => $rec
        );
        return response()->json($output);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($lead=NULL){

        $rec = [];

        $data['states'] = ["" => __('form.nothing_selected')];
        $data['cities'] = ["" => __('form.nothing_selected')];
        $data['areas']  = ["" => __('form.nothing_selected')];

        if($lead)
        {
            $rec = Lead::withTrashed()->find($lead);
            if(!$rec)
            {
                abort(404);
            }
            $rec->lead_id = $rec->id;
            if($rec->customer_id)
            {
                abort(404);
            }
            unset($rec->id);
            $number = [];
            if($rec->alternate_number){
                $number = explode(',',$rec->alternate_number);
            }

            $rec->owner_name = ucwords($rec->first_name.' '.$rec->last_name);
            $rec->shop_name  = ucwords($rec->company);
            $rec->email      = $rec->email;
            $rec->mobile     = $rec->phone;
            $rec->phone      = (isset($number[0]))?$number[0]:'';
            $rec->country_id = $rec->country_id;
            $rec->state_id   = State::where('name' ,$rec->state)->first()->id;
            $rec->city_id    = City::where('name' ,$rec->city)->first()->id;
            $rec->salesman_id = $rec->assigned_to;
            $rec->address     = ucwords($rec->address);
            $data['states']  = ["" => __('form.nothing_selected')]  + State::where('country_id' ,$rec->country_id)->orderBy('name','ASC')->pluck('name','id')->toArray();
            $data['cities']  = ["" => __('form.nothing_selected')]  + City::where('state_id' ,$rec->state_id)->orderBy('name','ASC')->pluck('name','id')->toArray();
            $data['areas']  = ["" => __('form.nothing_selected')]  + Zipcode::where('city_id' ,$rec->city_id)->orderBy('area_name','ASC')->pluck('area_name','id')->toArray();

        }
        $data['countries'] = ["" => __('form.nothing_selected')]  + Country::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        $data['salesman'] = StaffUser::whereNULL('inactive')->where('role_id',1)->whereNULL('is_administrator')->orderBy('name','ASC')->select(DB::raw('CONCAT(first_name, " ", last_name) AS name,id'))->pluck('name','id')->toArray();
        $data['usergroups'] = ["" => __('form.nothing_selected')]  + UserGroup::orderBy('name','ASC')->pluck('name','id')->toArray();
        $data['user_role'] = 'shopkeeper';
        $data['tags'] = [];
        return view('admin.shopkeeper.create', compact('data'))->with('rec', $rec);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $validatedRequest = $request->validate([
          'owner_name' => 'required',
          'shop_name' => 'required',
          'email'=> 'required',
          'mobile' => 'required',
          //'area'=> 'required',
          'city' => 'required',
          'state' => 'required',
          'country' => 'required',
          'address' => 'required',
        ]);


        $current_time = time();
        $path = 'assets/shopkeeper/'.$current_time;
        $owner_pic = '';
        $shop_pic = '';
        $logo = '';
        $banner = '';

        $shopkeeper = new Shopkeeper;
        $shopkeeper->name = $request->owner_name;
        $shopkeeper->shopname = $request->shop_name;
        $shopkeeper->email = $request->email;;
        $shopkeeper->password = Hash::make($request->password);
        $shopkeeper->mobile = $request->mobile;
        $shopkeeper->phone = $request->phone;
        $shopkeeper->zipcode_id = $request->area;
        $shopkeeper->city_id = $request->city;
        $shopkeeper->state_id = $request->state;
        $shopkeeper->country_id = $request->country;
        $shopkeeper->address = $request->address;
        $shopkeeper->latitude = $request->latitude;
        $shopkeeper->longitude = $request->longitude;
        $shopkeeper->status = $request->status;
        $shopkeeper->usergroup_id = $request->usergroup_id;
        $shopkeeper->images = '[]';
        $shopkeeper->documents = '[]';
        $shopkeeper->user_role = $request->user_role;
        $shopkeeper->qr_code = '';
        $shopkeeper->is_verified = 0;
        $shopkeeper->email_verified = 0;
        $shopkeeper->sms_verified = 0;
        $shopkeeper->sms_ver_code = rand(1 , 99999);
        $shopkeeper->email_ver_code = rand(1, 99999);
        $shopkeeper->salesman_id  = (isset($request->salesman_id)) ? $request->salesman_id : NULL;
        $shopkeeper->folder = $current_time;
        $shopkeeper->save();

        // If Lead was converted to Customer then Update Lead Status
        if(isset($request->lead_id) && $request->lead_id)
        {                
            $lead                   = Lead::withTrashed()->find($request->lead_id);

            if($lead)
            {
                $lead->dealer_id        = $shopkeeper->id;
                $lead->lead_status_id   = LEAD_STATUS_DEALER;
                $lead->is_lost          = NULL;
                $lead->deleted_at       = NULL;
                $lead->save();
            }
            
        }

        /* Path Create for Uploading */
        if (!file_exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        /* Path Create for Uploading */

        /* Images Uploading Section */
        if($request->hasFile('owner_pic')){
            $owner_pic = uniqid().rand(1111,9999) . '.jpg';
            $owner_pic_location = $path.'/'.$owner_pic;
            $background = Image::canvas(570, 570);
            $resizedImage = Image::make($request->owner_pic)->resize(570, 570, function ($c) {
                $c->aspectRatio();
            });
            // insert resized image centered into background
            $background->insert($resizedImage, 'center');
            // save or do whatever you like
            $background->save($owner_pic_location);
        }

        if($request->hasFile('shop_pic')){
            $shop_pic = uniqid().rand(1111,9999) . '.jpg';
            $shop_pic_location =  $path.'/'.$shop_pic;
            $background = Image::canvas(570, 570);
            $resizedImage = Image::make($request->shop_pic)->resize(570, 570, function ($c) {
                $c->aspectRatio();
            });
            // insert resized image centered into background
            $background->insert($resizedImage, 'center');
            // save or do whatever you like
            $background->save($shop_pic_location);
        }

        if($request->hasFile('logo')){
            $logo = uniqid().rand(1111,9999) . '.jpg';
            $logo_location =  $path.'/'.$logo;
            $background = Image::canvas(570, 570);
            $resizedImage = Image::make($request->logo)->resize(570, 570, function ($c) {
                $c->aspectRatio();
            });
            // insert resized image centered into background
            $background->insert($resizedImage, 'center');
            // save or do whatever you like
            $background->save($logo_location);
        }

        if($request->hasFile('banner')){
            $banner = uniqid().rand(1111,9999) . '.jpg';
            $banner_location =  $path.'/'.$banner;
            $background = Image::canvas(570, 570);
            $resizedImage = Image::make($request->banner)->resize(570, 570, function ($c) {
                $c->aspectRatio();
            });
            // insert resized image centered into background
            $background->insert($resizedImage, 'center');
            // save or do whatever you like
            $background->save($banner_location);
        }

        $documents = [];
        if($request->file('doc')){
            $imgs = $request->file('doc');
            foreach($imgs as $key => $img){
                $image = uniqid().rand(1111,9999) .'.'. $img->getClientOriginalExtension();
                $root =  $path.'/'.$image;
                $uploaded  = $img->move($path, $image);

                $documents[$key]['document_type'] = $request->doc_type[$key];
                $documents[$key]['image_name'] = $image;
                $documents[$key]['is_verified'] = '0';
            }
        }
        /* Images Uploading Section */

        /* Update Document and Images for User */
        $update = Shopkeeper::find($shopkeeper->id);
        $update->images = json_encode(['owner_pic'=>$owner_pic,'shop_pic' => $shop_pic,'logo'=>$logo,'banner_image'=>$banner]);
        $update->documents = json_encode($documents);
        /* Update Document and Images for User */

        /* Generate Qr-Code */
        $url = route('admin.shopkeeper.show',$shopkeeper->id);
        $image = \QrCode::format('png')
                            ->size(500)->errorCorrection('H')
                            ->generate($shopkeeper->id);
        // $images = response($image)->header('Content-type','image/png');
        // $output_file = '/img/qr-code/img-' . time() . '.png';
        // Storage::disk('local')->put($output_file, $image);

        $filename = uniqid() . '.jpg';
        $location = 'assets/qrcode/' . $filename;

        $background = Image::canvas(570, 570);
        // insert resized image centered into background
        $background->insert($image, 'center');
        // save or do whatever you like
        $background->save($location);

        $update->qr_code = $location;
        $update->save();
        /* Generate Qr-Code */

        return redirect()->route('admin.shopkeeper.index')->with('success','Successfully added Dealer');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id){
        $data['shopkeeper'] = Shopkeeper::find($user_id);
        return view('admin.shopkeeper.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $data['user_role'] = 'shopkeeper';

        $shopkeeper = Shopkeeper::findorfail($id);
        $shopkeeper->owner_name = ucwords($shopkeeper->name);
        $shopkeeper->shop_name  = ucwords($shopkeeper->shopname);

        $data['countries'] = ["" => __('form.nothing_selected')]  + Country::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        $data['states']  = ["" => __('form.nothing_selected')]  + State::where('country_id' ,$shopkeeper->country_id)->orderBy('name','ASC')->pluck('name','id')->toArray();
        $data['cities']  = ["" => __('form.nothing_selected')]  + City::where('state_id' ,$shopkeeper->state_id)->orderBy('name','ASC')->pluck('name','id')->toArray();
        $data['areas']  = ["" => __('form.nothing_selected')]  + Zipcode::where('city_id' ,$shopkeeper->city_id)->orderBy('area_name','ASC')->pluck('area_name','id')->toArray();
        $data['salesman'] = StaffUser::whereNULL('inactive')->where('role_id',1)->whereNULL('is_administrator')->orderBy('name','ASC')->select(DB::raw('CONCAT(first_name, " ", last_name) AS name,id'))->pluck('name','id')->toArray();
        $data['usergroups'] = ["" => __('form.nothing_selected')]  + UserGroup::orderBy('name','ASC')->pluck('name','id')->toArray();

        // $data                       = $customer->dropdowns();
        // $customer['group_id']       = $customer->groups()->pluck('group_id')->toArray();
        return view('admin.shopkeeper.create', compact('data'))->with('rec',$shopkeeper);

        // return view('admin.shopkeeper.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        
        $shopkeeper = Shopkeeper::find($id);
        // $documents = json_decode($shopkeeper->documents);
        $documents = ($shopkeeper->documents=='' || $shopkeeper->documents==[])?[]:json_decode($shopkeeper->documents);
        $owner_pic = $request->old_owner_pic;
        $shop_pic = $request->old_shop_pic;
        $logo = $request->old_logo;
        $banner = $request->old_banner;

        $current_time = $shopkeeper->folder;
        $path = 'assets/shopkeeper/'.$current_time;

        $docs = [];
        $imgs = $request->file('doc');
        if(!empty($imgs))
            foreach($imgs as $key => $img){
                $image = uniqid().rand(1111,9999).'.'.$img->getClientOriginalExtension();
                $root =  $path.'/'.$image;
                $uploaded  = $img->move($path, $image);
                $docs[$key]['document_type'] = $request->doc_type[$key];
                $docs[$key]['image_name'] = $image;
                $docs[$key]['is_verified'] = '0';
            }
        
        /* Path Create for Uploading */
        if (!file_exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        /* Path Create for Uploading */
        
        /* Images Uploading Section */
        if($request->hasFile('owner_pic')){
            $owner_pic = uniqid().rand(1111,9999) . '.jpg';
            $owner_pic_location = $path.'/'.$owner_pic;
            $background = Image::canvas(570, 570);
            $resizedImage = Image::make($request->owner_pic)->resize(570, 570, function ($c) {
                $c->aspectRatio();
            });
            // insert resized image centered into background
            $background->insert($resizedImage, 'center');
            // save or do whatever you like
            $background->save($owner_pic_location);
        }

        if($request->hasFile('shop_pic')){
            $shop_pic = uniqid().rand(1111,9999) . '.jpg';
            $shop_pic_location =  $path.'/'.$shop_pic;
            $background = Image::canvas(570, 570);
            $resizedImage = Image::make($request->shop_pic)->resize(570, 570, function ($c) {
                $c->aspectRatio();
            });
            // insert resized image centered into background
            $background->insert($resizedImage, 'center');
            // save or do whatever you like
            $background->save($shop_pic_location);
        }

        if($request->hasFile('logo')){
            $logo = uniqid().rand(1111,9999) . '.jpg';
            $logo_location =  $path.'/'.$logo;
            $background = Image::canvas(570, 570);
            $resizedImage = Image::make($request->logo)->resize(570, 570, function ($c) {
                $c->aspectRatio();
            });
            // insert resized image centered into background
            $background->insert($resizedImage, 'center');
            // save or do whatever you like
            $background->save($logo_location);
        }

        if($request->hasFile('banner')){
            $banner = uniqid().rand(1111,9999) . '.jpg';
            $banner_location =  $path.'/'.$banner;
            $background = Image::canvas(570, 570);
            $resizedImage = Image::make($request->banner)->resize(570, 570, function ($c) {
                $c->aspectRatio();
            });
            // insert resized image centered into background
            $background->insert($resizedImage, 'center');
            // save or do whatever you like
            $background->save($banner_location);
        }
        /* Images Uploading Section */
        if(!empty($docs)){
            $shopkeeper->documents = json_encode(array_merge($documents,$docs));
        }

        $shopkeeper->name = $request->owner_name;
        $shopkeeper->shopname = $request->shop_name;
        $shopkeeper->email = $request->email;
        $shopkeeper->mobile = $request->mobile;
        $shopkeeper->phone = $request->phone;
        $shopkeeper->zipcode_id = $request->area;
        $shopkeeper->city_id = $request->city;
        $shopkeeper->state_id = $request->state;
        $shopkeeper->country_id = $request->country;
        $shopkeeper->address = $request->address;
        $shopkeeper->latitude = $request->latitude;
        $shopkeeper->longitude = $request->longitude;
        $shopkeeper->status = $request->status;
        $shopkeeper->user_role = $request->user_role;
        $shopkeeper->usergroup_id = $request->usergroup_id;
        $shopkeeper->salesman_id = $request->salesman_id;
        $shopkeeper->folder = $current_time;
        $shopkeeper->images = json_encode(['owner_pic'=>$owner_pic,'shop_pic' => $shop_pic,'logo'=>$logo,'banner_image'=>$banner]);

        /* Generate Qr-Code */
        $url = route('admin.shopkeeper.show',$shopkeeper->id);
        
        $image = \QrCode::format('png')->merge(asset('assets/img/ss.png'), 0.3, true)
                            ->size(500)->errorCorrection('H')
                            ->generate($shopkeeper->id);
        $filename = uniqid() . '.jpg';
        $location = 'assets/qrcode/' . $filename;

        $background = Image::canvas(570, 570);
        $background->insert($image, 'center');
        $background->save($location);
        if($shopkeeper->qr_code=='qr_code' || $shopkeeper->qr_code==''){
            $shopkeeper->qr_code = $location;
        }
        /* Generate Qr-Code */
        $shopkeeper->save();
        /* Update Document and Images for User */

        return redirect()->route('admin.shopkeeper.index')->with('success','Successfully updated Dealer');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $resp = Shopkeeper::find($id);
        $resp->delete();
        return redirect()->route('admin.shopkeeper.index')->with('success','Dealer deleted');
    }

    public function documentVerification(Request $request){
        // dd($request->id,$request->docid,$request->doc_status);
        $resp = Shopkeeper::find($request->id);
        if(!empty($resp->documents)){
            $document = [];
            foreach(json_decode($resp->documents) as $key => $docs){
                if($key == $request->docid){
                    $docs->is_verified = $request->doc_status;
                    $document[$key] = $docs;
                }else{
                    $document[$key] = $docs;
                }
            }
            $resp->documents = json_encode($document);
        }
        $resp->save();
        return redirect()->route('admin.shopkeeper.show',$request->id)->with('success','Document Status successfully updated');
    }

    public function documentDelete($shop_id,$doc_id){
        $shopkeeper = Shopkeeper::find($shop_id);
        $documents = json_decode($shopkeeper->documents);
        $new_arr = [];
        foreach ($documents as $doc_key => $document) {
            if($doc_key == $doc_id){
                continue;
            }else{
                $new_arr[] = $document;
            }

        }
        $shopkeeper->documents = json_encode($new_arr);
        $shopkeeper->save();
        return redirect()->route('admin.shopkeeper.edit',$shop_id)->with('success','Document deleted');
    }

    public function changeStatus($id,$status_id){
        $resp = Shopkeeper::find($id);
        $resp->status = $status_id;
        $resp->save();

        Mail::send('admin.template.email',['email'=>$resp->email,'name'=>$resp->name], function ($message) {
            $message->from('contact@domainname.com','Company Name');
            $message->to($resp->email);
            $message->subject('Contact form submitted on domainname.com');

            dd($message);

        });
        if($resp->status == 1){
            $to = $resp->email;
            $name = $resp->email .'/'.$resp->mobile;
            $subject = 'Welcome To Laptop True Value';
            $message = '';
            send_email( $to, $name, $subject, $message);

            // $to = $resp->mobile;
            // $message = 'Greetings from Laptop True Value. You have been added as a verified dealer with Laptop True Value. Your account has been activated. Your login name is '.$resp->email.' / '.$resp->mobile.'. Please download the app from playstore. link.   Please change password from Forgot Password section to start using app. Support:07120009990.';
            // send_sms( $to, $message);
        }
        return redirect()->route('admin.shopkeeper.show',$id)->with('success','Document Status successfully updated');
    }

    public function changeAccountStatus(Request $request){
        $resp = Shopkeeper::find($request->id);
        $resp->is_verified = $request->is_verified;
        $resp->save();
        return redirect()->route('admin.shopkeeper.show',$request->id)->with('success','Verification Status successfully updated');
    }

    public function transactions(Request $request,$client_id="",$client_type_id=""){
        $data['payment_modes'] = PaymentMode::get();
        $data['client_id'] = $client_id;
        $data['client_type_id'] = $client_type_id;
        $data['gs'] = GS::first();
        $transactions = Transaction::where('client_id',$client_id)->where('client_type_id',$client_type_id)->get();
        $data['shopkeeper'] = Shopkeeper::find($client_id);
        if(!$transactions->isEmpty()){
            $last_balance_amount = Transaction::where('client_id',$client_id)->where('client_type_id',$client_type_id)->orderby('id','DESC')->first();
            foreach($transactions as $transaction){
                $transaction->payment_name = (isset($transaction->payment_mode) && ($transaction->payment_mode!=''))?PaymentMode::find($transaction->payment_mode)->name:'-';
            }
            $data['transactions'] = $transactions;
            $data['closing_balance'] = (isset($last_balance_amount) && !empty($last_balance_amount))?$last_balance_amount->after_balance:'0.00';
            $data['msg'] = 'Transaction Details';
            $data['status'] = true;
        }else{
            $data['closing_balance'] = "0.00";
            $data['msg'] = 'No Transaction Available';
            $data['status'] = false;
        }
        return view('admin.shopkeeper.transaction',$data);
    }

    public function addPayment(Request $request){
        $user = Auth::guard('salesman')->user();
        $validator = Validator::make($request->all(), [ 
            'amount'        => 'required',
            'payment_mode'  => 'required',
            'remarks'       => 'required',
        ]);
        
        if ($validator->fails()) { 
            return response()->json($validator->errors(), 401);            
        }

        $last_balance_amount = Transaction::where('client_id',$request->client_id)->where('client_type_id',$request->client_type_id)->orderby('id','DESC')->first();

        $after_balance = (isset($last_balance_amount) && !empty($last_balance_amount))?$last_balance_amount->after_balance:'0.00';

        $transaction                    = new Transaction;
        $transaction->amount            = $request->amount;
        $transaction->debit             = $request->amount;
        $transaction->client_id         = $request->client_id;
        $transaction->client_type_id    = $request->client_type_id;
        $transaction->staff_user_id     = $request->client_id;
        $transaction->payment_mode      = $request->payment_mode;
        $transaction->trx_id            = $request->transaction_id;
        $transaction->staff_user_remark = $request->remarks;
        $transaction->after_balance     = $after_balance - $request->amount;
        $transaction->save();

        /*Notification*/
        $title = sprintf(__('Collect Payment'));
        $message = sprintf(__('New Payment Collect Successfully'), __('From Client'));
        // Log Activity
        $description = $message.' '.anchor_link('Payment Id #'.$transaction->id,route('admin.shopkeeper.transaction',[$request->client_id,$request->client_type_id]) );
        log_activity($transaction, $description);

        // salesmanNotification(Auth::id(),$title,$message);
        $salesman = StaffUser::find(Auth::id());
        sendNotification($salesman->fcm_id,$title,$message);
        /*Notification*/

        if($transaction->id!=''){
            $msg = 'Payment Collect Successfully';
            $status = 'success';
        }else{
            $msg = 'No Payment Collect Detail Added';
            $status = 'danger';
        }
        return redirect()->route('admin.shopkeeper.transaction',[$request->client_id,$request->client_type_id])->with($status,$msg);
    }

    public function import_page(Request $request){
        $data['group_id_list'] = CustomerGroup::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        $data = Shopkeeper::dropdown(['assigned_to_list']);
        return view('admin.shopkeeper.import', compact('data'))->with('rec', "");
    }

    public function download_sample_dealer_import_file(Request $request){
        $filename = 'sample_dealer_import_file';
        $spreadsheet = new Spreadsheet();
        $Excel_writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $spreadsheet->setActiveSheetIndex(0);
        $activeSheet = $spreadsheet->getActiveSheet();
        $columns = Shopkeeper::column_sequence_for_import();
        foreach ($columns as $key=>$name){
            $activeSheet->setCellValue($key.'1' , str_replace("_", " ", ucfirst($name) ))->getStyle($key.'1')->getFont()->setBold(true);
            if($name=='status'){
                $activeSheet->setCellValue($key.'2' , 'Active or Inactive')->getStyle($key.'1')->getFont()->setBold(true);
            }
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); /*-- $filename is  Xlsx filename ---*/
        header('Cache-Control: max-age=0');
        $Excel_writer->save('php://output');
    }

    public function import(Request $request){
        $validator = Validator::make($request->all(), [        
            'file'        => 'required|max:1000|mimes:csv,xlsx',
            // 'assigned_to' => 'required',
            // 'status'      => 'required',
            'usergroup'   => 'required',
            'is_verified' => 'required',
            // 'password'    => 'required',
        ]);
        if ($validator->fails()) {
            return  redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $column_sequence_list = Shopkeeper::column_sequence_for_import();
        // Upload the file to a temporary directory. We will remove the file later usig cron.
        $file = Storage::putFileAs(TEMPORARY_FOLDER_IN_STORAGE, $request->file('file'), time().".".$request->file('file')->extension() );  
        $path = storage_path('app/'.$file);
        $extension      = $request->file('file')->getClientOriginalExtension();
        $reader         = ($extension == 'csv') ? new Csv() : new Xlsx();
        // Load the file with phpspreadsheet reader
        $spreadsheet    = $reader->load($path);  
        // Get the first active work sheet
        $worksheet      = $spreadsheet->getActiveSheet();
        // Get the highest column from the column sequeunce array. It will return a letter like: S        
        $highest_column = max(array_keys($column_sequence_list));
        // Get the next letter after the highest letter of the sequence
        $next_column_after_highest = ++$highest_column; 
        if (strlen($next_column_after_highest) > 1){
            // if you go beyond z or Z reset to a or A
            $next_column_after_highest = $next_column_after_highest[0];
        }
        // Check if the number of columns in the file match with requirement
        if(strtolower($worksheet->getHighestColumn()) < $highest_column){
            session()->flash('validation_errors', [__('form.number_of_columns_do_no_match')]);
            session()->flash('message', __('form.import_was_not_successfull'));
            return  redirect()->back();
        }
        if(isset($worksheet) && $worksheet){
            $errors = [];
            $update = 0;
            $insert = 0;
            foreach ($worksheet->getRowIterator() as $indexOfRow=>$row){
               if($indexOfRow > 1){             
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,                   
                    $cells = [];
                    // Get all the columns of the row into $cell array
                    foreach ($cellIterator as $column_key => $cell) 
                    {
                        if(isset($column_sequence_list[$column_key]))
                        {
                            $cells[$column_sequence_list[$column_key]] = $cell->getValue();
                        }
                    }
                    if(isset($cells['first_name']) && !$cells['first_name'])
                    {
                        continue;
                    }       
                    $error = $this->validate_customer_data($cells);
                    if($error)
                    {                                
                        $errors[$indexOfRow] = $error;
                        $col = $next_column_after_highest.$indexOfRow;         
                        $this->write_error_messages_in_spreadsheet($extension, $spreadsheet, $col , implode(",", $error), $path);
                    }
                    else
                    {
                        DB::beginTransaction();
                        $success = false;
                        try {

                            $cells['name']          = $cells['name'];
                            $cells['shopname']      = $cells['shop_name'];
                            $cells['mobile']        = $cells['mobile'];
                            $cells['phone']         = $cells['alternate_number'];
                            $cells['address']       = $cells['address'];
                            $cells['password']      = Hash::make($cells['password']);
                            // $cells['password']      = Hash::make($request->password);
                            $cells['salesman_id']   = ($request->assigned_to)?$request->assigned_to:auth()->user()->id;
                            $cells['usergroup_id']  = $request->usergroup;
                            $cells['is_verified']   = $request->is_verified;
                            // $cells['assigned_to']   = $request->assigned_to;
                            $cells['folder']        = time();
                            // $cells['created_by']    = auth()->user()->id;                
                            $cells['status']        = (strtolower($cells['status'])=='active')?'1':'0';


                            if($cells['country']){
                                $country = Country::firstOrCreate(['name' => $cells['country'] ]);
                                $cells['country_id']= $country->id;
                            }

                            if($cells['state']){
                                $state = State::firstOrCreate(['name' => $cells['state'],'country_id'=>$cells['country_id']]);
                                $cells['state_id']  = $state->id;
                            }

                            if($cells['city']){
                                $city = City::firstOrCreate(['name' => $cells['city'],'state_id' => $cells['state_id']]);
                                $cells['city_id']   = $city->id;
                            }

                            if(isset($cells['area']) && $cells['area']!=''){
                                $area = Zipcode::firstOrCreate(['area_name' => $cells['area'],'city_id' => $cells['city_id'],'state_id' => $cells['state_id'],'country_id'=>$cells['country_id'] ]);
                                $cells['zipcode_id']   = $area->id;
                            }


                            // Create the Customer
                            $check_for_update = Shopkeeper::where('mobile',$cells['mobile'])->first();
                            if(!empty($check_for_update)){
                                $update++;
                                unset($cells['shop_name']);
                                unset($cells['alternate_number']);
                                unset($cells['country']);
                                unset($cells['state']);
                                unset($cells['city']);
                                unset($cells['area']);
                                $customer               = Shopkeeper::where('mobile',$cells['mobile'])->update($cells);
                            }else{
                                $insert++;
                                $customer               = Shopkeeper::create($cells);
                            }
                            
                            // dd($cells,$customer);

                            // disable activity logging
                            // $customer->disableLogging();
                            // Create Contact Person
                            // Remove the values of the Row
                            $this->clear_all_columns_of_a_row_in_spreadsheet($spreadsheet, $path, $column_sequence_list, $indexOfRow);
                            DB::commit();
                        }
                        catch (\Exception  $e)
                        {   
                            dd($e);
                            DB::rollback();
                            $col = $next_column_after_highest.$indexOfRow;         
                            $this->write_error_messages_in_spreadsheet($extension, $spreadsheet, $col , __('form.system_error') , $path);
                        }
                    }
               }
            }
            if(count($errors) > 0){
                $download_link = gen_url_for_attachment_download($file);
                $message = sprintf(__('form.import_download_file_message'), anchor_link(__('form.file'), $download_link));
                session()->flash('download_file_to_see_unimported_rows', $message);
                session()->flash('message',(__('( Inserted Dealers ='.$insert.' Updated Dealers = '.$update.')')));
                return redirect()->route('admin.shopkeeper.import_page');
            }else{
                session()->flash('message', __('form.success_add').__('( Inserted Dealers ='.$insert.' Updated Dealers = '.$update.')'));
                return redirect()->route('admin.shopkeeper.import_page');
            }
        }else{
            session()->flash('message', __('form.invalid_file_provided'));
            return redirect()->route('admin.shopkeeper.import_page')->with('danger', __('form.invalid_file_provided'));
        }
    }

    private function validate_customer_data($records){
        $validator = Validator::make($records, [
            'name'             => 'required',
            'shop_name'        => 'required',
            'mobile'           => 'required|numeric|digits_between:10,10',
            'email'            => 'nullable',
        ]);

        if ($validator->fails()) 
        {
            return $validator->errors()->all();
        }
    }

    private function get_spreadsheet_writer($file_extension, $spread_sheet){
        if($file_extension == 'csv')
        {
            return new \PhpOffice\PhpSpreadsheet\Writer\Csv($spread_sheet);
        }
        else
        {
            return new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spread_sheet);
        }
    }

    private function write_error_messages_in_spreadsheet($extension, $spreadsheet, $column, $message, $path){
        $spreadsheet->getActiveSheet()->setCellValue($column , $message );
        $spreadsheet->getActiveSheet()->getStyle($column)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
        $writer = $this->get_spreadsheet_writer($extension, $spreadsheet);
        $writer->save($path); 
    }

    private function clear_all_columns_of_a_row_in_spreadsheet($spreadsheet, $path, $column_sequence_list, $row_number){
        foreach ($column_sequence_list as $key=>$value){
            $spreadsheet->getActiveSheet()->setCellValue($key.$row_number , NULL);
        } 
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);
    }

    public function adminVerify(Request $request,$id){
        $resp = Shopkeeper::find($id);
        $resp->admin_verify = json_encode($request->admin_check);
        $resp->save();
        return redirect()->back()->with('success','Admin Verified Successfully Added');
    }



}