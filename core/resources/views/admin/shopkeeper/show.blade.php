@extends('admin.layout.master')

@section('title', 'Product Update')

@section('headertxt', 'Product Update')

@section('content')
<main class="app-content">
	 
	<div class="">
		@php $documents = json_decode($shopkeeper->documents) @endphp
		<div class="row">
        <div class="col-md-12">
        <div class="main-content">
        <h5>View Dealer </h5>
        <hr />
        <div class="row">
        <div class="col-sm-8">
        <div class="row">
        <div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<strong>Personal Info</strong>
				</div>
				<div class="card-body">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered">
  <tr>
    <td><strong>Name  </strong></td>
    <td>{{ ($shopkeeper->name) }}</td>
  </tr>
  <tr>
    <td><strong>Shop Name</strong></td>
    <td>{{ ($shopkeeper->shopname) }}</td>
  </tr>
  <tr>
    <td><strong>Email</strong></td>
    <td>{{ ($shopkeeper->email) }}</td>
  </tr>
  <tr>
    <td><strong>Mobile</strong></td>
    <td>{{ ($shopkeeper->mobile) }}</td>
  </tr>
  <tr>
    <td><strong>Alternate Mobile</strong></td>
    <td>{{ ($shopkeeper->phone) }}</td>
  </tr>
  <tr>
    <td><strong>Country</strong></td>
    <td>{{ $shopkeeper->country->name }}</td>
  </tr>
  <tr>
    <td><strong>City</strong></td>
    <td>{{ $shopkeeper->city->name }}</td>
  </tr>
  <tr>
    <td><strong>Area</strong></td>
    <td>{{ (!empty($shopkeeper->area))?$shopkeeper->area->area_name:'' }}</td>
  </tr>
  <tr>
    <td><strong>Address</strong></td>
    <td>{{ strtolower($shopkeeper->address) }}</td>
  </tr>
  <tr>
    <td><strong>Group</strong></td>
    <td>{{ (!empty($shopkeeper->usergroup))?$shopkeeper->usergroup->name:'' }}</td>
  </tr>
  <tr>
    <td><strong>Salesman</strong></td>
    <td>{{ $shopkeeper->salesman->first_name }} {{ $shopkeeper->salesman->last_name }}</td>
  </tr>
   <tr>
    <td>Employer Name</td>
    <td>{{ $shopkeeper->employer_name }}</td>
  </tr>
   <tr>
    <td>Employer Contact no</td>
    <td>{{ $shopkeeper->employer_contactno }}</td>
  </tr>
