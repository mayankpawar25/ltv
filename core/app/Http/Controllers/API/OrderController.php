<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User,App\Shopkeeper,App\ShopkeeperNotification;
use App\Order;
use App\Country;
use App\City;
use App\State;
use App\Zipcode;
use App\Gateway;
use App\Coupon;
use App\Product;
use App\UserGroup;
use App\Orderedproduct;
use App\Orderpayment;
use App\Refund;
use App\GeneralSetting as GS;
use Carbon\Carbon;
use Auth;
use App\Cart,App\Favorit;
use App\PlacePayment;
use Session;
use Validator;

class OrderController extends Controller
{	
	public $successStatus = 200;

	public function placeOrder(Request $request){

		$validator = Validator::make($request->all(),[
        'product_detail' => 'required',
        // 'sub_total' => 'required',
        // 'total' => 'required',
        'payment_method' => 'required',
        'payment_method' => 'required',
      	],['product_detail.required'=>'Add Product in Cart']);

	      if ($validator->fails()) { 
	        return response()->json($validator->errors(), 401);                
	      }

		$gs = GS::first();
		// store in order table
		// $in = $request->except('_token', 'coupon_code', 'terms', 'terms_helper');
		$user = Shopkeeper::find(Auth::id());

		/*$group_disc = '0';
		$user_group = UserGroup::find($user->usergroup_id);
		if(!empty($user_group)){
			$group_disc = $user_group->percentage;
		}*/
		$in['user_id']     = Auth::id();
		$in['first_name']  = $user->name;
		$in['last_name']   = $user->name;
		$in['phone']       = $user->mobile;
		$in['email']       = $user->email;
		$in['address']     = $user->address;
		$in['country']     = Country::find($user->country_id)->name;
		$in['state']       = State::find($user->state_id)->name;
		$in['city']        = City::find($user->city_id)->name;
		$in['zip_code']    = ($user->zip_code_id!='')?Zipcode::find($user->zip_code_id):'';
		$in['order_notes'] = $request->order_notes;
		$in['subtotal']    = $this->getSubTotal($request->product_detail,$request->coupon_code);
		$in['total']       = $this->getTotal($request->product_detail,$request->payment_method,$request->place,$request->shipping_charge,$request->coupon_code);
		$in['place'] 	   = $request->place;
		
		
		$pm = $request->payment_method;
		$place = $request->place;

		// if payment method is cash on delivery
		if ($pm == 1) {
			if ($place == 'in') {
				$scharge = $gs->in_cash_on_delivery;
			} elseif ($place == 'around') {
				$scharge = $gs->around_cash_on_delivery;
			} else {
				$scharge = $gs->world_cash_on_delivery;
			}
		}
		// if payment method is cash on advance
		else {
			if ($place == 'in') {
				$scharge = $gs->in_advanced;
			} elseif ($place == 'around') {
				$scharge = $gs->around_advanced;
			} else {
				$scharge = $gs->world_advanced;
			}
		}

		$in['shipping_charge'] = $request->shipping_charge;
		$in['tax'] = $request->tax;
		$in['payment_method'] = $pm;
		$in['shipping_method'] = $place;
		$order = Order::create($in);
		$order->unique_id = $order->id + 100000;
		$order->save();

		//$carts = Cart::where('cart_id', Auth::user()->id)->get();
		$carts = json_decode($request->product_detail);

		// store products in orderedproducts table
		foreach($carts as $cart) {
			$op = new Orderedproduct;
			$op->user_id = Auth::id();
			$op->order_id = $order->id;
			$op->vendor_id = 1;
			$op->product_id = $cart->product_id;
			$op->product_name = $cart->title;
			$op->product_price = $cart->price;
			//$op->offered_product_price = $cart->current_price;
			$op->attributes = json_encode($cart->attributes);

			if ($request->coupon_code) {
				//$csession = session('coupon_code');
				$coupon = Coupon::where('coupon_code', $request->coupon_code)->first();
				if ($coupon->coupon_type=='percentage') {
					// if coupon type is percentage
					if (empty($cart->current_price)) {
						// if the product has no offer...
						$cartItemTotal = $cart->quantity*$cart->price;
						$cartItemCoupon = ($cartItemTotal*$coupon->coupon_amount)/100;
						$producttotal = $cartItemTotal - $cartItemCoupon;
					} else {
						// if the product has offer...
						$cartItemTotal = $cart->quantity*$cart->current_price;
						$cartItemCoupon = ($cartItemTotal*$coupon->coupon_amount)/100;
						$producttotal = $cartItemTotal - $cartItemCoupon;
					}
				}else{
					// if coupon type is fixed
					//$cartItems = Cart::where('cart_id', Auth::user()->id)->get();
					$cartItems = json_decode($request->product_detail);
					$amo = 0;
					foreach ($cartItems as $item) {
						if (!empty($item->current_price)) {
							$amo += $item->current_price*$item->quantity;
						} else {
							$amo += $item->price*$item->quantity;
						}
					}

					$charpertaka = $coupon->coupon_amount/$amo;


					if (empty($cart->current_price)) {
						$cartItemTotal = $cart->quantity*$cart->price;
						$cartItemCoupon = $cartItemTotal*$charpertaka;
						$producttotal = $cartItemTotal-$cartItemCoupon;
					} else {
						$cartItemTotal = $cart->quantity*$cart->current_price;
						$cartItemCoupon = $cartItemTotal*$charpertaka;
						$producttotal = $cartItemTotal-$cartItemCoupon;
					}

				}
			} else {
				if (empty($cart->current_price)) {
					// if cart item has no offer
					$producttotal = $cart->price*$cart->quantity;
					$cartItemCoupon = 0;
				} else {
					// if cart item has offer
					$producttotal = $cart->current_price*$cart->quantity;
					$cartItemCoupon = 0;
				}
			}

			$op->quantity = $cart->quantity;
			$op->product_total = $producttotal;
			$op->coupon_amount = $cartItemCoupon;
			$op->save();
		}
		/* $success['status'] = true; */
		/*return response()->json($success, $this-> successStatus); */

		$title = "New Order Placed";
		$message = "Your order has been placed successfully! Our agent will contact with you later. <br><strong>Order ID: </strong> " . $order->unique_id;
		
		$this->sendNotification($user->fcm_id,$title,$message);
		/* Save Notification */
		$saveNotification = new ShopkeeperNotification();
		$saveNotification->shopkeeper_id = $user->id;
		$saveNotification->title = $title;
		$saveNotification->message = $message;
		$saveNotification->is_viewed = '0';
		$saveNotification->save();
		/* Save Notification */

		if ($request->payment_method == 1) {
			$success['status'] = true;
			$success['order_id'] = $order->id;
			$success['invoice_id'] = $order->unique_id;
			$success['msg'] = "Your order has been placed successfully! Our agent will contact with you later. Order ID: " . $order->unique_id;
			return response()->json($success, $this-> successStatus); 
		} elseif ($request->payment_method == 2) {
			$payment['order_id'] = $order->id;
			$payment['user_id'] = Auth::user()->id;
			$payment['gateway_id'] = 100;
			$payment['amount'] =  $op->product_total;
			$payment['btc_amo'] = 0;
			$payment['btc_wallet'] = "";
			$payment['try'] = 0;
			$payment['status'] = 0;
			Orderpayment::create($payment);
			$success['status'] = true;
			$success['order_id'] = $order->id;
			$success['invoice_id'] = $order->unique_id;
			$success['msg'] = "Your order has been placed successfully! Our agent will contact with you later. Order ID: " . $order->unique_id ." Transactions ID:" . $request->trx_id ."";
			return response()->json($success, $this-> successStatus);
		// after payment clear Cart and redirect to success page
		}
	}

