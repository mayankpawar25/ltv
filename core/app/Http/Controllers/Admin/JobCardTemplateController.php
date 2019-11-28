<?php

namespace App\Http\Controllers\Admin;

use App\JobCardTemplate,App\JobCardFeildValue,App\JobCardFormValue,App\JobCardForm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use DB;
class JobCardTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $data['templates'] = JobCardTemplate::get();
        return view('admin.jobcardtemplate.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('admin.jobcardtemplate.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        
        $form_data = [];
        $job_card = new JobCardTemplate;
        $job_card->job_card_name = $request->form_title;
        $job_card->form_data = '[]';
        $job_card->token = $request->_token;
        $job_card->save();

        foreach($request->label as $key => $form){
            $template_field = new JobCardFeildValue();
            $template_field->job_card_template_id = $job_card->id;
            $template_field->label = $form;
            $template_field->slug  = strtolower(str_replace(' ','_',$form));
            $template_field->type  = $request->type[$key];
            $template_field->sort  = $request->sort_order[$key];
            $template_field->save();
        }
        return redirect()->route('admin.jobcardtemplate.index')->with('success','Job Template Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\JobCardTemplate  $jobCardTemplate
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $resp = JobCardTemplate::find($id);
        $resp->templatevalues;
        $data['template'] = $resp;
        return view('admin.jobcardtemplate.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JobCardTemplate  $jobCardTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(JobCardTemplate $jobCardTemplate,$id){
        $resp = JobCardTemplate::find($id);
        $resp->templatevalues;
        $data['template'] = $resp;
        return view('admin.jobcardtemplate.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JobCardTemplate  $jobCardTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){        
        $job_card = JobCardTemplate::find($request->id);
        $job_card->job_card_name = $request->form_title;
        $job_card->form_data = '[]';
        $job_card->token = $request->_token;
        $job_card->save();
        foreach($request->label as $key => $form){
            $template_field = new JobCardFeildValue();
            $template_field->job_card_template_id = $request->id;
            $template_field->label = $form;
            $template_field->slug  = strtolower(str_replace(' ','_',$form));
            $template_field->type  = $request->type[$key];
            $template_field->sort  = $request->sort_order[$key];
            $template_field->save();
        }
        return redirect()->route('admin.jobcardtemplate.index')->with('success','Job Template Update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JobCardTemplate  $jobCardTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $job_card = JobCardTemplate::find($id);
        $job_card->delete();
        return redirect()->route('admin.jobcardtemplate.index')->with('success','Job Template Deleted');
    }

    public function saveJobCardForm(Request $request){
        $form_data = [];

        $current_time = time();
        $path = 'assets/feild/'.$current_time;
        /* Path Create for Uploading */
        if (!file_exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        /* Path Create for Uploading */

        $i = 0;
        foreach ($request->all() as $key => $value) {
            if($key!='_token'){
                if($request->hasFile($key)){
                    $filename = uniqid().rand(1111,9999) .'.'. $value->getClientOriginalExtension();
                    $root =  $path.'/'.$filename;
                    $uploaded  = $value->move($path, $filename);
                    $form_data[$i]['id'] = $i;
                    $form_data[$i]['key'] = $key;
                    $form_data[$i]['value'] = $root;
                }else{
                    $form_data[$i]['id'] = $i;
                    $form_data[$i]['key'] = $key;
                    $form_data[$i]['value'] = $value;
                }
                $i++;
            }
        }
        $new = DB::table('job_card_form_datas')->insert(['data'=>json_encode($form_data)]);
        dd($new);
        exit;
    }
    public function get(){
        
    }
}