</table>

					 
					<br>
					<div class="row">
						@if($shopkeeper->images != '')
							@php $images = json_decode($shopkeeper->images); @endphp
							@foreach($images as $key => $image)
			            		@if($key == 'owner_pic')
					              	<div class="col-sm-3">
						                <label><strong>Owner Pic</strong></label>
						                <div class="form-group">

						                  @if($image!='')
						                  	<img src="{{ asset('assets/shopkeeper/'.$shopkeeper->folder.'/'.$image) }}" id="owner_pic" style="width:100px;height:100px;">
						                  @else
						                  	<span class="badge badge-danger">Not available</span>
						                  @endif
						                </div>
					              	</div>
			            		@endif
			            		@if($key == 'shop_pic')
					              	<div class="col-sm-3">
						                <label><strong>Shop Pic</strong></label>
						                <div class="form-group">
						                  @if($image!='')
						                  <img src="{{ asset('assets/shopkeeper/'.$shopkeeper->folder.'/'.$image) }}" id="shop_pic" style="width:100px;height:100px;">
						                   @else
						                  	<span class="badge badge-danger">Not available</span>
						                  @endif
						                </div>
					              	</div>
			            		@endif
			            		@if($key == 'logo')
					              	<div class="col-sm-3">
						                <label><strong>Logo</strong></label>
						                <div class="form-group">
						                  @if($image!='')
						                  <img src="{{ asset('assets/shopkeeper/'.$shopkeeper->folder.'/'.$image) }}" id="logo" style="width:100px;height:100px;">
						                   @else
						                  	<span class="badge badge-danger">Not available</span>
						                  @endif
						                </div>
					              	</div>
			            		@endif
			            		@if($key == 'banner_image')
					              	<div class="col-sm-3">
						                <label><strong>Banner</strong></label>
						                <div class="form-group">

						                  @if($image!='')
						                  <img src="{{ asset('assets/shopkeeper/'.$shopkeeper->folder.'/'.$image) }}" id="banner" style="width:100px;height:100px;">
						                   @else
						                  	<span class="badge badge-danger">Not available</span>
						                  @endif
						                </div>
					              	</div>
			            		@endif
			            	@endforeach
			            @endif
					</div>
				</div>
				 
			</div>
		</div>
        <div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<strong>Documents</strong>
				</div>
				<div class="card-body">
					<div class="row">
						@if($documents!='')
							@forelse($documents as $key => $document)
								<div class="col-sm-4">
									<div class="card">
										@php $resp = explode('.',$document->image_name);@endphp
										<div class="card-header">
											<label><strong>{{ ucwords(str_replace('_',' ',$document->document_type)) }}</strong>
											</label>
											@if($document->is_verified==0)
												<span class="pull-right badge badge-danger" style="float:right">Not Verified</span>
											@elseif($document->is_verified==1)
												<span class="pull-right badge badge-success" style="float:right">Verified</span>
											@elseif($document->is_verified==2)
												<span class="pull-right badge badge-danger" style="float:right">Rejected</span>
											@else
												<span class="pull-right badge badge-warning" style="float:right">Hold</span>
											@endif
										</div>
										<div class="card-body">
											@if($resp[1] == 'pdf')
						                  		<img src="{{ asset('assets/images/pdf.jpg') }}" alt="Shop Pic" style="width:100px">
					                		@elseif($resp[1] == 'doc' || $resp[1] == 'docx')
						                  		<img src="{{ asset('assets/images/docx.png') }}" alt="Shop Pic" style="width:100px">
					                		@else
												<img src="{{ asset('assets/shopkeeper/'.$shopkeeper->folder.'/'.$document->image_name) }}" width="200px" height="150px">
											@endif
										</div>
										<div class="card-footer">

											@if(auth()->user()->is_administrator)
											<a style="float:right" class="btn btn-sm update-document" data-docid="{{$key}}" data-id="{{ $shopkeeper->id }}" data-selectedkey={{$document->is_verified}}><span class="fa fa-cog"></span></a>
											@endif

											<a href="{{ asset('assets/shopkeeper/'.$shopkeeper->folder.'/'.$document->image_name) }}" download="{{$document->document_type}}.{{$resp[1]}}" class="btn btn-sm"><span class="fa fa-download"></span></a>

											<a href="{{ asset('assets/shopkeeper/'.$shopkeeper->folder.'/'.$document->image_name) }}" target="_blank" class="btn btn-sm"><span class="fa fa-eye"></span></a>

											@if(auth()->user()->is_administrator)
											<!-- <a href="{{ asset('assets/shopkeeper/'.$shopkeeper->folder.'/'.$document->image_name) }}" target="_blank" class="btn btn-sm"><span class="fa fa-trash"></span></a> -->
											@endif
										</div>
									</div>
								</div>
							@empty
								<div class="form-group badge badge-danger">No Documents uploaded</div>
							@endforelse
						@endif
					</div>
				</div>
				 
			</div>
		</div>
		</div>
        </div>
        <div class="col-sm-4">
       <div class="row">
        <div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<strong>Status</strong>
				</div>
				<div class="card-body">
					<div class="">
						<div class="form-group">
							<strong>Email</strong> : @if($shopkeeper->email_verified==0)
								<span class="badge badge-danger">Unverified</span>
							@else
								<span class="badge badge-success">Verified</span>
							@endif
						</div>
						<div class="form-group">
							<strong>Phone</strong> : @if($shopkeeper->sms_verified==0)
								<span class="badge badge-danger">Unverified</span>
							@else
								<span class="badge badge-success">Verified</span>
							@endif
						</div>
						<div class="form-group">
							@php $doc_verification = 1; @endphp
							@if($documents!='')
								@forelse($documents as $key => $document)
									@if($document->is_verified == 0 || $document->is_verified == 2 || $document->is_verified == 3)
										@php $doc_verification = 0 @endphp
									@endif
								@empty
									@php $doc_verification = 0; @endphp
								@endforelse
							@endif

							@php
								$d_none = "disabled";
							@endphp
							@if(auth()->user()->is_administrator)
								@php
									$d_none = "";
								@endphp
							@endif
							<label><strong>Dealer Status </strong></label>
							{{-- @if($shopkeeper->is_verified==1)
								<span class="badge badge-success">Verified</span>
							@else --}}
								<select name="is_verified" class="form-control is_verified">
									<option value="0" {{ ($shopkeeper->is_verified == 0)?'selected':'' }}>Not Verified</option>
									<option value="1" {{ ($shopkeeper->is_verified == 1)?'selected':'' }}>Verified</option>
									<option value="2" {{ ($shopkeeper->is_verified == 2)?'selected':'' }}>Not Interested</option>
								</select>
							{{-- @endif --}}
						</div>
					</div>
					 
					<div class="">
						<strong>Status : </strong>
							@if($shopkeeper->status==0)
								@if($shopkeeper->is_verified==1 && auth()->user()->is_administrator)
										<a href="{{ route('admin.shopkeeper.status',[$shopkeeper->id,'1']) }}"><span class="btn btn-sm btn-warning">Inactive</span></a>
								@else
									<a href="#"><span class="btn btn-sm btn-warning">Inactive</span></a>
								@endif
							@else
								@if(auth()->user()->is_administrator)
									<a href="{{ route('admin.shopkeeper.status',[$shopkeeper->id,'0']) }}"><span class="btn btn-sm btn-success">Active</span></a>
								@else
									<a href="#"><span class="btn btn-sm btne-success">Active</span></a>
								@endif
							@endif
					</div>

				</div>
				 
			</div>
		</div>
        <div class="col-sm-12">
			<div class="card">
				<div class="card-header">
				@php
					$check = '';
					$comment = '';
				@endphp
				@if(strlen($shopkeeper->admin_verify) > 2)
					@php
						$verify = json_decode($shopkeeper->admin_verify);
						$check = (isset($verify->check))?'1':'';
						$comment = $verify->comment;
					@endphp
				@endif
					<strong>Admin Verification</strong></div>
				<div class="card-body">
					@if(auth()->user()->is_administrator)
					<form action="{{ route('admin_verify',$shopkeeper->id) }}" method="post" accept-charset="utf-8">
						{{ csrf_field() }}
						<div class="form-group">
							<label><input type="checkbox" name="admin_check[check]" class="" value="1" {{ ($check==1)?'checked':'' }}> Admin Check  
								<!--<span class="fa {{ ($check==1)?'fa-check':'fa-window-close' }}"></span>--></label>
						</div>
						<div class="form-group">
							<label>Comment </label>
							<textarea name="admin_check[comment]" class="form-control">{{ ($comment)?$comment:'' }}</textarea>
						</div>
						<div class="text-right">
                        <hr />
							<button type="submit" class="btn btn-sm btn-success">Save</button>
						</div>
					</form>
					@else
					<div class="form-group">
						@if($check == '')
						<div class="form-group">
							<strong>Status</strong> : <span class="badge badge-danger">Not Verified</span>
						</div>
						<br />
						<div class="form-group">
							<strong>Comment</strong> : {{ $comment }}
						</div>
						@else
						<div class="form-group">
							<strong>Status</strong> : <span class="badge badge-success">Verified</span>
						</div>
						<div class="form-group">
							<strong>Comment</strong> : {{ $comment }}
						</div>
						@endif

					</div>
					@endif
				</div>
			</div>
		</div>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
	</div>
	<br />
	<div class="row">
		
		
	</div>