	public function cartDetail(Request $request){
			
		$gs = GS::first();

		$product_details = json_decode($request->product_detail);
		$data = [];
		$subtotal = 0;
		$total = 0;
		$tax_percentage = 0;
		$tax_amount = 0;
		$total = 0;
		$total_items = 0;

		$group_disc = '0';
		$user = Shopkeeper::find(Auth::id());
		$user_group = UserGroup::find($user->usergroup_id);
		if(!empty($user_group)){
			$group_disc = 0.00;
		}

		foreach ($product_details as $key => $product) {
			$products = Product::find($product->product_id);

			$product_price = $products->price;
			if($group_disc !='0'){
				$group_disc_amount = ($products->price*$group_disc)/100;
				$product_price = $product_price - $group_disc_amount;
			}
			$products->price = $product_price;
			$products->description = strip_tags($products->description);
			$products->attributes = $product->attributes;
			$products->cart_quantity = $product->quantity;
			$products->cart_amount = $product->quantity*$product_price;
			foreach($products->previewimages as $images){
    			$images->image = asset('assets/user/img/products/'.$images->image);
    			$images->big_image = asset('assets/user/img/products/'.$images->big_image);
    		}
			$data['products'][] = $products;
			$subtotal += $products->cart_amount;
			$total_items += $product->quantity;
		}
		$subtotal = $this->getSubTotal($request->product_detail);
		$tax_percentage = $gs->tax;
		$tax_amount = ( $subtotal * ( $tax_percentage / 100 ) );
		$total = $subtotal+$tax_amount;
		$data['total_items'] = $total_items;
		$data['subtotal'] = number_format($this->getSubTotal($request->product_detail),2);
		$data['tax_percentage'] = $tax_percentage;
		$data['tax_amount'] = number_format($tax_amount,2);
		$data['total'] = number_format($this->getTotal($request->product_detail),2);
		return response()->json($data, $this->successStatus);
	}

