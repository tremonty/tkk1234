<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$search_radius = (!empty($search_radius)) ? $search_radius : 1000;

$id = rand();


if ( empty( $lat ) ) {
    $lat = 36.169941;
}

if ( empty( $lng ) ) {
    $lng = - 115.139830;
}

$map_style = array();
$map_style['width'] = ' width: 100vw;';
$map_style['height'] = ' height: 100%;';
$disable_mouse_whell = 'false';

$mapHeight = (!empty($map_height)) ? $map_height : 580;
if(wp_is_mobile()) $mapHeight = 500;

$pin_url = (!empty($marker)) ? wp_get_attachment_image_src($marker, 'full')[0] : get_template_directory_uri() . '/assets/images/marker-listing-two.png';
$cluster_url_path = (!empty($cluster)) ? wp_get_attachment_image_src($cluster, 'full')[0] : get_template_directory_uri() . '/assets/images/cluster-listing-two.png';

?>

<div class="stm-inventory-map-wrap" style="height: <?php echo esc_attr($mapHeight); ?>px;">
    <div<?php echo( ( $map_style ) ? ' style="' . esc_attr( implode( ' ', $map_style ) ) . ' margin: 0 auto; "' : '' ); ?> id="stm_map-<?php echo esc_attr( $id ); ?>" class="stm_gmap"></div>
</div>


