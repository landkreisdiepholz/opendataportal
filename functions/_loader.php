<?php
/**
 * Created by PhpStorm.
 * User: d12hanse
 * Date: 01.02.2017
 * Time: 17:04
 */

$files = scandir($basedir."/functions");
foreach ($files as $file)
{
    if($file != "." AND $file != ".." AND $file != "_loader.php")
    include($basedir."/functions/".$file);
}