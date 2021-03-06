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
/*div#admins-table_filter {
    display: none;
}*/
.select2-container .select2-selection--single .select2-selection__rendered {
    padding-top: 0px;
}
</style>
  <div class="container-fluid">
    <div class="main-content">
      <h5>{{__('form.collection')}}</h5>
      <hr />
      
        @if(Session::has('success'))
          <p class="alert {{ Session::get('alert-class', 'alert-success') }}"><?php echo Session::get('success'); ?></p>
        @endif
      <div class="row">
        <div class="col-3">
          <div class="card">
            <div class="card-header"> <strong>Collection Feedback</strong> </div>
            <form class="form-horizontal m-t-20" role="form" id="loginform" method="POST" enctype="multipart/form-data" action="{{ route('admin.payment.adddescription',$collections->id) }}">
              {{ csrf_field() }}
              <div class="card-body"> @if(auth()->user()->level == 1 || auth()->user()->is_administrator)
                <div class="form-group">
                  <label>
                    <input type="checkbox" class="collect_payment_checkbox" value="1" name="collect_payment_checkbox" >
                    Collect Payment</label>
                </div>
                <!-- Collect Payment Checkbox --> 
                @endif
                <div class="form-group payment_collection d-none">
                  <label>Payment Type<span class="text-danger">*</span></label>
                  <select name="payment_type" class="form-control">
                    <option value="">-- Select payment type --</option>
                    <option value="full">Full</option>
                    <option value="partial">Partial</option>
                  </select>
                </div>
                <!-- Payment Type -->
                
                <div class="form-group payment_collection d-none">
                  <label>Due Amount</label>
                  <input type="text" name="amount" class="form-control" placeholder="Amount">
                </div>
                <!-- Amount -->
                
                <div class="form-group next_calling_date">
                  <label>Next Collection Date <span class="text-danger">*</span></label>
                  <input type="text" placeholder="Next Calling Date" name="next_calling_date" class="form-control datepicker2" value="{{old('next_calling_date')}}">
                  @if ($errors->has('next_calling_date'))
                  <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('next_calling_date') }}</strong> </span></p>
                  @endif </div>
                <!-- Next Calling Date -->
                
                <div class="form-group">
                  <label>Feedback<span class="text-danger">*</span></label>
                  <textarea name="feedback" class="form-control" placeholder="Feedback">{{old('feedback')}}</textarea>
                  @if ($errors->has('feedback'))
                  <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('feedback') }}</strong> </span></p>
                  @endif </div>
                <!-- Feedback --> 
                
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
                  @endif </div>
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
                  @endif </div>
                @else
                <input type="hidden" name="assigned_to" value="{{$collections->staff_user_id}}">
                @endif<!-- Assigned To -->
                <input type="hidden" name="status" value="0">
              </div>
              <div class="text-right"> @if($collections->staff_user_id == auth()->user()->id)
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
                <hr />
                <div class="col-sm-12 mb-2">
                  <button type="submit" class="btn btn-success {{$disable}} {{$disable2}}"  {{$disable}} {{$disable2}} > Submit </button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="col-md-9">
          <div class="row">
            <div class="col-sm-12">
              <div class="card">
                <div class="card-header"><strong>Collection Details</strong> </div>
                <div class="card-body">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered">
                    <tr>
                      <td><strong>{{__('form.name')}}</strong></td>
                      <td>{{ $collections->name }}</td>
                      <td><strong>{{__('form.shop_name')}}</strong></td>
                      <td>{{ $collections->mobile_no }}</td>
                    </tr>
                    <tr>
                      <td><strong>{{__('form.mobile')}}</strong></td>
                      <td>{{ $collections->mobile_no }}</td>
                      <td><strong>{{__('form.alternate_number')}}</strong></td>
                      <td>{{ $collections->alternate_no }}</td>
                    </tr>
                    <tr>
                      <td><strong>{{__('form.creation_date')}}</strong></td>
                      <td>{{ date('d M Y',strtotime($collections->collection_date)) }}</td>
                       <td><strong>{{ __('form.collection_due_date') }}</strong></td>
                      <td>{{ date('d M Y',strtotime($collections->new_date)) }}</td>
                    </tr>
                    <tr>
                      <td><strong>{{__('form.address')}}</strong></td>
                      <td>{{$collections->address}}</td>
                      <td><strong>{{__('form.city')}}</strong></td>
                      <td>{{ (isset($collections->city_id) && $collections->city !='')?$collections->city->name:'' }}</td>
                    </tr>
                    <tr>
                      <td><strong>{{__('form.state')}}</strong> </td>
                      <td>{{(isset($collections->state_id) && $collections->state !='')?$collections->state->name:''}}</td>
                      <td><strong>{{__('form.country')}}</strong></td>
                      <td>{{(isset($collections->country_id) && $collections->country !='')?$collections->country->name:'' }}</td>
                    </tr>
                    <tr>
                      <td><strong>{{__('form.due')}} {{__('form.amount')}}</strong></td>
                      <td>{{ $collections->amount }}</td>
                      <td><strong>{{__('form.balance')}} {{__('form.amount')}}</strong></td>
                      <td>{{ $collections->balance_amount }}</td>
                    </tr>
                    <tr>
                      <td><strong>{{__('form.status')}}</strong></td>
                      <td> @if($collections->status == 0) <span class="badge badge-danger">Open</span> @else <span class="badge badge-success">Closed</span> @endif</td>
                      <td><strong>{{__('form.assigned_to')}}</strong></td>
                      <td>{{ $collections->assigned->first_name .' '.$collections->assigned->last_name }}</td>
                    </tr>
                  </table>
                  
                </div>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="card">
                <div class="card-header"><strong>Feedback History</strong></div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered w-100" id="admins-table">
                      <thead>
                        <tr>
                          <th>Date</th>
                          <th>Feedback</th>
                          <th>Payment Type</th>
                          <th>Collected Amount</th>
                          <th>Balance Amount</th>
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
                        <td> @if($thread->payment_type) <span class='text text-success'>{{ucwords($thread->payment_type)}}</span> @else <span class='text text-danger'> - </span> @endif </td>
                        <td>{{ $thread->collect_amount }}</td>
                        <td>{{ $thread->balance_amount }}</td>
                        <td> @if($thread->status==0) <span class='text text-danger'>Open</span> @elseif($thread->status==1) <span class='text text-success'>Closed</span></td>
                        @else <span class='text text-info'>Closed By SalesMan</span>
                          </td>
                        @endif
                        <td>{{ $thread->assigned->first_name.' '.$thread->assigned->last_name }}</td>
                        <td>{{ $thread->created_at }}</td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan=8 align="center">No Data Available</td>
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

    $(document).on('click','.collect_payment_checkbox',function(){
      if($(this).is(':checked')){
        $('input[name=next_calling_date]').val("<?php echo date('d-m-Y'); ?>");
        $('.next_calling_date').addClass('d-none');
        $('.payment_collection').removeClass('d-none');
        $('input[name=status]').val('2');
        $('select[name=assigned_to]').attr('disabled',true);
      }else{
        $('input[name=amount]').val('');
        $('input[name=next_calling_date]').val("");
        $('.next_calling_date').removeClass('d-none');
        $('.payment_collection').addClass('d-none');
        $('input[name=status]').val('0');
        $('select[name=payment_type]').val('');
        $('select[name=assigned_to]').attr('disabled',false);
      }
    });

    $(document).on('change','select[name=payment_type]',function(){
      var type = $(this).val();
      var amount = '{{ $collections->amount }}';
      if(type == 'full'){
        $('input[name=amount]').val(amount);
      }else{
        $('input[name=amount]').val('');
      }
    });


// payment_collection

</script> 
@endsection
    
{{--End Body --}}
{{--End Html --}} 