	public function orderHistory(Request $request,$order_id=""){
		if($order_id!=''){
			$order_history = Order::where(['user_id' => Auth::id()])->where('id',$order_id)->orderby('id','DESC')->get();
		}else{
			$order_history = Order::where(['user_id' => Auth::id()])->orderby('id','DESC')->get();
		}
			
		$fav_arr = [];
        if(!empty(auth::user())){
            $user = auth::user();
            $favorit = Favorit::where('user_id',$user->id)->get();
            // echo json_encode($favorit);
            if(!empty($favorit)){
                foreach ($favorit as $fav_key => $fav_value) {
                    $fav_arr[$fav_key] = $fav_value->product_id;
                }
            }
        }

		if($order_history->isEmpty()){
			$data['order_history'] = [];
			$data['msg'] = "No Order Found";
			$data['status'] = false;
			$status = 401;
		}else{

			foreach ($order_history as $key => $value) {
				$value->tax_amount = number_format(($value->subtotal*$value->tax)/100,2);
				$value->orderedproducts;
				$value->total_products = count($value->orderedproducts);
				$coupon_amount = 0;
				foreach ($value->orderedproducts as $p_key => $p_value) {
					$p_value->refund;
					$coupon_amount += $p_value->coupon_amount;
					$attr = [];
      				$i = 0;
					foreach($p_value->product->previewimages as $images){
			            $images->image = asset('assets/user/img/products/'.$images->image);
			            $images->big_image = asset('assets/user/img/products/'.$images->big_image);
		          	}

		          	if($p_value->attributes!='[]' || $p_value->attributes!='' || $p_value->attributes!='""'){
			            $attributes = json_decode($p_value->attributes);
			            if(!empty($attributes))
			              foreach ($attributes as $key => $attribute) {
			                  $attr[$i]['name'] = $key;
			                  $attr[$i]['options'] = (isset($attribute[0]))?$attribute[0]:'';
			                  $i++;
			              }
			            $p_value->attributes = $attr;
			          }
	                $p_value->favorite = in_array($p_value->id,$fav_arr)?1:0;

				}
				$value->coupon_amount = $coupon_amount;
				/* Status */
				if($value->approve == '1'){
					if($value->shipping_status == '0'){
						$value->order_status = 'Pending';
					}elseif($value->shipping_status == '1'){
						$value->order_status = 'In-Progress';
					}elseif($value->shipping_status == '2'){
						$value->order_status = 'Completed';
					}
				}else if($value->approve == '-1'){
					$value->order_status = 'Rejected';
				}else{
					$value->order_status = 'Pending';
				}
				/* Status */
			}

			$data['order_history'] = $order_history;
			$data['msg'] = "Order History";
			$data['status'] = true;
			$status = $this-> successStatus;
		}
		return response()->json($data, $status);
	}

