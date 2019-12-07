<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductAttribute;
use App\Option;
use Session;
use Illuminate\Support\Facades\Input;

class ProductattrController extends Controller
{
    public function index() {
      $data['pas'] = ProductAttribute::orderBy('id', 'desc')->paginate(10);
      return view('admin.product_attribute.index', $data);
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
        $q           = ProductAttribute::query();
        $query       = ProductAttribute::orderBy($columns[$order[0]['column']]['name'], $order[0]['dir']);

        // Filtering Data
        if($status_id!=''){
            $query->where('status', $status_id );
            $q->whereIn('status', $status_id );
        }
        $number_of_records  = $q->get()->count();

        if($search_key)
        {
            $query->where(function ($k) use ($search_key) {
                $k->where('name', 'like', $search_key.'%');
                

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
                    anchor_link($row->name,route('admin.options.index', $row->id)),
                   ($row->status==0)?'<span class="badge badge-danger">Deactive</span>':'<span class="badge badge-success">Active</span>',
                   ' <a class="btn btn-primary btn-sm" href="'.route('admin.options.index', $row->id).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>',
                   ' <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Add Option" > <span data-toggle="modal" data-target="#addSub'.$row->id.'"><i class="fa fa-plus" aria-hidden="true"></i></span></button>
                                 <button type="button" class="btn btn-success btn-sm float-right" data-toggle="tooltip" title="Edit" > <span data-toggle="modal" data-target="#editModal'.$row->id.'"><i class="icon-pencil icon"></i> </span></button>',
                    
                    
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
      $validatedRequest = $request->validate([
        'name' => 'required',
      ]);

      $pa = new ProductAttribute;
      $pa->name = $request->name;
      $pa->attrname = str_slug($request->name, '_');
      $pa->status = $request->status;
      $pa->save();

      Session::flash('success', 'New product attribute added!');
      return redirect()->back();
    }


    public function update(Request $request) {
      $validatedRequest = $request->validate([
        'name' => 'required',
      ]);

      $pa = ProductAttribute::find($request->paId);
      $pa->name = $request->name;
      $pa->attrname = str_slug($request->name, '_');
      $pa->status = $request->status;
      $pa->save();

      Session::flash('success', 'Product attribute updated successfully');
      return redirect()->back();
    }
}
