
@extends('admin.layout.master')
{{-- Content Body --}}
@section('content')
 <main class="app-content">
<div class="app-title">
        <div>
           <h1><i class="fa fa-dashboard"></i> Payment Collection Feedback</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
           <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        </ul>
</div>
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
/*div#admins-table_filter {
    display: none;
}*/

</style>
<div class="container-fluid">
  <div class="row">
    <div class="col-3">
      <div class="card">
        <div class="card-header">
          <h4>Collection Feedback</h4>
        </div>
        <form class="form-horizontal m-t-20" role="form" id="loginform" method="POST" enctype="multipart/form-data" action="{{ route('admin.payment.adddescription',$collections->id) }}">
          {{ csrf_field() }}
          <div class="card-body">
            @if(auth()->user()->level == 1 || auth()->user()->is_administrator)
            <div class="form-group">
              <label>Collect Payment : <input type="checkbox" class="collect_payment_checkbox" value="{{ old('collect_payment_checkbox') }}" name="collect_payment_checkbox" ></label>
            </div><!-- Collect Payment Checkbox -->
            @endif
           

            <div class="form-group payment_collection d-none">
              <label>Payment Type<span class="text-danger">*</span></label>
              <select name="payment_type" class="form-control">
                <option value="">-- Select payment type --</option>
                <option value="full">Full</option>
                <option value="partial">Partial</option>
              </select>
            </div><!-- Payment Type -->

            <div class="form-group payment_collection d-none">
              <label>Amount</label>
              <input type="text" name="amount" class="form-control" placeholder="Amount">
            </div><!-- Amount -->

            <div class="form-group">
              <label>Next Calling Date <span class="text-danger">*</span></label>
              <input type="text" placeholder="Next Calling Date" name="next_calling_date" class="form-control initially_empty_datepicker" >
              @if ($errors->has('next_calling_date'))
              <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('next_calling_date') }}</strong> </span></p>
              @endif
            </div><!-- Next Calling Date -->

            <div class="form-group">
              <label>Feedback<span class="text-danger">*</span></label>
              <textarea name="feedback" class="form-control" placeholder="Feedback"></textarea>
              @if ($errors->has('feedback'))
              <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('feedback') }}</strong> </span></p>
              @endif
            </div><!-- Feedback -->

            @if(auth()->user()->level == 2 || auth()->user()->is_administrator)
              <div class="form-group">
                <label>Assigned To </label>
                <select name="assigned_to" class="salesman_select form-control select2">
                  <option value="">-- Select Salesman --</option>
                  @forelse($salesmans as $salesman)
                  <option value="{{ $salesman->id }}" {{ ($salesman->id == $collections->staff_user_id)?'selected':'' }}>{{ $salesman->first_name.' '.$salesman->last_name }} <sup>(Level : {{ $salesman->level }})</sup></option>
                  @empty
                  @endforelse
                </select>
                @if ($errors->has('assigned_to'))
                <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('assigned_to') }}</strong> </span></p>
                @endif
              </div>
            @elseif($collections->counter % \Config::get('constants.THREAD_COUNT') == 0)
              <div class="form-group">
                <label>Assigned To </label>
                <select name="assigned_to" class="salesman_select form-control select2">
                  <option value="">-- Select Salesman --</option>
                  @forelse($salesmans as $salesman)
                  <option value="{{ $salesman->id }}" {{ ($salesman->id == $collections->staff_user_id)?'selected':'' }}>{{ $salesman->first_name.' '.$salesman->last_name }} <sup>(Level : {{ $salesman->level }})</sup></option>
                  @empty
                  @endforelse
                </select>
                @if ($errors->has('assigned_to'))
                <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('assigned_to') }}</strong> </span></p>
                @endif
              </div>
            @else
              <input type="hidden" name="assigned_to" value="{{$collections->staff_user_id}}">
            @endif<!-- Assigned To -->

            <div class="form-group payment_collection {{ (auth()->user()->is_administrator)?'':'d-none' }}">
              <label>Status </label>
              <select name="status" id="status" class="form-control select2">
                <option value="0" selected>Open</option>
                <!-- @if(auth()->user()->is_administrator)
                <option value="1">Closed</option>
                @endif -->
                <option value="2">Closed</option>
              </select>
            </div><!-- Status -->

          </div>

          <div class="card-footer">            
            @if($collections->staff_user_id == auth()->user()->id)
              @php $disable2 = ''; @endphp
            @else
              @php $disable2 = 'disabled'; @endphp
            @endif

            @if($collections->status==0)
            @php $disable = ''; @endphp
            @else
            @php $disable = 'disabled'; @endphp
            @endif

            @if(auth()->user()->is_administrator)
              @php $disable = ''; @endphp
              @php $disable2 = ''; @endphp
            @endif

            <button type="submit" class="btn btn-success"  {{$disable}} {{$disable2}} > Submit </button>
          </div>
        </form>
      </div>
    </div>
    <div class="col-md-9">
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header">Collection Details
              @if($collections->status == 0)
                <span class="badge badge-danger">Open</span>
              @else
                <span class="badge badge-success">Closed</span>
              @endif
            </div>
            <div class="card-body"> 
              <div class="form-group">
                <label><strong>Name : </strong>{{ $collections->name }}</label>
              </div>
              <div class="form-group">
                <label><strong>Customer Mobile No : </strong>{{ $collections->mobile_no }}</label>
              </div>
              <div class="form-group">
                <label><strong>Alternate No : </strong>{{ $collections->alternate_no }}</label>
              </div>
              <div class="form-group">
                <label><strong>Collection Date : </strong>{{ date('d M Y',strtotime($collections->collection_date)) }}</label>
              </div>
              <div class="form-group">
                <label><strong>Calling Date : </strong>{{ date('d M Y',strtotime($collections->new_date)) }}</label>
              </div>
              <div class="form-group">
                <label><strong>Assigned to : </strong>{{ $collections->assigned->first_name .' '.$collections->assigned->last_name }}</label>
              </div>
              <div class="form-group">
                <label><strong>Amount : </strong>
                  {{ $collections->amount }}</label>
              </div>

              <div class="form-group">
                <label><strong>Closing Balance : </strong>
                  {{ $collections->balance_amount }}</label>
              </div>
              <div class="form-group">
                <label><strong>Status : </strong></label>
                @if($collections->status == 0)
                  <span class="badge badge-danger">Open</span>
                @else
                  <span class="badge badge-success">Closed</span>
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header"><strong>Next calling details</strong></div>
            <div class="card-body">
              @if(Session::has('success'))
                <p class="alert {{ Session::get('alert-class', 'alert-success') }}"><?php echo Session::get('success'); ?></p>
              @endif
              <div class="table-responsive">
                <table class="table table-bordered table-striped display" id="admins-table">
                  <thead>
                    <tr>
                      <th>Next Calling Date</th>
                      <th>Feedback</th>
                      <th>Payment Type</th>
                      <th>Amount</th>
                      <th>Balance</th>
                      <th>Status</th>
                      <th>Assigned To</th>
                      <th>Created date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($threads as $key => $thread)
                    <tr>
                      <td>{{ date('d-m-Y',strtotime($thread->calling_date)) }}</td>
                      <td>{{ $thread->feedback }}</td>
                      <td>
                          @if($thread->payment_type)
                            <span class='text text-success'>{{ucwords($thread->payment_type)}}</span>
                          @else
                            <span class='text text-danger'>None</span>
                          @endif
                      </td>
                      <td>{{ $thread->collect_amount }}</td>
                      <td>{{ $thread->balance_amount }}</td>
                      <td>
                      @if($thread->status==0)
                        <span class='text text-danger'>Open</span>
                      @elseif($thread->status==1)
                        <span class='text text-success'>Closed</span></td>
                      @else
                        <span class='text text-info'>Closed By SalesMan</span></td>
                      @endif
                      <td>{{ $thread->assigned->first_name.' '.$thread->assigned->last_name }}</td>
                      <td>{{ $thread->created_at }}</td>
                    </tr>
                    @empty
                      <tr>
                        <td colspan=5 align="center">No Data Available</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
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

