
@extends('admin.layout.master')
{{-- Content Body --}}
@section('content')
 <main class="app-content">
 
	</div>
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
			{{-- <div class="card">
				<div class="card-body"> <a href="{{ url('/admin/states') }}" class="btn btn-warning btn-block">Add State</a> </div>
			</div> --}}
			<div class="main-content">
             <h5>Add State</h5>
            <hr />
				<form class="form-horizontal m-t-20" role="form" id="loginform" method="POST" enctype="multipart/form-data" action="{{ route('states.store') }}">
					{{ csrf_field() }}
					<div class="">
						
						<!--<h4 class="card-title m-b-0">
						<div class="arrow-down float-right" onclick="toggleSetion(this.classList,'publish-setion')"></div>
						</h4>-->
						<p class="text-muted "></p>
						<div class="form-group">
							<label for="firstname" class="control-label col-form-label">Select Country<span class="text-danger">*</span></label>
							
						<select name="country_id" id="country" class="form-control select2"></select>
					</div>
					<div class="text-left ">
						
						<label>Add New State <span class="text-danger">*</span></label>
						<input type="text" placeholder="State Name" name="name" id="state" class="form-control" value="{{ old('name') }}">
						<div class=" {{ $errors->has('name') ? ' has-error' : '' }}">
							@if ($errors->has('name'))
							<p class="text-danger"> <span class="help-block">
								<strong>{{ $errors->first('name') }}</strong>
							</span></p>
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
			<div class="">
            <h5>States List</h5>
            <hr />
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
					<table class="table table-bordered table-striped display" id="admins-table">
						<thead>
							<tr>
								<th>Id</th>
								<th>Name</th>
								<th>Country Name</th>
								<!-- <th>Status</th> -->
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
        <h4 class="modal-title">Confirmation</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <h5 align="center" style="margin:0;"><strong>Are you sure you want to Change this status?</strong></h5>
      </div>
      <div class="modal-footer">
        <button type="button" name="status_button" id="status_button" class="btn btn-danger">OK</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
</main>
<!-- Status -->
<style>
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: 
#f2f2f2;
color:  #333;
}
.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid 
    #ddd;
    border-radius: 3px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: 
    #444;
    line-height: 30px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    top: 2px !important;
}
</style>

<script type="text/javascript">
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
	processing: true,
	serverSide: true,
	"pageLength": {{ Config::get('constants.RECORD_PER_PAGE') }},
	ajax: "{{ route('states.index') }}",
	columns: [
		 { data: 'id', name: 'id' },
	      { data: 'name', name: 'name' },
	      { data: 'country_name', name: 'country_name' },
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
	$('#status_button').click(function(){
		$.ajax({
			url:"{{ url('admin/updatestatestatus') }}/"+user_id+"/"+brand_status,
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
   $.validator.setDefaults( {
	    submitHandler: function () {
	    	form.submit();
	    }
	  });
        $( "#loginform" ).validate( {
            rules: {
                name: {
                    required: true,
                },

                country_id: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter State Name.",
                },
                country_id: {
                    required: "Please enter Country Name.",
                },
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
  $(document).ready( function () {
	 $(".select2").select2();
	  $.ajax({
           url: "{{ url('admin/countrydropdown') }}",
           method: 'GET',
           success: function(data) {
               $('#country').html(data.html);
           }
       });
	});

</script>


@endsection

{{--End Body --}}
{{--End Html --}}