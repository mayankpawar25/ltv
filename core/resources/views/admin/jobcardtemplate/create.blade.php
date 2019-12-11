@extends('admin.layout.master')

@section('title', 'Product Upload')

@section('headertxt', 'Product Upload')

@section('content')
<main class="app-content">
	<div class="main-content">
		<div>
			<h5>Add Job Card Template</h5>
		</div>
		<hr>
	<!--	<ul class="app-breadcrumb breadcrumb">
			<li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
			<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
		</ul>-->
	<form class="product-upload-form" action="{{ route('admin.jobcardtemplate.store') }}" method="post">
		{{ csrf_field() }}
		<div class="row">
			<div class="col-md-12">
				<div class="">
                	<div class="">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label>Form Title</label>
									<input type="text" name="form_title" value="" placeholder="" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-5">
								<div class="form-group">
									<label>Label Name : </label>
									<input type="text" name="label[]" value="" placeholder="" class="form-control">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>Type : </label>
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
							<div class="col-sm-2">
								<div class="form-group">
									<label>Sort Order : </label>
									<input type="text" name="sort_order[]" value="" placeholder="" class="form-control">
								</div>
							</div>
							<div class="col-sm-2">
                           <label class="text-right"><strong>&nbsp;</strong>  </label><br />

								<button type="button" class="btn btn-success float-right add-clone" data-toggle="tooltip" title="Add More"><span class="fa fa-plus"></span></button>
							</div>
						</div>
						<div class="put_clone_here"></div>
						<hr>
						<div class="row">
                            <div class="col-sm-12 text-right">
                            <div class="btn-wrapper">
								<button type="submit" class="btn btn-success">Save</button>
							</div></div>
						</div>
					</div>
					 
				</div>
			</div>
		</div>
	</form>
	<div class="row d-none clone_fields">
		<div class="col-sm-5">
			<div class="form-group">
				<label>Label Name : </label>
				<input type="text" name="label[]" value="" placeholder="" class="form-control">
			</div>
		</div>
		<div class="col-sm-3">
			<div class="form-group">
				<label>Type: </label>
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
		<div class="col-sm-2">
			<div class="form-group">
				<label>Sort Order : </label>
				<input type="text" name="sort_order[]" value="" placeholder="" class="form-control">
			</div>
		</div>
	</div>
	</div>
</main>
@endsection

@section('js-scripts')
<script type="text/javascript">
	var clone_id = 1;
	$(document).on('click','.add-clone',function(){
		var clone = $('.clone_fields').clone().html();
		$('.put_clone_here').append('<div class="row feild'+clone_id+'">'+clone+'<div class="col-sm-2"><label class="text-right"><strong>&nbsp;</strong>  </label><br /><button type="button" class="btn  btn-danger float-right remove-row" data-row="feild'+clone_id+'"><i class="fas fa-times" ></i></button></div></div>');
		clone_id++;
	});
	$(document).on('click','.remove-row',function(){
		$('.'+$(this).data('row')).remove();
	});
</script>
@endsection