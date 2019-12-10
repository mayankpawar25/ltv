@extends('admin.layout.master')

@section('content')
 <main class="app-content">
      
     <div class="row">
        <div class="col-md-12">
           <div class="main-content">
           <h5>Task Schedule Management
           
               <a class="btn btn-primary btn-sm pull-right" href="{{route('admin.tasks.salesmanlist')}}">
                  <i class="fa fa-arrow-left"></i> Back to Task List
                </a>
           
           </h5>
           <hr />
           
                
                 <form action="{{ route('admin.tasks.update') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    {{csrf_field()}}
                   <input type="hidden" name="salesman_id" id="salesman_id_select" value="{{ $task->salesman_id }}">
                   <input type="hidden" name="client_type_id" id="client_type_id" value="">
                   <input type="hidden" name="id" value="{{ $task->id }}">
                    <div class="row">
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label>Task Name: </label>
                          <input type="text" name="name" class="form-control" value="{{ $task->name }}">
                          @if($errors->has('name'))
                          <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('name') }}</strong></span></p>
                          @endif
                        </div>
                      </div>
                       <div class="col-sm-3">
                        <div class="form-group">
                          <label>Task Description: </label>
                          <input type="text" name="description" class="form-control" value="{{ $task->description }}">
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
                              <option value="1" {{ ($task->client_type_id =='1')?'selected':'' }}>Dealer</option>
                              <option value="2" {{ ($task->client_type_id =='2')?'selected':'' }}>Leads</option>
                              <option value="3" {{ ($task->client_type_id =='3')?'selected':'' }}>Customer</option>
                             <!--  <option value="3">Customers</option> -->
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
                          <input type="text" name="task_date" value="{{ date('d-m-Y',strtotime($task->task_date)) }}"  class="form-control date" id="task_date">
                          @if($errors->has('task_date'))
                          <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('task_date') }}</strong></span></p>
                          @endif
                        </div>
                      </div>

                      
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label> from Time: </label>
                          <div class="input-group bootstrap-timepicker">
                            <input type="text" name="from_time" id="from_timepicker" class="from_timepicker form-control" value="{{ date('H:i',strtotime($task->from_time)) }}"/>
                          </div>
                        </div>
                      </div>

                       <div class="col-sm-3">
                          <div class="form-group">
                            <label> To Time: </label>
                            <div class="input-group bootstrap-timepicker">
                              <input type="text" name="to_time" id="to_timepicker" class="to_timepicker form-control" value="{{ date('H:i',strtotime($task->to_time)) }}" />
                            </div>
                          </div>
                      </div>
                    </div>
               
                    <div class="text-right">
                    <hr />
                      <button type="submit" class="btn btn-success">Save</button>
                    </div>
                  </form>
                </div>
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
<style>
.clock-timepicker{
	width:100%;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    top: 0px !important;
}

</style>
  
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
        dateFormat: "dd-mm-yy"
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
  
  $(document).ready(function($) {
    $(".select2").select2();
    var selected_salesman = "{{ $task->salesman_id }}";
    var client_id = "{{ $task->client_type_id }}";
    var selected_client = "{{ $task->client_id }}";
    $.ajax({
      url: "{{ route('get.salesman') }}",
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      method: 'POST',
      data : { selected_salesman:selected_salesman },
      success: function(resp) {
        $('#salesman_select').html(resp.html);
      }
    });

    $.ajax({
      url: "{{ route('get.salesmandata') }}",
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      method: 'POST',
      data  : { 
          salesman_id : selected_salesman,
          client_id : client_id,
          selected_client : selected_client,
      },
      success: function(data) {
        $('#client_list').html(data.html);
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