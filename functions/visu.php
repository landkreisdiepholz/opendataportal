<?php
/**
 * Created by PhpStorm.
 * User: d12hanse
 * Date: 08.02.2017
 * Time: 15:55
 */
function get_colums($res_id)
{
    $ressource = mysql_fetch_array(mysql_query("SELECT * FROM ressource WHERE dkan_res_id = '".$res_id."'"));
    $res = mysql_fetch_array(mysql_query("SELECT * FROM ".$ressource["mysql_table_name"]),MYSQL_ASSOC);
    foreach($res as $key => $value)
    {
        $ret[$key] = $key;
    }

    return $ret;
}

function dkan_get_content($res_id,$where  = "")
{
    $r = array();
    $ressource = mysql_fetch_array(mysql_query("SELECT * FROM ressource WHERE dkan_res_id = '".$res_id."'"));
    $sql = "SELECT * FROM ".$ressource["mysql_table_name"]." ".$where;
    $res = mysql_query($sql);
    while($row = mysql_fetch_array($res,MYSQL_ASSOC))
    {
        $r[] = $row;
    }
    return $r;
}

function visu_get_data_json($_grafik_id)
{

    // TODO: CLASS
    $grafik_Data = get_grafik_info($_grafik_id);
    // $where = grafik_Data["sql_where"]

    if(strlen($grafik_Data["dropdown_class"]) > 0) {
        $class = $grafik_Data["dropdown_class"];
        $filter = new $class($grafik_Data);
        $where = $filter->_genWHEREStatement();
    }
    else {
        $filter = new ohneDropdown($grafik_Data);
        $where = "";
    }


    $rawdata = dkan_get_content($grafik_Data["dkan_res_id"],$where);
    $return = array();

    if($grafik_Data["betriebsart"] == "d_split")
    {
        if($grafik_Data["opmode"] == "count_anz_lines")
        {
            //BETRIEBSART "DATENSPLITTEN" && OPERATIONSMODUS "VORHANDENE EINTRAEGE ZÄHLEN"
            //$gruppierspalte = $grafik_Data["gruppenspalte"];
            $gruppierspalte = $filter->_getGruppenspalte();
            $summenspalte = $grafik_Data["sum_spalte"];
            //BETRIEBSART "DATENSPLITTEN" && OPERATIONSMODUS "WERTE ADDIEREN"
            foreach($rawdata as $row)
            {
                if(isset($return[$row[$gruppierspalte]]))
                    $return[$row[$gruppierspalte]]++;
                else
                    $return[$row[$gruppierspalte]] =  1;
            }

        }
        if($grafik_Data["opmode"] == "sum_all_lines")
        {
            $gruppierspalte = $filter->_getGruppenspalte();
            $summenspalte = $grafik_Data["sum_spalte"];
            //BETRIEBSART "DATENSPLITTEN" && OPERATIONSMODUS "WERTE ADDIEREN"

            foreach($rawdata as $row)
            {
                if(isset($return[$row[$gruppierspalte]]))
                    $return[$row[$gruppierspalte]] = $return[$row[$gruppierspalte]] +  $row[$summenspalte];
                else {
                    $return[$row[$gruppierspalte]] = $row[$summenspalte];
                }
            }
        }
    }
    if($grafik_Data["betriebsart"] == "d_add")
    {
        //BETRIEBSART "WERTE ADDIEREN"
        $gruppierspalte = $grafik_Data["gruppenspalte"];
        foreach($rawdata as $row)
        {
            if(isset($return[$row[$gruppierspalte]]))
                $return[$grafik_Data["text_y"]] = $return[$row[$gruppierspalte]] +  $row[$gruppierspalte];
            else {
                $return[$grafik_Data["text_y"]] = $row[$gruppierspalte];
            }
        }
    }

    if($grafik_Data["betriebsart"] == "zeitstrahl")
    {
        $gruppierspalte = $grafik_Data["gruppenspalte"];
        $x = 0;
        foreach($rawdata as $row)
        {
            if($row[$grafik_Data["filter"]] == $grafik_Data["filter_wert"])
            {
                $parts = explode(".",$row[$grafik_Data["sum_spalte"]]);
                if(count($parts) == 1)
                {
                    $rq[] = "{x:".$row[$grafik_Data["sum_spalte"]].", y:" . $row[$grafik_Data["y_spalte"]] . "}";

                }
                else {
                    $rq[] = "{x: new Date(" . $parts[2] . ", " . $parts[1] . ", " . $parts[0] . "), y: " . $row[$grafik_Data["y_spalte"]] . "}";
                }
            }
        }

        return implode(",",$rq);
    }
    else {

        $ret = array();
        $summe = 0;
        foreach ($return as $key => $value) {
            $summe = $summe + $value;
        }

        foreach ($return as $key => $value) {
            $sortarray[$key] = $value;
            $sortarrayKEY[$key] = $key;

            if ($grafik_Data["einheit"] == 0)
                $einheit = "Stck.";
            else
                $einheit = $grafik_Data["einheit"];

            $protz = round($value/($summe/100),2);

            $ret[] = array(
                "einheit" => $einheit,
                "tooltip" => $key . " => " . $value . " " . $grafik_Data["einheit"]."(".$protz."%)",
                "indexLabel" => $key,
                "label" => $key ,
                "y" => round($value, 2));
        }

        if ($grafik_Data["sortmode"] == "alpha") {
            array_multisort($sortarrayKEY, SORT_DESC, SORT_STRING, $ret);
        }
        if ($grafik_Data["sortmode"] == "num_desc") {
            array_multisort($sortarray, SORT_DESC, SORT_NUMERIC, $ret);
        }
        if ($grafik_Data["sortmode"] == "num_asc") {
            array_multisort($sortarray, SORT_ASC, SORT_NUMERIC, $ret);
        }
        if ($grafik_Data["sortmode"] == "") {
            array_multisort($sortarray, SORT_ASC, SORT_NUMERIC, $ret);
        }

        return $ret;
    }
}

