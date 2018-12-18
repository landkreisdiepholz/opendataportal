<?php

$ressource = mysql_fetch_array(mysql_query("SELECT * FROM ressource WHERE dkan_res_id = '".$page->request_filter."'"),MYSQL_ASSOC);
$datensatz = mysql_fetch_array(mysql_query("SELECT * FROM datensaetze WHERE id = '".$ressource["datensatz_id"]."'"),MYSQL_ASSOC);

?>

<div id="main" class="main container">

    <h2 class="element-invisible">Sie sind hier</h2>
    <div class="breadcrumb">
        <span class="inline odd first"><a href="/">Startseite</a></span>
        <span class="delimiter">»</span>
        <span class="inline even"><a href="/datensaetze">Datensätze</a></span>
        <span class="delimiter">»</span>
        <span class="inline odd"><a href="/datensatz/<?php echo $datensatz["url"]?>"><?php echo $datensatz["name"]?></a></span>
        <span class="delimiter">»</span>
        <span class="inline even last"><?php echo $ressource["name"]?></span>
    </div>

    <div class="main-row">

        <section>
            <a id="main-content"></a>
                <a href="/ressource/<?php echo $ressource["dkan_res_id"]?>" class="btn btn-primary"><i class="fa fa-lg fa-eye"></i>&nbsp;Ansicht</a>




                        <?php
                        $res = mysql_query("SELECT r.name as res_name,ds.name as ds_name,r.dkan_res_id FROM datensaetze as ds, datensaetze_tags as dt,ressource as r WHERE dt.datensatz_id = ds.id AND r.datensatz_id = ds.id  AND ds.released = 1 AND dt.tag_id IN 
                                              (SELECT dt.tag_id FROM `datensaetze` as d, datensaetze_tags as dt WHERE d.id = ".$datensatz["id"]." AND dt.datensatz_id = d.id)
                                              GROUP BY r.dkan_res_id ORDER BY ds.url ASC");
                        if(mysql_num_rows($res) > 0)
                        {
                            echo "
                            <div class=\"btn-group\">
                               <a href=\"/datensatz/".$datensatz["url"]."\" class=\"btn btn-default\"><i class=\"fa fa-lg fa-caret-left\"></i>&nbsp;Zurück zum Datensatz</a>
                            <button type=\"button\" class=\"btn btn-default dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                            <span class=\"caret\"></span>
                            <span class=\"sr-only\">Toggle Dropdown</span>
                            </button>
                            <ul class=\"dropdown-menu\">";


                        while($row = mysql_fetch_array($res))
                        {

                            if(isset($_GET["modul"]))
                                $link = "/ressource/".$row["dkan_res_id"]."&modul=".$_GET["modul"];
                            else
                                $link = "/ressource/".$row["dkan_res_id"];

                            if($row["res_name"] != $row["ds_name"])
                            echo "<li><a href=\"".$link."\">".strtoupper($row["ds_name"]).": ".$row["res_name"]."</a></li>";
                            else
                                echo "<li><a href=\"".$link."\">".$row["ds_name"]."</a></li>";

                        }
                        echo " </ul></div>";
                        }
                        else
                        {
                            echo "<a href=\"/datensatz/".$datensatz["url"]."\" class=\"btn btn-default\"><i class=\"fa fa-lg fa-caret-left\"></i>&nbsp;Zurück zum Datensatz</a>";
                        }
                        ?>

                <a  class="btn btn-default" href="/download/<?php echo $ressource["dkan_res_id"]?>?EXCEL=1"><i class="fa fa-lg fa-download"></i> Als Datei herunterladen</a>
                <a  class="btn btn-default" href="/api/action/datastore/search.json?resource_id=<?php echo $ressource["dkan_res_id"]?>"><i class="fa fa-lg fa-flask"></i> Data API</a>
            </ul>

            <div class="region region-content">
                <article class="node node-resource clearfix" about="/ressource/<?php echo $ressource["dkan_res_id"]?>" typeof="sioc:Item foaf:Document">
                <span property="dc:title" content="<?php echo $ressource["name"]?>" class="rdf-meta element-hidden"></span>
                    <div class="content">

                        <script>
                            var listeshown = false;

                            function toggle_liste(){
                                if(!listeshown)
                                {
                                    listeshown = true;
                                    $('#feldliste').slideDown();
                                    $('#feldlistenelembtn').attr('src','/images/minus_var0.png');
                                }
                                else
                                {
                                    listeshown = false;
                                    $('#feldliste').slideUp();
                                    $('#feldlistenelembtn').attr('src','/images/plus_var0.png');

                                }
                                return false;
                            }
                        </script>

                        <div class="field-items">
                                <div class="field-item even" property="content:encoded">
                                </div>
                            </div>

                            <div class="field-items">
                                <div class="field-item even">
                                    <div class="download">
                                        <img class="file_icon" src="/images/icons/daten.png">
                                        <p class="download_name"><?php echo $ressource["name"]?></p>
                                        <?php
                                        if(strlen($ressource["beschreibung"]) > 0)
                                        {
                                            echo "<p>".$ressource["beschreibung"]."</p>";
                                        }
                                        if(strlen($ressource["beschreibung_lang"]) > 0)
                                        {
                                            echo "<p>".$ressource["beschreibung_lang"]."</p>";
                                        }
                                        ?>
                                        <?php
                                        echo "<br><div id='feldlistenelem'><a href='#' class='noprint' onclick=\"toggle_liste();\">Liste der Felder anzeigen</a>
                                            <img  style='float:right;' onclick='toggle_liste();' id='feldlistenelembtn' src='/images/plus_var0.png'>
                                                 <br><div id='feldliste' style='display:none;'>";
                                        echo "Feld: ID</a> | Identifikationsnummer des Datensatzes | Int(32)<br>";
                                        $coldata[] = array("id" => 0,"name" => "ID");


                                        $sw= 1;
                                        if($ressource["datenquelle"] == "SDE") {
                                            $res = mysql_query("SELECT * FROM sde_import_col WHERE ressource_id = '" . $ressource["ressource_id"] . "'");
                                            while ($row = mysql_fetch_array($res)) {
                                                $r = array();

                                                if(strlen($row["beschreibung"]) > 0)
                                                    $r[] = $row["beschreibung"];

                                                $r[] =  $row["db_type"];

                                                $coldata[] = array("id" => $sw++,"name" => strtoupper($row["name"]),"show" => $row["hide_on_preview"]);

                                                    echo "Feld:  ".strtoupper($row["name"]) ."</a> | ". implode(" | ",$r) . "<br>";
                                            }

                                        }

                                        if($ressource["datenquelle"] == "MYSQL") {
                                            $res = mysql_query("SELECT * FROM datenquelle_mysql_schema WHERE ressource_id = '" . $ressource["ressource_id"]."'");
                                            while ($row = mysql_fetch_array($res)) {
                                                $r = array();

                                                if(strlen($row["beschreibung"]) > 0)
                                                    $r[] = $row["beschreibung"];

                                                $r[] =  $row["type"];

                                                $coldata[] = array("id" => $sw++,"name" => strtoupper($row["name"]),"show" => $row["hide_on_preview"]);

                                                 echo "Feld:  ".strtoupper($row["name"]) ."</a> | ". implode(" | ",$r) . "<br>";

                                            }
                                        }
                                         ?>
                                    </div>
                                    </div>
                                    </div>
                                    <br id="download_end">
                                </div>

                                    <span class="data-explorer">
                                        <div id="btn_area">

                                            <?php
                                            $tabelle_enabled = false;
                                            $diagram_enabled = false;
                                            $cluster_enabled = false;
                                            $map_enabled = false;
                                            $resume_enabled = false;

                                            if($ressource["disable_preview_table"] == "0") {
                                                $tabelle_enabled = true;
                                                if ($_GET["modul"] == "tabelle") {
                                                    echo " <a href='?modul=tabelle' class='btn btn-primary active noprint'>Tabelle</a> ";
                                                } else {
                                                    echo " <a href='?modul=tabelle' class='btn btn-primary noprint'>Tabelle</a> ";
                                                }
                                            }
                                            $btn = array();
                                            $res = mysql_query("SELECT * FROM ressource_diagrams WHERE ressource_id ='".$ressource["ressource_id"]."'");
                                            while($row_diagram = mysql_fetch_array($res)) {
                                                $diagram_enabled = true;
                                                if(strlen($row_diagram["name"]) > 0)
                                                    $name = $row_diagram["name"];
                                                else
                                                    $name = "Diagramm";

                                                if($_GET["modul"] == "diagram" AND $_GET["id"] == $row_diagram["diagram_id"])
                                                    echo "<a class='btn btn-primary active noprint' href='?modul=diagram&id=".$row_diagram["diagram_id"]."'>" .  $name. "</a> ";
                                                else
                                                    echo "<a class='btn btn-primary noprint' href='?modul=diagram&id=".$row_diagram["diagram_id"]."'>" .  $name. "</a> ";

                                            }

                                            if($ressource["preview_map_cluster"] == "1") {
                                                $cluster_enabled = true;
                                                if($_GET["modul"] == "map_cluster") {
                                                    echo "<a href='?modul=map_cluster' class='btn btn-primary active noprint'>Cluster</a> ";
                                                }
                                                else
                                                {
                                                    echo "<a href='?modul=map_cluster' class='btn btn-primary noprint'>Cluster</a> ";
                                                }
                                            }
                                            if($ressource["preview_map"] == "1") {
                                                $map_enabled = true;
                                                if($_GET["modul"] == "map") {

                                                    echo "<a href='?modul=map' class='btn btn-primary active noprint'>Karte</a> ";
                                                }
                                                else
                                                {
                                                    echo "<a href='?modul=map' class='btn btn-primary noprint'>Karte</a> ";
                                                }
                                            }

                                            $fnname = "resume_".$ressource["ressource_id"];
                                            if(function_exists($fnname)) {
                                                $resume_enabled = true;


                                                $f1nname = "resume_name_".$ressource["ressource_id"];
                                                if(function_exists($f1nname)) {
                                                    $name = $f1nname();
                                                }
                                                else
                                                {
                                                    $name = "Zusammenfassung";
                                                }

                                                if($_GET["modul"] == "resume") {
                                                    echo "<a href='?modul=resume' class='btn btn-primary active noprint'>".$name."</a>";
                                                }
                                                else
                                                {
                                                    echo "<a href='?modul=resume' class='btn btn-primary noprint'>".$name."</a>";
                                                }
                                            }
                                            else
                                            {
                                                echo "<!-- resume function ".$fnname." not found -->";
                                            }
                                            ?>
                                        </div>

                                        <?php

                                        if(isset($_GET["modul"]))
                                            $incl = $_GET["modul"];
                                        else
                                        {
                                            if($tabelle_enabled)
                                                $incl = "tabelle";
                                            else
                                            {
                                                if($diagram_enabled)
                                                    $incl = "diagram";
                                                else
                                                {
                                                    if($cluster_enabled)
                                                        $incl = "map_cluster";
                                                    else
                                                    {
                                                        if($map_enabled)
                                                            $incl = "map";
                                                        else
                                                        {
                                                            if($resume_enabled)
                                                                $incl = "resume";
                                                            else
                                                            {

                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }


                                        $file = $basedir."/preview/".$incl.".php";
                                        if(file_exists(($file)))
                                        {
                                            include($file);
                                        }
                                        else
                                            echo "file not found!".$file;



                                        ?>

                                    </span>
                    </div>
                </article>
            </div>
        </section>
    </div>
</div>
