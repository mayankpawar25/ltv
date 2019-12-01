
@extends('admin.layout.master')
{{-- Content Body --}}
@section('content')
 <main class="app-content">
<div class="app-title">
        <div>
           <h1><i class="fa fa-dashboard"></i> Payment Collection</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
           <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        </ul>
</div>
  </div>
</div>
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
div#admins-table_filter {
    display: none;
}

</style>
<main class="">
  <div class="main-content">
    <div class="row">
      <div class="col-md-6">
         <h5>Dealers</h5>
      </div>
      <div class="col-md-6">
         <div class="float-md-right">
            @if(check_perm('customers_create'))
              <a class="btn btn-primary btn-sm" href="{{ route('collection.create') }}">Add Payment Collection</a>
            @endif

            @if(auth()->user()->is_administrator)
              <a class="btn btn-primary btn-sm" href="{{ route('payment_collection_import_page') }}">Import</a>
            @endif
         </div>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-3 d-none">
        <div class="card">
        <form class="form-horizontal m-t-20" role="form" id="loginform" method="POST" enctype="multipart/form-data" action="{{ route('collection.store') }}">
          {{ csrf_field() }}
          <div class="card-body">
            <h4 class="card-title m-b-0">Payment Collect
              <div class="arrow-down float-right" onclick="toggleSetion(this.classList,'publish-setion')"></div>
            </h4>
            <div class="form-group">
              <label>Customer Name <span class="text-danger">*</span></label>
             <input type="text" placeholder="Customer Name" name="name" class="form-control" value="{{ old('name') }}">
            </div>
            <div class=" {{ $errors->has('name') ? ' has-error' : '' }}"> @if ($errors->has('name'))
              <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('name') }}</strong> </span></p>
              @endif
            </div>

            <div class="form-group">
              <label>Mobile No <span class="text-danger">*</span></label>
             <input type="text" placeholder="Customer Mobile No" name="mobile_no" class="form-control" value="{{ old('mobile_no') }}">
            </div>
            <div class=" {{ $errors->has('mobile_no') ? ' has-error' : '' }}"> @if ($errors->has('mobile_no'))
              <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('mobile_no') }}</strong> </span></p>
              @endif
            </div>

            <div class="form-group">
              <label>Alternate Number No <span class="text-danger">*</span></label>
             <input type="text" placeholder="Alternate Mobile No" name="alternate_no" class="form-control" value="{{ old('alternate_no') }}">
            </div>
            <div class=" {{ $errors->has('alternate_no') ? ' has-error' : '' }}"> @if ($errors->has('alternate_no'))
              <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('alternate_no') }}</strong> </span></p>
              @endif
            </div>

             <div class="form-group">
              <label>Collection Date <span class="text-danger">*</span></label>
             <input type="text" placeholder="Collection Date" name="collection_date" class="form-control initially_empty_datepicker" >
            </div>
            <div class=" {{ $errors->has('collection_date') ? ' has-error' : '' }}"> @if ($errors->has('collection_date'))
              <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('collection_date') }}</strong> </span></p>
              @endif
            </div>


             <div class="form-group">
              <label>Collection Amount <span class="text-danger">*</span></label>
             <input type="text" placeholder="Amount" name="amount" class="form-control" value="{{ old('amount') }}">
            </div>
            <div class=" {{ $errors->has('amount') ? ' has-error' : '' }}"> @if ($errors->has('amount'))
              <p class="text-danger"> <span class="help-block"> <strong>{{ $errors->first('amount') }}</strong> </span></p>
              @endif
            </div>

            <label>Select Salesman: </label>
            <div class="form-group">
                <select name="staff_user_id" id="salesman_select" class="salesman_select form-control select2"> </select>
            </div>
          

          
            

           
          </div>
          <div class="card-footer">
              <button type="submit" class="btn btn-success"> Submit </button>
            </div>
            </form>
        </div>
      </div>
      <div class="col-md-12">
      <div class="">
          <div class="card-body"> @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
            @endif
            @if(Session::has('success'))
            <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
            @endif 
            @if(Session::has('error'))
            <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('error') }}</p>
            @endif
            <div class="table-responsive">
              <table class="table table-bordered table-striped display" id="admins-table">
                <thead>
                  <tr>
                   <!--  <th>Id</th> -->
                    <th>Customer Name</th>
                    <th>Customer Mobile No</th>
                    <th>Alternate No</th>
                    <th>Collection date</th>
                    <th>Calling date</th>
                    <th>Amount</th>
                    <th>Collected Amount</th>
                    <th>Balance Amount</th>
                    <th>Salesman</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
               
              </table>
            </div>
          </div>
      </div>
    </div>
    </div>
  </div>
