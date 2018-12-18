<div id="datatable">
    <div class=\"table-responsive\">

        <table id='tabelle' width="1140px" class='table compact table-striped dt-responsive'>
            <thead>
            <tr>
                <?php
                $res = mysql_query("SELECT *  FROM ".$ressource["mysql_table_name"]);
                if(mysql_num_rows($res) > 0) {

                    $row = mysql_fetch_array($res, MYSQL_ASSOC);
                    foreach ($row as $key => $value) {
                        $fields[] = $key;

                        if ($key != "fid") {
                            echo "<th>";
                            echo $key;
                            echo "</th>";
                        }
                        else
                            echo "<th>ID</th>";
                    }
                }
                else

                    echo "Diese Ressource enthällt derzeit noch keine Daten!";
                ?>
            </tr>
            </thead>

            <tbody>
            <?php
            mysql_data_seek ($res,0);
            while($row = mysql_fetch_array($res,MYSQL_ASSOC)) {
                echo "<tr>";

                foreach ($fields as $key => $fied) {
                    $type = row_get_format($ressource["ressource_id"],$fied);
                   if(strtoupper($fied) != "LAT" AND strtoupper($fied) != "LON") {
                       if ($type == "FLOAT") {
                           $row[$fied] = str_replace(".", ",", $row[$fied]);
                       }
                       else {
                           if ($type == "DOUBLE") {
                               $row[$fied] = str_replace(".", ",", round((double)$row[$fied], 2));
                           }
                           else
                           {
                               $url = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';

                               $string= preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>',  $row[$fied]);
                               $row[$fied] = $string;

                           }
                       }
                   }

                    echo "<td>";
                    echo $row[$fied];
                    echo "</td>";
                }
                echo "</tr>";
            }
            echo "</tbody>
                </table>
                </div>";

            ?>

            <script>
                var Dtabelle;
                $(document).ready(function() {
                    Dtabelle = $('#tabelle').dataTable( {

                        "fnInitComplete": function(oSettings, json) {
                            add_btn();
                            $('select[name=tabelle_length]').addClass("form-control");

                        },
                        "language": {
                            "url": "/js/german.json"
                        },
                        "columnDefs": [
                        <?php
                                $x = 0;
                                foreach($fields as $header) {
                                    $hide = false;

                                        if($ressource["datenquelle"] == "SDE") {
                                        $row_dat = mysql_fetch_array(mysql_query("SELECT * FROM sde_import_col WHERE ressource_id = '" . $ressource["ressource_id"] . "' AND  name = '".$header."' ORDER BY position"));
                                        if($row_dat["hide_on_preview"] == "1")
                                            $hide = true;
                                         }

                                    if($ressource["datenquelle"] == "MYSQL") {
                                        $row_dat = mysql_fetch_array(mysql_query("SELECT * FROM datenquelle_mysql_schema WHERE ressource_id = '" . $ressource["ressource_id"]."' AND  name = '".$header."' ORDER BY position"));
                                        if($row_dat["hide_on_preview"] == "1")
                                            $hide = true;
                                    }

                                        if($hide == true) {
                                            $ws [] = "{
                                        \"targets\": [ " . $x . " ],
                                        \"visible\": false,
                                        \"searchable\": true
                                        }";
                                        }


                                    $x++;
                                }
                                echo implode(",",$ws);
                        ?>
                        ],
                        "autoWidth": true,
                        "pageLength": 50,
                        "lengthMenu": [ [10, 25, 50,100,200,500,1000, -1], [10, 25, 50, 100,200,500,1000, "Alle"] ]
                    });
                });


                function toggleTableRow(rowid)
                {
                    var table = $('#tabelle').DataTable();
                    // Get the column API object
                    var column = table.column(rowid);

                    console.log(column.visible());

                    if(!column.visible()) {
                        console.log("Enable");
                        $('#table_status_icon_'+rowid).attr("src","/images/accept.png");
                    }
                    else{
                        console.log("Disable");
                        $('#table_status_icon_'+rowid).attr("src","/images/delete.png");

                    }
                    // Toggle the visibility
                    column.visible( ! column.visible() );
                }

                var toggletablewidth = 0;
                function toggle_table_width()
                {
                    if(toggletablewidth == 0)
                    {
                        $('#tabelle').css('white-space','nowrap');
                        toggletablewidth = 1;
                    }
                    else
                    {
                        $('#tabelle').css('white-space','normal');
                        toggletablewidth = 0;
                    }

                }

                function add_btn()
                {
                    $('#tabelle_wrapper').prepend("<a class='btn btn-default tableaddbtn dropdown-toggle' id='feldereinundausblendenbutton' onclick='toggle_table_width(); return false;' href='#'>" +
                        "<span class='fa fa-arrow-left'></span> Tabellenbreite <span class='fa fa-arrow-right'></span>");

                    $('#tabelle_wrapper').prepend("<div class='dropdown tableaddbtn keep-open' id='myDropdown'>"+
                        "<a class='btn btn-default dropdown-toggle' id='feldereinundausblendenbutton' href='#'>" +
                        "Felder ein-/ausblenden &nbsp;"+
                        "<span class='caret'></span>"+
                        "</a>"+
                    "<ul class='dropdown-menu'>"+

                            <?php
                            foreach($coldata as $row)
                            {
                                if($row["show"] == "1")
                                    $img = "/images/delete.png";
                                else
                                    $img = "/images/accept.png";

                              echo "\"<li><a onclick='toggleTableRow(".$row["id"].");'><img id='table_status_icon_".$row["id"]."' src='".$img."'> ".$row["name"]."</a></li>\"+";
                            }
                            ?>
                        "</ul>"+
                    "</div>");

                    $('#myDropdown a').on('click', function (event) {
                        $(this).parent().toggleClass('open');
                        return false;
                    });

                    $('body').on('click', function (e) {
                        console.log(e.target);
                        if (!$('#myDropdown').is(e.target)
                            && $('#myDropdown').has(e.target).length === 0
                            && $('.open').has(e.target).length === 0
                        ) {
                            $('#myDropdown').removeClass('open');
                        }
                    });


                }
            </script>
    </div>
</div>

