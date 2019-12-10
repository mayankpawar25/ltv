@extends('admin.layout.master')

@push('styles')
<style media="screen">
  h3, h5 {
    margin: 0px;
  }
  .testimonial img {
    width: 100%;
  }
</style>
@endpush

@section('content')
  <main class="app-content">
      {{-- <div class="app-title">
        <div>
          <h1>Partner Setting</h1>
        </div>
      </div> --}}
      <div class="row">
        <div class="col-md-12">
          <div class="main-content">
            <h5>Partner Setting</h5><hr>
            <div class="row">
              <div class="col-md-12">
                @if ($errors->any())
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
                @endif
                <form action="{{route('admin.partner.store')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <div class="form-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Partner Image</label>
                          <div class="custom-file">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                            <input type="file" class="form-control" id="customFile">
                          </div>
                          {{-- <div><input type="file" name="partner" class="form-control"></div> --}}
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">URL</label>
                          <div><input type="text" name="url" class="form-control input-lg"></div>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-success">ADD NEW</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h5 style="color:#212529;display:inline-block;">Partners</h5>
                  </div>
                  <div class="card-body">
                    @if (count($partners) == 0)
                    <div class="text-center">
                      <img src="{{asset('assets/admin/images/no-data.jpg')}}" />
                    </div>
                  </div>
                  <h3 class="text-center"> NO PARTNER FOUND</h3>
                  @else
                  <div class="row"> {{-- .row start --}}
                    @foreach ($partners as $partner)
                    <div class="col-md-3">
                      <div class="card testimonial">
                        <div class="card-header bg-primary">
                          <h5 style="color:white">Partner</h5>
                        </div>
                        <div class="card-body text-center">
                          <img src="{{asset('assets/user/interfaceControl/partners/'.$partner->image)}}" alt="">
                          <h3 style="margin-top:20px;">URL: {{$partner->url}}</h3>
                        </div>
                        <div class="card-footer text-center">
                          <form action="{{route('admin.partner.delete')}}" method="POST">
                            {{csrf_field()}}
                            <input type="hidden" name="partnerID" value="{{$partner->id}}">
                            <button style="color:white;" type="submit" class="btn btn-danger btn-block" name="button">
                            <i class="fa fa-trash"></i>
                            Delete
                            </button>
                          </form>
                        </div>
                      </div>
                    </div>
                    @endforeach
                  </div> {{-- .row end --}}
                  <br>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