function get_chart_config($_grafik_id){
    $cachefile = "/tmp/ODC_diagram_".$_grafik_id.crc32(serialize($_GET));
    if(!file_exists($cachefile)) {
        $grafik_data = get_grafik_info($_grafik_id);

        if (strlen($grafik_data["dropdown_class"]) > 0) {
            $class = $grafik_data["dropdown_class"];
            $filter = new $class($grafik_data);

            if (isset($filter->fullcustom))
                $fullcustom = true;
        }

        if ($fullcustom) {
            $ret = $filter->custom();
        } else {

            if ($grafik_data["hoehe"] == 0)
                $hoehe = 500;
            else
                $hoehe = $grafik_data["hoehe"];

            if ($grafik_data["width"] == 0)
                $breite = 1140;
            else
                $breite = $grafik_data["width"];


            if ($grafik_data["type_diagram"] == "bar") {
                $ret = "
                height: " . $hoehe . ",
        		width: " . $breite . ",
        		 axisY: {
		    labelFontSize: 10,
		},
		axisX: {
		    labelFontSize: 10,
		},
		data: [
		{       
			 type: diagramtype,
			showInLegend: false,
			toolTipContent: \"{tooltip}\",
			legendText: \"{indexLabel}\",
			dataPoints: " . json_encode(visu_get_data_json($_grafik_id)) . "
		}
		]";
            }

            if ($grafik_data["type_diagram"] == "line") {

                $lines = explode(";", $grafik_data["y_spalte"]);
                $ds = array();

                if (strlen($grafik_data["dropdown_class"]) > 0) {
                    $class = $grafik_data["dropdown_class"];
                    $filter = new $class($grafik_data);

                    $where = $filter->_genWHEREStatement();
                } else {
                    if (strlen($grafik_data["filter_wert"]) > 0)
                        $where = "WHERE " . $grafik_data["filter"] . " = '" . $grafik_data["filter_wert"] . "'";
                    else
                        $where = "";
                }


                foreach ($lines as $yspalte) {
                    $r = array();
                    $sql = "SELECT " . $yspalte . "," . $grafik_data["sum_spalte"] . " FROM " . $grafik_data["mysql_table_name"] . " " . $where." ORDER BY STR_TO_DATE(".$grafik_data["sum_spalte"].",'%d.%m.%Y')";
                    $res = mysql_query($sql);

                    while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
                        $date = explode(".", $row[$grafik_data["sum_spalte"]]);
                        $r[] = "\n{ x: new Date(" . $date[2] . "," . $date[1] . "," . $date[0] . "), name: \"" . $yspalte . " (" . $row[$grafik_data["sum_spalte"]] . ")\", label:\"" . $row[$grafik_data["sum_spalte"]] . "\", y: $row[$yspalte] }";

                    }
                    $ds[] = "{type: \"spline\",  markerSize: 0,  showInLegend: true, name: \"" . $yspalte . "\",  dataPoints: [" . implode(",", $r) . "]}";

                }
                $ret = "
                height: " . $hoehe . ",
        		width: " . $breite . ",
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
		
			" . implode(",\n\n", $ds) . "
		]";
            }
        }

        file_put_contents($cachefile,$ret);
        return $ret;
    }
    else
        return file_get_contents($cachefile);
}

