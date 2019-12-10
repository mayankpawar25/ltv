@extends('admin.layout.master')
@section('title', __('form.activity_log'))
@section('content')
<div class="app-content">
    <div class="main-content">
    <h5>Activity Log</h5>
    <hr />
        <!-- <a class="btn btn-danger btn-sm" href="{{ route('customer_contacts') }}">@lang('form.delete_logs')</a>     
            <hr> -->
        <table class="table  table-bordered" cellspacing="0" width="100%" id="data">
            <thead>
                <tr>
                    <th>@lang('form.date_time')</th>                          
                    <th>@lang('form.activity_by')</th> 
                    <th>@lang('form.description')</th>     
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('onPageJs')


    <script>

        $(function() {
 
            $('#data').DataTable({
                // "bInfo" : false,
                "bLengthChange": false,
                "language": {
                                "infoFiltered": ""
                },
                // "oLanguage": { "sSearch": "" } ,
                // "dom": '<"float-md-left"f><"float-md-right"l>tip',
                searching: false,
                responsive: true,
                processing: true,
                serverSide: true,
                //iDisplayLength: 5
                pageLength: {{ Config::get('constants.RECORD_PER_PAGE') }},
                ordering: false,
                // "columnDefs": [
                //     { targets: [2,3,4,5], visible: false}
                   
                
                
                // ],
                "ajax": {
                    "url": '{!! route("datatable_activity_log") !!}',
                    "type": "POST",
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            });




            

            

        });


        

    </script>
@endsection