<script>
    jQuery("body").addClass("stm-inventory-map-body");
    jQuery(document).ready(function ($) {

        google.maps.event.addDomListener(window, 'load', init);

        var center, map;
        var markers = [];
        var markerCluster;

        function init() {
            center = new google.maps.LatLng(<?php echo esc_js( $lat ); ?>, <?php echo esc_js( $lng ); ?>);

            var mapOptions = {
                zoom: 5,
                center: center,
                scrollwheel: <?php echo esc_js( $disable_mouse_whell ); ?>,
                mapTypeId: 'roadmap',
                minZoom: 2,
                maxZoom: 20,
            };

            var mapElement = document.getElementById('stm_map-<?php echo esc_js( $id ); ?>');
            map = new google.maps.Map(mapElement, mapOptions);

            $(".stm_gmap").addClass("stm-loading");

            markers.length = 0;

            var data = 'ca_location=<?php echo esc_attr($address); ?>&stm_lat=<?php echo stm_do_lmth($lat); ?>&stm_lng=<?php echo stm_do_lmth($lng);?>&max_search_radius=<?php echo stm_do_lmth($search_radius);?>';

            $.ajax({
                url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
                type: "GET",
                data: 'action=stm_ajax_get_cars_for_inventory_map&security=' + getCarsInvtMap + '&' + data,
                dataType: "json",
                success: function (msg) {
                    $(".stm_gmap").removeClass("stm-loading");
                    locations = msg['markers'];
                    carData = msg['carsData'];
                    mapLocationCar = msg['mapLocationCar'];

                    for (var i = 0; i < locations.length; i++) {
                        var latLng = new google.maps.LatLng(locations[i]["lat"],locations[i]["lng"]);

                        if(i == 0) {
                            center = new google.maps.LatLng(locations[i]["lat"],locations[i]["lng"]);
                            map.setCenter(center);
                        }

                        var marker = new google.maps.Marker({
                            position: latLng,
                            icon: '<?php echo esc_url($pin_url); ?>',
                            map: map
                        });

                        var infowindow = new google.maps.InfoWindow({
                            pixelOffset: new google.maps.Size(-157, -200)
                        });

                        markers.push(marker);
                        google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
                            return function() {
                                if(mapLocationCar[locations[i]["lat"]].length == 1) {

                                    infowindow.setOptions({pixelOffset: new google.maps.Size(-157, -200)})

                                    infowindow.setContent('<a class="stm_iw_link" href="' + carData[i]["link"] + '"> <div class="stm_map_info_window_wrap">' +
                                        '<div class="stm_iw_condition">' + carData[i]["condition"] + ' ' + carData[i]["year"] + '</div>' +
                                        '<div class="stm_iw_title">' + carData[i]["title"] + '</div>' +
                                        '<div class="stm_iw_car_data_wrap">' +
                                        '<div class="stm_iw_img_wrap">' +
                                        carData[i]["image"] +
                                        '</div>' +
                                        '<div class="stm_iw_car_info">' +
                                        '<span class="stm_iw_car_opt stm_car_mlg"><i class="' + carData[i]["mileage_font"] + '"></i>' + carData[i]["mileage"] + '</span>' +
                                        '<span class="stm_iw_car_opt stm_car_engn"><i class="' + carData[i]["engine_font"] + '"></i>' + carData[i]["engine"] + '</span>' +
                                        '<span class="stm_iw_car_opt stm_car_trnsmsn"><i class="' + carData[i]["transmission_font"] + '"></i>' + carData[i]["transmission"] + '</span>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="stm_iw_car_price"><span class="stm_iw_price_trap"></span>' + carData[i]["price"] + '</div>' +
                                        '</div></a>');
                                } else {

                                    var groupClass = (mapLocationCar[locations[i]["lat"]].length <= 3) ? "stm_if_group_" + mapLocationCar[locations[i]["lat"]].length + " stm_if_group_no_scroll" : "stm_if_group_scroll"

                                    var infoWindowHtml = '<div class="stm_map_info_window_group_wrap ' + groupClass +'">';

                                    infowindow.setOptions({pixelOffset: new google.maps.Size(-158, -515)})
                                    for(var w=0;w<mapLocationCar[locations[i]["lat"]].length;w++) {
                                        var carPos = mapLocationCar[locations[i]["lat"]][w];
                                        infoWindowHtml += '<a class="stm_iw_link" href="' + carData[carPos]["link"] + '"> <div class="stm_map_info_window_wrap">' +
                                            '<div class="stm_iw_condition">' + carData[carPos]["condition"] + ' ' + carData[carPos]["year"] + '</div>' +
                                            '<div class="stm_iw_title">' + carData[carPos]["title"] + '</div>' +
                                            '<div class="stm_iw_car_data_wrap">' +
                                            '<div class="stm_iw_img_wrap">' +
                                            carData[carPos]["image"] +
                                            '</div>' +
                                            '<div class="stm_iw_car_info">' +
                                            '<span class="stm_iw_car_opt stm_car_mlg"><i class="' + carData[carPos]["mileage_font"] + '"></i>' + carData[carPos]["mileage"] + '</span>' +
                                            '<span class="stm_iw_car_opt stm_car_engn"><i class="' + carData[carPos]["engine_font"] + '"></i>' + carData[carPos]["engine"] + '</span>' +
                                            '<span class="stm_iw_car_opt stm_car_trnsmsn"><i class="' + carData[carPos]["transmission_font"] + '"></i>' + carData[carPos]["transmission"] + '</span>' +
                                            '</div>' +
                                            '</div>' +
                                            '<div class="stm_iw_car_price"><span class="stm_iw_price_trap"></span>' + carData[carPos]["price"] + '</div>' +
                                            '</div></a>';
                                    }

                                    infoWindowHtml += '</div>';

                                    infowindow.setContent(infoWindowHtml);
                                }
                                infowindow.open(map, marker);
                            }

                        })(marker, i));
                    }
                    markerCluster = new MarkerClusterer(map, markers, {maxZoom: 9, averageCenter: true, styles: [{url: '<?php echo stm_do_lmth($cluster_url_path); ?>', textColor: 'white', height: 60, width: 60, textSize: 20}]});

                    google.maps.event.addListener(map, 'click', function() {
                        if (infowindow) {
                            infowindow.close();
                        }
                    });
                }
            });
        }

        $(window).resize(function(){
            if(typeof map != 'undefined' && typeof center != 'undefined') {
                setTimeout(function () {
                    map.setCenter(center);
                }, 1000);
            }
        });
    });
</script>