</main>
<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Document Status</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="{{ route('admin.shopkeeper.doc_verify') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data" id="doc-form">
    	{{csrf_field()}}
	      <!-- Modal body -->
	      <div class="modal-body">
	        <input type="hidden" name="id" value="">
	        <input type="hidden" name="docid" value="">
	        <select name="doc_status" class="form-control">
	        	<option value="0">Not Verified</option>
	        	<option value="1">Verified</option>
	        	<option value="2">Rejected</option>
	        	<option value="3">Hold</option>
	        </select>
	      </div>
	    	<button type="submit" class="btn btn-info submit" style="display: none;">Save</button>
	    </form>


      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-info save-button">Save</button>
      </div>


    </div>
  </div>
</div>
@endsection
@section('js-scripts')
<script type="text/javascript">

	$(document).on('change','.is_verified',function(){
		var id = {{ $shopkeeper->id }};
		var is_verified = $(this).val();
		$.ajax({
			headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
			url: '{{ route('admin.shopkeeper.account_status') }}',
			type: 'POST',
			dataType: 'json',
			data: {id:id,is_verified:is_verified},
		})
		.done(function(resp) {
			console.log(resp);
		})
		.fail(function(resp) {
			console.log(resp);
		})
		.always(function(resp) {
			console.log(resp);
		});
	});

	$(document).on('click','.save-button',function(){
		$('.submit').click();		
	});

	$(document).on('click','.update-document',function(){
		var docid = $(this).data('docid');
		var id = $(this).data('id');
		var selected_attr = $(this).data('selectedkey');
		$('#myModal').find('input[name=id]').val(id);
		$('#myModal').find('input[name=docid]').val(docid);
		$('#myModal').find('select[name=doc_status] option').each(function(index,val){
			if(index==selected_attr){
				$(val).attr('selected',true);
			}else{
				$(val).attr('selected',false);
			}
		});
		$('#myModal').modal();
	});

	$(document).on('click','.checkboxforadmin',function(){
		if($(this).is(':checked')){
			$(this).parent().find('span').removeClass('fa-window-close');
			$(this).parent().find('span').addClass('fa-check');
		}else{
			$(this).parent().find('span').removeClass('fa-check');
			$(this).parent().find('span').addClass('fa-window-close');
		}
	})

</script>
@endsection