	public function NotificationList(Request $request){

		if($request->q == 'last'){
			$resp = ShopkeeperNotification::where('shopkeeper_id',Auth::id())->orderby('id','DESC')->first();
		}else{
			$resp = ShopkeeperNotification::where('shopkeeper_id',Auth::id())->orderby('id','DESC')->get();
		}

		$data['notifications'] = $resp;
		$data['msg'] = 'Notification List';
		$data['status'] = true;
		return response()->json($data, $this-> successStatus);
	}

	public function orderCancel(Request $request){
		$order = Order::find($request->order_id);
		if(!empty($order)){
			$order->approve = '2';
			$order->save();

			$user = Shopkeeper::find(Auth::id());
			$title = 'Order Status';
			$message = 'Order Id #'.$request->order_id.' is Cancelled';
			
			/* Save Notification */
			$saveNotification = new ShopkeeperNotification();
			$saveNotification->shopkeeper_id = $user->id;
			$saveNotification->title = $title;
			$saveNotification->message = $message;
			$saveNotification->is_viewed = '0';
			$saveNotification->save();
			/* Save Notification */

			$this->sendNotification($user->fcm_id,$title,$message);
			
			$data['msg'] = $message;
			$data['status'] = true;

		}else{
			$data['msg'] = 'Order Id not available';
			$data['status'] = false;
		}

		return response()->json($data, $this-> successStatus);

	}

	public function duplicatenotification(){
		$regId = 'ez0CUuslJTE:APA91bGlnuSdouPX0t7tk0UiFPNYm7Nin-9dgTGZY7131T-q6B7vfNtNmQqy-vfuMKvrYuo_sYNMMi-3zgBOLnV64xbAUakTAJ3wmkU05G7RLb-2JFeWLtF2WvsEicSwCHskU5Gh43Vg';
		$title = 'Hello World';
		$message = 'Laptop True Value Order Placed';
		$this->sendNotification($regId,$title,$message);
	}

	/* Send Firebase Notification */
	public function sendNotification($regId,$title,$message){

		// define('FIREBASE_API_KEY', 'AAAAUG7Snkg:APA91bFdUnrMQwY_hJ3mD0MLj_vjCpvlXFBQbuRykSIaSwFnyxv7dd-PNKsIUhWnSX8dxj_zmCgPaG06oqTWms0PtEKX01h5ulNeDB71iqX9HiabOWfA64jlYp5Eq8sMMXm9UfOjKFkN');

		$message = strip_tags($message);        
		$title = strip_tags($title);

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "{\r\n \"to\" : \"$regId\",\r\n \"collapse_key\" : \"type_a\",\r\n \"notification\" : {\r\n \"body\" : \"$message\",\r\n \"title\": \"$title\"\r\n },\r\n \"data\" : {\r\n \"body\" : \"$message\",\r\n \"title\": \"$title\",\r\n \"key_1\" : \"\" }\r\n}",
			CURLOPT_HTTPHEADER => array(
				"Authorization: key=".FIREBASE_API_KEY,
				"Cache-Control: no-cache",
				"Content-Type: application/json",
				"Postman-Token: 17dca3af-6994-4fe7-b8ec-68f99d13cfe8"
			),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		return true;
	}
	/* Send Firebase Notification */


