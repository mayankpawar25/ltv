<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Input;

use App\Http\Controllers\Controller;

use App\GeneralSetting as GS;
use App\Order;
use App\Orderedproduct;
use App\Product;
use App\Vendor;
use Carbon\Carbon;
use App\Transaction;
use App\Currency;
use App\Shopkeeper;
use App\Models\StaffUser;
use App\User;
use Auth;

class OrderController extends Controller
{ 

  public function index(){
    // 'approve', 0 : Pending Orders
    // 'approve', 1 : Accepted Orders
    // 'approve', -1: Rejected Orders

    // 'shipping_status', 0 : Delivery Pending
    // 'shipping_status', 1 : Delivery Inprocess
    // 'shipping_status', 2 : Delivered

    // 'payment_method', 1 : COD
    // 'payment_method', 2 : Online

    $data = Order::dropdown_for_filtering();    
    return view('admin.orders.index',compact('data'));
  }

  public function paginate(Request $request){

    $status_id = Input::get('status_id');
    $delivery_status = Input::get('delivery_status');
    $payment_method = Input::get('payment_method');
    $payment_status = Input::get('payment_status');
    $assigned_to    = Input::get('assigned_to');

    $query_key          = Input::get('search');
    $search_key         = $query_key['value'];
    $date_range         = Input::get('date_range');
    $currency_id        = Input::get('currency_id');

    $q = Order::orderBy('id','DESC');
    $query = Order::orderBy('id','DESC');

    $date_from          = "";
    $date_to            = "";

    if($date_range)
    {
      list($date_from, $date_to)  = explode("-", $date_range);
      $date_from                  = str_replace('/', '-', trim($date_from) );
      $date_to                    = str_replace('/', '-', trim($date_to));
      $date_from                  = date2sql(trim($date_from));
      $date_to                    = date2sql(trim($date_to));
    }

    if($status_id){
      // approve
      $q->whereIn('approve', $status_id);
      $query->whereIn('approve', $status_id);

    }

    if($delivery_status){
      // shipping_status
      $q->whereIn('shipping_status', $delivery_status);
      $query->whereIn('shipping_status', $delivery_status);
    }

    if($payment_method){
      // payment_method
      $q->whereIn('payment_method', $payment_method);
      $query->whereIn('payment_method', $payment_method);
    }

    if($payment_status){
      // payment_status
      $q->whereIn('payment_status', $payment_status);
      $query->whereIn('payment_status', $payment_status);
    }

    if($assigned_to){
        $q->whereIn('staff_user_id', $assigned_to);
        $query->whereIn('staff_user_id', $assigned_to);
    }

    if(!auth::user()->is_administrator){
        $q->where('staff_user_id', auth::user()->id);
        $query->where('staff_user_id', auth::user()->id);
    }

    // $q->whereNotNull('staff_user_id');
    // $query->whereNotNull('staff_user_id');

    if($date_from && $date_to)
    {
        $q->whereBetween('created_at', [$date_from, $date_to ]);
        $query->whereBetween('created_at', [$date_from, $date_to ]);
    }

    $number_of_records  = $q->get()->count();

    if ($search_key)
    {
        $query->orwhere('unique_id', 'like', like_search_wildcard_gen($search_key))
            ->orWhere('phone', 'like', like_search_wildcard_gen($search_key))
            ->orWhere('email', 'like', like_search_wildcard_gen($search_key))
            ->orWhere('total', 'like', like_search_wildcard_gen($search_key))
            ->orWhere('subtotal', 'like', like_search_wildcard_gen($search_key))
            ->orWhere('created_at', 'like', like_search_wildcard_gen(date2sql($search_key)))
            ->orWhere('first_name', 'like', like_search_wildcard_gen($search_key))
            ->orWhere('last_name', 'like', like_search_wildcard_gen($search_key))
            ->orWhere(DB::raw('CONCAT(first_name," ",last_name)'), 'like', like_search_wildcard_gen($search_key));
            /*->orWhereHas('user', function ($q) use ($search_key) {
                $q->where('users.name', 'like', $search_key . '%');
            });*/
           
        //     ->orWhereHas('status', function ($q) use ($search_key) {
        //         $q->where('name', 'like', $search_key . '%');
        //     });
    }

    $recordsFiltered = $query->get()->count();
    $query->skip(Input::get('start'))->take(Input::get('length'));
    $data = $query->get();

    $rec = [];

    if (count($data) > 0){   
      $subtotal           = 0;
      $total              = 0;
      $tax_total          = 0;
      $discount_total     = 0;
      $adjustment         = 0;
      $applied_credits    = 0;
      $open_amount        = 0;

      $currency                   = Currency::find($currency_id);
      $currency_symbol            = ($currency) ? $currency->symbol : NULL ;

      foreach ($data as $key => $row){
          $name = '-';
          /*if($row->user_type == 1){
              // $name = $row->user_id;
              $shopkeeper = Shopkeeper::find($row->user_id);
              $name = $shopkeeper->name;
              $email = $shopkeeper->email;
              $phone = $shopkeeper->phone;
          }else if($row->user_type == 2){
              $customer = User::find($row->user_id);
              $name = $customer->name;
              $email = $customer->email;
              $phone = $customer->phone;
          }*/
          $order_accept_btn = '';
          if($row->approve == 0){
            $order_accept_btn = '<a href="#" class="btn btn-sm btn-danger" onclick="cancelOrder(event, '.$row->id.')" title="Reject Order"><i class="fa fa-times"></i></a>';

            $order_accept_btn .= '<a href="#" class="btn btn-sm btn-success" onclick="acceptOrder(event, '.$row->id.')" title="Accept Order"><i class="fa fa-check"></i></a>';
          }

          if($row->approve == 1){
            $order_accept_btn = '<span class="badge badge-success">Accepted</span>';
          }

          if($row->approve == -1){
            $order_accept_btn = '<span class="badge badge-danger">Rejected</span>';
          }
          
          $checked1 = ($row->shipping_status==0)?"checked":"";
          $shipping_status = '<label><input type="radio" name="shipping'.$row->id.'" id="inlineRadio'.$row->id.'1" value="0" '.$checked1.' onchange="shippingChange(event, this.value, '.$row->id.')">Pending</label>';
        
          $checked2 = ($row->shipping_status==1)?"checked":"";
          $shipping_status .= '<label><input type="radio" name="shipping'.$row->id.'" id="inlineRadio'.$row->id.'2" value="1" '.$checked2.' onchange="shippingChange(event, this.value, '.$row->id.')">Ready to dispatch</label>';

          $checked3 = ($row->shipping_status==2)?"checked":"";
          $shipping_status .= '<label><input type="radio" name="shipping'.$row->id.'" id="inlineRadio'.$row->id.'3" value="2" '.$checked3.' onchange="shippingChange(event, this.value, '.$row->id.')">Delivered</label>';

          $rec[] = array(        

              $row->unique_id,
              date('d-m-Y',strtotime($row->created_at)),
              $row->first_name.' '.$row->last_name,
              $row->phone,
              $row->email,
              format_currency($row->subtotal, true , $currency_symbol),
              format_currency($row->total, true , $currency_symbol),
              $shipping_status,
              ($row->payment_method == 2)?'<span class="badge badge-warning">Advance</span>':'<span class="badge badge-warning">COD</span>',
              ($row->payment_status == 0)?'<span class="badge badge-danger paidstatus" data-orderid="'.$row->id.'" data-status="1">Unpaid</span>':'<span class="badge badge-success paidstatus"  data-orderid="'.$row->id.'" data-status="0">Paid</span>',
              anchor_link('<span class="icon-eye icon"></span>',route('admin.orderdetails', $row->id)).$order_accept_btn,

              // $row->unique_id,
              // $name,
              // ($row->staff_user_id!='')?StaffUser::select(DB::raw('CONCAT(first_name," ",last_name) as name'))->find($row->staff_user_id)->name:'',
              // $row->staff_user_remarks,
              // format_currency($row->subtotal,true,$currency_symbol),
              // format_currency($row->total,true,$currency_symbol),
              // date('d-m-Y',strtotime($row->created_at)),
              // anchor_link( $row->number, route('show_invoice_page', $row->id)),
              /*anchor_link($row->related_to->first_name .' '. $row->related_to->last_name, route('view_customer_page', $row->customer_id )),*/
              // ($row->related_to->name)?$row->related_to->name:$row->related_to->first_name.' '.$row->related_to->last_name,
              // isset(($row->date)) ? sql2date($row->date) : "",
              // isset(($row->due_date)) ? sql2date($row->due_date) : "",
              // format_currency($row->total, true, $currency_symbol  ),
              // format_currency($row->tax_total, true , $currency_symbol ),                    
              // format_currency($row->discount_total, true , $currency_symbol ),                
              // format_currency($row->adjustment, true , $currency_symbol ),    
              // format_currency($row->applied_credits, true , $currency_symbol ), 
              // format_currency($row->total - ($row->amount_paid + $row->applied_credits), true , $currency_symbol  ), 
              // $row->status->name,

          );

          $subtotal           += $row->subtotal;
          $total              += $row->total;
          // $tax_total          += ($row->tax!=null)?$row->tax:0.00;
          // $discount_total     += $row->discount_total;
          // $adjustment         += $row->adjustment;
          // $applied_credits    += $row->applied_credits;
          // $open_amount        += $row->total - ($row->amount_paid + $row->applied_credits);
      }

      array_push($rec, [

          '<b>'. __('form.total_per_page'). '<b>',
          "",
          "",
          "",
          "",
          '<b>'. format_currency($subtotal, true , $currency_symbol  ). '<b>',
          '<b>'. format_currency($total, true , $currency_symbol  ). '<b>',
          "",
          "",
          "",
          "",
          // '<b>'.format_currency($tax_total, true , $currency_symbol ) . '<b>',                    
          // '<b>'.format_currency($discount_total, true , $currency_symbol ) . '<b>',                
          // '<b>'.format_currency($adjustment, true , $currency_symbol ). '<b>',    
          // '<b>'.format_currency($applied_credits, true , $currency_symbol ). '<b>', 
          // '<b>'.format_currency($open_amount, true , $currency_symbol ). '<b>',
          '',
      ]);
    }


    $output = array(
        "draw" => intval(Input::get('draw')),
        "recordsTotal" => $number_of_records,
        "recordsFiltered" => $recordsFiltered,
        "data" => $rec
    );
    return response()->json($output);
  }

