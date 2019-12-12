<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Lead;
use App\LeadStatus;
use App\LeadSource;
use App\Note;
use App\Tag;
use App\Models\StaffUser;
use Validator;

class LeadController extends Controller
{
    /*Lead Management*/

    public $successStatus = 200;
    /**
     * Display a listing Leads.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $user = Auth::guard('salesman')->user();
        $salesman = StaffUser::where('id', Auth::id())->where('role_id', 1)->first();
        if(!empty($request->lead_id)){
            $Leads = Lead::where('assigned_to',$salesman->id )->where('id',$request->lead_id)->whereNULL('dealer_id')->whereNULL('customer_id')->orderby('id','DESC')->get();
            if(!$Leads->isEmpty()){
                $data['Leads'] = $Leads;
                $data['msg'] = 'Salesman Leads List';
                $data['status'] = true;
                $status = $this-> successStatus;
            }else{
                $data['Leads'] = [];
                $data['msg'] = 'No Leads Found';
                $data['status'] = false;
                $status = 401;
            }
        }else {
            $Leads = Lead::where('assigned_to',$salesman->id )->orderby('id','DESC')->get();
            if(!$Leads->isEmpty()){
                $data['Leads'] = $Leads;
                $data['msg'] = 'Salesman Leads List';
                $data['status'] = true;
                $status = $this-> successStatus;
            }else{
                $data['Leads'] = [];
                $data['msg'] = 'No Leads Found';
                $data['status'] = false;
                $status = 401;
            }
        }
        
        return response()->json($data, $status); 
    }

    /**
     * Lead Status List.
     */
    public function leadStatuslist(Request $request)
    {
        if(!empty($request->status_id)){
             $Lead_status = LeadStatus::whereNull('deleted_at')->where('id',$request->status_id)->orderby('id','ASC')->get();
            if(!$Lead_status->isEmpty()){
                $data['Lead_status'] = $Lead_status;
                $data['msg'] = 'Lead Status List';
                $data['status'] = true;
                $status = $this-> successStatus;
            }else{
                $data['Lead_status'] = [];
                $data['msg'] = 'No Lead Status Found';
                $data['status'] = false;
                $status = 401;
            }
        }else {
            $Lead_status = LeadStatus::whereNull('deleted_at')->orderby('id','ASC')->get();
            if(!$Lead_status->isEmpty()){
                $data['Lead_status'] = $Lead_status;
                $data['msg'] = 'Lead Status List';
                $data['status'] = true;
                $status = $this-> successStatus;
            }else{
                $data['Lead_status'] = [];
                $data['msg'] = 'No Lead Status Found';
                $data['status'] = false;
                $status = 401;
            }
        }
        
        return response()->json($data, $status); 
    }

    /**
     * Lead Status List.
     */
    public function leadSourceslist(Request $request)
    {
        if(!empty($request->source_id)){
            $Lead_sources = LeadSource::whereNull('deleted_at')->where('id',$request->source_id)->orderby('id','ASC')->get();
            if(!$Lead_sources->isEmpty()){
                $data['Lead_sources'] = $Lead_sources;
                $data['msg'] = 'Lead Sources List';
                $data['status'] = true;
                $status = $this-> successStatus;
            }else{
                $data['Lead_sources'] = [];
                $data['msg'] = 'No Lead Sources Found';
                $data['status'] = false;
                $status = 401;
            }
        }else {
            $Lead_sources = LeadSource::whereNull('deleted_at')->orderby('id','ASC')->get();
            if(!$Lead_sources->isEmpty()){
                $data['Lead_sources'] = $Lead_sources;
                $data['msg'] = 'Lead Sources List';
                $data['status'] = true;
                $status = $this-> successStatus;
            }else{
                $data['Lead_sources'] = [];
                $data['msg'] = 'No Lead Sources Found';
                $data['status'] = false;
                $status = 401;
            }
        }
       
        return response()->json($data, $status); 
    }

     /**
     * Tags List.
     */
    public function tagList()
    {
        $tags = Tag::whereNull('deleted_at')->orderby('id','ASC')->get();
        if(!$tags->isEmpty()){
            $data['tags'] = $tags;
            $data['msg'] = 'Tags List';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['tags'] = [];
            $data['msg'] = 'No Tags Found';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status); 
    }


    /**
     * Add/Create Lead.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

       // 
    }

    /**
     * Add/Create Lead..
     */
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'lead_status_id' => 'required',
            'lead_source_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email'     => 'nullable|email|unique:leads'
        ]);

        if ($validator->fails()) { 
            $data['msg'] = 'Please fill All Mandatory Fields Or Email Id Already Register';
            $data['status'] = false;
            $status = 401;
            return response()->json($data,$status);         
           }

        // Saving Data
        $request['created_by']      = Auth::id();
        $request['assigned_to']     = Auth::id();        
        $obj                        = Lead::create($request->all());     
        $obj->tag_attach($request->tag_id);
         if(isset($obj)){
            $data['msg'] = 'Lead Generate Successfully';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['msg'] = 'Something went wrong';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status);      
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
    public function edit($id)
    {
        //
    }

    /**
     * Edit/Update Lead.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_status_id'   => 'required',
            'lead_source_id'   => 'required',
            'first_name' => 'required',
            'last_name' => 'required',          
            /*'email' => [
                'nullable',
                'email',
                'email'     => 'nullable|email|unique:leads'
                ],*/
            ]);

        if ($validator->fails()) { 
            $data['msg'] = 'Please fill All Mandatory Fields';
            $data['status'] = false;
            $status = 401;
            return response()->json($data,$status);            
        }
        // Saving Data
        $obj = Lead::withTrashed()->find($request->lead_id);

        if($obj)
        {
            $obj->update($request->all());  
            $obj->tag_sync($request->tag_id);
            $data['msg'] = 'Update Lead Successfully';
            $data['status'] = true;
            $status = $this-> successStatus;
           
        }
        else
        {
            $data['msg'] = 'Something went wrong';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status);      
    }

    /**
     * Add Note/ Lead FeedBack.
     */
    function add_note(Request $request)
    {
       
        $lead = Lead::withTrashed()->find($request->lead_id);

        if(!$lead)
        {
            $data['msg'] = 'Please Enter Valid Lead Id ';
            $data['status'] = false;
            $status = 401;
            return response()->json($data, $status);         
        }
       
        $validator = Validator::make($request->all(), [            
            'details'                   => 'required',             

        ]);

        if ($validator->fails()) 
        {
            return response()->json($validator->errors(), 401);       
        }


        DB::beginTransaction();
        $success = false;

        try {        
            
            $note           = new Note();
            $note->body     = Input::get('details');
            $note->user_id  = Auth::id();              
            $lead->notes()->save($note);

            DB::commit();
            $success = true;
        } 
        catch (\Exception  $e) {
            
            $success = false;
            DB::rollback();
        }

        if ($success)
        {
            $data['msg'] = 'Add Lead FeedBack Successfully';
            $data['status'] = true;
            $status = $this-> successStatus;
        } 
        else 
        {
            $data['msg'] = 'Something went wrong';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status);      
    }

     /**
     *  Lead FeedBack List.
     */
    public function leadFeedbackList(Request $request)
    {
        $user = Auth::guard('salesman')->user();

        $feedback = Note::where('user_id',Auth::id())->where('noteable_id',$request->lead_id)->orderby('id','DESC')->get();
        if(!$feedback->isEmpty()){
            $data['feedback'] = $feedback;
            $data['msg'] = 'Lead Feedback List';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['feedback'] = [];
            $data['msg'] = 'No Feedback Found';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status); 
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

   
}
