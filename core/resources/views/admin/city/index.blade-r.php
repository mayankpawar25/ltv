
@extends('admin.layouts.master')


			{{-- Bread crumb --}}
			@section('breadcrumb')
<div class="page-breadcrumb">
  <div class="row">
    <div class="col-12 d-flex no-block align-items-center">
      <h4 class="page-title">City List</h4>
      <div class="ml-auto text-right">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cities List</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</div>
@endsection
<main class="app-content">
			{{-- Content Body --}}
			@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-3">
    	  <div class="card">
      <form class="form-horizontal m-t-20" role="form" id="loginform" method="POST" enctype="multipart/form-data" action="{{ route('admin.cities.store') }}">
      	{{ csrf_field() }}
        <div class="card-body">
          <h4 class="card-title m-b-0">Add City
            <div class="arrow-down float-right" onclick="toggleSetion(this.classList,'publish-setion')"></div>
          </h4>
          <p class="text-muted "><small>Add New City</small></p>
          <div class="form-group">
            <label>State Name <span class="text-danger">*</span></label>
            <select name="state_id" id="state" class="form-control select2">
            </select>
          </div>
          <div class=" {{ $errors->has('name') ? ' has-error' : '' }}"> @if ($errors->has('name'))
            <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('name') }}</strong> </span></p>
            @endif </div>
          <div class="form-group">
<label>City Name  <span class="text-danger">*</span></label>
  <input type="text" placeholder="City Name" name="name" class="form-control" value="{{ old('name') }}">
  </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success"> Submit </button>
          </div>
          </form>
      </div>
      
      
    </div>
    <div class="col-md-9">
       
    <div class="card">
        <div class="card-body"> @if(Session::has('message'))
          <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
          @endif
          @if(Session::has('success'))
          <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
          @endif 
          @if(Session::has('error'))
          <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('error') }}</p>
          @endif
          <div class="table-responsive">
            <table class="table table-bordered table-striped display" id="admins-table">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>State Name</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>State Name</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </tfoot>
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
<!-- Status --> 

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
			    	dom: 'Bfrtip',
			    	stripHtml: false,
			         buttons: [
              {
              extend:    'copyHtml5',
              text:      '<i class="fas fa-copy"></i>',
              titleAttr: 'Copy'
              },
              {
              extend:    'excelHtml5',
              text:      '<i class="fas fa-file-excel"></i>',
              titleAttr: 'Excel'
              },
              {
              extend:    'csvHtml5',
              text:      '<i class="fas fa-file-alt"></i>',
              titleAttr: 'CSV'
              },
              {
              extend:    'pdfHtml5',
              text:      '<i class="fas fa-file-pdf"></i>',
              titleAttr: 'PDF'
              }
              ],
		           processing: true,
		           serverSide: true,
		          ajax: "{{ route('admin.cities.index') }}",
		           columns: [
	                    { data: 'id', name: 'id' },
	                    { data: 'name', name: 'name' },
	                    { data: 'state_name', name: 'state_name' },
	            		{ data: 'status', name: 'status',render: getStatus },
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
			   success:function(data)
			   {
			    setTimeout(function(){
			     $('#statusconfirmModal').modal('hide');
			     $('#admins-table').DataTable().ajax.reload();
			    }, 2000);
			   }
			  })
			 });
	     });

	 $(document).ready( function () {
	 $(".select2").select2();
	  $.ajax({
           url: "{{ url('admin/citydropdown') }}",
           method: 'GET',
           success: function(data) {
               $('#state').html(data.html);
           }
       });
	});

	 
 $( document ).ready( function () {
   /*Form Velidation*/
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

                //country_flag: {
                  //  required: true,
                //},
                //  middlename: {
                //     required: true,
                // },
                //email: {
                  //  required: true,
                    //email_regex: true
                //},
                //mobile: {
                  //  required: true,
                    //mobile_regex: true,
                    //minlength: 10
                //},
                //password: {
                  //  required: true,
                    //minlength: 8
                //},
                //password_confirmation: {
                  //  required: true,
                    //minlength: 8,
                    //equalTo: "#password"
                //},
                //agree: "required",
                //gender: "required"
            },
            messages: {
                state_id: {
                    required: "Please Select State.",
                },
                name: {
                    required: "Please Enter City Name.",
                },
                /*country_flag: {
                    required: "Please Select Country Flag.",
                },
                email:{
                    required: "Please enter email address.",
                },
                mobile: {
                    required: "Please enter a mobile.",
                },
                password: {
                    required: "Please enter your password.",
                    minlength: "Your password must be at least 8 characters long."
                },
                password_confirmation: {
                    required: "Please enter your confirm password.",
                    minlength: "Your password must be at least 8 characters long.",
                    equalTo: "Please enter the same password as above."
                },
                agree: "Please accept our Terms & Condition.",
                gender: "Your select must be at least one gender."*/
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