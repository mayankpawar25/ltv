@extends('admin.layout.master')


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
#data tr td:last-child .btn-danger{
    width: 28px;
}
</style>
  <main class="app-content">
     <!--<div class="app-title">
        <div>
           <h3 class="page-title uppercase bold">
             @if (request()->path() == 'admin/orders/all')
               All
             @elseif (request()->path() == 'admin/orders/confirmation/pending')
                 Pending
             @elseif (request()->path() == 'admin/orders/confirmation/accepted')
                 Accepted
             @elseif (request()->path() == 'admin/orders/confirmation/rejected')
                 Rejected
             @elseif (request()->path() == 'admin/orders/delivery/pending')
                 Delivery Pending
             @elseif (request()->path() == 'admin/orders/delivery/inprocess')
                 Delivery Inprocess
             @elseif (request()->path() == 'admin/orders/delivered')
                 Delivered
             @elseif (request()->path() == 'admin/orders/cashondelivery')
                 Cash on Delivery
             @elseif (request()->path() == 'admin/orders/advance')
                 Advance Paid
             @endif
             Orders
           </h3>
        </div>
        <ul class="app-breadcrumb breadcrumb">
           <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        </ul>
     </div>-->
     <div class="row">
        <div class="col-md-12">
            <div class="main-content">
            <h5 class="">
             @if (request()->path() == 'admin/orders/all')
                All
             @elseif (request()->path() == 'admin/orders/confirmation/pending')
                Pending
             @elseif (request()->path() == 'admin/orders/confirmation/accepted')
                Accepted
             @elseif (request()->path() == 'admin/orders/confirmation/rejected')
                Rejected
             @elseif (request()->path() == 'admin/orders/delivery/pending')
                Delivery Pending
             @elseif (request()->path() == 'admin/orders/delivery/inprocess')
                Delivery Inprocess
             @elseif (request()->path() == 'admin/orders/delivered')
                Delivered
             @elseif (request()->path() == 'admin/orders/cashondelivery')
                Cash on Delivery
             @elseif (request()->path() == 'admin/orders/advance')
                Advance Paid
             @endif
                Orders
           </h5>
           <hr />
              @if(isset($orders))
              @if (count($orders) == 0)
                <div class="text-center d-none">
                <img src="{{asset('assets/admin/images/no-data.jpg')}}" />
                
                 <h3>NO ORDER FOUND !</h3>
                 </div>
              @else
              <div class="row mb-4  d-none">
                <div class="col-md-3 offset-md-9">
                  <form method="get"
                  action="
                  @if (request()->path() == 'admin/orders/all')
                    {{route('admin.orders.all')}}
                  @elseif (request()->path() == 'admin/orders/confirmation/pending')
                      {{route('admin.orders.cPendingOrders')}}
                  @elseif (request()->path() == 'admin/orders/confirmation/accepted')
                      {{route('admin.orders.cAcceptedOrders')}}
                  @elseif (request()->path() == 'admin/orders/confirmation/rejected')
                      {{route('admin.orders.cRejectedOrders')}}
                  @elseif (request()->path() == 'admin/orders/delivery/pending')
                      {{route('admin.orders.pendingDelivery')}}
                  @elseif (request()->path() == 'admin/orders/delivery/inprocess')
                      {{route('admin.orders.pendingInprocess')}}
                  @elseif (request()->path() == 'admin/orders/delivered')
                      {{route('admin.orders.delivered')}}
                  @elseif (request()->path() == 'admin/orders/cashondelivery')
                      {{route('admin.orders.cashOnDelivery')}}
                  @elseif (request()->path() == 'admin/orders/advance')
                      {{route('admin.orders.advance')}}
                  @endif
                  "
                  >
                    <input class="form-control" type="text" name="term" value="{{$term}}" placeholder="Search by order number">
                  </form>
                </div>
              </div>
              <table class="table table-bordered d-none" style="width:100%;">
                <thead>
                  <tr>
                      <th>Order id</th>
                      <th>Order Date</th>
                      <th>Dealer Name</th>
                      <th>User Type</th>
                      <th>Phone</th>
                      <th>Email</th>
                      <th>Total</th>
                      <th>Shipping Status</th>
                      <th>Payment Method</th>
                      <th>Payment Status</th>
                      <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($orders as $key => $order)
                    <tr>
                        <td class="padding-top-40">{{$order->unique_id}}</td>
                        <td class="padding-top-40">{{date('jS F, o', strtotime($order->created_at))}}</td>
                        <td class="padding-top-40">{{$order->first_name . ' ' . $order->last_name}}</td>
                        <td class="padding-top-40">{{$order->phone}}</td>
                        <td class="padding-top-40">{{$order->email}}</td>
                        <td class="padding-top-40">{{$gs->base_curr_symbol}} {{$order->total}}</td>
                        <td class="padding-top-40">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="shipping{{$order->id}}" id="inlineRadio{{$order->id}}1" value="0" onchange="shippingChange(event, this.value, {{$order->id}})" {{$order->shipping_status==0?'checked':''}} disabled>
                            <label class="form-check-label" for="inlineRadio{{$order->id}}1">Pending</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="shipping{{$order->id}}" id="inlineRadio{{$order->id}}2" value="1" onchange="shippingChange(event, this.value, {{$order->id}})" {{$order->shipping_status==1?'checked':''}} {{$order->shipping_status==1 || $order->shipping_status==2?'disabled':''}}>
                            <label class="form-check-label" for="inlineRadio{{$order->id}}2">Ready to dispatch</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="shipping{{$order->id}}" id="inlineRadio{{$order->id}}3" value="2" onchange="shippingChange(event, this.value, {{$order->id}})" {{$order->shipping_status==2?'checked':''}} {{$order->shipping_status==2?'disabled':''}}>
                            <label class="form-check-label" for="inlineRadio{{$order->id}}3">Delivered</label>
                          </div>
                        </td>
                        <td class="padding-top-40">
                          @if ($order->payment_method == 2)
                            Advance
                          @elseif ($order->payment_method == 1)
                            Cash on delivery
                          @endif
                        </td>
                        
                        <td>
                          @if ($order->payment_status == 0)
                            <span class="badge badge-danger paidstatus" data-orderid="{{ $order->id }}" data-status="1">Unpaid</span>
                          @elseif ($order->payment_status == 1)
                            <span class="badge badge-success paidstatus"  data-orderid="{{ $order->id }}" data-status="0">Paid</span>
                          @endif
                        </td>
                        
                        <td class="padding-top-40">
                            <a href="{{route('admin.orderdetails', $order->id)}}" target="_blank" title="View Order"><i class="text-primary fa fa-eye"></i></a>
                            @if ($order->approve == 0)
                              <span>
                                <a href="#" onclick="cancelOrder(event, {{$order->id}})" title="Reject Order">
                                  <i class="fa fa-times text-danger"></i>
                                </a>
                                <a href="#" onclick="acceptOrder(event, {{$order->id}})" title="Accept Order">
                                  <i class="fa fa-check text-success"></i>
                                </a>
                              </span>
                            @elseif ($order->approve == 1)
                              <span class="badge badge-success">Accepted</span>
                            @elseif ($order->approve == -1)
                              <span class="badge badge-danger">Rejected</span>
                            @endif

                        </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              @endif
              @endif

              <div class="form-row">
                <div class="form-group col-md-2">
                   <label>@lang('form.user_type')</label>
                   @php $data['user_type'] = ['unassigned'=>'Nothing Selected']+['1'=>'Dealer','2'=>'Customer'] @endphp
                   <?php
                      echo form_dropdown('user_type', $data['user_type'] , '' , "class='form-control four-boot' multiple='multiple' ");
                      ?>
                </div>
                <div class="form-group col-md-2">
                   <label>@lang('form.status')</label>
                   <?php
                      echo form_dropdown('status_id', $data['order_status'] , '' , "class='form-control four-boot' multiple='multiple' ");
                      ?>
                </div>
                <div class="form-group col-md-2">
                   <label>@lang('form.delivery_status')</label>
                   <?php
                      echo form_dropdown('delivery_status', $data['delivery_status'] , '' , "class='form-control four-boot' multiple='multiple' ");
                      ?>
                </div>
                <div class="form-group col-md-2">
                   <label>@lang('form.payment_method')</label>
                   <?php
                      echo form_dropdown('payment_method', $data['payment_method'] , '' , "class='form-control four-boot' multiple='multiple' ");
                      ?>
                </div>
                <div class="form-group col-md-2">
                   <label>@lang('form.payment_status')</label>
                   <?php
                      echo form_dropdown('payment_status', $data['payment_status'] , '' , "class='form-control four-boot' multiple='multiple' ");
                      ?>
                </div>
                <div class="form-group col-md-2 d-none">
                  <label for="name">@lang('form.date_range')</label>
                  <input type="text" class="form-control form-control-sm" id="reportrange" name="date" >                  
                </div>
              </div>

                <table class="table table-bordered" style="width:100%;" id="data">
                  <thead>
                    <tr>
                        <th>Order id</th>
                        <th>Order Date</th>
                        <th>Dealer Name</th>
                        <th>User Type</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Sub-Total</th>
                        <th>Tax</th>
                        <th>Total</th>
                        <th>Shipping Status</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                    </tr>
                  </thead>
                </table>
        </div>
     </div>
   </div>
  </main>

