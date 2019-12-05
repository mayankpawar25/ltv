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

				<div class="container-fluid">
					 
					<div class="row">
						
						<div class="col-md-3">
					<!--		 <div class="card">
        <div class="card-body"> <a href="{{ url('admin/cities') }}" class="btn btn-block btn-warning">Back to City List</a> </div>
      </div>-->
							<div class="main-content">
                            <h5>Edit City </h5>
                            <hr />
								<div class="">
									<form id="loginform" action="{{ route('cities.update',$city->id) }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
										 @method('PATCH')
										{{ csrf_field() }}
										<div class="form-group">
                                        <label>State Name  <span class="text-danger">*</span></label>
											<input type="hidden" id="stateid" value="{{ $city->id }}">
							 <select name="state_id" id="state" class="form-control select2">
                             </select>
							</div>
										<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
											<label>City Name </label> <input type="text" name="name" value="{{ $city->name }}" class="form-control">
											@if ($errors->has('name'))
			                                    <p class="text-danger"><span class="help-block">{{ $errors->first('name') }}</span></p>
			                                @endif
										</div>
										
																			
										<div class="text-right">
                                        <hr />
                                        <a href="{{ url('admin/cities') }}" class="btn btn-light">Cancel</a>
											<input type="submit" name="save" value="Save" class="btn btn-success">
										</div>
									</form>
								</div>
							</div>
						</div>
                        <div class="col-md-9">
     <!--  <div class="card">
        <div class="card-body"> <a href="{{ url('admin/citylist') }}" class="btn btn-block btn-warning">Back to Country List</a> </div>
      </div> -->
        
        
      
       <div class="main-content">
        <div class=""> 
        <h5>City List</h5>
        <hr />
        
        @if(Session::has('message'))
          <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
          @endif
          @if(Session::has('success'))
          <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
          @endif 
          @if(Session::has('error'))
          <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('error') }}</p>
          @endif
          <div class="table-responsive">
            <table class="table table-bordered w-100" id="admins-table">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>State Name</th>
                 <!--  <th>Status</th> -->
                  <th>Action</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>State Name</th>
                 <!--  <th>Status</th> -->
                  <th>Action</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
</div>
					
					{{-- Sales chart --}}
					
				</div>
			</main>
			<div id="confirmModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Confirmation</h2>
      </div>
      <div class="modal-body">
        <h5 align="center" style="margin:0;">Are you sure you want to remove this data?</h5>
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
        <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
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
        <h5 align="center" style="margin:0;"> Are you sure you want to Change this status? </h5>
      </div>
      <div class="modal-footer">
       
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
         <button type="button" name="status_button" id="status_button" class="btn btn-danger">OK</button>
      </div>
    </div>
  </div>
</div>
</main>
<!-- Status --> 
		<!-- End -->
		<script type="text/javascript">
			var state_id;
			$(document).ready( function () {
			  state_id = $('#stateid').val();
			 $(".select2").select2();
			  $.ajax({
		           url: "{{ url('admin/editcitydropdown') }}/"+state_id,
		           method: 'GET',
		           success: function(data) {
		           	$('#state').html(data.html);
		           }
		       });
			});


		/*City Listing Start*/
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
             stripHtml: false,
             "lengthMenu": [ [10, 20, 50, 100,150,200,250,300,350,450,500, -1], [10, 20, 50, 100,150,200,250,300,350,450,500, "All"] ],
             processing: true,
             serverSide: true,
             "pageLength": {{ Config::get('constants.RECORD_PER_PAGE') }},
		         ajax: "{{ route('cities.index') }}",
		         columns: [
	                    { data: 'id', name: 'id' },
	                    { data: 'name', name: 'name' },
	                    { data: 'state_name', name: 'state_name' },
	            		/*{ data: 'status', name: 'status',render: getStatus },*/
			         	{ data: 'action',name: 'action',orderable: false}
	                 ]
		        });

			 var user_id;
			 /*Delete Option*/
			 /*Start*/
			/* $(document).on('click', '.delete', function(){
				 user_id = $(this).attr('id');
				  $('#confirmModal').modal('show');
			 });
			 $('#ok_button').click(function(){
			  $.ajax({
			   url:"{{ url('admin/destroy') }}/"+user_id,
			   beforeSend:function(){
			    $('#ok_button').text('Deleting...');
			   },
			   success:function(data)
			   {
			    setTimeout(function(){
			     $('#confirmModal').modal('hide');
			     $('#admins-table').DataTable().ajax.reload();
			    }, 2000);
			   }
			  })
			 });*/
			 /*End Delete Option*/

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
			    url:"{{ url('admin/updatecitystatus') }}/"+user_id+"/"+brand_status,
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
		/*City Listing End*/	

/*Form Velidation*/
 
 $( document ).ready( function () {
 	 $.validator.setDefaults( {
        submitHandler: function (form) {
           form.submit();
        }
    } );
     $( "#loginform" ).validate( {
            rules: {
                state_id: {
                    required: true,
                },

                name: {
                    required: true,
                },
            },
            messages: {
                state_id: {
                    required: "Please Select State.",
                },
                name: {
                    required: "Please Enter City Name.",
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
 		</script>
	@endsection
	
{{--End Body --}}
{{--End Html --}}
