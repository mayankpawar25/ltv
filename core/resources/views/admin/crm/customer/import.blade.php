@extends('admin.layout.master')
@section('title', __('form.customers') .' : '. __('form.import'))
@section('content')
<div class="app-content">
<div class="main-content">
   <div class="row">
      <div class="col-md-6">
         <h5>@lang('form.import_customers')</h5>
      </div>
      <div class="col-md-6">
         <a href="{{ route('download_sample_customer_import_file') }}" class="btn btn-primary btn-sm float-md-right">@lang('form.download_sample')</a>
      </div>
   </div>
   <hr>
     @if($validation_errors = session('validation_errors'))
   <div class="alert alert-danger" role="alert">@lang('form.import_failed_message')</div>
   @foreach($validation_errors as $er)
   <div class="text-danger" style="font-size: 13px;">{{ $er }}</div>
   @endforeach
   @endif  
    
   @if(Session::has('download_file_to_see_unimported_rows'))
      <p class="alert {{ Session::get('alert-class', 'alert-info') }}"><?php echo Session::get('download_file_to_see_unimported_rows'); ?></p>
   @endif
   <div style="font-size: 13px;">
      <p>@lang('form.import_csv_line_1')</p>
      <p>@lang('form.import_csv_line_2')</p>
      <br>
   </div>
   <div class="table-responsive">
      <table class="table" style="font-size: 12px; ">
         <thead>
            <tr>
               <th class="bold"><span class="text-danger">*</span> @lang('form.first_name')                  
                  <span class="text-info">@lang('form.contact_field')</span>
               </th>
               <th class="bold"><span class="text-danger">*</span> @lang('form.last_name')          
                  <span class="text-info">@lang('form.contact_field')</span>
               </th>
               <th class="bold"><span class="text-danger">*</span> @lang('form.email')                    
                  <span class="text-info">@lang('form.contact_field')</span>
               </th>
               <th class="bold"> @lang('form.contact_phone')                   
                  <span class="text-info">@lang('form.contact_field')</span>
               </th>
               <th class="bold"> @lang('form.position')                   
                  <span class="text-info">@lang('form.contact_field')</span>
               </th>
               <th class="bold"> <span class="text-danger">* </span>@lang('form.company_name') </th>
               <th class="bold"> @lang('form.phone')</th>
               <th class="bold"> @lang('form.vat')</th>
               <th class="bold"> @lang('form.website')</th>
               <th class="bold"> @lang('form.address')</th>
               <th class="bold"> @lang('form.city')</th>
               <th class="bold"> @lang('form.state')</th>
               <th class="bold"> @lang('form.zip_code')</th>
               <th class="bold"> @lang('form.country')</th>
               <th class="bold">  @lang('form.shipping_address')</th>
               <th class="bold">  @lang('form.shipping_city')</th>
               <th class="bold">  @lang('form.shipping_state')</th>
               <th class="bold">  @lang('form.shipping_zip_code')</th>
               <th class="bold">  @lang('form.shipping_country')</th>
               <!--      <th class="bold">  @lang('form.latitude')</th>
                  <th class="bold">  @lang('form.longitude')</th>            
                  <th class="bold">  @lang('form.stripe_id')</th> -->
            </tr>
         </thead>
         <tbody>
            <tr>
               @for($i = 1; $i <= 19; $i++)
               <td>@lang('form.sample_data')</td>
               @endfor                        
            </tr>
         </tbody>
      </table>
      <div style="clear:both;"></div>
   </div>
   
   <form method="post" action='' enctype="multipart/form-data">
      {{ csrf_field()  }}
      <div class="form-row">
        
       <!--   <div class="form-group col-md-4">
            <label for="group_id">@lang('form.group_id')</label>
            <div class="select2-wrapper">
               <?php //echo form_dropdown("group_id[]", $data['group_id_list'], old_set("group_id", NULL, $rec), "class='form-control form-control-sm select2-multiple' multiple='multiple'") ?>
            </div>
            <div class="invalid-feedback">@php if($errors->has('group_id')) { echo $errors->first('group_id') ; } @endphp</div>
         </div>  -->
       
         <div class="form-group col-md-4">
            <label>@lang('form.password') </label>
            <input type="password" class="form-control " name="password">
            <div class="invalid-feedback">@php if($errors->has('password')) { echo $errors->first('password') ; } @endphp
            </div>
         </div>
          <div class="form-group col-md-4">
            <label for="assigned_to">@lang('form.assigned_to')</label>
            <?php echo form_dropdown("assigned_to", $data['assigned_to_list'], old_set("assigned_to", NULL, $rec), "class='form-control form-control-sm selectpicker'") ?>
            <div class="invalid-feedback d-block">@php if($errors->has('assigned_to')) { echo $errors->first('assigned_to') ; } @endphp</div>
         </div>
         <div class="col-md-4">
         <div class="form-group">
         <label>@lang('form.select_file')</label>
         <div class="custom-file">
  <input type="file" class="custom-file-input" id="customFile" name="file">
  <label class="custom-file-label" for="customFile">Choose file</label>
</div>
         <!--<input type="file" class="form-control-file" >-->
         <div class="invalid-feedback d-block">@php if($errors->has('file')) { echo $errors->first('file') ; } @endphp</div>
      </div>
         </div>
      </div>
      
      <?php echo bottom_toolbar(__('form.import')); ?>
   </form>
</div>
</div>
@endsection