@extends('admin.layout.master')

@section('content')
<!-- <script async defer src="https://maps.googleapis.com/maps/api/js?callback=initMap"></script> -->

<!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&callback=initMap"></script> -->
 <main class="app-content">
     <div class="app-title">
       <div>
          <h1><i class="fa fa-dashboard"></i>Arrivals</h1>
       </div>
       <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
       </ul>
    </div>
    <div class="row">
        <div class="col-sm-3">
          <input type="text" name="date" class="form-control datepicker2" value="<?php echo date('d-m-Y') ?>" placeholder="">
        </div>
        <div class="col-md-12">
          <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
              <div class="tile">
                <div id="map" style="width: 100%; height: 800px;"></div>  
              </div>
            </div>
            <div class="card-footer"></div>
          </div>
        </div>
    </div>
  </main>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<script>
      var map;
      var marker;
      var iconBase;

      function initMap(date = 'Y-m-d') {
        map = new google.maps.Map(
            document.getElementById('map'),
            {center: new google.maps.LatLng(22.7529391,75.8915093), zoom: 13});

        get_lat_long(date);
        // console.log(features);
      }

      $(document).on('change','.datepicker2',function(){
          var date = $(this).val();
          initMap(date);
      });

      function get_lat_long(date="Y-m-d"){
        iconBase = "{{ asset('assets/') }}";
        $.ajax({
          url: "{{ route('admin.json') }}",
          type: 'GET',
          dataType: 'json',
          data : {id:"{{$data['id']}}",date:date},
        })
        .done(function(resp) {
          $.each(resp,function(index,i){
            console.log(i);
            console.log(date);
            marker = new google.maps.Marker({
              position: new google.maps.LatLng(i.latitude, i.longitude),
              icon: iconBase + '/marker.png',
              map: map
            });
          });
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
      }

    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyACpHll7FB2imlHOJGSp9E3VPPGJTBlSro&callback=initMap">
    </script>
@endsection





