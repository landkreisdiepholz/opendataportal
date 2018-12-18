<?php


    if(!isset($_GET["id"]))
    {
        $data = mysql_fetch_array(mysql_query("SELECT * FROM ressource_diagrams WHERE ressource_id ='".$ressource["ressource_id"]."' LIMIT 1"));
        $id = $data["diagram_id"];
    }
    else
    $id = (int)$_GET["id"];


    $res = mysql_query("SELECT * FROM diagrams_grafiken WHERE diagram_id = '".$id."' order by reihenfolge ASC");

    $x=1;
    $grafik_id = 0;
    while($row = mysql_fetch_array($res)) {
        if($grafik_id == 0)
        $grafik_id = $row["grafik_id"];

        if(strlen($row["name"]) == 0)
            $name = "Grafik ".$x++;
        else
            $name = $row["name"];

        $subbtns[] = "<a class=\"btn btn-default\" href=\"?modul=diagram&id=".$id."&grafik=".$row["grafik_id"]."\">".$name."</a> ";
    }

    if(count($subbtns) != 1)
    echo "<br>".implode(" ",$subbtns);

    if(isset($_GET["grafik"]))
    {
        $grafik_id = $_GET["grafik"];
    }

$grafik = mysql_fetch_array(mysql_query("SELECT *, gr.name as diname FROM `diagrams_grafiken` as gr,
 ressource_diagrams as dia,
  ressource as re WHERE gr.diagram_id = dia.diagram_id AND dia.ressource_id = re.ressource_id AND gr.grafik_id = '".$grafik_id."'"),MYSQL_ASSOC);

        if($grafik["hoehe"] == 0)
            $hoehe = 500;
        else
            $hoehe = $grafik["hoehe"];

        if(!isset($grafik["breite"]) OR $grafik["breite"] == 0)
            $breite = 1140;
        else
            $breite = $grafik["breite"];


        if(strlen($grafik["dropdown_class"])) {
            $class = $grafik["dropdown_class"];
            $filter = new $class($grafik,$ressource);
            echo $filter->_getSelect();
        }
        else
        {
            // Spacer einbauen wenn kein Dropdown
            $breaker = "<br>";
        }

        if($grafik["dropdown_enable"] == "0")
        {
            $breaker = "<br>";
        }

        if($grafik["type_diagram"] == "bar") {
            echo "<div style='margin-top: 20px;'>";
            echo "<a href='#' class='nodeko' onclick='en_diagram(\"bar\");return false;'><span class=\"glyph-icon flaticon-horizontal-bars-chart\"></span></a>";
            echo "<a href='#' class='nodeko' onclick='en_diagram(\"pie\");return false;'><span class=\"glyph-icon flaticon-pie-chart-stats\"></span></a>";
            echo "<a href='#' class='nodeko' onclick='en_diagram(\"doughnut\");return false;'><span class=\"glyph-icon flaticon-marketing-circular-chart\"></span></a>";
            echo "</div>";
        }
        if(strlen($grafik["titel"]) > 0)
            echo "<hr class='hr-breaker'><h3>".$grafik["titel"]."</h3>";
        else
            echo "<hr class='hr-breaker'><h3>".$grafik["name"]."</h3>";

echo "<div class='grafiks'>";
            echo $grafik["text_oberhalb"];
            echo "<div id='grafik_sub_area".$grafik["grafik_id"]."' style=\"height:".$hoehe."px;width:".$breite."px;\"></div>";
            echo $grafik["text_unterhalb"];
            echo "</div>";
            echo "\n<script>";
            echo "function en_diagram(diagramtype){";
            echo "var chart = new CanvasJS.Chart(\"grafik_sub_area".$grafik["grafik_id"]."\",{".get_chart_config($grafik["grafik_id"])."});";
            echo "chart.render();}";
            echo "en_diagram(\"".$grafik["type_diagram"]."\")";
            echo "</script>";
        echo "</div>";
?>