<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use Session;
use Image;
class CategoryController extends Controller
{
    public function index() {
      $data['cats'] = Category::latest()->paginate(10);
      return view('admin.category.index', $data);
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
