@extends('admin.layout.master')

@push('nicedit-scripts')
  <script src="{{asset('assets/nic-edit/nicEdit.js')}}" type="text/javascript"></script>
  <script type="text/javascript">
    bkLib.onDomLoaded(function() {
      new nicEditor({iconsPath : '{{asset('assets/nic-edit/nicEditorIcons.gif')}}', fullPanel : true}).panelInstance('user');
    });
  </script>
  <script type="text/javascript">
    bkLib.onDomLoaded(function() {
      new nicEditor({iconsPath : '{{asset('assets/nic-edit/nicEditorIcons.gif')}}', fullPanel : true}).panelInstance('vendor');
    });
  </script>
@endpush

@section('content')
  <main class="app-content">
     {{-- <div class="app-title">
        <div>
           <h1>Login Page Text</h1>
        </div>
     </div> --}}
     <div class="row">
        <div class="col-md-12">
           <div class="main-content">
                 <h5>Login Text</h5><hr>
                 <form role="form" method="POST" action="{{route('admin.logintext.update')}}" enctype="multipart/form-data">
                    <div class="form-body">
                       {{csrf_field()}}
                       <div class="form-group">
                          <label><strong>User Login Text</strong></label>
                          <textarea class="form-control" name="user_login_text" id="user" rows="10">{{$gs->user_login_text}}</textarea>
                          @if ($errors->has('user_login_text'))
                            <span style="color:red;">{{$errors->first('user_login_text')}}</span>
                          @endif
                       </div>
                       <div class="form-group">
                          <label><strong>Vendor Login Text</strong></label>
                          <textarea class="form-control" name="vendor_login_text" id="vendor" rows="10">{{$gs->vendor_login_text}}</textarea>
                          @if ($errors->has('vendor_login_text'))
                            <span style="color:red;">{{$errors->first('vendor_login_text')}}</span>
                          @endif
                       </div>
                    </div>
                    <hr>
                    <div class="form-actions text-right">
                       <button type="submit" class="btn btn-success">Update</button>
                    </div>
                 </form>
              </div>
           </div>
        </div>
     </div>
  </main>
@endsection
