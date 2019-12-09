@extends('layout.master')

@section('title', 'Home')

@section('navbar')
  @includeif('layout.partials.navbar')
@endsection

@section('headerarea')
  @includeif('layout.partials.headerarea')
@endsection

@section('content')
  <div id="home">
    <div class="body-overlay" id="body-overlay"></div>
    <div class="search-popup" id="search-popup">
        <form action="index.html" class="search-popup-form">
            <div class="form-element">
                    <input type="text"  class="input-field" placeholder="Search.....">
            </div>
            <button type="submit" class="submit-btn"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <!-- feature area home 6 start -->
    {{-- <div class="feature-area-home-6">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                   <div class="img-wrapper">
                        {!! show_ad(1) !!}
                   </div>
                </div>
                <div class="col-lg-4 col-md-6">
                   <div class="img-wrapper">
                        {!! show_ad(1) !!}
                   </div>
                </div>
                <div class="col-lg-4 col-md-6">
                   <div class="img-wrapper">
                        {!! show_ad(1) !!}
                   </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="container">
      <div class="free-shipping">
        <div class="row">
          <div class="col-lg-3 col-md-6 bdr-right">
            <img src="assets/img/free-shipping.png">
            <h4>Free shipping</h4>
            <p>Free shipping on all paid order</p>
          </div>
          <div class="col-lg-3 col-md-6 bdr-right">
            <img src="assets/img/support.png">
            <h4>24/7 Support</h4>
            <p>Contact us 24 hours a day</p>
          </div>
          <div class="col-lg-3 col-md-6 bdr-right">
            <img src="assets/img/money-back.png">
            <h4>100% Money Back</h4>
            <p>We have 7 day to Return</p>
          </div>
          <div class="col-lg-3 col-md-6">
            <img src="assets/img/payment-secure.png">
            <h4>Payment secure</h4>
            <p>We are ensure secure payment</p>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="big-sale d-none d-lg-block">
        <div class="row">
          <div class="col-lg-3">
            <div class="img-box">
                <img src="assets/img/sale-img1.jpg" class="w-100">
            </div>
          </div>
          <div class="col-lg-6 col-md-12">
            <div class="img-box">
                <img src="assets/img/sale-img2.jpg" class="w-100">
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="img-box">
                <img src="assets/img/sale-img3.jpg" class="w-100">
            </div>
          </div>
        </div>
      </div>

      <div class="big-sale d-block d-lg-none">
        <div class="row">
          
          <div class="col-lg-6 col-md-12">
            <div class="img-box">
                <img src="assets/img/sale-img2.jpg" class="w-100">
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="img-box">
                <img src="assets/img/sale-img1.jpg" class="w-100">
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="img-box">
                <img src="assets/img/sale-img3.jpg" class="w-100">
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- feature area home 6 end -->

    <div id="flashsaleContainer">

      @if (count($flashsales) > 0)
        <div class="trending-seller-area home-6" id="flashSale">
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header base-bg">
                    <h3 class="text-white">
                      Flash Sales 
                      <div class="countdown">ur" class="time-blo
                        <span id="hock base-txt"></span><span>:</span>
                        <span id="mins" class="time-block base-txt"></span><span>:</span>
                        <span id="seconds" class="time-block base-txt"></span>
                      </div>
                    </h3>
                  </div>
                  <div class="card-body">
                    <!-- recently added start -->
                    <div class="recently-added-area home-6" style="padding: 0px;">
                            <div class="row">
                                <div class="col-lg-12">

                                    {{-- already added flash sales --}}
                                    <div class="recently-added-carousel flash-sale-carousel"><!-- recently added carousel -->
                                      {{dd($flashsale)}}
                                      @foreach ($flashsales as $key => $flashsale)
                                        <div class="single-new-collection-item"><!-- single new collections -->
                                            <div class="thumb">
                                              <img src="{{asset('assets/user/img/products/'.$flashsale->previewimages()->first()->image)}}" alt="new collcetion image">
                                              <div class="hover">
                                                <a href="{{route('user.product.details', [$flashsale->slug,$flashsale->id])}}" class="view-btn"><i class="fa fa-eye"></i></a>
                                              </div>
                                              <span class="discount-badge"> -{{$flashsale->flash_type == 0 ? "$gs->base_curr_symbol" : ''}} {{$flashsale->flash_amount}}{{$flashsale->flash_type == 1 ? '%' : ''}}</span>
                                            </div>
                                            <div class="content">
                                                <span class="category">{{$flashsale->category->name}}</span>
                                                <a href="{{route('user.product.details', [$flashsale->slug,$flashsale->id])}}"><h4 class="title">{{strlen($flashsale->title) > 20 ? substr($flashsale->title, 0, 20) . '...' : $flashsale->title}}</h4></a>
                                                @if (empty($flashsale->current_price))
                                                  <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$flashsale->price}}</span></div>
                                                @elseif (!empty($flashsale->current_price))
                                                  <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$flashsale->current_price}}</span> <del class="dprice">{{$gs->base_curr_symbol}} {{$flashsale->price}}</del></div>
                                                @endif
                                            </div>
                                        </div><!-- //. single new collections  -->
                                      @endforeach

                                </div>
                            </div>
                    </div>
                    <!-- recently added end -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif
    </div>


    <div class="trending-seller-area home-6">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 ">
                    <div class="home-six-trending-seller-filter"><!-- home six trending seller filter -->
                       <div class="row">
                           <div class="col-lg-12">
                             <div class="card">
                               <div class="card-header base-bg">
                                 <div class="home-six-trending-sellter-filter-nav">
                                  <div class="pro-title">
                                    <span>HOT DEALS</span>
                                  </div>
                                         <ul class="nav nav-tabs"  role="tablist">
                                             <li class="nav-item">
                                                 <a class="nav-link active" id="bestseller-tab" data-toggle="tab" href="#bestseller" role="tab" aria-controls="bestseller" aria-selected="true">Top Sales</a>
                                             </li>
                                             <li class="nav-item">
                                                 <a class="nav-link" id="trendeseller-tab" data-toggle="tab" href="#trendeseller" role="tab" aria-controls="trendeseller" aria-selected="false">Top Rated</a>
                                             </li>
                                             <li class="nav-item">
                                                 <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Special</a>
                                             </li>
                                         </ul>
                                 </div>
                               </div>
                               <div class="card-body">
                                 <div class="home-six-trending-masonry">
                                         <div class="tab-content" >
                                             <div class="tab-pane fade show active" id="bestseller" role="tabpanel" aria-labelledby="bestseller-tab">
                                                 <div class="row mx-0">
                                                   @foreach ($topSoldPros as $key => $topSoldPro)
                                                     <div class="col-lg-3 col-md-6 px-2">
                                                       <div class="single-new-collection-item "><!-- single new collections -->
                                                           
                                                           <div class="thumb">
                                                            @if($topSoldPro->previewimages())
                                                               <img src="{{asset('assets/user/img/products/'.$topSoldPro->previewimages()->first()->image)}}" alt="new collcetion image">
                                                            @endif
                                                               <div class="hover">
                                                                <a href="{{route('user.product.details', [$topSoldPro->slug, $topSoldPro->id])}}" class="view-btn"><i class="fa fa-eye"></i></a>
                                                               </div>
                                                           </div>
                                                               <div class="content">
                                                               {{-- <span class="category">{{\App\Category::find($topSoldPro->category_id)->name}}</span> --}}
                                                               <a href="{{route('user.product.details', [$topSoldPro->slug, $topSoldPro->id])}}"><h4 class="title">{{strlen($topSoldPro->title) > 20 ? substr($topSoldPro->title, 0, 20) . '...' : $topSoldPro->title}}</h4></a>
                                                               @if (empty($topSoldPro->current_price))
                                                                 <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$topSoldPro->price}}</span></div>
                                                               @else
                                                                 <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$topSoldPro->current_price}}</span> <del class="dprice">{{$gs->base_curr_symbol}} {{$topSoldPro->price}}</del></div>
                                                               @endif
                                                           </div>
                                                           
                                                       </div><!-- //. single new collections  -->
                                                     </div>
                                                   @endforeach
                                                   <div class="col-12 text-center">
                                                     <a class="view-all-products" href="{{url('/shop').'?sort_by=sales_desc'}}">View All <i class="fa fa-angle-right"></i></a>
                                                   </div>
                                                 </div>
                                             </div>
                                             <div class="tab-pane fade" id="trendeseller" role="tabpanel" aria-labelledby="trendeseller-tab">
                                               <div class="row mx-0">
                                                 @foreach ($topRatedPros as $key => $topRatedPro)
                                                   <div class="col-lg-3 col-md-6 px-2">
                                                     <div class="single-new-collection-item "><!-- single new collections -->
                                                         <div class="thumb">
                                                          @if($topRatedPro->previewimages())
                                                             <img src="{{asset('assets/user/img/products/'.$topRatedPro->previewimages()->first()->image)}}" alt="new collcetion image">
                                                          @endif
                                                             <div class="hover">
                                                               <a href="{{route('user.product.details', [$topRatedPro->slug, $topRatedPro->id])}}" class="view-btn"><i class="fa fa-eye"></i></a>
                                                             </div>
                                                         <div class="content">
                                                             {{-- <span class="category">{{\App\Category::find($topRatedPro->category_id)->name}}</span> --}}
                                                             <a href="{{route('user.product.details', [$topRatedPro->slug, $topRatedPro->id])}}"><h4 class="title">{{strlen($topRatedPro->title) > 20 ? substr($topRatedPro->title, 0, 20) . '...' : $topRatedPro->title}}</h4></a>
                                                             @if (empty($topRatedPro->current_price))
                                                               <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$topRatedPro->price}}</span></div>
                                                             @else
                                                               <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$topRatedPro->current_price}}</span> <del class="dprice">{{$gs->base_curr_symbol}} {{$topRatedPro->price}}</del></div>
                                                             @endif
                                                         </div>
                                                         </div>
                                                     </div><!-- //. single new collections  -->
                                                   </div>
                                                 @endforeach
                                                 <div class="col-12 text-center">
                                                   <a class="view-all-products" href="{{url('/shop').'?sort_by=rate_desc'}}">View All <i class="fa fa-angle-right"></i></a>
                                                 </div>
                                               </div>
                                             </div>
                                             <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                               <div class="row mx-0">
                                                 @foreach ($specialPros as $key => $specialPro)
                                                   <div class="col-lg-3 col-md-6 px-2">
                                                     <div class="single-new-collection-item "><!-- single new collections -->
                                                         <div class="thumb">
                                                             <img src="{{asset('assets/user/img/products/'.$specialPro->previewimages()->first()->image)}}" alt="new collcetion image">
                                                             <div class="hover">
                                                               <a href="{{route('user.product.details', [$specialPro->slug, $specialPro->id])}}" class="view-btn"><i class="fa fa-eye"></i></a>
                                                             </div>
                                                         <div class="content">
                                                             {{-- <span class="category">{{\App\Category::find($specialPro->category_id)->name}}</span> --}}
                                                             <a href="{{route('user.product.details', [$specialPro->slug, $specialPro->id])}}"><h4 class="title">{{strlen($specialPro->title) > 20 ? substr($specialPro->title, 0, 20) . '...' : $specialPro->title}}</h4></a>
                                                             @if (empty($specialPro->current_price))
                                                               <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$specialPro->price}}</span></div>
                                                             @else
                                                               <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$specialPro->current_price}}</span> <del class="dprice">{{$gs->base_curr_symbol}} {{$specialPro->price}}</del></div>
                                                             @endif
                                                         </div>
                                                         </div>
                                                     </div><!-- //. single new collections  -->
                                                   </div>
                                                 @endforeach
                                                 <div class="col-12 text-center">
                                                   <a class="view-all-products" href="{{url('/shop').'?type=special'}}">View All <i class="fa fa-angle-right"></i></a>
                                                 </div>
                                               </div>
                                             </div>
                                         </div>
                                 </div>
                               </div>
                             </div>
                        </div><!-- //.home six trending seller filter -->
                           </div>
                       </div>
                </div>
            </div>
        </div>
    </div>


    @foreach ($categories as $key => $cat)

      @if ($cat->products()->where('deleted', 0)->count() > 0)
        <!-- recently added start -->
        <div class="recently-added-area home-6">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="recently-added-nav-menu"><!-- recently added nav menu -->
                            <ul>
                              <li>{{$cat->name}}</li>
                            </ul>
                        </div><!-- //.recently added nav menu -->
                    </div>
                    <div class="col-lg-3 sub-mb">
                      <ul class="home-subcategories">
                        @foreach ($cat->subcategories()->where('status', 1)->get() as $key => $subcategory)
                          <li><a href="{{route('user.search', [$cat->id, $subcategory->id])}}">{{$subcategory->name}}</a></li>
                        @endforeach

                      </ul>
                    </div>
                    <div class="col-lg-9">
                        <div class="recently-added-carousel cat-carousel" id="recently-added-carousel"><!-- recently added carousel -->
                          @foreach ($cat->products()->where('deleted', 0)->orderBy('id', 'DESC')->limit(8)->get() as $key => $product)
                            <div class="single-new-collection-item "><!-- single new collections -->
                                <div class="thumb">
                                  @if(!$product->previewimages->isEmpty())
                                    <img src="{{asset('assets/user/img/products/'.$product->previewimages()->first()->image)}}" alt="new collcetion image">
                                  @endif
                                    <div class="hover">
                                      <a href="{{route('user.product.details', [$product->slug, $product->id])}}" class="view-btn"><i class="fa fa-eye"></i></a>
                                    </div>
                                <div class="content">
                                    {{-- <span class="category">{{\App\Category::find($product->category_id)->name}}</span> --}}
                                    <a href="{{route('user.product.details', [$product->slug, $product->id])}}"><h4 class="title">{{strlen($product->title) > 20 ? substr($product->title, 0, 20) . '...' : $product->title}}</h4></a>
                                    @if (empty($product->current_price))
                                      <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$product->price}}</span></div>
                                    @else
                                      <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$product->current_price}}</span> <del class="dprice">{{$gs->base_curr_symbol}} {{$product->price}}</del></div>
                                    @endif
                                </div>
                                </div>
                            </div><!-- //. single new collections  -->
                          @endforeach
                          <div class="single-new-collection-item ">
                            <div class="view-all-wrapper">
                              <div class="view-all-inner">
                                <a class="view-all-icon-wrapper" href="{{route('user.search', $cat->id)}}">
                                  <i class="fa fa-angle-right"></i>
                                </a>
                                <a class="d-block view-all-txt" href="{{route('user.search', $cat->id)}}">View All</a>
                              </div>
                            </div>
                          </div>
                        </div><!-- //. recently added carousel -->
                    </div>
                </div>
            </div>
        </div>
        <!-- recently added end -->
      @endif

  </div>

  @endforeach

  <!-- recently added start -->
  <div class="recently-added-area home-6">
      <div class="container">
          <div class="row">
              <div class="col-lg-12">
                  <div class="recently-added-nav-menu"><!-- recently added nav menu -->
                      <ul>
                          <li>Recently Added</li>
                      </ul>
                  </div><!-- //.recently added nav menu -->
              </div>
              <div class="col-lg-12">
                  <div class="recently-added-carousel" id="recently-added-carousel"><!-- recently added carousel -->
                    @foreach ($latestPros as $key => $latestPro)
                      <div class="single-new-collection-item "><!-- single new collections -->
                        <div class="row">
                          <div class="col-sm-12">
                          <div class="thumb">
                            @if(!$latestPro->previewimages->isEmpty())
                              <img src="{{asset('assets/user/img/products/'.$latestPro->previewimages()->first()->image)}}" alt="new collcetion image">
                            @endif
                              <div class="hover">
                                <a href="{{route('user.product.details', [$latestPro->slug,$latestPro->id])}}" class="view-btn"><i class="fa fa-eye"></i></a>
                              </div>
                              <div class="content">
                             {{--  <span class="category">{{\App\Category::find($latestPro->category_id)->name}}</span> --}}
                              <a href="{{route('user.product.details', [$latestPro->slug,$latestPro->id])}}"><h4 class="title">{{strlen($latestPro->title) > 20 ? substr($latestPro->title, 0, 20) . '...' : $latestPro->title}}</h4></a>
                              @if (empty($latestPro->current_price))
                                <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$latestPro->price}}</span></div>
                              @else
                                <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$latestPro->current_price}}</span> <del class="dprice">{{$gs->base_curr_symbol}} {{$latestPro->price}}</del></div>
                              @endif
                          </div>
                          </div>
                          
                           </div>
                          </div>
                      </div><!-- //. single new collections  -->
                    @endforeach
                    <div class="single-new-collection-item">
                      <div class="view-all-wrapper wrapper-height">
                        <div class="view-all-inner">
                          <a class="view-all-icon-wrapper" href="{{url('/').'/shop?sort_by=date_desc'}}">
                            <i class="fa fa-angle-right"></i>
                          </a>
                          <a class="d-block view-all-txt" href="{{url('/').'/shop?sort_by=date_desc'}}">View All</a>
                        </div>
                      </div>
                    </div>
                  </div><!-- //. recently added carousel -->
              </div>
          </div>
      </div>
  </div>
  <!-- recently added end -->

          <div class="trending-seller-area home-6">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 ">
                    <div class="home-six-trending-seller-filter"><!-- home six trending seller filter -->
                       <div class="row">
                           <div class="col-lg-12">
                             <div class="card">
                               <div class="card-header base-bg">
                                 <div class="home-six-trending-sellter-filter-nav">
                                  <div class="pro-title">
                                    <span>SPECIALS</span>
                                  </div>
                                         <ul class="nav nav-tabs"  role="tablist">
                                             <li class="nav-item">
                                                 <a class="nav-link active" id="bestseller-tab" data-toggle="tab" href="#bestseller" role="tab" aria-controls="bestseller" aria-selected="true">Top Sales</a>
                                             </li>
                                             <li class="nav-item">
                                                 <a class="nav-link" id="trendeseller-tab" data-toggle="tab" href="#trendeseller" role="tab" aria-controls="trendeseller" aria-selected="false">Top Rated</a>
                                             </li>
                                             <li class="nav-item">
                                                 <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Special</a>
                                             </li>
                                         </ul>
                                 </div>
                               </div>
                               <div class="card-body">
                                 <div class="home-six-trending-masonry">
                                         <div class="tab-content" >
                                             <div class="tab-pane fade show active" id="bestseller" role="tabpanel" aria-labelledby="bestseller-tab">
                                                 <div class="row mx-0">
                                                   @foreach ($topSoldPros as $key => $topSoldPro)
                                                     <div class="col-lg-3 col-md-6 px-2">
                                                       <div class="single-new-collection-item "><!-- single new collections -->
                                                           <div class="thumb">
                                                              @if(!$topSoldPro->previewimages->isEmpty())
                                                               <img src="{{asset('assets/user/img/products/'.$topSoldPro->previewimages()->first()->image)}}" alt="new collcetion image">
                                                               @endif
                                                               <div class="hover">
                                                                <a href="{{route('user.product.details', [$topSoldPro->slug, $topSoldPro->id])}}" class="view-btn"><i class="fa fa-eye"></i></a>
                                                               </div>
                                                           </div>
                                                               <div class="content">
                                                               {{-- <span class="category">{{\App\Category::find($topSoldPro->category_id)->name}}</span> --}}
                                                               <a href="{{route('user.product.details', [$topSoldPro->slug, $topSoldPro->id])}}"><h4 class="title">{{strlen($topSoldPro->title) > 20 ? substr($topSoldPro->title, 0, 20) . '...' : $topSoldPro->title}}</h4></a>
                                                               @if (empty($topSoldPro->current_price))
                                                                 <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$topSoldPro->price}}</span></div>
                                                               @else
                                                                 <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$topSoldPro->current_price}}</span> <del class="dprice">{{$gs->base_curr_symbol}} {{$topSoldPro->price}}</del></div>
                                                               @endif
                                                           </div>
                                                           
                                                       </div><!-- //. single new collections  -->
                                                     </div>
                                                   @endforeach
                                                   <div class="col-12 text-center">
                                                     <a class="view-all-products" href="{{url('/shop').'?sort_by=sales_desc'}}">View All <i class="fa fa-angle-right"></i></a>
                                                   </div>
                                                 </div>
                                             </div>
                                             <div class="tab-pane fade" id="trendeseller" role="tabpanel" aria-labelledby="trendeseller-tab">
                                               <div class="row mx-0">
                                                 @foreach ($topRatedPros as $key => $topRatedPro)
                                                   <div class="col-lg-3 col-md-6 px-2">
                                                     <div class="single-new-collection-item "><!-- single new collections -->
                                                         <div class="thumb">
                                                             <img src="{{asset('assets/user/img/products/'.$topRatedPro->previewimages()->first()->image)}}" alt="new collcetion image">
                                                             <div class="hover">
                                                               <a href="{{route('user.product.details', [$topRatedPro->slug, $topRatedPro->id])}}" class="view-btn"><i class="fa fa-eye"></i></a>
                                                             </div>
                                                         <div class="content">
                                                             {{-- <span class="category">{{\App\Category::find($topRatedPro->category_id)->name}}</span> --}}
                                                             <a href="{{route('user.product.details', [$topRatedPro->slug, $topRatedPro->id])}}"><h4 class="title">{{strlen($topRatedPro->title) > 20 ? substr($topRatedPro->title, 0, 20) . '...' : $topRatedPro->title}}</h4></a>
                                                             @if (empty($topRatedPro->current_price))
                                                               <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$topRatedPro->price}}</span></div>
                                                             @else
                                                               <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$topRatedPro->current_price}}</span> <del class="dprice">{{$gs->base_curr_symbol}} {{$topRatedPro->price}}</del></div>
                                                             @endif
                                                         </div>
                                                         </div>
                                                     </div><!-- //. single new collections  -->
                                                   </div>
                                                 @endforeach
                                                 <div class="col-12 text-center">
                                                   <a class="view-all-products" href="{{url('/shop').'?sort_by=rate_desc'}}">View All <i class="fa fa-angle-right"></i></a>
                                                 </div>
                                               </div>
                                             </div>
                                             <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                               <div class="row mx-0">
                                                 @foreach ($specialPros as $key => $specialPro)
                                                   <div class="col-lg-3 col-md-6 px-2">
                                                     <div class="single-new-collection-item "><!-- single new collections -->
                                                         <div class="thumb">
                                                             <img src="{{asset('assets/user/img/products/'.$specialPro->previewimages()->first()->image)}}" alt="new collcetion image">
                                                             <div class="hover">
                                                               <a href="{{route('user.product.details', [$specialPro->slug, $specialPro->id])}}" class="view-btn"><i class="fa fa-eye"></i></a>
                                                             </div>
                                                         <div class="content">
                                                             {{-- <span class="category">{{\App\Category::find($specialPro->category_id)->name}}</span> --}}
                                                             <a href="{{route('user.product.details', [$specialPro->slug, $specialPro->id])}}"><h4 class="title">{{strlen($specialPro->title) > 20 ? substr($specialPro->title, 0, 20) . '...' : $specialPro->title}}</h4></a>
                                                             @if (empty($specialPro->current_price))
                                                               <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$specialPro->price}}</span></div>
                                                             @else
                                                               <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$specialPro->current_price}}</span> <del class="dprice">{{$gs->base_curr_symbol}} {{$specialPro->price}}</del></div>
                                                             @endif
                                                         </div>
                                                         </div>
                                                     </div><!-- //. single new collections  -->
                                                   </div>
                                                 @endforeach
                                                 <div class="col-12 text-center">
                                                   <a class="view-all-products" href="{{url('/shop').'?type=special'}}">View All <i class="fa fa-angle-right"></i></a>
                                                 </div>
                                               </div>
                                             </div>
                                         </div>
                                 </div>
                               </div>
                             </div>
                        </div><!-- //.home six trending seller filter -->
                           </div>
                       </div>
                </div>
            </div>
        </div>
    </div>
    {{-- trending-seller-area home-6 --}}


  <!-- brand logo carousel area one start -->
  {{-- <div class="brand-logo-carousel-area-one">
      <div class="container-fluid">
          <div class="row">
              <div class="col-lg-12">
                  <div class="brand-logo-carousel-one" id="brand-logo-carousel-one"><!-- brand logo carousel one -->
                    @foreach ($partners as $key => $partner)
                      <div class="single-brang-logo-carousel-one-item">
                          <a href="{{$partner->url}}">
                              <img src="{{asset('assets/user/interfaceControl/partners/'.$partner->image)}}" alt="brand logo image">
                          </a>
                      </div>
                    @endforeach
                  </div><!-- //.brand logo carousel one -->
              </div>
          </div>
      </div>
  </div> --}}
  <!-- brand logo carousel area one end -->

