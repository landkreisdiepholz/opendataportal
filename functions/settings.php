<?php

$baseurl = "http://dkan.lkdh.intern/";
$basepath = "/var/www/";

$menu_left = array(
    "/" => array("name" => "Startseite","fullwidth" => true),
    "datensaetze" => array ("name" => "Datensätze"),
    "gruppen" => array("name" => "Gruppen"),
    "apps" => array("name" => "Apps")
);
$menu_right = array(
    "impressum" => array("name" => "Impressum")
);


function isfloat($value) {
    // PHP automagically tries to coerce $value to a number
    return is_float($value + 0);
}
