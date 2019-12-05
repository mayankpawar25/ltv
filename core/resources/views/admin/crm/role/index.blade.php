@extends('admin.crm.setup.index')
@section('title', __('form.roles'))
@section('setting_page')
<div class="app-content">
        
<div class="app-title">
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
 @include('admin.crm.setup.menu')
</div>
    <div class="tile">
        <div class="tile-body">
            <a href="{{ route('create_role_page') }}" class="btn btn-primary btn-sm">
                @lang('form.new_user_role')
            </a>
            <hr>
            <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="data">
                <thead>
                <tr>
                    <th>@lang("form.name")</th>
                     <th>@lang("form.action")</th>

                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection


@section('onPageJs')
    <script>
        $(function() {

            var dataTable = $('#data').DataTable({
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
                    "searchPlaceholder": "{{ __('form.search') }}"
                    // "paginate": {
                    //     "previous": '<i class="fa fa-angle-left"></i>',
                    //     "next": '<i class="fa fa-angle-right"></i>'
                    // }
                }

                ,
                responsive: true,
                processing: true,
                serverSide: true,
                //iDisplayLength: 5
                pageLength:  {{ Config::get('constants.RECORD_PER_PAGE') }},
                ordering: false,
                 "lengthMenu": [ [10, 20, 50, 100], [10, 20, 50, 100] ],
                // "columnDefs": [
                //     { className: "text-right", "targets": [2,4] },
                //     { className: "text-center", "targets": [5] }
                //
                //
                // ],
                "ajax": {
                    "url": '{!! route("datatables_roles") !!}',
                    "type": "POST",
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }

                }
            }).
            on('mouseover', 'tr', function() {
                jQuery(this).find('div.row-options').show();
            }).
            on('mouseout', 'tr', function() {
                jQuery(this).find('div.row-options').hide();
            });













        });


    </script>
@endsection