@endsection


  @section('js-scripts')

    <script>
      var home = new Vue({
        el: '#home',
        data: {

        },
        mounted() {
          this.countdown();
          this.flashsalecheck();
        },
        methods: {
          countdown() {
            // Set the date we're counting down to
            var countDownDate = new Date("{{!empty($countto) ? $countto : ''}}").getTime();

            // Update the count down every 1 second
            var x = setInterval(function() {

              // Get todays date and time
              var now = new Date().getTime();

              // Find the distance between now and the count down date
              var distance = countDownDate - now;

              // Time calculations for hours, minutes and seconds
              var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
              var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
              var seconds = Math.floor((distance % (1000 * 60)) / 1000);

              // Output the result in an element with id="demo"
              $("#hour").html(hours < 10 ? '0'+hours : hours);
              $("#mins").html(minutes < 10 ? '0'+minutes : minutes);
              $("#seconds").html(seconds < 10 ? '0'+seconds : seconds);

              // If the count down is over, write some text
              if (distance < 0) {
                clearInterval(x);
              }
            }, 1000);
          },


          flashsalecheck() {
            setInterval(function() {
              $.get("{{route('flashsalecheck')}}", (data) => {
                // console.log(data);
                if (data.status == 1) {
                  window.location = '{{url()->current()}}';
                }
              });
            }, 5000);

          }
        }
      });
    </script>
  @endsection
