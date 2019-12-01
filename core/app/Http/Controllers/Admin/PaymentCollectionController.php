<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;  
use App\PaymentCollection;
use App\PaymentCollectionDescription;
use App\Models\StaffUser;
use Validator;
use Hash;
use Auth;
use Datatables;
use Carbon\Carbon;
  
use Illuminate\Support\Facades\File;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;

class PaymentCollectionController extends Controller
{


    protected function validator(array $data,array $rules){
        return Validator::make($data, $rules);
    }
    /*Show States List*/
    public function index(Request $request){
           
        // if(!check_perm('collections_view')){
        //   exit('Permission Denied');
          // return redirect()->route('permission_denied');
        // }

        // if($request->ajax()){

        //      return datatables()->of(DB::table('payment_collections As t')
        //             ->leftjoin('staff_users', 't.staff_user_id', '=', 'staff_users.id')
        //             ->when(empty(auth()->user()->is_administrator), function($query) use ($request){
        //                 return $query->where('staff_users.id',Auth::id());
        //             })
        //             ->when(empty(auth()->user()->is_administrator), function($query) use ($request){
        //                 return $query->where('t.new_date',(new Carbon(now()))->format('Y-m-d'));
        //             })
        //             ->select('t.id','t.name','t.mobile_no','t.alternate_no','t.collection_date','t.new_date','t.amount','t.status','t.collected_amount','t.balance_amount','staff_users.first_name as salesman_first_name','staff_users.last_name as salesman_last_name')
        //             ->get())   
        //         ->addColumn('action', function($data){
        //         if(check_perm('collections_view')){
        //           $button = '<a href="'.route('collection.show',$data->id).'" name="show" id="'.$data->id.'" class="edit btn btn-info btn-sm">Show</a>';
        //         }
        //         $button .= '&nbsp;&nbsp;';

        //         $button.= '<a href="'.route('cities.edit',$data->id).'" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</a>';
        //         $button .= '&nbsp;&nbsp;';

        //         if(!empty(auth()->user()->is_administrator)){
        //           $button.= '<a href="'.route('collection.edit',$data->id).'" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</a>';
        //         $button .= '&nbsp;&nbsp';
        //         $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
        //             $button .= '&nbsp;&nbsp;';
        //              if($data->status == 0){
        //               $button .= '<button type="button" name="status" id="'.$data->id.'" class="status btn btn-success btn-sm" data-status="'.$data->status.'">Closed</button>';
        //                }else if($data->status == 2){
        //                 $button .= '<button type="button" name="status" id="'.$data->id.'" class="status btn btn-success btn-sm" data-status="'.$data->status.'">Closed</button>';
        //                 }
        //         }
        //         return $button;
        //         })
        //         ->rawColumns(['action'])
        //         ->make(true);
        // }
        //return view('admin.dashboard.pages.city.citylist');
        return view('admin.paymentCollection.index');
    }

