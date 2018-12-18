<?php
class ohneDropdown {
    var $Grafiksettings;
    var $gruppenspalte;
    var $filterspalte;
    var $filtervalue;
    var $selectOptions;
    function ohneDropdown($grafiksettungs){
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
    }
    function _getGruppenspalte(){
        return $this->gruppenspalte;
    }
    function _getSelect()
    {
        return "";
    }
    function _genWHEREStatement(){
        if(strlen($this->filterspalte) > 0)
            return "WHERE ".$this->filterspalte." = '".$this->filtervalue."'";
        else
            return "";
    }
}