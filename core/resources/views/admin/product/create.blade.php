@extends('admin.layout.master')

@section('title', 'Product Upload')

@section('headertxt', 'Product Upload')

@push('nicedit-scripts')
  <script src="{{asset('assets/nic-edit/nicEdit.js')}}" type="text/javascript"></script>
  <script type="text/javascript">
    bkLib.onDomLoaded(function() {
      new nicEditor({iconsPath : '{{asset('assets/nic-edit/nicEditorIcons.gif')}}', fullPanel : true}).panelInstance('desc');
    });
  </script>
@endpush

@push('styles')
  <!-- stylesheet -->
  <link rel="stylesheet" href="{{asset('assets/user/css/style.css')}}">
  <!-- responsive -->
  <link rel="stylesheet" href="{{asset('assets/user/css/responsive.css')}}">
  <style>
    table thead tr th , .card-header {background-color: #329688 !important;}
	.preimgs{max-height:180px; min-height:180px; max-width:180px; min-width:180px;}
	table tr td{
		border-top:none !important;
	}
	#imgtable td {

    padding: 0px 15px 0px 0px;

}
  </style>
@endpush

@section('content')
<!-- product upload area start -->
<main class="app-content">
     
    <div class="main-content">
    <h5>Create Product</h5>
    <hr />
    <div class="row ">
    			<div class="col-lg-12">
    				<div class="product-upload-inner"><!-- product upload inner -->
    					<form id="uploadForm" class="product-upload-form" onsubmit="upload(event)" enctype="multipart/form-data">
    						{{csrf_field()}}
    						
                            <div class="well">
                            <div class="form-element margin-bottom-20">

    							<label for="" class="sec-txt">Preview Images <span>*</span></label>
    							<div class="table-responsive">
    								<table class="table table-striped" id="imgtable">

    								</table>
    							</div>
    							<div class="form-group">
    								<label class="btn btn-success btn-sm" style="color:#fff;">
    									<input id="imgs" style="display:none;" type="file" />
    									<i class="fa fa-plus"></i> Add a photo
    								</label>
    								<p class="no-margin"><small>Maximum 5 images can be uploaded</small></p>
    								<p id="errpreimg" class="em no-margin text-danger"></p>
    							</div>

    						</div>
                            </div>

    						<div class="row">
    							<div class="col-md-6">
    								<div class="form-element margin-bottom-20">
    									<label>Title <span>*</span></label>
    									<input name="title" type="text"  class="form-control" placeholder="Enter title...">
    									<p id="errtitle" class="em no-margin text-danger"></p>
    								</div>
    							</div>
    							<div class="col-md-6">
    								<div class="row">
    									<div class="col-md-6">
    										<div class="form-element margin-bottom-20">
    											<label>Stock (quantity) <span>*</span></label>
    											<input name="quantity" type="text" class="form-control" placeholder="Enter quantity...">
    											<p id="errquantity" class="em no-margin text-danger"></p>
    										</div>
    									</div>
    									<div class="col-md-6">
    										<div class="form-element margin-bottom-20">
    											<label>Price ({{$gs->base_curr_text}}) <span>*</span></label>
    											<input name="price" type="text" class="form-control" placeholder="Enter price...">
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
    										<option value="" selected disabled>Select a category</option>
    										@foreach ($cats as $cat)
    										<option value="{{$cat->id}}">{{$cat->name}}</option>
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
    										<option value="" selected disabled>Select a subcategory</option>
    									</select>
    									<input type="hidden" name="subcat_helper" value="">
    									<p id="errsubcat" class="em no-margin text-danger"></p>
    								</div>
    							</div>
    							<div class="col-md-4">
    								<div class="form-element margin-bottom-20">
    									<label>Product Code <span>(Optional)</span></label>
    									<input name="product_code" type="text" class="form-control" placeholder="Enter product code...">
    									<small>If you do not specify an unique product code it will be generated automatically.</small>
    									<p id="errcode" class="em no-margin text-danger"></p>
    								</div>
    							</div>
    						</div>
    						<div id="proattrsid">

    						</div>

    						<div class="form-element margin-bottom-20">
    							<label>Description <span>*</span></label>
    							<textarea class="form-control" id="desc" rows="10"></textarea>
    							<p id="errdesc" class="em no-margin text-danger"></p>
    						</div>

                <br>
                <div class="row">
                  <div class="col-sm-12"><strong>User Group Price</strong>
                  <hr />
                  </div>
                  
                  @php $i=0 @endphp
                  @foreach($usergroups as $usergroup)
                  <div class="col-sm-4">
                    <div class="form-group">
                    <label>{{ $usergroup->name }} Price</label>
                    <input type="hidden" name="usergroups[{{$i}}][id]" value="{{$usergroup->id}}">
                    <input type="hidden" name="usergroups[{{$i}}][slug]" value="{{$usergroup->slug}}">
                    <input type="text" class="form-control" name="usergroups[{{$i}}][price]" value="" placeholder="">
                    </div>
                  </div>
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
														name="offer" onchange="changeOffer()">
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-element margin-bottom-20 d-none check-round" id="offerType">
														<label class="d-block">Offer Type <span>*</span></label>
														<input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
														data-width="100%" type="checkbox" data-on="Percentage" data-off="Fixed"
														name="offer_type" id="offerTypeToggle">
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-element margin-bottom-20 d-none" id="offerAmount">
														<label>Amount <span>*</span></label>
														<input name="offer_amount" type="text" class="form-control" placeholder="Enter offer amount...">
														<div id="calcTotal"></div>
														<p id="errofferamount" class="em no-margin text-danger"></p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
                                <div class="col-md-12">
									<div class="">
										<div class="">
											 
                                             <strong>Seclect Flash Sale (Fixed/Percentage)</strong>
                                             <hr />
										</div>
										<div class="">
											<div class="row">
												<div class="col-md-2">
													<div class="form-element margin-bottom-20 check-round">
														<label class="d-block">Flash Sale <span>*</span></label>
														<input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
														data-width="100%" type="checkbox"
														name="flash_sale">
													</div>
												</div>

												<div class="col-md-8 d-none" id="flashsale">
													<div class="row">
														<div class="col-md-3">
															<div class=" margin-bottom-20 check-round">
																<label class="d-block">Type <span>*</span></label>
																<input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
																data-width="100%" type="checkbox" data-on="Percentage" data-off="Fixed"
																name="flash_type">
															</div>
														</div>

														<div class="col-md-3">
															<div class=" margin-bottom-20">
																<label class="d-block">Amount <span>*</span></label>
																<div class="">
																	<input class="form-control" type="text" name="flash_amount" value="" autocomplete="off" placeholder="Enter flash amount">

																</div>
																<p id="errflashamount" class="em no-margin text-danger"></p>
																<div id="calcTotalFlash"></div>
															</div>
														</div>

														<div class="col-md-3">
															<div class="margin-bottom-20">
																<label class="d-block">Date <span>*</span></label>
																<div class="">
																	<input id="flash_date" class="form-control" type="text" name="flash_date" value="" placeholder="Enter flash date">
																</div>
																<p id="errflashdate" class="em no-margin text-danger"></p>
															</div>
														</div>

														<div class="col-md-3">
															<div class="form-element margin-bottom-20">
																<label class="d-block">Time interval <span>*</span></label>
																<div class="">
																	<select class="form-control" name="flash_interval">
																		@foreach ($flashints as $key => $flashint)
																		<option value="{{$flashint->id}}">{{$flashint->start_time . " - " . $flashint->end_time}}</option>
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
    							<input type="submit" class="btn btn-success" value="Upload Product">
    						</div>
    					</form>
    				</div><!-- //.product upload inner -->
    			</div>
    		</div>
            <div class="clearfix"></div>
    </div>
    
    
    <div class="product-upload-area" id="uploadDiv" style="padding:0px 0px;">
    	<div class="container">
    		
    	</div>
    </div>
