
@extends('admin.layout.master')
{{-- Content Body --}}
@section('content')



 <main class="app-content">
 
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
#data_filter {
    display: none !important;
}
#data tr td:last-child {
	text-align: right;
}

</style>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-3">
			<div class="main-content">
            <h5>Edit Area</h5>
            <hr />
				<form class="form-horizontal m-t-20" role="form" id="loginform" method="POST" enctype="multipart/form-data" action="{{ route('area.update',$area->id) }}">
					@method('PATCH')
				   	{{ csrf_field() }}
				   <!--	<div class="card-header">
						<h4 class="card-title m-b-0"></h4>
					</div>-->
					<div class="">
						<div class="form-group">
							<div class=" {{ $errors->has('country') ? ' has-error' : '' }}">
								<label for="firstname" class="control-label col-form-label">Country 
									<span class="text-danger">*</span>
								</label>
								<?php echo form_dropdown('country', [$country] , $area->country_id  , "class='form-control select2' "); ?>
								@if ($errors->has('country'))
								<p class="text-danger">
									<span class="help-block">
										<strong>{{ $errors->first('country') }}</strong>
									</span>
								</p>
								@endif
							</div>
						</div>
						<div class="form-group">
							<div class=" {{ $errors->has('state') ? ' has-error' : '' }}">
								<label for="firstname" class="control-label col-form-label">State 
									<span class="text-danger">*</span>
								</label>
								<?php echo form_dropdown('state', [''=>'Select State'] , $area->state_id  , "class='form-control select2' "); ?>
								@if ($errors->has('state'))
								<p class="text-danger">
									<span class="help-block">
										<strong>{{ $errors->first('state') }}</strong>
									</span>
								</p>
								@endif
							</div>
						</div>

						<div class="form-group">
							<div class=" {{ $errors->has('city') ? ' has-error' : '' }}">
								<label for="firstname" class="control-label col-form-label">City 
									<span class="text-danger">*</span>
								</label>
								<?php echo form_dropdown('city', [''=>'Select City'] , $area->city_id  , "class='form-control select2' "); ?>
								@if ($errors->has('city'))
								<p class="text-danger">
									<span class="help-block">
										<strong>{{ $errors->first('city') }}</strong>
									</span>
								</p>
								@endif
							</div>
						</div>

						<div class="form-group">
							<div class=" {{ $errors->has('name') ? ' has-error' : '' }}">
								<label for="firstname" class="control-label col-form-label">Area 
									<span class="text-danger">*</span>
								</label>
								<input type="text" name="name" value="{{ $area->area_name }}" placeholder="Enter Area Name" class="form-control">
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
					<div class="text-right">
                    <hr />
						<button type="submit" class="btn btn-success ">Submit</button>
					</div>
					
				</form>
			</div>
			
		</div>
		<div class="col-9">
			<div class="main-content">
            <h5>Area List</h5>
            <hr />
				<!--<div class="card-header">
					<div class="card-title">
						@if(auth()->user()->is_administrator)
			              <a class="btn btn-primary btn-sm" href="{{ route('area.import_page') }}" style="float:right">Import</a>
			            @endif
					</div>
				</div>-->
				<div class="">
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
						<table class="table table-bordered w-100" id="data">
							<thead>
								<tr>
									<th>Name</th>
									<th>Country</th>
									<th>State</th>
									<th>City</th>
									<th>Status</th>
									<th>Action</th>
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
	       dom: 'lfBfrtip',
	stripHtml: false,
	"lengthMenu": [ [10, 20, 50, 100,150,200,250,300,350,450,500, -1], [10, 20, 50, 100,150,200,250,300,350,450,500, "All"] ],
	buttons: [
            {
              extend: 'copyHtml5',
              exportOptions: {
                  columns: ':visible'
              }
            },{
              extend: 'excelHtml5',
              exportOptions: {
                columns: ':visible'
              }
            },{
              extend: 'print',
              exportOptions: {
                columns: ':visible'
              }
            },
            'colvis'
        ],
         "language": {
          "lengthMenu": '_MENU_ ',
          "search": '',
          "searchPlaceholder": "{{ __('form.search') }}"
      },
	processing: true,
	        responsive: true,
	        serverSide: true,
	        //iDisplayLength: 5
	        pageLength: {{ Config::get('constants.RECORD_PER_PAGE') }},
	        ordering: true,
	        "columnDefs": [
	            { className: "text-right", "targets": [4] },
	            { "name": "area_name",   "targets": 0 },
	            { "name": "country_id",  "targets": 1 },
	            { "name": "state_id", "targets": 2 },
	            { "name": "city_id",  "targets": 3 },
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

	$(function(){
  	setTimeout(loadajax,1000);
	});

	function loadajax(){
		selectState('{{ $area->country_id }}','{{$area->state_id}}');
		selectCity('{{ $area->state_id }}','{{$area->city_id}}');
		selectAreaCode('{{ $area->city_id }}','{{$area->zipcode_id}}');
	}

	function selectState(country_id,selected_state){
		$.ajax({
	        url: "{{ route('get.states') }}",
	        headers: {
	          'X-CSRF-TOKEN': $('input[name="_token"]').val()
	        },
	        method: 'POST',
	        data  : { country_id : country_id,selected_state : selected_state},
	        success: function(data) {
	          $('select[name=state]').html(data.html);
	        }
      	});
	}

	function selectCity(state_id,selected_city){
		$.ajax({
	        url: "{{ route('get.cities') }}",
	        headers: {
	          'X-CSRF-TOKEN': $('input[name="_token"]').val()
	        },
	        method: 'POST',
	        data  : { state_id : state_id,selected_city : selected_city},
	        success: function(data) {
	          $('select[name=city]').html(data.html);
	        }
      	});
	}

</script>
<!-- Status -->
@endsection

{{--End Body --}}
{{--End Html --}}