<?php
/* To Do List */
namespace App\Http\Controllers\Admin;
use App\Task;
use App\Zipcode;
use App\Vendor;
use App\Models\StaffUser;
use App\Shopkeeper,App\Lead,App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
      $tasks =Task::orderBy('created_at', 'ASC')->get();
      return view('admin.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
      return view('admin.tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
      $validatedRequest = $request->validate([
        'name' => 'required',
        'description' => 'required',
        'task_date'=> 'required',
        'to_time' => 'required',
        'from_time'=> 'required',
        'salesman_id' => 'required',
        'client_type_id' => 'required',
        'client_id' => 'required',
      ]);
        
      $arra = array();
      $task               = new Task();
      $task->name         = $request->name;
      $task->description  = $request->description;
      $task->task_date    = date('Y-m-d',strtotime($request->task_date));
      $task->to_time      = $request->to_time;
      $task->from_time    = $request->from_time;
      $task->salesman_id  = $request->salesman_id;
      $task->client_type_id= $request->client_type_id;
      $task->client_id    = $request->client_id;
      $save               = $task->save();

      $members = StaffUser::where('id',$task->salesman_id)->first();
      $title = "New Task Assigned";
      $message = 'Date '.$request->task_date.' Time '.$task->from_time.'-'.$task->to_time
      ;
      sendNotification($members->fcm_id,$title,$message);
      //Success and Error Message 
      $message = [];
      if(!$save){
          $message = [
              'error' => 'Something is wrong, Task could not added.'
          ];
     }else {
           $message = [
              'success' => 'New Task added successfully.'
          ];
      }
      return redirect()->route('admin.tasks.salesmanlist')->with($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,$type_id,$client_id){ 
        if($type_id == 1){
            $data['show'] = DB::table('tasks As t')
                          ->where('t.id',$id)
                          ->where('shopkeepers.id',$client_id)
                          ->join('shopkeepers', 't.salesman_id', '=', 'shopkeepers.salesman_id')
                          ->join('staff_users', 't.salesman_id', '=', 'staff_users.id')
                          ->join('task_statuses', 't.task_status_id', '=', 'task_statuses.id')
                          ->select('t.id', 't.name','t.description','t.task_date','t.to_time','t.from_time','t.salesman_id','t.client_type_id','t.client_id','t.task_status_id','task_statuses.name as task_status_name','shopkeepers.name as client_name','shopkeepers.shopname as shop_name','staff_users.first_name as salesman_first_name','staff_users.last_name as salesman_last_name')
                          ->first();
        }else if($type_id == 2){ 
             $data['show'] = DB::table('tasks As t')
                          ->where('t.id',$id)
                          ->where('leads.id',$client_id)
                          ->join('leads', 't.salesman_id', '=', 'leads.assigned_to')
                          ->join('staff_users', 't.salesman_id', '=', 'staff_users.id')
                          ->join('task_statuses', 't.task_status_id', '=', 'task_statuses.id')
                          ->select('t.id', 't.name','t.description','t.task_date','t.to_time','t.from_time','t.salesman_id','t.client_type_id','t.client_id','t.task_status_id','task_statuses.name as task_status_name','leads.first_name as client_name','leads.last_name as client_last_name','staff_users.first_name as salesman_first_name','staff_users.last_name as salesman_last_name')
                          ->first();
        }else {
          $data['show'] = DB::table('tasks As t')
                         ->where('t.id',$id)
                         ->where('users.id',$client_id)
                         ->join('users', 't.salesman_id', '=', 'users.assigned_to')
                         ->join('staff_users', 't.salesman_id', '=', 'staff_users.id')
                         ->join('task_statuses', 't.task_status_id', '=', 'task_statuses.id')
                         ->select('t.id', 't.name','t.task_status_id','task_statuses.name as task_status_name','t.description','t.task_date','t.to_time','t.from_time','t.salesman_id','t.client_type_id','t.client_id','users.first_name as client_name','users.last_name as client_last_name','staff_users.first_name as salesman_first_name','staff_users.last_name as salesman_last_name')
                         ->first();
        }
        /*die('test');*/
                  /*print_r($data);  
                  die; */
       return view('admin/tasks/show',$data); 
          }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
      $data['task'] = Task::find($id);
      return view('admin/tasks/edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){   
      $task               = Task::find($request->id);
      $task->name         = $request->name;
      $task->description  = $request->description;
      $task->task_date    =  date('Y-m-d',strtotime($request->task_date));;
      $task->from_time    = $request->from_time;
      $task->to_time      = $request->to_time;
      $task->salesman_id  = $request->salesman_id;
      $task->client_type_id = $request->client_type_id;
      $task->client_id    = $request->client_id;
      
      if($task->save()){
          return redirect()->route('admin.tasks.salesmanlist')->with('success','Update Task Successfully');
      }else{
          return redirect()->route('admin.tasks.salesmanlist',$request->id)->with('danger','Nothing to Update');
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function salesmanList2(){   
      $data['assigned_to'] = DB::table('staff_users')->where('role_id',1)->select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')->pluck('name', 'id')->toArray();
      if(empty(auth()->user()->is_administrator)){
          return view('admin.tasks.index', compact('data'));
      }else {
          $data['salesmans'] =StaffUser::whereNull('inactive')->whereNull('is_administrator')->where('role_id',1)->get();
          return view('admin.tasks.salesmanlist',$data);
      }
    }

    public function salesmanList(){
      $data['assigned_to'] = DB::table('staff_users')->where('role_id',1)->select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')->pluck('name', 'id')->toArray();
      if(empty(auth()->user()->is_administrator)){
          /*$data['salesmans'] = StaffUser::where('id',auth()->user()->id)->where('role_id',1)->get();
              return view('admin.tasks.salesmanlist',$data);*/
          $tasks =Task::where('salesman_id',auth()->user()->id)->orderBy('created_at', 'ASC')->get();

              return view('admin.tasks.index', compact('tasks'));
      }else {
              $data['salesmans'] =StaffUser::whereNull('inactive')->whereNull('is_administrator')->where('role_id',1)->get();
              return view('admin.tasks.salesmanlist',$data);
      }
    }

    public function salesmanTasklist($id){
        $tasks = Task::where('salesman_id',$id)->orderBy('created_at', 'ASC')->get();
        return view('admin.tasks.index', compact('tasks'));
    }

    public function myarrivals(Request $request,$id){
        $data['id'] = $id;
        $data['tasks'] = Task::where('salesman_id',$id)->get();
        return view('admin.tasks.mapview', compact('data'));
    }

    public function jsonView(Request $request){
      $date = date('Y-m-d');
      if($request->date!='Y-m-d'){
        $date = date('Y-m-d',strtotime($request->date));
      }
      $tasks = Task::where('salesman_id',$request->id)->where('task_date',$date)->get();
      foreach ($tasks as $t_key => $task) {
        if($task->client_type_id == 1){ // shopkeepers = 1 
            $client = Shopkeeper::find($task->client_id);
            $task->client_type = 'Shopkeeper';
            $task->client_name = $client->name.' ('.$client->shopname.')';
            $task->address = $client->address;
        }else if($task->client_type_id == 2){  // Leads = 2
            $client = Lead::find($task->client_id);
            $task->client_type = 'Lead';
            $task->client_name = $client->first_name.' '.$client->last_name.' ('.$client->company.')';
            $task->address = $client->address;
        }else if($task->client_type_id == 3){  // Users = 3
            $client = User::find($task->client_id);
            $task->client_type = 'User';
            $task->client_name = $client->first_name.' '.$client->last_name;
            $task->address = $client->address;
        }
      }
      echo json_encode($tasks);
    }

    public function paginate(Request $request){

        $order       = Input::get('order');
        $columns     = Input::get('columns');
        $query_key   = Input::get('search');
        $search_key  = $query_key['value'];
        $customer_id = Input::get('customer_id');
        $salesman_id = Input::get('salesman_id');
        $is_verified = Input::get('is_verified');
        $groups      = Input::get('groups');
        $q           = StaffUser::query();
        $query       = StaffUser::where('role_id',1)->orderBy($columns[$order[0]['column']]['name'], $order[0]['dir']);

        // If the user has permission to view only the ones that are created by himself;
        if(!check_perm('tasks_view') && check_perm('tasks_view_own'))
        {
            $q->where(function($k){
                $k->where('salesman_id', auth()->user()->id);
            });
            $query->where(function($k){
                $k->where('salesman_id', auth()->user()->id);
            });                   
            
        }

        if($salesman_id){
            $q->whereIn('id', $salesman_id);
            $query->whereIn('id', $salesman_id);
        }

        $number_of_records  = $q->where('role_id',1)->get()->count();

        if($search_key)
        {
            $query->where(function ($k) use ($search_key) {
                $k->where('first_name', 'like', $search_key.'%')
                ->orwhere('last_name', 'like', $search_key.'%')
                ->orwhere(DB::raw('CONCAT(first_name," ",last_name)'), 'like', $search_key.'%')
                ->orwhere('phone', 'like', $search_key.'%')
                ->orwhere('email', 'like', $search_key.'%');
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
            $i=0;
            foreach ($data as $key => $row)
            {  
              $rec[] = array(
                ++$i,
                ucwords($row->first_name).' '.ucwords($row->last_name),
                $row->email,
                $row->phone,
                anchor_link('<button class="btn btn-primary btn-sm" title="View Task"><i class="fa fa-eye"></i></button>',route('admin.salesmans.task',$row->id)).' '.anchor_link('<button class="btn btn-success btn-sm" title="Map"><i class="fa fa-map"></i></button>',route('admin.tasks.arrivals',$row->id),TRUE),
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

}
