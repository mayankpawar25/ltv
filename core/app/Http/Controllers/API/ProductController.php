<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Hash;
use DB;
use App\Category,App\ProductAttribute,App\Subcategory,App\Product,App\Gateway,App\ProductReview,App\Favorit,App\PreviewImage,App\Cart;
class ProductController extends Controller
{
	public $successStatus = 200;

    /* Category List By Category Id*/
    public function categories($category_id=""){
    	$category = Category::where('status',1)->get();
    	foreach ($category as $key => $value) {
            $value->image = ($value->image!='')?asset('assets/user/img/category/'.$value->image):'';
    		$value->subcategories;
            foreach ($value->subcategories as $key => $value) {
                $value->image = ($value->image!='')?asset('assets/user/img/subcategory/'.$value->image):'';
            }
    	}
    	$data['categories'] = $category;
    	$data['msg'] = 'Category List';
    	$data['status'] = true;
    	return response()->json($data, $this-> successStatus);
    }
    /* Category List By Category Id*/

    /* SubCategoryId List By Category Id , SubCategoryId*/
    public function subcategories($category_id="",$subcategory_id=""){
    	if($category_id!=""){
    		$subcategories = Subcategory::where('category_id',$category_id)->where('status',1)->get();
            foreach ($subcategories as $key => $value) {
                $value->image = ($value->image!='')?asset('assets/user/img/subcategory/'.$value->image):'';
            }
    	}else{
    		$subcategories = Subcategory::get();
            foreach ($subcategories as $key => $value) {
                $value->image = ($value->image!='')?asset('assets/user/img/subcategory/'.$value->image):'';
            }
    	}
    	if(!$subcategories->isEmpty()){
    		$data['subcategories'] = $subcategories;
    		$data['msg'] = 'Subcategory List';
    		$data['status'] = true;
    		$status = $this-> successStatus;
    	}else{
			$data['subcategories'] = $subcategories;
    		$data['msg'] = 'No Subcategory available for this category';
    		$data['status'] = false;
    		$status = 401;
    	}
    	return response()->json($data, $status);
    }
    /* SubCategoryId List By Category Id , SubCategoryId*/

    /* Product List By Category Id , SubCategoryId , Product Id */
    public function products(Request $request,$category_id="",$subcategory_id="",$id=""){

        $fav_arr = [];
        if(!empty($request->user('api'))){
            $user = $request->user('api');
            $favorit = Favorit::where('user_id',$user->id)->get();
            // echo json_encode($favorit);
            if(!empty($favorit)){
                foreach ($favorit as $fav_key => $fav_value) {
                    $fav_arr[$fav_key] = $fav_value->product_id;
                }

            }
        }

        // dd($request->sort_by,$request->minprice,$request->maxprice,json_decode($request['attributes']));
        // exit;
        $product = Product::select('*');
        if($category_id!=""){
            $product->where('category_id', $category_id);
        }
        if($subcategory_id!=""){
            $product->where('subcategory_id', $subcategory_id);
        }

        if($request->sort_by){
            if ($request->sort_by == 'date_desc') {
                $product->orderBy('created_at', 'DESC');
            } elseif ($request->sort_by == 'date_asc') {
                $product->orderBy('created_at', 'ASC');
            } elseif ($request->sort_by == 'price_desc') {
                $product->orderBy('price', 'DESC');
            } elseif ($request->sort_by == 'price_asc') {
                $product->orderBy('price', 'ASC');
            } elseif ($request->sort_by == 'sales_desc') {
                $product->orderBy('sales', 'DESC');
            } elseif ($request->sort_by == 'rate_desc') {
                $product->orderBy('avg_rating', 'DESC');
            }
        }

        if($request->term){
            $product->where('title', 'like', '%'.$request->term.'%');
        }

        if($request->minprice!=''){
            $product->where('price', '>=', $request->minprice);
        }

        if($request->maxprice!=''){
            $product->where('price', '<=', $request->maxprice);
        }
        $productids = [];
        $reqattrs = $request->except('maxprice', 'minprice', 'sort_by', 'term', 'page', 'type');
        // dd($reqattrs);
        // $reqattrs = json_decode($reqattrs['attributes']);
        $ptr = Product::orderBy('id', 'DESC');
        if($subcategory_id!=""){
            $ptr->where('subcategory_id', $subcategory_id);
        }
        $attr_search_product = $ptr->get();
        foreach ($attr_search_product as $k => $attr_product) {
            $proattrs = json_decode($attr_product->attributes, true);
            $count = 0;
            if(!empty($proattrs)){
                foreach ($proattrs as $key => $proattr) {
                  if (!empty($reqattrs[$key])) {
                    if (!empty(array_intersect($reqattrs[$key], $proattrs[$key]))) {
                      $count++;
                    }
                  }
                }
            }
            if ($count == count($reqattrs)) {
              $productids[] = $attr_product->id;
            }
        }
        if(!empty($productids)){
            $product->whereIn('id', $productids);
        }

        if($id!=""){
            $product->where('id', $id);
        }
        $products = $product->get();

    	if(!$products->isEmpty()){
	    	foreach ($products as $key => $value) {
	    		foreach($value->previewimages as $images){
	    			$images->image = asset('assets/user/img/products/'.$images->image);
	    			$images->big_image = asset('assets/user/img/products/'.$images->big_image);
	    		}
	    		$value->attributes = json_decode($value->attributes);
                $value->favorite = 0;
                if(!empty($fav_arr)){
                    $value->favorite = in_array($value->id,$fav_arr)?1:0;
                }

	    	}
	    	$data['products'] = $products;
	    	$data['msg'] = 'Products List';
	    	$data['status'] = true;
			$status = $this-> successStatus;
    	}else{
    		$data['products'] = [];
	    	$data['msg'] = 'No Products Available';
	    	$data['status'] = false;
			$status = 401;
    	}

        /* Category List Filter */
        /*$categories = Category::where('status', 1)->get();
        foreach ($categories as $key => $category) {
            $category->subcategories;
        }*/
        /* Category List Filter */

        /* Attributes */
        $attributes = ProductAttribute::where('status', 1)->get();
        foreach ($attributes as $key => $attribute) {
            $attribute->options;
        }
        /* Attributes */
        $data['filter']['sort'] = Product::sorting();
        // $data['filter']['categories'] = $categories;
        $data['filter']['attributes'] = $attributes;
        $data['filter']['minprice'] = Product::min('price');
        $data['filter']['maxprice'] = Product::max('price');
    	return response()->json($data, $status);
    }
    /* Product List By Category Id , SubCategoryId , Product Id */

