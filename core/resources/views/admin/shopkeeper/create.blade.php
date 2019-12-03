@extends('admin.layout.master')

@push('styles')
  <!-- stylesheet -->
  <!-- <link rel="stylesheet" href="{{asset('assets/user/css/style.css')}}"> -->
  <!-- responsive -->
  <!-- <link rel="stylesheet" href="{{asset('assets/user/css/responsive.css')}}"> -->
@endpush

@section('content')
<main class="app-content">
  <!--<div class="app-title">
    <div>
      <h1><i class="fa fa-dashboard"></i>Add Dealer</h1>
    </div>
   
  </div>-->
  <div class="row">
    <div class="col-md-12">
      <div class="main-content">
      <h5>Add Dealer</h5>
      <hr />
        <div class="">
          <form class="product-upload-form" action="{{ (isset($rec->id)) ? route( 'patch_lead', $rec->id) : route('admin.shopkeeper.store') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data" id="shop-form">
            <input type="hidden" name="user_role" value="{{ $data['user_role'] }}">
            {{csrf_field()}}
            <h3 class="tile-title pull-left"><strong></strong></h3>
            <div class="row">
              <div class="col-sm-3">
                <div class="form-group">
                  <label>Owner Name: </label>
                  <input type="text" name="owner_name" class="form-control" value="{{ old_set('owner_name',NULL,$rec) }}">
                  @if($errors->has('owner_name'))
                  <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('owner_name') }}</strong></span></p>
                  @endif
                  <div class="clearfix"></div>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label>Shop Name: </label>
                  <input type="text" name="shop_name" class="form-control"  value="{{ old_set('shop_name',NULL,$rec) }}">
                  @if($errors->has('shop_name'))
                  <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('shop_name') }}</strong></span></p>
                  @endif
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label>Email: </label>
                  <input type="text" name="email" class="form-control" value="{{ old_set('email',NULL,$rec) }}">
                  @if($errors->has('email'))
                  <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('email') }}</strong></span></p>
                  @endif
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label>Mobile: </label>
                  <input type="text" name="mobile" class="form-control" value="{{ old_set('mobile',NULL,$rec) }}">
                  @if($errors->has('mobile'))
                  <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('mobile') }}</strong></span></p>
                  @endif
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label>Alternate Number: </label>
                  <input type="text" name="phone" class="form-control" value="{{ old_set('phone',NULL,$rec) }}">
                  @if($errors->has('phone'))
                  <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('phone') }}</strong></span></p>
                  @endif
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label>Password: </label>
                  <input type="text" name="password" class="form-control" value="{{ old('password') }}">
                  @if($errors->has('password'))
                  <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('password') }}</strong></span></p>
                  @endif
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label>Country: </label>

                  <?php echo form_dropdown("country", $data['countries'], old_set("country_id", NULL, $rec), "class='form-control select2 '") ?>

                  <?php // echo form_dropdown("country_id", $data['countries'], old_set("country_id", NULL, $rec), "class='form-control  selectpicker '") ?>

                  @if($errors->has('country'))
                  <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('country') }}</strong></span></p>
                  @endif
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label>State: </label>
                  <select name="state" class="form-control select2">
                    <option value="">-- Select State --</option>
                  </select>
                  <!-- <input type="text" name="state" class="form-control"  value="{{ old('state') }}"> -->
                  @if($errors->has('state'))
                  <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('state') }}</strong></span></p>
                  @endif
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label>City: </label>
                  <select name="city" class="form-control select2">
                    <option value="">-- Select City --</option>
                  </select>
                  @if($errors->has('city'))
                  <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('city') }}</strong></span></p>
                  @endif
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label>Area: </label>
                  <select name="area" class="form-control select2">
                    <option value="">-- Select Area --</option>
                  </select>
                  @if($errors->has('area'))
                  <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('area') }}</strong></span></p>
                  @endif
                </div>
              </div>
              
              @php
                $class = ""
              @endphp
              @if(!auth()->user()->is_administrator)
                @php $class = "d-none"; @endphp
              @endif
              
              <div class="col-sm-3 {{ $class }}">
               <div class="form-group">
                  <label>Salesman: </label>
                  <!-- <select name="salesman_id" id="salesman_select" class="salesman_select form-control select2"> </select> -->
                  <?php echo form_dropdown("salesman_id", $data['salesman'], old_set("salesman_id", NULL, $rec), "class='form-control select2 '") ?>
                  
                </div>
              </div>
              <div class="col-sm-3">
               <div class="form-group">
                  <label>User Groups: </label>
                  <select name="usergroup_id" id="usergroup_select" class="usergroup_select form-control select2"> </select>
                </div>
              </div>
              <div class="col-sm-3 d-none">
                <div class="form-group">
                  <label>Status: </label>
                  <select name="status"  class="form-control">
                    <option value="0" selected>Inactive</option>
                    <option value="1">Active</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Address: </label>
                  <textarea type="text" rows="2" name="address" class="form-control" id="address_by_lat_long">{{ old('address') }}</textarea>
                  @if($errors->has('address'))
                  <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('address') }}</strong></span></p>
                  @endif
                </div>
              </div>
               <div class="col-sm-3 d-none">
                <div class="form-group">
                  <label>Latitude: </label>
                  <input type="text" id="latitude" name="latitude" class="form-control">
                </div>
              </div>
              <div class="col-sm-3 d-none">
                <div class="form-group">
                  <label>Longitude: </label>
                  <input type="text" id="longitude" name="longitude" class="form-control">
                </div>
              </div>
              {{-- <div class="col-sm-3">
                <div class="form-group">
                  <label>Tags: </label>
                  <select name="tag[]" class="form-control select2 form-control custom-select" multiple="true">
                    <option value="">-- Select Tags --</option>
                    @forelse($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @empty
                    <option value="">No Tag Available</option>
                    @endforelse
                  </select>
                  @if($errors->has('tag'))
                  <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('tag') }}</strong></span></p>
                  @endif
                </div>
              </div> --}}
              
              <div class="col-sm-3"></div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-3">
                <label>Owner Pic</label>
                <div class="form-group"> 
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="owner_pic" value="" accept="image/*">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                  </div>                         
                  <!--<input type="file" name="owner_pic" accept="image/*">-->
                  <img src="" id="owner_pic" style="width:100px;height:100px;display:none;">
                  @if($errors->has('owner_pic'))
                  <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('owner_pic') }}</strong></span></p>
                  @endif
                </div>
              </div>
              <div class="col-sm-3">
                <label>Shop Pic</label>
                <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="shop_pic" accept="image/*">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                  </div>                           
                  <!--<input type="file" name="shop_pic" accept="image/*">-->
                  <img src="" id="shop_pic" style="width:100px;height:100px;display:none;">
                  @if($errors->has('shop_pic'))
                  <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('shop_pic') }}</strong></span></p>
                  @endif
                </div>
              </div>
              <div class="col-sm-3">
                <label>Logo</label>
                <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="logo" accept="image/*">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                  </div>                    
                  <!--<input type="file" name="logo" accept="image/*"> -->
                  <img src="" id="logo" style="width:100px;height:100px;display:none;">
                  @if($errors->has('logo'))
                  <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('logo') }}</strong></span></p>
                  @endif
                </div>
              </div>
              <div class="col-sm-3">
                <label>Banner</label>
                <div class="form-group"> 
                 <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="banner" accept="image/*">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                  </div>                             
                  <!--<input type="file" name="banner" accept="image/*">-->
                  <img src="" id="banner" style="width:100px;height:100px;display:none;">
                  @if($errors->has('banner'))
                  <p class="text-danger m-t-20"><span class="help-block"><strong>{{ $errors->first('banner') }}</strong></span></p>
                  @endif
                </div>
              </div>
            </div>
            <hr />
             
            <div class="row">
              <div class="col-sm-3">
              <label>Select document </label>
                <select class="form-control" name="doc_type[]">
                  <option>-- Select Document Type --</option>
                  <option value="gumasta">Gumasta</option>
                  <option value="license">License</option>
                  <option value="address_proof">Address Proof</option>
                  <option value="pan_card">PAN Card</option>
                  <option value="shop_license">Shop License</option>
                  <option value="gst_document">GST Document</option>
                </select>
              </div>
              <div class="col-sm-3">
                <div class="form-group"> 
                 <label>document </label>
                 <div class="custom-file">
                
                    <input type="file" class="custom-file-input" id="customFile" name="doc[]" onchange="imagePreview(this,'preview')">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                  </div>                            
                 <!-- <input type="file" name="doc[]" onchange="imagePreview(this,'preview')">-->
                  
                </div>
              </div>
              <div class="col-sm-3">
              <label class="pull-left">Add / remove</label>
                <br />
                <button type="button" class="btn btn-success fa-fix btn-sm pull-left" id="add_more_docs"><strong><i class="fa fa-plus"></i></strong> </button>
              </div>
              <div class="col-md-3"><img src="" style="width:100px; margin-bottom:8px; height:100px;margin-bottom:8px; display:none;" id="preview"></div>
            </div>
            <span id="put_clone_here"></span>
           <!-- <div class="form-group">
              <button type="submit" class="btn btn-success">Save</button>
            </div>-->
            <hr />
            	<div class="text-right">
	                          	<input type="submit" class="btn btn-success" value="Save">
	                      	</div>
            
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

  
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.js"></script>
<script type="text/javascript">
    $(document).on('blur','#address_by_lat_long',function(){
        var address = $(this).val();
        $.ajax({
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          url: "{{ route('location.getlatlong') }}",
          type: 'POST',
          dataType: 'json',
          data: {address: address},
        })
        .done(function(resp) {
          $('#latitude').val(resp.latitude);
          $('#longitude').val(resp.longitude);
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
    });

    var count=0;
    $(document).on('click','#add_more_docs',function(){
      count++;
      var html = '';
      html += '<div class="row" id="rows_'+count+'">';
      html += '<div class="col-sm-3 form-group">';
      html += '<select class="form-control" name="doc_type[]">';
      html += '<option>-- Select Document Type --</option>';
      html += '<option value="gumasta">Gumasta</option>';
      html += '<option value="license">License</option>';
      html += '<option value="address_proof">Address Proof</option>';
      html += '<option value="pan_card">PAN Card</option>';
      html += '<option value="shop_license">Shop License</option>';
      html += '<option value="gst_document">GST Document</option>';
      html += '</select>';
      html += '</div>';
      html += '<div class="col-sm-3">';
      html += '<div class="form-group">                          ';
      html += '<div class="custom-file"><input type="file" class="custom-file-input" id="customFile" name="doc[]" onchange="imagePreview(this,\'preview'+count+'\')" accept=""><label class="custom-file-label" for="customFile">Choose file</label></div>';
     
    html += '</div>';
    html += '</div>';
    html += '<div class="col-sm-3"><button type="button" class="btn btn-danger btn-sm fa-fix" onclick="remove('+count+')"><i class="fa fa-trash"></i></button></div>';
	 html += '<div class="col-sm-3"><img src="" style="width:100px;height:100px;margin-bottom:8px; display:none;" id="preview'+count+'"></div>';
      html += '</div>';
      $('#put_clone_here').append(html);
    });

    function remove(id){
      $('#rows_'+id).remove();
    }

    $(document).ready(function(){
      $(".select2").select2({
        placeholder: "-- Select Tag --",
      });
    });

    $('input[name=shop_pic]').change(function() {
     readURL(this,'shop_pic');
    });


    $('input[name=owner_pic]').change(function() {
     readURL(this,'owner_pic');
    });

    $('input[name=logo]').change(function() {
     readURL(this,'logo');
    });

    $('input[name=banner]').change(function() {
     readURL(this,'banner');
    });

    function imagePreview(e,id){
      readURL(e,id);
    }

    // function readURL(input,id) {
    //   console.log(input);
    //   if (input.files && input.files[0]) {
    //     var reader = new FileReader();
    //     reader.onload = function(e) {
    //       $('#'+id).attr('src', e.target.result);
    //       $('#'+id).show();
    //     }
    //     reader.readAsDataURL(input.files[0]);
    //   }
    // }

  function getExtension(filename) {
      return filename.split('.').pop().toLowerCase();
  }

  function readURL(input,id) {
    var ext = (getExtension(input.files[0].name));
    if(ext == 'png' || ext == 'jpeg' || ext == 'jpg'){
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e) {
            $('#'+id).attr('src', e.target.result);
            $('#'+id).show();
          }
          reader.readAsDataURL(input.files[0]);
        }
    }else if(ext == 'docx' || ext == 'doc'){
      $('#'+id).attr('src', '{{asset('assets/images/docx.png')}}');
          $('#'+id).show();
    }else if(ext == 'pdf'){
      $('#'+id).attr('src', '{{asset('assets/images/pdf.jpg')}}');
          $('#'+id).show();
    }else{
      alert('Only pdf, jpg, png, docx, doc files are allowed to upload');
    }
  }

  $(document).on('change','select[name=country]',function(){
    var country_id = $(this).val();
    $.ajax({
      url: "{{ route('get.states') }}",
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      method: 'POST',
      data  : { country_id : country_id},
      success: function(data) {
        $('select[name=state]').html(data.html);
      }
    });
  });

  $(document).on('change','select[name=state]',function(){
    var state_id = $(this).val();
    $.ajax({
      url: "{{ route('get.cities') }}",
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      method: 'POST',
      data  : { state_id : state_id},
      success: function(data) {
        $('select[name=city]').html(data.html);
      }
    });
  });

  $(document).on('change','select[name=city]',function(){
    var city_id = $(this).val();
    $.ajax({
      url: "{{ route('get.zipcode') }}",
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      method: 'POST',
      data  : { city_id : city_id},
      success: function(data) {
        $('select[name=area]').html(data.html);
      }
    });
  });


	//---------------------------------------------------------
  	// Validator
  	//---------------------------------------------------------
  	/*Shop Form Velidation*/
	$( document ).ready( function () {
	  $.validator.addMethod("mobile_regex", function(value, element) {
	    return this.optional(element) || /^\d{10}$/i.test(value);
	  }, "Please enter a valid Phone number.");
	  $.validator.addMethod("email_regex", function(value, element) {
	    return this.optional(element) || /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/i.test(value);
	  }, "Please enter a valid email address"); 
	  
	  $( "#shop-form" ).validate( {
	      submitHandler: function(form) {
	         form.submit();
	       },
	      rules: {
	          owner_name: {
	              required: true,
	          },
	          shop_name:{
	              required:true,
	          },
	          gender:{
	              required:true,
	          },
	          email: {
	              required: true,
	              email_regex:true,                 
	          },
	          mobile: {
	              required: true,
	              mobile_regex: true,
	          },
            phone: {
                mobile_regex: true,
            },
            password: {
                required: true,
            },
	          country :{
	            required: true,
	          },
	          state: {
	              required: true,
	          },
	          city: {
	              required: true,
	          },
	          area:{
	            //required :true,
	          },
	          address:{
	            required:true,
	          },
	          /*owner_pic:{
	            required:true,
	          },
	          shop_pic:{
	            required:true,
	          },
	          logo:{
	            required:true,
	          },
	          banner:{
	            required:true,
	          },*/
	          status:{
	            required:true,
	          },
	      },
	      messages: {
	          first_name: {
	              required: "Please Enter First Name.",
	          },
	          last_name:{
	            required:"Please Enter Last Name.",
	          },
	          email: {
	              required: "Please Enter Shop Email Id.",
	              //remote: "This Delivery Boy Email Already Exits."
	          },
            mobile: {
                required: "Please Enter Mobile Number.",
            },
            /*phone: {
                required: "Please Enter Alternate Mobile Number.",
            },*/
             password: {
                required: "Please Enter Password.",
            },
	          country :{
	            required : "Please Select Country",
	          },
	          state: {
	              required: "Please Select State.",
	          },
	          city:{
	            required : "Please Select City",
	          },
	          area:{
	            required : "Please Select Area",
	          },
	          address:{
	            required : "Please Enter Address",
	          },
	          /*owner_pic:{
	            required : "Please Select Owner Profile Image",
	          },
	          shop_pic:{
	            required : "Please Select Shop Image",
	          },
	          logo:{
	            required : "Please Select Shop Logo Image",
	          },
	          banner:{
	            required : "Please Select Banner Image",
	          },*/
	          status: {
	              required: "Please Select Shop Status.",
	          },
	      },
	      errorElement: "span",
        errorClass: "text-danger help-block",
        errorPlacement: function ( error, element ) {
        if(element.parent('.form-group').length) {
              error.insertAfter(element.parent());
          } else {
              error.insertAfter(element);
          }
       },
	  });
	});

  $(document).ready(function($) {
    $(".select2").select2();
    /*Salesman List*/
    $.ajax({
      url: "{{ route('get.salesman') }}",
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      method: 'POST',
      success: function(data) {
        $('#salesman_select').html(data.html);
      }
    });
    /*User Group List*/
    $.ajax({
      url: "{{ route('get.usergroup') }}",
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      method: 'POST',
      success: function(data) {
        $('#usergroup_select').html(data.html);
      }
    });
  });
</script>
@endsection