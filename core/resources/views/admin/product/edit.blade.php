@extends('admin.layout.master')

@section('title', 'Product Update')

@section('headertxt', 'Product Update')

@push('nicedit-scripts')
  <script src="{{asset('assets/nic-edit/nicEdit.js')}}" type="text/javascript"></script>
  <script type="text/javascript">
    bkLib.onDomLoaded(function() {
      new nicEditor({iconsPath : '{{asset('assets/nic-edit/nicEditorIcons.gif')}}', fullPanel : true}).panelInstance('desc');
    });
  </script>
@endpush

@push('styles')
	 

	<link rel="stylesheet" href="{{asset('assets/user/css/jquery.datetimepicker.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap-toggle.min.css')}}">

	<!-- stylesheet -->
	<link rel="stylesheet" href="{{asset('assets/user/css/style.css')}}">
	<!-- responsive -->
	<link rel="stylesheet" href="{{asset('assets/user/css/responsive.css')}}">
@endpush

@section('content')
<style>
table tr td{
		border-top:none !important;
	}
	#imgtable td {

    padding: 0px 15px 0px 0px;

}
</style>
<main class="app-content">
	<div class="app-title">
        <div>
           <h1> </h1>
        </div>
       <!-- <ul class="app-breadcrumb breadcrumb">
           <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
           <li class="breadcrumb-item"><a href="{{route('admin.product.index')}}">Products</a></li>
        </ul>-->
    </div>

    <div class="main-content">
    <h5>Edit Product</h5>
    <hr />
	    <div class="row">
	      	<div class="col-lg-12">
	          	<div class="product-upload-inner"><!-- product upload inner -->
	              	<form id="uploadForm" class="product-upload-form" onsubmit="upload(event)" enctype="multipart/form-data">
	                  	{{csrf_field()}}
	                  	
	                  	<input type="hidden" name="product_id" value="{{$product->id}}">
	                  	<div class="well">
	                  	<div class="form-element margin-bottom-20">
	                        <label for="" class="sec-txt">Preview Images <span>*</span></label>
	                        <div class="">
	                          <table class="table table-striped" id="imgtable">

	                          </table>
	                        </div>
	                        <div class="form-group">
	                          <label class="btn btn-success btn-sm" style="color:#fff;">
	                            <input id="imgs" style="display:none;" type="file" />
	                            <i class="fa fa-plus"></i> Add a photo
	                          </label>
	                          <p class="no-margin"><small>Maximum 5 images can be uploaded</small></p>
	                          <input type="hidden" name="imgs_helper" value="">
	                          <p id="errpreimg" class="em no-margin text-danger"></p>
	                        </div>
	                  	</div>
						</div>
	                  	<div class="row">
	                        <div class="col-md-6">
	                          <div class="form-element margin-bottom-20">
	                              <label>Title <span>*</span></label>
	                              <input name="title" type="text" class="form-control" value="{{$product->title}}" placeholder="Enter title...">
	                              <p id="errtitle" class="em no-margin text-danger"></p>
	                          </div>
	                        </div>
	                        <div class="col-md-6">
	                          <div class="row">
	                            <div class="col-md-6">
	                              <div class="form-element margin-bottom-20">
	                                  <label>Stock (quantity) <span>*</span></label>
	                                  <input name="quantity" type="text" class="form-control" value="{{$product->quantity}}" placeholder="Enter quantity...">
	                                  <p id="errquantity" class="em no-margin text-danger"></p>
	                              </div>
	                            </div>
	                            <div class="col-md-6">
	                              	<div class="form-element margin-bottom-20">
	                                  	<label>Price ({{$gs->base_curr_text}})<span>*</span></label>
	                                  	<input name="price" type="text" class="form-control" value="{{$product->price}}" placeholder="Enter price...">
	                                  	<p id="errprice" class="em no-margin text-danger"></p>
	                              	</div>
	                            </div>
	                          </div>
	                        </div>
	                  	</div>

	                  	<div class="row">
	                        <div class="col-md-4">
	                          <div class="form-element margin-bottom-20">
	                              <label>Category <span>*</span></label>
	                              <select name="category" type="text" class="form-control" v-model="catid" onchange="showsubcats(this.value)">
	                                @foreach ($cats as $cat)
	                                  <option value="{{$cat->id}}" {{ ($cat->id == $product->category_id)?'selected':'' }}>{{$cat->name}}</option>
	                                @endforeach
	                              </select>
	                              <input type="hidden" name="cat_helper" value="">
	                              <p id="errcat" class="em no-margin text-danger"></p>
	                          </div>
	                        </div>
	                        <div class="col-md-4">
	                          <div class="form-element margin-bottom-20">
	                              <label>Subcategory <span>*</span></label>
	                              <select name="subcategory" type="text" class="form-control" v-model="subcatid" id="selsub" onchange="showattrs(this.value)">
	                                @foreach ($subcats as $subcat)
	                                  <option value="{{$subcat->id}}" {{ ($subcat->id == $product->subcategory_id)?'selected':'' }}>{{$subcat->name}}</option>
	                                @endforeach
	                              </select>
	                              <input type="hidden" name="subcat_helper" value="">
	                              <p id="errsubcat" class="em no-margin text-danger"></p>
	                          </div>
	                        </div>
	                        <div class="col-md-4">
	                          <div class="form-element margin-bottom-20">
	                              <label>Product Code <span>(Optional)</span></label>
	                              <input name="product_code" type="text" class="form-control" value="{{$product->product_code}}" placeholder="Enter product code...">
	                              <small>If you do not specify an unique product code it will be generated automatically.</small>
	                              <p id="errcode" class="em no-margin text-danger"></p>
	                          </div>
	                        </div>
	                  	</div>

	                  	<div id="proattrsid">
	                        @if (array_key_exists('attributes', $attrs))
	                          @foreach ($attrs['attributes'] as $attr)
	                            @php
	                              $attrname = \App\ProductAttribute::find($attr)->attrname;
	                            @endphp
	                            @if ($loop->iteration % 3 == 1)
	                              <div class="row">
	                            @endif
	                                <div class="col-md-4">
	                                    <div class="form-element margin-bottom-20">
	                                      <label>
	                                        @if (str_contains($attrname, '_'))
	                                          @php
	                                            $pieces = explode("_", $attrname);
	                                          @endphp
	                                          @foreach ($pieces as $piece)
	                                            <span style="text-transform: capitalize;color:black;">{{$piece}} </span>
	                                          @endforeach
	                                        @else
	                                          <span style="text-transform: capitalize;color:black;">{{$attrname}}</span>
	                                        @endif
	                                        <span>**</span>
	                                      </label>
	                                      @php
	                                        $options = \App\Option::where('product_attribute_id', $attr)->get();
	                                        // {{dd($checkedattrs,$options)}}
	                                      @endphp
	                                      <div>
	                                          @foreach ($options as $option)
	                                              <div class="form-check form-check-inline">
	                                                <input name="{{$attrname}}[]" value="{{$option->name}}" class="form-check-input" type="checkbox" id="attr{{$option->id}}"
	                                                @if($checkedattrs)
		                                                @if(array_key_exists("$attrname", $checkedattrs))
		                                                  @if(in_array($option->name, $checkedattrs["$attrname"]))
		                                                    checked
		                                                  @endif
		                                                @endif
	                                                @endif
	                                                  >
	                                                <label class="form-check-label" for="attr{{$option->id}}">{{$option->name}}</label>
	                                              </div>
	                                          @endforeach
	                                      </div>
	                                      <p class="em text-danger no-margin" id="err{{$attrname}}"></p>
	                                    </div>
	                                </div>
	                            @if ($loop->iteration % 3 == 0 || count($attrs['attributes']) < 3)
	                              <!-- </div> -->
	                            @endif
	                          @endforeach
	                        @endif
	                  	</div>

	                  	<div class="form-element margin-bottom-20">
	                     	<label>Description <span>*</span></label>
	                     	<textarea class="form-control" id="desc" rows="10">{{$product->description}}</textarea>
	                     	<p id="errdesc" class="em no-margin text-danger"></p>
	                  	</div>
	                  	<br>

	                  	<div class="row">
						    <div class="col-sm-12"><strong>User Group Price</strong></div><br>
						    @php $i=0 @endphp
						    @php $product_usergroup = json_decode($product->usergroup_prices) @endphp

						    @foreach($usergroups as $key => $usergrouping)
						    	@if(empty($product_usergroup))
						    	<div class="col-sm-4">
							    	<label><strong>{{ ucwords(str_replace('_',' ',$usergrouping->slug)) }} Price</strong></label>
							    	<input type="hidden" name="usergroups[{{$i}}][id]" value="{{$usergrouping->id}}">
	                				<input type="hidden" name="usergroups[{{$i}}][slug]" value="{{$usergrouping->slug}}">
							    	<input type="text" class="form-control" name="usergroups[{{$i}}][price]" value="" placeholder="">
							    </div>
							    @else
								    @foreach($product_usergroup as $key => $usergroup)
								    	@if($usergroup->slug == $usergrouping->slug)
										    <div class="col-sm-4">
										    	<label><strong>{{ ucwords(str_replace('_',' ',$usergroup->slug)) }} Price</strong></label>
										    	<input type="hidden" name="usergroups[{{$i}}][id]" value="{{$usergroup->id}}">
				                				<input type="hidden" name="usergroups[{{$i}}][slug]" value="{{$usergroup->slug}}">
										    	<input type="text" class="form-control" name="usergroups[{{$i}}][price]" value="{{ $usergroup->price }}" placeholder="">
										    </div>
									    @endif
								    @endforeach
							    @endif
						    	@php $i++ @endphp
						    @endforeach

					    </div>

					    <br>

	                  	<div class="row">
	                        <div class="col-md-12">
	                          	<div class="">
		                            <div class="">
		                              <strong>Seclect Offer (Fixed/Percentage)</strong>
                                      <hr />
		                            </div>
		                            <div class="">
		                              	<div class="row">
			                                <div class="col-md-2">
			                                  <div class="form-element margin-bottom-20 check-round">
			                                    <label class="d-block">Offer <span>*</span></label>
			                                    <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
			                                       data-width="100%" type="checkbox"
			                                       name="offer" onchange="changeOffer()" {{!empty($product->offer_type) ? 'checked' : ''}}>
			                                  </div>
			                                </div>

			                                <div class="col-md-2">
			                                  <div class="form-element margin-bottom-20 d-none check-round" id="offerType">
			                                    <label class="d-block">Offer Type <span>*</span></label>
			                                    <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
			                                       data-width="100%" type="checkbox" data-on="Percentage" data-off="Fixed"
			                                       name="offer_type" id="offerTypeToggle" {{$product->offer_type == 'percent' ? 'checked' : ''}}>
			                                  </div>
			                                </div>
			                                <div class="col-md-2">
			                                  <div class="form-element margin-bottom-20 d-none" id="offerAmount">
			                                    <label>Offer Amount <span>*</span></label>
			                                    <input name="offer_amount" type="text" class="form-control" placeholder="Enter offer amount..." value="{{$product->offer_amount}}">
			                                    <p id="errofferamount" class="em no-margin text-danger"></p>
			                                    <div id="calcTotal"></div>
			                                  </div>
			                                </div>
		                              	</div>
	                              	</div>
	                            </div>
	                      	</div>
	                	</div>

	                    <br>

	                  	<div class="row">
	                    	<div class="col-md-12">
	                      <div class="">
	                        <div class="">
	                         <strong>Seclect Flash Sale (Fixed/Percentage)</strong>
                             <hr />
	                        </div>
	                        <div class="">
	                          <div class="row">
	                            <div class="col-md-2">
	                              <div class="margin-bottom-20 check-round">
	                                <label class="d-block">Flash Sale <span>*</span></label>
	                                <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
	                                   data-width="100%" type="checkbox"
	                                   name="flash_sale" {{$product->flash_sale == 1 ? 'checked' : ''}}>
	                              </div>
	                            </div>

	                            <div class="col-md-8" id="flashsale">
	                              <div class="row">
	                                <div class="col-md-3">
	                                  <div class="margin-bottom-20 check-round">
	                                    <label class="d-block">Type <span>*</span></label>
	                                    <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
	                                       data-width="100%" type="checkbox" data-on="Percentage" data-off="Fixed"
	                                       name="flash_type" {{$product->flash_type == 1 ? 'checked' : ''}}>
	                                  </div>
	                                </div>

	                                <div class="col-md-3">
	                                  <div class="margin-bottom-20">
	                                    <label class="d-block">Amount <span>*</span></label>
	                                    <div class="">
	                                      <input class="form-control" type="text" name="flash_amount" value="{{$product->flash_amount}}" autocomplete="off" placeholder="Enter flash amount">
	                                    </div>
	                                    <p id="errflashamount" class="em no-margin text-danger"></p>
	                                    <div id="calcTotalFlash"></div>
	                                  </div>
	                                </div>

	                                <div class="col-md-3">
	                                  <div class=" margin-bottom-20">
	                                    <label class="d-block">Date <span>*</span></label>
	                                    <div class="">
	                                      <input id="flash_date" class="form-control" type="text" name="flash_date" value="{{$product->flash_date}}" placeholder="Enter flash date" autocomplete="off">
	                                    </div>
	                                    <p id="errflashdate" class="em no-margin text-danger"></p>
	                                  </div>
	                                </div>

	                                <div class="col-md-3">
	                                  <div class="margin-bottom-20">
	                                    <label class="d-block">Time interval <span>*</span></label>
	                                    <div class="">
	                                      <select class="form-control" name="flash_interval">
	                                        @foreach ($flashints as $key => $flashint)
	                                          <option value="{{$flashint->id}}" {{$flashint->id == $product->flash_interval ? 'selected' : ''}}>{{$flashint->start_time . " - " . $flashint->end_time}}</option>
	                                        @endforeach
	                                      </select>
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

	                  	<div class="text-right">
                        <hr />
	                      	<input type="submit" class="btn btn-success" value="Update Product">
	                  	</div>
	              	</form>
	          	</div><!-- //.product upload inner -->
	      	</div>
	  	</div>
    </div>
    
    
	<!-- product upload area start -->
	<div class="product-upload-area" id="editproduct" style="padding: 0px 0px;" >
	   
	</div>
	<!-- product upload area end -->
