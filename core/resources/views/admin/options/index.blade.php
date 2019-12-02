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
   <!--   <div class="app-title">
        <div>
           <h1>Option Management</h1>
        </div>
     </div> -->
     <div class="main-content">
       <div class="row">
       <div class="col-md-6">
            <h5 class="float-left">Option Management - All Options of <mark><strong>{{$pa->name}}</strong></mark></h5>
          </div>
          <div class="col-md-6">
            <a class="btn btn-warning float-right" href="{{route('admin.productattr.index')}}">
                  <i class="fa fa-list"></i> All Product Attributes
                </a> &nbsp;
                <a class="btn btn-primary float-right" data-toggle="modal" data-target="#addSub{{$pa->id}}">
                  <i class="fa fa-plus"></i> Add Option
                </a>
          </div>
     </div>
       <hr>
     <div class="row">
        <div class="col-md-12">
           <div class="">
            <!--   <h3 class="tile-title ">All Options of <mark><strong>{{$pa->name}}</strong></mark>
              
               <a class="btn btn-warning float-right" href="{{route('admin.productattr.index')}}">
                  <i class="fa fa-list"></i> All Product Attributes
                </a>
                <a class="btn btn-success float-right" data-toggle="modal" data-target="#addSub{{$pa->id}}">
                  <i class="fa fa-plus"></i> Add Option
                </a>
              </h3> -->
               
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
                @if (count($options) == 0)
                  <h2 class="text-center">NO DATA FOUND</h2>
                @else
                  <table class="table">
                     <thead>
                        <tr>
                           <th scope="col">SL</th>
                           <th scope="col">Name</th>
                           <th scope="col">Status</th>
                           <th scope="col" class="text-right">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                          @foreach ($options as $key => $option)
                            <tr>
                               <td>{{$key+1}}</td>
                               <td>{{$option->name}}</td>
                               <td>
                                 @if ($option->status == 1)
                                   <h4 style="display:inline-block;"><span class="badge badge-success">Active</span></h4>
                                 @elseif ($option->status == 0)
                                   <h4 style="display:inline-block;"><span class="badge badge-danger">Deactive</span></h4>
                                 @endif
                               </td>
                               <td>
                                 <button type="button" class="btn btn-success btn-sm float-right" data-toggle="tooltip" title="Edit" ><span data-toggle="modal" data-target="#editModal{{$option->id}}"><i class="fas fa-pencil-alt"></i></span></button>
                               </td>
                            </tr>
                            @includeif('admin.options.partials.edit')
                          @endforeach
                     </tbody>
                  </table>
                @endif
              </div>

              <div class="text-center">
                {{$options->links()}}
              </div>  --}}
            
               @if (count($options) == 0)
               @else
               @foreach ($options as $key => $option)
                      @includeif('admin.options.partials.edit')
               @endforeach
                @endif
                <div class="">
                <div class="sellers-product-inner">
                    <div class="bottom-content">
                        <table class="table table-bordered w-100" id="data">
                            <thead>
                                <tr>
                                  <th>Name</th>
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
     </div>
   </div>
  </main>

  @includeif('admin.options.partials.add')
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
          { className: "text-right", "targets": [2] },
          { "name": "name",   "targets": 0 },
          { "name": "status", "targets": 1 },
          { "name": "action",  "targets": 2,orderable:false },
         ],
        "ajax": {
            "url": '{!! route("datatable_option") !!}',
            "type": "POST",
            'headers': {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            "data": function (d) {
                d.status_id   = $("select[name=status_id]").val();
                d.is_verified = $('select[name=is_verified]').val();
                d.groups = $('select[name=groups]').val();
                d.id ={{ $pa->id }};
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
