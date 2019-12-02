@extends('admin.layout.master')
@section('title', (isset($rec->id)) ? __('form.edit_lead').' : '.$rec->first_name. " ". $rec->last_name : __('form.add_new_lead') )
@section('content')
<div class="app-content">
    <div class="">
        <div class="tile">
            <div class="tile-body">
                 <h5>{{ (isset($rec->id)) ?  __('form.lead').' : ' . $rec->first_name. " ". $rec->last_name : __('form.add_new_lead') }}</h5>
                <hr>

                <div id="formArea" class="{{ (isset($rec->id) && (Route::currentRouteName() == 'show_lead_page') ) ? 'hide' : '' }}">
                    <form method="post" action='{{ (isset($rec->id)) ? route( 'patch_lead', $rec->id) : route('post_lead') }}'>

                        {{ csrf_field()  }}
                        @if(isset($rec->id))
                            {{ method_field('PATCH') }}
                        @endif

                        <div class="form-row">
                        <div class="col-md-3">
                            <div class="form-group ">
                                <label for="lead_status_id">@lang('form.status') <span class="required">*</span></label>
                                <?php echo form_dropdown("lead_status_id", $data['lead_status_id_list'], old_set("lead_status_id", NULL, $rec), "class='form-control  selectPickerWithoutSearch'") ?>
                                <div class="invalid-feedback">@php if($errors->has('lead_status_id')) { echo $errors->first('lead_status_id') ; } @endphp</div>
                            </div>
						</div>
                        <div class="col-md-3">
                            <div class="form-group ">
                                <label for="lead_source_id">@lang('form.source') <span class="required">*</span></label>
                                <?php echo form_dropdown("lead_source_id", $data['lead_source_id_list'], old_set("lead_source_id", NULL, $rec), "class='form-control  selectPickerWithoutSearch'") ?>
                                <div class="invalid-feedback">@php if($errors->has('lead_source_id')) { echo $errors->first('lead_source_id') ; } @endphp</div>
                            </div>
                            @php
                                $class = 'd-none';
                            @endphp
                            @if(auth()->user()->is_administrator)
                                @php
                                    $class = '';
                                @endphp
                            @endif
                            </div>
                            <div class="col-md-3 {{ $class }}">
                            <div class="form-group  ">
                                <label for="assigned_to">@lang('form.assigned_to')</label>
                                <?php echo form_dropdown("assigned_to", $data['assigned_to_list'], old_set("assigned_to", NULL, $rec), "class='form-control  selectpicker'") ?>
                                <div class="invalid-feedback">@php if($errors->has('assigned_to')) { echo $errors->first('assigned_to') ; } @endphp</div>
                            </div>
                            </div>
                            
                            <div class="col-md-3">
                             <div class="form-group">
                                    <label for="name">@lang('form.first_name') <span class="required">*</span> </label>
                                    <input type="text" class="form-control  {{ showErrorClass($errors, 'first_name') }}"
                                           id="first_name" name="first_name" value="{{ old_set('first_name', NULL, $rec) }}">
                                    <div class="invalid-feedback">{{ showError($errors, 'first_name') }}</div>
                                </div>
                            </div>
                          
                           <div class="col-md-3">
                           
                            <div class="form-group">
                                    <label for="name">@lang('form.last_name') <span class="required">*</span> </label>
                                    <input type="text" class="form-control  {{ showErrorClass($errors, 'last_name') }}"
                                           id="last_name" name="last_name" value="{{ old_set('last_name', NULL, $rec) }}">
                                    <div class="invalid-feedback">{{ showError($errors, 'last_name') }}</div>
                                </div>
                           
                           </div>
                            
                             <div class="col-md-3">
                           
                           <div class="form-group ">
                                        <label for="email">@lang('form.email')</label>
                                        <input type="text" class="form-control  @php if($errors->has('email')) { echo 'is-invalid'; } @endphp"
                                               id="email" name="email" value="{{ old_set('email', NULL, $rec) }}">
                                        <div class="invalid-feedback">@php if($errors->has('email')) { echo $errors->first('email') ; } @endphp</div>
                                    </div>
                           
                           </div>
                            
                            
                            
                             <div class="col-md-3">
                           <div class="form-group ">
                                        <label for="phone">@lang('form.phone')</label>
                                        <input type="text" class="form-control  @php if($errors->has('phone')) { echo 'is-invalid'; } @endphp"
                                               id="phone" name="phone" value="{{ old_set('phone', NULL, $rec) }}">
                                        <div class="invalid-feedback">@php if($errors->has('phone')) { echo $errors->first('phone') ; } @endphp</div>
                                    </div>
                           
                                         
                        @php $alt = (Object)[]; @endphp
                        @if($rec->alternate_number!='')
                            @php
                            $att = explode(',',$rec->alternate_number);
                            $alt->alt_number_one = (isset($att[0]))?$att[0]:'';
                            $alt->alt_number_two = (isset($att[1]))?$att[1]:'';
                            @endphp
                        @endif
                           </div>
                            
                             <div class="col-md-3">
                           <div class="form-group">
                                        <label for="alt_number_one">@lang('form.alt_number_one')</label>
                                        <input type="text" class="form-control  @php if($errors->has('alt_number_one')) { echo 'is-invalid'; } @endphp"
                                               id="alt_number_one" name="alt_number[]" value="{{ old_set('alt_number_one', NULL, $alt) }}">
                                        <div class="invalid-feedback">@php if($errors->has('alt_number_one')) { echo $errors->first('alt_number_one') ; } @endphp</div>
                                    </div>
                           
                           
                           </div>
                            
                             <div class="col-md-3">
                           
                           <div class="form-group">
                                        <label for="alt_number_two">@lang('form.alt_number_two')</label>
                                        <input type="text" class="form-control  @php if($errors->has('alt_number_two')) { echo 'is-invalid'; } @endphp"
                                               id="alt_number_two" name="alt_number[]" value="{{ old_set('alt_number_two', NULL, $alt) }}">
                                        <div class="invalid-feedback">@php if($errors->has('alt_number_two')) { echo $errors->first('alt_number_two') ; } @endphp</div>
                                    </div>
                           
                           </div>
                            
                            
                             <div class="col-md-3">
                           
                           <div class="form-group">
                                    <label for="company">@lang('form.company') @lang('form.name')</label>
                                    <input type="text" class="form-control  @php if($errors->has('company')) { echo 'is-invalid'; } @endphp"
                                           id="company" name="company" value="{{ old_set('company', NULL, $rec) }}">
                                    <div class="invalid-feedback">@php if($errors->has('company')) { echo $errors->first('company') ; } @endphp</div>
                                    </div>
                           
                           </div>
                            
                            
                             <div class="col-md-3">
                           <div class="form-group">
                                        <label for="position">@lang('form.position')</label>
                                        <input type="text" class="form-control  @php if($errors->has('position')) { echo 'is-invalid'; } @endphp"
                                               id="position" name="position" value="{{ old_set('position', NULL, $rec) }}">
                                        <div class="invalid-feedback">@php if($errors->has('position')) { echo $errors->first('position') ; } @endphp</div>
                                    </div>
                           
                           
                           </div>
                            
                            
                             <div class="col-md-3">
                           <div class="form-group">
                                    <label for="website">@lang('form.website')</label>
                                    <input type="text" class="form-control  @php if($errors->has('website')) { echo 'is-invalid'; } @endphp"
                                           id="website" name="website" value="{{ old_set('website', NULL, $rec) }}">
                                    <div class="invalid-feedback">@php if($errors->has('website')) { echo $errors->first('website') ; } @endphp</div>
                                </div>
                           
                           
                           </div>
                            
                            
                             <div class="col-md-3">
                           
                           <div class="form-group">
                                        <label for="phone">@lang('form.city')</label>
                                        <input type="text" class="form-control " name="city" value="{{ old_set('city', NULL, $rec) }}">
                                        <div class="invalid-feedback">@php if($errors->has('city')) { echo $errors->first('city') ; } @endphp</div>
                                    </div>
                           
                           </div>
                            
                            
                             <div class="col-md-3">
                           
                            <div class="form-group">
                                        <label for="phone">@lang('form.state')</label>
                                        <input type="text" class="form-control " name="state" value="{{ old_set('state', NULL, $rec) }}">
                                        <div class="invalid-feedback">@php if($errors->has('state')) { echo $errors->first('state') ; } @endphp</div>
                                    </div>
                           
                           </div>
                            
                            
                             <div class="col-md-3">
                            <div class="form-group">
                                        <label for="phone">@lang('form.zip_code')</label>
                                        <input type="text" class="form-control " name="zip_code" value="{{ old_set('zip_code', NULL, $rec) }}">
                                        <div class="invalid-feedback">@php if($errors->has('zip_code')) { echo $errors->first('zip_code') ; } @endphp</div>
                                    </div>
                           
                           
                           </div>
                            
                            
                             <div class="col-md-3">
                           
                           <div class="form-group">
                                        <label for="phone">@lang('form.country')</label>
                                        <div class="select2-wrapper">
                                            <?php echo form_dropdown("country_id", $data['country_id_list'], old_set("country_id", NULL, $rec), "class='form-control  selectpicker '") ?>
                                        </div>
                                        <div class="invalid-feedback">@php if($errors->has('country_id')) { echo $errors->first('country_id') ; } @endphp</div>

                                    </div>
                           
                           </div>
                            
                         <div class="col-md-12">
                          <div class="form-group">
                                    <label for="address">@lang('form.address')</label>
                                    <textarea rows="2" class="form-control  @php if($errors->has('address')) { echo 'is-invalid'; } @endphp"
                                              id="address" name="address" >{{ old_set('address', NULL, $rec) }}</textarea>
                                    <div class="invalid-feedback">@php if($errors->has('address')) { echo $errors->first('address') ; } @endphp</div>
                                </div>
                         </div>   
                            
                            
                            
                            
                            
                        </div>
                            

                         

                        <div class="form-group">
                            <label for="address">@lang('form.description')</label>
                            <textarea rows="4" class="form-control @php if($errors->has('description')) { echo 'is-invalid'; } @endphp"
                                      id="description" name="description" >{{ old_set('description', NULL, $rec) }}</textarea>
                            <div class="invalid-feedback">@php if($errors->has('description')) { echo $errors->first('description') ; } @endphp</div>
                        </div>

                         <div class="form-group">
                            <label for="group_id">@lang('form.tag')</label>
                            <div class="select2-wrapper">
                                <?php echo form_dropdown("tag_id[]", $data['tag_id_list'], old_set("tag_id", NULL, $rec), "class='form-control select2-multiple' multiple='multiple'") ?>
                            </div>
                            <div class="invalid-feedback">@php if($errors->has('tag_id')) { echo $errors->first('group_id') ; } @endphp</div>
                        </div>
                   

                        <?php echo bottom_toolbar(); ?>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<style>
.select2-container--bootstrap .select2-selection--multiple .select2-search--inline .select2-search__field {
    margin: 5px 0 !important;
}
</style>
@endsection