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

</style>
  <main class="app-content">
 <!--    <div class="app-title">
        <div>
           <h1></h1>
        </div>
     </div>-->
     <div class="row">
        <div class="col-md-12">
           <div class="main-content">
             <div class="row">
             <div class="col-md-6">
              <h5 class="pull-left">User Group List</h5>
             
             </div>
             
              <div class="col-md-6 text-right">
             
               <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                   New User Group
                 </button>
             </div>
             
             </div>
             
             
               
                <hr />
              <p style="clear:both;margin:0px;"></p>
             

              <div class="">
                <div class="sellers-product-inner">
                    <div class="bottom-content">
                        <table class="table table-bordered w-100" id="data">
                            <thead>
                                <tr>
                                  <th>Name</th>
                                  <th>Disc. Percentage</th>
                                  <th>Status</th>
                                  <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            
                        </table>
                    </div>
                  </div>
              </div>
              <div class="clearfix"></div>
           </div>
        </div>
     </div>
  </main>

  {{-- Gateway Add Modal --}}
  @includeif('admin.usergroup.partials.add')

<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.colVis.min.js"></script> 
<script type="text/javascript">
  $(function() {
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
            "searchPlaceholder": "{{ __('form.search') }}"
        },
        responsive: true,
        processing: true,
        serverSide: true,
        //iDisplayLength: 5
        "lengthMenu": [ [10, 20, 50, 100], [10, 20, 50, 100] ],
        pageLength: {{ Config::get('constants.RECORD_PER_PAGE') }},
        ordering: true,
        "columnDefs": [
          { className: "text-right", "targets": [3] },
          { "name": "name",   "targets": 0 },
          { "name": "percentage",  "targets": 1 },
          { "name": "status", "targets": 2 },
          { "name": "action",  "targets": 3,orderable:false },
         ],
        "ajax": {
            "url": '{!! route("datatable_usergroup") !!}',
            "type": "POST",
            'headers': {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            "data": function (d) {
                d.status_id   = $("select[name=status_id]").val();
                d.is_verified = $('select[name=is_verified]').val();
                d.groups = $('select[name=groups]').val();
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
</script>  
@endsection
