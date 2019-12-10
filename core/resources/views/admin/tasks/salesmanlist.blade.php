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
div#admins-table_filter {
    display: none;
}
#data tr td:last-child {
  text-align: right;
}


</style>
<main class="app-content">
	 
  <div class="main-content">
    <div class="">
      <h5>Task Schedule Management <a href="{{route('admin.tasks.create')}}" class="btn btn-sm btn-primary float-right">Add Salesman Task</a></h5><hr />
    </div>
    <div class="row">
      <div class="col-md-3">
        <label>Salesman</label>
        <?php
          echo form_dropdown('salesman_id', $assigned_to , ''  , "class='form-control four-boot' multiple='multiple' ");
        ?>
      </div>
      <div class="col-md-12">
      	<div class="sellers-product-inner">
         <div class="bottom-content">
           <table class="table table-bordered w-100" id="admins-table">
             <thead>
               <tr>
                <th>S.No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th class="text-right">Action</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
	</div>
</main>
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
          { className: "text-right", "targets": [3] },
          { "name": "id",   "targets": 0 ,orderable:false},
          { "name": "first_name",   "targets": 1 },
          { "name": "email",   "targets": 2 },
          { "name": "phone",   "targets": 3 },
          { "name": "id",   "targets": 4 ,orderable:false},
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
            "url": '{!! route("datatables_salesman_paginate") !!}',
            "type": "POST",
            'headers': {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            "data": function (d) {
                d.salesman_id   = $("select[name=salesman_id]").val();
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
@endsection