<?php

$datensatz = mysql_fetch_array(mysql_query("SELECT * FROM datensaetze WHERE url = '".$page->request_filter."'"));

$name = $datensatz["name"];
$url = "/datensatz/".$datensatz["url"];
$url_name = $datensatz["url"];

?>

<div id="main" class="main container">

    <h2 class="element-invisible">Sie sind hier</h2>
    <div class="breadcrumb">
        <span class="inline odd first"><a href="/">Startseite</a></span>
        <span class="delimiter">»</span>
        <span class="inline even"><a href="/datensaetze">Datensätze</a></span>
        <span class="delimiter">»</span>
        <span class="inline odd last"><?php echo $name?></span>
    </div>

    <div class="main-row">

        <section>

            <div class="region region-content">
                <article class="node node-dataset clearfix" about="<? echo $url?>" typeof="sioc:Item foaf:Document">

                    <div class="content">
                            <div class="field-items">
                                <div class="field-item even" property="content:encoded"><h2><?php echo $name?></h2>
                                    <p><?php
                                        if(strlen($datensatz["beschreibung_lang"])== 0)
                                            echo $datensatz["beschreibung_kurz"];
                                        else
                                            echo $datensatz["beschreibung_lang"];
                                        ?></p>
                                </div>
                            </div>

                        <div id="data-and-resources"><h4>Daten und Ressourcen</h4>
                            <div property="dcat:distribution">
                                <div class="item-list">
                                    <ul class="resource-list">

                                        <?php
                                        _datensatz_get_ressources_detailes($datensatz["id"]);
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                <div class="field field-name-field-tags field-type-taxonomy-term-reference field-label-hidden compact-form-wrapper">
                        <div class="field-items">

                            <?php
                            $res = mysql_query("SELECT * FROM datensaetze as ds, datensaetze_tags as dt WHERE dt.datensatz_id = ds.id  AND ds.released = 1 AND dt.tag_id IN 
                                              (SELECT dt.tag_id FROM `datensaetze` as d, datensaetze_tags as dt,tags as t WHERE t.tag_id = dt.tag_id AND t.linked = 0 AND d.id = ".$datensatz["id"]." AND dt.datensatz_id = d.id)
                                              ORDER BY ds.name ASC");
                            echo mysql_error();
                            while($row = mysql_fetch_array($res))
                            {
                                    echo "<div class=\"field-item even\">
                                        <a href=\"/datensatz/" . $row["url"] . "\" typeof=\"skos:Concept\" property=\"rdfs:label skos:prefLabel\" datatype=\"\">" . $row["name"] . "</a>
                                      </div>";
                            }
                            ?>
                        </div>
                </div>
    <br>
                        <section class="field-group-table group_additional">
                            <table class="field-group-format group_additional">
                                <thead>
                                <tr>
                                    <th>Datensatzinfo</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr>
                                    <th class="field-label">Änderungsdatum</th>
                                    <td class="field-items">
                                        <div property="dcterms:modified" class="field-name-field-modified-date"><?php
                                            if($datensatz["time_changed"] != 0)
                                              echo date("d.m.Y",$datensatz["time_changed"]);
                                            else
                                              echo "-";
                                            ?></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="field-label">Erstelldatum</th>
                                    <td class="field-items"><div property="dcterms:issued" class="field-name-field-release-date"><?php echo date("d.m.Y",$datensatz["time_released"])?></div>
                                    </td>
                                </tr>

                                <tr>
                                    <th class="field-label">Lizenz</th>
                                    <td class="field-items">
                                        <div class="field field-name-field-license field-type-text field-label-hidden"><div class="field-items">
                                                <div class="field-item even" property="dc:license"><?php echo lizenz_get($datensatz["license_id"])?></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width: 300px;" class="field-label">Veröffentlicht von</th>
                                    <td class="field-items">
                                        <div class="field field-name-og-group-ref field-type-entityreference field-label-hidden">
                                            <div class="field-items">
                                                <div class="field-item even" property="dc:publisher"><?php echo author_get ($datensatz["author_id"])?></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </section>
                        <?php



                        if(strlen($datensatz["userinfo"]) > 0) {
                            $pfleger = json_decode($datensatz["userinfo"]);
                            $x = 1;
                            foreach ($pfleger as $user) {
                                    $title = "Ansprechpartner";

                                ?>
                                <section class="field-group-table group_additional">
                                    <table class="field-group-format group_additional">
                                        <thead>
                                        <tr>
                                            <th><?php echo $title?></th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach($user as $key => $value) {
                                            ?>
                                            <tr>
                                                <th style="width: 300px;" class="field-label"><?php echo $key?></th>
                                                <td class="field-items">
                                                    <div class="field field-name-og-group-ref field-type-entityreference field-label-hidden">
                                                        <div class="field-items">
                                                            <div class="field-item even"
                                                                 property="dc:publisher"><?php echo $value ?></div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                            ?>
                                        </tbody>
                                    </table>
                                </section>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </article>
            </div>
        </section>

    </div>

</div>