</main>
<div id="confirmModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Confirmation</h2>
      </div>
      <div class="modal-body">
        <h4 align="center" style="margin:0;">Are you sure you want to remove this data?</h4>
      </div>
      <div class="modal-footer">
        <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<!-- Status -->
<div id="statusconfirmModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Confirmation</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <h5 align="center" style="margin:0;"><strong>Are you sure you want to close this collection?</strong></h5>
      </div>
      <div class="modal-footer">
        <button type="button" name="status_button" id="status_button" class="btn btn-danger">OK</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
</main>
<!-- Status --> 
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.colVis.min.js"></script> 
<script>
$(function() {
    dataTable = $('#admins-table').DataTable({
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
          "searchPlaceholder": "{{ __('form.search') }}"
      },
      responsive: true,
      processing: true,
      serverSide: true,
      //iDisplayLength: 5
      "lengthMenu": [ [10, 50, 100,150,200,250,300,350,450,500,-1], [10, 50, 100,150,200,250,300,350,450,500,'All'] ],
      pageLength: {{ Config::get('constants.RECORD_PER_PAGE') }},
      ordering: true,
      "columnDefs": [
        { className: "text-right", "targets": [10] },
        { "name": "name",   "targets": 0 },
        { "name": "mobile_no",  "targets": 1 },
        { "name": "alternate_no", "targets": 2 },
        { "name": "collection_date",  "targets": 3 },
        { "name": "new_date",  "targets": 4 },
        { "name": "amount",  "targets": 5 },
        { "name": "collected_amount",  "targets": 6 },
        { "name": "balance_amount",  "targets": 7},
        { "name": "assigned_to",  "targets": 8,orderable:false},
        { "name": "status",  "targets": 9,orderable:false},
        { "name": "action",  "targets": 10,orderable:false},
        // { "name": "status",  "targets": 11,orderable:false},
        /*{targets: -5, visible: false},
        {targets: -6, visible: false},
        {targets: -7, visible: false},
        {targets: -8, visible: false},
        {targets: -9, visible: false},
        {targets: -10, visible: false},*/
      ],
      "ajax": {
          "url": '{!! route("datatable_payment_collection") !!}',
          "type": "POST",
          'headers': {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          "data": function (d) {
              d.status_id   = $("select[name=status_id]").val();
              d.staff_user_id = $('select[name=staff_user_id]').val();
              // d.groups = $('select[name=groups]').val();
          }
      }
    }).on('mouseover', 'tr', function() {
        jQuery(this).find('div.row-options').show();
    }).on('mouseout', 'tr', function() {
        jQuery(this).find('div.row-options').hide();
    });

    $('select').change(function(){
        // console.log('change here');
        dataTable.draw();
    });
});

var user_id;
/*Delete Option*/
/*Start*/
$(document).on('click', '.delete', function(){
  user_id = $(this).attr('id');
  $('#confirmModal').modal('show');
});
$('#ok_button').click(function(){
  $.ajax({
    url:"{{ url('admin/destroy') }}/"+user_id,
    beforeSend:function(){
      $('#ok_button').text('Deleting...');
    },
    success:function(data)
    {
      setTimeout(function(){
        $('#confirmModal').modal('hide');
        $('#admins-table').DataTable().ajax.reload();
      }, 2000);
    }
  })
});
/*End Delete Option*/

</script> 
@endsection
    
{{--End Body --}}
{{--End Html --}} 