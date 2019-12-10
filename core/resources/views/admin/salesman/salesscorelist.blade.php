@extends('admin.layout.master')

@section('title', 'Salesman List')

@section('headertxt', 'Salesman List')

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
div#data_filter {
    display: none;
}
#data tr td:last-child {
  text-align: right;
}
</style>
<main class="app-content">
  
  {{-- <div class="main-content"> --}}
    {{-- <h5>Salesman Sales Score</h5><hr> --}}
    <div class="row">
      <div class="col-md-3 {{(check_perm('salesscores_create'))?'':'d-none'}}">
        <div class="main-content">
          <h5 class="tile-title">Add Sales Score</h5><hr>
          <form action="{{ route('admin.salesman.addsalesscore') }}" method="post" accept-charset="utf-8" id="salesscores-register">
            {{csrf_field()}}
            <p style="clear:both;margin:0px;"></p>
            <div class="form-group">
              <label>Order ID: </label>
              <input type="text" name="unique_id" class="form-control" placeholder="Enter Order Id" value="" id="unique_id">
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
              <label>Remarks: </label>
               <textarea type="text" name="staff_user_remarks" placeholder="Enter Remarks" class="form-control" id="address_by_lat_long">{{ old('staff_user_remarks') }}</textarea>
                @if($errors->has('staff_user_remarks'))
                <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('staff_user_remarks') }}</strong></span></p>
                @endif
             </div>
             <hr>
            <div class="text-right">
              <button type="submit" class="btn btn-success">Save</button>
            </div>
          </form>
        </div>
      </div>
      <div class="col-md-{{(check_perm('salesscores_create'))?'9':'12'}}">
        <div class="main-content">
        <h5 class="tile-title">Sales Score Report</h5><hr>
        <div class="sellers-product-inner">
          <form>
            <div class="form-row">
              @if(Auth::user()->is_administrator)
                <div class="form-group col-md-3">
                <label>@lang('form.sales_agent')</label>
                    <?php echo form_dropdown('sales_agent_id', $data['sales_agent_id_list'] , [], "class='form-control four-boot' multiple='multiple'"); ?>
                </div>
              @endif
              <div class="form-group col-md-3">
                <label for="name">@lang('form.date_range')</label>
                <input type="text" class="form-control form-control-sm" id="reportrange" name="date" >                  
              </div>
            </div>
          </form>
          <table class="table dataTable no-footer dtr-inline collapsed" width="100%" id="data">
              <thead>
                  <tr>
                      <th>#@lang("form.unique_id")</th>
                      <th>@lang("form.customer")</th>
                      <th>@lang("form.staff")</th>
                      <th>@lang("form.remarks")</th>
                      <th>@lang("form.sub_total")</th>
                      <th>@lang("form.total")</th>
                      <th>@lang("form.date")</th>
                  </tr>
              </thead>
          </table>
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

  $(function () {

      dataTable = $('#data').DataTable({

          dom: 'lfBfrtip',
          /*buttons: [

              {
                  init: function(api, node, config) {
                      $(node).removeClass('btn-secondary')
                  },
                  className: "btn-light btn-sm",
                  extend: 'collection',
                  text: 'Export',
                  buttons: [
                      'copy',
                      'excel',
                      'csv',
                      'pdf',
                      'print'
                  ]
              }
          ],*/
          buttons: [
                    {
                      extend: 'copyHtml5',
                      exportOptions: {
                          columns: ':visible'
                      }
                    },{
                      extend: 'excelHtml5',
                      exportOptions: {
                        columns: ':visible'
                      }
                    },{
                      extend: 'print',
                      exportOptions: {
                        columns: ':visible'
                      }
                    },
                    'colvis'
                  ],
          "language": {
              "lengthMenu": '_MENU_ ',
              "search": '',
              "searchPlaceholder": "{{ __('form.search') }}",
              /*"paginate": {
                  "previous": '<i class="fa fa-angle-left"></i>',
                  "next": '<i class="fa fa-angle-right"></i>'
              }*/
          },
          pageResize: true,
          responsive: true,
          processing: true,
          serverSide: true,
          // iDisplayLength: 5,
          pageLength: {{ Config::get('constants.RECORD_PER_PAGE') }},
          ordering: false,
          "columnDefs": [
              { className: "text-right", "targets": [5] }
              // { className: "text-center", "targets": [5] }
          ],
          "ajax": {
              "url": '{!! route("report_salesscore") !!}',
              "type": "POST",
              'headers': {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              "data": function ( d ) {
                  
                  d.currency_id       = $("select[name=currency_id]").val();
                  d.sales_agent_ids   = $('select[name=sales_agent_id]').val();
                  d.status_ids        = $('select[name=status_id]').val();
                  d.date_range        = $("#reportrange").val();
                  // etc
              }
          }
      }).
      on('mouseover', 'tr', function() {
          jQuery(this).find('div.row-options').show();
      }).
      on('mouseout', 'tr', function() {
          jQuery(this).find('div.row-options').hide();
      });

      $('select').change(function(){
        dataTable.draw();
      });
      $("#reportrange").on("change paste keyup", function() {
          dataTable.draw();
      });

      $('.dataTables_info').append('<div class="clearfix"></div>');

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