    public function paginate(){
      $order       = Input::get('order');
      $columns     = Input::get('columns');
      $query_key   = Input::get('search');
      $search_key  = $query_key['value'];
      $customer_id = Input::get('customer_id');
      $status_id   = Input::get('status_id');
      $is_verified = Input::get('is_verified');
      $groups      = Input::get('groups');
      $q           = PaymentCollection::query();
      $query       = PaymentCollection::orderBy($columns[$order[0]['column']]['name'], $order[0]['dir']);

      // Filtering Data
      if($status_id!=''){
          $query->where('status', $status_id );
          $q->whereIn('status', $status_id );
      }

      if($is_verified!=''){
          $q->whereIn('is_verified', $is_verified );
          $query->whereIn('is_verified', $is_verified );
      }
      if($groups!=''){
          $q->whereIn('usergroup_id', $groups );
          $query->whereIn('usergroup_id', $groups );
      }
      

      // If the user has permission to view only the ones that are created by himself;
      if(!check_perm('shopkeepers_view') && check_perm('shopkeepers_view_own'))
      {
          $q->where(function($k){
              $k->where('salesman_id', auth()->user()->id);
          });
          $query->where(function($k){
              $k->where('salesman_id', auth()->user()->id);
          });                   
          
      }


      /*if($customer_id)
      {
          $q->whereHas('invoice', function ($q) use ($customer_id) {
              $q->where('invoices.customer_id', '=', $customer_id);
          });

          $query->whereHas('invoice', function ($q) use ($customer_id) {
              $q->where('invoices.customer_id', '=', $customer_id);
          });

      }*/

      $number_of_records  = $q->get()->count();

      if($search_key)
      {
          $query->where(function ($k) use ($search_key) {
              $k->where('name', 'like', $search_key.'%')
              /*->orWhere('shopname', 'like', $search_key.'%')
              ->orWhere('email', 'like', $search_key.'%')
              ->orWhere('mobile', 'like', $search_key.'%')
              ->orWhere('phone', 'like', $search_key.'%')
              ->orwhereHas('usergroup',function ($q) use ($search_key){
                  $q->where('user_groups.name', 'like', $search_key.'%');
              })
              ->orwhereHas('country',function ($q) use ($search_key){
                  $q->where('countries.name', 'like', $search_key.'%');
              })
              ->orwhereHas('state',function ($q) use ($search_key){
                  $q->where('states.name', 'like', $search_key.'%');
              })
              ->orwhereHas('city',function ($q) use ($search_key){
                  $q->where('cities.name', 'like', $search_key.'%');
              })
              ->orwhereHas('zipcode',function ($q) use ($search_key){
                  $q->where('zipcodes.area_name', 'like', $search_key.'%');
              })*/;
          });
      }

      $recordsFiltered = $query->get()->count();
      $length = Input::get('length');
      if($length != '-1'){
          $query->skip(Input::get('start'))->take(Input::get('length'));
      }
      $data = $query->get();
      //
      // echo json_encode($data);
      // exit;
      $rec = [];

      if (count($data) > 0)
      {
          foreach ($data as $key => $row)
          {   
              $rec[] = array(
                  // anchor_link($row->name,route('admin.shopkeeper.show',$row->id)),
                  $row->name,
                  $row->mobile_no,
                  $row->alternate_no,
                  $row->collection_date,
                  $row->new_date,
                  $row->amount,
                  $row->collected_amount,
                  $row->balance_amount,
                  $row->assigned->first_name,
                  $row->status,
                  'Action',
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

  /*Add City*/
  public function store(Request $request){
      $rules = array(
           'name' => 'required',
           'mobile_no' => 'required',
            'alternate_no' => 'required',
            'collection_date' => 'required',
            'amount' => 'required',
            );
        $this->validator($request->all(),$rules)->validate();
         $data = array( 
              'payment_collections' => array(
                  'name' => strip_tags($request->input('name')),
                  'mobile_no' => strip_tags($request->input('mobile_no')),
                  'alternate_no' => strip_tags($request->input('alternate_no')),
                  'collection_date' => date("Y-m-d", strtotime($request->input('collection_date'))),
                  'new_date' => date("Y-m-d", strtotime($request->input('collection_date'))),
                  'amount' => strip_tags($request->input('amount')),
                  'staff_user_id' => strip_tags($request->input('staff_user_id')),
                   'status'=> 0,
                 ) 
            );
      $resp = DB::table('payment_collections')->insert($data);
      return redirect('admin/collection')->with('message', 'Payment Collection Add Successfully!');
  }

  public function show(Request $request,$id){
    $paymentcollection  = PaymentCollection::find($id);
    $data['threads']  = PaymentCollectionDescription::where('payment_collection_id',$id)->get();
    $salesman = StaffUser::where('role_id',1);

    if(auth()->user()->level == 2 || auth()->user()->is_administrator){
        $salesman = $salesman->whereNotNull('level')->get();
    }else{
      if($paymentcollection->counter % \Config::get('constants.THREAD_COUNT') == 0){
        $salesman = $salesman->where('level',2)->get();
      }else{
        $salesman = $salesman->where('level',1)->get();
      }
    }

    $data['salesmans'] = $salesman;
    $data['collections'] = $paymentcollection;    
    return view('admin.paymentCollection.show',$data);
  }

     
  public function UpdateStatus($id,$status){
      if($status == 1)
      {
          $data = array( 
                 'status' => 1, 
             );
      }else if($status == 2){
           $data = array( 
                 'status' => 1, 
             );
      }else {
           $data = array( 
                 'status' => 1, 
             );
      }
      PaymentCollection::where('id',$id)->update($data);
  }
   

  public function edit(Request $request,$token){
        //= PaymentCollection::find($token);
      $data['collection']= DB::table('payment_collections As t')
                  ->leftjoin('staff_users', 't.staff_user_id', '=', 'staff_users.id')
                 ->where('t.id',$token)
                  ->select('t.id','t.name','t.mobile_no','t.alternate_no','t.collection_date','t.new_date','t.amount','t.status','t.collected_amount','t.balance_amount','staff_users.first_name as salesman_first_name','staff_users.last_name as salesman_last_name','t.staff_user_id')
                  ->first();

     /* print_r($data['collection']);
      die;*/
       $data['salesman'] = StaffUser::where('role_id',1)->where('level',1)->get();
                
       return view('admin.paymentCollection.edit',$data);
  }

  /*Edit City DropDown*/
  public function EditCityDropdownList(Request $request,$token){
      $html = '';
      $city_id = City::find($token);
      $states = State::where('status',1)->get();
        foreach ($states as $state) {
      $selected = ($city_id->state_id == $state->id) ? 'selected' : '';
          $html .= '<option  value="'.$state->id.' " '.$selected.'>'.$state->name.'</option>';
      }
      return response()->json(['html' => $html]);
  }

   
  public function update(Request $request,$token){
     $rules = array(
           'name' => 'required',
           'mobile_no' => 'required',
            /*'alternate_no' => 'required',*/
            'collection_date' => 'required',
            'amount' => 'required',
            );
        $this->validator($request->all(),$rules)->validate();
      
             $data = array( 
             
                  'name' => strip_tags($request->input('name')),
                  'mobile_no' => strip_tags($request->input('mobile_no')),
                  'alternate_no' => strip_tags($request->input('alternate_no')),
                  'collection_date' => date("Y-m-d", strtotime($request->input('collection_date'))),
                  'new_date' => date("Y-m-d", strtotime($request->input('collection_date'))),
                  'amount' => strip_tags($request->input('amount')),
                  'staff_user_id' => strip_tags($request->input('staff_user_id')),
               
            );
   
   /* print_r($data);
    die;*/
      //dd($data);
      $resp = PaymentCollection::where('id',$token)->update($data);
      if($resp == 1){
          return redirect('admin/collection')->with('success','Payment Collection Details Change Succesfully');
      }else{
          return redirect('admin/collection')->with('error','Something went wrong!!');
      }
  }

  /* Author : 225 */
  public function createPaymentThread(Request $request,$collection_id="",$thread_id=""){

    $rules = [ 
      'next_calling_date' => 'required',
      'feedback'          => 'required',
      'assigned_to'       => 'required'
    ];

    $this->validator($request->all(),$rules)->validate();
    
    $update = PaymentCollection::find($collection_id);

    $thread = new PaymentCollectionDescription;
    $thread->calling_date           = date('Y-m-d H:i:s',strtotime($request->next_calling_date));
    $thread->feedback               = $request->feedback;
    $thread->payment_type           = $request->payment_type;
    $thread->collect_amount         = ($request->amount) ? $request->amount:'0.00';
    $thread->balance_amount         = $update->amount - $request->amount;
    $thread->assigned_to            = $request->assigned_to;
    $thread->status                 = $request->status;
    $thread->payment_collection_id  = $collection_id;
    $thread->save();

    $update->staff_user_id      = $request->assigned_to;
    $update->new_date           = date('Y-m-d',strtotime($request->next_calling_date));
    $update->collected_amount   = $request->amount;
    $update->balance_amount     = $update->amount - $request->amount;
    $update->status             = $request->status;

    $update->counter = (auth()->user()->level == 1)?($update->counter+1):1;

    $update->save();

    $description = sprintf('New Payment Collection Updated');
    log_activity($thread, $description, anchor_link($thread->feedback, route('show_proposal_page', $thread->id ))  );

    session()->flash('success', __('Successfully update payment collection'));
    return redirect()->back();

  }
    /* Author : 225 */


     public function destroy($id)
    {
        $product = PaymentCollection::find($id);
        $product->delete();

        return redirect()->back()->with('status', 'New brand deleted successfully.')->with('alert', 'alert-success');

    }

}
