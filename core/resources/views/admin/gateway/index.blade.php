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
{{--  
  <main class="app-content">
     <div class="app-title">
        <div>
           <h1>Gateway Setting</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
           <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        </ul>
     </div>
     <div class="row">
        <div class="col-md-12">
           <div class="tile">
              <h3 class="tile-title float-left">Payment Gateways</h3>
              @if(check_perm('gateways_create')) 
              <div class="float-right icon-btn">
                 <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">
                   <i class="fa fa-plus"></i> Add Gateway
                 </button>
              </div>
              @endif
              @if(check_perm(['estimates_view', 'estimates_view_own']))
              @endif
              <p style="clear:both;margin:0px;"></p>
              <div class="col-md-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
              </div>
              <div class="table-responsive">
                 <table class="table">
                    <thead>
                       <tr>
                          <th scope="col">SL</th>
                          <th scope="col">Gateway Name</th>
                          <th scope="col">Name for User</th>
                          <th scope="col">Status</th>
                          <th scope="col">Action</th>
                       </tr>
                    </thead>
                    <tbody>
                       @php
                         $i=0;
                       @endphp
                       @foreach ($gateways as $gateway)
                         <tr>
                            <td data-label="Name">{{++$i}}</td>
                            <td>{{ $gateway->main_name }}</td>
                            <td>{{ $gateway->name }}</td>
                            <td>
                              @if($gateway->status == 1)
                              <a class="btn btn-sm btn-success text-white">Active </a>
                              @else
                              <a class="btn btn-sm btn-danger text-white">Deactve </a>
                              @endif
                            </td>
                            <td>
                              <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editModal{{$gateway->id}}" data-act="Edit">Edit</button>
                            </td>
                         </tr>
                         @includeif('admin.gateway.partials.edit')
                       @endforeach
                    </tbody>
                 </table>
              </div>
           </div>
        </div>
     </div>
  </main>
  --}}
 <main class="app-content">
    <div class="main-content">
       <div class="row">
          <div class="col-md-6">
            <h5 class="float-left">Payment Gateways</h5>
          </div>
          <div class="col-md-6">
           @if(check_perm('gateways_create')) 
          <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addModal">
                   New Gateway
            </button>
          @endif
          </div>
       
     </div>
          <hr>
     <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
               @if (count($gateways) == 0)
                  @else
                    @foreach ($gateways as $key => $gateway)
                     @includeif('admin.gateway.partials.edit')
                  @endforeach
               @endif
                <div class="sellers-product-inner">
                    <div class="bottom-content">
                        <table class="table table-bordered w-100" id="data">
                            <thead>
                                <tr>
                                  <th>Gateway Name</th>
                                  <th>Name for User</th>
                                  <th>Status</th>
                                  <th class="text-right">Action</th>
                                </tr>
                            </thead>
                          
                        </table>
                    </div>
                  </div>
                  <div class="clearfix"></div>
              </div>
     </div>
   </div>
  </main>
  {{-- Gateway Add Modal --}}
@includeif('admin.gateway.partials.addGateway')
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
          { className: "text-right", "targets": [2] },
          { "name": "main_name",   "targets": 0 },
          { "name": "name",   "targets": 1 },
          { "name": "status", "targets": 2 },
          { "name": "action",  "targets": 3,orderable:false },
         ],
        "ajax": {
            "url": '{!! route("datatable_gateways") !!}',
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
