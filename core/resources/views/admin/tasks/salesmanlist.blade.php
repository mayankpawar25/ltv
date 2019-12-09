@extends('admin.layout.master')

@section('title', 'Salesman List')

@section('headertxt', 'Salesman List')

@section('content')
<main class="app-content">
	 
	<div class="row">
		<div class="col-md-12">
			<div class="main-content">
				<div class="">
        	 
                	<div class="">
                  	 <h5>Task Schedule Management <a href="{{route('admin.tasks.create')}}" class="btn btn-primary float-right">Add Salesman Task</a></h5>
                      
                      
                  	 
                     <hr />
                	</div>
                	<div class="sellers-product-inner">
                    	<div class="bottom-content">
                        	<table class="table table-bordered" id="datatableOne">
                            	<thead>
                                	<tr>
                                    <th>S.No.</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                     <th>Status</th>
                                    <th class="text-right">Action</th>
                                	</tr>
                            	</thead>
                            	<tbody>
                            		@php $i=0 @endphp
                            		@foreach($salesmans as $user)
                            		<tr>
                            			<td>{{ ++$i }}</td>
                                  <td>{{ $user->code }}</td>
                            			<td>{{ $user->first_name }} {{ $user->last_name }}</td>
                            			<td>{{ $user->email }}</td>
                            			<td>{{ $user->phone }}</td>
                            			
                            			<td>
                            				@if($user->inactive == NULL)
                            					<span class="badge badge-success">Active</span>
                            				@else
                            					<span class="badge badge-success">Inactive</span>
                            				@endif
                            			</td>
                            			<td>
                                  <?php 
                                   $salesman_data = App\Task::where('salesman_id' ,$user->id)->get();
                                   $salesman = json_decode($salesman_data);
                                   ?>
                                    <?php 
                                      if (!empty($salesman)) { ?>
										                    <span class="btn-group float-right">
                                  				<a href="{{ route('admin.salesmans.task',$user->id) }}" class="btn btn-primary btn-sm " data-toggle="tooltip" title="View Calander"><span class="fa fa-eye"></span></a>
                                  			<!-- 	<a href="{{ route('admin.shopkeeper.edit',$user->id) }}" class="btn btn-success btn-sm" data-toggle="tooltip" title="Edit"><span class="fa fa-edit"></span></a> -->
                                  				<!-- <a href="{{ route('admin.shopkeeper.delete',$user->id) }}" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete"><span class="fa fa-trash"></span></a> -->
                                         </span>
                                  <?php }
                                    ?>
                            			</td>
                            		</tr>
                            		@endforeach
                            	</tbody>
                        	</table>
                    	</div>
                    	<div class="row">
                        <div class="col-md-12">
                          <div class="text-center">

                          </div>
                        </div>
                    	</div>
                	</div>
            	 
      	</div>
			</div>
		</div>
	</div>
</main>
@endsection