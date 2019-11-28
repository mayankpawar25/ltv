@extends('admin.layout.master')

@section('content')
<?php 
  /*print_r($edit);
  die;*/
?>
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-dashboard"></i>Salesman Management</h1>
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
            <a class="btn btn-info" href="{{route('admin.salesman.index')}}">
              <i class="fa fa-plus"></i> Add Salesman
            </a>
        </div>
        <p style="clear:both;margin-top:50px;"></p>
        <form action="{{ route('admin.salesman.update',$edit->id) }}" method="post" accept-charset="utf-8" id="tax-register">
          {{csrf_field()}}
          <h3 class="tile-title pull-left">Edit Salesman<strong></strong></h3>
          <p style="clear:both;margin:0px;"></p>
          <input type="hidden" name="id" value="{{ $edit->id }}">
          <div class="form-group">
            <label>Name: </label>
            <input type="text" name="name" class="form-control" value="{{ $edit->name }}" >
            <div class=" {{ $errors->has('name') ? ' has-error' : '' }}">
              @if ($errors->has('name'))
              <p class="text-danger">
                <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
              </p>
              @endif
            </div>
          </div>
          <div class="form-group">
             <label>Employer Number: </label>
            <input type="text" name="emp_id" class="form-control" value="{{ $edit->emp_id }}">
            <div class=" {{ $errors->has('emp_id') ? ' has-error' : '' }}">
              @if ($errors->has('emp_id'))
              <p class="text-danger">
                <span class="help-block"><strong>{{ $errors->first('emp_id') }}</strong></span>
              </p>
              @endif
            </div>
          </div>
           <div class="form-group">
             <label>Email: </label>
            <input type="text" name="email" class="form-control" value="{{ $edit->email }}">
            <div class=" {{ $errors->has('email') ? ' has-error' : '' }}">
              @if ($errors->has('email'))
              <p class="text-danger">
                <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
              </p>
              @endif
            </div>
          </div>
          <div class="form-group">
             <label>Phone: </label>
            <input type="text" name="phone" class="form-control" value="{{ $edit->phone }}">
            <div class=" {{ $errors->has('phone') ? ' has-error' : '' }}">
              @if ($errors->has('phone'))
              <p class="text-danger">
                <span class="help-block"><strong>{{ $errors->first('phone') }}</strong></span>
              </p>
              @endif
            </div>
          </div>
        <!--   <div class="form-group">
             <label>Password: </label>
            <input type="text" name="password" class="form-control" value="{{ $edit->password }}">
            <div class=" {{ $errors->has('password') ? ' has-error' : '' }}">
              @if ($errors->has('password'))
              <p class="text-danger">
                <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
              </p>
              @endif
            </div>
          </div> -->
          <div class="form-group">
             <label>Address: </label>
            <textarea type="text" name="address" class="form-control" id="address_by_lat_long">{{ $edit->address }}</textarea>
          </div>
          <div class="form-group">
            <label>Status: </label>
            <select name="status"  class="form-control" required="true">
              <option value="0" {{ ($edit->status==0)?'selected':'' }}>Inactive</option>
              <option value="1" {{ ($edit->status==1)?'selected':'' }}>Active</option>
            </select>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-success">Save</button>
          </div>
        </form>
      </div>
    </div>
    <div class="col-md-9">
      <div class="tile">
    
        <h3 class="tile-title pull-left">Salesman List<strong></strong></h3>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>S.No.</th>
                <th>Name</th>
                <th>Employer Number </th>
                <th>Email</th>
                <th>Phone</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @if(isset($taxes))
              @php $i=0; @endphp
              @forelse($taxes as $tax)
              @php $i++; @endphp
              <tr>
                <td>{{ $i }}</td>
                <td>{{ $tax->name }}</td>
                <td>{{ $tax->emp_id }}</td>
                <td>{{ $tax->email }}</td>
                <td>{{ $tax->phone }}</td>
                <td>
                  <a href="{{ route('admin.salesman.edit',$tax->id) }}"><i class="fa fa-edit"></i></a>
                 
                  @if ($tax->status==1)
                  <a href="{{ route('admin.salesman.visibility',$tax->id) }}" onclick="event.preventDefault();document.getElementById('tax-status-update-{{$tax->id}}').submit();">
                    <span class="badge badge-pill badge-success" style="margin-left: 20px; ">
                      {{ __('Active') }}
                    </span>
                  </a>
                  @endif
                  @if ($tax->status==0)
                  <a href="{{ route('admin.salesman.visibility',$tax->id) }}" onclick="event.preventDefault();document.getElementById('tax-status-update-{{$tax->id}}').submit();">
                    <span class="badge badge-pill badge-danger" style="margin-left: 20px; ">
                      {{ __('Inactive') }}
                    </span>
                  </a>
                  @endif
                  <form id="tax-status-update-{{$tax->id}}" action="{{ route('admin.salesman.visibility',$tax->id) }}" method="post" style="display: none;">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="{{ ($tax->status == 0) ? '1' : '0'  }}">
                  </form>
                </td>
              </tr>
              @empty
              <div></div>
              <tr>
                <td colspan="7" rowspan="" headers="" class="text-center">No Data found</td>
              </tr>
              @endforelse
              @endif
            </tbody>
          </table>
        </div>
        <div class="pagination">
          
        </div>
      </div>
    </div>
  </div>
</main>
  <script type="text/javascript">
  //---------------------------------------------------------
  // Validator
  //---------------------------------------------------------
  /*Add Staff Form Velidation*/
   $( document ).ready( function () {
     $( "#tax-register" ).validate( {
          submitHandler: function(form) {
             form.submit();
          },
          rules: {
              tax_name: {
                  required: true,
              },
             tax_percentage: {
                  required: true,
              },
              status: {
                  required: true,
             },
          },
          messages: {
              tax_name: {
                  required: "Please Enter Tax Name.",
              },
              tax_percentage: {
                  required: "Please Enter Tax Percentage.",
              },
              status: {
                  required: "Please Select Tax Status.",
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