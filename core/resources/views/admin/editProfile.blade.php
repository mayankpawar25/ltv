@extends('admin.layout.master')

@section('content')
  <main class="app-content">
      
     <div class="main-content">
     <h5>Edit Profile</h5>
     <hr />
     <div class="row">
        <div class="col-md-12">
           <div class="">
              <div class="">
                <div class="row">
                  <div class="col-md-12 ">
                    <form action="{{route('admin.updateProfile', $admin->id)}}" method="post">
                       {{csrf_field()}}
                       <input type="hidden" name="adminID" value="{{$admin->id}}">
                       <div class="">
                        <div class="row">
                        
                        <div class="col-md-4"> <div class="form-group">
                              <div class="">
                                <label > Full Nane </label>
                              </div>
                             <div class="">
                                <input class="form-control input-lg" name="name" value="{{$admin->first_name.' '.$admin->last_name}}" placeholder="Your Full Name" type="text">
                                @if ($errors->has('name'))
                                  <p style="margin:0px;" class="text-danger">{{$errors->first('name')}}</p>
                                @endif
                             </div>
                          </div></div>
                          <div class="col-md-4"><div class="form-group">
                            <div class="">
                             <label > Email </label>
                            </div>
                             <div class="">
                                <input class="form-control input-lg" name="email" value="{{$admin->email}}" placeholder="Your Email" type="email">
                                @if ($errors->has('email'))
                                  <p style="margin:0px;" class="text-danger">{{$errors->first('email')}}</p>
                                @endif
                             </div>
                          </div></div>
                          <div class="col-md-4"><div class="form-group">
                            <div class="">
                             <label > Mobile </label>
                            </div>
                             <div class="">
                                <input class="form-control input-lg" name="phone" value="{{$admin->phone}}" placeholder="Your Mobile Number" type="text">
                                @if ($errors->has('phone'))
                                  <p style="margin:0px;" class="text-danger">{{$errors->first('phone')}}</p>
                                @endif
                             </div>
                          </div></div>
                          
                          </div>
                          <div class="row">
                             <div class="col-md-12">
                               <div class="text-right">
                               <hr />
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
<div class="clearfix"></div>
     </div>
  </main>
@endsection
