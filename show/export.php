<?php
/**
 * Created by PhpStorm.
 * User: d12hanse
 * Date: 18.10.2017
 * Time: 11:25
 */

ini_set("display_errors",1);
$parts = explode("/",$_GET["module"]);
if(count($parts) == 3) {
    if ($parts[2] == "shape.geojson" and $parts["0"] == "export")
    {
        $res_id = $parts["1"];
        $ressource = mysql_fetch_array(mysql_query("SELECT * FROM ressource WHERE dkan_res_id = '".$res_id."'"),MYSQL_ASSOC);

        if(isset($_GET["gruppe"])) {
            if (strlen($_GET["gruppe"]) == 0 OR $_GET["gruppe"] == "undefined")
                $gruppe = false;
            else
                $gruppe = $_GET["gruppe"];
        }
        else
            $gruppe = false;

        if(!isset($_GET["fid"]))
            $fid = false;
        else
            $fid = $_GET["fid"];

        if(!isset($_GET["query"]))
            $query = false;
        else
            $query = $_GET["query"];

        if(!isset($_GET["style"]))
            $style = false;
        else
            $style = $_GET["style"];


        $cachefile = "/tmp/ODC_diagram_".crc32(serialize($_GET));
        if(!file_exists($cachefile) OR file_exists("/demo")) {
            $geojson = get_geojson($ressource, $gruppe, $fid, $query, $style);
            file_put_contents($cachefile,$geojson);
            echo $geojson;
        }
        else {
            $geojson = file_get_contents($cachefile);
            echo $geojson;
        }
    }
}