    /* Product Detail */
    public function productDetail(Request $request,$product_id=""){
        $fav_arr = [];
        if(!empty($request->user('api'))){
            $user = $request->user('api');
            $favorit = Favorit::where('user_id',$user->id)->get();
            // echo json_encode($favorit);
            if(!empty($favorit)){
                foreach ($favorit as $fav_key => $fav_value) {
                    $fav_arr[$fav_key] = $fav_value->product_id;
                }

            }
        }
        if($product_id!=""){
            $product = Product::find($product_id);
            $product->favorite = in_array($product_id,$fav_arr)?1:0;
            if(!empty($product)){
                $attributes = json_decode($product->attributes);
                $attr = [];
                $i = 0;
                foreach ($attributes as $key => $attribute) {
                    $attr[$i]['name'] = $key;
                    $attr[$i]['options'] = $attribute;
                    $i++;
                }
                $product->attributes = $attr;
                $product->description = strip_tags($product->description);
                foreach ($product->previewimages as $key => $value) {
                    $value->image = asset('assets/user/img/products/'.$value->image);
                    $value->big_image = asset('assets/user/img/products/'.$value->big_image);
                }

                $rproducts = Product::where('subcategory_id', $product->subcategory_id)->where('deleted', 0)->inRandomOrder()->limit(10)->get();
                foreach ($rproducts as $key => $rproduct) {
                    foreach ($rproduct->previewimages as $rkey => $rvalue) {
                        $rvalue->image = asset('assets/user/img/products/'.$rvalue->image);
                        $rvalue->big_image = asset('assets/user/img/products/'.$rvalue->big_image);
                    }
                    $rattributes = json_decode($rproduct->attributes);
                    $r_attr = [];
                    $i = 0;
                    if(!empty($rattributes)){
                        foreach ($rattributes as $rkey => $rattribute) {
                            $r_attr[$i]['name'] = $rkey;
                            $r_attr[$i]['options'] = $rattribute;
                            $i++;
                        }
                    }
                    $rproduct->attributes = $r_attr;
                    $rproduct->favorite = in_array($rproduct->id,$fav_arr)?1:0;
                }
                $data['product_detail'] = $product;
                $data['related_products'] = $rproducts;
                $data['msg'] = "Product Detail";
                $data['status'] = true;
                $status = $this-> successStatus;
            }else{
                $data['product_detail'] = [];
                $data['related_products'] = [];
                $data['msg'] = "Check Product Id";
                $data['status'] = false;
                $status = 401;
            }
        }else{
            $data['msg'] = "Please Check Product Id";
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status);
    }
    /* Product Detail */

