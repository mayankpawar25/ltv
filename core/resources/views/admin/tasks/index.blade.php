@extends('admin.layout.master')
@section('content')
<style type="text/css" media="screen">
.fc-agenda-slots .unavailable{
  background-color: #e6e6e6;

}  
</style>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
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
            <!-- <h3 class="tile-title float-left">All Product Attributes</h3>-->
              <div class="float-right icon-btn">
                <?php 
                if(empty(auth()->user()->is_administrator)){ ?>
                    <a href="{{route('admin.tasks.create')}}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Add Task</a>
                   <!--  <a href="{{route('admin.tasks.arrivals',Auth::id())}}" class="" title=""><i class="fa fa-map-marker"></i>View Arrival</a> -->
                <?php } else { ?>
                  <a class="btn btn-info" href="{{route('admin.tasks.salesmanlist')}}">
                    <i class="fa fa-arrow-left"></i> Back to Salesman List
                  </a>
               <?php }?>
                <p style="clear:both;margin-top:20px;"></p>
              
              </div>
              <div class="float-left icon-btn">
                <h4>#{{Auth::user()->code.' '.Auth::user()->first_name.' '.Auth::user()->last_name}}</h4>
                <p style="clear:both;margin-top:50px;"></p>
              </div>
              <p style="clear:both;margin-top:50px;"></p>
                <!-- <h3 class="tile-title pull-left">Task Calender<strong></strong></h3> -->
                <div id='calendar'></div>
           </div>
        </div>
     </div>
  </main>

<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
<script>
    $(document).ready(function() {
      jQuery("th.fc-agenda-axis").hide();
        // page is now ready, initialize the calendar...
        var today = moment();
        $('#calendar').fullCalendar({
            // put your options and callbacks here
            header: {
              left: 'prev,next today',
              center: 'title',
              right: 'month,agendaWeek,agendaDay,list'
            },
            //defaultView: 'agendaWeek',
            firstDay: 1,
            defaultDate: today,
            editable: true,
            events : [
                @foreach($tasks as $task)
                {
                    title : '{{ $task->name }}',
                    start : '{{ $task->task_date }} {{ $task->from_time }}',
                    end :   '{{ $task->task_date }} {{ $task->to_time }}',
                    url : '{{ route('admin.tasks.show',[$task->id,$task->client_type_id,$task->client_id]) }}'
                },
                @endforeach
            ],

           
        })
    });
</script>
@endsection



