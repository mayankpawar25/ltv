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
     
     <div class="row">
        <div class="col-md-12">

          <div class="main-content">
            <h5>Footer Text</h5><hr>
            <div class="row">

              <div class="col-md-12">
                <form action="{{route('admin.footer.update')}}" method="post" role="form">
                  {{csrf_field()}}
                   <div class="form-body">
                      <div class="form-group">
                         
                         <div class="">
                            <textarea id="footerTextArea" style="width:100%;" class="form-control" name="footer_text" rows="3" cols="80">{!! $gs->footer !!}</textarea>
                            @if ($errors->has('footer_text'))
                              <p class="text-danger">{{$errors->first('footer_text')}}</p>
                            @endif
                         </div>
                      </div>
                      <hr>
                      <div class="row">
                           <div class="col-md-12 text-right">
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
