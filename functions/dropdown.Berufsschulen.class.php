<?php
/**
 * Created by PhpStorm.
 * User: d12hanse
 * Date: 01.03.2017
 * Time: 17:04
 */
class DropDownBerufsschulen {

    var $Grafiksettings;
    var $gruppenspalte;
    var $filterspalte;
    var $filtervalue;
    var $selectOptions;
    var $fullcustom = true;
    var $showsumlk = false;

    function custom(){
        if(!isset($_GET["f_val"]) AND !isset($_GET["f_val2"])) {
            $res = mysql_query("SELECT sum(SGESAMT) as ANZ,DATUM FROM ".$this->Grafiksettings["mysql_table_name"]." GROUP BY STR_TO_DATE(DATUM,'%d.%m.%Y');");
            $data = array();
            while($row = mysql_fetch_array($res))
            {
                $split = explode(".",$row["DATUM"]);
                $data[] = "{ x: new Date(".$split[2].",".$split[1].",".$split[0]."), name: \"GESAMT (".$row["DATUM"].")\", label:\"".$row["DATUM"]."\", y: ".$row["ANZ"]." }";
            }


            $ret = "height: 500,
        		width: 1140,
        axisY: {
		     valueFormatString: \"0\"
		},
		axisX: {
		},
		 toolTip: {
        shared: true
      },
 
		data: [
			{type: \"spline\",showInLegend: true, name: \"GESAMT\",  markerSize: 0,      dataPoints:         
			[".implode(",",$data)."]}]
			";
        }

        if(isset($_GET["f_val"]) and isset($_GET["f_val2"])) {

            // keine Auswahl im zweiten Select gemacht
            if ($_GET["f_val"] == $_GET["f_val2"]) {
                $data = array();
                $datas = array();
                $schulename = array();

                // GESAMT Linie
                $res = mysql_query("SELECT SUM(SGESAMT) as ANZ,DATUM FROM ".$this->Grafiksettings["mysql_table_name"]." WHERE BERUFSSCHULE = '".$_GET["f_val"]."' GROUP BY STR_TO_DATE(DATUM,'%d.%m.%Y') ORDER BY STR_TO_DATE(DATUM,'%d.%m.%Y') ");
                while($row = mysql_fetch_array($res))
                {
                    $schulename[0] = "GESAMT";
                    $split = explode(".",$row["DATUM"]);
                    $data[0][] = "{ x: new Date(".$split[2].",".$split[1].",".$split[0]."), name: \"GESAMT (".$row["DATUM"].")\", label:\"".$row["DATUM"]."\", y: ".$row["ANZ"]." }";
                }

                $x = 1;
                $res_berufsch = mysql_query("SELECT STANDORT FROM ".$this->Grafiksettings["mysql_table_name"]." WHERE BERUFSSCHULE = '".$_GET["f_val"]."' GROUP BY STANDORT");
                while($standort = mysql_fetch_array($res_berufsch))
                {
                    $res = mysql_query("SELECT SGESAMT as ANZ,DATUM FROM ".$this->Grafiksettings["mysql_table_name"]." WHERE BERUFSSCHULE = '".$_GET["f_val"]."' AND STANDORT = '".$standort["STANDORT"]."' ORDER BY STR_TO_DATE(DATUM,'%d.%m.%Y')");
                    while($row = mysql_fetch_array($res))
                    {
                        $schulename[$x] = "Standort ".$standort["STANDORT"];
                        $split = explode(".",$row["DATUM"]);
                        $data[$x][] = "{ x: new Date(".$split[2].",".$split[1].",".$split[0]."), name: \"Standort ".$standort["STANDORT"]." (".$row["DATUM"].")\", label:\"".$row["DATUM"]."\", y: ".$row["ANZ"]." }";
                    }
                    $x++;
                }


                $x = 0;
                foreach($data as $line)
                {
                    if($x == 0)
                       $options = "visible: false,";
                    else
                        $options = "";
                    $datalines[] = "{type: \"spline\",".$options." showInLegend: true,   markerSize: 0,     name: \"".$schulename[$x]."\",  dataPoints: [" . implode(",", $line) . "]}";
                    $x++;
                }
                $datas = implode(",",$datalines);


                $ret = "height: 500,
        		width: 1140,
        axisY: {
		     valueFormatString: \"0\"
		},
		axisX: {
		    intervalType: 'year',
		      interval: 1,
		},
		 toolTip: {
        shared: true
      },
 
		data: [
			  ".$datas."
			], legend:{
            cursor:\"pointer\",
            itemclick:function(e){
              if (typeof(e.dataSeries.visible) === \"undefined\" || e.dataSeries.visible) {
              	e.dataSeries.visible = false;
              }
              else{
                e.dataSeries.visible = true;
              }
              chart.render();
            }
          }
			";
            }

            // Auswahl im zweiten Select gemacht
            if ($_GET["f_val"] != $_GET["f_val2"])
            {
                $data = array();
                $datas = array();
                $schulename = array();

              //   GESAMT Linie
                $res = mysql_query("SELECT SUM(SGESAMT) as ANZ,DATUM FROM ".$this->Grafiksettings["mysql_table_name"]." WHERE STANDORT = '".$_GET["f_val"]."' GROUP BY STR_TO_DATE(DATUM,'%d.%m.%Y') ORDER BY STR_TO_DATE(DATUM,'%d.%m.%Y') ");
                while($row = mysql_fetch_array($res))
                {
                    $schulename[0] = " GESAMT";
                    $split = explode(".",$row["DATUM"]);
                    $data[0][] = "{ x: new Date(".$split[2].",".$split[1].",".$split[0]."), name: \"GESAMT (".$row["DATUM"].")\", label:\"".$row["DATUM"]."\", y: ".$row["ANZ"]." }";
                }
                $fields = array("SBES","SBFS","SBFSB","SFOS11T","SFOS12","SFS","SBVJ","SVZOP","SVZMP","SFACHS","SFACHOB","SBERUFG","SUEBT");
                $x = 1;
                foreach($fields as $sform)
                {
                    $res = mysql_query("SELECT ".$sform." as ANZ,DATUM FROM ".$this->Grafiksettings["mysql_table_name"]." WHERE STANDORT = '".$_GET["f_val"]."' ORDER BY STR_TO_DATE(DATUM,'%d.%m.%Y') ");
                    while($row = mysql_fetch_array($res))
                    {
                        $schulename[$x] = $sform;
                        $split = explode(".",$row["DATUM"]);
                        $data[$x][] = "{ x: new Date(".$split[2].",".$split[1].",".$split[0]."), name: \"".$sform." (".$row["DATUM"].")\", label:\"".$row["DATUM"]."\", y: ".$row["ANZ"]." }";
                    }
                    $x++;
                }



                $x = 0;
                foreach($data as $line)
                {
                       if($x == 0)
                            $options = "visible: false,";
                      else
                    $options = "";
                    $datalines[] = "{type: \"stackedArea\",".$options." showInLegend: true,   markerSize: 0,     name: \"".$schulename[$x]."\",  dataPoints: [" . implode(",", $line) . "]}";
                    $x++;
                }
                $datas = implode(",",$datalines);


                $ret = "height: 500,
        		width: 1140,
        axisY: {
		     valueFormatString: \"0\"
		},
		axisX: {
		    intervalType: 'year',
		      interval: 1,
		},
		 toolTip: {
        shared: true
      },
 
		data: [
			  ".$datas."
			], legend:{
            cursor:\"pointer\",
            itemclick:function(e){
              if (typeof(e.dataSeries.visible) === \"undefined\" || e.dataSeries.visible) {
              	e.dataSeries.visible = false;
              }
              else{
                e.dataSeries.visible = true;
              }
              chart.render();
            }
          }
			";
            }
        }
        return $ret;
    }

