@extends('admin.crm.setup.index')
@section('title', __('form.settings') . " : " .__('form.email_template'))
@section('setting_page')
<div class="app-content">

<div class="app-title">    
 @include('admin.crm.setup.menu')
</div>
<div class="main-content">
   <h5>@lang('form.email_templates')</h5>
   <hr>
	@include('admin.crm.setup.email_template_list')
</div>	
</div>
@endsection