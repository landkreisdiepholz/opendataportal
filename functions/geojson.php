<?php
/**
 * Created by PhpStorm.
 * User: d12hanse
 * Date: 18.10.2017
 * Time: 11:30
 */


function get_geojson($ressource,$groupcol = false,$fid = false,$query = false,$style = false)
{
    $fn = "export_geojson_".$ressource["ressource_id"];
    if(function_exists($fn))
    {
        return $fn($groupcol,$ressource);
    }

    $colorpos = 0;

    if($query)
    {
        $where = " WHERE ".$query;
    }
    else
        $where = "";

    if($fid == false)
        $sql ="SELECT * FROM ".$ressource["mysql_table_name"].$where;
    else
        $sql ="SELECT * FROM ".$ressource["mysql_table_name"]." WHERE fid = '".$fid."'";

    $res_fid = mysql_query($sql);

    $geojson = array();
    while($row_fid = mysql_fetch_array($res_fid,MYSQL_ASSOC)) {

        $res_ring = mysql_query("SELECT ring_id FROM shape_coordinate WHERE ressource_id = '" . $ressource["ressource_id"] . "' AND fid = '" . $row_fid["fid"] . "' GROUP BY ring_id");
        while($ring = mysql_fetch_array($res_ring))
        {


        $points = array();
        $prop = array();

        $sql = "SELECT * FROM shape_coordinate WHERE ressource_id = '" . $ressource["ressource_id"] . "' AND ring_id = '".$ring["ring_id"]."' AND fid = '" . $row_fid["fid"] . "'";
        $res = mysql_query($sql);


        while ($row = mysql_fetch_array($res,MYSQL_ASSOC)) {
            $points[] = "[" . $row["lon"] . ", " . $row["lat"] . "]";
        }

        foreach($row_fid as $key => $value)
        {
            $prop[] = "\"".$key."\": \"".$value."\"";
        }

        $colorscodes = array(
            "#e03616",
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

        if($groupcol)
        {
            if($style)
            {

                if(function_exists("color_".$style))
                {
                    $fn = "color_".$style;
                    $value = $fn($row_fid[$groupcol]);
                }
            }
            else {

                if($colorpos >= (count($colorscodes)-1))
                    $colorpos = 1;

                if (isset($colormaps[$row_fid[$groupcol]])) {
                    $value = $colormaps[$row_fid[$groupcol]];
                } else {
                    $colormaps[$row_fid[$groupcol]] = $colorscodes[$colorpos];
                    $value = $colormaps[$row_fid[$groupcol]];
                    $colorpos++;
                }
            }
            $prop[] = "\"fill\": \"".$value."\"";
        }
        else
            $value = true;

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
    }

    return "{
  \"type\": \"FeatureCollection\",
  \"features\": [".implode(",",$geojson)."  ]}";
}

function color_redtoyellow($value)
{
    if($value == 0)
    return false;

    if($value == 1)
        return "#ff0000";

    if($value == 2)
        return "#ffb400";

    if($value == 3)
        return "#e3ff00";

    if($value == 4)
        return "#00ff00";
}