  public function all(Request $request,$shopkeeper_id="") {
    if(empty($request->term)){
      $data['term'] = '';
      if($shopkeeper_id!=''){
        $data['orders'] = Order::where('user_id',$shopkeeper_id)->orderBy('id', 'DESC')->paginate(10);
      }else{
        $data['orders'] = Order::orderBy('id', 'DESC')->paginate(10);
      }
    } else {
      $data['term'] = $request->term;
      if($shopkeeper_id!=''){
        $data['orders'] = Order::where('unique_id', $request->term)->orderBy('id', 'DESC')->paginate(10);
      }else{
        $data['orders'] = Order::where('user_id',$shopkeeper_id)->where('unique_id', $request->term)->orderBy('id', 'DESC')->paginate(10);
      }
    }

    return view('admin.orders.index', $data);
  }

  public function cPendingOrders(Request $request) {
    if (empty($request->term)) {
      $data['term'] = '';
      $data['orders'] = Order::where('approve', 0)->orderBy('id', 'DESC')->paginate(10);
    } else {
      $data['term'] = $request->term;
      $data['orders'] = Order::where('approve', 0)->where('unique_id', $request->term)->orderBy('id', 'DESC')->paginate(10);
    }
    return view('admin.orders.index', $data);
  }

  public function cAcceptedOrders(Request $request) {
    if (empty($request->term)) {
      $data['term'] = '';
      $data['orders'] = Order::where('approve', 1)->orderBy('id', 'DESC')->paginate(10);
    } else {
      $data['term'] = $request->term;
      $data['orders'] = Order::where('approve', 1)->where('unique_id', $request->term)->orderBy('id', 'DESC')->paginate(10);
    }
    return view('admin.orders.index', $data);
  }

