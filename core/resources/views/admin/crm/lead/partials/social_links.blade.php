<div class="card">
<div id="social_links" v-cloak>
   <div class="card-header">
        <strong>@lang('form.social_links') </strong>
        <a class="btn btn-sm btn-primary float-md-right mb-1" style="font-size: 12px;" v-on:click.prevent="toggleModal" href="#">@lang('form.add')</a> 
      <div class="clearfix"></div>
   </div>
   <div class="card-body" style="margin-bottom: 20px;">
     
      <div class="modal fade mt-5" id="socialLinkModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">@lang('form.social_link')</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" v-on:click.prevent="toggleModal">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <form>
                     <div class="form-group">
                        <label for="recipient-name" class="col-form-label">@lang('form.social_media') <span class="required">*</span></label>
                        <input type="text" class="form-control form-control-sm" v-model="formInput.name">
                        <div class="invalid-feedback d-block name"></div>
                     </div>
                     <div class="form-group">
                        <label for="message-text" class="col-form-label">@lang('form.link') <span class="required">*</span></label>
                        <input type="text" class="form-control form-control-sm" v-model="formInput.link">
                        <div class="invalid-feedback d-block link"></div>
                     </div>
                  </form>
               </div>
               <div class="modal-footer">
                  <button v-on:click.prevent="toggleModal" type="button" class="btn btn-light" data-dismiss="modal">@lang('form.close')</button>
                  <button type="button" class="btn btn-success" v-on:click.prevent="save">@lang('form.submit')</button>
               </div>
            </div>
         </div>
      </div>
      <ul class="list-group" v-if="Object.keys(links).length > 0">
         <li class="list-group-item" v-for="key in Object.keys(links)"><a target="_blank" :href="links[key]">@{{ key }}</a> 
            <a href="#"  v-on:click.prevent="removeItem(key)"class="float-md-right text-danger"><strong>x</strong></a>
         </li>
      </ul>
   </div>
</div>
</div>