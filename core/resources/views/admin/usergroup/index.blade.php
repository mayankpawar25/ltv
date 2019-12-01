@extends('admin.layout.master')

@section('content')
  <main class="app-content">
 <!--    <div class="app-title">
        <div>
           <h1></h1>
        </div>
     </div>-->
     <div class="row">
        <div class="col-md-12">
           <div class="main-content">
             <div class="row">
             <div class="col-md-6">
              <h5 class="pull-left">User Group List</h5>
             
             </div>
             
              <div class="col-md-6 text-right">
             
               <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">
                   <i class="fa fa-plus"></i> Add User Group
                 </button>
             </div>
             
             </div>
             
             
               
                <hr />
              <p style="clear:both;margin:0px;"></p>
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
              </div>
              <div class="table-responsive">
                @if (count($cats) == 0)
                  <h2 class="text-center">NO CATEGORY FOUND</h2>
                @else
                  <table class="table table-bordered">
                     <thead>
                        <tr>
                           <th scope="col">SL</th>
                           <th scope="col">Name</th>
                           <th scope="col">Disc. Percentage</th>
                           <th scope="col">Status</th>
                           <th scope="col" class="text-right">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                          @foreach ($cats as $key => $cat)
                            <tr>
                               <td>{{$key+1}}</td>
                               <td>{{$cat->name}}</td>
                               <td>{{ $cat->percentage }}</td>
                               <td>
                                 @if ($cat->status == 1)
                                   <h4 style="display:inline-block;"><span class="badge badge-success">Active</span></h4>
                                 @elseif ($cat->status == 0)
                                   <h4 style="display:inline-block;"><span class="badge badge-danger">Deactive</span></h4>
                                 @endif
                               </td>
                               <td>
                                 <button type="button" class="btn btn-success btn-sm float-right"><span data-toggle="modal" data-target="#editModal{{$cat->id}}" ><i class="fas fa-pencil-alt"></i></span></button>
                               </td>
                            </tr>
                            @includeif('admin.usergroup.partials.edit')
                          @endforeach
                     </tbody>
                  </table>
                @endif
              </div>

              <div class="text-center">
                {{$cats->links()}}
              </div>
           </div>
        </div>
     </div>
  </main>

  {{-- Gateway Add Modal --}}
  @includeif('admin.usergroup.partials.add')
@endsection
