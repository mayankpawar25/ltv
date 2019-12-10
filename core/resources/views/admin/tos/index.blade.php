@extends('admin.layout.master')

@push('nicedit-scripts')
  <script src="{{asset('assets/nic-edit/nicEdit.js')}}" type="text/javascript"></script>
  <script type="text/javascript">
    bkLib.onDomLoaded(function() {
      new nicEditor({iconsPath : '{{asset('assets/nic-edit/nicEditorIcons.gif')}}', fullPanel : true}).panelInstance('tos');
    });
  </script>
@endpush

@section('content')
  <main class="app-content">
     {{-- <div class="app-title">
        <div>
           <h1>Terms & Conditios</h1>
        </div>
     </div> --}}
     <div class="row">
        <div class="col-md-12">
           <div class="main-content">
            <h5>Terms & Conditions</h5><hr>
              <div>
                 <form role="form" method="POST" action="{{route('admin.tos.update')}}" enctype="multipart/form-data">
                    <div class="">
                       {{csrf_field()}}
                       <div class="form-group">
                          
                          <textarea class="form-control" name="tos" id="tos" rows="10">{{$gs->tos}}</textarea>
                          @if ($errors->has('tos'))
                            <span style="color:red;">{{$errors->first('tos')}}</span>
                          @endif
                       </div>
                    </div>
                    <hr>
                    <div class="form-actions text-right">
                       <button type="submit" class="btn btn-success btn-sm">Update</button>
                    </div>
                 </form>
              </div>
           </div>
        </div>
     </div>
  </main>
@endsection
