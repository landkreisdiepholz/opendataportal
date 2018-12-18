<?php
/**
 * Created by PhpStorm.
 * User: d12hanse
 * Date: 01.03.2017
 * Time: 17:04
 */
class DropDownSchuelerzahlen {

    var $Grafiksettings;
    var $gruppenspalte;
    var $filterspalte;
    var $filtervalue;
    var $selectOptions;
    var $fullcustom = true;
    var $showsumlk = false;

    function custom(){
        if(!isset($_GET["f_val"]) AND !isset($_GET["f_val2"])) {
            $res = mysql_query("SELECT sum(SCHUELER) as ANZ,SJAHR,STICHTAG FROM ".$this->Grafiksettings["mysql_table_name"]." GROUP BY SJAHR");
            $data = array();
            while($row = mysql_fetch_array($res))
            {
                $split = explode(".",$row["STICHTAG"]);
                $data[] = "{ x: new Date(".$split[2].",".$split[1].",".$split[0]."), name: \"GESAMT (".$row["STICHTAG"].")\", label:\"".$row["STICHTAG"]."\", y: ".$row["ANZ"]." }";
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

        if ($_GET["f_val"] == $_GET["f_val2"]) {
            $data = array();
            $datas = array();
            $schulename = array();

            // GESAMT Linie
            $res = mysql_query("SELECT sum(SCHUELER) as ANZ,SJAHR,STICHTAG,KOMMUNE FROM ".$this->Grafiksettings["mysql_table_name"]." WHERE KOMMUNE = '".$_GET["f_val"]."' GROUP BY SJAHR");
            while($row = mysql_fetch_array($res))
            {
                $schulename[0] = "GESAMT";
                $split = explode(".",$row["STICHTAG"]);
                $data[0][] = "{ x: new Date(".$split[2].",".$split[1].",".$split[0]."), name: \"GESAMT (".$row["STICHTAG"].")\", label:\"".$row["STICHTAG"]."\", y: ".$row["ANZ"]." }";
            }

            // LOOP FOR SCHULE
            $schuleI = 1;

            $res = mysql_query("SELECT IDENT,SCHULE FROM ".$this->Grafiksettings["mysql_table_name"]." WHERE KOMMUNE = '".$_GET["f_val"]."' GROUP BY IDENT");
            while($schule = mysql_fetch_array($res))
            {
                $schulename[$schuleI] = $schule["SCHULE"];
                $res_schule = mysql_query("SELECT * FROM ".$this->Grafiksettings["mysql_table_name"]." WHERE IDENT = '".$schule["IDENT"]."'");
                while($point = mysql_fetch_array($res_schule))
                {
                    $split = explode(".",$point["STICHTAG"]);
                    $data[$schuleI][] = "{ x: new Date(".$split[2].",".$split[1].",".$split[0]."), name: \"$schulename[$schuleI] (".$point["STICHTAG"].")\", label:\"".$point["STICHTAG"]."\", y: ".$point["SCHUELER"]." }";
                }
                $schuleI++;
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
        if ($_GET["f_val"] != $_GET["f_val2"]) {
            $res = mysql_query("SELECT SCHUELER as ANZ,SJAHR,STICHTAG FROM ".$this->Grafiksettings["mysql_table_name"]." WHERE SCHULE = '".$_GET["f_val"]."'");
            $data = array();
            while($row = mysql_fetch_array($res))
            {
                $split = explode(".",$row["STICHTAG"]);
                $data[] = "{ x: new Date(".$split[2].",".$split[1].",".$split[0]."), name: \"GESAMT (".$row["STICHTAG"].")\", label:\"".$row["STICHTAG"]."\", y: ".$row["ANZ"]." }";
            }


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
			{type: \"spline\",showInLegend: true,  markerSize: 0,    name: \"GESAMT\",  dataPoints:         
			[".implode(",",$data)."]}]
			";
        }
    }
    return $ret;
    }

    function DropDownSchuelerzahlen($grafiksettungs){
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
        $res = mysql_query("SELECT * FROM opendata_sde80 ORDER BY POS ASC");
        while($row = mysql_fetch_array($res))
        {

            $filtervalue = $row["NAME"];

            $selected = "";
            if(isset($_GET["f_val2"])){
                if(strtolower(urldecode($_GET["f_val2"])) == strtolower($filtervalue))
                    $selected = "selected";
            }
            $ret .= "<option ".$selected." value=\"?modul=diagram&id=".$this->Grafiksettings["diagram_id"]."&grafik=".$this->Grafiksettings["grafik_id"]."&f_sp=BEZEICHNUNG&f_val=".$filtervalue."&f_val2=".$filtervalue."\">Bereich ".$filtervalue."</option>";


        }
        $ret .= "</select>";

        if(isset($_GET["f_val2"]))
        {
            $res = mysql_query("SELECT SCHULE as NAME FROM ".$this->Grafiksettings["mysql_table_name"]." WHERE KOMMUNE = '".$_GET["f_val2"]."' GROUP BY IDENT ORDER BY IDENT ASC");

            if(mysql_num_rows($res) != 1) {
                $ret .= "  <b>=></b> <select onchange=\"window.location = jQuery('#previewfilterdropdown2 option:selected').val();\" id='previewfilterdropdown2'>";
                $ret .= "<option value=\"?modul=diagram&id=" . $this->Grafiksettings["diagram_id"] . "&grafik=" . $this->Grafiksettings["grafik_id"] . "&f_val=" . $_GET["f_val2"] . "&f_val2=" . $_GET["f_val2"] . "\">Bereich " . $_GET["f_val2"] . "</option>";


                while ($row = mysql_fetch_array($res)) {

                    $filtervalue = $row["NAME"];

                    $selected = "";
                    if (isset($_GET["f_val"])) {
                        if (strtolower(urldecode($_GET["f_val"])) == strtolower($filtervalue))
                            $selected = "selected";
                    }
                    $ret .= "<option " . $selected . " value=\"?modul=diagram&id=" . $this->Grafiksettings["diagram_id"] . "&grafik=" . $this->Grafiksettings["grafik_id"] . "&f_sp=BEZEICHNUNG&f_val=" . $filtervalue . "&f_val2=" . $_GET["f_val2"] . "\">" . $filtervalue . "</option>";


                }
                $ret .= "</select>";
            }


        }

        return $ret;
    }

}