</main>
<!-- product upload area end -->
@endsection

@section('js-scripts')
<script>
    function changeOffer() {
      let offer = $("input[name='offer']:checked").val();
      if (offer == 'on') {
        $("#offerType").removeClass("d-none");
        $("#offerAmount").removeClass("d-none");
        $("#offerType").addClass("d-block");
        $("#offerAmount").addClass("d-block");

        $('#offerTypeToggle').bootstrapToggle();
      } else {
        $("#offerType").removeClass("d-block");
        $("#offerAmount").removeClass("d-block");
        $("#offerType").addClass("d-none");
        $("#offerAmount").addClass("d-none");
      }
    }
	    
    $(document).ready(function() {
      	var curr = '{{$gs->base_curr_text}}';
      	$("input[name='offer_amount'], input[name='price'], input[name='offer_type']").on('input', function(event) {
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
      	});
      	$("input[name='offer_type']").on('change', function(event) {
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
	              $("#calcTotal").html('<strong>Price: </strong>'+price+'<br/><strong>Offer: </strong>'+offer+'<br/><strong>Total: </strong>' + total);
	            } else {
	              // if offer type is Fixed...
	              let total = price - offeramount;
	              $("#calcTotal").html('<strong>Price: </strong>'+price+'<br/><strong>Offer: </strong>'+offeramount+'<br/><strong>Total: </strong>' + total);
	            }
	            console.log(offeramount);
	          } else {
	            $("#calcTotal").html('Enter price first');
	            $("#calcTotal").addClass('text-danger')
	          }
	        }
      	});
  		$("input[name='flash_amount'], input[name='price']").on('input', function(event) {
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
      });
      $("input[name='flash_type']").on('change', function(event) {
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
              $("#calcTotalFlash").html('<strong>Price: </strong>'+price+'<br/><strong>Offer: </strong>'+offer+'<br/><strong>Total: </strong>' + total);
            } else {
              // if offer type is Fixed...
              let total = price - flashamount;
              $("#calcTotalFlash").html('<strong>Price: </strong>'+price+'<br/><strong>Offer: </strong>'+flashamount+'<br/><strong>Total: </strong>' + total);
            }
            console.log(flashamount);
          } else {
            $("#calcTotalFlash").html('Enter price first');
            $("#calcTotalFlash").addClass('text-danger')
          }
        }
      });
    });
	$(document).ready(function() {
	    /*$('#flash_date').datetimepicker({
	     format:'Y/m/d',
	     timepicker: false
	    });*/

	    $("input[name='flash_sale']").on('change', function () {
	      let flashsale = $("input[name='flash_sale']:checked").val();
	      if (flashsale == 'on') {
	        $("#flashsale").removeClass('d-none');
	        $("#flashsale").addClass('d-block');
	      } else {
	        $("#flashsale").removeClass('d-block');
	        $("#flashsale").addClass('d-none');
	      }
	    })
  	});

    var el = 0;
    var imgs = [];

    /*$(window).load(function(){
      $.get(
        '{{route('package.validitycheck')}}',
        function(data) {
          // console.log(data);

          if (data.products == 0) {
            $("#dangerAlert").addClass('d-block');
            $("#successAlert").addClass('d-none');
          } else if (data.products > 0) {
            $("#successAlert").addClass('d-block');
            $("#dangerAlert").addClass('d-none');
          }
        }
      );
    });*/

    $(document).on('change', '#imgs', function(e) {
        if (this.files.length && imgs.length < 5) {
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
      fd.append('description', description);
      $.ajax({
        url: '{{route('admin.product.store')}}',
        type: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: function(data) {
    	console.log('data showing here : '+data);
          swal.close();
          var em = document.getElementsByClassName('em');
          for (var i = 0; i < em.length; i++) {
            em[i].innerHTML = '';
          }

          if (data == "no_product") {
            swal("Alert!", "You have to buy a package to upload product!", "error");
          }
          if (data=="success") {

            window.location = "{{url()->current()}}";
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
            if (typeof data.images != 'undefined') {
              document.getElementById('errpreimg').innerHTML = data.images[0];
            }
            if (typeof data.offer_amount != 'undefined') {
              document.getElementById('errofferamount').innerHTML = data.offer_amount[0];
            }
            if (typeof data.flash_amount != 'undefined') {
              document.getElementById('errflashamount').innerHTML = data.flash_amount[0];
            }
            if (typeof data.flash_date != 'undefined') {
              document.getElementById('errflashdate').innerHTML = data.flash_date[0];
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