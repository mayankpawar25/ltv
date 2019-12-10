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
    <div class="main-content">
       <h5>Login Page Text</h5><hr>
     <div class="row">
        <div class="col-md-12">

          <div class="">
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
                <form class="" action="{{route('admin.contact.update')}}" method="post" role="form">
                   {{csrf_field()}}
                   <div class="form-body">
                    <div class="row">
                    <div class="col-md-3">
                     <div class="form-group">
                        <label class="">Phone</label>
                        <div class="">
                           <input class="form-control input-lg" name="con_phone" value="{{$gs->con_phone}}" type="text">
                           @if ($errors->has('con_phone'))
                             <p class="text-danger">{{$errors->first('con_phone')}}</p>
                           @endif
                        </div>
                     </div>
                    </div>

                    <div class="col-md-3">
                     <div class="form-group">
                        <label class="">Email</label>
                        <div class="">
                           <input class="form-control input-lg" name="con_email" value="{{$gs->con_email}}" type="text">
                           @if ($errors->has('con_email'))
                             <p class="text-danger">{{$errors->first('con_email')}}</p>
                           @endif
                        </div>
                     </div>
                    </div>

                    <div class="col-md-3">
                     <div class="form-group">
                        <label class="">Address</label>
                        <div class="">
                           <input class="form-control input-lg" name="con_address" value="{{$gs->con_address}}" type="text">
                           @if ($errors->has('con_address'))
                             <p class="text-danger">{{$errors->first('con_address')}}</p>
                           @endif
                        </div>
                     </div>
                    </div>
                    <div class="col-md-3">
                     <div class="form-group">
                        <label class="">Working Time</label>
                        <div class="">
                           <input class="form-control input-lg" name="work_hours" value="{{$gs->work_hours}}" type="text">
                           @if ($errors->has('work_hours'))
                             <p class="text-danger">{{$errors->first('work_hours')}}</p>
                           @endif
                        </div>
                     </div>
                     </div>
                      <div class="col-md-12 text-right">
                        <hr>
                         <div class="text-right">
                            <button type="submit" class="btn btn-success">UPDATE</button>
                         </div>
                      </div>
                    </div>
                   </div>
                </form>
              </div>

            </div>
          </div>
        </div>
     </div>
   </div>
  </main>
@endsection
