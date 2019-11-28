@extends('admin.layout.master')

@section('title', 'Product Upload')

@section('headertxt', 'Product Upload')
@section('content')
<main class="app-content">
	<div class="app-title">
		<div>
			<h1><i class="fa fa-dashboard"></i>Add Job Card Template</h1>
		</div>
		<!--<ul class="app-breadcrumb breadcrumb">
			<li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
			<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
		</ul>-->
	</div>
		
		<input type="hidden" name="id" value="{{$template->id}}">
		<div class="row">
			<div class="col-md-12">
				<div class="tile">
					<div class="tile-title">Add Job Card Template</div>
					<div class="">
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label><strong>Form Title</strong> : {{ $template->job_card_name }}</label>									
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12"><h4><strong>Form Fields</strong></h4></div>
						</div>
						@php $i=0 @endphp
						<form class="product-upload-form" action="{{ route('admin.jobcardtemplate.save') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
							{{ csrf_field() }}
						<div class="row feild{{$i}}">
						@foreach($template->templatevalues as $form_key => $form_data)
						@php $i++ @endphp
							<div class="col-sm-3">
								<div class="form-group">
									<label><strong>{{ $form_data->label }}</strong> : 
									</label>
									@if($form_data->type == 'text')
										<input type="text" name="{{ $form_data->slug }}" value="" placeholder="" class="form-control">
									@elseif($form_data->type == 'number')
										<input type="number" name="{{ $form_data->slug }}" value="" placeholder="" class="form-control">
									@elseif($form_data->type == 'email')
										<input type="email" name="{{ $form_data->slug }}" value="" placeholder="" class="form-control">
									@elseif($form_data->type == 'textarea')
										<textarea name="{{ $form_data->slug }}" class="form-control"></textarea>
									@elseif($form_data->type == 'file')
										<input type="file" name="{{ $form_data->slug }}" value="" placeholder="" class="form-control">
									@endif
								</div>
							</div>
						@endforeach
						</div>
						<div class="row">
							<div class="col-sm-12">
							 
                            <div class="btn-wrapper mt-4 d-block text-center">
	                          	<input type="submit" class="submit-btn" value="Update Product">
	                      	</div>
                            </div>
						</div>
						</form>

					</div>
					 
				</div>
			</div>
		</div>
</main>
@endsection