    /* PayTm Credentials */
    public function paytmCredentials(){
        $resp = Gateway::find(105);
        $data = array(
                    'payment_gateway_name' => 'PayTm',
                    'merchant_id' => $resp->val1,
                    'merchant_key' => $resp->val2,
                    'website' => $resp->val3,
                    'industry_type' => $resp->val4,
                    'channel_id' => $resp->val5,
                    'transaction_url' => $resp->val6,
                    'status_url' => $resp->val7,
                    'status' => $resp->status,
                );
        echo json_encode($data);
    }
    /* PayTm Credentials */

    public function productSearch($product_search=""){
        if($product_search!=""){
            $products = Product::where('title', 'like', '%' . $product_search . '%')->get();
            if(!empty($products)){
                foreach ($products as $section) {
                    $products->attributes = json_decode($section->attributes);
                    $products->description = strip_tags($section->description);
                    foreach ($section->previewimages as $key => $value) {
                        $value->image = asset('assets/user/img/products/'.$value->image);
                        $value->big_image = asset('assets/user/img/products/'.$value->big_image);
                    }
                }
                $data['product_search_result'] = $products;
                $data['msg'] = "Search Result";
                $data['status'] = true;
                $status = $this-> successStatus;
            }else{
                $data['msg'] = "No Result Found";
                $data['status'] = false;
                $status = 401;
            }
       }else {
            $data['msg'] = "No Result Found";
            $data['status'] = false;
            $status = 401;
       }
        return response()->json($data, $status);
    }

