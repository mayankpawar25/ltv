
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
          <h5>{{__('form.add')}} {{__('form.new_collection')}}</h5>
          <hr />
          <form class="form-horizontal m-t-20" role="form" id="loginform" method="POST" enctype="multipart/form-data" action="{{ route('collection.store') }}">
            {{ csrf_field() }}
            <div class="">
              <h4 class="card-title m-b-0">
                <div class="arrow-down float-right" onclick="toggleSetion(this.classList,'publish-setion')"></div>
              </h4>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>{{__('form.customer_name')}}<span class="text-danger">*</span></label>
                   <input type="text" placeholder="Customer Name" name="name" class="form-control" value="{{ old('name') }}">
                  </div>
                  <div class=" {{ $errors->has('name') ? ' has-error' : '' }}"> @if ($errors->has('name'))
                    <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('name') }}</strong> </span></p>
                    @endif
                  </div>
                </div><!-- Name -->
                
                <div class="col-md-3">
                  <div class="form-group">
                    <label>{{__('form.mobile')}}<span class="text-danger">*</span></label>
                    <input type="text" placeholder="Customer Mobile No" name="mobile_no" class="form-control" value="{{ old('mobile_no') }}">
                  </div>
                  <div class=" {{ $errors->has('mobile_no') ? ' has-error' : '' }}">
                    @if ($errors->has('mobile_no'))
                    <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('mobile_no') }}</strong> </span></p>
                    @endif
                  </div>
                </div><!-- Mobile Number -->
                
                <div class="col-md-3">
                  <div class="form-group">
                    <label>{{__('form.alt_number')}}</label>
                    <input type="text" placeholder="Alternate Mobile No" name="alternate_no" class="form-control" value="{{old('alternate_no')}}">
                  </div>
                  <div class=" {{ $errors->has('alternate_no') ? ' has-error' : '' }}"> @if ($errors->has('alternate_no'))
                    <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('alternate_no') }}</strong> </span></p>
                    @endif
                  </div>
                </div><!-- Alternate Number No -->

                <div class="col-md-3">
                  <div class="form-group">
                    <label>{{__('form.installments')}} <span class="text-danger">*</span></label>
                    <select class="form-control installments" name="installments">
                      <option>-- select installments --</option>
                      <option value="1" selected>1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                  </div>
                </div><!-- Installments -->
              </div>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>{{__('form.calling_date')}}</th>
                    <th>{{__('form.amount')}}</th>
                    <th>{{__('form.assigned')}} {{__('form.to')}}</th>
                  </tr>
                </thead>
                <tbody id="putclonehere"></tbody>
              </table>
              <div class="row">
                <div class="col-sm-6">
                  <strong>{{__('form.total')}} : </strong><p id="total_amount">0.00</p>
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

<table class="d-none">
  <tbody class="clone-fields">
    <tr>
      <td>
        <input type="text" placeholder="Date" name="installment[date][]" class="date2 form-control" value="{{ old('date') }}">
      </td>
      <td>
        <input type="text" placeholder="Amount" name="installment[amount][]" class="countamount form-control" value="{{ old('amount') }}">
      </td>
      <td>
        <select name="installment[staff_user_id][]" id="salesman_select" class="salesman_select form-control">
          @forelse($salesman as $salesman)
            <option value="{{ $salesman->id }}" {{ ($salesman->id == old('staff_user_id'))?'selected':'' }}>{{ $salesman->first_name.' '.$salesman->last_name }} <sup>(Level : {{ $salesman->level }})</sup></option>
          @empty
          @endforelse
        </select>
      </td>
    </tr>
  </tbody>
</table>
<div class="row d-none">
  <div class="col-md-3">

    <div class=" {{ $errors->has('date') ? ' has-error' : '' }}"> @if ($errors->has('date'))
      <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('date') }}</strong> </span></p>
      @endif
    </div>
  </div><!-- Calling Date -->

  <div class="col-md-3">
    <div class="form-group">

    </div>
    <div class=" {{ $errors->has('amount') ? ' has-error' : '' }}"> @if ($errors->has('amount'))
      <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('amount') }}</strong> </span></p>
      @endif
    </div>
  </div><!-- Collection Amount -->

  <div class="col-md-3">
    <label>{{__('form.assigned')}} {{__('form.to')}}: </label>
    <div class="form-group">

    </div>
  </div><!-- Assigned To -->

  <div class="col-md-3"></div>

</div>
</main>
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
      console.log(tds);
      for (i = 0; i < total_installments; i++) {
        $(tds).find('input.date2').datepicker({
          autoclose: true,
          dateFormat: "dd-mm-yy"
        });
        $('#putclonehere').append(tds);
      }
      reAssignVariableProuctsNames();
      $('input.date2').datepicker({
        autoclose: true,
        dateFormat: "dd-mm-yy"
      });
      console.log('date initiated');
    });

    $(document).on('change','.installments',function(){
      $('#putclonehere').html('');
      var total_installments = $(this).val();
      var tds = $('.clone-fields').clone().html();
      
      for (i = 0; i < total_installments; i++) {
        $('#putclonehere').append(tds);
      }

      $('#putclonehere').find('input.date2').each(function() {
         $(this).removeAttr('id').removeClass('hasDatepicker');
         $(this).datepicker({
          autoclose: true,
          dateFormat: "dd-mm-yy"
         });
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
        console.log($(td_ele).attr('name'));
      });
    });
  }


   /* function reInitialize(){
      $('#putclonehere').find('input.date').each(function(index, el) {
        $('.date').datepicker();
        console.log($(this));
      });
    }*/

  $(document).on('blur','.countamount',function(){
    var total_amount = 0.00;
    $('.countamount').each(function(index, el) {
      console.log(index,el);
      total_amount = parseInt(total_amount) + parseInt(($(el).val())?$(el).val():0.00);
    });
    $('#total_amount').text('Rs. '+total_amount.toFixed(2));
  });

</script> 
@endsection
    
{{--End Body --}}
{{--End Html --}} 