<?php
/**
 * Created by PhpStorm.
 * User: d12hanse
 * Date: 10.02.2017
 * Time: 15:24
 */

class page{
    var $fullwidth = false;
    var $request_module = "";
    var $org_req;
    var $anzahl_datensaetze;
    var $request_filter = "";
    var $request_id = "";

    var $is_api = false;
    var $is_download = false;
    var $parts;
    function page()
    {

        if (isset($_GET["module"])) {

            $this->parts = explode("/", $_GET["module"]);

            $this->request_module = $this->parts[0];

            if (isset($this->parts[1]))
                $this->request_filter = $this->parts[1];

            if (isset($this->parts[2]))
                $this->request_id = $this->parts[2];

            $this->org_req = $_GET["module"];

            if ($this->parts[0] == "api") {
                $toload = "api.php";
                $this->is_api = true;
            }
            if ($this->parts[0] == "export") {
                $toload = "export.php";
                $this->is_api = true;
            }

            if ($this->parts[0] == "download") {
                $this->is_api = true;
                $toload = "download.php";
            }

        } else
            $this->request_module = "home";

        if ($this->request_module == "home") {
            $this->fullwidth = true;
        }

        if ($this->is_api == false) {
            if (!file_exists("show/" . $this->request_module . ".php")) {
                $this->request_module = "404";
                header("HTTP/1.0 404 Not Found");
            }

            $data = mysql_fetch_array(mysql_query("SELECT count(*) as anz FROM datensaetze WHERE released = 1"));
            $this->anzahl_datensaetze = $data["anz"];
        } else {
            include("show/".$toload);
        }
    }
}