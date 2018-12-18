<?php
/**
 * Created by PhpStorm.
 * User: d12hanse
 * Date: 10.02.2017
 * Time: 17:02
 */

function row_get_format($ressource_id,$row)
{
        $row_dat = mysql_fetch_array(mysql_query("SELECT * FROM sde_import_col WHERE ressource_id = '" . $ressource_id . "' AND  name = '".$row."'"));
        if(isset($row_dat["db_type"])) {
            return strtoupper($row_dat["db_type"]);
        }
        else
        {
            $row_dat = mysql_fetch_array(mysql_query("SELECT * FROM datenquelle_mysql_schema WHERE ressource_id = '" . $ressource_id."' AND name = '".strtoupper($row)."'"));
            if(isset($row_dat["type"])) {
                return strtoupper($row_dat["type"]);
            }
            else
                return "";
        }
}

function data_get_table_schema($ressource_id)
{
    $ressource = mysql_fetch_array(mysql_query("SELECT * FROM ressource WHERE ressource_id = '".$ressource_id."'"));
    if($ressource["datenquelle"] == "SDE")
    {
        $res = mysql_query("SELECT * FROM sde_import_col WHERE ressource_id = '".$ressource_id."'");
        while($row = mysql_fetch_array($res,MYSQL_ASSOC))
        {
            $ret[$row["name"]] = $row;
        }
    }
    if($ressource["datenquelle"] == "MYSQL")
    {
        $sql = "SELECT * FROM datenquelle_mysql_schema WHERE ressource_id = '".$ressource_id."'";
        $res = mysql_query($sql);

        while($row = mysql_fetch_array($res,MYSQL_ASSOC))
        {
            $ret[$row["name"]] = $row;
        }
    }

    return $ret;
}


function datensatz_generate_entry($datensatz_array)
{
    echo "<div class=\"view-content\">
            <div class=\"views-row views-row-1 views-row-odd views-row-first\">
             <article class=\"node-search-result row\" xmlns=\"http://www.w3.org/1999/html\">
              <div class=\"col-md-2 col-lg-1 col-xs-2 icon-container\">
               <img src='/images/datensatz.png'>
              </div>
              <div class=\"col-md-10 col-lg-11 col-xs-10 search-result search-result-dataset\">
               <h2 class=\"node-title\">
                <a href=\"/datensatz/".$datensatz_array["url"]."\" title=\"".$datensatz_array["name"]."\">".$datensatz_array["name"]."</a>
               </h2>
               <ul class=\"dataset-list\"></ul>
               <div class=\"node-description\">".$datensatz_array["beschreibung_kurz"]."</div>
               <div class=\"data-and-resources\">
                <div class=\"form-item form-type-item form-group\">
                 <div class=\"item-list\">
                  <ul class=\"resource-list clearfix\">
                   <li class=\"first last\">
                   "._datensatz_get_ressources($datensatz_array["id"],$datensatz_array["url"],$datensatz_array["name"])."
                  </ul>
                 </div>
                </div>
               </div>
              </div>
              </article>
             </div>";
}
function _datensatz_get_ressources($datensatzid,$url,$name)
{
    $api = false;
    $ret = "";
    $res = mysql_query("SELECT type, count(*) as anz FROM ressource WHERE datensatz_id = '".$datensatzid."' GROUP BY type");
    while($row = mysql_fetch_array($res))
    {
        if($row["anz"] > 1)
            $anz = $row["anz"]."x";
        else
            $anz = "";

        $mapping = array(
            "csv" => array("name" => "csv","style" => "csv","api" => 1),
            "www" => array("name" => "www","style" => "pdf"),
            "xml" => array("name" => "xml","style" => "xml"),
            "pdf" => array("name" => "pdf","style" => "htm")
            );

        if($mapping[$row["type"]]["api"] == 1)
            $api = true;

        $ret .= " <span class=\"count-resource\">".$anz."</span>
                <a href=\"/datensatz/".$url."\" class=\"label\" title=\"Ressource: ".$name."\" data-format=\"".$mapping[$row["type"]]["style"]."\">".$mapping[$row["type"]]["name"]."</a>
               </li>";
    }

    if($api)
        $ret = "<a href=\"/datensatz/".$url."\" class=\"label\" title=\"Ressource: ".$name."\" data-format=\"api\">api</a></li>".$ret;

    return $ret;
}