</main>


@endsection


@section('js-scripts')
  <script src="{{asset('assets/user/js/jquery.datetimepicker.full.min.js')}}"></script>
  <!-- <script src="{{asset('assets/admin/js/bootstrap-toggle.min.js')}}"></script> -->
  <script>
    var curr = '{{$gs->base_curr_text}}';
    function calcTotalForOffer() {
      let offeramount = $("input[name='offer_amount']").val();
      let offertype = $("input[name='offer_type']:checked").val();
      let price = $("input[name='price']").val();
      // if offer amount is present
      if (offeramount.length > 0) {
        // if price is present
        if (price.length > 0) {
          $("#calcTotal").html('');
          $("#calcTotal").removeClass('text-danger');
          // what is the offer type, take action depending on the type
          if (offertype == 'on') {
            // if offer type is Percentage...
            let offer = (offeramount*price)/100;
            let total = price - offer;
            $("#calcTotal").html('<strong>Price: </strong>'+price + ' ' + curr +'<br/><strong>Offer: </strong>'+offer + ' ' + curr +'<br/><strong>Total: </strong>' + total + ' ' + curr);
          } else {
            // if offer type is Fixed...
            let total = price - offeramount;
            $("#calcTotal").html('<strong>Price: </strong>'+price + ' ' + curr + '<br/><strong>Offer: </strong>'+offeramount + ' ' + curr+'<br/><strong>Total: </strong>' + total + ' ' + curr);
          }
          console.log(offeramount);
        } else {
          $("#calcTotal").html('Enter price first');
          $("#calcTotal").addClass('text-danger')
        }
      }
    }
    function calcTotalForFlash() {
      let flashamount = $("input[name='flash_amount']").val();
      let flashtype = $("input[name='flash_type']:checked").val();
      let price = $("input[name='price']").val();
      // if offer amount is present
      if (flashamount.length > 0) {
        // if price is present
        if (price.length > 0) {
          $("#calcTotalFlash").html('');
          $("#calcTotalFlash").removeClass('text-danger');
          // what is the offer type, take action depending on the type
          if (flashtype == 'on') {
            // if offer type is Percentage...
            let offer = (flashamount*price)/100;
            let total = price - offer;
            $("#calcTotalFlash").html('<strong>Price: </strong>'+price + ' ' + curr +'<br/><strong>Offer: </strong>'+offer + ' ' + curr +'<br/><strong>Total: </strong>' + total + ' ' + curr);
          } else {
            // if offer type is Fixed...
            let total = price - flashamount;
            $("#calcTotalFlash").html('<strong>Price: </strong>'+price + ' ' + curr + '<br/><strong>Offer: </strong>'+flashamount + ' ' + curr+'<br/><strong>Total: </strong>' + total + ' ' + curr);
          }
          console.log(flashamount);
        } else {
          $("#calcTotalFlash").html('Enter price first');
          $("#calcTotalFlash").addClass('text-danger')
        }
      }
    }
    $(document).ready(function() {
      $("input[name='offer_amount'], input[name='price'], input[name='offer_type']").on('input', calcTotalForOffer);
      $("input[name='offer_type']").on('change', calcTotalForOffer);
      $("input[name='flash_amount'], input[name='price']").on('input', calcTotalForFlash);
      $("input[name='flash_type']").on('change', calcTotalForFlash);

      calcTotalForOffer();
      calcTotalForFlash();
    });
  </script>
  <script>
    $(document).ready(function() {
      $('#flash_date').datetimepicker({
       format:'Y/m/d',
       timepicker: false
      });

      let flashsale = $("input[name='flash_sale']:checked").val();
      console.log('flash ' + flashsale);
      if (flashsale == 'on') {
        $("#flashsale").removeClass('d-none');
        $("#flashsale").addClass('d-block');
      } else {
        $("#flashsale").removeClass('d-block');
        $("#flashsale").addClass('d-none');
      }

      $("input[name='flash_sale']").on('change', function () {
        let flashsale = $("input[name='flash_sale']:checked").val();
        console.log(flashsale);
        if (flashsale == 'on') {
          $("#flashsale").removeClass('d-none');
          $("#flashsale").addClass('d-block');
        } else {
          $("#flashsale").removeClass('d-block');
          $("#flashsale").addClass('d-none');
        }
      });
    });

    $(document).ready(function() {
      let offer = $("input[name='offer']:checked").val();
      console.log('offer ' + offer);
      if (offer == 'on') {
        $("#offerType").removeClass("d-none");
        $("#offerAmount").removeClass("d-none");
        $("#offerType").addClass("d-block");
        $("#offerAmount").addClass("d-block");
      }
    });

    function changeOffer() {
      let offer = $("input[name='offer']:checked").val();
      console.log(offer);
      if (offer == 'on') {
        $("#offerType").removeClass("d-none");
        $("#offerAmount").removeClass("d-none");
        $("#offerType").addClass("d-block");
        $("#offerAmount").addClass("d-block");
      } else {
        $("#offerType").removeClass("d-block");
        $("#offerAmount").removeClass("d-block");
        $("#offerType").addClass("d-none");
        $("#offerAmount").addClass("d-none");
      }
    }
  </script>
  <script>
    var el = 0;
    var imgs = [];
    var imgsdb = [];


    $(document).on('change', '#imgs', function(e) {
        if (this.files.length && (imgs.length+imgsdb.length) < 5) {
          el++;
          $("#imgtable").append('<tr class="trcl" id="tr'+(el-1)+'"><td><img class="preimgs"></td><td><button class="rmvbtn btn btn-danger btn-sm" type="button" onclick="rmvimg('+(el-1)+')"><i class="fa fa-times"></i></button></td></tr>');
          var file = this.files[0];
          var reader = new FileReader();

          reader.onload = function(e) {
              var data = e.target.result;

              document.getElementsByClassName('preimgs')[el-1].setAttribute('src', data);
              document.getElementsByClassName('preimgs')[el-1].setAttribute('style', 'width:150px');
          };

          reader.readAsDataURL(file);
          imgs.push(file);
          console.log(imgs);
        }

    });

    function rmvimg(index) {
        $("#tr"+index).remove();
        imgs.splice(index, 1);
        console.log(imgs);
        var trcl = document.getElementsByClassName('trcl');
        var rmvbtn = document.getElementsByClassName('rmvbtn');
        for(el=0; el<trcl.length; el++) {
            trcl[el].setAttribute('id', 'tr'+el);
            rmvbtn[el].setAttribute('onclick', 'rmvimg('+el+')');
        }
    }

    $(document).ready(function() {
      setTimeout(function() {
        $.get("{{route('admin.product.getimgs', $product->id)}}", function(data){
            for (var i = 0; i < data.length; i++) {
              imgsdb.push(data[i].image);
              $("#imgtable").append('<tr class="trdb" id="trdb'+i+'"><td><div class="thumbnail"><img style="width:150px;" src="{{asset('assets/user/img/products')}}/'+data[i].image+'" alt="Ad Image"></div></td><td><button type="button" class="btn btn-danger btn-sm pull-right rmvbtndb" onclick="rmvdbimg('+i+')"><i class="fa fa-times"></i></button></td></tr>');
            }
            console.log(imgsdb);
        });
      }, 2000);
    });

    function rmvdbimg(indb) {
      $("#trdb"+indb).remove();
      imgsdb.splice(indb, 1);
      console.log(imgsdb);
      var trdb = document.getElementsByClassName('trdb');
      var rmvbtndb = document.getElementsByClassName('rmvbtndb');
      for (var i = 0; i < rmvbtndb.length; i++) {
        trdb[i].setAttribute('id', 'trdb'+i);
        rmvbtndb[i].setAttribute('onclick', 'rmvdbimg('+i+')');
      }
    }

    function upload(e) {
      e.preventDefault();

      swal({
        title: "Checking...",
        text: "Please wait",
        icon: "{{asset('assets/user/img/ajax-loading.gif')}}",
        buttons: false,
        closeOnClickOutside: false
      });

      var uploadForm = document.getElementById('uploadForm');

      var fd = new FormData(uploadForm);
      var descriptionElement = new nicEditors.findEditor('desc');
      description = descriptionElement.getContent();
      for (var i = 0; i < imgs.length; i++) {
        fd.append('images[]', imgs[i]);
      }
      for (var k = 0; k < imgsdb.length; k++) {
        fd.append('imgsdb[]', imgsdb[k]);
      }
      fd.append('description', description);

      $.ajax({
        url: '{{route('admin.product.update')}}',
        type: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: function(data) {
          swal.close();
          // console.log(data);
          var em = document.getElementsByClassName('em');
          for (var i = 0; i < em.length; i++) {
            em[i].innerHTML = '';
          }
          if (data=="success") {
            swal("Success!", "product has been updated successfully!", "success");
          }
          // if error occurs
          if(typeof data.error != 'undefined') {
            if (typeof data.title != 'undefined') {
              document.getElementById('errtitle').innerHTML = data.title[0];
            }
            if (typeof data.quantity != 'undefined') {
              document.getElementById('errquantity').innerHTML = data.quantity[0];
            }
            if (typeof data.price != 'undefined') {
              document.getElementById('errprice').innerHTML = data.price[0];
            }
            if (typeof data.cat_helper != 'undefined') {
              document.getElementById('errcat').innerHTML = data.cat_helper[0];
            }
            if (typeof data.subcat_helper != 'undefined') {
              document.getElementById('errsubcat').innerHTML = data.subcat_helper[0];
            }
            if (typeof data.description != 'undefined') {
              document.getElementById('errdesc').innerHTML = data.description[0];
            }
            if (typeof data.imgs_helper != 'undefined') {
              document.getElementById('errpreimg').innerHTML = data.imgs_helper[0];
            }
            if ("proattr" in data) {
              data.proattr[0]; // object
              for (var key in data.proattr[0]) {
                document.getElementById(`err${key}`).innerHTML = data.proattr[0][key];
              }
            }
          }
        }
      });
    }

 	function showsubcats(val) {
        // console.log(this.catid);
        $.ajax({
        	headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
		    },
        	url: '{{ route('admin.product.getsubcats') }}',
        	type: 'POST',
        	dataType: 'json',
        	data: {catid: val},
        })
        .done(function(data) {
        	// console.log("success");
        	var subopt = '<option value="" selected disabled>Select a subcategory</option>';
            for (var i = 0; i < data.length; i++) {
              subopt += `
                <option value="${data[i].id}">${data[i].name}</option>
              `;
            }
            $("#selsub").html(subopt);
        })
        .fail(function() {
        	console.log("error");
        })
        .always(function() {
        	console.log("complete");
        });
  	}

  	function showattrs(value){
  		$.get(
          '{{route('admin.product.getattributes')}}',
          {
            'subcatid': value
          },
          function(data) {
            console.log(data);
            if (data != 'no_attr') {
              this.iteratoroptions = data.iteratoroptions;
              this.options = data.options;
              this.productattrs = data.productattrs;
              console.log(this.iteratoroptions, this.options, this.productattrs);
              var txt = ``;
              var k = 0;
              for (var i = 0; i < this.iteratoroptions.length; i++) {
                  if ((i+1) % 3 == 1) {
                    txt += `<div class="row">`;
                  }
                        txt += `<div class="col-md-4">
                                     <div class="form-element margin-bottom-20">
                                          <label>${this.productattrs[i].name} <span>**</span></label>`;

                                  txt += `<div>`;
                                for (var j = 0; j < this.iteratoroptions[i]; j++) {
                                    txt += `<div class="form-check form-check-inline">
                                              <input name="${this.productattrs[i].attrname}[]" value="${this.options[k].name}" class="form-check-input" type="checkbox" id="attr${this.options[k].id}">
                                              <label class="form-check-label" for="inlineCheckbox1">${this.options[k].name}</label>
                                            </div>`;
                                    k++;
                                }
                            txt +=      `</div>
                                         <p class="em text-danger no-margin" id="err${this.productattrs[i].attrname}"></p>
                                     </div>
                                </div>`;
                  if ((i+1) % 3 == 0) {
                    txt += `</div>`;
                  }
              }
              $("#proattrsid").html(txt);
            } else {
              $("#proattrsid").html('');
            }

          }
        );
  	}

  </script>
@endsection
