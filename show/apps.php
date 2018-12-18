<div id="main" class="main container">

    <h2 class="element-invisible">Sie sind hier</h2><div class="breadcrumb"><span class="inline odd first"><a href="/">Startseite</a></span> <span class="delimiter">»</span> <span class="inline even last">Apps</span></div>

    <div class="main-row">

        <section>
            <a id="main-content"></a>
            <h1 class="page-header">Apps</h1>
            <div class="region region-content">
                <div class="view">



                    <div class="view-content">
                        <div class="">

                            <?php
                            $x = 0;
                            $res = mysql_query("SELECT * FROM apps_kategorien");
                            while($row = mysql_fetch_array($res))
                            {
                                if($x == 0)
                                    echo "<div class=\"apps_kat_row\">";

                                    echo "
                                    <div class=\"app_row_entry\">
                                        <div class=\"app_row_link\">  
                                                <a href=\"/app-kategorie/".$row["url"]."\">".$row["name"]."</a>
                                        </div>
                                        <div class=\"app_row_img\">
                                                <a href=\"/app-kategorie/".$row["url"]."\">
                                                    <img src=\"/images/apps_kategorien_bilder/".$row["apps_kategorien_id"].".png\" alt=\"\">
                                                </a>
                                        </div>
                                        <div class=\"app_row_desc\">
                                                <p>".$row["beschreibung"]."</p>
                                        </div>
                                    </div>";

                                if($x == 2)
                                {
                                    echo "</div>";
                                    $x = 0;
                                }

                                $x++;
                            }
                            ?>

                        </div>
                    </div>






                </div>  </div>
        </section>

    </div>

</div>