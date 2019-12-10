@extends('admin.layout.master')

@section('content')
  <main class="app-content">
      
     <div class="row">
        <div class="col-md-12">
           <div class="main-content" style="margin-bottom:30px">
              <h5>Short Code</h5>
              <hr />
              <div class="">
                 <div class="table-responsive">
                    <table class="table table-bordered w-100">
                       <thead>
                          <tr>
                             <th> # </th>
                             <th> CODE </th>
                             <th> DESCRIPTION </th>
                          </tr>
                       </thead>
                       <tbody>
                          <tr>
                             <td> 1 </td>
                             <td>
                                <pre>&#123;&#123;message&#125;&#125;</pre>
                             </td>
                             <td> Details Text From Script</td>
                          </tr>
                          <tr>
                             <td> 2 </td>
                             <td>
                                <pre>&#123;&#123;number&#125;&#125;</pre>
                             </td>
                             <td> Users Number. Will Pull From Database</td>
                          </tr>
                       </tbody>
                    </table>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <div class="row">
        <div class="col-md-12">
           <div class="main-content">
           <h5>Sms Api</h5>
           <hr />
              <div class="">
                 <form role="form" method="POST" action="{{route('admin.UpdateSmsSetting')}}" >
                    {{csrf_field()}}
                    <div class="form-body">
                       <div class="form-group">
                          <label for=""></label>
                          <input type="text" name="smsApi" id="smsapi" class="form-control" value="{{$gs->sms_api}}">
                          @if ($errors->has('smsApi'))
                            <span style="color:red;">{{$errors->first('smsApi')}}</span>
                          @endif
                       </div>
                    </div>
                    <hr>
                    <div class="text-right">
                    <button type="submit" class="btn btn-success">Update</button>
                    </div>
                 </form>
              </div>
           </div>
        </div>
     </div>
  </main>
@endsection
