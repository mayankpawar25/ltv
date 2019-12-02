<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Option;
use App\ProductAttribute;
use Session;
use Illuminate\Support\Facades\Input;

class OptionController extends Controller
{
    public function index($id) {
      $data['pa'] = ProductAttribute::find($id);
      $data['options'] = Option::where('product_attribute_id', $id)->orderBy('id', 'DESC')->paginate(10);
      return view('admin.options.index', $data);
    }

    public function paginate(){

        $order       = Input::get('order');
        $columns     = Input::get('columns');
        $query_key   = Input::get('search');
        $search_key  = $query_key['value'];
        $product_attribute_id = Input::get('id');
        $status_id   = Input::get('status_id');
        $is_verified = Input::get('is_verified');
        $groups      = Input::get('groups');
        $q           = Option::query();
        $query       = Option::orderBy($columns[$order[0]['column']]['name'], $order[0]['dir']);

        // Filtering Data
        if($status_id!=''){
            $query->where('status', $status_id );
            $q->whereIn('status', $status_id );
        }

         if($product_attribute_id!=''){
            $query->where('product_attribute_id', $product_attribute_id );
            $q->where('product_attribute_id', $product_attribute_id );
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
                    $row->name,
                   ($row->status==0)?'<span class="badge badge-danger">Deactive</span>':'<span class="badge badge-success">Active</span>',
                   '<button type="button" class="btn btn-success btn-sm float-right" data-toggle="tooltip" title="Edit" > <span data-toggle="modal" data-target="#editModal'.$row->id.'"><i class="fas fa-pencil-alt"></i> </span></button>',
                    
                    
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

      $option = new Option;
      $option->product_attribute_id = $request->product_attribute_id;
      $option->name = $request->name;
      $option->status = $request->status;
      $option->save();

      Session::flash('success', 'New option added!');
      return redirect()->back();
    }

    public function update(Request $request) {
      $validatedRequest = $request->validate([
        'name' => 'required',
      ]);

      $option = Option::find($request->option_id);
      $option->name = $request->name;
      $option->status = $request->status;
      $option->save();

      Session::flash('success', 'Option updated successfully');
      return redirect()->back();
    }
}
