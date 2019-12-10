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
div#admins-table_filter {
    display: none;
}
#data tr td:last-child {
  text-align: right;
}


</style>
  <main class="app-content">
      <div class="main-content">
        <h5 class="">
          @if (request()->path() == 'admin/refunds/all')
            All
          @elseif (request()->path() == 'admin/refunds/pending')
            Pending
          @elseif (request()->path() == 'admin/refunds/accepted')
            Accepted
          @elseif (request()->path() == 'admin/refunds/rejected')
            Rejected
          @endif
            Requests
        </h5>
        <hr />
        <div class="row">
          <div class="col-md-2">
            <label>Status</label>
            <?php
              echo form_dropdown('status_id', $status , $status  , "class='form-control four-boot' multiple='multiple' ");
            ?>
          </div>
          <hr>
          <div class="col-md-12">
              <div class="">
                <table class="table table-bordered" id="admins-table" style="width:100%;">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Customer Phone</th>
                      <th>Customer Email</th>
                      <!-- <th>Shop Name</th>
                      <th>Vendor Phone</th>
                      <th>Vendor Email</th> -->
                      <th>Product Title</th>
                      <th>Money to Return</th>
                      <th>Reason</th>
                      <th>Order Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                </table>

                 <!-- print pagination -->
                 <div class="row">
                   <div class="col-md-12">
                     <div class="text-center">
                        {{$refunds->links()}}
                     </div>
                   </div>
                 </div>
                 <!-- row -->
          </div>
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

    function accept(e, rid) {
      e.preventDefault();
      swal({
        title: "Are you sure?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willAccept) => {
        if (willAccept) {
          $.ajax({
            url: '{{route('admin.refunds.accept')}}',
            type: 'POST',
            data: {
              rid: rid
            },
            success: function(data) {
              if (data == "success") {
                window.location = "{{url()->current()}}";
              }
            }
          });
        }
      });
    }

    function reject(e, rid) {
      e.preventDefault();
      swal({
        title: "Are you sure?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willReject) => {
        if (willReject) {
          $.ajax({
            url: '{{route('admin.refunds.reject')}}',
            type: 'POST',
            data: {
              rid: rid
            },
            success: function(data) {
              if (data == "success") {
                window.location = "{{url()->current()}}";
              }
            }
          });
        }
      });
    }
  </script>
  <script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.colVis.min.js"></script> 
<script type="text/javascript">
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
          { className: "text-right", "targets": [5] },
          { "name": "id",   "targets": 0 },
          /*{ "name": "shopname",  "targets": 1 },
          { "name": "email", "targets": 2 },
          { "name": "mobile",  "targets": 3 },
          { "name": "phone",  "targets": 4 ,visible: false},
          { "name": "address",  "targets": 5 ,visible: false},
          { "name": "country_id",  "targets": 6 ,orderable:false,visible: false},
          { "name": "state_id",  "targets": 7,orderable:false},
          { "name": "city_id",  "targets": 8,orderable:false},
          { "name": "status",  "targets": 9 },
          { "name": "usergroup",  "targets": 10,orderable:false },
          { "name": "is_verified",  "targets": 11 },
          { "name": "employer_name",  "targets": 12 ,visible: false},
          { "name": "employer_contactno",  "targets": 13,visible: false },
          { "name": "status",  "targets": 14},
          { "name": "action",  "targets": 15,orderable:false },*/
        ],
        "ajax": {
            "url": '{!! route("datatables_refund_request") !!}',
            "type": "POST",
            'headers': {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            "data": function (d) {
                d.status_id   = $("select[name=status_id]").val();
                // d.is_verified = $('select[name=is_verified]').val();
                // d.groups = $('select[name=groups]').val();
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
        // console.log('change here');
        dataTable.draw();
    });
  });
  $('.select2').select2();
</script>
@endpush
