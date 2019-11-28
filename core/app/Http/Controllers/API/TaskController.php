<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Task,App\Models\StaffUser,App\Shopkeeper,App\TaskStatus;
use App\Lead;
use App\User;
use Spatie\Activitylog\Models\Activity;
use Validator;
use DB;

class TaskController extends Controller
{   
    public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $datequery = [];
        if(isset($request->year)){
            $datequery['year'] = "YEAR(task_date) = '".$request->year."'";
        }
        if(isset($request->month)){
            $datequery['month'] = "MONTH(task_date) = '".$request->month."'";
        }
        if(isset($request->day)){
            $datequery['day'] = "DAY(task_date) = '".$request->day."'";
        }
        
        $date_where = 'WHERE';
        if(!empty($datequery)){
            $date_where = "WHERE " .implode(' && ', $datequery)." AND ";
        }

        // $date = $request->year.'-'.$request->month.'-'.$request->day;
        // $task = Task::where($date_where)->get();
        $tasks = DB::select("SELECT * FROM `tasks` ".$date_where." `salesman_id`=".Auth::id()." ORDER BY from_time ASC");

        if(!empty($tasks)){

            foreach ($tasks as $key => $task) {
                $staff = StaffUser::find($task->salesman_id);
                $task->salesman = $staff->first_name.' '.$staff->last_name;
                    
                if($task->client_type_id == 1){ // Shopkeepers
                    $client = Shopkeeper::find($task->client_id);
                    $task->shopkeeper_name = $client->name;
                    $task->shopkeeper_mobile = $client->mobile;
                    $task->shopkeeper_address = $client->address;
                    $task->client_type = 'Shopkeeper';
                }elseif($task->client_type_id == 2){ // Leads
                    $lead = Lead::find($task->client_id);
                    $task->shopkeeper_name = $lead->first_name.' '.$lead->last_name;
                    $task->shopkeeper_mobile = $lead->phone;
                    $task->shopkeeper_address = $lead->address;
                    $task->client_type = 'Lead';
                }elseif($task->client_type_id == 3){ // Customers
                    $customer = User::find($task->client_id);
                    $task->shopkeeper_name = $customer->name;
                    $task->shopkeeper_mobile = $customer->mobile;
                    $task->shopkeeper_address = $customer->address;
                    $task->client_type = 'Customer';
                }
                $task->status_name = TaskStatus::find($task->task_status_id)->name;
            }

            $data['task_list'] = $tasks;
            $data['msg'] = 'Task List';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['task_list'] = $tasks;
            $data['msg'] = 'No Task Available';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $validator = Validator::make($request->all(), [ 
            'title'          => 'required',
            'description'   => 'required',
            'salesman_id'   => 'required',
            'client_type'   => 'required',
            'client_id'     => 'required',
            'from_time'     => 'required',
            'to_time'       => 'required',
            'date'     => 'required',
        ]);
        
        if ($validator->fails()) { 
            return response()->json($validator->errors(), 401);            
        }

        $task = new Task;
        $task->name = $request->title;
        $task->description = $request->description;
        $task->salesman_id = $request->salesman_id;
        $task->client_type_id = $request->client_type;
        $task->client_id = $request->client_id;
        $task->from_time = $request->from_time;
        $task->to_time = $request->to_time;
        $task->task_date = $request->date;
        $task->save();

        if($task->id!=''){
            $data['msg'] = 'Task Added successfully';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['msg'] = 'No Task Added';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id=""){

        $validator = Validator::make($request->all(), [ 
            'title'          => 'required',
            'description'   => 'required',
            'salesman_id'   => 'required',
            'client_type'   => 'required',
            'client_id'     => 'required',
            'from_time'     => 'required',
            'to_time'       => 'required',
            'date'     => 'required',
        ]);
        
        if ($validator->fails()) { 
            return response()->json($validator->errors(), 401);            
        }

        $task = Task::find($request->task_id);
        $task->name = $request->title;
        $task->description = $request->description;
        $task->salesman_id = $request->salesman_id;
        $task->client_type_id = $request->client_type;
        $task->client_id = $request->client_id;
        $task->from_time = $request->from_time;
        $task->to_time = $request->to_time;
        $task->task_date = $request->date;
        $task->save();

        if($task->id!=''){
            $data['msg'] = 'Task update successfully';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['msg'] = 'No Task Updated';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status); 
    }

    public function taskStatus(Request $request){
        $taskStatus = TaskStatus::get();
        if(!$taskStatus->isEmpty()){
            $data['task_status'] = $taskStatus;
            $data['msg'] = 'Task update successfully';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['task_status'] = [];
            $data['msg'] = 'No Task Updated';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status); 
    }
    
    public function updateTaskStatus(Request $request){
        $task = Task::find($request->task_id);
        $task->task_status_id = $request->task_status;
        if($task->save()){
            $data['msg'] = 'Task Status update successfully';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['msg'] = 'No Task Status Updated';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status); 
    }

    public function taskArrival(Request $request,$task_id=""){
        $task = Task::find($task_id);
        if(!empty($task)){
            $task->latitude = $request->latitude;
            $task->longitude = $request->longitude;
            $task->save();
            $data['msg'] = 'Task Status update successfully';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['msg'] = 'No Task Status Updated';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status); 
    }
    /*Testing Notification*/
       public  function activity_log_paginate(){
        $query              = Activity::where('causer_id',Auth::user()->id)->orderBy('id', 'DESC');
        $data = $query->get();
        
        $rec = array();
        if (count($data) > 0)
        {
            foreach ($data as $key => $row)
            {
                $causer = $row->causer()->withTrashed()->get()->first();
                $rec[ $key]['time'] =    \Carbon\Carbon::parse($row->created_at)->diffForHumans();
                //$rec[ $key]['name'] =    $causer->first_name . " " . $causer->last_name ;
                $rec[ $key]['description'] =   strip_tags($row->description) . " ". strip_tags($row->getExtraProperty('item'));
            }
            $output = array(
                "notificationlist" => $rec,
                "msg"=>'Notification List',
                "status" => true
            );
            $status = $this-> successStatus;
        }else{
             $output = array(
                "notificationlist" => $rec,
                "msg"=>'No Notification Found',
                "status" => false
            );
            $status = 401;
        }
        return response()->json($output,$status);
    }
     /*Testing Notification*/
}