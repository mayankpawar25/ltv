@section('title', __('form.payments_received') . " : ". __('form.report'))

<h6>@lang('form.payments_received')</h6>
<hr>

<form>

   <div class="form-row">
       
        <div class="form-group col-md-2">
            <label>@lang('form.status')</label>
            <?php
                echo form_dropdown('currency_id', $data['currency_id_list'] , config('constants.default_currency_id') , "class='form-control four-boot' ");
            ?>
        </div>

          <div class="form-group col-md-2">
            <label for="name">@lang('form.date_range')</label>
            <input type="text" class="form-control form-control-sm" id="reportrange" name="date" >
                   

        </div>


   </div>
  

 
</form>

<table class="table dataTable no-footer dtr-inline collapsed" width="100%" id="data">
    <thead>
        <tr>
            <th>@lang("form.payment_#")</th>
            <th>@lang("form.invoice_#")</th>
            <th>@lang("form.payment_mode")</th>
            <th>@lang("form.transaction_id")</th>
            <th>@lang("form.customer")</th>
            <th class="text-right">@lang("form.amount")</th>
            <th>@lang("form.date")</th>
        </tr>
    </thead>
</table>


@section('innerPageJS')

<script>

        $(function () {

           dataTable = $('#data').DataTable({

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

                pageResize: true,
                responsive: true,
                processing: true,
                serverSide: true,
                // iDisplayLength: 5,
                pageLength: 15,
                ordering: false,
                "columnDefs": [
                    { className: "text-right", "targets": [5] }
                    // { className: "text-center", "targets": [5] }




                ],
                "ajax": {
                    "url": '{!! route("report_payment") !!}',
                    "type": "POST",
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    "data": function ( d ) {
                        d.currency_id = $('select[name=currency_id]').val(); 
                        d.date_range = $("#reportrange").val();
                       
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