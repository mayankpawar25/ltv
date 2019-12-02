@extends('admin.layout.master')
@section('title', __('form.customers') .' : '. __('form.import'))
@section('content')
<div class="app-content">
<div class="main-content">
   <div class="row">
      <div class="col-md-6">
         <h5>Import Dealers</h5>
          
      </div>
      <div class="col-md-6">
         <a href="{{ route('download_sample_collection_import_file') }}" class="btn btn-primary btn-sm float-md-right">@lang('form.download_sample')</a>
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

   @if(Session::has('message'))
      <p class="alert {{ Session::get('alert-class', 'alert-success') }}">
         <?php echo Session::get('message'); ?>
      </p>
   @endif
   <div style="font-size: 13px;">
      <p>@lang('form.import_csv_line_1')</p>
      <p>@lang('form.import_csv_line_2')</p>
      <br>
   </div>
   <div class="table-responsive">
      <table class="table table-bordered" >
         <thead>
            <tr>
               <th class="bold">Name  </th>
               <th class="bold">Mobile   </th>
               <th class="bold">Alternate number  </th>
               <th class="bold">Collection date   </th>
               <th class="bold">Calling date   </th>
               <th class="bold">Amount   </th>
               <th class="bold">Collected amount  </th>
               <th class="bold">Balance </th>
               <th class="bold">amount </th>
               <th class="bold">Status</th>
            </tr>
         </thead>
         <tbody>
            <tr>
               @for($i = 1; $i <= 10; $i++)
               @if($i==10)
               <td>Open / Close</td>
               @elseif($i==4 || $i==5)
               <td>Date Format YYYY/MM/DD</td>
               @else
               <td>@lang('form.sample_data')</td>
               @endif
               @endfor                        
            </tr>
         </tbody>
      </table>
      <div style="clear:both;"></div>
   </div>
   <br>
  

   <form method="post" action="{{ route('payment_collection_import') }}" enctype="multipart/form-data">
      {{ csrf_field()  }}
      <div class="form-row">
         <div class="form-group col-md-3">
            <label for="assigned_to">@lang('form.assigned_to')</label>
            <?php echo form_dropdown("assigned_to", $data['assigned_to_list'], old_set("assigned_to", NULL, $rec), "class='form-control form-control-sm selectpicker'") ?>
            <div class="invalid-feedback d-block">@php if($errors->has('assigned_to')) { echo $errors->first('assigned_to') ; } @endphp</div>
         </div>         
         <div class="form-group col-md-3">
            <label>@lang('form.select_file')</label>
            <div class="custom-file">
  <input type="file" class="custom-file-input" id="customFile" name="file">
  <label class="custom-file-label" for="customFile">Choose file</label>
</div>
            <!--<input type="file" class="form-control-file" name="file">-->
            <div class="invalid-feedback d-block">@php if($errors->has('file')) { echo $errors->first('file') ; } @endphp</div>
         </div>

      </div>
      <?php echo bottom_toolbar(__('form.import')); ?>
   </form>
</div>
</div>
<style>
.invalid-feedback {
	font-size:85%;
}

</style>
@endsection