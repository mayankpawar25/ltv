<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Gateway as Gateway;
use Session;
use Image;
use Illuminate\Support\Facades\Input;
class GatewayController extends Controller
{
    public function index() {
      $data['gateways'] = Gateway::all();
      return view('admin.gateway.index', $data);
    }

    public function paginate(){
        $order       = Input::get('order');
        $columns     = Input::get('columns');
        $query_key   = Input::get('search');
        $search_key  = $query_key['value'];
        //$customer_id = Input::get('customer_id');
        $status_id   = Input::get('status_id');
        $is_verified = Input::get('is_verified');
        $groups      = Input::get('groups');
        $q           = Gateway::query();
        $query       = Gateway::orderBy($columns[$order[0]['column']]['name'], $order[0]['dir']);

        // Filtering Data
        if($status_id!=''){
            $query->where('status', $status_id );
            $q->whereIn('status', $status_id );
        }
        $number_of_records  = $q->get()->count();

        if($search_key)
        {
            $query->where(function ($k) use ($search_key) {
                $k->where('main_name', 'like', $search_key.'%');
                

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
                  anchor_link($row->main_name,route('admin.subcategory.index', $row->id)),
                  $row->name,
                  ($row->status==0)?'<span class="badge badge-danger">Deactive</span>':'<span class="badge badge-success">Active</span>',
                   '<button type="button" class="btn btn-success btn-sm float-right" data-toggle="tooltip" title="Edit"><span data-toggle="modal" data-target="#editModal'.$row->id.'"><i class="icon-pencil icon"></i></span></button>',
                    
                    
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

    public function store(Request $request) {
      $gatewayID = $request->id;
      $messages = [
        'gateimg.mimes' => 'Gateway logo must be a file of type: jpeg, jpg, png.',
        'minamo.required' => 'Minimum Limit Per Transaction is required',
        'minamo.numeric' => 'Minimum Limit Per Transaction must be number',
        'maxamo.required' => 'Maximum Limit Per Transaction is required',
        'maxamo.numeric' => 'Maximum Limit Per Transaction must be number',
        'chargefx.required' => 'Fixed Charge is required',
        'chargefx.numeric' => 'Fixed Charge must be number',
        'chargepc.required' => 'Charge in Percentage is required',
        'chargepc.numeric' => 'Charge in Percentage must be number',

      ];
      $validatedData = $request->validate([
          'name' => 'required',
          'gateimg' => 'mimes:jpeg,jpg,png',
          'rate' => 'required',
          'minamo' => 'required|numeric',
          'maxamo' => 'required|numeric',
          'chargefx' => 'required|numeric',
          'chargepc' => 'required|numeric',
      ], $messages);
      $gateway = new Gateway;
      for ($i=900; $i < 1200 ; $i++) {
        $gw = Gateway::find($i);
        if (empty($gw)) {
          $gateway->id = $i;
          break;
        }
      }
      $gateway->name = $request->name;
      $gateway->main_name = $request->name;
      $gateway->minamo = $request->minamo;
      $gateway->maxamo = $request->maxamo;
      $gateway->rate = $request->rate;
      if($request->hasFile('gateimg')) {
        $fileName = $gateway->id . '.jpg';
        $image = $request->file('gateimg');
        $location = 'assets/gateway/' . $fileName;
        Image::make($image)->resize(800, 800)->save($location);
      }
      $gateway->fixed_charge = $request->chargefx;
      $gateway->percent_charge = $request->chargepc;

      $gateway->val3 = $request->val3;

      $gateway->status = $request->status;

      $gateway->save();

      Session::flash('success', 'Gateway added successfully');

      return redirect()->back();
    }

    public function update(Request $request) {
      $gatewayID = $request->id;
      $messages = [
        'gateimg.mimes' => 'Gateway logo must be a file of type: jpeg, jpg, png.',
        'minamo.required' => 'Minimum Limit Per Transaction is required',
        'minamo.numeric' => 'Minimum Limit Per Transaction must be number',
        'maxamo.required' => 'Maximum Limit Per Transaction is required',
        'maxamo.numeric' => 'Maximum Limit Per Transaction must be number',
        'chargefx.required' => 'Fixed Charge is required',
        'chargefx.numeric' => 'Fixed Charge must be number',
        'chargepc.required' => 'Charge in Percentage is required',
        'chargepc.numeric' => 'Charge in Percentage must be number',

      ];
      $validatedData = $request->validate([
          'name' => 'required',
          'rate' => 'required|numeric',
          'gateimg' => 'mimes:jpeg,jpg,png',
          'minamo' => 'required|numeric',
          'maxamo' => 'required|numeric',
          'chargefx' => 'required|numeric',
          'chargepc' => 'required|numeric',
      ], $messages);

      $gateway = Gateway::find($gatewayID);
      $gateway->name = $request->name;
      $gateway->rate = $request->rate;
      $gateway->minamo = $request->minamo;
      $gateway->maxamo = $request->maxamo;
      if($request->hasFile('gateimg')) {
        $gateImagePath = 'assets/gateway/' . $gateway->id . '.jpg';
        if(file_exists($gateImagePath)) {
          unlink($gateImagePath);
        }
        $image = $request->file('gateimg');
        $fileName = $gateway->id . '.jpg';
        $location = 'assets/gateway/' . $fileName;
        Image::make($image)->resize(800, 800)->save($location);
      }
      $gateway->fixed_charge = $request->chargefx;
      $gateway->percent_charge = $request->chargepc;
      if ($gatewayID > 899) {
        $gateway->val3 = $request->val3;
      }
       if ($gatewayID == 100) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
        $gateway->val3 = $request->val3;
       }
      if ($gatewayID == 101) {
        $gateway->val1 = $request->val1;
      }
      if ($gatewayID == 102) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
      }
      if($gatewayID == 103) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
      }
      if($gatewayID == 104) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
      }
      if($gatewayID == 105) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
        $gateway->val3 = $request->val3;
        $gateway->val4 = $request->val4;
        $gateway->val5 = $request->val5;
        $gateway->val6 = $request->val6;
        $gateway->val7 = $request->val7;
      }
      if($gatewayID == 106) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
      }
      if($gatewayID == 107) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
      }
      if($gatewayID == 108) {
        $gateway->val1 = $request->val1;
      }
      if($gatewayID == 501) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
      }
      if($gatewayID == 502) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
        $gateway->val3 = $request->val3;
      }
      if($gatewayID == 503) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
      }
      if($gatewayID == 504) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
      }
      if($gatewayID == 505) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
      }
      if($gatewayID == 506) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
      }
      if($gatewayID == 507) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
      }
      if($gatewayID == 508) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
      }
      if($gatewayID == 509) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
      }
      if($gatewayID == 510) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
      }
      if ($gatewayID == 512) {
        $gateway->val1 = $request->val1;
      }
      if($gatewayID == 513) {
        $gateway->val1 = $request->val1;
        $gateway->val2 = $request->val2;
      }
      $gateway->status = $request->status;

      $gateway->save();

      Session::flash('success', $gateway->name.' informations updated successfully!');

      return redirect()->back();
    }
}
