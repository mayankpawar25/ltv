@extends('admin.layout.master')

@section('title', 'Salesman List')

@section('headertxt', 'Salesman List')

@section('content')
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-dashboard"></i>Salesman Sales Score</h1>
    </div>
  <!--  <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    </ul>-->
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="tile">
        <form action="{{ route('admin.salesman.addsalesscore') }}" method="post" accept-charset="utf-8" id="salesscores-register">
          {{csrf_field()}}
          <h3 class="tile-title pull-left">Add Sales Score<strong></strong></h3>
          <p style="clear:both;margin:0px;"></p>
          <div class="form-group">
            <label>Order ID: </label>
            <input type="text" name="unique_id" class="form-control" value="" id="unique_id">
            <div class=" {{ $errors->has('unique_id') ? ' has-error' : '' }}">
              @if ($errors->has('unique_id'))
              <p class="text-danger">
                <span class="help-block"><strong>{{ $errors->first('unique_id') }}</strong></span>
              </p>
              @endif
            </div>
          </div>
           <div class="form-group">
            <label>Order Amount: </label>
            <input type="text" name="order_amount" class="form-control" value="0.00" id="order_amount" disabled>
            <div class=" {{ $errors->has('order_amount') ? ' has-error' : '' }}">
              @if ($errors->has('order_amount'))
              <p class="text-danger">
                <span class="help-block"><strong>{{ $errors->first('order_amount') }}</strong></span>
              </p>
              @endif
            </div>
          </div>
          <label>Select Salesman: </label>
          <div class="form-group">
              <select name="staff_user_id" id="salesman_select" class="salesman_select form-control select2"> </select>
          
          </div>
         
          <div class="form-group">
            <label>Salesman Remarks: </label>
             <textarea type="text" name="staff_user_remarks" class="form-control" id="address_by_lat_long">{{ old('staff_user_remarks') }}</textarea>
              @if($errors->has('staff_user_remarks'))
              <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('staff_user_remarks') }}</strong></span></p>
              @endif
           </div>


          <div class="form-group">
            <button type="submit" class="btn btn-success">Save</button>
          </div>
        </form>
      </div>
    </div>
    <div class="col-md-9">
      <div class="tile">
        <div class="col-lg-12">
            <div class="">
                <div class="">
                    <div class="">
                      <h3 class="tile-title">
                        {{--<a href="{{route('admin.tasks.create')}}" class="btn btn-success float-right"><i class="fa fa-plus"></i> Add Salesman Task</a> --}}
                        
                      <p style="clear:both;margin-top:50px;"></p>
                        </h3>
                    </div>
                    <div class="sellers-product-inner">
                        <div class="bottom-content">
                            <table class="table table-default" id="datatableOne">
                                <thead>
                                    <tr>
                                      <th>S.No.</th>
                                      <th>Order ID</th>
                                      <th>Salesman Name</th>
                                      <th>Customer Name</th>
                                      <th>Remarks</th>
                                     <!--  <th>Tax</th> -->
                                      <th>Sub Total</th>
                                      <th>Amount</th>
                                     <!--  <th class="text-right">Action</th> -->
                                    </tr>
                                </thead>
                                 <?php if (count($salesscores) == 0){ ?>
                                   <tr>
                                  <td colspan="3">&nbsp;</td>
                                  <td><b style="color: red;">No Result Found</b></td>
                                </tr>
                              <?php  }else{ ?>

                                <tbody>
                                  @php
                                    $i = $salesscores->perPage() * ($salesscores->currentPage() - 1); 
                                   @endphp
                                  <?php
                                  $sum = array();
                                  ?>

                                  @foreach($salesscores as $user)

                                  <tr>
                                   <td>{{ ++$i }}</td>
                                    <td>#{{ $user->unique_id }}</td>
                                    <td>
                                    <?php 
                                     $salesman_data = App\Models\StaffUser::where('id' ,$user->staff_user_id)->get();
                                     $salesman = json_decode($salesman_data);
                                     ?>
                                    
                                     <?php echo $salesman[0]->first_name.' '.$salesman[0]->last_name; ?>
                                    </td>
                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                     @if ( !empty($user->staff_user_remarks))
                                       <td>{{ $user->staff_user_remarks }} </td>
                                     @else
                                      <td>---</td>
                                     @endif
                                      <!-- <td>
                                      @if($user->inactive == NULL)
                                        <span class="badge badge-success">Active</span>
                                      @else
                                        <span class="badge badge-success">Inactive</span>
                                      @endif
                                    </td> -->
                                    <!--  <td>{{ $user->tax }}</td> -->
                                     <td >
                                      {{ number_format($user->subtotal,2) }}
                                       <?php $b[] = array_sum((array)$user->subtotal); ?>
                                    </td>
                                    <td >
                                      {{ number_format($user->total,2) }}
                                     <?php $a[] = array_sum((array)$user->total); ?>
                                    </td>
                                  </tr>
                                  @endforeach
                                  <tr>
                                  <td colspan="5">&nbsp;</td>
                                  <td><b><?php echo number_format(array_sum($b),2);   ?></b></td>
                                  <td><b><?php echo number_format(array_sum($a),2);   ?></b></td>
                                </tr>
                                </tbody>
                            <?php  } ?>
                            </table>
                            
                        </div>
                        <?php 
                        if (count($salesscores) != 0) { ?>
                          <div class="row">
                          <div class="col-md-12">
                            <div class="right" style="float: right; margin-top: 10px;">
                               {{$salesscores->links()}}
                            </div>
                          </div>
                        </div>
                       <?php  }
                        ?>
                        
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
  <div class="main-content">
   <form>
      <div class="form-row">
         <div class="form-group col-md-2">
            <?php
               echo form_dropdown('month', $data['months'] , strtolower(date("F")) , "class='form-control select2 ' ");
               ?>
         </div>
      </div>
   </form>
   <canvas id="monthly_conversion"></canvas>
