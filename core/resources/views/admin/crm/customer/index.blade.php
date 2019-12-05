@extends('admin.layout.master')
@section('title', __('form.customers'))
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
   <div class="row">
      <div class="col-md-6">
         <h5>@lang('form.customers')</h5>
      </div>
      <div class="col-md-6">
         <div class="float-md-right">
            @if(check_perm('customers_create'))
              <a class="btn btn-primary btn-sm" href="{{ route('add_customer_page') }}">@lang('form.new_customer')</a>
            @endif

            @if(auth()->user()->is_administrator)
              <a class="btn btn-primary btn-sm" href="{{ route('import_customer_page') }}">@lang('form.import_customers')</a>
            @endif

            @if(check_perm('customers_view_own'))
              <a class="btn btn-primary btn-sm" href="{{ route('customer_contacts') }}">@lang('form.contacts')</a>
            @endif

            @if(check_perm('customers_view_own'))
              <input type="hidden" name="assigned_to" value="{{ Auth::id() }}">
            @endif

         </div>
      </div>
   </div>
    <hr>
   <div class="row">
      <div class="col-md-2 bd-highlight">
         <h5>{{ $data['stat']['customer_active'] + $data['stat']['customer_inactive'] }}</h5>
         <div>@lang('form.total_customers')</div>
      </div>
      <div class="col-md-2 bd-highlight">
         <h5>{{ $data['stat']['customer_active'] }}</h5>
         <div class="text-success">@lang('form.active_customers')</div>
      </div>
      <div class="col-md-2 bd-highlight">
         <h5>{{ $data['stat']['customer_inactive'] }}</h5>
         <div class="text-danger">@lang('form.inactive_customers')</div>
      </div>
      <div class="col-md-2 bd-highlight">
         <h5>{{ $data['stat']['contact_active'] }}</h5>
         <div class="text-primary">@lang('form.active_contacts')</div>
      </div>
      <div class="col-md-2 bd-highlight">
         <h5>{{ $data['stat']['contact_inactive'] }}</h5>
         <div class="text-danger">@lang('form.inactive_contacts')</div>
      </div>      
   </div>
   <hr>
    @if(check_perm('customers_view_own'))
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="exclude_inactive_customers" id="exclude_inactive_customers"
         value="1" checked>   
      <label class="form-check-label" for="defaultCheck1">
      @lang('form.exclude_inactive_customers')
      </label>
    </div>
    @endif
    @if(check_perm('customers_view_own'))
    <table class="table table-bordered w-100" cellspacing="0" width="100%" id="data">
      <thead>
         <tr>
            <th>@lang("form.name")</th>
            <th>@lang("form.ID")</th>
            <th>@lang("form.primary_contact")</th>
            <th>@lang("form.primary_email")</th>
            <th>@lang("form.phone")</th>
            <th>@lang("form.assigned_to")</th>
            <th>@lang("form.active")</th>
            <th>@lang("form.groups")</th>
            <th>@lang("form.date_created")</th>
            <th>@lang("form.action")</th>
         </tr>
      </thead>
    </table>
    @endif
    <div class="clearfix"></div>
  </div>
</div>

<style>
table tr td:last-child{
	text-align:right;
}

</style>

@endsection
@section('onPageJs')


    <script>

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
                //pageLength: "{{ data_table_page_length() }}",
                 pageLength: {{ Config::get('constants.RECORD_PER_PAGE') }},
                ordering: false,
                "columnDefs": [
                    // { className: "text-right", "targets": [2,4] },
                    { className: "text-center", "targets": [4] }


                ],
                "ajax": {
                    "url": '{!! route("datatables_customers") !!}',
                    "type": "POST",
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    "data": function ( d ) {
                        d.assigned_to  = $('input[name=assigned_to]').val();
                        if($('#exclude_inactive_customers').is(':checked'))
                        {
                            d.exclude_inactive_customers = true;

                        }

                       
                    }
                }
            }).
            on('mouseover', 'tr', function() {
                jQuery(this).find('div.row-options').show();
            }).
            on('mouseout', 'tr', function() {
                jQuery(this).find('div.row-options').hide();
            });


            $("#exclude_inactive_customers").click(function(){

                dataTable.draw();
            });

            

        });


        $(document).on('change','.customer_status',function(e){

            e.preventDefault();
            var id = $(this).data('id');

            $.post( "{{ route("change_customer_status") }}", {
                "_token": "{{ csrf_token() }}",
                id : id,
                inactive : (this.checked) ? '' : 1

            });


        });



        


    </script>
@endsection