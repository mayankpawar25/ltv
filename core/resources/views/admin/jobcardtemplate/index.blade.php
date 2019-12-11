@extends('admin.layout.master')

@section('title', 'Product Upload')

@section('headertxt', 'Product Upload')

@section('content')
<main class="app-content">
	<div class="main-content">
		<div>
			<h5>Job Card Templates
				<a href="{{ route('admin.jobcardtemplate.create') }}" class="float-right btn btn-success"> <i class="fa fa-plus"></i> Add Job Card</a></h3>
			</h5>
		</div>
		<hr>
		<!--<ul class="app-breadcrumb breadcrumb">
			<li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
			<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
		</ul>-->
	<div class="row">
		<div class="col-sm-12">
			<div class="">
				{{-- <div class="">
                <h3 >
					Job Card Templates
					<a href="{{ route('admin.jobcardtemplate.create') }}" class="float-right btn btn-success"> <i class="fa fa-plus"></i> Add Job Card</a></h3>
				</div> --}}
				<div class="">
					<table class="table table-stripe">
						<thead>
							<tr>
								<th>S.No.</th>
								<th>Template Name</th>
								<th class="text-right">Action</th>
							</tr>
						</thead>
						<tbody>
							@php $i=0 @endphp
							@foreach($templates as $key => $template)
							<tr>
								<td>{{ ++$i }}</td>
								<td>{{ $template->job_card_name }}</td>
								<td>
                                <span class="btn-group float-right">
                                <a href="{{ route('admin.jobcardtemplate.show',$template->id) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Edit"><span class="fa fa-eye"></span></a>
									<a href="{{ route('admin.jobcardtemplate.edit',$template->id) }}" class="btn btn-sm btn-success" data-toggle="tooltip" title="Edit"><span class="fa fa-edit"></span></a>
									<a href="{{ route('admin.jobcardtemplate.delete',$template->id) }}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete"><span class="fa fa-trash"></span></a>
									
                                    </span>
                                    
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				 
			</div>	
		</div>
	</div>
</main>
@endsection