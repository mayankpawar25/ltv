@extends('admin.layout.master')

@section('content')
 <main class="app-content">
     <div class="app-title">
       <div>
          <h1><i class="fa fa-dashboard"></i>Task Schedule Management</h1>
       </div>
       <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
       </ul>
    </div>
     <div class="row">
        <div class="col-md-12">
           <div class="tile">
            <div class="float-right icon-btn">
                <a class="btn btn-success" href="{{route('admin.tasks.salesmanlist')}}">
                  <i class="fa fa-arrow-left"></i> Back to Salesman List
                </a>
              </div>
              <p style="clear:both;margin-top:50px;"></p>
                <div class="col-sm-12">
                 <form action="{{ route('admin.tasks.store') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    {{csrf_field()}}
                   <input type="hidden" name="country_id" id="country_select" value="1">
                   <input type="hidden" name="salesman_id" id="salesman_id_select" value="">
                   <input type="hidden" name="client_type_id" id="client_type_id" value="1">
                    <div class="row">
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label>Task Name: </label>
                          <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                          @if($errors->has('name'))
                          <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('name') }}</strong></span></p>
                          @endif
                        </div>
                      </div>
                       <div class="col-sm-3">
                        <div class="form-group">
                          <label>Task Description: </label>
                          <input type="text" name="description" class="form-control" value="{{ old('description') }}">
                          @if($errors->has('description'))
                          <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('description') }}</strong></span></p>
                          @endif
                        </div>
                      </div>
                       <div class="col-sm-3">
                       <div class="form-group">
                          <label>Select Salesman: </label>
                          <select name="salesman_id" id="salesman_select" class="salesman_select form-control select2"> </select>
                        </div>

                        </div>
                        <div class="col-sm-3">
                           <div class="form-group">
                            <label>Client Type: </label>
                            <select name="client_type_id"  class="form-control" required="true" id="client_select">

                              <option value="">-- Select Client Type --</option>
                              <option value="1">Dealer</option>
                              <option value="2">Leads</option>
                              <option value="3">Customers</option>
                            </select>
                          </div> 
                        </div>
                      <div class="col-sm-3">
                       <div class="form-group">
                          <label>Select Client: </label>
                          <select name="client_id" id="client_list" class="client_list form-control select2"> </select>
                        </div>
                        </div>
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label>Task Date: </label>
                          <input type="text" name="task_date" class="form-control date" id="task_date">
                          @if($errors->has('task_date'))
                          <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('task_date') }}</strong></span></p>
                          @endif
                        </div>
                      </div>

                      
                       <div class="col-sm-3">
                       <div class="form-group">
                          <label> from Time: </label>
                         <div class="input-group bootstrap-timepicker">
                          <input type="text" name="from_time" id="from_timepicker" class="from_timepicker form-control" value="09:00"/>
                        </div>
                        </div>
                      </div>

                       <div class="col-sm-3">
                         <div class="form-group">
                          <label> To Time: </label>
                         <div class="input-group bootstrap-timepicker">
                           <input type="text" name="to_time" id="to_timepicker" class="to_timepicker form-control" value="10:00" />
                         </div>

                        </div>
                      </div>

                    
                      </div>
                      
                     <!--  <div class="col-sm-3">
                        <div class="form-group">
                          <label>State: </label>
                          <select name="state_id" id="state_select" class="form-control select2"></select>
                          @if($errors->has('state'))
                          <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('state') }}</strong></span></p>
                          @endif
                        </div>
                      </div> -->


                     <!--  <div class="col-sm-3">
                        <div class="form-group">
                          <label>City: </label>
                          <select name="city_id" id="city_select" class="form-control select2"> </select>
                           @if($errors->has('city'))
                          <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('city') }}</strong></span></p>
                          @endif
                        </div>
                      </div> -->
                      
                     
                  <!--  <div class="col-sm-3">
                        <div class="form-group">
                          <label>Task Description: </label>
                          <textarea type="text" name="description" class="form-control">{{ old('description') }}</textarea>
                        </div>
                      </div>  -->

                    </div>
                 
                
                   <!--  <h4>Task</h4> -->
             <!--        <div class="row">
                      <div class="col-sm-2">
                        <label>Select area: </label>
                        <select name="zipcode_id[]" id="zipcode_select" class="zipcode_select form-control select2"> </select>
                      </div>
                       <div class="col-sm-2">
                        <div class="form-group">
                          <label> To Time: </label>
                         <div class="input-group bootstrap-timepicker">
                           <input type="text" name="to_time[]" id="to_timepicker" class="to_timepicker form-control" value="10:00" />
                         </div>

                        </div>
                      </div>

                       <div class="col-sm-2">
                        <div class="form-group">
                          <label> from Time: </label>
                         <div class="input-group bootstrap-timepicker">
                          <input type="text" name="from_time[]" id="from_timepicker" class="from_timepicker form-control" value="01:00"/>
                        </div>

                        </div>
                      </div>
                      <div class="col-sm-2 shop_list">
                        <label>Select Shop: </label>
                       <select name="shop_id[]" id="shop_select" class="shop_select form-control"> </select>
                      </div>
                      <div class="col-sm-4">
                        <button type="button" class="btn btn-info btn-sm" id="add_more_docs">Add More Schedule</button>
                      </div>
                    </div> -->
                   <!--  <span id="put_clone_here"></span> -->
                    <div class="form-group">
                      <button type="submit" class="btn btn-success">Save</button>
                    </div>
                  </form>
                 <!--  <div class="row" id="clone_this_row" style="display:none">
                      <div class="col-sm-2">
                        <label>Select area: </label>
                        <select name="zipcode_id[]"  class="zipcode_select form-control"> </select>
                      </div>
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label> To Time: </label>
                         <div class="input-group bootstrap-timepicker">
                           <input type="text" name="to_time[]" id="to_timepicker" class="to_timepicker form-control" value="10:00" />
                         </div>

                        </div>
                      </div>
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label> from Time: </label>
                         <div class="input-group bootstrap-timepicker">
                          <input type="text" name="from_time[]" id="from_timepicker" class="from_timepicker form-control" value="01:00" />
                        </div>

                        </div>
                      </div>
                      <div class="col-sm-2 shop_list">
                        <label>Select Shop: </label>
                         <select name="shop_id[]" id="shop_select_clone" class="shop_select form-control"> </select>
                      </div>
                  </div> -->
                    
                  
                </div>
           </div>
        </div>
     </div>
  </main>

  <!-- <main class="app-content">
     <div class="app-title">
       <div>
          <h1><i class="fa fa-dashboard"></i>Task Schedule Management</h1>
       </div>
       <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
       </ul>
    </div>
     <div class="row">
       
        <div class="col-md-12">
           <div class="tile">
             <div class="float-left icon-btn">
                <a class="btn btn-info" href="{{route('admin.tasks.index')}}">
                  <i class="fa fa-arrow-left"></i> Back to Schedule Calander
                </a>
              </div>
               <p style="clear:both;margin-top:50px;"></p>
                <form action="{{ route('admin.tasks.store') }}" method="post">
                {{ csrf_field() }}
                Task name:
                <br />
                <input type="text" name="name" />
                <br /><br />
                Task description:
                <br />
                <textarea name="description"></textarea>
                <br /><br />
                Task Date:
                <br />
                <input type="text" name="task_date" class="date" />
                <br /><br />
                <input type="submit" value="Save" />
              </form>
              
           </div>
        </div>
     </div>
  </main> -->
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js">
</script>
<!-- Time Picker Clock -->
  <script src="{{asset('assets/admin/js/jquery-clock-timepicker.min.js')}}"></script>
  <!-- Time Picker Clock -->
