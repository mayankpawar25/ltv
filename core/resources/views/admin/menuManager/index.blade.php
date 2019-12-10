@extends('admin.layout.master')

@push('styles')
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
<style media="screen">
  h3, h4 {
    margin: 0px;
  }
</style>
@endpush

@section('content')
{{--
  <main class="app-content">
     <div class="app-title">
        <div class="row" style="width:100%;">
          <div class="col-md-6">
            <h1 class="d-inline-block">Menu Management</h1>
          </div>
          <div class="col-md-6 text-right">
            <a href="{{route('admin.menuManager.add')}}" class="float-right btn btn-primary">Add Menu</a>
          </div>
        </div>
     </div>
     <div class="row">
        <div class="col-md-12">

          <div class="tile">

            <!---ROW-->
            <div class="row">
               <div class="col-md-12">
                 <div class="col-md-12">


                   <div class="card">
                     <div class="card-header bg-primary" style="color:white;">
                       <h4><i class="fa fa-list"></i> MENU LIST</h4>
                     </div>
                     <div class="card-body">
                       @if (count($menus) == 0)
                         <h1 class="text-center">NO DATA FOUND</h1>
                       @else
                         <div class="table-scrollable">
                            <table class="table table-bordered table-hover">
                               <thead>
                                  <tr>
                                     <th> # </th>
                                     <th> Menu Name </th>
                                     <th> Menu Title </th>
                                     <th> Action </th>
                                  </tr>
                               </thead>
                               <tbody>
                                 @foreach ($menus as $menu)
                                   <tr>
                                      <td>{{$loop->iteration}}</td>
                                      <td>{{$menu->name}}</td>
                                      <td>{{$menu->title}}</td>
                                      <td>
                                        <a class="btn btn-info btn-sm" href="{{route('admin.menuManager.edit', $menu->id)}}">
                                        <i class="fa fa-pencil"></i> Edit
                                        </a>
                                         <button type="button" class="btn btn-danger btn-sm delete_button" data-toggle="modal" data-target="#DelModal{{$menu->id}}" data-id="2">
                                         <i class="fa fa-times"></i> DELETE
                                         </button>
                                      </td>
                                   </tr>
                                   <!-- Modal for DELETE -->
                                   <!-- Modal -->
                                   <div class="modal fade" id="DelModal{{$menu->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                     <div class="modal-dialog modal-dialog-centered" role="document">
                                       <div class="modal-content">
                                         <div class="modal-header">
                                           <h5 class="modal-title text-center" id="exampleModalCenterTitle">
                                             Are you sure you want to delete this page?
                                           </h5>
                                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                             <span aria-hidden="true">&times;</span>
                                           </button>
                                         </div>
                                         <div class="modal-body text-center">
                                           <form style="display:inline-block;" class="" action="{{route('admin.menuManager.delete', $menu->id)}}" method="post">
                                              {{csrf_field()}}
                                              <button type="submit" class="btn btn-success">Yes</button>
                                           </form>
                                           <button class="btn btn-danger" type="button" data-dismiss="modal">No</button>
                                         </div>
                                       </div>
                                     </div>
                                   </div>
                                 @endforeach
                               </tbody>
                            </table>
                         </div>
                       @endif
                     </div>
                   </div>
                 </div>
               </div>
            </div>
            <!-- row -->
          </div>
        </div>
     </div>
  </main>
    --}}


<main class="app-content">
    <div class="main-content">
       <div class="row">
          <div class="col-md-6">
            <h5 class="float-left">Menu Management</h5>
          </div>
          <div class="col-md-6">
          <a href="{{route('admin.menuManager.add')}}" class="float-right btn btn-primary">New Menu</a>
         </div>
     </div>
          <hr>
     <div class="row">
        <div class="col-md-12">
            <div class="">
               @if (count($menus) == 0)
                  @else
                    @foreach ($menus as $key => $menu)
      <!-- Modal for DELETE -->
                   <!-- Modal -->
                   <div class="modal fade" id="DelModal{{$menu->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                     <div class="modal-dialog modal-dialog-centered" role="document">
                       <div class="modal-content">
                         <div class="modal-header">
                           <h5 class="modal-title text-center" id="exampleModalCenterTitle">
                             Are you sure you want to delete this page?
                           </h5>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                           </button>
                         </div>
                         <div class="modal-body text-center">
                           <form style="display:inline-block;" class="" action="{{route('admin.menuManager.delete', $menu->id)}}" method="post">
                              {{csrf_field()}}
                              <button type="submit" class="btn btn-success">Yes</button>
                           </form>
                           <button class="btn btn-danger" type="button" data-dismiss="modal">No</button>
                         </div>
                       </div>
                     </div>
                   </div>
                  @endforeach
               @endif
                <div class="sellers-product-inner">
                    <div class="bottom-content">
                        <table class="table table-bordered w-100" id="data">
                            <thead>
                                <tr>
                                  <th>Menu Name</th>
                                  <th>Menu Title </th>
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
          { "name": "name",   "targets": 0 },
          { "name": "title",   "targets": 1 },
          { "name": "action",  "targets": 2,orderable:false },
         ],
        "ajax": {
            "url": '{!! route("datatable_pages") !!}',
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
