<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Storage;

use App\GeneralSetting as GS;
use App\Product;
use App\Order;
use App\Orderedproduct;
use App\PreviewImage;
use App\Category;
use App\Option;
use Carbon\Carbon;
use App\Subcategory;
use App\ProductAttribute;
use App\FlashInterval;
use App\Vendor;
use App\UserGroup,App\Shopkeeper;
use DB;
use Auth;
use Validator;
use Image;
use Artisan;
use Session;
class ProductController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(){
    $data['products'] = Product::orderBy('id', 'DESC')->where('deleted', 0)->paginate(20);  
    return view('admin.product.index',$data);
  }

  public function paginate(Request $request){
    $order       = Input::get('order');
    $columns     = Input::get('columns');
    $query_key   = Input::get('search');
    $status_id   = Input::get('quantity');
    $search_key  = $query_key['value'];
    $customer_id = Input::get('customer_id');
    $q           = Product::query();
    $query       = Product::orderBy($columns[$order[0]['column']]['name'], $order[0]['dir']);
    
    /*$column = $order[0]['column'];
    $dir = $order[0]['dir'];
    echo $columns[$order[0]['column']]['name'];*/

    // If the user has permission to view only the ones that are created by himself;
    /*if(!check_perm('products_view') && check_perm('products_view_own'))
    {
        $q->where(function($k){
            $k->where('salesman_id', auth()->user()->id);
        });
        $query->where(function($k){
            $k->where('salesman_id', auth()->user()->id);
        });                   
        
    }*/


    /*if($customer_id)
    {
        $q->whereHas('invoice', function ($q) use ($customer_id) {
            $q->where('invoices.customer_id', '=', $customer_id);
        });

        $query->whereHas('invoice', function ($q) use ($customer_id) {
            $q->where('invoices.customer_id', '=', $customer_id);
        });

    }*/

     if($status_id!=''){
            $query->where('status', $status_id );
            $q->whereIn('status', $status_id );
        }

    $number_of_records  = $q->get()->count();

    if($search_key)
    {
        $query->where(function ($k) use ($search_key) {

            $k->where('title', 'like', $search_key.'%')
            ->orWhere('price', 'like', $search_key.'%')
            ->orWhere('quantity', 'like', $search_key.'%')
            ->orWhere('product_code', 'like', $search_key.'%')
            ->orWhere('sales', 'like', $search_key.'%');
            /*
            ->orWhere('email', 'like', $search_key.'%')
            ->orWhere('mobile', 'like', $search_key.'%')
            ->orwhereHas('usergroup',function ($q) use ($search_key){

                $q->leftJoin('shopkeepers', 'shopkeepers.usergroup_id', '=', 'user_groups.id')
                    ->where('user_groups.name', 'like', $search_key.'%');
            });*/
        });
    }

    $recordsFiltered = $query->get()->count();
    if(Input::get('length') > 0){
      $query->skip(Input::get('start'))->take(Input::get('length'));
    }
    $data = $query->get();
    //
    $rec = [];
    $gs = GS::first();
    if (count($data) > 0)
    {   

        foreach ($data as $key => $row)
        {   
            $totalearning = Orderedproduct::where('shipping_status', 2)->where('refunded', '<>', 1)->where('product_id', $row->id)->sum('product_total');
            $image = 'no-image.png';
            if(!empty($row->previewimages) && isset($row->previewimages[0])){
              $image = $row->previewimages[0]->image;
            }
            $image_path = asset('assets/user/img/products/'.$image);
            $rec[] = array(

                a_links(anchor_link("<img src='".$image_path."' width='50px'>",route('admin.product.edit',$row->id)), [
                ]),
                $row->title,
                $row->product_code,
                $row->price,
                $row->quantity,
                ($row->quantity > 1) ?'<span class="badge badge-success">InStock</span>':'<span class="badge badge-danger">Out of Stock</span>',
                $gs->base_curr_symbol.' '.$totalearning,
                $row->sales,
                anchor_link('<button class="btn btn-sm btn-success pull-right"><span class="icon-pencil icons" data-toggle="tooltip" title="Edit"></span></button>',route('admin.product.edit', $row->id),'','shopkeepers_edit').' '.
                anchor_link('<button class="btn btn-sm btn-danger pull-right"><span class="icon-trash icons" data-toggle="tooltip" title="Delete"></span></button>',route('admin.product.delete', $row->id),'','shopkeepers_delete'),
            );
        }
    }
    /*class="btn btn-warning btn-sm"  data-toggle="tooltip" title="View Ledger"*/

    // echo json_encode($number_of_records);
    // exit;

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
  public function create(){
    $data['usergroups'] = UserGroup::where('status',1)->get();
    $data['flashints'] = FlashInterval::all();
    $data['cats'] = Category::where('status', 1)->get();
    $data['subcats'] = Subcategory::where('status', 1)->get();
    return view('admin.product.create',$data);
  }



  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request){

    // dd($request->all());

    $imgs = $request->file('images');
    $allowedExts = array('jpg', 'png', 'jpeg');
    $slug = str_slug($request->title, '-');

    $vendor = Vendor::find(1);

    /*if ($vendor->products == 0) {
      return "no_product";
    }*/

    $rules = [
      'images' => [
        'required',
        function($attribute, $value, $fail) use ($imgs, $allowedExts) {
            foreach($imgs as $img) {
                $ext = $img->getClientOriginalExtension();
                if(!in_array($ext, $allowedExts)) {
                    return $fail("Only png, jpg, jpeg images are allowed");
                }
            }
            if (count($imgs) > 5) {
              return $fail("Maximum 5 images can be uploaded");
            }
        },
      ],
      'title' => [
        function ($attribute, $value, $fail) use ($request) {
            if (!$request->filled('title')) {
                $fail('Title is required.');
            }
        }
      ],
      'price' => [
        function ($attribute, $value, $fail) use ($request) {
          if (!$request->filled('price')) {
              $fail('Price is required.');
          }
          if ($request->filled('price')) {
            if (!is_numeric($request->price)) {
              $fail('Price must be a number.');
            }
          }
        },
      ],
      'quantity' => [
        function ($attribute, $value, $fail) use ($request) {
          if (!$request->filled('quantity')) {
              $fail('Quantity is required.');
          }
          if ($request->filled('quantity')) {
            if (!is_numeric($request->quantity)) {
              $fail('Quantity must be a number.');
            }
          }
        },
      ],
      'cat_helper' => [
        function ($attribute, $value, $fail) use ($request) {
            if (!$request->filled('category')) {
                $fail('Category is required.');
            }
        }
      ],
      'subcat_helper' => [
        function ($attribute, $value, $fail) use ($request) {
            if (!$request->filled('subcategory')) {
                $fail('Subcategory is required.');
            }
        }
      ],
      'description' => [
        'required',
      ],
      'offer_amount' => [
        'required_if:offer,1',
      ],
      'flash_amount' => [
        'required_if:flash_sale,1',
      ],
      'flash_date' => [
        'required_if:flash_sale,1',
      ]
    ];

    $messages = [
      'offer_amount.required_if' => 'Offer amount field is required',
      'flash_amount.required_if' => 'Flash amount field is required',
      'flash_date.required_if' => 'Flash date field is required',
    ];

    if ($request->has('subcategory')) {
      $subcat = Subcategory::find($request->subcategory);
      $attrjson = json_decode($subcat->attributes, true);

      if (!array_key_exists('attributes', $attrjson)) {
        $errproattr = '';
      }
      // if subcategory contains no proattr
      else {
        $attrarrs = $attrjson['attributes'];
        $errproattr = [];
        foreach ($attrarrs as $key => $attrarr) {
          $proattr = ProductAttribute::find($attrarr);
          if (!$request->has("$proattr->attrname")) {
            $errproattr["$proattr->attrname"] = "$proattr->name is required";
          }
        }
        // if proattr has no error
        if (empty($errproattr)) {
          $errproattr = '';
        }
      }
    }
    // if there is no subcat given
    else {
      $errproattr = '';
    }


    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails() || !empty($errproattr)) {
      $errmsgs = $validator->getMessageBag()->add('error', 'true');
      if (!empty($errproattr)) {
        $errmsgs->add('proattr', $errproattr);
      }
      return response()->json($validator->errors());
    }
    $in = $request->only('title','price','description','quantity', 'offer_amount', 'flash_amount', 'flash_date', 'flash_interval');
    $in['vendor_id'] = 1;
    $in['slug'] = $slug;
    $in['category_id'] = $request->category;
    $in['subcategory_id'] = $request->subcategory;
    $in['flash_request_date'] = new \Carbon\Carbon(Carbon::now());
    $in['flash_sale'] = $request->has('flash_sale') ? 1 : 0;
    $in['flash_type'] = $request->has('flash_type') ? 1 : 0;
    if ($request->has('offer')) {
      $in['offer_type'] = $request->has('offer_type') ? 'percent' : 'fixed';
      // if offer type percentage
      if ($in['offer_type'] == 'percent') {
        // price - $request->offer_amount*price/100
        $in['current_price'] = $request->price - (($request->offer_amount*$request->price)/100);
      }
      // if offer type fixed
      if ($in['offer_type'] == 'fixed') {
        // price - $request->offer_amount
        $in['current_price'] = $request->price - $request->offer_amount;
      }
    } else {
      $in['current_price'] = NULL;
    }
    if (empty($in['current_price'])) {
      $in['search_price'] = $in['price'];
    } else {
      $in['search_price'] = $in['current_price'];
    }
    if ($request->filled('product_code')) {
      $in['product_code'] = $request->product_code;
    } else {
      $in['product_code'] = product_code(8);
    }
    $in['attributes'] = json_encode($request->except('_token','cat_helper','subcat_helper','images','title','price','category','subcategory','product_code','description', 'quantity', 'offer', 'offer_type', 'offer_amount', 'flash_sale', 'flash_type', 'flash_amount', 'flash_date', 'flash_interval','usergroups'));

    $in['usergroup_prices'] = json_encode($request->usergroups);

    $product = Product::create($in);

    foreach($imgs as $img) {
      $pi = new PreviewImage;
      $filename = uniqid() . '.jpg';
      $filename1 = uniqid() . '.jpg';
      $location = 'assets/user/img/products/' . $filename;
      $location1 = 'assets/user/img/products/' . $filename1;

      $background = Image::canvas(570, 570);
      $resizedImage = Image::make($img)->resize(570, 570, function ($c) {
          $c->aspectRatio();
      });
      // insert resized image centered into background
      $background->insert($resizedImage, 'center');
      // save or do whatever you like
      $background->save($location);


      $background1 = Image::canvas(1140, 1140);
      $resizedImage1 = Image::make($img)->resize(1140, 1140, function ($c) {
          $c->aspectRatio();
      });
      // insert resized image centered into background
      $background1->insert($resizedImage1, 'center');
      // save or do whatever you like
      $background1->save($location1);

      $pi = new PreviewImage;
      $pi->product_id = $product->id;
      $pi->image = $filename;
      $pi->big_image = $filename1;
      $pi->save();
    }

   /* $vendor->products = $vendor->products - 1;
    if ($vendor->products == 0) {
      $vendor->expired_date = NULL;
    }
    $vendor->save();*/

    Session::flash('success', 'Product uploaded successfully!');
    return "success";
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id){
    $data['flashints'] = FlashInterval::all();
    $data['product'] = Product::find($id);
    // if ($data['product']->vendor_id != Auth::guard('admin')->user()->id) {
    //   return back();
    // }

    $data['usergroups'] = UserGroup::where('status',1)->get();
    
    $data['checkedattrs'] = json_decode($data['product']->attributes, true);
    $data['attrs'] = json_decode(Subcategory::find($data['product']->subcategory_id)->attributes, true);
    $data['cats'] = Category::where('status', 1)->get();
    $data['subcats'] = Subcategory::where('category_id', $data['product']->category_id)->get();
    // dd($data);

    return view('admin.product.edit', $data);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request){
    if ($request->hasFile('images')) {
      $imgs = $request->file('images');
    } else {
      $imgs = [];
    }
    if (!$request->has('imgsdb')) {
      $request->imgsdb = [];
    }
    $allowedExts = array('jpg', 'png', 'jpeg');
    $slug = str_slug($request->title, '-');

    $rules = [
      'imgs_helper' => [
        function($attribute, $value, $fail) use ($imgs, $allowedExts, $request) {
            if (count($request->imgsdb) == 0 && count($imgs) == 0) {
              return $fail("Preview image is required");
            }
            foreach($imgs as $img) {
              $ext = $img->getClientOriginalExtension();
              if(!in_array($ext, $allowedExts)) {
                  return $fail("Only png, jpg, jpeg images are allowed");
              }
            }
            if ((count($imgs)+count($request->imgsdb)) > 5) {
              return $fail("Maximum 5 images can be uploaded");
            }
        },
      ],
      'title' => [
        function ($attribute, $value, $fail) use ($request) {
            if (!$request->filled('title')) {
                $fail('Title is required.');
            }
        }
      ],
      'price' => [
        function ($attribute, $value, $fail) use ($request) {
          if (!$request->filled('price')) {
              $fail('Price is required.');
          }
          if ($request->filled('price')) {
            if (!is_numeric($request->price)) {
              $fail('Price must be a number.');
            }
          }
        },
      ],
      'quantity' => [
        function ($attribute, $value, $fail) use ($request) {
          if (!$request->filled('quantity')) {
              $fail('Quantity is required.');
          }
          if ($request->filled('quantity')) {
            if (!is_numeric($request->quantity)) {
              $fail('Quantity must be a number.');
            }
          }
        },
      ],
      'cat_helper' => [
        function ($attribute, $value, $fail) use ($request) {
            if (!$request->filled('category')) {
                $fail('Category is required.');
            }
        }
      ],
      'subcat_helper' => [
        function ($attribute, $value, $fail) use ($request) {
            if (!$request->filled('subcategory')) {
                $fail('Subcategory is required.');
            }
        }
      ],
      'description' => [
        'required',
      ],

    ];

    if ($request->has('subcategory')) {
      $subcat = Subcategory::find($request->subcategory);
      $attrjson = json_decode($subcat->attributes, true);

      if (!array_key_exists('attributes', $attrjson)) {
        $errproattr = '';
      }
      // if subcategory contains no proattr
      else {
        $attrarrs = $attrjson['attributes'];
        $errproattr = [];
        foreach ($attrarrs as $key => $attrarr) {
          $proattr = ProductAttribute::find($attrarr);
          if (!$request->has("$proattr->attrname")) {
            $errproattr["$proattr->attrname"] = "$proattr->name is required";
          }
        }
        // if proattr has no error
        if (empty($errproattr)) {
          $errproattr = '';
        }
      }
    }
    // if there is no subcat given
    else {
      $errproattr = '';
    }


    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails() || !empty($errproattr)) {
      $errmsgs = $validator->getMessageBag()->add('error', 'true');
      if (!empty($errproattr)) {
        $errmsgs->add('proattr', $errproattr);
      }
      return response()->json($validator->errors());
    }

    $in = $request->only('title', 'price', 'description','quantity', 'offer_amount', 'flash_amount', 'flash_date', 'flash_interval');
    $in['vendor_id'] = Auth::guard('admin')->user()->id;
    $in['category_id'] = $request->category;
    $in['subcategory_id'] = $request->subcategory;
    $in['slug'] = $slug;
    $in['flash_request_date'] = new \Carbon\Carbon(Carbon::now());
    $in['flash_sale'] = $request->has('flash_sale') ? 1 : 0;
    $in['flash_type'] = $request->has('flash_type') ? 1 : 0;
    if ($request->has('offer')) {
      // if offer type percentage
      if ($request->has('offer_type')) {
        $in['offer_type'] = 'percent';
        $in['current_price'] = $request->price - (($request->offer_amount*$request->price)/100);
      } else {
        $in['offer_type'] = 'fixed';
        $in['current_price'] = $request->price - $request->offer_amount;
      }
    } else {
      $in['current_price'] = NULL;
      $in['offer_type'] = NULL;
      $in['offer_amount'] = NULL;
    }
    if (empty($in['current_price'])) {
      $in['search_price'] = $in['price'];
    } else {
      $in['search_price'] = $in['current_price'];
    }
    if ($request->filled('product_code')) {
      $in['product_code'] = $request->product_code;
    } else {
      $in['product_code'] = product_code(8);
    }

    $in['attributes'] = json_encode($request->except('_token','cat_helper','subcat_helper','images','imgsdb','title','price','category','subcategory','product_code','description','quantity','imgs_helper','product_id', 'offer', 'offer_type', 'offer_amount', 'flash_sale', 'flash_type', 'flash_amount', 'flash_date', 'flash_interval','usergroups'));

    $in['usergroup_prices'] = json_encode($request->usergroups);

    $product = Product::find($request->product_id);
    $product->fill($in)->save();

    // bring all the product images of that product
    $productimgs = PreviewImage::where('product_id', $product->id)->get();

    // then check whether a filename is missing in imgsdb if it is missing remove it from database and unlink it
    foreach($productimgs as $productimg) {
      if(!in_array($productimg->image, $request->imgsdb)) {
          @unlink('assets/user/img/products/'.$productimg->image);
          @unlink('assets/user/img/products/'.$productimg->big_image);
          $productimg->delete();
      }
    }
    foreach($imgs as $img) {
      $pi = new PreviewImage;
      $filename = uniqid() . '.jpg';
      $filename1 = uniqid() . '.jpg';
      $location = 'assets/user/img/products/' . $filename;
      $location1 = 'assets/user/img/products/' . $filename1;

      $background = Image::canvas(570, 570);
      $resizedImage = Image::make($img)->resize(570, 570, function ($c) {
          $c->aspectRatio();
      });
      // insert resized image centered into background
      $background->insert($resizedImage, 'center');
      // save or do whatever you like
      $background->save($location);

      $background1 = Image::canvas(1140, 1140);
      $resizedImage1 = Image::make($img)->resize(1140, 1140, function ($c) {
          $c->aspectRatio();
      });
      // insert resized image centered into background
      $background1->insert($resizedImage1, 'center');
      // save or do whatever you like
      $background1->save($location1);

      $pi = new PreviewImage;
      $pi->product_id = $product->id;
      $pi->image = $filename;
      $pi->big_image = $filename1;
      $pi->save();
    }

    return "success";
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id){
    $productimgs = PreviewImage::where('product_id', $id)->get();
    // then check whether a filename is missing in imgsdb if it is missing remove it from database and unlink it
    foreach($productimgs as $productimg) {
      @unlink('assets/user/img/products/'.$productimg->image);
      @unlink('assets/user/img/products/'.$productimg->big_image);
      $productimg->delete();
    }
    $product = Product::find($id);
    $product->delete();
    return redirect()->back()->with('success','Product Deleted');
  }

  public function import_page(Request $request){
        $data['group_id_list'] = [];
        $data = Shopkeeper::dropdown(['assigned_to_list']);
        return view('admin.product.import', compact('data'))->with('rec', "");
    }

  public function download_sample_product_import_file(Request $request){
      $filename = 'sample_dealer_product_file';
      $spreadsheet = new Spreadsheet();
      $Excel_writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
      $spreadsheet->setActiveSheetIndex(0);
      $activeSheet = $spreadsheet->getActiveSheet();
      $columns = Product::column_sequence_for_import();
      foreach ($columns as $key=>$name){
          $activeSheet->setCellValue($key.'1' , str_replace("_", " ", ucfirst($name) ))->getStyle($key.'1')->getFont()->setBold(true);
      }
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); /*-- $filename is  Xlsx filename ---*/
      header('Cache-Control: max-age=0');
      $Excel_writer->save('php://output');
  }

  public function import(Request $request){
      $validator = Validator::make($request->all(), [        
          'file'        => 'required|max:1000|mimes:csv,xlsx',
          'status'      => 'required',
      ]);
      if ($validator->fails()) {
          return  redirect()->back()
              ->withErrors($validator)
              ->withInput();
      }
      $column_sequence_list = Product::column_sequence_for_import();
      // Upload the file to a temporary directory. We will remove the file later usig cron.
      $file = Storage::putFileAs(TEMPORARY_FOLDER_IN_STORAGE, $request->file('file'), time().".".$request->file('file')->extension() );
      $path = storage_path('app/'.$file);
      $extension      = $request->file('file')->getClientOriginalExtension();
      $reader         = ($extension == 'csv') ? new Csv() : new Xlsx();
      // Load the file with phpspreadsheet reader
      $spreadsheet    = $reader->load($path);
      // Get the first active work sheet
      $worksheet      = $spreadsheet->getActiveSheet();
      // Get the highest column from the column sequeunce array. It will return a letter like: S
      $highest_column = max(array_keys($column_sequence_list));
      // Get the next letter after the highest letter of the sequence
      $next_column_after_highest = ++$highest_column; 
      if (strlen($next_column_after_highest) > 1){
          // if you go beyond z or Z reset to a or A
          $next_column_after_highest = $next_column_after_highest[0];
      }
      // Check if the number of columns in the file match with requirement
      if(strtolower($worksheet->getHighestColumn()) < $highest_column){
          session()->flash('validation_errors', [__('form.number_of_columns_do_no_match')]);
          session()->flash('message', __('form.import_was_not_successfull'));
          return  redirect()->back();
      }
      if(isset($worksheet) && $worksheet){
          $errors = [];
          foreach ($worksheet->getRowIterator() as $indexOfRow=>$row){
             if($indexOfRow > 1){             
                  $cellIterator = $row->getCellIterator();
                  $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,                   
                  $cells = [];
                  // Get all the columns of the row into $cell array
                  foreach ($cellIterator as $column_key => $cell) 
                  {
                      if(isset($column_sequence_list[$column_key]))
                      {
                          $cells[$column_sequence_list[$column_key]] = $cell->getValue();
                      }
                  }
                  if(isset($cells['first_name']) && !$cells['first_name'])
                  {
                      continue;
                  }       
                  $error = $this->validate_product_data($cells);
                  if($error)
                  {                                
                      $errors[$indexOfRow] = $error;
                      $col = $next_column_after_highest.$indexOfRow;         
                      $this->write_error_messages_in_spreadsheet($extension, $spreadsheet, $col , implode(",", $error), $path);
                  }
                  else
                  {
                      DB::beginTransaction();
                      $success = false;
                      try {



                          if(isset($cells['category']) && $cells['category']!=''){
                              $category = Category::firstOrCreate(['name' => $cells['category'] ]);
                              $cells['category_id']   = $category->id;
                              unset($cells['category']);
                          }

                          if(isset($cells['subcategory']) && $cells['subcategory']!=''){
                              $subcategory = Subcategory::firstOrCreate(['name' => $cells['subcategory'] ]);
                              $cells['subcategory_id']   = $subcategory->id;
                              unset($cells['subcategory']);
                          }
                          // Create the Product


                          $customer = Product::create($cells);
                          
                          // dd($cells,$customer);
                          // dd($cells,$customer);

                          // disable activity logging
                          // $customer->disableLogging();
                          // Create Contact Person
                          // Remove the values of the Row
                          $this->clear_all_columns_of_a_row_in_spreadsheet($spreadsheet, $path, $column_sequence_list, $indexOfRow);
                          DB::commit();
                      }
                      catch (\Exception  $e)
                      {   
                          dd($e);
                          DB::rollback();
                          $col = $next_column_after_highest.$indexOfRow;         
                          $this->write_error_messages_in_spreadsheet($extension, $spreadsheet, $col , __('form.system_error') , $path);
                      }
                  }
             }
          }
          if(count($errors) > 0){
              $download_link = gen_url_for_attachment_download($file);
              $message = sprintf(__('form.import_download_file_message'), anchor_link(__('form.file'), $download_link));
              dd($message);

              session()->flash('download_file_to_see_unimported_rows', $message);   
              return redirect()->route('admin.product.import_page');
          }else{
              // dd(__('form.success_add'));
              session()->flash('success', __('form.success_add'));
              return redirect()->route('admin.product.import_page');
          }
      }else{
          // dd(__('form.invalid_file_provided'));
          session()->flash('message', __('form.invalid_file_provided'));
          return redirect()->route('admin.product.import_page');
      }
  }

  private function validate_product_data($records){
      $validator = Validator::make($records, [
          'title'        => 'required',
          'description'  => 'required',
          'price'        => 'required',
          'quantity'     => 'required',
          'category'     => 'required',
          'subcategory'  => 'required',
      ]);

      if ($validator->fails()) 
      {
          return $validator->errors()->all();
      }
  }

  private function get_spreadsheet_writer($file_extension, $spread_sheet){
      if($file_extension == 'csv')
      {
          return new \PhpOffice\PhpSpreadsheet\Writer\Csv($spread_sheet);
      }
      else
      {
          return new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spread_sheet);
      }
  }

  private function write_error_messages_in_spreadsheet($extension, $spreadsheet, $column, $message, $path){
      $spreadsheet->getActiveSheet()->setCellValue($column , $message );
      $spreadsheet->getActiveSheet()->getStyle($column)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
      $writer = $this->get_spreadsheet_writer($extension, $spreadsheet);
      $writer->save($path); 
  }

  private function clear_all_columns_of_a_row_in_spreadsheet($spreadsheet, $path, $column_sequence_list, $row_number){
      foreach ($column_sequence_list as $key=>$value){
          $spreadsheet->getActiveSheet()->setCellValue($key.$row_number , NULL);
      } 
      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      $writer->save($path);
  }

}