	/*Order Amount On Basic of ID*/
	public function orderAmount(Request $request){
		if(!empty($request->order_id)){
            $order = Order::where('unique_id',$request->order_id)->orderby('id','DESC')->first();
             if(empty($order)){
            	$data['order'] = [];
	            $data['msg'] = 'No Orders Found';
	            $data['status'] = false;
	            $status = 401;
	        }else if ($order->staff_user_id != NULL){
	            $data['order'] = [];
	            $data['msg'] = 'Order Id Already Used by Salesman';
	            $data['status'] = false;
	            $status = 401;
	        }else {
	        	$data['order_price'] = $order->total;
		        $data['msg'] = 'Order Amount';
		        $data['status'] = true;
		        $status = $this-> successStatus;
	        }
       
        }else {
          	$data['order'] = [];
            $data['msg'] = 'Please Enter Valid Order Id';
            $data['status'] = false;
            $status = 401;
        }
      
        return response()->json($data, $status); 
	}

	public function getSubTotal($cartdetail,$coupon_code=""){
	    $cartItems = json_decode($cartdetail);
	    $amo = 0;
	    foreach ($cartItems as $item){
	    	$product = Product::find($item->product_id);
	      if (!empty($product->current_price)) {
	        $amo += $product->current_price*$item->quantity;
	      } else {
	        $amo += $product->price*$item->quantity;
	      }
	    }
	    $char = 0;
	    $coupon = $coupon_code;
	    if($coupon!='' && Coupon::where('coupon_code', $coupon)->count() == 1){
	      $cdetails = Coupon::where('coupon_code', $coupon)->latest()->first();
	      if ($cdetails->coupon_type == 'percentage'){
	        $char = ($amo*$cdetails->coupon_amount)/100;
	      }else{
	        if($cdetails->coupon_min_amount <= $amo){
	          $char = $cdetails->coupon_amount;
	        }
	      }
	    }
	    $subtotal = $amo - $char;
	    return round($subtotal, 2);
	}

	public function getTotal($cartdetail,$pm=1,$place='in',$scharge="0",$coupon_code=""){
	    $cartItems = json_decode($cartdetail);
	    $subtotal = $this->getSubTotal($cartdetail,$coupon_code);
	    $gs = GS::first();
	    if (count($cartItems) > 0) {
	      // $pm = $request->payment_method;
	      // $place = $request->place;

	      /*// if payment method is cash on delivery
	      if ($pm == 1) {
	        if ($place == 'in') {
	          $scharge = $gs->in_cash_on_delivery;
	        } elseif ($place == 'around') {
	          $scharge = $gs->around_cash_on_delivery;
	        } else {
	          $scharge = $gs->world_cash_on_delivery;
	        }
	      }
	      // if payment method is cash on advance
	      else {
	        if ($place == 'in') {
	          $scharge = $gs->in_advanced;
	        } elseif ($place == 'around') {
	          $scharge = $gs->around_advanced;
	        } else {
	          $scharge = $gs->world_advanced;
	        }
	      }
	    } else {
	      $scharge = 0;
	    */
	    }

	    $total = $subtotal + (($gs->tax*$subtotal)/100);
	    $total = $total+$scharge;

	    return round($total, 2);
	}

	/*E- Commerce And Dealer Refund Request*/
	public function refund(Request $request) {
     $validator = Validator::make($request->all(),[
        'reason' => 'required'
      ]);

      $refund = new Refund;
      $refund->orderedproduct_id = $request->orderedproduct_id;
      $refund->status = 0;
      $refund->reason = $request->reason;
      $refund->save();
      $data['msg'] = 'Refund request sent successfully';
	  $data['status'] = true;
	  $status = $this-> successStatus;
      return response()->json($data, $status); 
    }
}