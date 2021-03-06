<!DOCTYPE html>
<html lang="en">
  <head>
  @include('admin.layout.partials.head')
  </head>

  <body class="app sidebar-mini rtl">
    <!-- Navbar-->
    @includeif('admin.layout.partials.topnavbar')

    <!-- Sidebar menu-->
    @includeif('admin.layout.partials.sidenavbar')

    @yield('content')


    <!-- Essential javascripts for application to work-->
    @includeif('admin.layout.partials.scripts')

    @yield('onPageJs')

 <!-- <script type="text/javascript">
     $('.datepicker,.initially_empty_datepicker,.datepicker2').datepicker({
        autoclose: true,
        dateFormat: "dd-mm-yy"
  });
  </script> --> 
  <script type="text/javascript">
     $('.datepicker2').datepicker({
        autoclose: true,
        dateFormat: "dd-mm-yy"
  });
  </script>  
  </body>

</html>
