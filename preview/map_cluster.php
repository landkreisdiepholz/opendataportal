<?php
// TODO: nur anzeigen wenn SPALTE KOMMUNE VORhnd.
// TODO: GEOJSON Togglen anstelle überlagern

?>
<div id="maparea">
    <div id="mapid" style="margin-top:16px; width: 100%; height: 600px;"></div>
    <br>
    <a href="#" onclick="loadGeoJson('lkdh.geojson');return false;" class="btn btn-default">Kreisgrenze anzeigen</a>
    <a href="#" onclick="loadGeoJson('kommune.geojson');return false;" class="btn btn-default">Kommunen anzeigen</a>
    <a href="#" onclick="loadGeoJson('lkdh_gem.geojson');return false;" class="btn btn-default">Mitgliedsgemeinden anzeigen</a>
    <a href="#" onclick="loadGeoJson(false);return false;" class="btn btn-default">kein Umriss anzeigen</a>
</div>
<script>
    var mymap = L.map('mapid', {fullscreenControl: true}).setView([52.7136799, 8.50045], 9);
    var res_id = '<?php echo $ressource["dkan_res_id"] ?>';
    var only_cluster = '<?php echo $ressource["preview_map_cluster_only_cluster"] ?>';
    var kreisGrenze;
    var gemeindGrenze;
    var defmarker;
    var clustermarker;

    mymap.on('zoomend', this.actioncenter);
    mymap.whenReady(this.actioncenter);

    function actioncenter(){
        zoomLev = mymap.getZoom();
        console.log("LVL:"+zoomLev);

        console.log(only_cluster)

        switch(zoomLev)
        {
            case 1:
                ac_cluster();
                break;
            case 2:
                ac_cluster();
                break;
            case 3:
                ac_cluster();
                break;
            case 4:
                ac_cluster();
                break;
            case 5:
                ac_cluster();
                break;
            case 6:
                ac_cluster();
                break;
            case 7:
                ac_cluster();
                break;
            case 8:
                ac_cluster();
                break;
            case 9:
                ac_cluster();
                break;
            case 10:
                ac_cluster();
                break;
            default:
                if(only_cluster == "1")
                {
                    ac_cluster();
                }
                else
                ac_default();
                break;
        }
    }

    var autocluster_inited = false;
    var no_kommune_found = false;
    function ac_cluster()
    {
        if(!no_kommune_found) {
            ac_initmap();
            remove_layers();
            generate_cluster();
        }

        if(no_kommune_found)
        {
            if(!autocluster_inited) {
                console.log("Keine Kommune autocluster ...");
                var markers = L.markerClusterGroup({chunkedLoading: true});

                $.get({
                    method: "GET",
                    url: "/api/action/datastore/search.json",
                    data: {resource_id: res_id, limit: -1},
                })
                    .done(function (msg) {

                        $.each(msg.result.records, function (i, item) {
                            var popuptext = "";

                            $.each(item, function (i, bla) {
                                popuptext = popuptext + "<b>" + i + "</b>: " + bla + "<br>";
                            });

                            // PER ENTRY SET MARKER
                            var marker = L.marker([item.LAT, item.LON]).bindPopup(popuptext);
                            markers.addLayer(marker);

                        });

                        mymap.addLayer(markers);

                    });
                autocluster_inited = true;
            }

        }
    }


    function ac_default()
    {
        ac_initmap();
        remove_layers();
        default_marker();
    }


    function remove_layers()
    {
        if (typeof(defmarker) != "undefined")
        mymap.removeLayer(defmarker);
        if (typeof(clustermarker) != "undefined")
        mymap.removeLayer(clustermarker);
    }



    var mapinited = false;
    function ac_initmap()
    {
        if(!mapinited) {
            mapinited = true;
            var popup = L.popup();
            L.tileLayer('https://tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href="http://openstreetmap.de">OpenStreetMap</a> contributors, ' +
                '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, '
            }).addTo(mymap);

            function onMapClick(e) {

                console.log( e.latlng.toString());
            }

            mymap.on('click', onMapClick);
            mymap.options.minZoom = 7;
            mymap.options.maxZoom = 18;

            loadGeoJson('kommune.geojson');

        }
    }

    var defaultmarkerinited = false;
    function default_marker()
    {
        if(!defaultmarkerinited)
        {
            defaultmarkerinited = true;
            var markerArray = [];
            $.get({
                method: "GET",
                url: "/api/action/datastore/search.json",
                data: {resource_id: res_id, limit: -1},
            })
                .done(function (msg) {

                    $.each(msg.result.records, function (i, item) {

                        var popuptext = "";
                        $.each(item, function (i, bla) {
                            popuptext = popuptext + "<b>" + i + "</b>: " + bla + "<br>";
                        });

                        // PER ENTRY SET MARKER
                        markerArray.push(L.marker([item.LAT, item.LON]).bindPopup(popuptext));
                    });

                    defmarker = L.featureGroup(markerArray);
                    mymap.addLayer(defmarker);
                });
        }
        else
        mymap.addLayer(defmarker);
    }


    var ClusterValues =  [];

    var clusterGenerated = false;
    function generate_cluster()
    {
        if(!clusterGenerated)
        {
            clusterGenerated = true;
            var markerArray = [];
            var ClusterValues = [];
            $.get({
                method: "GET",
                url: "/api/action/datastore/search.json",
                data: { resource_id: res_id, limit: -1},
            })
                .done(function( msg ) {
                    $.each(msg.result.records, function(i, item) {
                        if(!no_kommune_found) {
                            if (typeof(item.KOMMUNE) != "undefined") {
                                if (isNaN(ClusterValues[item.KOMMUNE]))
                                    ClusterValues[item.KOMMUNE] = 1;
                                else
                                    ClusterValues[item.KOMMUNE] = ClusterValues[item.KOMMUNE] + 1;

                            }
                            else {
                                no_kommune_found = true;
                                ac_cluster();
                                console.log("Kommune nicht gesetzt!");
                            }
                        }
                        });
                    if(!no_kommune_found) {
                        // add Marker for Kommune
                        $.get({
                            method: "GET",
                            url: "/api/action/datastore/search.json",
                            data: {resource_id: "198561aaeab90125e42f7580a7021429", limit: -1},
                        })
                            .done(function (msg) {
                                $.each(msg.result.records, function (i, item) {
                                    if (item.LAT !== null && item.LON !== null) {
                                        var childs = 0;

                                        if (typeof( ClusterValues[item.NAME]) != "undefined") {
                                            var text = '<div><span>' + ClusterValues[item.NAME] + '</span></div>';
                                            childs = ClusterValues[item.NAME];
                                        }
                                        else
                                            var text = '<div><span>0</span></div>';


                                        var c = ' marker-cluster-';

                                        if(childs == 0)
                                        {
                                            c += 'null';
                                        }
                                        else {
                                            if (childs < 10) {
                                                c += 'small';
                                            } else if (childs < 100) {
                                                c += 'medium';
                                            } else {
                                                c += 'large';
                                            }
                                        }
                                        var popuptext = item.NAME + " => " + childs + " Stck.";
                                        var myIcon = L.divIcon({
                                            html: text,
                                            className: 'marker-cluster' + c,
                                            iconSize: L.point(40, 40)
                                        });
                                        var marker = L.marker([item.LAT, item.LON], {icon: myIcon}).bindPopup(popuptext);

                                        markerArray.push(marker);
                                    }
                                });
                                clustermarker = L.featureGroup(markerArray);
                                mymap.addLayer(clustermarker);
                            });
                    }

                });

        }
        else
        {
            mymap.addLayer(clustermarker);
            return true;
        }
    }
    var geojson;
    var geojsoninited = false;
    function loadGeoJson(filename){

        if(filename == false)
        {
            geojson.clearLayers();
            mymap.removeLayer(geojson);
            geojsoninited = false;
        }
        else {
            console.log("Loading Layer: " + filename);
            jQuery.getJSON("/" + filename, function (data) {

                if (geojsoninited) {
                    geojson.clearLayers();
                }

                geojson = L.geoJson(data, {style: styleKreis});

                if (!geojsoninited) {
                    geojsoninited = true;
                    mymap.addLayer(geojson);
                }
                else {
                    mymap.addLayer(geojson);
                }

            });
        }

    }

    function styleKreis(feature) {
        return {
            fillColor: '#ff0000',
            weight: 2.5,
            opacity: 1,
            color: 'grey',
            dashArray: '3',
            fillOpacity: 0.1
        };
    }
</script>