<?php

if(isset($_GET["search"]))
{
    $search_res = preg_replace("|[^a-zA-Z0-9-äöüÄÜÖß ]|", "", $_GET["search"]);
   # $res = mysql_query("SELECT * FROM `datensaetze`
   #                     WHERE (`name` LIKE '%".$search_res."%'
    #                    OR `beschreibung_kurz` LIKE '%".$search_res."%'
     #                   OR `beschreibung_lang` LIKE '%".$search_res."%')
      #                  AND `released` = 1 ORDER by name ASC");

    $res = mysql_query("SELECT d.* FROM `datensaetze` as d, ressource as r
                        WHERE (d.name LIKE '%".$search_res."%' 
                        OR d.beschreibung_kurz LIKE '%".$search_res."%' 
                        OR d.beschreibung_lang LIKE '%".$search_res."%'
                        OR r.name LIKE '%".$search_res."%' 
                        OR r.beschreibung LIKE '%".$search_res."%' ) 
                        AND d.released = 1 AND r.datensatz_id = d.id GROUP BY d.id ORDER by d.name ASC");
}
else
{
    if($page->request_filter == "tags")
    {
        $res = mysql_query("SELECT da.* FROM `datensaetze_tags` as dtg, datensaetze as da,tags as ta WHERE da.released = 1 AND ta.tag_id = dtg.tag_id AND da.id = dtg.datensatz_id AND ta.url = '".$page->request_id."'");
        echo mysql_error();
    }
    else {
        if ($page->request_filter == "gruppen") {
            $res = mysql_query("SELECT da.* FROM `datensaetze_gruppen` as dgr, datensaetze as da,gruppen as gr WHERE da.released = 1 AND gr.gruppe_id = dgr.gruppen_id AND da.id = dgr.datensatz_id AND gr.url = '" . $page->request_id . "'");
        } else {
            if ($page->request_filter == "type") {

                if($page->request_id  == "api")
                    $search_type = "csv";
                else
                    $search_type = $page->request_id;

                $res = mysql_query("select da.* from datensaetze as da, ressource as r WHERE da.id = r.datensatz_id AND da.released = 1 AND r.type = '" . $search_type . "' GROUP BY r.datensatz_id");
            } else
                $res = mysql_query("SELECT * FROM datensaetze WHERE released = 1 ORDER by name ASC");
        }
    }
}


if(mysql_num_rows($res) == 1)
    $wording = "Datensatz";
else
    $wording = "Datensätze";

?>

<div id="main" class="main container">

    <?php


    if(isset($_GET["search"]))
    {
        echo "<h2 class=\"element-invisible\">Sie sind hier</h2>
                <div class=\"breadcrumb\">
                <span class=\"inline odd first\"><a href=\"/\">Startseite</a></span> 
                <span class=\"delimiter\">»</span> 
                <span class=\"inline even last\"><a href=\"/datensaetze\">".$wording."</a></span>
                <span class=\"delimiter\">»</span> 
                <span class=\"inline even last\">Suchergebnis für: „".$search_res."“</span>

                </div>";
    }
    else
    {
    switch ($page->request_filter) {
        case "gruppen":
            $sel_gruppe = mysql_fetch_array(mysql_query("SELECT * FROM gruppen WHERE url = '".$page->request_id."'"));
            echo "<h2 class=\"element-invisible\">Sie sind hier</h2>
                  <div class=\"breadcrumb\">
                    <span class=\"inline odd first\"><a href=\"/\">Startseite</a></span> 
                    <span class=\"delimiter\">»</span> 
                    <span class=\"inline even last\"><a href=\"/datensaetze\">".$wording."</a></span>
                    <span class=\"delimiter\">»</span> 
                    <span class=\"inline even last\">Gruppe: „".$sel_gruppe["name"]."“</span>
                  </div>          
                ";
            break;
        case "tags":
            $sel_gruppe = mysql_fetch_array(mysql_query("SELECT * FROM tags WHERE url = '".$page->request_id."'"));
            echo "<h2 class=\"element-invisible\">Sie sind hier</h2>
                  <div class=\"breadcrumb\">
                    <span class=\"inline odd first\"><a href=\"/\">Startseite</a></span> 
                    <span class=\"delimiter\">»</span> 
                    <span class=\"inline even last\"><a href=\"/datensaetze\">".$wording."</a></span>
                    <span class=\"delimiter\">»</span> 
                    <span class=\"inline even last\">Tag: „".$sel_gruppe["name"]."“</span>
                  </div>          
                ";
            break;

        case "type":
            echo "<h2 class=\"element-invisible\">Sie sind hier</h2>
                  <div class=\"breadcrumb\">
                    <span class=\"inline odd first\"><a href=\"/\">Startseite</a></span> 
                    <span class=\"delimiter\">»</span> 
                    <span class=\"inline even last\"><a href=\"/datensaetze\">".$wording."</a></span>
                    <span class=\"delimiter\">»</span> 
                    <span class=\"inline even last\">Datei-Format: „".mb_strtoupper($page->request_id)."“</span>
                  </div>          
                ";
            break;

        default:
            echo "<h2 class=\"element-invisible\">Sie sind hier</h2>
                <div class=\"breadcrumb\">
                <span class=\"inline odd first\"><a href=\"/\">Startseite</a></span> 
                <span class=\"delimiter\">»</span> 
                <span class=\"inline even last\">".$wording."</span></div>";
    }



    }
    ?>

    <div class="main-row">

        <section>
            <a id="main-content"></a>
            <div class="region region-content">

                <div class="panel-display bryant clearfix radix-bryant">

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-3 radix-layouts-sidebar panel-panel">
                                <div class="panel-panel-inner">
                                    <section class="block block-facetapi block--gruppen clearfix">

                                        <h2>Gruppen</h2>
                                        <div class="content">
                                            <div class="item-list">

                                                <?php
                                                $res_lic = mysql_query("select count(*) as anz,g.url,g.name,g.gruppe_id,g.icon from gruppen as g, datensaetze_gruppen as dg, datensaetze as da WHERE da.id = dg.datensatz_id AND da.released = 1 AND g.gruppe_id = dg.gruppen_id GROUP BY gruppe_id");
                                                while($row_lic = mysql_fetch_array($res_lic))
                                                {


                                                    if($page->request_filter == "gruppen")
                                                    {
                                                        if($page->request_id == $row_lic["url"])
                                                            $class = "facetapi-active";
                                                        else
                                                            $class = "facetapi-inactive";
                                                    }
                                                    else
                                                        $class = "facetapi-inactive";

                                                    echo "<ul class=\"facetapi-terms facetapi-facet-field-topic facetapi-processed\" id=\"facetapi-facet-search-apidatasets-block-field-topic\">
                                                    <li class=\"leaf first\">
                                                        <a href=\"/datensaetze/gruppen/".$row_lic["url"]."\" rel=\"nofollow\" class=\"".$class."\">
                                                            <div class=\"field field-name-field-topic-icon field-type-font-icon-select-icon field-label-above\">
                                                                <div class=\"field-items\">
                                                                    <div class=\"field-item even\">
                                                                        <p class=\"font-icon-select-1 font-icon-select-1-e".$row_lic["icon"]."\">&nbsp;</p>
                                                                    </div>
                                                                </div>
                                                            </div>".$row_lic["name"]." (".$row_lic["anz"].")
                                                        </a>
                                                    </li>
                                                </ul>";
                                                }
                                                ?>
                                            </div>
                                        </div>

                                    </section>

                                    <section class="block block-facetapi block--gruppen clearfix">

                                        <h2>Tags</h2>
                                        <div class="content">
                                            <div class="item-list">

                                                <?php
                                                $res_lic = mysql_query("select count(*) as anz,g.url,g.name,g.tag_id from tags as g, datensaetze_tags as dg, datensaetze as da WHERE da.id = dg.datensatz_id AND da.released = 1 AND g.tag_id = dg.tag_id AND g.visible = 1 GROUP BY tag_id");
                                            echo mysql_error();
                                                while($row_lic = mysql_fetch_array($res_lic))
                                                {


                                                    if($page->request_filter == "tags")
                                                    {
                                                        if($page->request_id == $row_lic["url"])
                                                            $class = "facetapi-active";
                                                        else
                                                            $class = "facetapi-inactive";
                                                    }
                                                    else
                                                        $class = "facetapi-inactive";

                                                    echo "<ul class=\"facetapi-terms facetapi-facet-field-topic facetapi-processed\" id=\"facetapi-facet-search-apidatasets-block-field-topic\">
                                                    <li class=\"leaf first\">
                                                        <a href=\"/datensaetze/tags/".$row_lic["url"]."\" rel=\"nofollow\" class=\"".$class."\">
                                                            <div class=\"field field-name-field-topic-icon field-type-font-icon-select-icon field-label-above\">
                                                                <div class=\"field-items\">
                                                                    <div class=\"field-item even\">
                                                                    </div>
                                                                </div>
                                                            </div>".$row_lic["name"]." (".$row_lic["anz"].")
                                                        </a>
                                                    </li>
                                                </ul>";
                                                }
                                                ?>
                                            </div>
                                        </div>

                                    </section>

                                    <section  class="block block-facetapi block--format clearfix">

                                        <h2>Format</h2>
                                        <div class="content">
                                            <div class="item-list">
                                                <ul class="facetapi-facetapi-links facetapi-facet-field-resourcesfield-format facetapi-processed" id="facetapi-facet-search-apidatasets-block-field-resourcesfield-format">


                                                    <?php


                                                    if ($page->request_id == "api")
                                                        $class = "facetapi-active";
                                                    else
                                                        $class = "facetapi-inactive";

                                                    $row_lic = mysql_fetch_array(mysql_query("select count(*) as anz , r.type from datensaetze as da, ressource as r WHERE da.id = r.datensatz_id AND da.released = 1 AND r.type='csv'"));

                                                    echo
                                                        "<li class=\"leaf first\">
                                                        <a href=\"/datensaetze/type/api\" rel=\"nofollow\" class=\"".$class."\">api (".$row_lic["anz"].")
                                                            <span class=\"element-invisible\"> Apply api filter </span></a>
                                                    </li>";


                                                $res_lic = mysql_query("select count(*) as anz , r.type from datensaetze as da, ressource as r WHERE da.id = r.datensatz_id AND da.released = 1 group by type");
                                                while($row_lic = mysql_fetch_array($res_lic)) {
                                                    if ($page->request_filter == "type") {
                                                        if ($page->request_id == $row_lic["type"])
                                                            $class = "facetapi-active";
                                                        else
                                                            $class = "facetapi-inactive";
                                                    } else
                                                        $class = "facetapi-inactive";

                                                    echo
                                                    "<li class=\"leaf first\">
                                                        <a href=\"/datensaetze/type/".$row_lic["type"]."\" rel=\"nofollow\" class=\"".$class."\">".$row_lic["type"]." (".$row_lic["anz"].")
                                                            <span class=\"element-invisible\"> Apply csv filter </span></a>
                                                    </li>";


                                                }

                                                    ?>

                                                   </ul>
                                            </div>
                                        </div>

                                    </section>
                                    <section class="block block-facetapi block--lizenz clearfix">
                                        <h2>Lizenz</h2>
                                        <div class="content">
                                            <div class="item-list">
                                                <ul class="facetapi-facetapi-links facetapi-facet-field-license facetapi-processed" id="facetapi-facet-search-apidatasets-block-field-license">
                                                    <?php
                                                    $res_lic = mysql_query("SELECT li.*, count(*) as anz FROM `datensaetze` as da, license as li WHERE da.released = 1 AND da.license_id = li.license_id GROUP BY li.license_id");
                                                    while($row_lic = mysql_fetch_array($res_lic))
                                                    {


                                                        if($page->request_filter == "lizenz")
                                                        {
                                                            if($page->request_id == urlizer($row_lic["name"]))
                                                                $class = "facetapi-active";
                                                            else
                                                                $class = "facetapi-inactive";
                                                        }
                                                        else
                                                            $class = "facetapi-inactive";

                                                        echo " <li class=\"leaf first last\">
                                                                <a href=\"/datensaetze/lizenz/".urlizer($row_lic["name"])."\" rel=\"nofollow\" class=\"".$class."\" id=\"facetapi-link\">".$row_lic["name"]." (".$row_lic["anz"].")
                                                                    <span class=\"element-invisible\">Filter ".$row_lic["name"]." anwenden</span>
                                                                </a>
                                                               </li>";
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                            <div class="col-md-9 radix-layouts-content panel-panel">
                                <div class="panel-panel-inner">
                                    <div class="panel-pane pane-views-panes pane-dkan-datasets-panel-pane-1">

                                        <h2 class="pane-title"><?php echo $wording ?></h2>


                                        <div class="pane-content">
                                            <div class="view view-dkan-datasets view-id-dkan_datasets">
                                                <div class="view-header">
                                                    <?php
                                                    if(isset($_GET["search"])) {
                                                        echo "Suchergebnis für: „" . $search_res . "“ (". mysql_num_rows($res)." Treffer)</div>";
                                                    }
                                                    else
                                                    {
                                                        echo mysql_num_rows($res) . " ".$wording."</div>";
                                                    }

                                                    if(mysql_num_rows($res) == 0)
                                                        echo "Es wurden keine Datensätze gefunden!";

                                                    while($row = mysql_fetch_array($res))
                                                    {
                                                        datensatz_generate_entry($row);
                                                    }
                                                ?>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- /.bryant -->  </div>
        </section>

    </div>

</div>