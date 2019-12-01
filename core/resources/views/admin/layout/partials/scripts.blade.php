
<script src="{{asset('assets/admin/js/popper.min.js')}}"></script>
<!--<script src="{{asset('assets/admin/js/bootstrap.min.js')}}"></script>-->
 <!-- Bootstrap -->
        <script src="{{asset('assets/admin/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/admin/js/main.js')}}"></script>
<!-- The javascript plugin to display page loading on top-->
<script src="{{asset('assets/admin/js/plugins/pace.min.js')}}"></script>

<!-- <script src="{{asset('assets/admin/js/plugins/chart.js')}}"></script> -->

<!-- Page specific javascripts-->
<!-- <script type="text/javascript" src="{{asset('assets/admin/js/plugins/chart.js')}}"></script> -->

<script src="{{asset('assets/admin/js/bootstrap-toggle.min.js')}}"></script>
<script src="{{ asset('assets/plugins/bootstrap-fileinput.js') }}" type="text/javascript"></script>
<!-- jQuery UI popup -->
<!--<script src="{{asset('assets/user/js/jquery-ui.js')}}"></script>-->
<script src="{{asset('assets/admin/plugins/jquery-ui-1.12.1/jquery-ui.min.js')}}" type="text/javascript"></script>

{{-- Toastr JS --}}
<script src="{{asset('assets/user/js/toastr.min.js')}}"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!-- jQuery Validation -->
<script type="text/javascript" src="{{asset('assets/plugins/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/plugins/jquery-validation/dist/additional-methods.js')}}"></script>

<!-- Select 2 JS-->
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script> 

<!--script vj-->
		
       
         
<!--        <script src="{{asset('assets/admin/plugins/materialize/js/materialize.min.js')}}" type="text/javascript"></script>
        
        <script src="{{asset('assets/admin/plugins/metismenu-master/dist/metisMenu.min.js')}}" type="text/javascript"></script>
     
        <script src="{{asset('assets/admin/plugins/slimScroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
     
        <script src="{{asset('assets/admin/plugins/malihu-custom-scrollbar/jquery.mCustomScrollbar.concat.min.js')}}" type="text/javascript"></script>
     
        <script src="{{asset('assets/admin/plugins/simplebar/dist/simplebar.js')}}" type="text/javascript"></script>
        
        <script src="{{asset('assets/admin/dist/js/custom.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/admin/dist/js/main.js')}}" type="text/javascript"></script>-->
        
<!--script end-->

<!-- Data Table Js -->
<!-- <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="//cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="//cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script> -->



<!-- Data Table Js -->


@if (session('success'))
<script type="text/javascript">
    $(document).ready(function(){
      toastr["success"]("<strong>Success!</strong> {{ session('success') }}!");
    });
</script>
@endif

@if (session('alert'))
<script type="text/javascript">
    $(document).ready(function(){
        toastr["error"]("{{ session('alert') }}!");
    });
</script>
@endif

{{-- Tostr options --}}
<script type="text/javascript">
  toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "3000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }

  
</script>



{{ load_extended_files('admin_js') }}
<script type="text/javascript">  

  accounting.settings = <?php echo json_encode(config('constants.money_format')) ?>;

    $(function(){

         <?php if($flash = session('message')) {?>
            $.jGrowl("<?php echo $flash; ?>", { position: 'bottom-right'});
        <?php } ?>

        $('.currency_changed').change(function(){
            $(this)
        });
    });
  


$(document).on('click','.change_task_status',function(e){

        e.preventDefault();

        var url       = $(this).attr("href");
        var name      = $(this).data('name');
        var id        = $(this).data('id');
        var task_id   = $(this).data('task');


        if(url)
        {
          $scope = this;
          $.post(url , { "_token": global_config.csrf_token, task_id : task_id, status_id : id }).done(function( response ) {
                      
              if(response.status == 1)
              {
                $($scope).closest(".dropdown").find(".btn").text(name);
              }
              
          });

        }       

    });

$(document).ready(function() {
  $(document).on('focus', ':input', function() {
    $(this).attr('autocomplete', 'off');
  });
});

</script>


<!-- @if(is_pusher_enable())
    <script src="https://js.pusher.com/4.3/pusher.min.js"></script> 
@endif  -->
<script  src="{{  url(asset('core/js/tinymce.js')) }}"></script>

@stack('scripts')

@yield('js-scripts')
