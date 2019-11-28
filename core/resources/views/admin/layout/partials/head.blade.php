
  <meta name="description" content="Vali is a responsive and free admin theme built with Bootstrap 4, SASS and PUG.js. It's fully customizable and modular.">

  <title>{{$gs->website_title}} - Admin</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- favicon -->
  <link rel="shortcut icon" href="{{asset('assets/user/interfaceControl/logoIcon/icon.jpg')}}" type="image/x-icon">
  <!-- Toastr  -->
  <link rel="stylesheet" href="{{asset('assets/user/css/toastr.min.css')}}">
  <!-- bootstrap -->
  <link rel="stylesheet" href="{{asset('assets/user/css/bootstrap.min.css')}}">
  <!-- jQUery UI -->
  <link rel="stylesheet" href="{{asset('assets/user/css/jquery-ui.css')}}">
  <!-- Main CSS-->
  <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/main.css')}}">
  <!-- Font-icon css-->
  


  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
  <!-- Font-icon 5 css-->
  <link rel="stylesheet" href="{{asset('assets/admin/css/fontawesome-5.css')}}">

  {{-- Bootstrap Toggle CSS --}}
  <link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap-toggle.min.css')}}">
  {{-- jquery datetimepicker css --}}
  <link rel="stylesheet" href="{{asset('assets/user/css/jquery.datetimepicker.min.css')}}">
  <script src="{{asset('assets/admin/js/jquery-3.2.1.min.js')}}"></script>

  <script src="{{asset('assets/user/js/vue.js')}}"></script>
  {{-- File input CSS --}}
  <link href="{{ asset('assets/plugins/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
 
 <!--vj-->
  
		<link href="{{asset('assets/admin/plugins/jquery-ui-1.12.1/jquery-ui.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- materialize css -->
        <link href="{{asset('assets/admin/plugins/materialize/css/materialize.min.css')}}" rel="stylesheet">
        <!-- Bootstrap css-->
        <link href="{{asset('assets/admin/plugins/materialize/css/materialize.min.css')}}" rel="stylesheet">
        <!-- Animation Css -->
        <link href="{{asset('assets/admin/plugins/animate/animate.css')}}" rel="stylesheet" />
        <!-- Material Icons CSS -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="{{asset('assets/admin/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Monthly css -->
        <!-- <link href="assets/plugins/monthly/monthly.css" rel="stylesheet" type="text/css" />-->
        <!-- simplebar scroll css -->
        <link href="{{asset('assets/admin/plugins/simplebar/dist/simplebar.css')}}" rel="stylesheet" type="text/css" />
        <!-- mCustomScrollbar css -->
        <link href="{{asset('assets/admin/plugins/malihu-custom-scrollbar/jquery.mCustomScrollbar.css')}}" rel="stylesheet" type="text/css" />
        <!-- custom CSS -->
        <link href="{{asset('assets/admin/dist/css/stylematerial.css')}}" rel="stylesheet">
        
        <!--css end-->


  @stack('styles')
  {{-- NICedit CDN --}}
  @stack('nicedit-scripts')
   <link rel="stylesheet" href="{{asset('assets/css/app.css')}}">  
<link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/custom.css')}}">
<script type="text/javascript">

 <?php $pusher = get_pusher_api_info(); ?>

    global_config = {
        csrf_token                      : "{{ csrf_token() }}",
        url_get_unread_notifications    : "#", 
        lang_no_record_found            : "{{ __('form.no_record_found') }}",
        url_global_search               : "#",
        url_upload_attachment           : "#",
        url_delete_temporary_attachment : "#",
        txt_delete_confirm_title        : "{{ __('form.delete_confirm_title') }}",
        txt_delete_confirm_text         : "{{ __('form.delete_confirm_text') }}",
        txt_btn_cancel                  : "{{ __('form.btn_cancel') }}",
        txt_yes                         : "{{ __('form.yes') }}",        
        is_pusher_enable                : {{ (is_pusher_enable()) ?  'true' : 'false' }},
        url_patch_note                  : "#",
        url_delete_note                 : "#"

    };

    <?php if(is_pusher_enable()) {?>

        global_config.pusher_log_status = {{ ( App::environment('local') || App::environment('development') ) ? 'true' : 'false' }};
        global_config.pusher_app_key    = '{{ $pusher->app_key }}';
        global_config.pusher_cluster    = "{{ ($pusher->app_cluster) ? $pusher->app_cluster : 'mt1' }}";
        global_config.pusher_channel    = 'chanel_{{ auth()->user()->id }}';

    <?php } ?>    

</script>
<script type="text/javascript" src="{{  url(asset('core/js/app.js')) }}"></script>
<script  type="text/javascript" src="{{  url(asset('core/js/vendor.js')) }}"></script>
<script  type="text/javascript" src="{{  url(asset('core/js/main.js')) }}"></script>
<script  type="text/javascript" src="{{ asset('assets/vendor/gantt-chart/js/modified_jquery.fn.gantt.min.js') }}"></script>