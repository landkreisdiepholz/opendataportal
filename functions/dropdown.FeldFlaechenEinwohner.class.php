<?php
/**
 * Created by PhpStorm.
 * User: d12hanse
 * Date: 01.03.2017
 * Time: 17:04
 */
class DropDownFeldFlaechenEinwohner {

    var $Grafiksettings;
    var $gruppenspalte;
    var $filterspalte;
    var $filtervalue;
    var $selectOptions;

    function DropDownFeldFlaechenEinwohner($grafiksettungs){
        $this->Grafiksettings = $grafiksettungs;
        $this->gruppenspalte = $this->Grafiksettings["gruppenspalte"];
        $this->filterspalte  = "BEZEICHNUNG";

        if(isset($_GET["f_sp"]))
        {
            $this->filterspalte = $_GET["f_sp"];
        }

        if(isset($_GET["f_val"]))
        {
            $this->filtervalue = $_GET["f_val"];
        }
    }

    function _genWHEREStatement(){
        if(strlen($this->filtervalue) > 0)
            return "WHERE ".$this->filterspalte." = '".$this->filtervalue."'";
        else
            return "WHERE ".$this->filterspalte." = 'Landkreis Diepholz'";
    }

    function _getGruppenspalte(){
        return $this->gruppenspalte;
    }

    function _getSelect(){

        if($this->Grafiksettings["dropdown_enable"] == 0)
            return false;

        $ret = "<br><select onchange=\"window.location = jQuery('#previewfilterdropdown option:selected').val();\" id='previewfilterdropdown'>";

        $ret .= "<option value=\"?modul=diagram&id=".$this->Grafiksettings["diagram_id"]."&grafik=".$this->Grafiksettings["grafik_id"]."\">Landkreis Diepholz</option>";
        $res = mysql_query("SELECT * FROM opendata_sde80");
        while($row = mysql_fetch_array($res))
        {

            $filtervalue = $row["NAME"];

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
            $res = mysql_query("SELECT * FROM opendata_sde79 WHERE KOMMUNE = '".$_GET["f_val2"]."'");

            if(mysql_num_rows($res) != 1) {
                $ret .= "  <b>=></b> <select onchange=\"window.location = jQuery('#previewfilterdropdown2 option:selected').val();\" id='previewfilterdropdown2'>";
                $ret .= "<option value=\"?modul=diagram&id=" . $this->Grafiksettings["diagram_id"] . "&grafik=" . $this->Grafiksettings["grafik_id"] . "&f_val=" . $_GET["f_val2"] . "&f_val2=" . $_GET["f_val2"] . "\">" . $_GET["f_val2"] . "</option>";


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