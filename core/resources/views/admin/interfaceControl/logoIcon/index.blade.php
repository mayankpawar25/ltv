@extends('admin.layout.master')

@push('styles')
<style media="screen">
  h3 {
    margin: 0px;
  }
</style>
@endpush

@section('content')
    <main class="app-content">
      {{-- <div class="app-title">
        <div>
          <h1>Logo & Icon Setting</h1>
        </div>
      </div> --}}
      <div class="row">
        <div class="col-md-12">
          <div class="main-content">
            <h5>Logo & Icon Setting</h5><hr>
            <form action="{{route('admin.logoIcon.update')}}" method="post" enctype="multipart/form-data">{{csrf_field()}}
              <div class="row">
                @if ($errors->any())
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
                @endif
                
                {{-- <div class="col-md-3">
                  <div class="">
                    <div class="">
                      <h5 style="color:#2d2d2d"><i class="fa fa-cog"></i> Change Images</h5>
                    </div>
                    <div class="">
                      <form action="{{route('admin.logoIcon.update')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label class=""><strong style="text-transform: uppercase;">Header Logo</strong></label>
                              
                              <div class=""><input name="logo" type="file" id="logo" class="w-100"></div>
                              <p class=""><strong>[Upload 190 X 49 image for best quality]</strong></p>
                              <label class=""><strong style="text-transform: uppercase;">Footer Logo</strong></label>
                              <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                              </div>
                              <div class=""><input name="footer_logo" type="file" id="footerLogo" class="w-100"></div>
                              <p class=""><strong>[Upload 190 X 49 image for best quality]</strong></p>
                            </div>
                          </div>
                          <br>
                          <br>
                          <br>
                          <div class="col-md-12">
                            <div class="form-group">
                              <label class=""><strong style="text-transform: uppercase;">favicon</strong></label>
                              <div class=""><input name="icon" type="file" id="icon" class="w-100"></div>
                              <p class=""><strong>[Upload 25 X 25 image for best quality]</strong></p>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <hr>
                            <div class="form-group">
                              <div class=" text-right"> <button type="submit" class="btn btn-success">UPLOAD</button></div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div> --}}
                <div class="col-md-4">
                  <div class="">
                    <div class="">
                      <h5 style="color:#2d2d2d">favicon</h5><hr>
                    </div>
                    <div class="">
                      <img style="max-width:100%;" src="{{asset('assets/user/interfaceControl/logoIcon/icon.jpg')}}" alt="">
                    </div>
                    <div class="form-group">
                      <label class="">Change Images</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="icon" name="icon">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                      </div>
                      {{-- <div class=""><input name="icon" type="file" id="icon" class="w-100"></div> --}}
                      <p class="">[Upload 25 X 25 image for best quality]</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="">
                    <div class="">
                      <h5 style="color:#2d2d2d">Header Logo</h5><hr>
                    </div>
                    <div class="">
                      <img style="max-width:100%;" src="{{asset('assets/user/interfaceControl/logoIcon/logo.jpg')}}" alt="">
                    </div>
                    <div class="form-group">
                      <label class="">Change Image</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="logo" name="logo">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                      </div>
                      {{-- <div class=""><input name="logo" type="file" id="logo" class="w-100"></div> --}}
                      <p class="">[Upload 190 X 49 image for best quality]</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="">
                    <div class="">
                      <h5 style="color:#2d2d2d">Footer Logo</h5><hr>
                    </div>
                    <div class="">
                      <img style="max-width:100%;" src="{{asset('assets/user/interfaceControl/logoIcon/footer_logo.jpg')}}" alt="">
                    </div>
                    <div class="form-group">
                      <label class="">Change Image</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="footerLogo" name="footer_logo">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                      </div>
                      {{-- <div class=""><input name="footer_logo" type="file" id="footerLogo" class="w-100"></div> --}}
                      <p class="">[Upload 190 X 49 image for best quality]</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-12"><hr>
                  <div class="form-group">
                    <div class=" text-right"> <button type="submit" class="btn btn-success">UPLOAD</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
@endsection
