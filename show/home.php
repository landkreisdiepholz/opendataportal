<div id="main" class="main">



    <div class="main-row">

        <section>
            <a id="main-content"></a>
            <div class="region region-content">
                <div class="panel-display panel-full-width clearfix">

                    <div class="panel-top panel-row" style="background-image:url(/images/earthlightslrg-iloveimg-cropped-1_0.jpg);background-color:transparent">
                        <div class="tint"></div>
                        <div class="container">
                            <div class="panel-col-first" id="welcometext">
                                <div class="inside"><div class="panel-pane pane-block pane-dkan-sitewide-demo-front-dkan-demo-front">
                                        <h2 class="pane-title">Welcome to the DKAN LKDH</h2>
                                        <div class="pane-content">
                                            <p>Alle Informationen des OpenData-Portals können Sie herunterladen und als CSV-Datei (Excel) weiterverarbeiten. Einfache visuelle Auswertungen stehen Ihnen online bereits als Tabelle Diagramm, Cluster oder Karte vorgefertigt zur Verfügung. Professionelle Nutzer können darüber hinaus per Programmierschnittstelle (API) alle Informationen in den Formaten JSON, JSONP oder XML-Format abrufen.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-col-second">
                                <div class="inside"><div class="panel-pane pane-block pane-dkan-sitewide-dkan-sitewide-search-bar">
                                        <div class="pane-content">
                                            <form action="/datensaetze" method="get" id="dkan-sitewide-dataset-search-form" accept-charset="UTF-8" role="form">
                                                <div><div class="form-item form-type-textfield form-item-search form-group">
                                                        <label for="edit-search">Search </label>
                                                        <input placeholder="Suchen" class="form-control form-text" type="text" id="edit-search" name="search" value="" size="30" maxlength="128">
                                                    </div>
                                                    <input type="submit" value="" class="form-submit btn btn-default btn-primary">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-middle panel-row">
                        <div class="container">
                            <div class="inside"><div class="panel-pane pane-views-panes pane-dkan-topics-featured-panel-pane-1">

                                    <div class="startheaderblock"><a href="/datensaetze">Datensätze: <?php echo $page->anzahl_datensaetze; ?></a></div>

                                    <div class="pane-content">
                                        <div class="view view-dkan-topics-featured view-id-dkan_topics_featured view-display-id-panel_pane_1 test-123 view-dom-id-9dc381245c589d3c90d6c15ac49151de">

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
            </div>
        </section>

    </div>

</div>