<?php


ini_set("display_errors","0");
include("functions/mysql.php");

if(isset($_POST["key"]) AND isset($_FILES["file"]))
{
    $file_hash = md5_file($_FILES["file"]["tmp_name"]);
    $filename = $_FILES["file"]["tmp_name"];
    $key = $file_hash.md5(SYNC_KEY);
    if($key == $_POST["key"])
    {
        $cmd = file_get_contents($_FILES["file"]["tmp_name"]);
        exec("mysql -u ".MYSQL_USER_RW." -p".MYSQL_PASSWORD." -D ".MYSQL_DATABASE." < ".$_FILES["file"]["tmp_name"]);
        exec("rm /tmp/ODC_*");
    }
}
?>