</div>
</main>
<script>

window.chartColors = {
  red: 'rgb(255, 99, 132)',
  orange: 'rgb(255, 159, 64)',
  yellow: 'rgb(255, 205, 86)',
  green: 'rgb(75, 192, 192)',
  blue: 'rgb(54, 162, 235)',
  purple: 'rgb(153, 102, 255)',
  grey: 'rgb(201, 203, 207)'
};



var color = Chart.helpers.color;
// Conversion by Month Chart
    var conversion_by_month_chart_data = {
          labels: <?php echo json_encode($data['conversion_by_month']['labels']); ?>,
          datasets: [{
            label: '# of Tomatoes',
            data: <?php echo json_encode($data['conversion_by_month']['data']); ?>,
            backgroundColor: color(window.chartColors.green).alpha(0.5).rgbString()
          }]
        };
    var ctx = document.getElementById("monthly_conversion");
    window.conversion_by_month_chart = new Chart(ctx, {
        type: 'bar',
        data: conversion_by_month_chart_data,
        options: {
          legend: {
              display: false
          },
          tooltips: {
              callbacks: {
                 label: function(tooltipItem) {
                        return tooltipItem.yLabel;
                 }
              }
          },

          responsive: true,
        title: {
          display: true,
          text: '<?php echo __("form.sales_scores") ?>'
        },
          scales: {
            xAxes: [{
              ticks: {
                maxRotation: 90,
                minRotation: 80
              }
            }],
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          }
        }
    });



    $('select[name=month]').change(function(){      

      postData = { "_token" : "{{ csrf_token() }}" , month : $(this).val()  };

          $.post( "{{ route('get_salesscore_report') }}" , postData ).done(function( response ) {
              
                  conversion_by_month_chart_data.datasets.forEach(function(dataset) {
            dataset.data = response.conversion_by_month.data;
          });     
          conversion_by_month_chart_data.labels = response.conversion_by_month.labels;
          window.conversion_by_month_chart.update();


          }, 'json');


      


    });


// End of Conversion by Month Chart
</script>
<script type="text/javascript">
  //---------------------------------------------------------
  // Validator
  //---------------------------------------------------------
  /*Add Delivery Boy Form Velidation*/
  $( document ).ready( function () {
          
      $( "#salesscores-register" ).validate( {
          submitHandler: function(form) {
             form.submit();
           },
          rules: {
             staff_user_id:{
                  required:true,
              },
              staff_user_remarks:{
                  required:true,
              },
              unique_id: {
                  required: true,
                   remote:{
                    url:"{{route('admin.check.orderid')}}",
                   
                } 
              }, 
          },
          messages: {
              staff_user_id: {
                  required: "Please Select Salesman.",
              },
              staff_user_remarks:{
                required:"Please Enter Salesman Remarks.",
              },
              unique_id: {
                  required: "Please Enter Order Id.",
                  remote: "This Order Id Already Used by Salesman."
              },
          },
          //errorElement: "strong",
          errorClass: "text-danger help-block",
          errorPlacement: function ( error, element ) {
          if ( element.prop( "type" ) === "checkbox" ) {
                error.insertBefore( element.parent( "label" ) );
              } else {
                error.insertAfter( element );
              }
         },
      });
  });

    /*Salesman List*/
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

       $(document).on('change', '#unique_id', function(event) {
        var unique_id = $('#unique_id').val();
         $.ajax({
          url: "{{ route('admin.check.orderidamount') }}",
          headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
          },
          method: 'POST',
          data: {unique_id: unique_id},
          success: function(data) {
            console.log(data);
            $('#order_amount').val(data);
          }
        });
      });
    });
  </script>
@endsection