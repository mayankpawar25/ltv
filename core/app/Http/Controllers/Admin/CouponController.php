<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneralSetting as GS;
use App\Coupon;
use Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;
class CouponController extends Controller
{
    public function index() {
      $data['coupons'] = Coupon::orderBy('id', 'DESC')->get();
      return view('admin.coupon-lists', $data);
    }

    public function paginate(){
       $order       = Input::get('order');
        $columns     = Input::get('columns');
        $query_key   = Input::get('search');
        $search_key  = $query_key['value'];
        //$customer_id = Input::get('customer_id');
        //$status_id   = Input::get('status_id');
        $is_verified = Input::get('is_verified');
        $groups      = Input::get('groups');
        $q           = Coupon::query();
        $query       = Coupon::orderBy($columns[$order[0]['column']]['name'], $order[0]['dir']);

        // Filtering Data
        /*if($status_id!=''){
            $query->where('status', $status_id );
            $q->whereIn('status', $status_id );
        }*/
        $number_of_records  = $q->get()->count();

        if($search_key)
        {
            $query->where(function ($k) use ($search_key) {
                $k->where('coupon_code', 'like', $search_key.'%')
                ->orWhere('coupon_type', 'like', $search_key.'%')
                ->orWhere('coupon_min_amount', 'like', $search_key.'%')
                ->orWhere('valid_till', 'like', $search_key.'%')
                 ->orWhere('coupon_amount', 'like', $search_key.'%');

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
                    $row->coupon_code,
                    $row->coupon_type,
                    $row->coupon_amount,
                    $row->coupon_min_amount,
                    $row->valid_till,
                    '<a href="'.route('admin.coupon.edit', $row->id).'" class="btn btn-success btn-sm" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                    <a href="#" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>',
                    
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

    public function create() {
      return view('admin.coupon');
    }

    public function edit($id) {
      $data['coupon'] = Coupon::find($id);
      return view('admin.coupon-edit', $data);
    }

    public function store(Request $request) {
      $request->validate([
        'coupon_code' => 'required|unique:coupons',
        'coupon_amount' => 'required',
        'minimum_amount' => 'required_if:coupon_type,fixed',
        // 'minimum_amount' => [
        //   function ($attribute, $value, $fail) use ($request) {
        //       if ($request->type == 'fixed') {
        //           $fail('Minimum amount is required');
        //       }
        //   },
        // ],
        'valid_till' => 'required',
        'type_helper' => [
          function ($attribute, $value, $fail) use ($request) {
              if (!$request->has('coupon_type')) {
                  $fail('Type is required');
              }
          },
        ]
      ]);

      // $gs = GS::first();
      // $in = $request->except('_token', 'type_helper', 'valid_till', 'minimum_amount');
      // $valid_till = new \Carbon\Carbon($request->valid_till);
      // $in['valid_till'] = $valid_till->format('Y-m-d');
      // if ($request->coupon_type == 'fixed') {
      //   $in['coupon_min_amount'] = $request->minimum_amount;
      // }
      // $in['valid_till'] = $valid_till->format('Y-m-d');
      // $gs->fill($in)->save();

      $coupon = new Coupon;
      $coupon->coupon_code = $request->coupon_code;
      $coupon->coupon_type = $request->coupon_type;
      $coupon->coupon_amount = $request->coupon_amount;
      $coupon->coupon_min_amount = $request->minimum_amount;
      $coupon->valid_till = $request->valid_till;
      $coupon->save();

      Session::flash('success', 'Coupon added successfully!');
      return back();
    }

    public function update(Request $request) {
      $coupon = Coupon::find($request->coupon_id);

      $request->validate([
        'coupon_code' => [
            'required',
            Rule::unique('coupons')->ignore($coupon->id),
        ],
        'coupon_amount' => 'required',
        'minimum_amount' => 'required_if:coupon_type,fixed',
        'valid_till' => 'required',
        'type_helper' => [
          function ($attribute, $value, $fail) use ($request) {
              if (!$request->has('coupon_type')) {
                  $fail('Type is required');
              }
          },
        ]
      ]);

      // $gs = GS::first();
      // $in = $request->except('_token', 'type_helper', 'valid_till', 'minimum_amount');
      // $valid_till = new \Carbon\Carbon($request->valid_till);
      // $in['valid_till'] = $valid_till->format('Y-m-d');
      // if ($request->coupon_type == 'fixed') {
      //   $in['coupon_min_amount'] = $request->minimum_amount;
      // }
      // $in['valid_till'] = $valid_till->format('Y-m-d');
      // $gs->fill($in)->save();


      $coupon->coupon_code = $request->coupon_code;
      $coupon->coupon_type = $request->coupon_type;
      $coupon->coupon_amount = $request->coupon_amount;
      $coupon->coupon_min_amount = $request->minimum_amount;
      $coupon->valid_till = $request->valid_till;
      $coupon->save();

      Session::flash('success', 'Coupon updated successfully!');
      return back();
    }

    public function delete(Request $request) {
      $coupon = Coupon::find($request->coupon_id);
      $coupon->delete();

      Session::flash('success', 'Coupon deleted successfully!');
      return back();
    }
}