    function DropDownBerufsschulen($grafiksettungs){
        $this->Grafiksettings = $grafiksettungs;
        $this->gruppenspalte = $this->Grafiksettings["gruppenspalte"];
        $this->filterspalte  = "BEZEICHNUNG";

        if(isset($_GET["f_sp"]))
        {
            $this->filterspalte = $_GET["f_sp"];
            $showsumlk = false;
        }
        else
            $showsumlk = true;

        if(isset($_GET["f_val"]))
        {
            $this->filtervalue = $_GET["f_val"];
        }
    }

    function _getSelect(){

        if($this->Grafiksettings["dropdown_enable"] == 0)
            return false;

        $ret = "<br><select onchange=\"window.location = jQuery('#previewfilterdropdown option:selected').val();\" id='previewfilterdropdown'>";

        $ret .= "<option value=\"?modul=diagram&id=".$this->Grafiksettings["diagram_id"]."&grafik=".$this->Grafiksettings["grafik_id"]."\">Landkreis Diepholz</option>";
        $res = mysql_query("SELECT * FROM ".$this->Grafiksettings["mysql_table_name"]." GROUP BY BERUFSSCHULE");
        while($row = mysql_fetch_array($res))
        {

            $filtervalue = $row["BERUFSSCHULE"];

            $selected = "";
            if(isset($_GET["f_val2"])){
                if(strtolower(urldecode($_GET["f_val2"])) == strtolower($filtervalue))
                    $selected = "selected";
            }
            $ret .= "<option ".$selected." value=\"?modul=diagram&id=".$this->Grafiksettings["diagram_id"]."&grafik=".$this->Grafiksettings["grafik_id"]."&f_sp=BEZEICHNUNG&f_val=".$filtervalue."&f_val2=".$filtervalue."\">".$filtervalue."</option>";


        }
        $ret .= "</select>";

        if(isset($_GET["f_val2"]))
        {

                // Gesamt für die Schule
                $ret .= "  <b>=></b> <select onchange=\"window.location = jQuery('#previewfilterdropdown2 option:selected').val();\" id='previewfilterdropdown2'>";
                $ret .= "<option value=\"?modul=diagram&id=" . $this->Grafiksettings["diagram_id"] . "&grafik=" . $this->Grafiksettings["grafik_id"] . "&f_val=" . $_GET["f_val2"] . "&f_val2=" . $_GET["f_val2"] . "\">Alle Schulstandorte " . $_GET["f_val2"] . "</option>";


            $res = mysql_query("SELECT * FROM ".$this->Grafiksettings["mysql_table_name"]." WHERE BERUFSSCHULE = '".$_GET["f_val2"]."' GROUP BY STANDORT");
            while($row = mysql_fetch_array($res)) {

                $filtervalue = $row["STANDORT"];
                if(strlen($filtervalue) > 0) {
                    $selected = "";
                    if (isset($_GET["f_val"])) {
                        if (strtolower(urldecode($_GET["f_val"])) == strtolower($filtervalue))
                            $selected = "selected";
                    }
                    $ret .= "<option " . $selected . " value=\"?modul=diagram&id=" . $this->Grafiksettings["diagram_id"] . "&grafik=" . $this->Grafiksettings["grafik_id"] . "&f_sp=STANDORT&f_val=" . $filtervalue . "&f_val2=" . $_GET["f_val2"] . "\">" . $filtervalue . "</option>";
                }
            }
            $ret .= "</select>";
        }
        return $ret;
    }

}