@extends('admin.crm.setup.index')
@section('title', __('form.roles'))
@section('setting_page')
<div class="app-content">
        
<div class="app-title">    
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
                dom: 'Bfrtip',
                buttons: [
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
                pageLength: 5,
                ordering: false,
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