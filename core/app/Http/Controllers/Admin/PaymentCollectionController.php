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
class PaymentCollectionController extends Controller
{


    protected function validator(array $data,array $rules){
        return Validator::make($data, $rules);
    }
    /*Show States List*/
    public function index(Request $request){
           
        if(!check_perm('collections_view')){
          exit('Permission Denied');
          // return redirect()->route('permission_denied');
        }

        if($request->ajax()){

             return datatables()->of(DB::table('payment_collections As t')
                    ->leftjoin('staff_users', 't.staff_user_id', '=', 'staff_users.id')
                    ->when(empty(auth()->user()->is_administrator), function($query) use ($request){
                        return $query->where('staff_users.id',Auth::id());
                    })
                    ->when(empty(auth()->user()->is_administrator), function($query) use ($request){
                        return $query->where('t.new_date',(new Carbon(now()))->format('Y-m-d'));
                    })
                    ->select('t.id','t.name','t.mobile_no','t.alternate_no','t.collection_date','t.new_date','t.amount','t.status','t.collected_amount','t.balance_amount','staff_users.first_name as salesman_first_name','staff_users.last_name as salesman_last_name')
                    ->get())   
                ->addColumn('action', function($data){
                if(check_perm('collections_view')){
                  $button = '<a href="'.route('collection.show',$data->id).'" name="show" id="'.$data->id.'" class="edit btn btn-info btn-sm">Show</a>';
                }
                $button .= '&nbsp;&nbsp;';

               /* $button.= '<a href="'.route('cities.edit',$data->id).'" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</a>';
                $button .= '&nbsp;&nbsp;';*/

                if(!empty(auth()->user()->is_administrator)){
                    $button .= '&nbsp;&nbsp;';
                     if($data->status == 0){
                      $button .= '<button type="button" name="status" id="'.$data->id.'" class="status btn btn-success btn-sm" data-status="'.$data->status.'">Closed</button>';
                       }else if($data->status == 2){
                        $button .= '<button type="button" name="status" id="'.$data->id.'" class="status btn btn-success btn-sm" data-status="'.$data->status.'">Closed</button>';
                        }
                }
                return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
         }
        //return view('admin.dashboard.pages.city.citylist');
        return view('admin.paymentCollection.index');
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
         
         /* print_r($data);
            die;*/
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
      if(count($data['threads']) > 0){
        if(count($data['threads']) % \Config::get('constants.THREAD_COUNT') == 0){
          $salesman = $salesman->where('level',2)->get();
        }else{
          $salesman = $salesman->where('level',1)->get();
        }
      }else{
        $salesman = $salesman->where('level',1)->get();
      }
    }

    $data['salesmans'] = $salesman;
    $data['collections'] = $paymentcollection;    
    return view('admin.paymentCollection.show',$data);
  }

     /*Update City Status (Active / Inactive)*/
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
     /*Edit City*/

    public function edit(Request $request,$token){
        $data['city']  = city::find($token);
         return view('admin.city.edit',$data);
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

    /*Update City*/
  public function update(Request $request,$token){

      $rules = array(
          'name'  => 'required',
          'state_id'  => 'required',
         );

      $dt = $this->validator($request->all(),$rules)->validate();
      
             $data = array( 
                 'name' => strip_tags($request->input('name')),
                 'state_id' => strip_tags($request->input('state_id')),
                 
               );
   
      //dd($data);
      $resp = City::where('id',$token)->update($data);
      if($resp == 1){
          return redirect('admin/cities')->with('success','City updated Succesfully');
      }else{
          return redirect('admin/cities')->with('error','Something went wrong!!');
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
    $update->save();

    $description = sprintf('New Payment Collection Updated');
    log_activity($thread, $description, anchor_link($thread->feedback, route('show_proposal_page', $thread->id ))  );

    session()->flash('success', __('Successfully update payment collection'));
    return redirect()->back();

  }
    /* Author : 225 */

}
