@extends('admin.layout.master')

@section('title', 'Product Update')

@section('headertxt', 'Product Update')

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
         <h5>Dealers</h5>
      </div>
      <div class="col-md-6">
         <div class="float-md-right">
            @if(check_perm('customers_create'))
              <a class="btn btn-primary btn-sm" href="{{ route('admin.shopkeeper.create') }}">New Dealer</a>
            @endif

            @if(auth()->user()->is_administrator)
              <a class="btn btn-primary btn-sm" href="{{ route('admin.shopkeeper.import_page') }}">Import</a>
            @endif
         </div>
      </div>
    </div>
    <hr>
  	<div class="row">
      <div class="col-sm-12">
        <div class="form-row">
          <div class="form-group col-md-2">
             <label>@lang('form.status')</label>

             <?php
                echo form_dropdown('status_id', $status , []  , "class='form-control four-boot' multiple='true'");
                ?>
          </div>
          <div class="form-group col-md-2">
             <label>Verification</label>
             <?php
                echo form_dropdown('is_verified', $is_verified ,  [] , "class='form-control four-boot' multiple='true'");
                ?>
          </div>
          <div class="form-group col-md-2">
             <label>Groups</label>
             <?php
                echo form_dropdown('groups', $group ,  [] , "class='form-control four-boot' multiple='true'");
                ?>
          </div>
       </div>
      </div>
  		<div class="col-lg-12">
      	<div class="sellers-product-inner">
          	<div class="bottom-content table-responsive">
              	<table class="table table-default" id="data">
                  	<thead>
                      	<tr>
                          <th>Name</th>
                          <th>Shop Name</th>
                          <th>Email</th>
                          <th>Mobile</th>
                          <th>Alternate Number</th>
                          <th>Address</th>
                          <th>Country</th>
                          <th>State</th>
                          <th>City</th>
                          <th>Area</th>
                          <th>Group</th>
                          <th>Verification</th>
                          <th>Status</th>
                          <th class="text-right">Action</th>
                      	</tr>
                  	</thead>
                  	{{-- <tbody>
                  		@php $i = ($shopkeeper->currentPage() == 1)?0:$shopkeeper->currentPage()*10 @endphp
                  		@foreach($shopkeeper as $user)
                  		<tr>
                  			<td>{{ ++$i }}</td>
                  			<td>{{ $user->name }}</td>
                  			<td>{{ $user->shopname }}</td>
                        <td>{{ $user->email }}</td>
                  			<td>{{ $user->mobile }}{{ ($user->phone)?' / '.$user->phone:'' }}</td>
                        <td>{{ (!empty($user->usergroup))?$user->usergroup->name:'' }}</td>
                        <td>{{ $user->phone}}</td>
                        <td>{{ App\Country::find($row->country_id)->name}}</td>
                        <td>{{ App\State::find($row->state_id)->name}}</td>
                        <td>{{ App\City::find($row->city_id)->name}}</td>
                        <td>{{ App\Zipcode::find($row->zipcode_id)->area_name}}</td>
                  			<td>
                  				@if($user->is_verified==0)
                  					<span class="badge badge-warning">Under Review</span>
                  				@elseif($user->is_verified==1)
                  					<span class="badge badge-success">Verified</span>
              					@elseif($user->is_verified==2)
                  					<span class="badge badge-info">Hold</span>
              					@elseif($user->is_verified==3)
                  					<span class="badge badge-danger">Deactivated</span>
                  				@endif
                  			</td>
                  			<td>
                  				@if($user->status == 0)
                  					<span class="badge badge-warning">Inactive</span>
                  				@else
                  					<span class="badge badge-success">Active</span>
                  				@endif
                  			</td>
                  			<td>
  		                    <span class="btn-group float-right">
                            <a href="{{ route('admin.shopkeeper.show',$user->id) }}" class="btn btn-primary btn-sm " data-toggle="tooltip" title="View"><span class="fa fa-eye"></span></a>
                            
                            @if(check_perm('shopkeepers_edit'))
                    				  <a href="{{ route('admin.shopkeeper.edit',$user->id) }}" class="btn btn-success btn-sm" data-toggle="tooltip" title="Edit"><span class="fa fa-edit"></span></a>
                            @endif
                            
                            @if(check_perm('shopkeepers_delete'))
                    				<a href="{{ route('admin.shopkeeper.delete',$user->id) }}" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete"><span class="fa fa-trash"></span></a>
                            @endif

                            <a href="{{ route('admin.shopkeeper.transaction',['1',$user->id]) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" title="View Ledger" target="blank"><span class="fa fa-credit-card"></span></a>
                            <a href="{{ route('admin.orders.all',$user->id) }}" class="btn btn-default btn-sm" data-toggle="tooltip" title="View Order History" target="blank"><span class="fa fa-shopping-cart"></span></a>
                          </span>
                  			</td>
                  		</tr>
                  		@endforeach
                  	</tbody> --}}
              	</table>
          	</div>
          	<div class="row">
              <div class="col-md-12">
                <div class="text-center">
                  {{-- $shopkeeper->links() --}}
                </div>
              </div>
          	</div>
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
        "lengthMenu": [ [10, 50, 100,150,200,250,300,350,450,500,-1], [10, 50, 100,150,200,250,300,350,450,500,'All'] ],
        pageLength: {{ Config::get('constants.RECORD_PER_PAGE') }},
        ordering: true,
        "columnDefs": [
          { className: "text-right", "targets": [5] },
          { "name": "name",   "targets": 0 },
          { "name": "shopname",  "targets": 1 },
          { "name": "email", "targets": 2 },
          { "name": "mobile",  "targets": 3 },
          { "name": "phone",  "targets": 4 },
          { "name": "address",  "targets": 5 },
          { "name": "country_id",  "targets": 6 ,orderable:false},
          { "name": "state_id",  "targets": 7,orderable:false},
          { "name": "city_id",  "targets": 8,orderable:false},
          { "name": "status",  "targets": 9 },
          { "name": "usergroup",  "targets": 10,orderable:false },
          { "name": "is_verified",  "targets": 11 },
          { "name": "status",  "targets": 12},
          { "name": "action",  "targets": 13,orderable:false },
          {targets: -5, visible: false},
          {targets: -6, visible: false},
          {targets: -7, visible: false},
          {targets: -8, visible: false},
          {targets: -9, visible: false},
          {targets: -10, visible: false},
        ],
        "ajax": {
            "url": '{!! route("datatables_dealers") !!}',
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