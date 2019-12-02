<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserGroup;
use App\Category;
use Illuminate\Support\Facades\Input;

class UserGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
      $data['cats'] = UserGroup::latest()->paginate(10);
      return view('admin.usergroup.index', $data);
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
        $q           = UserGroup::query();
        $query       = UserGroup::orderBy($columns[$order[0]['column']]['name'], $order[0]['dir']);

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
                ->orWhere('percentage', 'like', $search_key.'%');

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
                    $row->percentage,
                   ($row->status==0)?'<span class="badge badge-danger">Deactive</span>':'<span class="badge badge-success">Active</span>',
                   '<button type="button" class="btn btn-success btn-sm float-right"><span data-toggle="modal" data-target="#editModal'.$row->id.'" ><i class="icon icon-pencil"></i></span></button>'
                    
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $validatedRequest = $request->validate([
            'name' => 'required',
            'percentage' => 'required',
        ]);

        $usergroup = new UserGroup;
        $usergroup->name = $request->name;
        $usergroup->slug = strtolower(str_replace(' ', '_', $request->name));
        $usergroup->percentage = $request->percentage;
        $usergroup->status = $request->status;
        $usergroup->save();

        return redirect()->back()->with('success', 'User Group added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserGroup  $userGroup
     * @return \Illuminate\Http\Response
     */
    public function show(UserGroup $userGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserGroup  $userGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(UserGroup $userGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserGroup  $userGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserGroup $userGroup){
        $validatedRequest = $request->validate([
            'name' => 'required',
          ]);

        $usergroup = UserGroup::find($request->statusId);
        $usergroup->name = $request->name;
        $usergroup->slug = strtolower(str_replace(' ', '_', $request->name));
        $usergroup->percentage = $request->percentage;
        $usergroup->status = $request->status;
        $usergroup->save();
        return redirect()->back()->with('success', 'User Group updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserGroup  $userGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserGroup $userGroup)
    {
        //
    }
}
