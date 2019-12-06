@extends('layout.profilemaster')

@section('title', 'Wishlist')

@section('headertxt', 'Wishlist')

@section('content')

<<<<<<< HEAD
  <div class="row px-3">
=======
  <div class="row px-2 margin-r-l">
>>>>>>> 84d281abc192c685ff44c6aaab198a31281068fe
    @if ($user->products()->count() == 0)
      <h2 style="font-size: 24px;display: block;margin: 0 auto;">No item added to favorit list yet.</h2>
    @else
        @foreach (\App\Favorit::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get() as $favorit)
          @php
            $product = \App\Product::find($favorit->product_id);
          @endphp
<<<<<<< HEAD
          <div class="col-lg-4 col-md-6 px-0">
=======
          <div class="col-lg-4 col-md-6 px-2">
>>>>>>> 84d281abc192c685ff44c6aaab198a31281068fe
              <div class="single-new-collection-item "><!-- single new collections -->
                  <div class="thumb">
                      <img src="{{asset('assets/user/img/products/'.$product->previewimages()->first()->image)}}" alt="new collcetion image">
                      <div class="hover">
                          <a href="{{route('user.product.details', [$product->slug, $product->id])}}" class="view-btn"><i class="fa fa-eye"></i></a>
                      </div>
                  </div>
                  <div class="content">
                      {{-- <span class="category">{{\App\Category::find($product->category_id)->name}}</span> --}}
                      <a href="{{route('user.product.details', [$product->slug, $product->id])}}"><h4 class="title">{{strlen($product->title) > 25 ? substr($product->title, 0, 25) . '...' : $product->title}}</h4></a>
                      @if (empty($product->current_price))
                        <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$product->price}}</span></div>
                      @else
                        <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$product->current_price}}</span> <del class="dprice">{{$gs->base_curr_symbol}} {{$product->price}}</del></div>
                      @endif
                  </div>
              </div><!-- //. single new collections  -->
          </div>
        @endforeach
    @endif

  </div>
@endsection
