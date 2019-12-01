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
         <a href="{{ route('download_sample_dealer_import_file') }}" class="btn btn-success btn-sm float-md-right">@lang('form.download_sample')</a>
      </div>
   </div>
   <hr>
   <div style="font-size: 13px;">
      <p>@lang('form.import_csv_line_1')</p>
      <p>@lang('form.import_csv_line_2')</p>
      <br>
   </div>
   <div class="table-responsive">
      <table class="table" style="font-size: 12px; ">
         <thead>
            <tr>
               <th class="bold"> 
                  Name
               </th>
               <th class="bold">
                  Shop Name
               </th>
               <th class="bold">
                  Email
               </th>
               <th class="bold">
                  Contact Number
               </th>
               <th class="bold">
                  Alternate Contact Number
               </th>
               <th class="bold"> @lang('form.address')</th>
               <th class="bold"> @lang('form.country')</th>
               <th class="bold"> @lang('form.state')</th>
               <th class="bold"> @lang('form.city')</th>
               <th class="bold"> Area</th>
               <th class="bold"> Password</th>
               <th class="bold"> Status</th>
            </tr>
         </thead>
         <tbody>
            <tr>
               @for($i = 1; $i <= 12; $i++)
               @if($i==11)
               <td>********</td>
               @elseif($i==12)
               <td>Active / Inactive</td>
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
   @if($validation_errors = session('validation_errors'))
   <div class="alert alert-danger" role="alert">@lang('form.import_failed_message')</div>
      @foreach($validation_errors as $er)
         <div class="text-danger" style="font-size: 13px;">{{ $er }}</div>
      @endforeach
   @endif  
   <br>
   @if(Session::has('download_file_to_see_unimported_rows'))
      <p class="alert {{ Session::get('alert-class', 'alert-info') }}"><?php echo Session::get('download_file_to_see_unimported_rows'); ?></p>
   @endif

   @if(Session::has('message'))
      <p class="alert {{ Session::get('alert-class', 'alert-success') }}">
         <?php echo Session::get('message'); ?>
      </p>
   @endif

   <form method="post" action="{{ route('admin.shopkeeper.import') }}" enctype="multipart/form-data">
      {{ csrf_field()  }}
      <div class="form-row">
         <div class="form-group col-md-3">
            <label for="assigned_to">@lang('form.assigned_to')</label>
            <?php echo form_dropdown("assigned_to", $data['assigned_to_list'], old_set("assigned_to", NULL, $rec), "class='form-control form-control-sm selectpicker'") ?>
            <div class="invalid-feedback d-block">@php if($errors->has('assigned_to')) { echo $errors->first('assigned_to') ; } @endphp</div>
         </div><!-- 
         <div class="form-group col-md-3">
            <label for="status">@lang('form.status')</label>
            <?php echo form_dropdown("status", $data['status'], old_set("status", NULL, $rec), "class='form-control form-control-sm selectpicker'") ?>
            <div class="invalid-feedback d-block">@php if($errors->has('status')) { echo $errors->first('status') ; } @endphp</div>
         </div> -->
         <div class="form-group col-md-3">
            <label for="usergroup">User Group</label>
            <?php echo form_dropdown("usergroup", $data['usergroup'], old_set("usergroup", NULL, $rec), "class='form-control form-control-sm selectpicker'") ?>
            <div class="invalid-feedback d-block">@php if($errors->has('usergroup')) { echo $errors->first('usergroup') ; } @endphp</div>
         </div>

         <div class="form-group col-md-3">
            <label for="usergroup">Dealer Status</label>
            <select name="is_verified" class="form-control">
               <option value="0">Not Verified</option>
               <option value="1">Verified</option>
               <option value="2">Not Interested</option>
            </select>
         </div>
         
         <div class="form-group col-md-3">
            <label>@lang('form.select_file')</label>
            <input type="file" class="form-control-file" name="file">
            <div class="invalid-feedback d-block">@php if($errors->has('file')) { echo $errors->first('file') ; } @endphp</div>
         </div>

      </div>
      <?php echo bottom_toolbar(__('form.import')); ?>
   </form>
</div>
</div>
@endsection