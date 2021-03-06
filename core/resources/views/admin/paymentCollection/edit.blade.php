
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
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: 
    #444;
    line-height: 28px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    top: 0px !important;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: 
#ddd;
color:
    #333;
}
.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid 
    #ddd;
    border-radius: 3px;
}
/*div#admins-table_filter {
    display: none;
}*/


</style>
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="main-content">
      <h5>{{ __('form.edit') }} {{ __('form.collection') }}</h5>
      <hr />
      <form class="form-horizontal m-t-20" role="form" id="loginform" method="POST" enctype="multipart/form-data" action="{{ route('collection.update', $collection->id) }}">
        <h4 class="card-title m-b-0">
            <div class="arrow-down float-right" onclick="toggleSetion(this.classList,'publish-setion')"></div>
          </h4>
        {{ csrf_field() }}
        
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label>Customer Name <span class="text-danger">*</span></label>
              <input type="text" placeholder="Customer Name" name="name" class="form-control" value="{{$collection->name}}">
            </div>
            <div class=" {{ $errors->has('name') ? ' has-error' : '' }}"> @if ($errors->has('name'))
              <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('name') }}</strong> </span></p>
              @endif
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>{{__('form.shop_name')}}<span class="text-danger">*</span></label>
             <input type="text" placeholder="{{__('form.shop_name')}}" name="shop_name" class="form-control" value="{{$collection->shop_name}}">
            </div>
            <div class=" {{ $errors->has('shop_name') ? ' has-error' : '' }}"> @if ($errors->has('shop_name'))
              <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('shop_name') }}</strong> </span></p>
              @endif
            </div>
          </div><!-- Shop Name -->

          <div class="col-md-3">
            <div class="form-group">
              <label>Mobile No <span class="text-danger">*</span></label>
              <input type="text" placeholder="Customer Mobile No" name="mobile_no" class="form-control" value="{{$collection->mobile_no}}">
            </div>
            <div class=" {{ $errors->has('mobile_no') ? ' has-error' : '' }}"> @if ($errors->has('mobile_no'))
              <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('mobile_no') }}</strong> </span></p>
              @endif
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Alternate Number No </label>
              <input type="text" placeholder="Alternate Mobile No" name="alternate_no" class="form-control" value="{{$collection->alternate_no}}">
            </div>
            <div class=" {{ $errors->has('alternate_no') ? ' has-error' : '' }}"> @if ($errors->has('alternate_no'))
              <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('alternate_no') }}</strong> </span></p>
              @endif
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label>{{__('form.country')}}</label>
              <?php echo form_dropdown("country_id", $countries, old_set("country_id",NULL,$collection), "class='form-control select2 '") ?>
            </div>
            <div class=" {{ $errors->has('country_id') ? ' has-error' : '' }}"> @if ($errors->has('country_id'))
              <p class="text-danger"> <span class="help-block"> <strong>Country is Required</strong> </span></p>
              @endif
            </div>
          </div><!-- Country -->

          <div class="col-md-3">
            <div class="form-group">
              <label>{{__('form.state')}}</label>
              <?php echo form_dropdown("state_id", $states, old_set("state_id",NULL,$collection), "class='form-control select2 '") ?>
            </div>
            <div class=" {{ $errors->has('state_id') ? ' has-error' : '' }}"> @if ($errors->has('state_id'))
              <p class="text-danger"> <span class="help-block"> <strong>State is Required</strong> </span></p>
              @endif
            </div>
          </div><!-- State -->

          <div class="col-md-3">
            <div class="form-group">
              <label>{{__('form.city')}}</label>
              <?php echo form_dropdown("city_id", $cities, old_set("city_id",NULL,$collection), "class='form-control select2 '") ?>
            </div>
            <div class=" {{ $errors->has('city_id') ? ' has-error' : '' }}"> @if ($errors->has('city_id'))
              <p class="text-danger"> <span class="help-block"> <strong>City is Required</strong> </span></p>
              @endif
            </div>
          </div><!-- City -->

          <div class="col-md-6">
            <div class="form-group">
              <label>{{__('form.address')}}</label>
              <textarea name="address" class="form-control" rows="2">{{$collection->address}}</textarea>
            </div>
            <div class=" {{ $errors->has('address') ? ' has-error' : '' }}"> @if ($errors->has('address'))
              <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('address') }}</strong> </span></p>
              @endif
            </div>
          </div><!-- Address -->

          <div class="col-md-6"></div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Collection Due Date <span class="text-danger">*</span></label>
              <input type="text" placeholder="Collection Date" name="collection_date" class="datepicker2 form-control" value="{{date('d-m-Y',strtotime($collection->new_date))}}">
            </div>
            <div class=" {{ $errors->has('collection_date') ? ' has-error' : '' }}"> @if ($errors->has('collection_date'))
              <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('collection_date') }}</strong> </span></p>
              @endif
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Due Amount <span class="text-danger">*</span></label>
              <input type="text" placeholder="Amount" name="amount" class="form-control" value="{{ $collection->amount }}">
            </div>
            <div class=" {{ $errors->has('amount') ? ' has-error' : '' }}"> @if ($errors->has('amount'))
              <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('amount') }}</strong> </span></p>
              @endif
            </div>
          </div>
          <div class="col-md-3">
            <label>Select Salesman: </label>
            <div class="form-group">
              <select name="staff_user_id" id="salesman_select" class="salesman_select form-control select2">
                @forelse($salesman as $salesman)
                <option value="{{ $salesman->id }}" {{ ($salesman->id == $collection->staff_user_id)?'selected':'' }}>{{ $salesman->first_name.' '.$salesman->last_name }} <sup>(Level : {{ $salesman->level }})</sup></option>
                @empty
                @endforelse
              </select>
            </div>
          </div>
          <div class="col-md-3">
          </div>
          <div class="col-md-3">
          </div>
        </div>
        
        
        <div class="text-right">
        <hr />
            <button type="submit" class="btn btn-success"> Submit </button>
          </div>
          </form>
      </div>
      
      
    </div>
   
    <!-- <div class="card">
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
                  <th>Customer Name</th>
                  <th>Customer Mobile No</th>
                  <?php if(empty(auth()->user()->is_administrator)){  ?>
                  <th>Alternate No</th>
                  <?php  }?>
                  <th>Collection date</th>
                  <th>Calling date</th>
                  <th>Amount</th>
                  <th>Collected Amount</th>
                  <th>Balance Amount</th>
                  <th>Salesman</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
             
            </table>
          </div>
        </div>
      </div>
 -->




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

