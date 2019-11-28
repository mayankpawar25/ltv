
@extends('admin.layout.master')
{{-- Content Body --}}
@section('content')
 <main class="app-content">
<div class="app-title">
    <div>
       <h1><i class="fa fa-dashboard"></i> Area</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
       <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
       <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    </ul>
</div>
<style type="text/css" media="screen">
.dataTables_length, .dt-buttons {
    float: left;
    width: 100%;
}

.dataTables_wrapper .dt-buttons {
    float: left;
    text-align: center;
    width: auto;
}
div.dataTables_wrapper div.dataTables_filter {
    text-align: right;
    width: auto;
}
div#admins-table_filter {
    display: none;
}

</style>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-3">
			<div class="card">
				<form class="form-horizontal m-t-20" role="form" id="loginform" method="POST" enctype="multipart/form-data" action="{{ route('area.store') }}">
				   	{{ csrf_field() }}
				   	<div class="card-header">
						<h4 class="card-title m-b-0">Add Area</h4>
					</div>
					<div class="card-body">
						<div class="text-left">
							<div class=" {{ $errors->has('country') ? ' has-error' : '' }}">
								<label for="firstname" class="control-label col-form-label">Country 
									<span class="text-danger">*</span>
								</label>
								<?php echo form_dropdown('country', [$country] , []  , "class='form-control select2' "); ?>
								@if ($errors->has('country'))
								<p class="text-danger">
									<span class="help-block">
										<strong>{{ $errors->first('country') }}</strong>
									</span>
								</p>
								@endif
							</div>
						</div>
						<div class="text-left">
							<div class=" {{ $errors->has('state') ? ' has-error' : '' }}">
								<label for="firstname" class="control-label col-form-label">State 
									<span class="text-danger">*</span>
								</label>
								<?php echo form_dropdown('state', [''=>'Select State'] , []  , "class='form-control select2' "); ?>
								@if ($errors->has('state'))
								<p class="text-danger">
									<span class="help-block">
										<strong>{{ $errors->first('state') }}</strong>
									</span>
								</p>
								@endif
							</div>
						</div>

						<div class="text-left">
							<div class=" {{ $errors->has('city') ? ' has-error' : '' }}">
								<label for="firstname" class="control-label col-form-label">City 
									<span class="text-danger">*</span>
								</label>
								<?php echo form_dropdown('city', [''=>'Select City'] , []  , "class='form-control select2' "); ?>
								@if ($errors->has('city'))
								<p class="text-danger">
									<span class="help-block">
										<strong>{{ $errors->first('city') }}</strong>
									</span>
								</p>
								@endif
							</div>
						</div>

						<div class="text-left">
							<div class=" {{ $errors->has('name') ? ' has-error' : '' }}">
								<label for="firstname" class="control-label col-form-label">Area 
									<span class="text-danger">*</span>
								</label>
								<input type="text" name="name" value="" placeholder="Enter Area Name" class="form-control">
								@if ($errors->has('name'))
								<p class="text-danger">
									<span class="help-block">
										<strong>{{ $errors->first('name') }}</strong>
									</span>
								</p>
								@endif
							</div>
						</div>

					</div>
					<div class="card-footer">
						<button type="submit" class="btn btn-success ">Submit</button>
					</div>
					
				</form>
			</div>
			
		</div>
		<div class="col-9">
			<div class="card">
				<div class="card-header">
					<div class="card-title">Area List
						@if(auth()->user()->is_administrator)
			              <a class="btn btn-primary btn-sm" href="{{ route('area.import_page') }}" style="float:right">Import</a>
			            @endif
					</div>
				</div>
				<div class="card-body">
					@if(Session::has('error'))
					<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('error') }}</p>
					@endif

					@if(Session::has('message'))
					<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
					@endif

					@if(Session::has('success'))
					<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
					@endif
					
					<!--  <h5 class="card-title">States List</h5> -->
					<div class="table-responsive">
						<table class="table table-bordered table-striped display" id="data">
							<thead>
								<tr>
									<th>Name</th>
									<th>Country</th>
									<th>State</th>
									<th>City</th>
									<th>Status</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
		
		
	</div>
</div>
<div id="confirmModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Confirmation</h2>
			</div>
			<div class="modal-body">
				<h4 align="center" style="margin:0;">Are you sure you want to remove this data?</h4>
			</div>
			<div class="modal-footer">
				<button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>
<!-- Status -->
<div id="statusconfirmModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Confirmation</h2>
			</div>
			<div class="modal-body">
				<h4 align="center" style="margin:0;">Are you sure you want to Change this status?</h4>
			</div>
			<div class="modal-footer">
				<button type="button" name="status_button" id="status_button" class="btn btn-danger">OK</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>
</main>
<script type="text/javascript">
	$(".select2").select2();
  	$(document).on('change','select[name=country]',function(){
	    var country_id = $(this).val();
	    $.ajax({
	      url: "{{ route('get.states') }}",
	      headers: {
	        'X-CSRF-TOKEN': $('input[name="_token"]').val()
	      },
	      method: 'POST',
	      data  : { country_id : country_id},
	      success: function(data) {
	        $('select[name=state]').html(data.html);
	      }
	    });
  	});

  	$(document).on('change','select[name=state]',function(){
	    var state_id = $(this).val();
	    $.ajax({
	      url: "{{ route('get.cities') }}",
	      headers: {
	        'X-CSRF-TOKEN': $('input[name="_token"]').val()
	      },
	      method: 'POST',
	      data  : { state_id : state_id},
	      success: function(data) {
	        $('select[name=city]').html(data.html);
	      }
	    });
  	});

	$(function() {
	    dataTable = $('#data').DataTable({
	        dom: 'Bfrtip',
	        buttons: [
	            {
	                init: function(api, node, config) {
	                    $(node).removeClass('btn-secondary')
	                },
	                className: "btn-light btn-sm",
	                extend: 'collection',
	                text: 'Export',
	                buttons: [
	                  'copy',
	                  'excel',
	                  'csv',
	                  'pdf',
	                  'print'
	                ]
	            }
	        ],
	        "language": {
	            "lengthMenu": '_MENU_ ',
	            "search": '',
	            "searchPlaceholder": "{{ __('form.search') }}"
	        },
	        responsive: true,
	        processing: true,
	        serverSide: true,
	        //iDisplayLength: 5
	        pageLength: {{ Config::get('constants.RECORD_PER_PAGE') }},
	        ordering: true,
	        "columnDefs": [
	            { className: "text-right", "targets": [4] },
	            { "name": "area_name",   "targets": 0 },
	            { "name": "country",  "targets": 1 },
	            { "name": "state", "targets": 2 },
	            { "name": "city",  "targets": 3 },
	            { "name": "status",  "targets": 4 },
	        ],
	        "ajax": {
	            "url": '{!! route("datatables_area") !!}',
	            "type": "POST",
	            'headers': {
	                'X-CSRF-TOKEN': '{{ csrf_token() }}'
	            }
	        }
	    }).
	    on('mouseover', 'tr', function() {
	        jQuery(this).find('div.row-options').show();
	    }).
	    on('mouseout', 'tr', function() {
	        jQuery(this).find('div.row-options').hide();
	    });

	    /*$('select').change(function(){
	        console.log('change here');
	        dataTable.draw();
	    });*/
  	});

</script>
<!-- Status -->
@endsection

{{--End Body --}}
{{--End Html --}}