
@extends('admin.layout.master')
{{-- Content Body --}}
@section('content')

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
.select2-container .select2-selection--single .select2-selection__rendered {
    padding-top: 0px;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: 
#5897fb;
color:
    white;
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
<main class="app-content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="main-content">
          <h5>Payment Collect </h5>
          <hr />
          <form class="form-horizontal m-t-20" role="form" id="loginform" method="POST" enctype="multipart/form-data" action="{{ route('collection.store') }}" onsubmit="checkamount();">
            {{ csrf_field() }}

            <input type="hidden" name="collection_id" value="{{$collection->id}}">
            <input type="hidden" name="balance_amount" value="{{$collection->balance_amount}}">
            <input type="hidden" name="amount" class="total_amount" value="">
            <div class="">
              <h4 class="card-title m-b-0">
                <div class="arrow-down float-right" onclick="toggleSetion(this.classList,'publish-setion')"></div>
              </h4>
              <div class="row">
                <div class="col-sm-12">
                  <p><strong>Total Collection Amount: </strong>{{$collection->balance_amount}}</p>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Customer Name <span class="text-danger">*</span></label>
                   <input type="text" placeholder="Customer Name" name="name" class="form-control" value="{{ $collection->name }}">
                  </div>
                  <div class=" {{ $errors->has('name') ? ' has-error' : '' }}"> @if ($errors->has('name'))
                    <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('name') }}</strong> </span></p>
                    @endif
                  </div>
                </div><!-- Name -->
                
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Mobile No <span class="text-danger">*</span></label>
                    <input type="text" placeholder="Customer Mobile No" name="mobile_no" class="form-control" value="{{ $collection->mobile_no }}">
                  </div>
                  <div class=" {{ $errors->has('mobile_no') ? ' has-error' : '' }}">
                    @if ($errors->has('mobile_no'))
                    <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('mobile_no') }}</strong> </span></p>
                    @endif
                  </div>
                </div><!-- Mobile Number -->
                
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Alternate Number No</label>
                    <input type="text" placeholder="Alternate Mobile No" name="alternate_no" class="form-control" value="{{$collection->alternate_no}}">
                  </div>
                  <div class=" {{ $errors->has('alternate_no') ? ' has-error' : '' }}"> @if ($errors->has('alternate_no'))
                    <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('alternate_no') }}</strong> </span></p>
                    @endif
                  </div>
                </div><!-- Alternate Number No -->

                <div class="col-md-3">
                  <div class="form-group">
                    <label>Installments <span class="text-danger">*</span></label>
                    <select class="form-control installments" name="installments">
                      <option>-- select installments --</option>
                      <option value="1" selected>1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                  </div>
                  <div class=" {{ $errors->has('collection_date') ? ' has-error' : '' }}">
                    @if($errors->has('collection_date'))
                    <p class="text-danger">
                      <span class="help-block"><strong>
                      {{ $errors->first('collection_date') }}</strong></span>
                    </p>
                    @endif
                  </div>
                </div><!-- Installments -->
              </div>
              <div id="putclonehere"></div>
              <div class="row">
                <div class="col-sm-6">
                  <strong>Total : </strong><p id="total_amount">0.00</p>
                </div>
              </div>
            </div>
            <div class="text-right">
              <hr />
              <button type="submit" class="btn btn-success"> Submit </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="d-none clone-fields">
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label>Calling Date <span class="text-danger">*</span></label>
          <input type="text" placeholder="Date" name="installment[date][]" class="date form-control" value="{{ old('date') }}">
        </div>
        <div class=" {{ $errors->has('date') ? ' has-error' : '' }}"> @if ($errors->has('date'))
          <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('date') }}</strong> </span></p>
          @endif
        </div>
      </div><!-- Calling Date -->
      
      <div class="col-md-3">
        <div class="form-group">
          <label>Collection Amount <span class="text-danger">*</span></label>
          <input type="text" placeholder="Amount" name="installment[amount][]" class="countamount form-control" value="{{ old('amount') }}">
        </div>
        <div class=" {{ $errors->has('amount') ? ' has-error' : '' }}"> @if ($errors->has('amount'))
          <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('amount') }}</strong> </span></p>
          @endif
        </div>
      </div><!-- Collection Amount -->

      <div class="col-md-3">
        <label>Salesman: </label>
        <div class="form-group">
          <select name="installment[staff_user_id][]" id="salesman_select" class="salesman_select form-control">
            @forelse($assigned_to as $salesman)
            <option value="{{ $salesman->id }}" {{ ($salesman->id == old('staff_user_id'))?'selected':'' }}>{{ $salesman->first_name.' '.$salesman->last_name }} <sup>(Level : {{ $salesman->level }})</sup></option>
            @empty
            @endforelse
          </select>
        </div>
      </div><!-- Assigned To -->
      
      <div class="col-md-3"></div>
    
    </div>
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
<!-- Status -->

<script>

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
     
    $(document).ready( function () {
      /*Form Validation*/
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

    $(document).ready(function(){
      $('#putclonehere').html('');
      var total_installments = 1;
      var tds = $('.clone-fields').clone().html();
      for (i = 0; i < total_installments; i++) {
        $(tds).find('input.date').datepicker();
        $('#putclonehere').append(tds);
      }
      reAssignVariableProuctsNames();
      $('input.date').datepicker();
    });

    $(document).on('change','.installments',function(){
      $('#putclonehere').html('');
      var total_installments = $(this).val();
      var tds = $('.clone-fields').clone().html();
      
      for (i = 0; i < total_installments; i++) {
        $('#putclonehere').append(tds);
      }

      $('#putclonehere').find('input.date').each(function() {
         $(this).removeAttr('id').removeClass('hasDatepicker');
         $(this).datepicker();
      });

      reAssignVariableProuctsNames();
      // $('.date').datepicker();
      // $.each(function(index, el) {
      //   console.log(index,el);
      // });
    });

  function reAssignVariableProuctsNames(){
    $('#putclonehere .row').each(function(tr_index,tr_ele){
      $(tr_ele).find('select, input').each(function(td_index, td_ele){
        var elem_name = $(td_ele).attr('name');
        var rest = elem_name.substring(0, elem_name.lastIndexOf("["));
        var last = elem_name.substring(elem_name.lastIndexOf("["), elem_name.length);
        var new_elem_name = rest+'['+tr_index+']';
        // console.log(td_index,td_ele,elem_name,new_elem_name);
        $(td_ele).attr('name',new_elem_name);
        // console.log($(td_ele).attr('name'));
      });
    });
  }

  var carryfwd = '{{$collection->balance_amount}}';
  $(document).ready(function(){
    $('#putclonehere').find('.countamount').val(carryfwd);
    $('#total_amount').text('Rs. '+carryfwd);
    $('.total_amount').val(carryfwd);
  });

  $(document).on('blur','.countamount',function(){
    var total_amount = 0.00;
    $('.countamount').each(function(index, el) {
      console.log(index,el);
      total_amount = parseInt(total_amount) + parseInt(($(el).val())?$(el).val():0.00);
    });
    $('#total_amount').text('Rs. '+total_amount.toFixed(2));
    $('.total_amount').val(total_amount.toFixed(2));
  });

  function checkamount(){
    alert('form submit Onsubmit');
    return false;
  }

</script> 
@endsection
    
{{--End Body --}}
{{--End Html --}} 