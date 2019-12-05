@extends('admin.layout.master')

@section('content')
  <main class="app-content">
      
     <div class="row">
        <div class="col-md-12">
           <div class="">
              <div class="main-content">
              <h5>Change Password</h5>
              
              <hr />
                <div class="row">
                  <div class="col-md-12">
                    <form action="{{route('admin.updatePassword')}}" method="post" role="form">
                       {{csrf_field()}}
                       <div class="">
                         <div class="row">
                         <div class="col-md-4">
                          <div class="form-group">
                             <label >Current Password</label>
                             <div class="">
                                <input class="form-control input-lg" name="old_password" placeholder="Your Current Password" type="password">
                                @if ($errors->has('old_password'))
                                <span style="color:red;">
                                    <strong>{{ $errors->first('old_password') }}</strong>
                                </span>
                                @else
                                @if ($errors->first('oldPassMatch'))
                                <span style="color:red;">
                                    <strong>{{"Old password doesn't match with the existing password!"}}</strong>
                                </span>
                                @endif
                                @endif
                             </div>
                          </div></div>
                           <div class="col-md-4">
                          <div class="form-group">
                             <label >New Password</label>
                             <div class="">
                                <input class="form-control input-lg" name="password" placeholder="New Password" type="password">
                                @if ($errors->has('password'))
                                <span style="color:red;">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                             </div>
                          </div></div>
                           <div class="col-md-4">
                          <div class="form-group">
                             <label >New Password Again</label>
                             <div class="">
                                <input class="form-control input-lg" name="password_confirmation" placeholder="New Password Again" type="password">
                             </div>
                          </div>
                          </div>
                          </div>
                          <div class="row">
                             <div class="col-md-12 text-right">
                             <hr />
                                <button type="submit" class="btn btn-success">Submit</button>
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
