<?php

$key = "Blumenwiese12345";
ini_set("display_errors","0");
include("functions/mysql.php");

if(isset($_POST["key"]) AND isset($_FILES["file"]))
{
    $file_hash = md5_file($_FILES["file"]["tmp_name"]);
    $filename = $_FILES["file"]["tmp_name"];
    $key = $file_hash.md5($key);
    if($key == $_POST["key"])
    {
        file_put_contents("files/".$_FILES["file"]["name"],file_get_contents($filename));
    }
}
?>