function _datensatz_get_ressources_detailes($datensatz_id)
{
    if(file_exists("/demo"))
    $res = mysql_query("SELECT * FROM ressource WHERE datensatz_id = '".$datensatz_id."' ORDER BY pos_nr ASC");
    else
        $res = mysql_query("SELECT * FROM ressource WHERE datensatz_id = '".$datensatz_id."' AND released = 1 ORDER BY pos_nr ASC");

    if(mysql_num_rows($res) == 0)
    echo "Es wurden keine Ressourcen für diesen Datensatz gefunden!";



    while($row = mysql_fetch_array($res)) {
        if($row["datenquelle"] == "MAN")
        {
            echo "<li>
                    <div property=\"dcat:Distribution\">
                        <a target=\"_blank\" href=\"/". $row["filename"] . "\" class=\"heading\" title=\"" . $row["name"] . "\" property=\"dcat:accessURL\">" . $row["name"] . "
                            <img class='file_icon' src='/images/icons/".$row["type"].".png'>                                              
                        </a>
                        <p class=\"description\"></p>
                        <p style='width:700px;'>" . $row["beschreibung"] . "</p>
                        <p></p>
                            <span class=\"links\">
                                <a target=\"_blank\" href=\"/". $row["filename"] . "\" class=\"btn btn-primary\">
                                    <i class=\"fa fa-download\"></i> Als Datei herunterladen
                                </a>
                            </span>
                    </div>
                  </li>";
        }

        if($row["datenquelle"] == "URL")
        {
            echo "<li>
                    <div property=\"dcat:Distribution\">
                        <a target=\"_blank\" href=\"". $row["url_to_ext"] . "\" class=\"heading\" title=\"" . $row["name"] . "\" property=\"dcat:accessURL\">" . $row["name"] . "
                            <img class='file_icon' src='/images/icons/".$row["type"].".png'>                                              
                        </a>
                        <p class=\"description\"></p>
                        <p style='width:700px;'>" . $row["beschreibung"] . "</p>
                        <p></p>
                            <span class=\"links\">
                                                    <a target=\"_blank\" href=\"". $row["url_to_ext"] . "\" class=\"btn btn-primary\">
                                                        <i class=\"fa fa-external-link\"></i> Externe URL öffnen
                                                        </a>
                                                </span>
                                            </div>
                                        </li>
                                       ";
        }

        if($row["datenquelle"] == "SDE") {
            echo "<li>
                    <div property=\"dcat:Distribution\">
                        <a href=\"/ressource/" . $row["dkan_res_id"] . "\" class=\"heading\" title=\"" . $row["name"] . "\" property=\"dcat:accessURL\">" . $row["name"] . "
                            <img class='file_icon' src='/images/icons/daten.png'>                                              
                        </a>
                        <p class=\"description\"></p>
                        <p style='width:700px;'>" . $row["beschreibung"] . "</p>
                        <p></p>
                            <span class=\"links\">
                               <div class=\"btn-group\"> 
                                <a href=\"/ressource/" .$row["dkan_res_id"] . "\" class=\"btn btn-primary\"><i class=\"fa fa-bar-chart\"></i> Anzeigen</a>
                                <button type=\"button\" class=\"btn btn-primary dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                    <span class=\"caret\"></span>
                                    <span class=\"sr-only\">Toggle Dropdown</span>
                                </button>
                                <ul class=\"dropdown-menu\">
                                ";

                                foreach(get_modules($row) as $key => $value)
                                {
                                    echo " <li><a href=\"/ressource/".$row["dkan_res_id"]."?modul=".$key."\">".$value."</a></li>";
                                }

                                     echo "
                                </ul>
                            </div>
                              
                                <a href=\"/download/" . $row["dkan_res_id"] . "?EXCEL=1\" class=\"btn btn-primary data-link\">
                                    <i class=\"fa fa-download\"></i> Als Datei herunterladen
                                </a>
                        </span>
                    </div>
                   </li>";
        }

        if($row["datenquelle"] == "MYSQL") {
            echo " <li>
                    <div property=\"dcat:Distribution\">
                        <a href=\"/ressource/" . $row["dkan_res_id"] . "\" class=\"heading\" title=\"" . $row["name"] . "\" property=\"dcat:accessURL\">" . $row["name"] . "
                            <img class='file_icon' src='/images/icons/daten.png'>                                              
                        </a>
                        <p class=\"description\"></p>
                        <p style='width:700px;'>" . $row["beschreibung"] . "</p>
                        <p></p>
                            <span class=\"links\">
                            
                            <div class=\"btn-group\"> 
                                <a href=\"/ressource/" . $row["dkan_res_id"] . "\" class=\"btn btn-primary\"><i class=\"fa fa-bar-chart\"></i> Anzeigen</a> 
                                <button type=\"button\" class=\"btn btn-primary dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"> 
                                    <span class=\"caret\"></span> 
                                    <span class=\"sr-only\">Toggle Dropdown</span> 
                                </button> 
                                <ul class=\"dropdown-menu\"> 
                                ";

                                foreach(get_modules($row) as $key => $value)
                                {
                                    echo " <li><a href=\"/ressource/".$row["dkan_res_id"]."?modul=".$key."\">".$value."</a></li>";
                                }

                                     echo "
                                </ul> 
                            </div>
                            
                                   
                                                   <a href=\"/download/" . $row["dkan_res_id"] . "?EXCEL=1\" class=\"btn btn-primary data-link\">
                                                        <i class=\"fa fa-download\"></i> Als Datei herunterladen
                                                   </a>
                                                </span>
                                            </div>
                                        </li>
                                       ";
        }
}
}