<script>

$(document).ready( function () {
  /*Change Date*/
  function getDate(data, type, full, meta) {
     var d = new Date(data),
         month = '' + (d.getMonth() + 1),
         day = '' + d.getDate(),
         year = d.getFullYear();

     if (month.length < 2) month = '0' + month;
     if (day.length < 2) day = '0' + day;

     return [day, month, year].join('-');
 }

 /* function getDate(data, type, full, meta){
    var date = new Date(data);
    var newDate = date;
    return newDate;
  }*/
    function salesmanName(data, type, full, meta){
      return full['salesman_first_name']+'&nbsp'+full['salesman_last_name'];
    }
    function collectionAmount(data, type, full, meta){
      if(full['collected_amount'] ==null){
         return '----------';
      }else{
        return full['collected_amount'];
      }
    }
     function balanceAmount(data, type, full, meta){
      if(full['balance_amount'] ==null){
         return '----------';
      }else{
       return full['balance_amount']; 
      }
    }
    function getImg(data, type, full, meta) {
      return '<img  src="'+data+'"  width="100px" height="50px"/>';
    }
    
    function getStatus(data, type, full, meta) {
        if(full['status'] == 1){
            return '<span class="badge badge-success">Closed</span>';
        }else if(full['status'] == 2) {
           return '<span class="badge badge-warning">Closed By Salesman</span>';
        }else{
            return '<span class="badge badge-danger">Open</span>';
        }
    }

$('#admins-table').DataTable({
      //dom: 'Bfrtip',
      stripHtml: false,
      "lengthMenu": [ [10, 20, 50, 100,150,200,250,300,350,450,500, -1], [10, 20, 50, 100,150,200,250,300,350,450,500, "All"] ],
      processing: true,
      serverSide: true,
      "pageLength": {{ Config::get('constants.RECORD_PER_PAGE') }},
      ajax: "{{ route('collection.index') }}",
       columns: [
            /*{ data: 'id', name: 'id' },*/
            { data: 'name', name: 'name' },
            { data: 'mobile_no', name: 'mobile_no' },
              <?php if(empty(auth()->user()->is_administrator)){  ?>
            { data: 'alternate_no', name: 'alternate_no' },
             <?php  }?>
            { data: 'collection_date', name: 'collection_date',render: getDate },
            { data: 'new_date', name: 'new_date',render: getDate },
            { data: 'amount', name: 'amount' },
            { data: 'collected_amount', name: 'collected_amount',render:collectionAmount },
            { data: 'balance_amount', name: 'balance_amount',render:balanceAmount },
            { data: 'salesman_first_name',name:'salesman_first_name',render:salesmanName},
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
        url:"{{ url('admin/updatepaymentstatus') }}/"+user_id+"/"+brand_status,
       beforeSend:function(){
        $('#status_button').text('Changing Status...');
       },
       success:function(data)
       {
        console.log(data);
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
    $.validator.addMethod("mobile_regex", function(value, element) {
      return this.optional(element) || /^\d{10}$/i.test(value);
    }, "Please enter a valid Phone number.");
    $.validator.setDefaults( {
        submitHandler: function (form) {
           form.submit();
        }
    } );
     $( "#loginform" ).validate( {
            rules: {
                name: {
                    required: true,
                },
                mobile_no: {
                    required: true,
                    mobile_regex: true,
                },
                alternate_no: {
                   /* required: true,*/
                    mobile_regex: true,
                },
                collection_date: {
                    required: true,
                },
                amount: {
                    required: true,
                },
                staff_user_id: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please Enter Customer Name.",
                },
                mobile_no: {
                    required: "Please Enter Customer Mobile No.",
                },
                alternate_no: {
                    required: "Please Enter Alternate Mobile No.",
                },
                collection_date: {
                    required: "Please Select Collection Date .",
                },
                amount: {
                    required: "Please Enter Collection Amount .",
                },
                staff_user_id: {
                    required: "Please Select Salesman .",
                },
            },
            errorElement: "span",
            errorClass: "text-danger help-block",
            errorPlacement: function ( error, element ) {
            if(element.parent('.form-group').length) {
                  error.insertAfter(element.parent());
              } else {
                  error.insertAfter(element);
              }
           },
        });
    });

  $(document).on('change','select[name=country_id]',function(){
    var country_id = $(this).val();
    $.ajax({
      url: "{{ route('get.states') }}",
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      method: 'POST',
      data  : { country_id : country_id},
      success: function(data) {
        $('select[name=state_id]').html(data.html);
      }
    });
  });

  $(document).on('change','select[name=state_id]',function(){
    var state_id = $(this).val();
    $.ajax({
      url: "{{ route('get.cities') }}",
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      method: 'POST',
      data  : { state_id : state_id},
      success: function(data) {
        $('select[name=city_id]').html(data.html);
      }
    });
  });

 /*Salesman List*/
   /* $(document).ready(function($) {
    $(".select2").select2();
     $.ajax({
        url: "{{ route('get.salesman.lavelone') }}",
        headers: {
          'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        method: 'POST',
        success: function(data) {
          $('#salesman_select').html(data.html);
        }
      });
    });*/
</script> 
@endsection
    
{{--End Body --}}
{{--End Html --}} 