/*$('#admins-table').DataTable({
      //dom: 'Bfrtip',
      stripHtml: false,
      "lengthMenu": [ [10, 50, 100,150,200,250,300,350,450,500, -1], [10, 50, 100,150,200,250,300,350,450,500, "All"] ],
      processing: true,
      serverSide: true,
      "pageLength": {{ Config::get('constants.RECORD_PER_PAGE') }},
      ajax: "{{ route('collection.index') }}",
       columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'mobile_no', name: 'mobile_no' },
            { data: 'alternate_no', name: 'alternate_no' },
            { data: 'collection_date', name: 'collection_date',render: getDate },
            { data: 'amount', name: 'amount' },
            { data: 'salesman_id', name: 'salesman_id' },
            { data: 'status', name: 'status',render: getStatus },
            { data: 'action',name: 'action',orderable: false}
         ]
    });
*/
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
    /* Form Velidation */
    $.validator.setDefaults( {
        submitHandler: function (form) {
           form.submit();
        }
    });
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

    /* Salesman List */
   /* var level = 1;
    $(document).ready(function($) {
      $(".select2").select2();
      $.ajax({
        url: "{{ route('get.salesman') }}",
        headers: {
          'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        method: 'POST',
        success: function(data) {
          $('#salesman_select').html(data.html);
        }
      });
    });*/

    $(document).on('click','.collect_payment_checkbox',function(){
        if($(this).is(':checked')){
          $('input[name=next_calling_date]').val("<?php echo date('d-m-Y'); ?>");
          $('.payment_collection').removeClass('d-none');
        }else{
          $('.payment_collection').addClass('d-none')
        }
    });


// payment_collection

</script> 
@endsection
    
{{--End Body --}}
{{--End Html --}} 