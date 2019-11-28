@extends('admin.layout.master')

@section('title', 'Product Upload')

@section('headertxt', 'Product Upload')
@section('content')
<main class="app-content">
	<div class="app-title">
		<div>
			<h1><i class="fa fa-dashboard"></i>Add JobCard Template</h1>
		</div>
		<ul class="app-breadcrumb breadcrumb">
			<li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
			<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
		</ul>
	</div>
	<form action="{{ route('admin.jobcardtemplate.update') }}" method="post">
		{{ csrf_field() }}
		<input type="hidden" name="id" value="{{$template->id}}">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">Add JobCard Template</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label><strong>Form Title</strong></label>
									<input type="text" name="form_title" value="{{ $template->job_card_name }}" placeholder="" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-1"><strong>Form Fields</strong></div>
							<div class="col-sm-3">
								<button type="button" class="btn btn-warning btn-sm add-clone"><span class="fa fa-plus"></span></button>
							</div>
						</div>
						@php $i=0 @endphp
						@foreach($template->templatevalues as $form_key => $form_data)
						@php $i++ @endphp
						<input type="hidden" name="old_data[id][]" value="{{ $form_data->id }}">
						<div class="row feild{{$i}}">
							<div class="col-sm-3">
								<div class="form-group">
									<label><strong>Label Name</strong> : </label>
									<input type="text" name="old_data[label][]" value="{{ $form_data->label }}" placeholder="" class="form-control">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label><strong>Type</strong> : </label>
									<select name="old_data[type][]" id="input-type" class="form-control">
										<optgroup label="Choose">
											<option value="select" {{ ($form_data->type == 'select')?'selected':'' }}>Select</option>
											<option value="radio" {{ ($form_data->type == 'radio')?'selected':'' }}>Radio</option>
											<option value="checkbox" {{ ($form_data->type == 'checkbox')?'selected':'' }}>Checkbox</option>
										</optgroup>
										<optgroup label="Input">
											<option value="text" {{ ($form_data->type == 'text')?'selected':'' }}>Text</option>
											<option value="number" {{ ($form_data->type == 'number')?'selected':'' }}>Number</option>
											<option value="email" {{ ($form_data->type == 'email')?'selected':'' }}>Email</option>
											<option value="textarea" {{ ($form_data->type == 'textarea')?'selected':'' }}>Textarea</option>
										</optgroup>
										<optgroup label="File">
											<option value="file" {{ ($form_data->type == 'file')?'selected':'' }}>File</option>
										</optgroup>
										<optgroup label="Date">
											<option value="date" {{ ($form_data->type == 'date')?'selected':'' }}>Date</option>
											<option value="time" {{ ($form_data->type == 'time')?'selected':'' }}>Time</option>
											<option value="datetime" {{ ($form_data->type == 'datetime')?'selected':'' }}>Date &amp; Time</option>
										</optgroup>
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label><strong>Sort Order</strong> : </label>
									<input type="text" name="old_data[sort_order][]" value="{{ $form_data->sort }}" placeholder="" class="form-control">
								</div>
							</div>
							<div class="col-sm-3">
								<button type="button" class="btn btn-sm btn-warning remove-row" data-row="feild{{$i}}">Remove</button>
							</div>
						</div>
						@endforeach
						<div class="put_clone_here"></div>
						<div class="row">
							<div class="col-sm-12">
								<button type="submit" class="btn btn-warning">Save</button>
							</div>
						</div>
					</div>
					<div class="card-footer">Add JobCard Template</div>
				</div>
			</div>
		</div>
	</form>
	<div class="row d-none clone_fields">
		<div class="col-sm-3">
			<div class="form-group">
				<label><strong>Label Name</strong> : </label>
				<input type="text" name="label[]" value="" placeholder="" class="form-control">
			</div>
		</div>
		<div class="col-sm-3">
			<div class="form-group">
				<label><strong>Type</strong> : </label>
				<select name="type[]" id="input-type" class="form-control">
					<optgroup label="Choose">
						<option value="select">Select</option>
						<option value="radio">Radio</option>
						<option value="checkbox">Checkbox</option>
					</optgroup>
					<optgroup label="Input">
						<option value="text">Text</option>
						<option value="textarea">Textarea</option>
						<option value="number">Number</option>
						<option value="email">Email</option>
					</optgroup>
					<optgroup label="File">
						<option value="file">File</option>
					</optgroup>
					<optgroup label="Date">
						<option value="date">Date</option>
						<option value="time">Time</option>
						<option value="datetime">Date &amp; Time</option>
					</optgroup>
				</select>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="form-group">
				<label><strong>Sort Order</strong> : </label>
				<input type="text" name="sort_order[]" value="" placeholder="" class="form-control">
			</div>
		</div>
	</div>
</main>
@endsection

@section('js-scripts')
<script type="text/javascript">
	var clone_id = {{ $i }};
	$(document).on('click','.add-clone',function(){
		clone_id++;
		var clone = $('.clone_fields').clone().html();
		$('.put_clone_here').append('<div class="row feild'+clone_id+'">'+clone+'<div class="col-sm-3"><button type="button" class="btn btn-sm btn-warning remove-row" data-row="feild'+clone_id+'">Remove</button></div></div>');
	});
	$(document).on('click','.remove-row',function(){
		$('.'+$(this).data('row')).remove();
	});
</script>
@endsection