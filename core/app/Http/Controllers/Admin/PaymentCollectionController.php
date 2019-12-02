<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;  
use App\PaymentCollection;
use App\PaymentCollectionDescription;
use App\Models\StaffUser,App\Shopkeeper;
use App\Notifications\CollectionReceived;
use Validator;
use Hash;
use Auth;
use Datatables;
use Carbon\Carbon;
use App\Currency;
  
use Illuminate\Support\Facades\File;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;

use App\Notification;

class PaymentCollectionController extends Controller
{
  protected function validator(array $data,array $rules){
      return Validator::make($data, $rules);
  }
  /*Show States List*/

  public function create(){
    $data['salesman'] = StaffUser::where('role_id',1)->where('level',1)->get();
    return view('admin.paymentCollection.create',$data);
  }

  public function index(Request $request){
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
    if(!auth()->user()->is_administrator){
      $q->where(function($k){
          $k->where('staff_user_id', auth()->user()->id);
      });
      $query->where(function($k){
          $k->where('staff_user_id', auth()->user()->id);
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
        ->orWhere('mobile_no', 'like', $search_key.'%')
        ->orWhere('alternate_no', 'like', $search_key.'%')
        ->orWhere('collection_date', 'like', date('Y-m-d', strtotime($search_key)).'%')
        ->orWhere('new_date', 'like', date('Y-m-d', strtotime($search_key)).'%')
        ->orWhere('amount', 'like', $search_key.'%')
        ->orWhere('collected_amount', 'like', $search_key.'%')
        ->orWhere('balance_amount', 'like', $search_key.'%')
        ->orwhereHas('assigned',function ($q) use ($search_key){
            $q->where('staff_users.first_name', 'like', $search_key.'%');
        })
        /*->orwhereHas('country',function ($q) use ($search_key){
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
        foreach ($data as $key => $row){
          $close_btn = '';
          $open_close_badge = ($row->status==0)?'<span class="badge badge-warning">open</span>':'<span class="badge badge-success">closed</span>';
          $action = '<a href="'.route('collection.show',$row->id).'" class="btn btn-success btn-sm"><i class="icon-eye icons"></i></a>';
          if(auth()->user()->is_administrator){

            if($row->status==0 || $row->status==2){
              $close_btn = '<button type="button" name="status" id="'.$row->id.'" class="status btn btn-success btn-sm" data-status="'.$row->status.'"><i class="icon-check icons"></i></button>';
            }
            if($row->status == '2'){
              $open_close_badge ='<span class="badge badge-success">closed by Salesman</span>';
            }
            
            $action = '<a href="'.route('collection.edit',$row->id).'" name="edit" id="'.$row->id.'" class="edit btn btn-primary btn-sm"><span class="icon-pencil icons" data-toggle="tooltip" title="Edit"></span></a>'.'<button type="button" name="delete" id="'.$row->id.'" class="delete btn btn-danger btn-sm"><span class="icon-trash icons" data-toggle="tooltip" title="Delete"></span></button>'.''.$close_btn;
          }



          $rec[] = array(
              anchor_link($row->name,route('collection.show',$row->id)),
              // $row->name,
              $row->mobile_no,
              $row->alternate_no,
              date('d-m-Y',strtotime($row->collection_date)),
              date('d-m-Y',strtotime($row->new_date)),
              $row->amount,
              $row->collected_amount,
              $row->balance_amount,
              $row->assigned->first_name.' '.$row->assigned->last_name,
              $open_close_badge,
              $action,

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

  /* Add Collection */
  public function store(Request $request){
    $rules = array(
      'name' => 'required',
      'mobile_no' => 'required',
      'collection_date' => 'required',
      'amount' => 'required',
    );

    $this->validator($request->all(),$rules)->validate();

    $payment_collection = new PaymentCollection;
    $payment_collection->name = strip_tags($request->input('name'));
    $payment_collection->mobile_no = strip_tags($request->input('mobile_no'));
    $payment_collection->alternate_no = strip_tags($request->input('alternate_no'));
    $payment_collection->collection_date = date("Y-m-d", strtotime($request->input('collection_date')));
    $payment_collection->new_date = date("Y-m-d", strtotime($request->input('collection_date')));
    $payment_collection->amount = strip_tags($request->input('amount'));
    $payment_collection->staff_user_id = strip_tags($request->input('staff_user_id'));
    $payment_collection->status = 0;
    $payment_collection->save();

    $description = sprintf('New Collection Added');
    log_activity($payment_collection, $description, anchor_link($payment_collection->name, route('collection.show', $payment_collection->id )).' '.'<br>Assigned To: '.$payment_collection->assigned->first_name.' '.$payment_collection->assigned->last_name  );

    $currency           = $this->get_currency();
    $formatted_amount   = format_currency($payment_collection->amount, TRUE, $currency['symbol']);

    // salesmanNotification($payment_collection->staff_user_id,'New Collection Added','Collection Assigned ');
    $member = StaffUser::find($payment_collection->staff_user_id);

    $message = array(
      'message'   => sprintf(__('form.collection_received'),$payment_collection->amount,$payment_collection->name),
      'url'       => route('collection.show',$payment_collection->id),
    );

    $notification = new Notification;
    $notification->id = uniqid();
    $notification->type = get_class($payment_collection);
    $notification->notifiable_type = get_class($member);
    $notification->notifiable_id = $member->id;
    $notification->data = json_encode($message);
    $notification->save();

    $this->duplicatenotification($request->input('staff_user_id'),$title="LTV : Payment Collection",$message="New Collection Assigned");
    return redirect('admin/collection')->with('success', 'Collection Added Successfully!');
  }
  /* Add Collection */

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

   
  public function update(Request $request,$id){
    $rules = array(
           'name' => 'required',
           'mobile_no' => 'required',
            /*'alternate_no' => 'required',*/
            'collection_date' => 'required',
            'amount' => 'required',
            );
    $this->validator($request->all(),$rules)->validate();
    $payment_collection = PaymentCollection::find($id);
    $payment_collection->name = strip_tags($request->input('name'));
    $payment_collection->mobile_no = strip_tags($request->input('mobile_no'));
    $payment_collection->alternate_no = strip_tags($request->input('alternate_no'));
    $payment_collection->collection_date = date("Y-m-d", strtotime($request->input('collection_date')));
    $payment_collection->new_date = date("Y-m-d", strtotime($request->input('collection_date')));
    $payment_collection->amount = strip_tags($request->input('amount'));
    $payment_collection->staff_user_id = strip_tags($request->input('staff_user_id'));
    $payment_collection->status = 0;
    /*$data = array( 
    'name' => strip_tags($request->input('name')),
    'mobile_no' => strip_tags($request->input('mobile_no')),
    'alternate_no' => strip_tags($request->input('alternate_no')),
    'collection_date' => date("Y-m-d", strtotime($request->input('collection_date'))),
    'new_date' => date("Y-m-d", strtotime($request->input('collection_date'))),
    'amount' => strip_tags($request->input('amount')),
    'staff_user_id' => strip_tags($request->input('staff_user_id')),
    );*/
    //$resp = PaymentCollection::where('id',$token)->update($data);
    if($payment_collection->save()){
      $description = sprintf('Collection updated');
      log_activity($payment_collection, $description, anchor_link($payment_collection->name, route('collection.show', $payment_collection->id )).' '.'<br>Assigned: '.$payment_collection->assigned->first_name.' '.$payment_collection->assigned->last_name  );
      return redirect('admin/collection')->with('success','Collection details updated succesfully');
    }else{
        return redirect('admin/collection')->with('error','Something went wrong!!');
    }
  }

  /* Author : 225 */
  public function createPaymentThread(Request $request,$collection_id="",$thread_id=""){
    if($request->collect_payment_checkbox == 1){
      $rules = [ 
        'next_calling_date' => 'required',
        'feedback'          => 'required',
      ];
    }else{
      $rules = [ 
        'next_calling_date' => 'required',
        'feedback'          => 'required',
        'assigned_to'       => 'required'
      ];
    }

    $this->validator($request->all(),$rules)->validate();
    
    $update = PaymentCollection::find($collection_id);

    $thread = new PaymentCollectionDescription;
    $thread->calling_date           = date('Y-m-d H:i:s',strtotime($request->next_calling_date));
    $thread->feedback               = $request->feedback;
    $thread->payment_type           = $request->payment_type;
    $thread->collect_amount         = ($request->amount) ? $request->amount:'0.00';
    $thread->balance_amount         = $update->amount - $request->amount;
    $thread->assigned_to            = ($request->assigned_to)?$request->assigned_to:$update->staff_user_id;
    $thread->status                 = $request->status;
    $thread->payment_collection_id  = $collection_id;
    $thread->save();

    $update->staff_user_id      = ($request->assigned_to)?$request->assigned_to:$update->staff_user_id;
    $update->new_date           = date('Y-m-d',strtotime($request->next_calling_date));
    $update->collected_amount   = $request->amount;
    $update->balance_amount     = $update->amount - $request->amount;
    $update->status             = $request->status;

    $update->counter = (auth()->user()->level == 1)?($update->counter+1):1;

    $update->save();

    $description = sprintf('New Payment Collection Updated');
    log_activity($thread, $description, anchor_link($thread->feedback, route('show_proposal_page', $thread->id ))  );

    session()->flash('success', __('Successfully updated collection'));
    return redirect()->back();

  }
    /* Author : 225 */


    public function destroy($id){
        $product = PaymentCollection::find($id);
        $product->delete();

        return redirect()->back()->with('status', 'New brand deleted successfully.')->with('alert', 'alert-success');
    }

    /* Import */

    public function import_page(Request $request){
      $data = Shopkeeper::dropdown(['assigned_to_list']);
      return view('admin.paymentCollection.import', compact('data'))->with('rec', "");
    }

    public function download_sample_collection_import_file(Request $request){
        $filename = 'sample_collection_import_file';
        $spreadsheet = new Spreadsheet();
        $Excel_writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $spreadsheet->setActiveSheetIndex(0);
        $activeSheet = $spreadsheet->getActiveSheet();
        $columns = PaymentCollection::column_sequence_for_import();
        foreach ($columns as $key=>$name){
            $activeSheet->setCellValue($key.'1' , str_replace("_", " ", ucfirst($name) ))->getStyle($key.'1')->getFont()->setBold(true);
            if($name=='status'){
                $activeSheet->setCellValue($key.'2' , 'Open or Close')->getStyle($key.'2')->getFont()->setBold(true);
            }
            if($name == 'collection_date' || $name == 'calling_date'){
                $activeSheet->setCellValue($key.'2' , 'Date Format YYYY/MM/DD')->getStyle($key.'2')->getFont()->setBold(true);
            }
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); /*-- $filename is  Xlsx filename ---*/
        header('Cache-Control: max-age=0');
        $Excel_writer->save('php://output');
    }

    public function import(Request $request){
        $validator = Validator::make($request->all(), [        
          'file'        => 'required|max:1000|mimes:csv,xlsx',
        ]);
        if ($validator->fails()) {
            return  redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $column_sequence_list = PaymentCollection::column_sequence_for_import();
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
            $update = 0;
            $insert = 0;
            foreach ($worksheet->getRowIterator() as $indexOfRow=>$row){
               if($indexOfRow > 1){             
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,                   
                    // dd($cellIterator);
                    // Get all the columns of the row into $cell array
                    foreach ($cellIterator as $column_key => $cell) 
                    {
                      if(isset($column_sequence_list[$column_key])){
                          $cells[$column_sequence_list[$column_key]] = $cell->getValue();
                      }
                    }
                    if(isset($cells['first_name']) && !$cells['first_name'])
                    {
                        continue;
                    }       
                    $error = $this->validate_customer_data($cells);
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
                            $cells['name']          = $cells['name'];
                            $cells['mobile_no']     = $cells['mobile'];
                            $cells['alternate_no']  = $cells['alternate_number'];
                            $cells['collection_date']  = date('Y-m-d',strtotime($cells['collection_date']));
                            $cells['new_date']         = date('Y-m-d',strtotime($cells['calling_date']));
                            $cells['amount']           = $cells['amount'];
                            $cells['collected_amount'] = $cells['collected_amount'];
                            $cells['balance_amount']   = $cells['balance_amount'];
                            $cells['status']        = (strtolower($cells['status'])=='open')?'0':'1';
                            $cells['staff_user_id'] = ($request->assigned_to)?$request->assigned_to:auth()->user()->id;
                            // Create the Customer
                            /*$check_for_update = Shopkeeper::where('mobile',$cells['mobile'])->first();
                            if(!empty($check_for_update)){
                                $update++;
                                unset($cells['shop_name']);
                                unset($cells['alternate_number']);
                                unset($cells['country']);
                                unset($cells['state']);
                                unset($cells['city']);
                                unset($cells['area']);
                                $customer               = Shopkeeper::where('mobile',$cells['mobile'])->update($cells);
                            }else{*/
                                $insert++;
                                $customer               = PaymentCollection::create($cells);
                            // }
                            
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
                session()->flash('download_file_to_see_unimported_rows', $message);
                session()->flash('message',(__('(Total Collections Inserted: '.$insert.')')));
                return redirect()->route('payment_collection_import_page');
            }else{
                session()->flash('message', __('form.success_add').__('(Total Collections Inserted: '.$insert.')'));
                return redirect()->route('payment_collection_import_page');
            }
        }else{
            session()->flash('message', __('form.invalid_file_provided'));
            return redirect()->route('payment_collection_import_page')->with('danger', __('form.invalid_file_provided'));
        }
    }

    private function validate_customer_data($records){
        $validator = Validator::make($records, [
            'name'             => 'required',
            'mobile'           => 'required|numeric',
            'alternate_number' => 'numeric',
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

    public function duplicatenotification($salesman_id,$title="LTV",$message="Payment Collection Module"){
        $regId = StaffUser::find($salesman_id)->fcm_id;
        // $regId = 'eP835HRmBi8:APA91bEqO9kBmd6raR0Wf6h3rqtzfGmZXAfqpCkS1xCJr6n3HaqlFwwZXazC83ceUGN0G1qCCyMbE7lhTc85pOjEkchPVCIC-MNTN8cM0Ux39ol5FRZo3ahwwOfyYUgBi7WxSABlXpMH';
        $this->sendNotification($regId,$title,$message);
    }
    
    /* Send Firebase Notification */
    public function sendNotification($regId,$title,$message){

        define('FIREBASE_API_KEY', 'AAAAUG7Snkg:APA91bFdUnrMQwY_hJ3mD0MLj_vjCpvlXFBQbuRykSIaSwFnyxv7dd-PNKsIUhWnSX8dxj_zmCgPaG06oqTWms0PtEKX01h5ulNeDB71iqX9HiabOWfA64jlYp5Eq8sMMXm9UfOjKFkN');

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
        // echo $response;
        // exit;
        return true;
    }
    /* Send Firebase Notification */

    function get_currency(){
         // Get the currency iso code and symbol
        if(isset($this->currency->code))
        {
            $data['symbol']    = $this->currency->symbol;
            $data['iso']       = $this->currency->code;
        }
        else
        {
            $currency          = Currency::default()->get()->first();
            $data['symbol']    = $currency->symbol;
            $data['iso']       = $currency->code;
        }

        return $data;        
    }

}
