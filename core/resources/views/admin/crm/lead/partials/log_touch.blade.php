<style>
.select2-container--bootstrap{
	width:100% !important;
}
</style>
<div class="modal fade" id="logTouchModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">@lang('form.log_touch')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="logTouchForm" autocomplete="off" action="" method="POST">

            <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                  <label for="lead_status_id">@lang('form.medium') <span class="required">*</span></label><br />

                  <?php echo form_dropdown("medium", $data['touch_mediums'], [], "class='form-control  selectPickerWithoutSearch'") ?>
                  <div class="invalid-feedback d-block medium"></div>
              </div>
              </div>
               <div class="col-md-4">
              <div class="form-group ">
                <label for="">@lang('form.date') <span class="required">*</span></label>
                <input type="text" class="form-control" name="date">
                <div class="invalid-feedback d-block date"></div>
              </div>
			</div>
             <div class="col-md-4">
              <div class="form-group">
                <label for="message-text">@lang('form.time') <span class="required">*</span></label><br />

                <?php echo form_dropdown("time", $data['time'], [], "class='form-control form-control-sm selectPickerWithoutSearch'") ?>

                <div class="invalid-feedback d-block time"></div>
              </div>
			</div>
            <div class="col-md-4">
            <div class="form-group">
                  <label for="lead_status_id">@lang('form.resolution') <span class="required">*</span></label>
                  <?php echo form_dropdown("resolution", $data['resolutions'], [], "class='form-control form-control-sm selectPickerWithoutSearch'") ?>
                  <div class="invalid-feedback d-block resolution"></div>
              </div>
            </div>
           </div> 


           

            <div class="form-group">
                  <label for="lead_status_id">@lang('form.description') </label>
                  <textarea class="form-control form-control-sm" name="description"></textarea>
                  <div class="invalid-feedback d-block description"></div>
              </div>
          
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">@lang('form.close')</button>
        <button type="button" class="btn btn-success" id="submitLogTouchForm">@lang('form.submit')</button>
      </div>
    </div>
  </div>
</div>