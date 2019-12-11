@extends('admin.crm.setup.index')
@section('title', __('form.payments'))
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
<div class="app-content">
    <div class="main-content">
        <h5>@lang('form.payments')</h5>
        <hr>
        {{--@if(check_perm(['payments_view', 'invoices_view', 'invoices_view_own']))--}}
        <table class="table table-bordered w-100" cellspacing="0" width="100%" id="data">
            <thead>
            <tr>
               <th>@lang("form.payment_#")</th>
                <th>@lang("form.invoice_#")</th>
                <th>@lang("form.payment_mode")</th>
                <th>@lang("form.transaction_id")</th>
                <th>@lang("form.customer")</th>
                <th class="text-right">@lang("form.amount")</th>
                <th>@lang("form.date")</th>
                <th>@lang("form.action")</th>                         
            </tr>
            </thead>
        </table>
       {{-- @endif --}}
    </div>
</div>    
@endsection
@section('onPageJs')
    <script>

        $(function() {

            $('#data').DataTable({
                dom: 'lfBfrtip',
               /* buttons: [

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
                /*pageLength: {{ data_table_page_length() }},*/
                "lengthMenu": [ [10, 20, 50, 100,150,200,250,300,350,450,500,-1], [10, 20, 50, 100,150,200,250,300,350,450,500,'All'] ],
                pageLength: {{ Config::get('constants.RECORD_PER_PAGE') }},
                ordering: false,
                "columnDefs": [
                    { className: "text-right", "targets": [5] }
                    // { className: "text-center", "targets": [5] }


                ],
                "ajax": {
                    "url": '{!! route("datatables_payment") !!}',
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