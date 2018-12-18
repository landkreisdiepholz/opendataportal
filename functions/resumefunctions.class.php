<?php
/**
 * Created by PhpStorm.
 * User: d12hanse
 * Date: 25.08.2017
 * Time: 08:57
 */


function caldiff($akt, $old)
{
    if ($old > 0) {
        $diff = $akt - $old;
        if ($diff > 0)
            return "<span style='color:green;'>" . $diff . "</span>";

        if ($diff == 0)
            return $diff;

        if ($diff < 0)
            return "<span style='color:red;'>" . $diff . "</span>";
    } else
        return 0;
}

// Zusammenfassung von für DS 82 -> Einwohner
function resume_82($data)
{
    echo "<h1>Stichtag</h1>";
    echo "<form action=''  name='stichtag' method='get'>";
    echo "<button class=\"btn noprint btn-default\" onclick=\"$('#resume option:selected').next().attr('selected','selected');document.stichtag.submit();\"><i class=\"fa fa-arrow-left\" aria-hidden=\"true\"></i> zurück</button> ";
    echo "<select class='select' id='resume' onchange='document.stichtag.submit()' name='resume'>";
    $sql = "SELECT UNIX_TIMESTAMP(STR_TO_DATE(DATUM,'%d.%m.%Y')) as DATE,DATUM FROM " . $data["mysql_table_name"] . " GROUP BY DATUM ORDER BY DATE DESC";
    $res = mysql_query($sql);
    $highest = 0;

    while ($row = mysql_fetch_array($res)) {

        if ($highest == 0) {
            $highest = $row["DATUM"];
        }

        if ($_GET["resume"] == $row["DATUM"])
            $sel = "selected";
        else
            $sel = "";
        echo "<option " . $sel . " option='" . $row["DATUM"] . "'>" . date("d.m.Y", $row["DATE"]) . "</option>";
    }
    echo "</SELECT><input type=\"hidden\" name='modul' value='resume'> ";
    echo "<button class=\"btn noprint btn-default\" onclick=\"$('#resume option:selected').prev().attr('selected','selected');document.stichtag.submit();\">vor <i class=\"fa fa-arrow-right\" aria-hidden=\"true\"></i></button>";

    echo "</form></span>";

    if (!isset($_GET["resume"]))
        $stichtag = $highest;
    else
        $stichtag = $_GET["resume"];


    $prev_stichtag = "SELECT UNIX_TIMESTAMP(STR_TO_DATE(DATUM,'%d.%m.%Y')) as DATE,DATUM FROM " . $data["mysql_table_name"] . " WHERE  UNIX_TIMESTAMP(STR_TO_DATE(DATUM,'%d.%m.%Y')) < UNIX_TIMESTAMP(STR_TO_DATE('" . $stichtag . "','%d.%m.%Y')) ORDER BY DATE DESC LIMIT 1";
    $data_prev_strichtag = mysql_fetch_array(mysql_query($prev_stichtag));

    $res = mysql_query("SELECT * FROM " . $data["mysql_table_name"] . " WHERE DATUM = '" . $data_prev_strichtag["DATUM"] . "'");
    $data_prev = array();
    while ($row = mysql_fetch_array($res)) {
        $data_prev[$row["BEZEICHNUNG"]] = $row;
    }


    $res = mysql_query("SELECT * FROM " . $data["mysql_table_name"] . " WHERE DATUM = '" . $stichtag . "'");
    $data = array();
    while ($row = mysql_fetch_array($res)) {
        $data[$row["BEZEICHNUNG"]] = $row;
    }

    echo "<table class='table compact no-footer'>
    <thead style='background-color:#eee; font-weight: bold;'><tr>";
    echo "<td colspan='2'>BEZEICHNUNG</td>";
    echo "<td>MÄNNLICH</td>";
    echo "<td>WEIBLICH</td>";
    echo "<td>GESAMT</td>";
    echo "<td>DIFF*</td>";
    echo "</tr></thead>";

    echo "<tbody>";

    $name = "Landkreis Diepholz";
    echo "<tr style='height:10px;font-weight: bold;'>";
    echo "<td colspan='2'>Landkreis Diepholz</td>";
    echo "<td>" . $data[$name]["MAENNLICH"] . "</td>";
    echo "<td>" . $data[$name]["WEIBLICH"] . "</td>";
    echo "<td>" . $data[$name]["GESAMT"] . "</td>";
    echo "<td>" . caldiff($data[$name]["GESAMT"], $data_prev[$name]["GESAMT"]) . "</td>";
    echo "</tr>";

    $res = mysql_query("SELECT * FROM opendata_sde80 ORDER by POS ASC");
    while ($kom = mysql_fetch_array($res)) {
        $name = $kom["NAME"];

        if ($kom["BEZEICHNUNG"] == "Samtgemeinde")
            $style = "style='background-color:#fffeee;font-weight: bold;'";
        else
            $style = "";

        echo "<tr " . $style . ">";
        echo "<td colspan='2'>" . $name . "</td>";
        echo "<td>" . $data[$name]["MAENNLICH"] . "</td>";
        echo "<td>" . $data[$name]["WEIBLICH"] . "</td>";
        echo "<td>" . $data[$name]["GESAMT"] . "</td>";
        echo "<td>" . caldiff($data[$name]["GESAMT"], $data_prev[$name]["GESAMT"]) . "</td>";
        echo "</tr>";

        $res1 = mysql_query("SELECT * FROM opendata_sde79 WHERE KOMMUNE ='" . $name . "' ORDER BY NAME ASC");
        if (mysql_num_rows($res1) != 1) {

            while ($gem = mysql_fetch_array($res1)) {
                $name = $gem["NAME"];
                echo "<tr>";
                echo "<td></td>";
                echo "<td colspan='1'>" . $name . "</td>";
                echo "<td>" . $data[$name]["MAENNLICH"] . "</td>";
                echo "<td>" . $data[$name]["WEIBLICH"] . "</td>";
                echo "<td>" . $data[$name]["GESAMT"] . "</td>";
                echo "<td>" . caldiff($data[$name]["GESAMT"], $data_prev[$name]["GESAMT"]) . "</td>";
                echo "</tr>";
            }
        }

    }
    echo "</tbody>";


    echo "</table>";
    echo "*Differenz wird zum vorherigen Stichtag berechnet. (" . $data_prev_strichtag["DATUM"] . ")";


}