  public function cRejectedOrders(Request $request) {
    if (empty($request->term)) {
      $data['term'] = '';
      $data['orders'] = Order::where('approve', -1)->orderBy('id', 'DESC')->paginate(10);
    } else {
      $data['term'] = $request->term;
      $data['orders'] = Order::where('approve', -1)->where('unique_id', $request->term)->orderBy('id', 'DESC')->paginate(10);
    }
    return view('admin.orders.index', $data);
  }

  public function pendingDelivery(Request $request) {
    if (empty($request->term)) {
      $data['term'] = '';
      $data['orders'] = Order::where('shipping_status', 0)->orderBy('id', 'DESC')->paginate(10);
    } else {
      $data['term'] = $request->term;
      $data['orders'] = Order::where('shipping_status', 0)->where('unique_id', $request->term)->orderBy('id', 'DESC')->paginate(10);
    }
    return view('admin.orders.index', $data);
  }

  public function pendingInprocess(Request $request) {
    if (empty($request->term)) {
      $data['term'] = '';
      $data['orders'] = Order::where('shipping_status', 1)->orderBy('id', 'DESC')->paginate(10);
    } else {
      $data['term'] = $request->term;
      $data['orders'] = Order::where('shipping_status', 1)->where('unique_id', $request->term)->orderBy('id', 'DESC')->paginate(10);
    }
    return view('admin.orders.index', $data);
  }

