
@extends('admin.layout.master')
{{-- Content Body --}}
@section('content')
 <main class="app-content">
 
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
#data tr td:last-child {
	text-align: right;
}

</style>
<main class="">
  <div class="main-content">
    <div class="row">
      <div class="col-md-6">
         <h5>{{__('form.collections')}}</h5>
      </div>
      <div class="col-md-6">
         <div class="float-md-right">
            @if(auth()->user()->is_administrator)
              <a class="btn btn-primary btn-sm" href="{{ route('collection.create') }}">{{__('form.new_collection')}}</a>
              <a class="btn btn-primary btn-sm" href="{{ route('payment_collection_import_page') }}">{{__('form.import')}}</a>
            @endif
         </div>
      </div>
    </div>
    <hr>
    <div class="row">
        <div class="form-group col-md-3">
         <label>@lang('form.status')</label>
         <?php
            echo form_dropdown('status_id', $status , $status  , "class='form-control four-boot' multiple='multiple' ");
            ?>
        </div>
      <div class="col-md-12">
      <div class="">
          <div class=""> @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
            @endif
            @if(Session::has('success'))
            <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
            @endif 
            @if(Session::has('error'))
            <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('error') }}</p>
            @endif
            <div class="">
              <table class="table table-bordered w-100" id="admins-table">
                <thead>
                  <tr>
                   <!--  <th>Id</th> -->
                    <th>{{__('form.customer_name')}}</th>
                    <th>{{__('form.customer_mobile')}}</th>
                    <th>{{__('form.alt_number')}}</th>
                    <th>{{__('form.creation_date')}}</th>
                    <th>{{__('form.collection_due_date')}}</th>
                    <th>{{__('form.due')}} {{__('form.amount')}}</th>
                    <th>{{__('form.collected')}} {{__('form.amount')}}</th>
                    <th>{{__('form.balance')}} {{__('form.amount')}}</th>
                    <!-- <th>Alternate No</th>
                    <th>Creation date</th>
                    <th>Collection due date</th>
                    <th>Due Amount</th>
                    <th>Collected Amount</th>
                    <th>Balance Amount</th> -->
                    <th>{{__('form.assigned')}}</th>
                    <th>{{__('form.status')}}</th>
                    <th>{{__('form.action')}}</th>
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
      "lengthMenu": [ [10, 20, 50, 100,150,200,250,300,350,450,500,-1], [10, 20, 50, 100,150,200,250,300,350,450,500,'All'] ],
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
  

/*Active Otion*/
var brand_status;
$(document).on('click', '.status', function(){
     $('#status_button').text('Ok');
     user_id = $(this).attr('id');
     brand_status = $(this).attr('data-status');
     $('#statusconfirmModal').modal('show');
});
$('#status_button').click(function(){
  $.ajax({
    url:"{{ url('admin/updatepaymentstatus') }}/"+user_id+"/"+brand_status,
   beforeSend:function(){
    $('#status_button').text('Changing Status...');
   },
   success:function(data)
   {
    setTimeout(function(){
     $('#statusconfirmModal').modal('hide');
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