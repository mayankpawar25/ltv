<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Refund;
use App\Orderedproduct;
use App\Product;
use App\GeneralSetting as GS;
use Session;
use Illuminate\Support\Facades\Input;

class RefundController extends Controller
{
    public function all() {
      $data['status'] = [''=>'Nothing Selected'] + ['0'=>'Pending','1'=>'Accepted','-1'=>'Rejected'];
      $data['refunds'] = Refund::orderBy('id', 'DESC')->paginate(10);
      return view('admin.refunds.index', $data);
    }

    public function paginate(Request $request){

        $order       = Input::get('order');
        $columns     = Input::get('columns');
        $query_key   = Input::get('search');
        $search_key  = $query_key['value'];
        $customer_id = Input::get('customer_id');
        $status_id   = Input::get('status_id');
        $is_verified = Input::get('is_verified');
        $groups      = Input::get('groups');
        $q           = Refund::query();
        $query       = Refund::orderBy($columns[$order[0]['column']]['name'], $order[0]['dir']);

        // Filtering Data
        if($status_id!=''){
            $query->whereIn('status', $status_id );
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
                ->orWhere('employer_name', 'like', $search_key.'%')
                ->orWhere('employer_contactno', 'like', $search_key.'%')
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

                if($row->status == 0):
                  $action_btn = '<a href="#" title="Accept Request" onclick="accept(event, '.$row->id.')" style="font-size: 20px;margin-right: 5px;"><i class="fa fa-check-circle text-success"></i></a>'.''.'<a href="#" title="Reject Request" onclick="reject(event, '.$row->id.')" style="font-size: 20px;"><i class="fa fa-times-circle text-danger"></i></a>';
                elseif ($row->status == 1):
                  $action_btn = '<span class="badge badge-success">Accepted</span>';
                elseif ($row->status == -1):
                  $action_btn = '<span class="badge badge-danger">Rejected</span>';
                endif;

              $rec[] = array(
                $row->orderedproduct->order->first_name.' '.$row->orderedproduct->order->last_name,
                $row->orderedproduct->order->phone,
                $row->orderedproduct->order->email,
                $row->orderedproduct->product_name,
                $row->orderedproduct->product_total,
                $row->reason,
                date('jS F, Y', strtotime($row->orderedproduct->created_at)),
                $action_btn,
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

    public function rejected() {
      $data['refunds'] = Refund::where('status', -1)->orderBy('id', 'DESC')->paginate(10);
      return view('admin.refunds.index', $data);
    }

    public function accepted() {
      $data['refunds'] = Refund::where('status', 1)->orderBy('id', 'DESC')->paginate(10);
      return view('admin.refunds.index', $data);
    }

    public function pending() {
      $data['refunds'] = Refund::where('status', 0)->orderBy('id', 'DESC')->paginate(10);
      return view('admin.refunds.index', $data);
    }

    public function accept(Request $request) {
      $gs = GS::first();

      $refund = Refund::find($request->rid);
      $refund->status = 1;
      $refund->save();

      $op = Orderedproduct::find($refund->orderedproduct_id);
      $op->refunded = 1;
      $op->save();

      $product = Product::find($op->product_id);
      $product->sales = $product->sales - $op->quantity;
      $product->quantity = $product->quantity + $op->quantity;
      $product->save();

      // snding mail to user
      send_email($refund->orderedproduct->user->email, $refund->orderedproduct->user->first_name, 'Refund request accepted', "Your refund request for <a href='".url('/')."/product/".$refund->orderedproduct->product->slug . "/" . $refund->orderedproduct->product->id."'>" .$refund->orderedproduct->product->title. "</a> has been accepted. You will get ". $refund->orderedproduct->product_total ." " . $gs->base_curr_text . ". We will contact with you later.");
      // snding mail to vendor
      send_email($refund->orderedproduct->vendor->email, $refund->orderedproduct->vendor->shop_name, 'Order refunded', "Refund request for <a href='".url('/')."/product/".$refund->orderedproduct->product->slug."/".$refund->orderedproduct->product->id."'>" .$refund->orderedproduct->product->title. "</a> has been accepted. You will have to return back ". $refund->orderedproduct->product_total ." " . $gs->base_curr_text . " to us. We will contact with you later.");

      Session::flash('success', 'Refund request accepted!');

      return "success";
    }

    public function reject(Request $request) {
      $gs = GS::first();

      $refund = Refund::find($request->rid);
      $refund->status = -1;
      $refund->save();

      // snding mail to user
      send_email($refund->orderedproduct->user->email, $refund->orderedproduct->user->first_name, 'Refund request rejected', "Your refund request for <a href='".url('/')."/product/".$refund->orderedproduct->product->slug. '/' .$refund->orderedproduct->product->id."'>" .$refund->orderedproduct->product->title. "</a> has been rejected.");
      // snding mail to vendor
      send_email($refund->orderedproduct->vendor->email, $refund->orderedproduct->vendor->shop_name, 'Order refunded', "Refund request for <a href='".url('/')."/product/".$refund->orderedproduct->product->slug . "/" . $refund->orderedproduct->product->id."'>" .$refund->orderedproduct->product->title. "</a> has been rejected.");

      Session::flash('success', 'Refund request rejected!');

      return "success";
    }
}