    /*Add Review by Customer*/
    public function reviewsubmit(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'rating'    => 'required',
        ],['rating.required'=>'Rating is required']);

        if ($validator->fails()) {
            $validator->errors()->add('error', 'true'); 
            return response()->json($validator->errors(), 401);            
        }
          

        $productreview              = new ProductReview;
        $productreview->user_id     = Auth::user()->id;
        $productreview->product_id  = $request->product_id;
        $productreview->rating      = floatval($request->rating);
        $productreview->comment     = $request->comment;
        $save_review                       = $productreview->save();
        if(!empty($save_review)){
            $product             = Product::find($request->product_id);
            $product->avg_rating = ProductReview::where('product_id', $request->product_id)->avg('rating');
            $save_rating = $product->save();
            if(!empty($save_rating)){
                $data['msg'] = "Reviewed successfully";
                $data['status'] = true;
                $status = $this-> successStatus;
            }else{
                $data['msg'] = "Something went wrong.Please try again after some time";
                $data['status'] = false;
                $status = 401;
            }
        }else {
            $data['msg'] = "Something went wrong.Please try again after some time";
            $data['status'] = false;
            $status = 401;
        }
         return response()->json($data, $status);
    }

    /*All Customer Review*/
    public function reviewList(Request $request){
        if($request->product_id!=""){
            //$rating = ProductReview::where('product_id',$request->product_id)->get();
            $rating =  DB::table('product_reviews As t')
                          ->where('t.user_id',Auth::user()->id)
                          ->where('t.product_id',$request->product_id)
                          ->join('users', 't.user_id', '=', 'users.id')
                          ->select('t.id', 't.user_id','t.product_id','t.rating','t.comment','t.created_at','t.updated_at','users.name as user_name','users.first_name as first_name','users.last_name as last_name')
                          ->get();
           
            if(!empty($rating)){
                $data['rating'] = $rating;
                $data['msg'] = "Customer Rating";
                $data['status'] = true;
                $status = $this-> successStatus;
            }else{
                $data['msg'] = "No Rating Found";
                $data['status'] = false;
                $status = 401;
            }
       }else {
            $data['msg'] = "No Rating Found";
            $data['status'] = false;
            $status = 401;
       }
        return response()->json($data, $status);
    }

    /*Add Product In WishList*/
    public function favorit(Request $request) {
        if(!empty($request->product_id)){
            $count = Favorit::where('user_id', Auth::user()->id)->where('product_id', $request->product_id)->count();
          if ($count > 0) {
            Favorit::where('user_id', Auth::user()->id)->where('product_id', $request->product_id)->delete();
                $data['msg'] = "Product Remove successfully";
                $data['status'] = true;
                $status = $this-> successStatus;
          } else {
            $favorit = new Favorit;
            $favorit->user_id = Auth::user()->id;
            $favorit->product_id = $request->product_id;
            $save           = $favorit->save();
            if($save){
                $data['msg'] = "Product add successfully in wishlist";
                $data['status'] = true;
                $status = $this-> successStatus;
            }else {
                $data['msg'] = "Something went wrong.please try again after some time ";
                $data['status'] = false;
                $status = 401;
            }
            
          }

        }else {
             $data['msg'] = "place check you product id .Something went wrong.please try again after some time ";
             $data['status'] = false;
             $status = 401;
        }
       return response()->json($data, $status);
    }

    /*User WishList*/
    public function userWishlist2(Request $request) {
        $favorit = Favorit::where('user_id', Auth::user()->id)->get();
          if (!empty($favorit)) {
                $data['wishlist'] = $favorit;
                $data['msg'] = "User Wishlist";
                $data['status'] = true;
                $status = $this-> successStatus;
          }else {
             $data['msg'] = "No Product found in wishlist";
             $data['status'] = false;
             $status = 401;
        }
       return response()->json($data, $status);
    }

     public function userWishlist(Request $request) {
        $favorits = Favorit::where('user_id', Auth::user()->id)->get();
           foreach ($favorits as $k => $favorit) {
                $product = Product::select('*');
                $productids[] = $favorit->product_id;

               if(!empty($productids)){
                   
                    $product->whereIn('id', $productids);
                }
                $products = $product->get();
            }
           if(!$products->isEmpty()){
                foreach ($products as $key => $value) {
                    foreach($value->previewimages as $images){
                        $images->image = asset('assets/user/img/products/'.$images->image);
                        $images->big_image = asset('assets/user/img/products/'.$images->big_image);
                    }
                    $value->attributes = json_decode($value->attributes);
                }
            $data['wishlist'] = $products;
            $data['msg'] = 'User Wishlist';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['wishlist'] = [];
            $data['msg'] = 'No Product found in wishlist';
            $data['status'] = false;
            $status = 401;
        }

       return response()->json($data, $status);
    }

    /*Temporary Cart List*/
    public function cartSection(Request $request) {
        
        $sessionid = Auth::user()->id;
        // get details of the selected product
        $product = Product::find($request->productid);
        $preimg = PreviewImage::where('product_id', $product->id)->first();
        $product['preimg'] = $preimg->image;
       
        // if this product is already in the cart then just update the quantity...
        if (Cart::where('cart_id', $sessionid)->where('product_id', $product->id)->count() > 0) {
            $cart = Cart::where('cart_id', $sessionid)->where('product_id', $product->id)->first();
            $cart->quantity = $cart->quantity + 1;
            $cart->attributes = $request->attribute;
            $cart->save();
            return response()->json(['status'=>true,'msg'=>'Cart Update', 'productid'=>$product->id, 'quantity'=>$cart->quantity]);
        }


         // if a new product is added to cart
         $cart = new Cart;
         $cart->cart_id = $sessionid;
         $cart->product_id = $product->id;
         $cart->title = $product->title;
         $cart->price = $product->price;
         $cart->price = $product->current_price;
         $cart->quantity = $request->quantity;
         $cart->attributes = $request->attribute;
         $cart->save();

          $product['quantity'] = $request->quantity;
          return response()->json(['status'=>true,'msg'=>'product add in cart', 'product'=>$product, 'quantity'=>$product['quantity']]);
        }


    /*Update temporary Cart*/
     public function updateCart(Request $request) {
    
      $sessionid = Auth::user()->id;
      $cart = Cart::where('cart_id', $sessionid);

      if (empty($request->removedpros)) {
        $request->removedpros = [];
      }

      foreach ($request->removedpros as $removedpro) {
        $cart = Cart::find($removedpro);
        $product = Product::find($request->product_id);
        $product->quantity = $product->quantity + $cart->quantity;
        $product->save();
        $cart->delete();
      }


      $i = 0;
      $prices = [];
      $totalItems = 0;

      foreach (Cart::where('cart_id', $sessionid)->where('product_id',$request->product_id)->get() as $singlecart) {

        $product = Product::find($request->product_id);

        if ($request->qts < $singlecart->quantity) {
          $product->quantity = $product->quantity + ($singlecart->quantity - $request->qts);
          $product->save();
        } elseif ($request->qts > $singlecart->quantity) {
          $product->quantity = $product->quantity - ($request->qts - $singlecart->quantity);
          $product->save();
        }

        $singlecart->quantity = $request->qts;
        $singlecart->save();


        if (empty($singlecart->current_price)) {
          $prices[] = $singlecart->price * $singlecart->quantity;
        } else {
          $prices[] = $singlecart->current_price * $singlecart->quantity;
        }
        $i++;
      }

      //$total = getTotal($sessionid);
      //$subtotal = getSubTotal($sessionid);

      $totalItems = Cart::where('cart_id', $sessionid)->sum('quantity');
      $msg = "Cart Update successfully";
      return response()->json(['status' => true, 'totalItems'=>$totalItems,'msg'=>$msg]);
    }

    /*Latest Product*/
    public function latestProduct(Request $request){
        $fav_arr = [];
        $product = Product::where('deleted', 0)->orderBy('id', 'DESC')->limit(10)->get();
        $soldproduct = Product::where('deleted', 0)->orderBy('sales', 'DESC')->limit(10)->get();
        $specialproduct = Product::whereNotNull('current_price')->where('deleted', 0)->orderBy('id', 'DESC')->limit(10)->get();
        $topratedproduct = Product::where('deleted', 0)->orderBy('avg_rating', 'DESC')->limit(10)->get();
            if(!$product->isEmpty()){
                foreach ($product as $section) {
                    $product->attributes = json_decode($section->attributes);
                    $attributes = $product->attributes;
                    $r_attr = [];
                    $i = 0;
                    if(!empty($attributes)){
                        foreach ($attributes as $rkey => $attribute) {
                            $r_attr[$i]['name'] = $rkey;
                            $r_attr[$i]['options'] = $attribute;
                            $i++;
                        }
                    }
                    $section->attributes = $r_attr;
                    $section->favorite = in_array($section->id,$fav_arr)?1:0;

                    $section->description = strip_tags($section->description);
                    foreach ($section->previewimages as $key => $value) {
                        $value->image = asset('assets/user/img/products/'.$value->image);
                        $value->big_image = asset('assets/user/img/products/'.$value->big_image);
                    }
                }
                foreach ($soldproduct as $section) {
                    $soldproduct->attributes = json_decode($section->attributes);
                    $soldproduct->description = strip_tags($section->description);

                    $attributes = $soldproduct->attributes;
                    $r_attr = [];
                    $i = 0;
                    if(!empty($attributes)){
                        foreach ($attributes as $rkey => $attribute) {
                            $r_attr[$i]['name'] = $rkey;
                            $r_attr[$i]['options'] = $attribute;
                            $i++;
                        }
                    }
                    $section->attributes = $r_attr;
                    $section->favorite = in_array($section->id,$fav_arr)?1:0;

                    foreach ($section->previewimages as $key => $value) {
                        $value->image = asset('assets/user/img/products/'.$value->image);
                        $value->big_image = asset('assets/user/img/products/'.$value->big_image);
                    }
                }
                foreach ($specialproduct as $section) {
                    $specialproduct->attributes = json_decode($section->attributes);
                    $specialproduct->description = strip_tags($section->description);
                    $attributes = $specialproduct->attributes;
                    $r_attr = [];
                    $i = 0;
                    if(!empty($attributes)){
                        foreach ($attributes as $rkey => $attribute) {
                            $r_attr[$i]['name'] = $rkey;
                            $r_attr[$i]['options'] = $attribute;
                            $i++;
                        }
                    }
                    $section->attributes = $r_attr;
                    $section->favorite = in_array($section->id,$fav_arr)?1:0;

                    foreach ($section->previewimages as $key => $value) {
                        $value->image = asset('assets/user/img/products/'.$value->image);
                        $value->big_image = asset('assets/user/img/products/'.$value->big_image);
                    }
                }
                foreach ($topratedproduct as $section) {
                    $topratedproduct->attributes = json_decode($section->attributes);
                    $topratedproduct->description = strip_tags($section->description);
                    $attributes = $topratedproduct->attributes;
                    $r_attr = [];
                    $i = 0;
                    if(!empty($attributes)){
                        foreach ($attributes as $rkey => $attribute) {
                            $r_attr[$i]['name'] = $rkey;
                            $r_attr[$i]['options'] = $attribute;
                            $i++;
                        }
                    }
                    $section->attributes = $r_attr;
                    $section->favorite = in_array($section->id,$fav_arr)?1:0;
                    foreach ($section->previewimages as $key => $value) {
                        $value->image = asset('assets/user/img/products/'.$value->image);
                        $value->big_image = asset('assets/user/img/products/'.$value->big_image);
                    }
                }
                $data['latest_product'] = $product;
                $data['sold_product'] = $soldproduct;
                $data['special_product'] = $specialproduct;
                $data['top_rated_product'] = $topratedproduct;
                $data['msg'] = "product";
                $data['status'] = true;
                $status = $this-> successStatus;
            }else{
                $data['msg'] = "No Product Found";
                $data['status'] = false;
                $status = 401;
            }
        return response()->json($data, $status);
    }
    

}