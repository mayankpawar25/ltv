<div class="row">
   <div class="col-md-12 ">
     <img class="card-img-top img-fluid img__img member-avatar" src="<?php echo asset('images/user-placeholder.jpg') ; ?>"  v-bind:src="records.contact_photo_url">
   </div>
   <div class="col-md-12 " >
      <h4 class="text-center"><a v-bind:href="records.contact_edit_page_url">@{{ records.first_name   }} @{{ records.last_name }}</a>
      <i v-if="records.is_important" class="fas fa-star star-important" data-toggle="tooltip" data-placement="top" title="@lang('form.important')"></i></h4>
      <p class="text-center">@{{ records.position   }}, <a v-bind:href="records.company_page_url" >@{{ records.company_name }}</a></p>     
   
      <div class="quick-preview text-center">
   <P>@lang('form.email')
               : @{{ records.email }}</P>
   <P>@lang('form.phone'): @{{ records.phone }}</P> 
    
      </div>
   </div>
</div>
 