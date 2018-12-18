<?php

if($page->anzahl_datensaetze == 1)
    $wording = "Datensatz";
else
    $wording = "Datensätze";

?>
<div id="main" class="main container">

    <h2 class="element-invisible">Sie sind hier</h2>
    <div class="breadcrumb">
        <span class="inline odd first">
            <a href="/">Startseite</a>
        </span>
        <span class="delimiter">»</span>
        <span class="inline even last">Gruppen</span>
    </div>

    <div class="main-row">

        <div class="container">
            <div class="inside"><div class="panel-pane pane-views-panes pane-dkan-topics-featured-panel-pane-1">
                    <div class="pane-content">
                        <div class="startheaderblock"><a href="/datensaetze"><?php echo $wording?>: <?php echo $page->anzahl_datensaetze; ?></a></div>

                        <div class="view view-dkan-topics-featured view-id-dkan_topics_featured ">

                            <div class="view-content">
                                <div class="views-responsive-grid views-responsive-grid-horizontal views-columns-6">

                                    <div class="row container-12 views-row-1 views-row-first">
                                        <?php
                                        gruppen_generate_big_list();
                                        ?>
                                    </div>
                                </div>






                            </div>  </div>


                    </div>
                </div>
            </div>

    </div>

</div>