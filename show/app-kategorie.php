<?php
$kategorie = mysql_fetch_array(mysql_query("SELECT * FROM apps_kategorien WHERE url = '".$page->request_filter."'"));
?>

<div id="main" class="main container">

    <h2 class="element-invisible">Sie sind hier</h2>
    <div class="breadcrumb">
        <span class="inline odd first">
            <a href="/">Startseite</a>
        </span>
        <span class="delimiter">»</span>
        <span class="inline odd first">
            <a href="/apps">Apps</a>
        </span>
        <span class="delimiter">»</span>
        <span class="inline odd first">
            <?php echo $kategorie["name"]?>
        </span>
    </div>

    <div class="main-row">

        <section>
            <a id="main-content"></a>
            <h1 class="page-header"><?php echo $kategorie["name"]?></h1>
            <div class="region region-content">
                <div class="view">
                    <div class="view-content">
                        <div id="beschreibung_kurz"> <?php echo $kategorie["beschreibung"]?>
                            <a href="#" onclick="$('#beschreibung_lang').toggle();$('#beschreibung_kurz').toggle();">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Mehr anzeigen</a>
                        </div>

                        <div style="display:none;" id="beschreibung_lang"> <?php echo $kategorie["beschreibung_lang"]?>
                            <a href="#" onclick="$('#beschreibung_lang').toggle();$('#beschreibung_kurz').toggle();">
                                <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> Weniger anzeigen</a>
                        </div>

                        <div><br>
                            <?php
                            $res = mysql_query("SELECT * FROM apps WHERE kategorie_id = '".$kategorie["apps_kategorien_id"]."'");

                            if(mysql_num_rows($res) == 0)
                            {
                                echo "<b>Es wurden keine Apps gefunden!</b>";
                            }

                            while($row = mysql_fetch_array($res))
                            {
                               ?>
                                <div class="download">
                                    <img class="file_icon" src="/images/icons/map.png">
                                    <p class="download_name"><?php echo $row["name"]?></p>

                                    <?php
                                    if(strlen($row["url_2"]) > 0) {
                                        echo "<div class=\"app_link_kat1\"><a target=\"_blank\" href=\"" . $row["url_2"] . "\">" . $row["url_2_type"] . "</a></div>";
                                    }
                                    if(strlen($row["url_3"]) > 0) {
                                        echo "<div class=\"app_link_kat1\"><a target=\"_blank\" href=\"" . $row["url_3"] . "\">" . $row["url_3_type"] . "</a></div>";
                                    }
                                    if(strlen($row["url_1"]) > 0) {
                                        echo "<div class=\"app_link_kat1\"><a target=\"_blank\" href=\"" . $row["url_1"] . "\">" . $row["url_1_type"] . "</a></div>";
                                    }
                                    ?>
                                </div>
                                <br id="download_end">
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>