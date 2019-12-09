@section('title', __('form.invoices') . " : ". __('form.report'))
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
<h6>@lang('form.invoices')</h6>
<hr>

<form>
   <div class="form-row">
        @if(Auth::user()->is_administrator)
        <div class="form-group col-md-2">
        <label>@lang('form.sales_agent')</label>
            <?php echo form_dropdown('sales_agent_id', $data['sales_agent_id_list'] , [], "class='form-control four-boot' multiple='multiple'"); ?>
        </div>
        @endif
        <div class="form-group col-md-2">
            <label for="name">@lang('form.date_range')</label>
            <input type="text" class="form-control form-control-sm" id="reportrange" name="date" >                  
        </div>
   </div>
</form>

<table class="table dataTable no-footer dtr-inline collapsed table-bordered" width="100%" id="data">
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


@section('innerPageJS')

<script>

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
                { className: "text-right", "targets": [6] }
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

    });

</script> 
@endsection