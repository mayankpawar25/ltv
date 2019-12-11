@extends('admin.layout.master')
@section('title', __('form.leads'))
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

</style>
<div class="app-content">
<div class="main-content">
   <div class="">
        <div class="tile-body">
   <div class="row">
      <div class="col-md-6">
         <h5>@lang('form.leads')</h5>
      </div>
      <div class="col-md-6">
         
         <div class="float-md-right">
            @if(check_perm('leads_create'))
            <a class="btn btn-primary btn-sm" href="{{ route('add_lead_page') }}" role="button">@lang('form.new_lead')</a>
            @endif

            @if(auth()->user()->is_administrator)
              <a class="btn btn-primary btn-sm" href="{{ route('import_lead_page') }}">@lang('form.import_leads')</a>
            @endif
           
         </div>
        
      </div>
   </div>
   <hr>
   <div class="row">
      @if(count($data['stat']) > 0)   
      @foreach($data['stat'] as $stat)      
      <div class="col-md-2 bd-highlight">
         <h5>{{ $stat['total'] }}</h5>
         <div class="text-secondary">{{ $stat['name'] }}</div>
      </div>
      @endforeach
      @endif
      <div class="col-md-2 bd-highlight">
         <h5>{{ $data['stat_customer']['total'] }}</h5>
         <div class="text-success">{{ $data['stat_customer']['name'] }}</div>
      </div>
   </div>
   <hr>
   <div class="row">
      <div class="col-md-2 bd-highlight">
         <h5>{{ $data['lost_lead'] }}%</h5>
         <div class="text-danger">{{ __('form.lost_leads') }}</div>
      </div>
      <div class="col-md-2 bd-highlight">
         <h5>{{ $data['junk_lead'] }}%</h5>
         <div class="text-danger">{{ __('form.junk_leads') }}</div>
      </div>
   </div>
   <hr>
   <div class="form-row">
    
      <div class="form-group col-md-2">
         <label>@lang('form.tag')</label>
         <?php
            echo form_dropdown('tag_id', $data['lead_tags_list'] , ''  , "class='form-control four-boot' multiple='multiple' ");
            ?>
      </div>
      <div class="form-group col-md-2">
         <label>@lang('form.status')</label>
         <?php
            echo form_dropdown('status_id', $data['lead_status_id_list'] , $data['default_lead_status_id_list']  , "class='form-control four-boot' multiple='multiple' ");
            ?>
      </div>
      <div class="form-group col-md-2">
         <label>@lang('form.source')</label>
         <?php
            echo form_dropdown('source_id', $data['lead_source_id_list'] ,  '' , "class='form-control four-boot' multiple='multiple' ");
            ?>
      </div>
      
      @if(check_perm('leads_view'))
       <div class="form-group col-md-2">
         <label>@lang('form.assigned_to')</label>
         <?php
            echo form_dropdown('assigned_to', $data['assigned_to_list'] , '', "class='form-control four-boot multiple'");
            ?>
      </div>

      <div class="form-group col-md-2">
         <label>@lang('form.filter_by')</label>
         <?php
            echo form_dropdown('additional_filter', $data['additional_filter_list'] , [], "class='form-control four-boot' ");
            ?>
      </div>

     
      @endif
   </div>
   <hr>
   
   <table class="table w-100 table-bordered" cellspacing="0" width="100%" id="data">
      <thead>
         <tr>
            <th>@lang("form.company")</th>
            <th>@lang("form.name")</th>
            <th>@lang("form.phone")</th>
            <th>@lang("form.alternate_number")</th>
            <th>@lang("form.position")</th>
            <th>@lang("form.address")</th>
            <th>@lang("form.city")</th>
            <th>@lang("form.state")</th>
            <th>@lang("form.description")</th>
            <th>@lang("form.country")</th>
            <th>@lang("form.email")</th>
            <th>@lang("form.website")</th>
            <th>@lang("form.employer_name")</th>
            <th>@lang("form.employer_phoneno")</th>
            <th>Pincode</th>
            <th>@lang("form.tags")</th>
            

            <th>@lang("form.assigned")</th>
            <th>@lang("form.status")</th>
            <th>@lang("form.source")</th>
            <th>@lang("form.last_contacted")</th>
            <th>@lang("form.action")</th>
         </tr>
      </thead>
   </table>
 </div>
