<?php
// TODO: nur anzeigen wenn SPALTE KOMMUNE VORhnd.

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
    mymap.whenReady(this.actioncenter);

    function actioncenter(){

                ac_initmap();
                default_marker();
        loadGeoJson('lkdh.geojson');
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