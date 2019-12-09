<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Subcategory;
use App\Category;
use App\ProductAttribute;
use Session;
use Image;
use Illuminate\Support\Facades\Input;
class SubcategoryController extends Controller
{
    public function index($id) {
      $data['category'] = Category::find($id);
      $data['subcats'] = Subcategory::where('category_id', $id)->get();
      $data['pas'] = ProductAttribute::where('status', 1)->get();
      return view('admin.subcategory.index', $data);
    }

    public function paginate(){

          $order       = Input::get('order');
          $columns     = Input::get('columns');
          $query_key   = Input::get('search');
          $search_key  = $query_key['value'];
          $category_id = Input::get('id');
          $status_id   = Input::get('status_id');
          $is_verified = Input::get('is_verified');
          $groups      = Input::get('groups');
          $q           = Subcategory::query();
          $query       = Subcategory::orderBy($columns[$order[0]['column']]['name'], $order[0]['dir']);

          // Filtering Data
          if($status_id!=''){
              $query->where('status', $status_id );
              $q->whereIn('status', $status_id );
          }

           if($category_id!=''){
              $query->where('category_id', $category_id );
              $q->where('category_id', $category_id );
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
                     '<button type="button" class="btn btn-success btn-sm float-right" data-toggle="tooltip" title="Edit"> <span data-toggle="modal" data-target="#editModal'.$row->id.'"> <i class="fas fa-pencil-alt"></i> </span> </button>',
                      
                      
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

      $subcategory_image = '';
      if($request->hasFile('image')){
        $subcategory_image = rand().uniqid() . '.jpg';
        $location = 'assets/user/img/subcategory/' . $subcategory_image;
        $image_value = $request->file('image');

        $background = Image::canvas(150, 200);
        $resizedImage = Image::make($image_value)->resize(150, 200, function ($c) {
          $c->aspectRatio();
        });
        $background->insert($resizedImage, 'center'); // insert resized image centered into background
        $background->save($location); // save or do whatever you like
      }

      $attributes = json_encode($request->except('_token', 'name', 'category_id','image'));
      $subcat = new Subcategory;
      $subcat->category_id = $request->category_id;
      $subcat->name = $request->name;
      $subcat->attributes = $attributes;
      $subcat->image = $subcategory_image;
      $subcat->save();

      Session::flash('success', 'Subcategory stored successfully');
      return redirect()->back();
    }

    public function update(Request $request) {
      $validatedRequest = $request->validate([
        'name' => 'required',
      ]);
      // return $request;

      $subcategory_image = $request->old_image;
      if($request->hasFile('image')){
        $subcategory_image = rand().uniqid() . '.jpg';
        $location = 'assets/user/img/subcategory/' . $subcategory_image;
        $image_value = $request->file('image');

        $background = Image::canvas(150, 200);
        $resizedImage = Image::make($image_value)->resize(150, 200, function ($c) {
          $c->aspectRatio();
        });
        $background->insert($resizedImage, 'center'); // insert resized image centered into background
        $background->save($location); // save or do whatever you like
      }

      $attributes = json_encode($request->except('_token', 'name', 'status', 'statusId','old_image'));
      $subcat = Subcategory::find($request->statusId);
      $subcat->name = $request->name;
      $subcat->attributes = $attributes;
      $subcat->status = $request->status;
      $subcat->image = $subcategory_image;
      $subcat->save();

      Session::flash('success', 'Subcategory updated successfully');
      return redirect()->back();
    }
}
