<?php
/**
 * Created by PhpStorm.
 * User: d12hanse
 * Date: 13.02.2017
 * Time: 17:13
 */
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");
$parts = explode("/",$_GET["module"]);
$err = "";
$ressource_id = preg_replace("/[^a-zA-Z0-9]+/", "", $_GET["resource_id"]);
$ressource = @mysql_fetch_array(mysql_query("SELECT * FROM ressource WHERE dkan_res_id = '".$ressource_id."'"),MYSQL_ASSOC);


if(!isset($ressource["mysql_table_name"]))
{
    $err = "resource_id ".$_GET["resource_id"]." unknown!";
}
else
{
    $datenbank_name = $ressource["mysql_table_name"];
}

if(isset($_GET["filter"]))
    $filter = " WHERE ".$_GET["filter"];
else
    $filter = "";

if(isset($_GET["cols"]))
    $cols = $_GET["cols"];
else
    $cols = "*";


if(isset($_GET["offset"]))
    $offset = (int)$_GET["offset"];
else
    $offset = 0;

if(isset($_GET["limit"]))
    $limit = (int)$_GET["limit"];
else
    $limit = 100;

if($limit > 100)
{
    $limit = 100;
}

if($limit == 0)
    $limit = 100;

$records = array();


$res = mysql_query("SELECT ".$cols." FROM ".$datenbank_name." LIMIT 1");
$row = mysql_fetch_array($res,MYSQL_ASSOC);
foreach($row as $key => $value)
{
    $fields[] = $key;
}

$max = @mysql_fetch_array(mysql_query("SELECT count(*) as anz FROM ".$datenbank_name.$filter));

if($limit != "-1")
$res = mysql_query("SELECT ".$cols." FROM ".$datenbank_name.$filter." LIMIT ".$limit." OFFSET ".$offset);
else
$res = mysql_query("SELECT ".$cols." FROM ".$datenbank_name.$filter);

if($res)
{
    $mysq_result = "true";
    $help = "Search a datastore table. :param resource_id: id or alias of the data that is going to be selected.";
}
else
{
    $mysq_result = "false";
    $help = "Internal Server Error!";
}

while($row = @mysql_fetch_array($res,MYSQL_ASSOC))
{
    $records[] = $row;
}


$ret = array(
    "help" => $help." ".$err,
    "success" => $mysq_result,
    "result" => array ("fields" => $fields,
                        "ressource_id" => $ressource_id,
                        "limit" => $limit,
                        "offset" => $offset,
                        "total" => $max["anz"],
                        "records" => $records)
);

if($parts[3] == "search.json")
echo json_encode($ret);

if($parts[3] == "search.xml") {
    $xml = new SimpleXMLElement('<root/>');
    array_walk_recursive($ret, array($xml, 'addChild'));
    print $xml->asXML();
}
