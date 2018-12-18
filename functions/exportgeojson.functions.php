<?php
/**
 * Created by PhpStorm.
 * User: d12hanse
 * Date: 21.11.2017
 * Time: 14:05
 */


// Export funktion for Auswertung Mobilfunk
function export_geojson_99($data,$ressource)
{
    $res_fid = mysql_query("SELECT * FROM opendata_sde99 WHERE  `2G-".$data."-ASU` > 0 OR `3G-".$data."-ASU` > 0 OR `4G-".$data."-ASU` > 0");

    $geojson = array();
    while($row_fid = mysql_fetch_array($res_fid,MYSQL_ASSOC)) {
        $points = array();
        $prop = array();
        $res = mysql_query("SELECT * FROM shape_coordinate WHERE ressource_id = '" . $ressource["ressource_id"] . "' AND fid = '" . $row_fid["fid"] . "'");
        while ($row = mysql_fetch_array($res,MYSQL_ASSOC)) {
            $points[] = "[" . $row["lon"] . ", " . $row["lat"] . "]";
        }

        foreach($row_fid as $key => $value)
        {
            $prop[] = "\"".$key."\": \"".$value."\"";
        }

        $asu = $row_fid["4G-".$data."-ASU"];
        if($asu > 0)
        {

            $con = "LTE";
            if($asu <= 30)
            {
                $lvl = 1;
                // Rot
                $color = "#ff0000";
            }
            if($asu > 30 AND $asu <= 47)
            {
                $lvl = 2;
                // orange
                $color = "#f0ff00";
            }
            if($asu > 48 AND $asu <= 55)
            {
                $lvl = 3;
                // gelb
                $color = "#a3ff00";
            }
            if($asu > 55 AND $asu <= 60)
            {
                $lvl = 4;
                // gelb
                $color = "#a3ff00";
            }
            if($asu > 60)
            {
                $lvl = 5;
                // grün
                $color = "#00ff00";
            }
        }
        else
        {

            if($row_fid["2G-".$data."-ASU"] > 0)
            {
                $lvl = $row_fid["2G-".$data."-LVL"];
                $con = "2G";
                $asu = $row_fid["2G-".$data."-ASU"];
            }

            if($row_fid["3G-".$data."-ASU"] > 0)
            {
                $lvl = $row_fid["2G-".$data."-LVL"];
                $con = "3G";
                $asu = $row_fid["3G-".$data."-ASU"];

            if($asu <= 10)
                $color = "#ff0000";

            if($asu < 15)
                $color = "#f0ff00";

            if($asu > 15)
                $color = "#a3ff00";

            if($asu > 23)
            $color = "#00ff00";



                $color = "#ff0000";
            }
        }

      #  $banner = "<p><b>Verbindung:</b> ".$con."<br><b>ASU:</b>".$asu."</p>";

        $banner = "<center>".$con."<br>ASU <b>".$asu."</b><img style='width:50px;' src='/images/netz-icons/".$lvl.".png'></center>";

       $prop[] = "\"popup\": \"" . $banner . "\"";
        $prop[] = "\"fill\": \"" . $color . "\"";

        if($value != false) {
            $geojson[] = "
           {
            \"type\": \"Feature\",
            \"properties\": {" . implode(",", $prop) . "},
            \"geometry\": {
                \"type\": \"Polygon\",
                    \"coordinates\": [
                     [
                    " . implode(",", $points) . "
                     ]
                ]
            }
           }
           ";
        }
    }

    return "{
  \"type\": \"FeatureCollection\",
  \"features\": [".implode(",",$geojson)."  ]}";
}