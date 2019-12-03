<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use Session;
use Image;
use Illuminate\Support\Facades\Input;
class CategoryController extends Controller
{
    public function index() {
      $data['cats'] = Category::latest()->paginate(10);
      return view('admin.category.index', $data);
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
        $q           = Category::query();
        $query       = Category::orderBy($columns[$order[0]['column']]['name'], $order[0]['dir']);

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
                    anchor_link($row->name,route('admin.subcategory.index', $row->id)),
                   ($row->status==0)?'<span class="badge badge-danger">Deactive</span>':'<span class="badge badge-success">Active</span>',
                   '<a href="'.route('admin.subcategory.index', $row->id).'" data-toggle="tooltip" title="Add Sub Category" class="btn btn-info btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                 <button type="button" class="btn btn-success btn-sm float-right" data-toggle="tooltip" title="Edit"><span data-toggle="modal" data-target="#editModal'.$row->id.'"><i class="fas fa-pencil-alt"></i></span></button>',
                    
                    
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

      $category_image = '';
      if($request->hasFile('image')){
        $category_image = rand().uniqid() . '.jpg';
        $location = 'assets/user/img/category/' . $category_image;
        $image_value = $request->file('image');

        $background = Image::canvas(150, 200);
        $resizedImage = Image::make($image_value)->resize(150, 200, function ($c) {
          $c->aspectRatio();
        });
        $background->insert($resizedImage, 'center'); // insert resized image centered into background
        $background->save($location); // save or do whatever you like
      }


      $cateogry = new Category;
      $cateogry->name = $request->name;
      $cateogry->image = $category_image;
      $cateogry->save();

      Session::flash('success', 'Category added successfully');
      return redirect()->back();
    }

    public function update(Request $request) {
      $validatedRequest = $request->validate([
        'name' => 'required',
      ]);

      $category_image = $request->old_image;
      if($request->hasFile('image')){
        $category_image = rand().uniqid() . '.jpg';
        $location = 'assets/user/img/category/' . $category_image;
        $image_value = $request->file('image');

        $background = Image::canvas(150, 200);
        $resizedImage = Image::make($image_value)->resize(150, 200, function ($c) {
          $c->aspectRatio();
        });
        $background->insert($resizedImage, 'center'); // insert resized image centered into background
        $background->save($location); // save or do whatever you like
      }

      $cat = Category::find($request->statusId);
      $cat->name = $request->name;
      $cat->image = $category_image;
      $cat->status = $request->status;
      $cat->save();

      Session::flash('success', 'Category updated successfully');
      return redirect()->back();
    }

}
