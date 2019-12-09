@extends('admin.layout.master')

@push('styles')
  	<!-- stylesheet -->
  	<link rel="stylesheet" href="{{asset('assets/user/css/style.css')}}">
  	<!-- responsive -->
  	<link rel="stylesheet" href="{{asset('assets/user/css/responsive.css')}}">
@endpush

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

<main class="app-content">
  <div class="main-content">
     <div class="row">
      <div class="col-md-6">
         <h5>Product Listing</h5>
      </div>
      <div class="col-md-6">
         <div class="float-md-right">
           @if(auth()->user()->is_administrator)
            <a href="{{route('admin.product.import_page')}}" class="btn btn-sm btn-primary float-right">Import</a>
            @endif

            <a href="{{route('admin.product.create')}}" class="btn btn-sm btn-primary mr-2 float-right">New Product</a>
         </div>
      </div>
    </div>
    <hr>
   <!--  <div class="app-title">
      <div>
         <h1 class="float-left">Product Listing</h1>
      </div>
      <ul class="app-breadcrumb breadcrumb">
         <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
         <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
      </ul>
    </div> -->
  <!-- sellers product content area start -->
  <div class="row">
    <div class="col-lg-12">
      <div class="">
        {{-- 
           <div class=card-header><h3 class="float-left">Your Products</h3>
          <div class="float-right icon-btn">

            @if(auth()->user()->is_administrator)
            <a href="{{route('admin.product.import_page')}}" class="btn btn-sm btn-primary float-right">Import</a>
            @endif

            <a href="{{route('admin.product.create')}}" class="btn btn-sm btn-primary mr-2 float-right">New Product</a>
          </div>
        </div>
         --}}
       
        <div class="">
          <div class="">
            <div class="bottom-content">
              <table class="table table-bordered w-100" id="data">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th width="30%">Title</th>
                        <th>Product Code</th>
                        <th>Price</th>
                        <th>Quantity left</th>
                        <th>Status</th>
                        <th>Total Earnings</th>
                        <th>Sales</th>
                        <th>Action</th>
                    </tr>
                </thead>
                {{-- <tbody>
                  @foreach ($products as $product)
                    @php
                      $totalearning = \App\Orderedproduct::where('shipping_status', 2)
                                            ->where('refunded', '<>', 1)
                                            ->where('product_id', $product->id)->sum('product_total');
                    @endphp
                    <tr>
                        <td>
                            <div class="single-product-item"><!-- single product item -->
                                <div class="thumb">
                                  <a href="#">
                                    <img style="width:60px;" src="{{asset('assets/user/img/products/'.$product->previewimages()->first()->image)}}" alt="seller product image">
                                  </a>
                                </div>
                                 
                            </div><!-- //.single product item -->
                        </td>
                        <td>
                        <a target="_blank" href="#">{{strlen($product->title) > 28 ? substr($product->title, 0, 28) . '...' : $product->title}}</a>
                        </td>
                        <td class="padding-top-40">
                          @if (!empty($product->current_price))
                            <del>{{$gs->base_curr_symbol}} {{$product->price}}</del> <span class="text-secondary">{{$gs->base_curr_symbol}} {{$product->current_price}}</span>
                          @else
                            <span>{{$gs->base_curr_symbol}} {{$product->price}}</span>
                          @endif
                        </td>
                        <td class="padding-top-40">
                          @if ($product->quantity==0)
                            <span class="badge badge-danger">Out of stock</span>
                          @else
                            {{$product->quantity}}
                          @endif
                        </td>
                        <td class="padding-top-40">{{$gs->base_curr_symbol}} {{$totalearning}}</td>
                        <td class="padding-top-40">{{$product->sales}}</td>
                        <td class="padding-top-40">
                           <span class="btn-group float-right">
                           <a target="_blank" href="{{route('admin.product.edit', $product->id)}}" class="btn btn-success btn-sm" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                          <span class="sp-close-btn"> <a href="#" onclick="delproduct(event, {{$product->id}})" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a></span>
                           </span>
                           <!-- <ul class="action">
                                 <li><a target="_blank" href="{{route('user.product.details', [$product->slug,$product->id])}}"><i class="far fa-eye"></i></a></li> 
                                <li></li>
                                <li class="sp-close-btn"></li>
                            </ul>-->
                        </td>
                    </tr>
                  @endforeach
                </tbody> --}}
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  <div class="sellers-product-content-area" style="padding: 0px 0;">
      <div class="container">
          
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
        "lengthMenu": [ [10, 20, 50, 100,150,200,250,300,350,450,500,-1], [10, 20, 50, 100,150,200,250,300,350,450,500,'All'] ],
        pageLength: {{ Config::get('constants.RECORD_PER_PAGE') }},
        ordering: true,
        "columnDefs": [
          { className: "text-right", "targets": [5] },
          { "name": "id",   "targets": 0 ,orderable:false},
          { "name": "title",  "targets": 1 },
          { "name": "product_code",  "targets": 2 },
          { "name": "price", "targets": 3 },
          { "name": "quantity",  "targets": 4 },
          { "name": "status",  "targets": 5 ,orderable:false},
          { "name": "earning",  "targets": 6 ,orderable:false},
          { "name": "sales",  "targets": 7 },
          { "name": "status",  "targets": 8 ,orderable:false},
          // { "name": "state_id",  "targets": 7},
        /*  { "name": "city_id",  "targets": 8},
          { "name": "status",  "targets": 9 },
          { "name": "usergroup",  "targets": 10,orderable:false },
          { "name": "is_verified",  "targets": 11,orderable:false },
          { "name": "status",  "targets": 12,orderable:false },
          { "name": "action",  "targets": 13,orderable:false },
          {targets: -5, visible: false},
          {targets: -6, visible: false},
          {targets: -7, visible: false},
          {targets: -8, visible: false},
          {targets: -9, visible: false},
          {targets: -10, visible: false},*/
        ],
        "ajax": {
            "url": '{!! route("product_datatable") !!}',
            "type": "POST",
            'headers': {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            "data": function (d) {
                /*d.status_id   = $("select[name=status_id]").val();
                d.is_verified = $('select[name=is_verified]').val();
                d.groups = $('select[name=groups]').val();*/
            }
        }
    }).
    on('mouseover', 'tr', function() {
        jQuery(this).find('div.row-options').show();
    }).
    on('mouseout', 'tr', function() {
        jQuery(this).find('div.row-options').hide();
    });

   /* $('select').change(function(){
        // console.log('change here');
        dataTable.draw();
    });*/
  });
</script>
  <!-- sellers product content area end -->
@endsection