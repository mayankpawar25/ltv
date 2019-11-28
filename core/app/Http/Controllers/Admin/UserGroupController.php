<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserGroup;
use App\Category;

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
