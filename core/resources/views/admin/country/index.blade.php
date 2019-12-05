
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
div#admins-table_filter {
    display: none;
}

</style>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-3">
			{{-- <div class="card">
					<div class="card-body"> <a href="{{ url('/admin/addcountry') }}" class="btn btn-warning btn-block">Add Country</a> </div>
			</div> --}}

			<div class="main-content">
				<form class="form-horizontal m-t-20" role="form" id="loginform" method="POST" enctype="multipart/form-data" action="{{ route('countries.store') }}">
					   {{ csrf_field() }}
					<div class="">
						<h5 class="">Add New Country <!--<div class="arrow-down float-right" onclick="toggleSetion(this.classList,'publish-setion')"></div>--></h5>
<hr />
						<div class="text-left">
							<div class=" {{ $errors->has('name') ? ' has-error' : '' }}">
								<label for="firstname" class="control-label col-form-label">Enter Country Name 
									<span class="text-danger">*</span>
								</label>
								<input type="text" placeholder="Country Name" name="name" id="name" class="form-control" value="{{ old('name') }}">
								@if ($errors->has('name'))
								<p class="text-danger">
									<span class="help-block">
										<strong>{{ $errors->first('name') }}</strong>
									</span>
								</p>
								@endif
							</div>

							<!-- <div class=" {{ $errors->has('iso_code') ? ' has-error' : '' }}">
								<label for="firstname" class="control-label col-form-label">Enter ISO Code 
									<span class="text-danger">*</span>
								</label>
								<input type="text" placeholder="ISO Code" name="iso_code" id="iso_code"class="form-control" value="{{ old('iso_code') }}">
								@if ($errors->has('iso_code'))
								<p class="text-danger">
									<span class="help-block">
										<strong>{{ $errors->first('iso_code') }}</strong>
									</span>
								</p>
								@endif
							</div> -->

						<!-- 	<div class=" {{ $errors->has('phone_code') ? ' has-error' : '' }}">
								<label for="firstname" class="control-label col-form-label">Enter Country Code 
									<span class="text-danger">*</span>
								</label>
								<input type="text" placeholder="Country Code" name="phone_code" id="phone_code"class="form-control" value="{{ old('phone_code') }}">
								@if ($errors->has('phone_code'))
								<p class="text-danger">
									<span class="help-block">
										<strong>{{ $errors->first('phone_code') }}</strong>
									</span>
								</p>
								@endif
							</div> -->

							
							 
						
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
				<h5>Countries List</h5>
                <hr />
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
						<table class="table  display" id="admins-table">
							<thead>
								<tr>
									<th>Id</th>
									<th>Name</th>
									<!-- <th>ISO Code</th>
									<th>Country Code</th> -->
									<!-- <th>Status</th> -->
									<th>Action</th>
								</tr>
							</thead>
						<!-- 	<tfoot>
								<tr>
									<th>Id</th>
									<th>Name</th>
									<th>ISO Code</th>
									<th>Country Code</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</tfoot> -->
						</table>
					</div>
				</div>
			</div>
		</div>
		
		
	</div>
</div>
<div id="confirmModal" class="modal " role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Confirmation</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>
      <div class="modal-body">
        <h5 align="center" style="margin:0;">Are you sure you want to remove this data?</h5>
      </div>
      <div class="modal-footer">
        <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<!-- Status -->
<div id="statusconfirmModal" class="modal " role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Confirmation</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <h5 align="center" style="margin:0;"><strong>Are you sure you want to change this status?</strong></h5>
      </div>
      <div class="modal-footer">
        <button type="button" name="status_button" id="status_button" class="btn btn-danger">OK</button>
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>


</main>
<!-- Status -->
 <script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.colVis.min.js"></script> 
<script>
/****************************************
*       Basic Table                   *
****************************************/
//  $('#zero_config').DataTable();
</script>

<script>
$(document).ready( function () {
	function getImg(data, type, full, meta) {
		return '<img  src="'+data+'"  width="100px" height="50px"/>';
	}
	
	function getStatus(data, type, full, meta) {
		if(data == 0){
			return 'Inactive';
		}else{
			return 'Active';
		}
	}
$('#admins-table').DataTable({
		dom: 'lfBfrtip',
		stripHtml: false,
		"lengthMenu": [ [10, 20, 50, 100, -1], [10, 20, 50, 100, "All"] ],
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
		serverSide: true,
		"pageLength": {{ Config::get('constants.RECORD_PER_PAGE') }},
		ajax: "{{ route('countries.index') }}",
		columns: [
			{ data: 'id', name: 'id' },
			{ data: 'name', name: 'name' },
			/*{ data: 'iso_code', name: 'iso_code' },*/
			/*{ data: 'phone_code', name: 'phone_code' },*/
			/*{ data: 'status', name: 'status',render: getStatus },*/
			{ data: 'action',name: 'action',orderable: false}
		]
	});
	var user_id;
	
	/*Active Otion*/
	var brand_status;
	$(document).on('click', '.status', function(){
		$('#status_button').text('Ok');
		user_id = $(this).attr('id');
		brand_status = $(this).attr('data-status');
		$('#statusconfirmModal').modal('show');
	});

	/*Status*/
	$('#status_button').click(function(){
		$.ajax({
		url:"{{ url('admin/updatecountrystatus') }}/"+user_id+"/"+brand_status,
			beforeSend:function(){
				$('#status_button').text('Changing Status...');
			},
			success:function(data){
				setTimeout(function(){
					$('#statusconfirmModal').modal('hide');
					$('#admins-table').DataTable().ajax.reload();
				}, 2000);
			  }
		})
	});
});

/*Form Velidation*/
	

	 $( document ).ready( function () {
		$.validator.addMethod("hasUppercase", function(value, element) {
			if (this.optional(element)) {
			return true;
			}
			return /[A-Z]/.test(value);
		}, "Must contain uppercase");
	        $( "#loginform" ).validate( {
	            rules: {
	                name: {
	                    required: true,
	                },

	               /* iso_code: {
	                    required: true,
	                    hasUppercase:true,
	                },

	                phone_code: {
	                    required: true,
	                },*/

	                /*country_flag: {
	                    required: true,
	                },*/
	              },
	            messages: {
	                name: {
	                    required: "Please Enter Country Name.",
	                },
	               /*  iso_code: {
	                    required: "Please enter ISO Code.",
	                },
	                phone_code: {
	                    required: "Please enter Country phone Code.",
	                },*/
	                /*country_flag: {
	                    required: "Please Select Country Flag.",
	                },*/
	             },
	            errorElement: "strong",
	            errorClass: "text-danger help-block",
	            errorPlacement: function ( error, element ) {
	            if ( element.prop( "type" ) === "checkbox" ) {
	                  error.insertAfter( element.parent( "label" ) );
	                } else {
	                  error.insertAfter( element );
	                }
	              },
	        });
	    });
</script>


@endsection

{{--End Body --}}
{{--End Html --}}