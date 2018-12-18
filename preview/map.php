<div id="maparea">
    <?php
    $res = mysql_fetch_array(mysql_query("SELECT * FROM ".$ressource["mysql_table_name"]." LIMIT 1"),MYSQL_ASSOC);
    $schema = data_get_table_schema($ressource["ressource_id"]);
    $found = 0;
    foreach($res as $key => $val)
    {
        if($schema[$key]["groupable"] == 1) {
            $ret[] = "<option>" . $key . "</option>";
            $found++;
        }
    }

    if($found > 0) {
        echo "<b>Markerfarbe:</b> <select id='gruppenspalte' onchange='colorbygroupmarker();'>";
        echo "<option value='-1'>Bitte wählen...</option>";
        echo implode("",$ret);
        echo "</select>";

    }
    ?>
    <div id="mapid" style="margin-top:16px; width: 100%; height: 600px;"></div>
    <?php
    if($found > 0)
    {
        echo "<div id='markerinfo'>&nbsp;</div>";
    }
    ?>

</div>

<script>
    var mymap = L.map('mapid', {fullscreenControl: true}).setView([52.7136799, 8.50045], 9);
    var res_id = '<?php echo $ressource["dkan_res_id"] ?>';
    var shape_enabled = <?php echo $ressource["preview_map_shape"] ?>;
    mymap.whenReady(this.actioncenter);
    mymap.scrollWheelZoom.disable();

    mymap.on('focus', function() {
            mymap.scrollWheelZoom.enable();
    });


    function actioncenter(){

        ac_initmap();
    }

    function ac_initmap()
    {
            var popup = L.popup();

            var options = {
                bingMapsKey: 'AkrjEJSeBzOvMQypgV2dA9WdUImUtKmVZSayl2YZ3Z7_4Bi_vXG5nCle_GxVnSZf',
                imagerySet: 'AerialWithLabels',
                culture: 'de-de'
            };



            var geojsonLandkreis;
            var geojsonGemeinde;
            var geojsonKommune;
            var nichts;

        var basemaps = {
            "Straßenkarte": L.tileLayer('https://tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href="http://openstreetmap.de">OpenStreetMap</a> contributors, ' +
                '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, '
            }),
            "Luftbilder (Bing)": L.tileLayer.bing("AkrjEJSeBzOvMQypgV2dA9WdUImUtKmVZSayl2YZ3Z7_4Bi_vXG5nCle_GxVnSZf"),
            "Luftbilder (mit Beschriftung)": L.tileLayer.bing(options)

        };

            $.getJSON("/lkdh.geojson", function (data) {
                geojsonLandkreis = L.geoJson(data, {style: styleKreis});
                $.getJSON("/kommune.geojson", function (data) {
                    geojsonKommune = L.geoJson(data, {style: styleKreis});
                    $.getJSON("/lkdh_gem.geojson", function (data) {
                        geojsonGemeinde = L.geoJson(data, {style: styleKreis});
                        $.getJSON("/nix.geojson", function (data) {
                        nichts = L.geoJson(data, {style: styleKreis});

                        var baselayers ={
                            "Umriss": {
                                "Kreisgrenze": geojsonLandkreis,
                                "Kommunen": geojsonKommune,
                                "Gemeindegrenzen": geojsonGemeinde,
                                "kein Umriss anzeigen": nichts
                            }
                        };

                        var options = {
                            // Make the "Landmarks" group exclusive (use radio inputs)
                            exclusiveGroups: ["baselayers","Umriss"],
                            // Show a checkbox next to non-exclusive group labels for toggling all
                            groupCheckboxes: true
                        };

                        var layerControl = L.control.groupedLayers(basemaps, baselayers, options);
                        mymap.addControl(layerControl);
                        mymap.addLayer(basemaps.Straßenkarte);
                        mymap.addLayer(geojsonKommune);
                        geojsonLandkreis.bringToBack();
                        colorbygroupmarker();
                        });
                    });
                });
            });

            function onMapClick(e) {

                console.log( e.latlng.toString());
            }

            mymap.on('click', onMapClick);
           // mymap.options.minZoom = 7;
         //   mymap.options.maxZoom = 18;
    }



    var myIcon = L.divIcon({
        className: "color_marker",
        iconAnchor: [0, 40],
        labelAnchor: [0, 0],
        popupAnchor: [11, -15],
        html: '<i class="fa fa-map-marker" style="color:#ee0000;" aria-hidden="true"></i>'
    });

    var defmarker;
    var exists = false;
    var numobjects = 0;

    var layerexists = false;

    function colorbygroupmarker()
    {
        if(shape_enabled == 1)
        {
            if (layerexists == true) {
                mymap.removeLayer(defmarker);
            }
            else {
                layerexists = true;
            }

            var seletedgroup = $("#gruppenspalte").val();




            if( seletedgroup == -1) {
                seletedgroup = '<?php echo $ressource["preview_marker_default_group"] ?>'
            }
            $.getJSON("/export/" + res_id + "/shape.geojson?gruppe=" + seletedgroup, function (data) {
                defmarker = L.geoJson(data, {style: styleGeoJson ,onEachFeature: onEachFeature});
                mymap.addLayer(defmarker);
            });


        }
        else {
            if (layerexists == true) {
                mymap.removeLayer(defmarker);
            }
            else {
                layerexists = true;
            }

            var colorscodes = new Array(
                "#e03616",
                "#4293cf",
                "#f3a712",
                "#e88eed",
                "#1be7ff",
                "#6eeb83",
                "#e4ff1a",
                "#0a2342",
                "#5bc0eb",
                "#ff5714",
                "#157f1f",
                "#73937e",
                "#fb8b24",
                "#730071",
                "#d90368",
                "#45b793",
                "#00a1ff"
            );

            var markerArray = [];
            $.get({
                method: "GET",
                url: "/api/action/datastore/search.json",
                data: {resource_id: res_id, limit: -1},
            })
                .done(function (msg) {

                    var seletedgroup = $("#gruppenspalte").val();
                    $("#markerinfo").html("");
                    var colors = new Array();
                    var founds = 1;
                    numobjects = Object.keys(msg.result.records).length;
                    $.each(msg.result.records, function (i, item) {

                        var popuptext = "";
                        $.each(item, function (i, bla) {
                            popuptext = popuptext + "<b>" + i + "</b>: " + bla + "<br>";
                        });


                        if (item[seletedgroup] in colors) {
                        }
                        else {
                            colors[item[seletedgroup]] = colorscodes[founds];
                            founds = founds + 1;
                            if (item[seletedgroup] === undefined) {

                            }
                            else
                                $("#markerinfo").append("<div class='map_legend_entry' style='background-color:" + colors[item[seletedgroup]] + ";'>" + item[seletedgroup] + "</div>");

                            if (founds >= colorscodes.length) {
                                founds = 1;
                            }
                        }

                        var myIcon = L.divIcon({
                            className: "color_marker",
                            iconAnchor: [0, 40],
                            labelAnchor: [0, 0],
                            popupAnchor: [0, 0],
                            html: '<i class="fa fa-map-marker" style="color:' + colors[item[seletedgroup]] + ';" aria-hidden="true"></i>'
                        });

                        // PER ENTRY SET MARKER
                        markerArray.push(L.marker([item.LAT, item.LON], {icon: myIcon}).bindPopup(popuptext));
                        defmarker = L.featureGroup(markerArray);
                        mymap.addLayer(defmarker);
                    });
                });
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

    function onEachFeature(feature, layer) {
        if (feature.properties && feature.properties.fid) {
            var popuptext = "";
            $.each(feature.properties, function (i, bla) {
                popuptext = popuptext + "<b>" + i + "</b>: " + bla + "<br>";
            });
            layer.bindPopup(popuptext);
        }
    }

    function styleGeoJson(feature) {
        if (feature.properties.fill) {
            return {
                weight: 2,
                opacity: 1,
                color: 'grey',
                dashArray: '3',
                fillOpacity: 0.7,
                fillColor: feature.properties.fill
            };
        } else {
            return {
                fillColor: '#ff0000',
                weight: 2.5,
                opacity: 1,
                color: 'grey',
                dashArray: '3',
                fillOpacity: 0.1
            };
        }
    }



    var numfetched = 0;
    function check_release()
    {
        numfetched = numfetched + 1;

        if(numfetched == numobjects)
        {
            console.log("alle da!");
        }
    }


</script>