function get_grafik_info($grafik_id)
{
    return mysql_fetch_array(mysql_query("SELECT * FROM `diagrams_grafiken` as gr,
 ressource_diagrams as dia,
  ressource as re WHERE gr.diagram_id = dia.diagram_id AND dia.ressource_id = re.ressource_id AND gr.grafik_id = '".$grafik_id."'"),MYSQL_ASSOC);
}

function urlizer($name)
{
    return strtolower(trim(sonderzeichen($name)));
}

function sonderzeichen($t)
{
    $t = str_replace(" ", chr(45), $t);
    $t = str_replace("À", "Ae", $t);
    $t = str_replace("À", "Ae", $t);
    $t = str_replace("à", "ae", $t);
    $t = str_replace("Á", "Ae", $t);
    $t = str_replace("á", "ae", $t);
    $t = str_replace("Â", "Ae", $t);
    $t = str_replace("â", "ae", $t);
    $t = str_replace("Ã", "Ae", $t);
    $t = str_replace("ã", "ae", $t);
    $t = str_replace("Ä", "Ae", $t);
    $t = str_replace("ä", "ae", $t);
    $t = str_replace("Å", "Ae", $t);
    $t = str_replace("å", "ae", $t);
    $t = str_replace("Æ", "Ae", $t);
    $t = str_replace("æ", "ae", $t);
    $t = str_replace("Ç", "C", $t);
    $t = str_replace("ç", "c", $t);
    $t = str_replace("È", "E", $t);
    $t = str_replace("è", "e", $t);
    $t = str_replace("É", "E", $t);
    $t = str_replace("é", "e", $t);
    $t = str_replace("Ê", "E", $t);
    $t = str_replace("ê", "e", $t);
    $t = str_replace("Ë", "E", $t);
    $t = str_replace("ë", "e", $t);
    $t = str_replace("Ì", "I", $t);
    $t = str_replace("ì", "i", $t);
    $t = str_replace("Í", "I", $t);
    $t = str_replace("í", "i", $t);
    $t = str_replace("Î", "I", $t);
    $t = str_replace("î", "i", $t);
    $t = str_replace("Ï", "I", $t);
    $t = str_replace("ï", "i", $t);
    $t = str_replace("Ñ", "N", $t);
    $t = str_replace("ñ", "n", $t);
    $t = str_replace("Ò", "O", $t);
    $t = str_replace("ò", "o", $t);
    $t = str_replace("Ó", "O", $t);
    $t = str_replace("ó", "o", $t);
    $t = str_replace("Ô", "O", $t);
    $t = str_replace("ô", "o", $t);
    $t = str_replace("Õ", "O", $t);
    $t = str_replace("õ", "o", $t);
    $t = str_replace("Ö", "Oe", $t);
    $t = str_replace("ö", "oe", $t);
    $t = str_replace("Ø", "Oe", $t);
    $t = str_replace("ø", "oe", $t);
    $t = str_replace("Ù", "U", $t);
    $t = str_replace("ù", "u", $t);
    $t = str_replace("Ú", "U", $t);
    $t = str_replace("ú", "u", $t);
    $t = str_replace("Û", "U", $t);
    $t = str_replace("û", "u", $t);
    $t = str_replace("Ü", "Ue", $t);
    $t = str_replace("ü", "ue", $t);
    $t = str_replace("Y´", "Y", $t);
    $t = str_replace("y´", "y", $t);
    $t = str_replace("ß", "ss", $t);

    for ($i = 0; $i < 45; $i++)
        $t = str_replace(chr ($i), "", $t);

    for ($i = 46; $i < 48; $i++)
        $t = str_replace(chr ($i), "", $t);

    for ($i = 58; $i < 65; $i++)
        $t = str_replace(chr ($i), "", $t);

    for ($i = 91; $i < 97; $i++)
        $t = str_replace(chr ($i), "", $t);
    for ($i = 123; $i < 256; $i++)
        $t = str_replace(chr ($i), "", $t);

    $t = str_replace( "--", "-", $t);
    return strtolower($t);
}