@endsection


@push('scripts')
  <script>
    $(document).ready(function() {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
    });

    function shippingChange(e, value, orderid) {

      var fd = new FormData();
      fd.append('value', value);
      fd.append('orderid', orderid);

      swal({
        title: "Are you sure?",
        text: "Once shipping status changed e-mail will be sent to customer",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willChange) => {

        if (willChange) {
          $.ajax({
            url: '{{route('admin.shippingchange')}}',
            type: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            success: function(data) {
              console.log(data);
              if (data == "success") {
                e.target.disabled = true;
                toastr["success"]("<strong>Success!</strong> Shipping status updated successfully!");
              }
            }
          });

        } else {
          window.location = '{{url()->current()}}';
        }
      });

    }

    function cancelOrder(e, orderid) {
      e.preventDefault();
      console.log(orderid);

      var fd = new FormData();
      fd.append('orderid', orderid);

      swal({
        title: "Are you sure?",
        text: "Once cancelled, you will not be able to recover this order!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            url: '{{route('admin.cancelOrder')}}',
            type: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            success: function(data) {
              console.log(data);
              if (data == "success") {
                window.location = '{{url()->full()}}';
              }
            }
          });

        }
      });
    }

    function acceptOrder(e, orderid) {
      e.preventDefault();
      console.log(orderid);

      var fd = new FormData();
      fd.append('orderid', orderid);

      swal({
        title: "Are you sure?",
        text: "Once accepted, you will not be able to reject this order!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            url: '{{route('admin.acceptOrder')}}',
            type: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            success: function(data) {
              console.log(data);
              if (data == "success") {
                window.location = '{{url()->full()}}';
              }
            }
          });

        }
      });
    }

    function payment(e, orderid) {
      e.preventDefault();
      console.log(orderid);

      var fd = new FormData();
      fd.append('orderid', orderid);

      swal({
        title: "Are you sure?",
        text: "Once accepted, you will not be able to reject this order!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            url: '{{route('admin.acceptOrder')}}',
            type: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            success: function(data) {
              console.log(data);
              if (data == "success") {
                window.location = '{{url()->full()}}';
              }
            }
          });

        }
      });
    }

    $(document).on('click','.paidstatus',function(){
      var orderid = $(this).data('orderid');
      var paystatus = $(this).data('status');
      var fd = new FormData();

      fd.append('orderid', orderid);
      fd.append('payment_status', paystatus);

      swal({
        title: "Are you sure?",
        text: "Once accepted, you will not be able to unpaid this order!",
        icon: "success",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            url: '{{route('admin.orders.paymentstatus')}}',
            type: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            success: function(data) {
              if (data == "success") {
                window.location = '{{url()->full()}}';
              }
            }
          });
        }
      });
    });

    var shopkeeper = "{{isset($data['shopkeeper_id'])?$data['shopkeeper_id']:''}}";

    $(function () {

      dataTable = $('#data').DataTable({
          dom: 'lfBfrtip',
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
          "lengthMenu": [ [10, 20, 50, 100,150,200,250,300,350,450,500,-1], [10, 20, 50, 100,150,200,250,300,350,450,500,'All'] ],
          pageLength: {{ Config::get('constants.RECORD_PER_PAGE') }},
          ordering: false,
          "columnDefs": [
              { className: "text-right", "targets": [11] }
          ],
          "ajax": {
              "url": '{!! route("orders_paginate") !!}',
              "type": "POST",
              'headers': {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              "data":function(d){
                  d.status_id       = $("select[name=status_id]").val();
                  d.user_type       = $("select[name=user_type]").val();
                  d.delivery_status = $('select[name=delivery_status]').val();
                  d.payment_method  = $('select[name=payment_method]').val();
                  d.payment_status  = $('select[name=payment_status]').val();
                  d.assigned_to     = $('select[name=assigned_to]').val();
                  d.shopkeeper_id   = shopkeeper; 
                  // d.date_range      = $("#reportrange").val();
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

      if(shopkeeper){
        dataTable.draw();
      }

      $('.dataTables_info').append('<div class="clearfix"></div>');

  });

  </script>
@endpush