</div>
<div class="clearfix"></div>
</div>
</div>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.colVis.min.js"></script> 
 <script>
        $(function() {
            dataTable = $('#data').DataTable({
                // dom: 'B<"toolbar">frtip',
                // initComplete: function(){
                //   $("div.toolbar")
                //      .html('<button class="btn btn-light btn-sm" type="button" id="bulk_action">{{ __("form.bulk_action") }}</button>');           
                // },  
               /* dom: 'Bfrtip',*/
                dom: 'lfBfrtip',
              /*  buttons: [

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
                "lengthMenu": [ [10, 20, 50, 100,150,200,250,300,350,450,500,-1], [10, 20, 50, 100,150,200,250,300,350,450,500,'All'] ],
                pageLength: {{ Config::get('constants.RECORD_PER_PAGE') }},
               // ordering: false,
                // "columnDefs": [
                //     { className: "text-right", "targets": [2,4] },
                //     { className: "text-center", "targets": [5] }
                //
                //
                // ],
                 ordering: true,
                  "columnDefs": [
                    { className: "text-right", "targets": [5] },
                    { "name": "0",   "targets": 0 },
                    { "name": "1",  "targets": 1 },
                    { "name": "2", "targets": 2 },
                    { "name": "3",  "targets": 3 ,visible: false},
                    { "name": "4",  "targets": 4 ,visible: false},
                    { "name": "5",  "targets": 5 },
                    { "name": "6",  "targets": 6 ,orderable:false},
                    { "name": "7",  "targets": 7,orderable:false},
                    { "name": "8",  "targets": 8,orderable:false},
                    { "name": "9",  "targets": 9,orderable:false},
                    { "name": "10",  "targets": 10,orderable:false},
                    { "name": "11",  "targets": 11 ,visible: false},
                    { "name": "12", "targets": 12 ,visible: false},
                    { "name": "13",  "targets": 13 ,visible: false},
                    { "name": "14",  "targets": 14 ,visible: false},
                    { "name": "15",  "targets": 15 ,visible: false},
                    { "name": "16",  "targets": 16 ,orderable:false},
                    { "name": "17",  "targets":17,orderable:false},
                    { "name": "18",  "targets": 18,orderable:false},
                    { "name": "19",  "targets": 19,orderable:false},
                    { "name": "20",  "targets": 20,orderable:false},
                  /*  {targets: -6, visible: false},
                    {targets: -7, visible: false},
                    {targets: -8, visible: false},
                    {targets: -9, visible: false},
                    {targets: -10, visible: false},*/
                   /* {targets: -3, visible: false},*/
                   /* {targets: -4, visible: false},
                    {targets: -12, visible: false},
                    {targets: -13, visible: false},
                    {targets: -14, visible: false},
                    {targets: -15, visible: false},
                    {targets: -16, visible: false},*/

                 
                   
                  ],
                "ajax": {
                    "url": '{!! route("datatables_leads") !!}',
                    "type": "POST",
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },                   
                    "data": function ( d ) {
                        d.status_id                 = $("select[name=status_id]").val();
                        d.source_id                 = $('select[name=source_id]').val();
                        d.assigned_to               = $('select[name=assigned_to]').val();
                        d.additional_filter         = $('select[name=additional_filter]').val();
                        d.tag_id                    = $('select[name=tag_id]').val();
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

        });


        $(document).on('click', '#bulk_action', function(e){
            e.preventDefault();
            
            
           
        });


        

    </script>
@endsection
@section('onPageJs')
   
@endsection
