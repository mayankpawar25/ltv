<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;  
use App\Country;
use App\State;
use App\City,App\Zipcode;
use Validator;
use Hash;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Input;

use \Yajra\Datatables\Datatables;
class AreaController extends Controller
{
     protected function validator(array $data,array $rules){
        return Validator::make($data, $rules);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        $data['country'] = array(''=>'Select Country') + Country::get()->pluck('name', 'id')->toArray();
        $data['state'] = State::get()->pluck('name', 'id')->toArray();
        $data['city'] = City::get()->pluck('name', 'id')->toArray();
        // dd($data);
        return view('admin.area.index',$data);
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $rules = array(
            'country' => 'required',
            'state'   => 'required',
            'city'    => 'required',
            'name'    => 'required',
        );
        $this->validator($request->all(),$rules)->validate();
        $area = new Zipcode;
        $area->area_name = $request->name;
        $area->country_id = $request->country;
        $area->state_id = $request->state;
        $area->city_id = $request->city;
        $area->status = 1;
        $area->save();
        return redirect()->route('area.index')->with('success', 'Area Added Successfully!');
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        // Edit Area
        $data['country'] = array(''=>'Select Country') + Country::get()->pluck('name', 'id')->toArray();
        $data['area']  = Zipcode::find($id);
        return view('admin.area.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $rules = array(
            'country' => 'required',
            'state'   => 'required',
            'city'    => 'required',
            'name'    => 'required',
        );
        $this->validator($request->all(),$rules)->validate();
        $area = Zipcode::find($id);
        $area->area_name = $request->name;
        $area->country_id = $request->country;
        $area->state_id = $request->state;
        $area->city_id = $request->city;
        $area->status = 1;
        $area->save();
        return redirect()->route('area.index')->with('success', 'Area Added Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $area = Zipcode::find($id);
        $area->delete();
        return redirect()->route('area.index')->with('success', 'Area Deleted Successfully!');

    }
    
    /*Update Country Status (Active / Inactive)*/
    public function updatestatus($id,$status){
        if($status == 0)
        {
            $data = array( 
                   'status' => 1, 
               );
        }else {
             $data = array( 
                   'status' => 0, 
               );
        }
        Country::where('id',$id)->update($data);
    }

    public function paginate(){
        /*echo "<pre>";
        echo print_r(Input::get('order'));
        exit;*/
        $order       = Input::get('order');
        $columns     = Input::get('columns');
        $query_key   = Input::get('search');
        $search_key  = $query_key['value'];
        $customer_id = Input::get('customer_id');
        $status_id   = Input::get('status_id');
        $is_verified = Input::get('is_verified');
        $groups      = Input::get('groups');
        $q           = Zipcode::query();
        $query       = Zipcode::orderBy($columns[$order[0]['column']]['name'], $order[0]['dir']);
        $number_of_records  = $q->get()->count();

        if($search_key)
        {
            $query->where(function ($k) use ($search_key) {
                $k->where('area_name', 'like', $search_key.'%');
                /*->orwhereHas('city',function ($q) use ($search_key){
                $q->leftJoin('zipcodes', 'zipcodes.city_id', '=', 'cities.id')
                        ->where('cities.name', 'like', $search_key.'%');
                
                });*/
            });
        }

        $recordsFiltered = $query->get()->count();
        $query->skip(Input::get('start'))->take(Input::get('length'));
        $data = $query->get();
        //

        $rec = [];
        // echo json_encode($data);
        // exit;
        if (count($data) > 0)
        {   $i = 0;
            foreach ($data as $key => $row)
            {   
                $rec[] = array(
                    // ++$i,
                    a_links(anchor_link($row->area_name,route('#',$row->id)), [
                        [
                            'action_link' => route('area.edit', $row->id), 
                            'action_text' => __('form.edit'), 'action_class' => '',
                            'permission' => 'area_edit',
                        ],
                        [
                            'action_link' => route('area.delete', $row->id), 
                            'action_text' => __('form.delete'), 'action_class' => 'delete_item',
                            'permission' => 'area_delete',
                        ]
                    ]),
                    $row->city->state->country->name,
                    $row->city->state->name,
                    $row->city->name,
                    ($row->status==0)?'<span class="badge badge-warning">Inactive</span>':'<span class="badge badge-success">Active</span>'
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

    public function import_page(){
        return view('admin.area.import');
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
        $column_sequence_list = Zipcode::column_sequence_for_import();
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
            foreach ($worksheet->getRowIterator() as $indexOfRow=>$row){
               if($indexOfRow > 1){             
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,                   
                    $cells = [];
                    // Get all the columns of the row into $cell array
                    foreach ($cellIterator as $column_key => $cell) 
                    {
                        if(isset($column_sequence_list[$column_key]))
                        {
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

                            $cells['area_name']  = $cells['area_name'];
                            $cells['status']     = 1;
                            $cells['approved']   = 1;
                            $cells['created_by'] =  auth()->user()->id;                
                            if($cells['country'])
                            {                       
                                $country = Country::firstOrCreate(['name' => $cells['country'] ]);
                                $cells['country_id']            = $country->id;
                            }
                            if($cells['state'])
                            {                       
                                $state = State::firstOrCreate(['name' => $cells['state'] ]);
                                $cells['state_id']   = $state->id;
                            }
                            if($cells['city'])
                            {                       
                                $city = City::firstOrCreate(['name' => $cells['city'] ]);
                                $cells['city_id']   = $city->id;
                            }                            
                            // Create the Customer

                            $customer  = Zipcode::create($cells);
                            
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
                dd($download_link);
                $message = sprintf(__('form.import_download_file_message'), anchor_link(__('form.file'), $download_link));
                session()->flash('download_file_to_see_unimported_rows', $message);   
                return redirect()->route('area.import_page')->with('danger', $message);
            }else{
                session()->flash('message', __('form.success_add'));
                return redirect()->route('area.import_page')->with('success', __('form.success_add'));
            }
        }else{
            session()->flash('message', __('form.invalid_file_provided'));
            return redirect()->route('area.import_page')->with('danger', __('form.invalid_file_provided'));
        }
    }
    public function download_sample_area_import_file(){
        $filename = 'sample_area_import_file';
        $spreadsheet = new Spreadsheet();
        $Excel_writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $spreadsheet->setActiveSheetIndex(0);
        $activeSheet = $spreadsheet->getActiveSheet();
        $columns = Zipcode::column_sequence_for_import();
        foreach ($columns as $key=>$name){
            $activeSheet->setCellValue($key.'1' , str_replace("_", " ", ucfirst($name) ))->getStyle($key.'1')->getFont()->setBold(true);
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); /*-- $filename is  Xlsx filename ---*/
        header('Cache-Control: max-age=0');
        $Excel_writer->save('php://output');
    }

    private function validate_customer_data($records){
        $validator = Validator::make($records, [
            'area_name' => 'required',
            'country'   => 'required',
            'state'     => 'required',
            'city'      => 'required',
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
        foreach ($column_sequence_list as $key=>$value) 
        {
             $spreadsheet->getActiveSheet()->setCellValue($key.$row_number , NULL);
            
        } 
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);
    }

}