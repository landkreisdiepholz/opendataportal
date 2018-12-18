<?php
/**
 * Created by d12hanse on 26.01.2017.
 */

include("portal/functions/_loader.php");
$res_id = $_GET["ressource"];
$datensatz = mysql_fetch_array(mysql_query("SELECT * FROM datensaetze WHERE dkan_res_id = '".$res_id."'"));
$res_diagramme = mysql_query("SELECT * FROM datensaetze_diagrams WHERE datensatz_id = '".$datensatz["id"]."'");

$dg =array();
$dk = array();


/* ZUFALLSDATEN */
$x = 0;
$anz = rand(5,20);
$data = array();
while($x < $anz)
{
    //  { y: 21, label: "21%", indexLabel: "Video" },
    $wert = rand(1,100);
    $data[] = array("y" => $wert, "label" => $wert."%","indexLabel" => "Zufall Wert ".$wert,"legendText" => "Zufall Wert ".$wert);
    $x++;
}
/* END ZUFALLSDATEN*/

while ($diagram = mysql_fetch_array($res_diagramme))
{
    /* LOOP FOR DIAGRAM */
    $dk[] = array("name" => $diagram["name"],"id" => "dia_".$diagram["diagram_id"]);
    $res_grafik = mysql_query("SELECT * FROM diagrams_grafiken WHERE diagram_id = '".$diagram["diagram_id"]."'");

    while ($grafik = mysql_fetch_array($res_grafik))
    {
        /* LOOP FOR GRAFIK */
        $dg["dia_".$diagram["diagram_id"]]["grafik_".$grafik["grafik_id"]] = array(
            "type" => $grafik["type"],
            "name" =>  $grafik["name"],
            "id" => "grafik_".$grafik["grafik_id"],
            "hoehe" => $grafik["hoehe"],
            "infotext" => $grafik["beschreibung"],
            "data" => visu_get_data_json($grafik["grafik_id"]));
        /* END LOOP FOR GRAFIK */
    }
    /* END LOOP FOR DIAGRAM */
}


/* ########### */

$res = array("modul_karte" => 1,"modul_diagramm" => 1,"diagram_config" => $dk);



echo json_encode(array_merge($res,$dg));
#echo "<pre>";
#print_r(array_merge($res,$dg));
?>