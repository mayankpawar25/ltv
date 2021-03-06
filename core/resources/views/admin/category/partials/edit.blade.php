<!-- Modal -->
<div class="modal fade" id="editModal{{$cat->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="" action="{{route('admin.category.update')}}" method="post" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              {{csrf_field()}}
              <input type="hidden" name="statusId" value="{{$cat->id}}">
              <div class="form-group">
                <div class="row">
                  <div class="col-md-12 mb-10">
                     <label>Category Name</label>
                     <input type="text" value="{{$cat->name}}" class="form-control" id="name" name="name" placeholder="Enter category name" >
                  </div>
                  <div class="col-md-12 mb-10">
                      @if($cat->image)
                      <div class="image">
                        <input type="hidden" name="old_image" value="{{ $cat->image }}">
                        <!-- <i class="fa fa-trash removeimage" data-id={{ $cat->id }}></i> -->
                        <img src="{{ asset('assets/user/img/category/'.$cat->image) }}">
                      </div>
                      @endif
                    <label>Image</label>
                    <input type="file" value="{{old('image')}}" class="form-control" id="image" name="image">
                  </div>
                  <div class="col-md-12 mb-10">
                    <label>Status</label>
                    <select class="form-control" name="status">
                      <option value="1" {{($cat->status==1) ? 'selected' : ''}}>Active</option>
                      <option value="0" {{($cat->status==0) ? 'selected' : ''}}>Deactive</option>
                    </select>
                  </div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">UPDATE</button>
          </div>
      </form>
    </div>
  </div>
</div>
