<?php
/**
 * Created by PhpStorm.
 * User: d12hanse
 * Date: 10.02.2017
 * Time: 15:53
 */

function gruppen_generate_big_list(){
    $res = mysql_query("SELECT * FROM gruppen");

    $x = 1;
    while($row = mysql_fetch_array($res))
    {
        if($x == 1)
        {
           echo "<div class=\"col-xs-12 col-sm-6 col-md-2 views-column-1 views-column-first\">";
        }

        echo "<div class=\"col-xs-12 views-column-".$x."\">
                <div class=\"views-field views-field-field-image\">        
                    <div class=\"field-content\"></div>  
                </div>
                <div class=\"topic-icon\">        
                    <div>
                        <a href=\"datensaetze/gruppen/".$row["url"]."\" class=\"font-icon-select-1 font-icon-select-1-e".$row["icon"]."\">
                            <span class=\"screenreader\">icon</span>
                        </a>
                    </div>  
                </div>
              
                <div class=\"views-field views-field-name\">
                  <span class=\"field-content\">
                    <a href=\"datensaetze/gruppen/".$row["url"]."\">".$row["name"]."</a>
                  </span>  
                </div>    
            </div></div>";


        if($x == 6)
        {
            $x = 0;
            echo "</div>";
        }
    }
}