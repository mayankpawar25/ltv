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
           <h1>Support Informations</h1>
        </div>
     </div> --}}
     <div class="row">
        <div class="col-md-12">
          <div class="main-content">
          <h5>Support Informations</h5><hr>

            <div class="row">

              <div class="col-md-12">

                <form class="form-horizontal" action="{{route('admin.support.update')}}" method="post" role="form">
                   {{csrf_field()}}
                   <div class="form-body">
                    <div class="row">
                    <div class="col-md-6">
                     <div class="form-group">
                        <label class=" "><strong style="text-transform: uppercase;">Phone</strong></label>
                        <div class="">
                           <input class="form-control input-lg" name="support_phone" value="{{$gs->support_phone}}" type="text">
                           @if ($errors->has('support_phone'))
                             <p class="text-danger">{{$errors->first('support_phone')}}</p>
                           @endif
                        </div>
                     </div>
                     </div>

                     <div class="col-md-6">
                     <div class="form-group">
                        <label class=" "><strong style="text-transform: uppercase;">Email</strong></label>
                        <div class="">
                           <input class="form-control input-lg" name="support_email" value="{{$gs->support_email}}" type="text">
                           @if ($errors->has('support_email'))
                             <p class="text-danger">{{$errors->first('support_email')}}</p>
                           @endif
                        </div>
                     </div>
                     </div>
                     </div>
                     <hr>
                      <div class="form-group">
                         <div class="text-right">
                            <button type="submit" class="btn btn-success">UPDATE</button>
                         </div>
                      </div>
                   </div>
                </form>
              </div>

            </div>
          </div>
        </div>
     </div>
  </main>
@endsection
