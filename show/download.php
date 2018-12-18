<?php
/**
 * Created by PhpStorm.
 * User: d12hanse
 * Date: 14.02.2017
 * Time: 14:00
 */
header('Content-Type: text/csv');
$parts = explode("/",$_GET["module"]);
$ressource_id = preg_replace("/[^a-zA-Z0-9]+/", "", $parts[1]);
$ressource = @mysql_fetch_array(mysql_query("SELECT * FROM ressource WHERE dkan_res_id = '".$ressource_id."'"),MYSQL_ASSOC);


if(isset($_GET["EXCEL"]))
{
    $filename = 'opendata-EXCEL-'.sonderzeichen($ressource["name"]).'-'.date("Y-m-d",$ressource["time_changed"]).'.csv';
    $excel = true;
}
else
{
    $filename = 'opendata-'.sonderzeichen($ressource["name"]).'-'.date("Y-m-d",$ressource["time_changed"]).'.csv';
    $excel = false;
}


header('Content-Disposition: attachment; filename="'.$filename.'"');
$res = mysql_query("SELECT * FROM ".$ressource["mysql_table_name"]);
$headers = mysql_fetch_array($res,MYSQL_ASSOC);
$r = array();

foreach($headers as $key => $value)
{
    if($key != "fid")
    $r[] = $key;
}

if($excel)
    echo utf8_decode(implode(";",$r)."\n");
else
    echo implode(";",$r)."\n";

mysql_data_seek($res,0);
while($row = mysql_fetch_array($res,MYSQL_ASSOC))
{
    $r = array();
        foreach($row as $key => $value)
        {
            if($key != "fid") {
                if (isfloat($value)) {
                    $value = str_replace(".", ",", $value);
                }


                $r[] = str_replace(";",",",$value);
            }
        }

        if($excel)
            echo utf8_decode(implode(";",$r)."\n");
        else
            echo implode(";",$r)."\n";
}