function get_modules($ressource)
{

    $ret = array();
    if ($ressource["disable_preview_table"] == "0") {
        $ret["tabelle"] = "Tabelle";
    }

    $res = mysql_query("SELECT * FROM ressource_diagrams WHERE ressource_id ='" . $ressource["ressource_id"] . "'");
    while ($row_diagram = mysql_fetch_array($res)) {
        if (strlen($row_diagram["name"]) > 0)
            $name = $row_diagram["name"];
        else
            $name = "Diagramm";
        $ret["diagram&id=" . $row_diagram["diagram_id"]] = $name;
    }

    if ($ressource["preview_map_cluster"] == "1") {
        $ret["map_cluster"] = "Cluster";
    }

    if ($ressource["preview_map"] == "1") {
        $ret["map"] = "Karte";
    }
    $fnname = "resume_".$ressource["ressource_id"];
    if(function_exists($fnname)) {

        $fnname = "resume_name_".$ressource["ressource_id"];
        if(function_exists($fnname)) {
            $ret["resume"] = $fnname();
        }
        else
        {
            $ret["resume"] = "Zusammenfassung";
        }
    }
    return $ret;
}


function author_get($author_id){
    $dta = mysql_fetch_array(mysql_query("SELECT * FROM author WHERE author_id = '".$author_id."'"));
    if(isset($dta["name"]))
        return $dta["name"];
    else
        return "-";
}
function lizenz_get($lizenz_id){
    $dta = mysql_fetch_array(mysql_query("SELECT * FROM license WHERE license_id = '".$lizenz_id."'"));
    if(isset($dta["name"]))
        return "<a href=\"".$dta["url"]."\">".$dta["name"]."</a>";
    else
        return "-";
}