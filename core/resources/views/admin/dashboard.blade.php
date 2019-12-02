@extends('admin.layout.master')

@push('styles')
  <style media="screen">
    a.info {
      text-decoration: none;
    }
  </style>
@endpush

@section('content')
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
  <main class="app-content">
    <div class="app-title">
      <div>
        <h1>Dashboard</h1>
      </div>
    </div>
<?php 
if(empty(auth()->user()->is_administrator)){ 
$mytime = Carbon\Carbon::now();
      $task_date = $mytime->toDateString();  
  $tasks = \App\Task::where('task_date', $task_date)->where('salesman_id',auth()->user()->id)->orderBy('created_at', 'ASC')->get();

?>
    
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
              left: ' today',
              center: 'title',
              right: 'agendaDay,list'
            },
            height: 450,
            defaultView: 'agendaDay',
            firstDay: 1,
            defaultDate: today,
            editable: true,
            events : [
                @foreach($tasks as $task)
                {
                    title : '{{ $task->name }}',
                    start : '{{ $task->task_date }} {{ $task->from_time }}',
                    end :   '{{ $task->task_date }} {{ $task->to_time }}',
                    url : '{{ route('admin.tasks.show',[$task->id,$task->client_type_id,$task->client_id]) }}',
                    color:''
                },
                @endforeach
            ],
          

        })
    });
</script>
<div class="row">
           <div class="col-md-12">
               <div class="main-content" style="margin-bottom:30px;">
                <div class="">
                    <h5 class="mb-4">
                      To Do List
                      <a href="{{route('admin.tasks.create')}}" class="btn btn-primary float-right">Add Task</a>
                   
                      </h5>
                  </div>
                  <hr />
                    <div id='calendar' style="width: 100%;" ></div>
               </div>
           </div>
       </div>

<?php } ?>
    <div class="row">
      <div class="col-md-6 col-lg-4">
        <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
          <a class="info" href="{{route('admin.allUsers')}}">
            <h4>TOTAL USERS</h4>
            <p><b>{{\App\User::count()}}</b></p>
          </a>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="widget-small danger coloured-icon"><i class="icon fa fa-times fa-3x"></i>
          <a class="info" href="{{route('admin.bannedUsers')}}">
            <h4>BANNED USERS</h4>
            <p><b>{{\App\User::where('status', 'blocked')->count()}}</b></p>
          </a>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="widget-small success coloured-icon"><i class="icon fa fa-check fa-3x"></i>
          <a class="info" href="{{route('admin.verifiedUsers')}}">
            <h4>VERIFIED USERS</h4>
            <p><b>{{\App\User::where('email_verified', 1)->where('sms_verified', 1)->count()}}</b></p>
          </a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 col-lg-4">
        <div class="widget-small danger coloured-icon"><i class="icon fa fa-mobile fa-3x"></i>
          <a class="info" href="{{route('admin.mobileUnverifiedUsers')}}">
            <h4>MOBILE UNVERIFIED USERS</h4>
            <p><b>{{\App\User::where('sms_verified', 0)->count()}}</b></p>
          </a>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="widget-small danger coloured-icon"><i class="icon fa fa-envelope fa-3x"></i>
          <a class="info" href="{{route('admin.emailUnverifiedUsers')}}">
            <h4>EMAIL UNVERIFIED USERS</h4>
            <p><b>{{\App\User::where('email_verified', 0)->count()}}</b></p>
          </a>
        </div>
      </div>
      <?php 
      $mytime = Carbon\Carbon::now();
      $task_date = $mytime->toDateString();  
      if(empty(auth()->user()->is_administrator)){ ?>
      <div class="col-md-6 col-lg-4">
        <div class="widget-small info coloured-icon"><i class="icon fa fa-envelope fa-3x"></i>
          <a class="info" href="{{route('admin.salesmans.task',auth()->user()->id)}}">
           <h4>Salesman Task</h4>
            <p><b>{{\App\Task::where('task_date', $task_date)->where('salesman_id',auth()->user()->id)->count()}}</b></p>
          </a>
        </div>
      </div>
      <?php }else { ?>
       <div class="col-md-6 col-lg-4">
        <div class="widget-small info coloured-icon"><i class="icon fa fa-envelope fa-3x"></i>
          <a class="info" href="{{route('admin.tasks.salesmanlist')}}">
           <h4>Salesman Task</h4>
           </a>
        </div>
      </div>        
      <?php } ?>
    </div>

     <!--<div class="row">
           <div class="col-md-12">
               <div class="tile">
                   <h3 class="tile-title">Product Upload Chart (Monthly)</h3>
                   <div class="embed-responsive embed-responsive-16by9">
                       <canvas class="embed-responsive-item" id="lineChartDemo"></canvas>
                   </div>
               </div>
           </div>
       </div>--> 
  </main>
@endsection

@push('scripts')
  <script type="text/javascript">
         var d = {!! json_encode($month) !!};
         var m =  {!! json_encode($sold) !!};
         var data = {
             labels: d,
             datasets: [
                 {
                     label: "My First dataset",
                     fillColor: "rgba(47, 79, 79,0.2)",
                     strokeColor: "rgba(47, 79, 79,1)",
                     pointColor: "rgba(47, 79, 79,1)",
                     pointStrokeColor: "#fff",
                     pointHighlightFill: "#fff",
                     pointHighlightStroke: "rgba(220,220,220,1)",
                     data: m
                 }
             ]
         };


         var ctxl = $("#lineChartDemo").get(0).getContext("2d");
         var lineChart = new Chart(ctxl).Line(data);

     </script>
@endpush
