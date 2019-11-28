@extends('admin.layout.master')

@section('content')
  <main class="app-content">
     <div class="app-title">
       <div>
          <h1><i class="fa fa-dashboard"></i>Area Management</h1>
       </div>
       <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
       </ul>
    </div>
     <div class="row">
        <div class="col-md-3">
           <div class="tile">
              	<div class="float-left icon-btn">
                    <a class="btn btn-info" href="{{route('admin.zipcodes.index')}}">
                      <i class="fa fa-plus"></i> Add Area
                    </a>
                  </div>
                <p style="clear:both;margin-top:50px;"></p>
              	<div class="col-md-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
              	</div>
              	<div class="col-sm-12">
              		@if(isset($edit))
                
              		<form action="{{ route('admin.zipcodes.update') }}" method="post" accept-charset="utf-8" id="regform">
              			{{csrf_field()}}
                    <input type="hidden" name="id" value="{{ $edit->id }}">
		                <div class="form-group">
                  <label>Enter Country <span class="text-danger">*</span></label>
                  <select name="country_id" id="country_select" class="form-control ">
                    <option value="1" selected>India</option>
                    option
                  </select>
                </div>
                <div class="form-group">
                  <label>Enter State <span class="text-danger">*</span></label>
                  <select name="state_id" id="state_select" class="form-control select2">
                  </select>
                </div>
                <div class="form-group ">
                  <label>Enter City <span class="text-danger">*</span></label>
                  <select name="city_id" id="city_select" class="form-control select2">
                  </select>
                  
                </div>
                <div class=" {{ $errors->has('zipcode_name') ? ' has-error' : '' }}">
                  <label for="firstname" class="control-label col-form-label">Enter Zipcode
                    <span class="text-danger">*</span>
                  </label>
                  <input type="text" placeholder="Zipcode Name" name="zipcode_name" id="zipcode_name" class="form-control" value="{{ $edit->zipcode_name }}">
                  @if ($errors->has('zipcode_name'))
                  <p class="text-danger">
                    <span class="help-block">
                      <strong>{{ $errors->first('zipcode_name') }}</strong>
                    </span>
                  </p>
                  @endif
                </div>
                <div class=" {{ $errors->has('area_name') ? ' has-error' : '' }}">
                  <label for="firstname" class="control-label col-form-label">Enter Area Name
                    
                  </label>
                  <input type="text" placeholder="Area Name" name="area_name" id="area_name" class="form-control" value="{{ $edit->area_name }}">
                  @if ($errors->has('area_name'))
                  <p class="text-danger">
                    <span class="help-block">
                      <strong>{{ $errors->first('area_name') }}</strong>
                    </span>
                  </p>
                  @endif
                </div>
		                <div class="form-group">
		                	<label>Status: </label>
		                	<select name="status"  class="form-control">
		                		<option value="0" {{ ($edit->status==0)?'selected':'' }}>Inactive</option>
		                		<option value="1" {{ ($edit->status==1)?'selected':'' }}>Active</option>
		                	</select>
		                </div>
		                <div class="form-group">
		                	<button type="submit" class="btn btn-success">Save</button>
		                </div>
              		</form>
              		@else
              		<form action="{{ route('admin.zipcodes.store') }}" method="post" accept-charset="utf-8" id="regform">
              			{{csrf_field()}}
                  <div class="form-group">
                    <label>Enter Country <span class="text-danger">*</span></label>
                    <select name="country_id" id="country_select" class="form-control select2">
                    </select>
                  </div>
                  <div class="form-group">
                  <label>Enter State <span class="text-danger">*</span></label>
                  <select name="state_id" id="state_select" class="form-control select2">
                  </select>
                </div>
                <div class="form-group ">
                  <label>Enter City <span class="text-danger">*</span></label>
                  <select name="city_id" id="city_select" class="form-control select2">
                  </select>
                  
                </div>
                <div class=" {{ $errors->has('zipcode_name') ? ' has-error' : '' }}">
                  <label for="firstname" class="control-label col-form-label">Enter Zipcode
                    <span class="text-danger">*</span>
                  </label>
                  <input type="text" placeholder="Zipcode Name" name="zipcode_name" id="zipcode_name" class="form-control" value="{{ old('zipcode_name') }}">
                  @if ($errors->has('zipcode_name'))
                  <p class="text-danger">
                    <span class="help-block">
                      <strong>{{ $errors->first('zipcode_name') }}</strong>
                    </span>
                  </p>
                  @endif
                </div>
                <div class=" {{ $errors->has('area_name') ? ' has-error' : '' }}">
                  <label for="firstname" class="control-label col-form-label">Enter Area Name
                    
                  </label>
                  <input type="text" placeholder="Area Name" name="area_name" id="area_name" class="form-control" value="{{ old('area_name') }}">
                  @if ($errors->has('area_name'))
                  <p class="text-danger">
                    <span class="help-block">
                      <strong>{{ $errors->first('area_name') }}</strong>
                    </span>
                  </p>
                  @endif
                </div>
		                <div class="form-group">
		                	<label>Status: </label>
		                	<select name="status"  class="form-control">
		                		<option value="0">Inactive</option>
		                		<option value="1">Active</option>
		                	</select>
		                </div>
		                <div class="form-group">
		                	<button type="submit" class="btn btn-success">Save</button>
		                </div>
              		</form>
              		@endif
              	</div>
           </div>
        </div>
        <div class="col-md-9">
           <div class="tile">
              	<h3 class="tile-title pull-left">Area List<strong></strong></h3>
              	<div class="table-responsive">
              		<table class="table table-striped">
              			<thead>
              				<tr>
              					<th>S.No.</th>
              					<th>Area Name</th>
                        <th>Area Code</th>
                        <th>State</th>
                         <th>City</th>
              					<th>Status</th>
              					<th>Action</th>
              				</tr>
              			</thead>
              			<tbody>
              				@if(isset($staffroles))
              				@php $i=0; @endphp
              				@forelse($staffroles as $role)
              				@php $i++; @endphp
              				<tr>
              					<td>{{ $i }}</td>
              					<td>{{ $role->area_name }}</td>
                        <td>{{ $role->zipcode_name }}</td>
                        <td>{{ $role->state_name }}</td>
                        <td>{{ $role->city_name }}</td>
              					<td>@if($role->status == '1')
              						<span class="badge badge-success">Active</span>
              						@else
              						<span class="badge badge-danger">Inactive</span>
              						@endif
              					</td>
              					<td>
              						<a href="{{ route('admin.zipcodes.edit',$role->id) }}"><i class="fa fa-edit"></i></a> 
              						<a href="{{ route('admin.zipcodes.delete',$role->id) }}"><i class="fa fa-trash"></i></a>
              					</td>
              				</tr>
              				@empty
              				<tr>
              					<td>No Data found</td>
              				</tr>
              				@endforelse
              				@endif
              			</tbody>
              		</table>
              	</div>
           </div>
        </div>
     </div>
  </main>
  @if(isset($edit))
