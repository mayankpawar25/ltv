@extends('admin.layout.master')

@section('content')
  <?php
/* print_r($show);
    die;*/
  ?>
  <main class="app-content">
      
     <div class="row">
       
        <div class="col-md-12">
          
            <div class="main-content">
           <h5> Task Schedule Management 
           
            <a class="btn btn-primary btn-sm pull-right ml-2" href="{{ route('admin.tasks.edit',$show->id) }}"><i class="icon icon-pencil"></i></a>
           <a class="btn btn-primary btn-sm pull-right" href="{{route('admin.tasks.salesmanlist')}}">
                  <i class="fa fa-arrow-left"></i> Back to Task List
             </a>
            
           </h5>
              <hr />

              

<div class="table-responsive">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered">
  <tr>
    <td><strong>Task Title</strong></td>
     <td>{{ $show->name }}</td>
    <td><strong>Salesman Name</strong></td>
   <td>{{ $show->salesman_first_name }} {{ $show->salesman_last_name }}</td>
  </tr>
  <tr>
    <td><strong>Task Description</strong></td>
     <td>{{ $show->description }}</td>
    <td><strong>Task Date</strong></td>
    <td>{{ date('d-m-Y',strtotime($show->task_date)) }}</td>
  </tr>
  <tr>
    <td><strong>Time: From - To</strong></td>
   <td>{{ date('H:i', strtotime($show->from_time))}}-{{ date('H:i', strtotime($show->to_time))}}</td>
    <td><strong>Client Type</strong></td>
     <?php 
                          if($show->client_type_id ==1){ ?>
                            <td>Dealer</td>
                          <?php } elseif ($show->client_type_id == 2) { ?>
                             <td>Leads</td>
                         <?php  } else { ?>
                           <td>Customer</td>
                        <?php }
                        ?>
  </tr>
  <tr>
    <td><strong>Client Name</strong></td>
      <?php 
                        if(isset($show->client_last_name)){ ?>
                           <td>{{  ucfirst($show->client_name)}} {{ $show->client_last_name }}</td>
                       <?php } else { ?>
                        <td>{{ ucfirst($show->client_name) }} : {{ $show->shop_name }} </td>
                       <?php }
                        ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</div> 
                  
              
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





