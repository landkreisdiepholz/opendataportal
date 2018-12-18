<?php
/**
 * Created by PhpStorm.
 * User: d12hanse
 * Date: 06.06.2017
 * Time: 16:23
 */
class DropdownKreisKommuneGemeinde {

    var $ressource;
    var $Grafiksettings;
    var $gruppenspalte;
    var $filterspalte;
    var $filtervalue;
    var $selectOptions;

    function DropdownKreisKommuneGemeinde($grafiksettungs,$ressource){

        $this->ressource = $ressource;
        $this->Grafiksettings = $grafiksettungs;
        $this->gruppenspalte = $this->Grafiksettings["gruppenspalte"];

        if(isset($_GET["gruppe"]))
        {
            // TODO: Prüfen
            $this->gruppenspalte = $_GET["gruppe"];
        }

        if(isset($_GET["f_sp"]))
        {
            // TODO: Prüfen
            $this->filterspalte = $_GET["f_sp"];
        }
        if(isset($_GET["f_val"]))
        {
            // TODO: Prüfen
            $this->filtervalue = $_GET["f_val"];
        }
        $this->fillSelect();
    }

    function fillSelect()
    {
        $re = array(
            "NAME" => "Landkreis Diepholz",
            "GRUPPE_SPALTE" => "KOMMUNE",
            "FILTER_SPALTE" => "LANDKREIS",
            "FILTER_WERT" => "Landkreis Diepholz");
        $this->selectOptions[] = $re;

        $sql = "SELECT count(*) as anz, KOMMUNE FROM ".$this->ressource["mysql_table_name"]." GROUP BY ".$this->Grafiksettings["gruppenspalte"];
        $res = mysql_query($sql);
        while($row = mysql_fetch_array($res))
        {
            if(strlen($row["KOMMUNE"]) > 0) {
                $re = array(
                    "NAME" => $row["KOMMUNE"] . " (" . $row["anz"] . " Stück)",
                    "GRUPPE_SPALTE" => "GEMEINDE",
                    "FILTER_SPALTE" => "KOMMUNE",
                    "FILTER_WERT" => $row["KOMMUNE"]);
                $this->selectOptions[] = $re;
            }
            else
            {
                $re = array(
                    "NAME" => "Ohne Angaben (" . $row["anz"] . " Stück)",
                    "GRUPPE_SPALTE" => "GEMEINDE",
                    "FILTER_SPALTE" => "KOMMUNE",
                    "FILTER_WERT" => "");
                $this->selectOptions[] = $re;
            }
        }


    }

    function _genWHEREStatement(){
        if (strlen($this->filterspalte) > 0) {
            if (strlen($this->filtervalue) == 0) {
                return "WHERE " . $this->filterspalte . " IS NULL";
            } else {
                return "WHERE " . $this->filterspalte . " = '" . $this->filtervalue . "'";
            }
        }
        else
            return "";
    }

    function _getGruppenspalte(){
        return $this->gruppenspalte;
    }

    function _getSelect(){

        if($this->Grafiksettings["dropdown_enable"] == 0)
            return false;

        $ret = "<br><br><select onchange=\"window.location = jQuery('#previewfilterdropdown option:selected').val();\" id='previewfilterdropdown'>";

        foreach($this->selectOptions as $option)
        {
            $selected = "";
            if(isset($_GET["f_val"])){
                if(strtolower(urldecode($_GET["f_val"])) == strtolower($option["FILTER_WERT"]))
                    $selected = "selected";
            }
            $ret .= "<option ".$selected." value=\"?modul=diagram&id=".$this->Grafiksettings["diagram_id"]."&grafik=".$this->Grafiksettings["grafik_id"]."&f_sp=".$option["FILTER_SPALTE"]."&f_val=".$option["FILTER_WERT"]."&gruppe=".$option["GRUPPE_SPALTE"]."\">".$option["NAME"]."</option>";

        }
        $ret .= "</select>";
        return $ret;
    }

}