  public function delivered(Request $request) {
    if (empty($request->term)) {
      $data['term'] = '';
      $data['orders'] = Order::where('shipping_status', 2)->orderBy('id', 'DESC')->paginate(10);
    } else {
      $data['term'] = $request->term;
      $data['orders'] = Order::where('shipping_status', 2)->where('unique_id', $request->term)->orderBy('id', 'DESC')->paginate(10);
    }
    return view('admin.orders.index', $data);
  }

  public function cashOnDelivery(Request $request) {
    if (empty($request->term)) {
      $data['term'] = '';
      $data['orders'] = Order::where('payment_method', 1)->orderBy('id', 'DESC')->paginate(10);
    } else {
      $data['term'] = $request->term;
      $data['orders'] = Order::where('payment_method', 1)->where('unique_id', $request->term)->orderBy('id', 'DESC')->paginate(10);
    }
    return view('admin.orders.index', $data);
  }

  public function advance(Request $request) {
    if (empty($request->term)) {
      $data['term'] = '';
      $data['orders'] = Order::where('payment_method', 2)->orderBy('id', 'DESC')->paginate(10);
    } else {
      $data['term'] = $request->term;
      $data['orders'] = Order::where('payment_method', 2)->where('unique_id', $request->term)->orderBy('id', 'DESC')->paginate(10);
    }
    return view('admin.orders.index', $data);
  }

