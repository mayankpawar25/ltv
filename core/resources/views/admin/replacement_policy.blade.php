@extends('admin.layout.master')

@push('nicedit-scripts')
  <script src="{{asset('assets/nic-edit/nicEdit.js')}}" type="text/javascript"></script>
  <script type="text/javascript">
    bkLib.onDomLoaded(function() {
      new nicEditor({iconsPath : '{{asset('assets/nic-edit/nicEditorIcons.gif')}}', fullPanel : true}).panelInstance('replacementPolicy');
    });
  </script>
@endpush

@section('content')
  <main class="app-content">
     {{-- <div class="app-title">
        <div>
           <h1>Replacement Policy</h1>
        </div>
     </div> --}}

     <div class="row">
        <div class="col-md-12">
           <div class="main-content">
            <h5>Replacement Policy</h5><hr>
              <div class="">
                 <form role="form" method="POST" action="{{route('admin.replacement.update')}}" enctype="multipart/form-data">
                    <div class="form-body">
                       {{csrf_field()}}
                       <div class="form-group">
                          
                          <textarea id="replacementPolicy" class="form-control" name="replacement_policy" rows="5" cols="80">{!! $gs->replacement_policy !!}</textarea>
                          @if ($errors->has('replacement_policy'))
                            <span style="color:red;">{{$errors->first('replacement_policy')}}</span>
                          @endif
                       </div>
                    </div>
                    <hr>
                    <div class="form-actions text-right">
                       <button type="submit" class="btn btn-success  btn-sm">Update</button>
                    </div>
                 </form>
              </div>
           </div>
        </div>
     </div>
  </main>
@endsection