<script>
  $('.date').datepicker({
        autoclose: true,
        dateFormat: "yy-mm-dd"
  });
 
  $('.to_timepicker').clockTimePicker({
    duration:false,
    afternoonHoursInOuterCircle:false,
     alwaysSelectHoursFirst:false,

  });
  $('.from_timepicker').clockTimePicker({
    duration:false,
    afternoonHoursInOuterCircle:false,
     alwaysSelectHoursFirst:false,

  });

  
  /*Clone Section*/
  // $(document).on('click','#add_more_docs',function(){
  //   var clone = $('#clone_this_row').clone().html();
  //   $('#put_clone_here').append('<div class="row">'+clone+'</div>');

  //     /*Clone Time Picker*/    
  //     $('.to_timepicker').clockTimePicker({
  //       duration:false,
  //       afternoonHoursInOuterCircle:false,
  //       alwaysSelectHoursFirst:false,
  //     });

  //     $('.from_timepicker').clockTimePicker({
  //       duration:false,
  //       afternoonHoursInOuterCircle:false,
  //       alwaysSelectHoursFirst:false,
  //     });
  //     /*Clone Time Picker End*/    
  // });



    // $(document).ready(function(){
    //   $(".select2").select2({
    //     placeholder: "-- Select Area --",
    //   });
    // });

  $(document).ready( function () {
    //$(".select2").select2();
    // Country Dropdown
    /*var country_id;
    country_id = $('#country_select').val();
    $.ajax({
      url: "{{ route('get.states') }}",
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      success: function(resp) {
        $('#state_select').html(resp.html);
      }
    });*/

    // State Dropdown
    var country_id;
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

     // City Dropdown
    var city_id;
    $(document).on('change', '#city_select', function(event) {
      city_id = $('#city_select').val();
      $.ajax({
        url: "{{ route('get.zipcode') }}",
        headers: {
          'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        method: 'POST',
        data  : { city_id : city_id},
        success: function(data) {
          $('.zipcode_select').html(data.html);
          $('.clone_zipcode_select ').html(data.html);
        }
      });
    });

     // Shop Dropdown
    $(document).on('change', '.zipcode_select', function(event) {
      var zipcode_id = $(this).val();
      console.log(zipcode_id);
      var option = $(this).parent().parent().find('div.shop_list > select');
      var html = '';
      $.ajax({
        url: "{{ route('get.shop') }}",
        headers: {
          'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        method: 'POST',
        data  : { zipcode_id : zipcode_id},
        success: function(data) {
          $(option).html(data.html);
          // $('.shop_select').html(data.html);
        }
      });
      // $(this).parent().parent().find('div.col-sm-2 select.shop_select').html(html);
      // console.log($(this).parent().parent().find('select.shop_select').html(html));
    });

    $(document).on('change', '.clone_zipcode_select', function(event) {
      zipcode_id = $(this).val();
      aler('here');
       var html;
      $.ajax({
        url: "{{ route('get.shop') }}",
        headers: {
          'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        method: 'POST',
        data  : { zipcode_id : zipcode_id},
        success: function(data) {
          html += data.html;
          // $('.clone_shop_select').html(data.html);
        }
      });
      console.log($(this).parent().parent().find('select.clone_shop_select').html());
      $(this).parent().parent().find('select.clone_shop_select').html(html);
    });

    }); //End


  $(document).ready(function($) {
    $(".select2").select2();
    // Salesman List
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

     var salesman;
      $(document).on('change', '#salesman_select', function(event) {
        salesman = $('#salesman_select').val();
        $('#salesman_id_select').val(salesman);
      });
       $(document).on('change', '#client_select', function(event) {
        client = $('#client_select').val();
        $('#client_type_id').val(client);

        var salesman_id = $('#salesman_id_select').val();
        var client_id = $('#client_type_id').val();

       $.ajax({
          url: "{{ route('get.salesmandata') }}",
          headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
          },
          method: 'POST',
          data  : { 
              salesman_id : salesman_id,
              client_id : client_id
          },
          success: function(data) {
            $('#client_list').html(data.html);
          }
        });
      });
  });
  </script>     
@endsection