  public function shippingchange(Request $request) {
    $gs = GS::first();

    $order = Order::find($request->orderid);
    $order->shipping_status = $request->value;
    $order->save();

    $ops = Orderedproduct::where('order_id', $order->id)->get();

    foreach ($ops as $key => $op) {
      $op = Orderedproduct::find($op->id);
      $op->shipping_status = $request->value;
      $op->save();
    }

    $sentVendors = [];


    // if order is in process
    if ($order->shipping_status == 1) {
      // if in main city
      if ($order->shipping_method == 'in') {
        // sending mails to vendor
        foreach ($order->orderedproducts as $key => $op) {
          // if (!in_array($op->vendor->id, $sentVendors)) {
          
          /*  $sentVendors[] = $op->vendor->id;
            send_email($op->vendor->email, $op->vendor->shop_name, 'Product delivery is in process', "Thanks for sending your products. We will send these products to customer via courier within ".$gs->in_min." to ".$gs->in_max." days.<p><strong>Order number: </strong>".$order->unique_id."</p> <p><strong>Order details: </strong><a href='".url('/')."/vendor"."/".$order->id."/orderdetails'>".url('/')."/vendor"."/".$order->id."/orderdetails"."</a></p>");
          }*/
        }
        // sending mail to user
        send_email($order->user->email, $order->user->first_name, 'Product delivery is in process', "Your product delivery is in process. We have collected products from vendors and will send to you via courier within ".$gs->in_min." to ".$gs->in_max." days.<p><strong>Order Number: </strong>$order->unique_id</p><p><strong>Order details: </strong><a href='".url('/')."/".$order->id."/orderdetails'>".url('/')."/".$order->id."/orderdetails"."</a></p>");
        send_sms($order->user->phone, "Your product delivery is in process. We have collected products from vendors and will send to you via courier within ".$gs->in_min." to ".$gs->in_max." days.<p><strong>Order Number: </strong>$order->unique_id</p><p><strong>Order details: </strong><a href='".url('/')."/".$order->id."/orderdetails'>".url('/')."/".$order->id."/orderdetails"."</a></p>");
      }
      // if in around main city
      elseif ($order->shipping_method == 'around') {
        // sending mails to vendor
        foreach ($order->orderedproducts as $key => $op) {
          if (!in_array($op->vendor->id, $sentVendors)) {
            $sentVendors[] = $op->vendor->id;
            send_email($op->vendor->email, $op->vendor->shop_name, 'Product delivery is in process', "Thanks for sending your products. We will send these products to customer via courier within ".$gs->am_min." to ".$gs->am_max." days.<p><strong>Order number: </strong>".$order->unique_id."</p> <p><strong>Order details: </strong><a href='".url('/')."/vendor"."/".$order->id."/orderdetails'>".url('/')."/vendor"."/".$order->id."/orderdetails"."</a></p>");
          }
        }

        // sending mail to user
        send_email($order->user->email, $order->user->first_name, 'Product delivery is in process', "Your product delivery is in process. We have collected products from vendors and will send to you via courier within ".$gs->am_min." to ".$gs->am_max." days.<p><strong>Order Number: </strong>$order->unique_id</p><p><strong>Order details: </strong><a href='".url('/')."/".$order->id."/orderdetails'>".url('/')."/".$order->id."/orderdetails"."</a></p>");
        send_sms($order->user->phone, "Your product delivery is in process. We have collected products from vendors and will send to you via courier within ".$gs->am_min." to ".$gs->am_max." days.<p><strong>Order Number: </strong>$order->unique_id</p><p><strong>Order details: </strong><a href='".url('/')."/".$order->id."/orderdetails'>".url('/')."/".$order->id."/orderdetails"."</a></p>");
      }
      // if in around world
      elseif ($order->shipping_method == 'world') {
        // sending mails to vendor
        foreach ($order->orderedproducts as $key => $op) {
          if (!in_array($op->vendor->id, $sentVendors)) {
            $sentVendors[] = $op->vendor->id;
            send_email($op->vendor->email, $op->vendor->shop_name, 'Product delivery is in process', "Thanks for sending your products. We will send these products to customer via courier within ".$gs->aw_min." to ".$gs->aw_max." days.<p><strong>Order number: </strong>".$order->unique_id."</p> <p><strong>Order details: </strong><a href='".url('/')."/vendor"."/".$order->id."/orderdetails'>".url('/')."/vendor"."/".$order->id."/orderdetails"."</a></p>");
          }
        }
        // sending mail to user
        send_email($order->user->email, $order->user->first_name, 'Product delivery is in process', "Your product delivery is in process. We have collected products from vendors and will send to you via courier within ".$gs->aw_min." to ".$gs->aw_max." days.<p><strong>Order Number: </strong>$order->unique_id</p><p><strong>Order details: </strong><a href='".url('/')."/".$order->id."/orderdetails'>".url('/')."/".$order->id."/orderdetails"."</a></p>");
        send_sms($order->user->phone, "Your product delivery is in process. We have collected products from vendors and will send to you via courier within ".$gs->aw_min." to ".$gs->aw_max." days.<p><strong>Order Number: </strong>$order->unique_id</p><p><strong>Order details: </strong><a href='".url('/')."/".$order->id."/orderdetails'>".url('/')."/".$order->id."/orderdetails"."</a></p>");

      }
    }
    // if order  is shipped
    elseif ($order->shipping_status == 2) {
      $orderedproducts = Orderedproduct::where('order_id', $order->id)->get();
      foreach ($orderedproducts as $key => $orderedproduct) {
        $today = Carbon::now();
        $orderedproduct->shipping_date = $today;
        $orderedproduct->save();

        // increase product sales
        $product = Product::find($orderedproduct->product_id);
        $product->sales = $product->sales + $orderedproduct->quantity;
        $product->save();

        /*$vendor = Vendor::find($orderedproduct->vendor_id);
        $vendor->balance = $vendor->balance + $orderedproduct->product_total;
        $vendor->save();*/

        // $tr = new Transaction;
        // $tr->vendor_id = $orderedproduct->vendor_id;
        // $tr->details = "Sold  <strong>" . $orderedproduct->product->title . "</strong>";
        // $tr->amount = $orderedproduct->product_total;
        // $tr->trx_id = str_random(16);
        // $tr->after_balance = $vendor->balance + $orderedproduct->product_total;
        // $tr->save();
      }

      $last_balance_amount = Transaction::where('client_id',$order->user_id)->where('client_type_id','1')->orderby('id','DESC')->first();
      $bal = (isset($last_balance_amount->after_balance) && $last_balance_amount->after_balance!='')?$last_balance_amount->after_balance:'0.00';
      $bal = $bal + $order->total;

      $transaction = new Transaction;
      $transaction->client_id = $order->user_id;
      $transaction->client_type_id = '1';
      $transaction->details = "Purchase Item Order Id ".$order->unique_id;
      $transaction->amount = $order->total;
      $transaction->trx_id = rand(111111,101010101010);
      $transaction->credit = $order->total;
      $transaction->after_balance = $bal;
      $transaction->payment_mode = $order->payment_method;
      $transaction->save();

      // sending mails to vendor
      foreach ($order->orderedproducts as $key => $op) {
        if (!in_array($op->vendor->id, $sentVendors)) {
          $sentVendors[] = $op->vendor->id;
          send_email($op->vendor->email, $op->vendor->shop_name, 'Products delivered', "Thanks sending you products. We have delivered yours products to customer.<p><strong>Order number: </strong>".$order->unique_id."</p> <p><strong>Order details: </strong><a href='".url('/')."/vendor"."/".$order->id."/orderdetails'>".url('/')."/vendor"."/".$order->id."/orderdetails"."</a></p>");
        }
      }

      // sending mail to user
      send_email($order->user->email, $order->user->first_name, 'Products delivered', "Thanks for choosing <strong>".$gs->website_title."</strong> for shopping. We have been noticed that you have received the desired products delivery. Please give a review/suggestion so that we can enhance quality of our products.<p><strong>Order Number: </strong>$order->unique_id</p><p><strong>Order details: </strong><a href='".url('/')."/".$order->id."/orderdetails'>".url('/')."/".$order->id."/orderdetails"."</a></p>");
      send_sms($order->user->phone, "Thanks for choosing <strong>".$gs->website_title."</strong> for shopping. We have been noticed that you have received the desired products delivery. Please give a review/suggestion so that we can enhance quality of our products.<p><strong>Order Number: </strong>$order->unique_id</p><p><strong>Order details: </strong><a href='".url('/')."/".$order->id."/orderdetails'>".url('/')."/".$order->id."/orderdetails"."</a></p>");
    }


    return "success";
  }