// Zusammenfassung von für DS 82 -> Schülerzahlen
function resume_10($data)
{
    echo "<h1>Stichtag</h1>";
    echo "<form action=''  name='stichtag' method='get'>";
    echo "<button class=\"btn noprint btn-default\" onclick=\"$('#resume option:selected').next().attr('selected','selected');document.stichtag.submit();\"><i class=\"fa fa-arrow-left\" aria-hidden=\"true\"></i> zurück</button> ";
    echo "<select class='select' id='resume' onchange='document.stichtag.submit()' name='resume'>";
    $sql = "SELECT UNIX_TIMESTAMP(STR_TO_DATE(STICHTAG,'%d.%m.%Y')) as DATE,STICHTAG AS DATUM FROM " . $data["mysql_table_name"] . " GROUP BY STICHTAG ORDER BY STICHTAG DESC";
    $res = mysql_query($sql);
    $highest = 0;

    while ($row = mysql_fetch_array($res)) {

        if ($highest == 0) {
            $highest = $row["DATUM"];
        }

        if ($_GET["resume"] == $row["DATUM"])
            $sel = "selected";
        else
            $sel = "";
        echo "<option " . $sel . " option='" . $row["DATUM"] . "'>" . date("d.m.Y", $row["DATE"]) . "</option>";
    }
    echo "</SELECT><input type=\"hidden\" name='modul' value='resume'> ";
    echo "<button class=\"btn noprint btn-default\" onclick=\"$('#resume option:selected').prev().attr('selected','selected');document.stichtag.submit();\">vor <i class=\"fa fa-arrow-right\" aria-hidden=\"true\"></i></button>";

    echo "</form></span>";

    if (!isset($_GET["resume"]))
        $stichtag = $highest;
    else
        $stichtag = $_GET["resume"];

    $prev_stichtag = "SELECT UNIX_TIMESTAMP(STR_TO_DATE(STICHTAG,'%d.%m.%Y')) as DATE,STICHTAG FROM " . $data["mysql_table_name"] . " WHERE  UNIX_TIMESTAMP(STR_TO_DATE(STICHTAG,'%d.%m.%Y')) < UNIX_TIMESTAMP(STR_TO_DATE('" . $stichtag . "','%d.%m.%Y')) ORDER BY DATE DESC LIMIT 1";
    $data_prev_strichtag = mysql_fetch_array(mysql_query($prev_stichtag));

    $daten_landkreis = mysql_fetch_array(mysql_query("SELECT SUM(SCHUELER) as ANZ FROM " . $data["mysql_table_name"] . " WHERE STICHTAG = '" . $stichtag . "'"));
    $daten_landkreis_prev = mysql_fetch_array(mysql_query("SELECT SUM(SCHUELER) as ANZ FROM " . $data["mysql_table_name"] . " WHERE STICHTAG = '" . $data_prev_strichtag["STICHTAG"] . "'"));


    $res_kommune = mysql_query("SELECT SUM(SCHUELER) as ANZ, KOMMUNE FROM " . $data["mysql_table_name"] . " WHERE STICHTAG = '" . $stichtag . "' GROUP BY KOMMUNE");
    $kommune = array();
    while ($row = mysql_fetch_array($res_kommune)) {
        $kommune[$row["KOMMUNE"]] = $row["ANZ"];
    }

    $res_kommune_prev = mysql_query("SELECT SUM(SCHUELER) as ANZ, KOMMUNE FROM " . $data["mysql_table_name"] . " WHERE STICHTAG = '" . $data_prev_strichtag["STICHTAG"] . "' GROUP BY KOMMUNE");
    $kommune_prev = array();
    while ($row = mysql_fetch_array($res_kommune_prev)) {
        $kommune_prev[$row["KOMMUNE"]] = $row["ANZ"];
    }


    $res1 = mysql_query("SELECT * FROM " . $data["mysql_table_name"] . " WHERE STICHTAG = '" . $data_prev_strichtag["STICHTAG"] . "'");
    $schule_prev = array();
    while ($row = mysql_fetch_array($res1)) {
        $schule_prev[$row["IDENT"]] = $row["SCHUELER"];
    }


    echo "<table class='table compact no-footer'>
    <thead style='background-color:#eee; font-weight: bold;'><tr>";
    echo "<td colspan='2'>SCHULE</td>";
    echo "<td>SCHUELER</td>";
    echo "<td>DIFF*</td>";
    echo "</tr></thead>";

    echo "<tbody>";

    $name = "Landkreis Diepholz";
    echo "<tr style='height:10px;font-weight: bold;'>";
    echo "<td colspan='2'>Landkreis Diepholz</td>";
    echo "<td>" . $daten_landkreis["ANZ"] . "</td>";
    echo "<td>" . caldiff($daten_landkreis["ANZ"], $daten_landkreis_prev["ANZ"]) . "</td>";
    echo "</tr>";

    $res = mysql_query("SELECT * FROM opendata_sde80 ORDER by POS ASC");
    while ($kom = mysql_fetch_array($res)) {
        $name = $kom["NAME"];
        $style = "style='background-color:#fffeee;'";

        echo "<tr " . $style . ">";
        echo "<td colspan='2'><b>" . $name . "</b></td>";
        echo "<td>" . $kommune[$name] . "</td>";
        echo "<td>" . caldiff($kommune[$name], $kommune_prev[$name]) . "</td>";
        echo "</tr>";

        $res1 = mysql_query("SELECT * FROM " . $data["mysql_table_name"] . " WHERE KOMMUNE ='" . $name . "' AND STICHTAG = '" . $stichtag . "' ORDER BY IDENT ASC");
        if (mysql_num_rows($res1) != 0) {
            while ($schule = mysql_fetch_array($res1)) {
                echo "<tr>";
                echo "<td></td>";
                echo "<td colspan='1'>" . $schule["SCHULE"] . "</td>";
                echo "<td>" . $schule["SCHUELER"] . "</td>";
                echo "<td>" . caldiff($schule["SCHUELER"], $schule_prev[$schule["IDENT"]]) . "</td>";

                echo "</tr>";
            }
        }

    }
    echo "</tbody>";


    echo "</table>";
    echo "*Differenz wird zum vorherigen Stichtag berechnet. (" . $data_prev_strichtag["STICHTAG"] . ")";

}

