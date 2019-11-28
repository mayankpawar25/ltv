@extends('admin.layout.master')

@section('content')
  <?php
 /*  print_r($show);
    die;*/
  ?>
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
        <div class="col-md-3">
          
        </div>
        <div class="col-md-12">
           <div class="tile">
             <div class="float-right icon-btn">
               <a class="btn btn-info" href="{{route('admin.tasks.salesmanlist')}}">
                  <i class="fa fa-arrow-left"></i> Back to Task List
                </a>
              </div>
               <p style="clear:both;margin-top:50px;"></p>

                 <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Task Title</th>
                        <th>Salesman Name</th>
                        <th>Task Description</th>
                        <th>Task Date</th>
                        <th>Time: From - To</th>
                        <th>Client Type</th>
                        <th>Client Name</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>{{ $show->name }}</td>
                        <td>{{ $show->salesman_first_name }} {{ $show->salesman_last_name }}</td>
                        <td>{{ $show->description }}</td>
                        <td>{{ $show->task_date }}</td>
                        <td>{{ date('H:i', strtotime($show->from_time))}}-{{ date('H:i', strtotime($show->to_time))}}</td>
                        <?php 
                          if($show->client_type_id ==1){ ?>
                            <td>Dealer</td>
                          <?php } elseif ($show->client_type_id == 2) { ?>
                             <td>Leads</td>
                         <?php  } else { ?>
                           <td>Customer</td>
                        <?php }
                        ?>
                        <?php 
                        if(isset($show->client_last_name)){ ?>
                           <td>{{  ucfirst($show->client_name)}} {{ $show->client_last_name }}</td>
                       <?php } else { ?>
                        <td>{{ ucfirst($show->client_name) }} : {{ $show->shop_name }} </td>
                       <?php }
                        ?>
                        <td><a href="{{ route('admin.tasks.edit',$show->id) }}"><i class="fa fa-edit"></i></a></td>
                      </tr>

                    </tbody>
                  </table>
                  
              
           </div>
        </div>
     </div>
  </main>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<script>
    $('.date').datepicker({
        autoclose: true,
        dateFormat: "yy-mm-dd"
    });
</script>
                
@endsection