  public function acceptOrder(Request $request) {
    $order = Order::find($request->orderid);
    $order->approve = 1;
    $order->save();


    $ops = Orderedproduct::where('order_id', $order->id)->get();
    foreach ($ops as $key => $op) {
      $nop = Orderedproduct::find($op->id);
      $nop->approve = 1;
      $nop->save();
    }


    $sentVendors = [];

    // sending mails to vendor
    foreach ($order->orderedproducts as $key => $op) {
      if (!in_array($op->vendor->id, $sentVendors)) {
        $sentVendors[] = $op->vendor->id;
        send_email($op->vendor->email, $op->vendor->shop_name, 'Order accepted', "Order ID #".$order->unique_id." has been accepted.<p><strong>Order details: </strong><a href='".url('/')."/vendor"."/".$order->id."/orderdetails'>".url('/')."/vendor"."/".$order->id."/orderdetails"."</a></p>");
      }
    }
    // sending mail to user
    send_email($order->user->email, $order->user->first_name, 'Order accepted', "Your order has been accepted.<p><strong>Order Number: </strong>$order->unique_id</p><p><strong>Order details: </strong><a href='".url('/')."/".$order->id."/orderdetails'>".url('/')."/".$order->id."/orderdetails"."</a></p>");
    send_sms($order->user->phone, "Your order has been accepted.<p><strong>Order Number: </strong>$order->unique_id</p><p><strong>Order details: </strong><a href='".url('/')."/".$order->id."/orderdetails'>".url('/')."/".$order->id."/orderdetails"."</a></p>");
    return "success";
  }

