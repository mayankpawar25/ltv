<?php
/* To Do List */
namespace App\Http\Controllers\Admin;
use App\Task;
use App\Zipcode;
use App\Vendor;
use App\Models\StaffUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$tasks = Task::all();
        $tasks =Task::orderBy('created_at', 'ASC')->get();
       /* print_r( $tasks);
        die;*/
        return view('admin.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        $task->task_date    = $request->task_date;
        $task->to_time      = $request->to_time;
        $task->from_time    = $request->from_time;
        $task->salesman_id  = $request->salesman_id;
        $task->client_type_id= $request->client_type_id;
        $task->client_id    = $request->client_id;
        $save               = $task->save();

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
    public function show($id,$type_id,$client_id)
    {
        if($type_id == 1){
            $data['show'] = DB::table('tasks As t')
                          ->where('t.id',$id)
                          ->where('shopkeepers.id',$client_id)
                          ->join('shopkeepers', 't.salesman_id', '=', 'shopkeepers.salesman_id')
                          ->join('staff_users', 't.salesman_id', '=', 'staff_users.id')
                          ->select('t.id', 't.name','t.description','t.task_date','t.to_time','t.from_time','t.salesman_id','t.client_type_id','t.client_id','shopkeepers.name as client_name','shopkeepers.shopname as shop_name','staff_users.first_name as salesman_first_name','staff_users.last_name as salesman_last_name')
                          ->first();
        }else if($type_id == 2){ 
             $data['show'] = DB::table('tasks As t')
                          ->where('t.id',$id)
                          ->where('leads.id',$client_id)
                          ->join('leads', 't.salesman_id', '=', 'leads.assigned_to')
                          ->join('staff_users', 't.salesman_id', '=', 'staff_users.id')
                          ->select('t.id', 't.name','t.description','t.task_date','t.to_time','t.from_time','t.salesman_id','t.client_type_id','t.client_id','leads.first_name as client_name','leads.last_name as client_last_name','staff_users.first_name as salesman_first_name','staff_users.last_name as salesman_last_name')
                          ->first();
         }else {
           $data['show'] = DB::table('tasks As t')
                         ->where('t.id',$id)
                         ->where('users.id',$client_id)
                         ->join('users', 't.salesman_id', '=', 'users.assigned_to')
                         ->join('staff_users', 't.salesman_id', '=', 'staff_users.id')
                         ->select('t.id', 't.name','t.description','t.task_date','t.to_time','t.from_time','t.salesman_id','t.client_type_id','t.client_id','users.first_name as client_name','users.last_name as client_last_name','staff_users.first_name as salesman_first_name','staff_users.last_name as salesman_last_name')
                         ->first();

                        
        }
                     
       return view('admin/tasks/show',$data);    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
    public function update(Request $request)
    {   
        $task               = Task::find($request->id);
        $task->name         = $request->name;
        $task->description  = $request->description;
        $task->task_date    = $request->task_date;
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

    public function salesmanList()
    {   
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

    public function myarrivals(Request $request){
        $tasks = Task::where('salesman_id',auth()->user()->id)->get();
        return view('admin.tasks.mapview', compact('tasks'));
    }

    public function jsonView(){
        $tasks = Task::where('salesman_id',18)->where('task_date',date('Y-m-d'))->get();
        echo json_encode($tasks);
    }


}