// Zusammenfassung von für DS 97 -> Mobilfunkmesswerte
function resume_97($data)
{

    if (!isset($_GET["id"]))
        $plmnid = 26201;
    else
        $plmnid = $_GET["id"];


    echo "<h1>Anzahl der Messungen</h1>";
    echo "<form action='' method='GET'>
    <input type='hidden' name='modul' value='resume'>
    Netzbetreiber: <select name='id' onchange=\"this.form.submit()\">";

    if ($plmnid == "26201")
        $telekom_checked = " selected ";

    if ($plmnid == "26202")
        $vf_checked = " selected ";

    if ($plmnid == "26203")
        $telef_checked = " selected ";

    if ($plmnid == "0")
        $nonet = " selected ";

    echo "
    <option" . $telekom_checked . " value='26201'>Karte 1 Telekom</option>
            <option" . $vf_checked . " value='26202'>Karte 2 Vodafone</option>
            <option" . $telef_checked . " value='26203'>Karte 3 Telefonica (ehem. O2 oder E-Plus)</option>

          </select></form>";
    #      echo "<br><p>HINWEIS: Die Ursache für einen fehlenden Netzempfang könnte beispielsweise der Aufenthalt im Kellergeschoss eines Hauses oder gleichermaßen auch der schlechte Netzausbau eines Mobilfunknetzbetreibers sein. Um Informationen aus der Karte 4 (kein Netz) zu bewerten, schauen Sie bitte parallel auf die Karten 1 bis 3 und vergleichen Sie die angezeigten Informationen. Nach Ende der Mobilfunkumfrage Ende Oktober 2017 wird hier an diese Stelle eine Übersichtskarte aufbereitet und angezeigt, die nicht nur die Anzahl Messungen beinhaltet, sondern die eigentliche Qualität und Netzabdeckung.</p>";
    echo "<div id=\"maparea\">
    <div id=\"mapid\" style=\"margin-top:16px; width: 100%; height: 600px;\"></div>
        <script src=\"/js/leaflet-heat.js\"></script>

    <script>
        var mymap = L.map('mapid', {fullscreenControl: true}).setView([52.7136799, 8.50045], 9);
        var res_id = '" . $data["dkan_res_id"] . "';
        L.tileLayer('https://tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href=\"http://openstreetmap.de\">OpenStreetMap</a> contributors, ' +
                '<a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">CC-BY-SA</a>, '
            }).addTo(mymap);

            function onMapClick(e) {

                console.log( e.latlng.toString());
            }

            var heat = L.heatLayer([
                ";


    $res = mysql_query("SELECT * FROM " . $data["mysql_table_name"] . " WHERE PLMNID = '" . $plmnid . "'");
    echo mysql_error();
    while ($row = mysql_fetch_array($res)) {
        echo "[" . $row["LAT"] . ", " . $row["LON"] . ", 1],";
    }

    // lat, lng, intensity
    echo "], {radius: 7}).addTo(mymap);

            
            mymap.on('click', onMapClick);
            mymap.options.minZoom = 7;
            mymap.options.maxZoom = 14;
            loadGeoJson('kommune.geojson');
            
            
            var geojsoninited = false;
            function loadGeoJson(filename){

        if(filename == false)
        {
            geojson.clearLayers();
            mymap.removeLayer(geojson);
            geojsoninited = false;
        }
        else {
            console.log(\"Loading Layer: \" + filename);
            jQuery.getJSON(\"/\" + filename, function (data) {

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
            weight: 2.5,
            opacity: 1,
            color: 'grey',
            dashArray: '3',
            fillOpacity: 0
        };
    }
    </script>
    ";

}

// Zusammenfassung für DS 99 -> Auswertung Mobilfunk
function resume_name_99()
{
    return "Karte";
}

function resume_99($data)
{
    if (!isset($_GET["heatmap"])) {
        if (!isset($_GET["id"]))
            $plmnid = 26201;
        else
            $plmnid = $_GET["id"];


        echo "<h1>Auswertung der Messergebnisse</h1>";
        echo "<form action='' method='GET'>
    <input type='hidden' name='modul' value='resume'>
    Netzbetreiber: <select name='id' onchange=\"this.form.submit()\">";

        if ($plmnid == "26201")
            $telekom_checked = " selected ";

        if ($plmnid == "26202")
            $vf_checked = " selected ";

        if ($plmnid == "26203")
            $telef_checked = " selected ";

        if ($plmnid == "0")
            $nonet = " selected ";

        echo "
    <option" . $telekom_checked . " value='26201'>Telekom</option>
            <option" . $vf_checked . " value='26202'>Vodafone</option>
            <option" . $telef_checked . " value='26203'>Telefonica (ehem. O2 oder E-Plus)</option>

          </select></form>";
        echo "<div id=\"maparea\">
    <div id=\"mapid\" style=\"margin-top:16px; width: 100%; height: 600px;\"></div>

    <script>
        var mymap = L.map('mapid', {fullscreenControl: true}).setView([52.73962, 8.71628], 11); 
        mymap.spin(true);
        var res_id = '" . $data["dkan_res_id"] . "';
        L.tileLayer('https://tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href=\"http://openstreetmap.de\">OpenStreetMap</a> contributors, ' +
                '<a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">CC-BY-SA</a>, '
            }).addTo(mymap);
        
        
        var overlay = $.getJSON(\"/export/" . $data["dkan_res_id"] . "/shape.geojson?gruppe=" . $plmnid . "\", function (data) {
                defmarker = L.geoJson(data, {style: styleGeoJson ,onEachFeature: onEachFeature});
                mymap.addLayer(defmarker);
               mymap.spin(false);
            });
      
        
         $.getJSON(\"/kommune.geojson\", function (data) {
                    geojsonKommune = L.geoJson(data, {style: styleKreis});
         mymap.addLayer(geojsonKommune);
         
         });
        

    </script>
    ";
    } else {
        if (!isset($_GET["id"]))
            $plmnid = 26201;
        else
            $plmnid = $_GET["id"];


        echo "<h1>Auswertung der Messergebnisse</h1>";
        echo "<form action='' method='GET'>
    <input type='hidden' name='heatmap' value='1'>
    Netzbetreiber: <select name='id' onchange=\"this.form.submit()\">";

        if ($plmnid == "26201")
            $telekom_checked = " selected ";

        if ($plmnid == "26202")
            $vf_checked = " selected ";

        if ($plmnid == "26203")
            $telef_checked = " selected ";

        if ($plmnid == "0")
            $nonet = " selected ";

        echo "
    <option" . $telekom_checked . " value='26201'>Telekom</option>
            <option" . $vf_checked . " value='26202'>Vodafone</option>
            <option" . $telef_checked . " value='26203'>Telefonica (ehem. O2 oder E-Plus)</option>

          </select></form>";
        #      echo "<br><p>HINWEIS: Die Ursache für einen fehlenden Netzempfang könnte beispielsweise der Aufenthalt im Kellergeschoss eines Hauses oder gleichermaßen auch der schlechte Netzausbau eines Mobilfunknetzbetreibers sein. Um Informationen aus der Karte 4 (kein Netz) zu bewerten, schauen Sie bitte parallel auf die Karten 1 bis 3 und vergleichen Sie die angezeigten Informationen. Nach Ende der Mobilfunkumfrage Ende Oktober 2017 wird hier an diese Stelle eine Übersichtskarte aufbereitet und angezeigt, die nicht nur die Anzahl Messungen beinhaltet, sondern die eigentliche Qualität und Netzabdeckung.</p>";
        echo "<div id=\"maparea\">
    <div id=\"mapid\" style=\"margin-top:16px; width: 100%; height: 600px;\"></div>
        <script src=\"/js/leaflet-heat.js\"></script>

    <script>
        var mymap = L.map('mapid', {fullscreenControl: true}).setView([52.7136799, 8.50045], 11);
        var res_id = '" . $data["dkan_res_id"] . "';
        L.tileLayer('https://tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href=\"http://openstreetmap.de\">OpenStreetMap</a> contributors, ' +
                '<a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">CC-BY-SA</a>, '
            }).addTo(mymap);

            function onMapClick(e) {

                console.log( e.latlng.toString());
            }

            var heat = L.heatLayer([
                ";

        $res = mysql_query("SELECT * FROM opendata_sde99 WHERE  `2G-".$plmnid."-ASU` > 0 OR `3G-".$plmnid."-ASU` > 0 OR `4G-".$plmnid."-ASU` > 0");
        while ($row = mysql_fetch_array($res)) {
        $asu = $row["4G-".$plmnid."-ASU"];
        if($asu > 0)
        {
            if($asu <= 30)
            {
                $lvl = 1;
            }

            if($asu > 30 AND $asu <= 47)
            {
                $lvl = 2;
            }

            if($asu > 48 AND $asu <= 55)
            {
                $lvl = 3;
            }

            if($asu > 55 AND $asu <= 60)
            {
                $lvl = 4;
            }

            if($asu > 60)
            {
                $lvl = 5;
            }
        }
        else
        {
            $lvl = 0;
        }
            echo "[" . $row["LAT"] . ", " . $row["LON"] . ", ".$lvl."],";
        }
        // lat, lng, intensity
        echo "], {
            radius: 10,
            max:5,
            gradient:{
            0.4: 'red',
            0.6: 'yellow', 
            0.8: 'green'},
            minOpacity: 0.1,
            blur:5,
            }).addTo(mymap);

            
            mymap.on('click', onMapClick);
            mymap.options.minZoom = 7;
            mymap.options.maxZoom = 14;
            loadGeoJson('kommune.geojson');
            
            
            var geojsoninited = false;
            function loadGeoJson(filename){

        if(filename == false)
        {
            geojson.clearLayers();
            mymap.removeLayer(geojson);
            geojsoninited = false;
        }
        else {
            console.log(\"Loading Layer: \" + filename);
            jQuery.getJSON(\"/\" + filename, function (data) {

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
            weight: 2.5,
            opacity: 1,
            color: 'grey',
            dashArray: '3',
            fillOpacity: 0
        };
    }
    </script>
    ";
    }
}

// Zusammenfassung für DS 104 -> Auswertung Mobilfunk Heatmap mit 3G
function resume_104($data)
{
    if (!isset($_GET["heatmap"])) {
        if (!isset($_GET["id"]))
            $plmnid = 26201;
        else
            $plmnid = $_GET["id"];


        echo "<h1>Auswertung der Messergebnisse</h1>";
        echo "<form action='' method='GET'>
    <input type='hidden' name='modul' value='resume'>
    Netzbetreiber: <select name='id' onchange=\"this.form.submit()\">";

        if ($plmnid == "26201")
            $telekom_checked = " selected ";

        if ($plmnid == "26202")
            $vf_checked = " selected ";

        if ($plmnid == "26203")
            $telef_checked = " selected ";

        if ($plmnid == "0")
            $nonet = " selected ";

        echo "
    <option" . $telekom_checked . " value='26201'>Telekom</option>
            <option" . $vf_checked . " value='26202'>Vodafone</option>
            <option" . $telef_checked . " value='26203'>Telefonica (ehem. O2 oder E-Plus)</option>

          </select></form>";
        echo "<div id=\"maparea\">
    <div id=\"mapid\" style=\"margin-top:16px; width: 100%; height: 600px;\"></div>

    <script>
        var mymap = L.map('mapid', {fullscreenControl: true}).setView([52.73962, 8.71628], 11); 
        mymap.spin(true);
        var res_id = '" . $data["dkan_res_id"] . "';
        L.tileLayer('https://tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href=\"http://openstreetmap.de\">OpenStreetMap</a> contributors, ' +
                '<a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">CC-BY-SA</a>, '
            }).addTo(mymap);
        
        
        var overlay = $.getJSON(\"/export/" . $data["dkan_res_id"] . "/shape.geojson?gruppe=" . $plmnid . "\", function (data) {
                defmarker = L.geoJson(data, {style: styleGeoJson ,onEachFeature: onEachFeature});
                mymap.addLayer(defmarker);
               mymap.spin(false);
            });
      
        
         $.getJSON(\"/kommune.geojson\", function (data) {
                    geojsonKommune = L.geoJson(data, {style: styleKreis});
         mymap.addLayer(geojsonKommune);
         
         });
        

    </script>
    ";
    } else {
        if (!isset($_GET["id"]))
            $plmnid = 26201;
        else
            $plmnid = $_GET["id"];


        echo "<h1>Auswertung der Messergebnisse</h1>";
        echo "<form action='' method='GET'>
    <input type='hidden' name='heatmap' value='1'>
    Netzbetreiber: <select name='id' onchange=\"this.form.submit()\">";

        if ($plmnid == "26201")
            $telekom_checked = " selected ";

        if ($plmnid == "26202")
            $vf_checked = " selected ";

        if ($plmnid == "26203")
            $telef_checked = " selected ";

        if ($plmnid == "0")
            $nonet = " selected ";

        echo "
    <option" . $telekom_checked . " value='26201'>Telekom</option>
            <option" . $vf_checked . " value='26202'>Vodafone</option>
            <option" . $telef_checked . " value='26203'>Telefonica (ehem. O2 oder E-Plus)</option>

          </select></form>";
        #      echo "<br><p>HINWEIS: Die Ursache für einen fehlenden Netzempfang könnte beispielsweise der Aufenthalt im Kellergeschoss eines Hauses oder gleichermaßen auch der schlechte Netzausbau eines Mobilfunknetzbetreibers sein. Um Informationen aus der Karte 4 (kein Netz) zu bewerten, schauen Sie bitte parallel auf die Karten 1 bis 3 und vergleichen Sie die angezeigten Informationen. Nach Ende der Mobilfunkumfrage Ende Oktober 2017 wird hier an diese Stelle eine Übersichtskarte aufbereitet und angezeigt, die nicht nur die Anzahl Messungen beinhaltet, sondern die eigentliche Qualität und Netzabdeckung.</p>";
        echo "<div id=\"maparea\">
    <div id=\"mapid\" style=\"margin-top:16px; width: 100%; height: 600px;\"></div>
        <script src=\"/js/leaflet-heat.js\"></script>

    <script>
        var mymap = L.map('mapid', {fullscreenControl: true}).setView([52.7136799, 8.50045], 11);
        var res_id = '" . $data["dkan_res_id"] . "';
        L.tileLayer('https://tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href=\"http://openstreetmap.de\">OpenStreetMap</a> contributors, ' +
                '<a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">CC-BY-SA</a>, '
            }).addTo(mymap);

            function onMapClick(e) {

                console.log( e.latlng.toString());
            }

            var heat = L.heatLayer([
                ";

        $res = mysql_query("SELECT * FROM opendata_sde99");
        while ($row = mysql_fetch_array($res)) {

            $lvl = 0;
            $asu = $row["4G-".$plmnid."-ASU"];
            if($asu > 0)
            {
                if($asu <= 30)
                {
                    $lvl = 1;
                }

                if($asu > 30 AND $asu <= 47)
                {
                    $lvl = 2;
                }

                if($asu > 48 AND $asu <= 55)
                {
                    $lvl = 3;
                }

                if($asu > 55 AND $asu <= 60)
                {
                    $lvl = 4;
                }

                if($asu > 60)
                {

                    $lvl = 5;
                }
            }
            else
            {
                if("3G-".$plmnid."-ASU" > 11)
                    $lvl = 5;
                //if("3G-".$plmnid."-ASU" > 15)
                    //$lvl = 4;
                //if("3G-".$plmnid."-ASU" > 24)
                  //  $lvl = 5;

            }
            echo "[" . $row["LAT"] . ", " . $row["LON"] . ", ".$lvl."],";
        }
        // lat, lng, intensity
        echo "], {
            radius: 10,
            max:5,
            gradient:{
            0.4: 'red',
            0.6: 'yellow', 
            0.8: 'green'},
            minOpacity: 0.1,
            blur:5,
            }).addTo(mymap);

            
            mymap.on('click', onMapClick);
            mymap.options.minZoom = 7;
            mymap.options.maxZoom = 14;
            loadGeoJson('kommune.geojson');
            
            
            var geojsoninited = false;
            function loadGeoJson(filename){

        if(filename == false)
        {
            geojson.clearLayers();
            mymap.removeLayer(geojson);
            geojsoninited = false;
        }
        else {
            console.log(\"Loading Layer: \" + filename);
            jQuery.getJSON(\"/\" + filename, function (data) {

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
            weight: 2.5,
            opacity: 1,
            color: 'grey',
            dashArray: '3',
            fillOpacity: 0
        };
    }
    </script>
    ";
    }
}