  public function cancelOrder(Request $request) {
    $order = Order::find($request->orderid);
    $order->approve = -1;
    $order->save();

    $sentVendors = [];
    // sending mails to vendor
    foreach ($order->orderedproducts as $key => $op) {
      if (!in_array($op->vendor->id, $sentVendors)) {
        $sentVendors[] = $op->vendor->id;
        send_email($op->vendor->email, $op->vendor->shop_name, 'Order rejected', "Order ID #".$order->unique_id." has been rejected.<p><strong>Order details: </strong><a href='".url('/')."/vendor"."/".$order->id."/orderdetails'>".url('/')."/vendor"."/".$order->id."/orderdetails"."</a></p>");
      }
    }
    // sending mail to user
    send_email($order->user->email, $order->user->first_name, 'Order rejected', "Your order has been rejected.<p><strong>Order Number: </strong>$order->unique_id</p><p><strong>Order details: </strong><a href='".url('/')."/".$order->id."/orderdetails'>".url('/')."/".$order->id."/orderdetails"."</a></p>");
    send_sms($order->user->phone, "Your order has been rejected.<p><strong>Order Number: </strong>$order->unique_id</p><p><strong>Order details: </strong><a href='".url('/')."/".$order->id."/orderdetails'>".url('/')."/".$order->id."/orderdetails"."</a></p>");
    return "success";
  }

  public function orderdetails($orderid) {
    $data['order'] = Order::find($orderid);
    $data['orderedproducts'] = Orderedproduct::where('order_id', $orderid)->get();
   /* print_r($data['order']);*/
    return view('admin.orders.details', $data);
  }

  public function paymentStatus(Request $request){
    $order = Order::find($request->orderid);
    $order->payment_status = $request->payment_status;
    if($order->save()){
      echo "success";
    }else{
      echo "failure";
    }
    exit;
    // echo json_encode($order);
  }

}