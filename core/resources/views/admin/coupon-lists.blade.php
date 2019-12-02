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
        <div class="row" style="width:100%">
          <div class="col-md-6">
            <h1 class="float-left">Coupon Lists</h1>
          </div>
          <div class="col-md-6">
            <a href="{{route('admin.coupon.create')}}" class="btn btn-success float-right"><i class="fa fa-plus"></i> Add Coupon</a>
          </div>
        </div>
     </div>

     <div class="row">
      {{-- 
        <div class="col-md-12">
           <div class="tile">
              <div class="tile-body">
                @if (count($coupons) == 0)
                  <h4 class="text-center">NO COUPON FOUND</h4>
                @else
                  <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">Code</th>
                        <th scope="col">Type</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Minimum Amount</th>
                        <th scope="col">Valid Till</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($coupons as $key => $coupon)
                        <tr>
                          <th scope="row">{{$coupon->coupon_code}}</th>
                          <td>{{$coupon->coupon_type}}</td>
                          <td>{{$coupon->coupon_amount}}</td>
                          <td>{{$coupon->coupon_min_amount}}</td>
                          <td>{{$coupon->valid_till}}</td>
                          <td>
                           <span class="btn-group float-right">
                          
                            <a href="{{route('admin.coupon.edit', $coupon->id)}}" class="btn btn-success btn-sm" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                           
                            <form class="d-inline-block" action="{{route('admin.coupon.delete', $coupon->id)}}" method="post">
                              {{csrf_field()}}
                              <input type="hidden" name="coupon_id" value="{{$coupon->id}}">
                                
                              <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></button>
                            </form>
                            </span>
                          </td>
                        </tr>
                      @endforeach

                    </tbody>
                  </table>
                @endif

              </div>
           </div>
        </div>
       --}}
        

          <div class="col-md-12">
                <div class="sellers-product-inner">
                    <div class="bottom-content">
                        <table class="table table-bordered w-100" id="data">
                            <thead>
                                <tr>
                                  <th>Code</th>
                                  <th>Type</th>
                                  <th>Amount</th>
                                  <th>Minimum Amount</th>
                                  <th>Valid Till</th>
                                  <th class="text-right">Action</th>
                                </tr>
                            </thead>
                          
                        </table>
                    </div>
                  </div>
              </div>
     </div>
  </main>
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
          { "name": "coupon_code",   "targets": 0 },
          { "name": "coupon_type", "targets": 1 },
          { "name": "coupon_min_amount",  "targets": 2 ,orderable:false},
          { "name": "valid_till",  "targets": 3 ,orderable:false},
          { "name": "action",  "targets": 4,orderable:false },
         ],
        "ajax": {
            "url": '{!! route("datatable_coupon") !!}',
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