<script>
  $(document).ready( function () {
    // State Dropdown
    var country_id;
    //$(document).on('change', '#country_select', function(event) {
      //country_id = $('#country_select').val();
      var selected_state = {{ $edit->state_id }};
      country_id = 1;
      $.ajax({
        url: "{{ route('get.states') }}",
        headers: {
          'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        method: 'POST',
        data  : { country_id : country_id,selected_state : selected_state },
        success: function(data) {
          $('#state_select').html(data.html);
        }
      });

      // City Dropdown
          var state_id;
          var selected_city = '{{ $edit->city_id }}';
          $.ajax({
            url: "{{ route('get.cities') }}",
            headers: {
              'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            method: 'POST',
            data  : { state_id : state_id,selected_city:selected_city},
            success: function(data) {
              $('#city_select').html(data.html);
            }
          });
    //});

    // City Dropdown
    var state_id;
    $(document).on('change', '#state_select', function(event) {
      state_id = $('#state_select').val();
      $.ajax({
        url: "{{ route('get.cities') }}",
        headers: {
          'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        method: 'POST',
        data  : { state_id : state_id},
        success: function(data) {
          $('#city_select').html(data.html);
        }
      });
    });
  });
</script>
      
<script>
$( document ).ready( function () {
    /*Form Velidation*/
 $.validator.setDefaults( {
    submitHandler: function () {
    form.submit();
    //alert( "submitted!" );
    }
  });
  $.validator.addMethod("lettersonly", function(value, element) {
    return this.optional(element) || /^[a-z\s]+$/i.test(value);
  }, "Enter only  alphabetical characters");
        $( "#regform" ).validate( {
            rules: {
                //city_id: {
                    //required: true,
                   //},

                 zipcode_name: {
                    required: true,
                },

            },
            messages: {
                city_id: {
                    required: "Please enter City Name.",
                },
                zipcode_name: {
                    required: "Please enter Zip Code.",
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





   @else

   <script>
  $(document).ready( function () {
    $(".select2").select2();
    // Country Dropdown
    $.ajax({
      url: "{{ route('get.countries') }}",
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      success: function(resp) {
        $('#country_select').html(resp.html);
      }
    });

    // State Dropdown
    var country_id;
    $(document).on('change', '#country_select', function(event) {
      country_id = $('#country_select').val();
      $.ajax({
        url: "{{ route('get.states') }}",
        headers: {
          'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        method: 'POST',
        data  : { country_id : country_id},
        success: function(data) {
          $('#state_select').html(data.html);
        }
      });
    });

    // City Dropdown
    var state_id;
    $(document).on('change', '#state_select', function(event) {
      state_id = $('#state_select').val();
      $.ajax({
        url: "{{ route('get.cities') }}",
        headers: {
          'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        method: 'POST',
        data  : { state_id : state_id},
        success: function(data) {
          $('#city_select').html(data.html);
        }
      });
    });

  });
</script>
      
<script>
$( document ).ready( function () {
    /*Form Velidation*/
 $.validator.setDefaults( {
    submitHandler: function () {
    form.submit();
    //alert( "submitted!" );
    }
  });
  $.validator.addMethod("lettersonly", function(value, element) {
    return this.optional(element) || /^[a-z\s]+$/i.test(value);
  }, "Enter only  alphabetical characters");
        $( "#regform" ).validate( {
            rules: {
                //city_id: {
                    //required: true,
                   //},

                 zipcode_name: {
                    required: true,
                },

            },
            messages: {
                city_id: {
                    required: "Please enter City Name.",
                },
                zipcode_name: {
                    required: "Please enter Zip Code.",
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
   @endif
  
@endsection