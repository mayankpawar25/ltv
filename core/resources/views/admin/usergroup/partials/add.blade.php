<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="" action="{{route('admin.usergroup.store')}}" method="post" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add New User Group</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              {{csrf_field()}}
              <div class="form-group">
                <div class="row">

                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Name</label>
                      <input type="text" value="{{old('name')}}" class="form-control" id="name" name="name" placeholder="Enter User Group Name" >
                    </div>
                  </div>

                  <div class="col-md-12 mb-10">
                    <div class="form-group">
                      <label>Disc. Percentage</label>
                      <input type="number" value="{{old('percentage')}}" class="form-control" id="percentage" name="percentage" placeholder="Enter User Group Percentage" >
                    </div>
                  </div>

                  <div class="col-md-12 mb-10">
                    <label>Status</label>
                    <select class="form-control" name="status">
                      <option value="1">Active</option>
                      <option value="0">Deactive</option>
                    </select>
                  </div>

                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">ADD</button>
          </div>
      </form>
    </div>
  </div>
</div>
