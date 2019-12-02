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
  <main class="app-content">
     <div class="app-title">
        <div>
           <h1>Product Attribute Management</h1>
        </div>
     </div>
     <div class="row">
        <div class="col-md-12">
           <div class="tile">
              <h3 class="tile-title">&nbsp;&nbsp;&nbsp;&nbsp; <button class="btn btn-success float-right" data-toggle="modal" data-target="#addModal">
                  <i class="fa fa-plus"></i> Add Product Attribute
                </button></h3>
              
              <p style="clear:both;margin:0px;"></p>
                {{-- 
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
                @if (count($pas) == 0)
                  <h2 class="text-center">NO DATA FOUND</h2>
                @else
                  <table class="table">
                     <thead>
                        <tr>
                           <th scope="col">SL</th>
                           <th scope="col">Name</th>
                           <th scope="col">Status</th>
                           <th>All Options</th>
                           <th scope="col">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                          @foreach ($pas as $key => $pa)
                            <tr>
                               <td>{{$key+1}}</td>
                               <td>{{$pa->name}}</td>
                               <td>
                                 @if ($pa->status == 1)
                                   <h4 style="display:inline-block;"><span class="badge badge-success">Active</span></h4>
                                 @elseif ($pa->status == 0)
                                   <h4 style="display:inline-block;"><span class="badge badge-danger">Deactive</span></h4>
                                 @endif
                               </td>
                               <td>
                                 <a class="btn btn-primary btn-sm" href="{{route('admin.options.index', $pa->id)}}" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>
                               </td>
                               <td>
                               <span class="btn-group float-right">
                                 <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Add Option" > <span data-toggle="modal" data-target="#addSub{{$pa->id}}"><i class="fa fa-plus" aria-hidden="true"></i></span></button>
                                 <button type="button" class="btn btn-success btn-sm float-right" data-toggle="tooltip" title="Edit" > <span data-toggle="modal" data-target="#editModal{{$pa->id}}"><i class="fas fa-pencil-alt"></i> </span></button>
                                 </span>
                               </td>
                            </tr>
                            @includeif('admin.product_attribute.partials.edit')
                            @includeif('admin.options.partials.add')
                          @endforeach
                     </tbody>
                  </table>
                @endif
              </div>

              <div class="text-center">
                {{$pas->links()}}
              </div>
              --}}
              @if (count($pas) == 0)
                 @else
                 
                          @foreach ($pas as $key => $pa)
                          
                            @includeif('admin.product_attribute.partials.edit')
                            @includeif('admin.options.partials.add')
                          @endforeach
                    
                @endif
               <div class="col-md-12">
                <div class="sellers-product-inner">
                    <div class="bottom-content">
                        <table class="table table-bordered w-100" id="data">
                            <thead>
                                <tr>
                                  <th>Name</th>
                                  <th>Status</th>
                                  <th>All Option</th>
                                  <th class="text-right">Action</th>
                                </tr>
                            </thead>
                          
                        </table>
                    </div>
                  </div>
              </div>

           </div>
        </div>
     </div>
  </main>

  @includeif('admin.product_attribute.partials.add')
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.colVis.min.js"></script> 
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
        "lengthMenu": [ [10, 50, 100], [10, 50, 100] ],
        pageLength: {{ Config::get('constants.RECORD_PER_PAGE') }},
        ordering: true,
        "columnDefs": [
          { className: "text-right", "targets": [3] },
          { "name": "name",   "targets": 0 },
          { "name": "status", "targets": 1 },
          { "name": "alloption",  "targets": 2 ,orderable:false},
          { "name": "action",  "targets": 3,orderable:false },
         ],
        "ajax": {
            "url": '{!! route("datatable_attribute") !!}',
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
