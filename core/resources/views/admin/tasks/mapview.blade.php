@extends('admin.layout.master')

@section('content')
<!-- <script async defer src="https://maps.googleapis.com/maps/api/js?callback=initMap"></script> -->

<!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&callback=initMap"></script> -->
 <main class="app-content">
     <div class="app-title">
       <div>
          <h1><i class="fa fa-map"></i>Arrivals</h1>
       </div>
       <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
       </ul>
    </div>
    <div class="row card">
      <div class="card-header"><h4>Arrivals</h4></div>
      <div class="card-body">
        <div class="col-sm-3">
          <div class="form-group">
            <label>Date: </label>
            <input type="text" name="date" class="form-control datepicker2" value="<?php echo date('d-m-Y') ?>" placeholder="">
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <div class="tile">
              <div id="map" style="width: 100%; height: 800px;"></div>  
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer"></div>
    </div>
  </main>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<script>
      var map;
      var marker;
      var iconBase;
      var base_url = '';
      var InforObj = [];
      var features = [];
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
          if(resp.length > 0){
            $.each(resp,function(index,i){
                  var coordinates = {
                    'position': new google.maps.LatLng(i.latitude, i.longitude), 
                    'icon' : iconBase + '/marker.png',
                    'id': i.id, 
                    'name': i.name, 
                    'description': i.description, 
                    'task_date': i.task_date, 
                    'from_time': i.from_time,
                    'to_time': i.to_time,
                    'task_status_id': i.task_status_id,
                    'client_name': i.client_name,
                    'client_type': i.client_type,
                    'address': i.address,
                    'created_at': i.created_at,
                    'salesman_name': i.salesman,
                    'status': i.status,
                    'time':i.time
                  };
                  features.push(coordinates);
            });
            for (var i = 0; i < features.length; i++) {
              if(features[i] != undefined){
                var contentString = '';
                contentString += '<div class="map-dec-tbl">'+
                '<tr>'+
                '<td colspan="2"><h5><strong>'+features[i].name+'</strong></h4></td>'+
                '</tr>';
                contentString +=  '<td width="73%"><p><strong>Description</strong>: '+features[i].description+'</p>'+
                            '<p><strong>'+features[i].client_type+' Name: </strong>'+features[i].client_name+'</p>'+
                            '<p><strong>Salesman Name </strong>.: '+features[i].salesman_name+'</p>'+
                            '<p><strong>Date </strong>: '+features[i].task_date+'</p>'+
                            '<p><strong>Time </strong>: '+features[i].time+'</p>'+
                            '<p><strong>Address </strong>: '+features[i].address+'</p>'+
                            '<p><strong>Status </strong>: '+features[i].status+'</p>'+
                            '</td>'+
                            '</tr></table></div></a>';
                contentString +=    '</div>';
              }
              if( $.trim(features[i].name).length > 0){
                /* Marker */
                const marker = new google.maps.Marker({
                              position: features[i].position,
                              icon: features[i].icon,
                              title: features[i].name,
                              fillColor: '#000000',
                              label: {
                                text: 'LTV',
                                color: '#51647c',
                                fontSize: '11px',
                                fontWeight: 'bold',
                              },
                              labelAnchor: new google.maps.Point(22, 0),
                              map: map
                            });



                
                const infowindow = new google.maps.InfoWindow({
                                    content: contentString,
                                    maxWidth: 300,
                                    maxHeight: 400
                                  });

                marker.addListener('click', function() {
                  closeOtherInfo();
                  infowindow.open(marker.get('map'), marker);
                  InforObj[0] = infowindow;
                });

                marker.addListener('mouseover', function() {
                  closeOtherInfo();
                  infowindow.open(marker.get('map'), marker);
                  InforObj[0] = infowindow;
                });
              }

              function closeOtherInfo() {
                if (InforObj.length > 0) {
                    /* detach the info-window from the marker ... undocumented in the API docs */
                    InforObj[0].set("marker", null);
                    /* and close it */
                    InforObj[0].close();
                    /* blank the array */
                    InforObj.length = 0;
                }
              }
            }
          }
        }).fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
      }

      function closeOtherInfo() {
        if (InforObj.length > 0) {
            /* detach the info-window from the marker ... undocumented in the API docs */
            InforObj[0].set("marker", null);
            /* and close it */
            InforObj[0].close();
            /* blank the array */
            InforObj.length = 0;
        }
      }

      function icon_generator(type, price){
        // console.log(type, price, price.length);
        var property_type = '';
        switch(type){
          case 'Residential':
            property_type = 'r';
            break;
          case 'Commercial':
            property_type = 'c';
            break;
          case 'Agricultural':
            property_type = 'a';
            break;
          default:
            property_type = 'c';
        }
      }

    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyACpHll7FB2imlHOJGSp9E3VPPGJTBlSro&callback=initMap">
    </script>
@endsection





