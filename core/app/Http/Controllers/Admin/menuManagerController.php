<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneralSetting as GS;
use App\Menu;
use Session;
use Illuminate\Support\Facades\Input;

class menuManagerController extends Controller
{
    public function index() {
      $data['menus'] = Menu::latest()->get();
      return view('admin.menuManager.index', $data);
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
        $q           = Menu::query();
        $query       = Menu::orderBy($columns[$order[0]['column']]['name'], $order[0]['dir']);

        // Filtering Data
        if($status_id!=''){
            $query->where('status', $status_id );
            $q->whereIn('status', $status_id );
        }
        $number_of_records  = $q->get()->count();

        if($search_key)
        {
            $query->where(function ($k) use ($search_key) {
                $k->where('name', 'like', $search_key.'%')
                 ->orWhere('title', 'like', $search_key.'%');;
                

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
                  anchor_link($row->name,route('admin.menuManager.edit', $row->id)),
                  $row->title,
                  '<a href="'.route('admin.menuManager.edit',$row->id).'" title="Edit" id="'.$row->id.'" class="btn btn-sm btn-success"><i class="icon-pencil icon"></i></a><button type="button" class="btn btn-danger btn-sm delete_button" data-toggle="modal" data-target="#DelModal'.$row->id.'" data-id="2"><i class="icon-trash icon"></i></button>',
                    
                    
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

    public function add() {
      return view('admin.menuManager.add');
    }

    public function store(Request $request) {
      $slug = str_slug($request->name, '-');

      $validatedRequest = $request->validate([
        'name' => 'required',
        'title' => 'required',
        'body' => 'required'
      ]);

      $menu = new Menu;
      $menu->name = $request->name;
      $menu->title = $request->title;
      $menu->slug = $slug;
      $menu->body = $request->body;
      $menu->save();

      Session::flash('success', 'Menu added successfully!');
      return redirect()->back();
    }

    public function edit($menuID) {
      $data['menu'] = Menu::find($menuID);
      return view('admin.menuManager.edit', $data);
    }

    public function update(Request $request, $menuID) {
      $slug = str_slug($request->name, '-');

      $validatedRequest = $request->validate([
        'name' => 'required',
        'title' => 'required',
        'body' => 'required'
      ]);

      $menu = Menu::find($menuID);
      $menu->name = $request->name;
      $menu->title = $request->title;
      $menu->slug = $slug;
      $menu->body = $request->body;
      $menu->save();

      Session::flash('success', 'Menu updated successfully!');
      return redirect()->back();
    }

    public function delete($menuID) {
      $menu = Menu::find($menuID);
      $menu->delete();
      Session::flash('success', 'Menu deleted successfully!');
      return redirect()->back();
    }
}
