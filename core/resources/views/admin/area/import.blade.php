@extends('admin.layout.master')
@section('title', __('form.customers') .' : '. __('form.import'))
@section('content')
<div class="app-content">
<div class="main-content">
   <div class="row">
      <div class="col-md-6">
         <h5>Import Area</h5>
      </div>
      <div class="col-md-6">
         <a href="{{ route('download_sample_area_import_file') }}" class="btn btn-success btn-sm float-md-right">@lang('form.download_sample')</a>
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
                  Area Name
               </th>
               <th class="bold"> @lang('form.country')</th>
               <th class="bold"> @lang('form.city')</th>
               <th class="bold"> @lang('form.state')</th>
            </tr>
         </thead>
         <tbody>
            <tr>
               @for($i = 1; $i <= 4; $i++)
               <td>@lang('form.sample_data')</td>
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
   <form method="post" action="{{ route('area.import') }}" enctype="multipart/form-data">
      {{ csrf_field()  }}
      <div class="form-row">
         <div class="form-group col-md-6">
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