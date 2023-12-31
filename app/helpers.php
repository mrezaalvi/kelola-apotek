<?php

use App\Settings\ProfileApotekSettings;

    if(!function_exists('toDecimalDBFormat')){
        function toDBDecimalFormat(string | int | float | null $numeric=null){
            if(!$numeric || empty(trim($numeric)))
                return floatval($numeric);

            if(is_numeric($numeric))
                return floatval($numeric);

            if(is_string($numeric)){
                $commaPos = strrpos($numeric, ",");
                $pointPos = strrpos($numeric, ".");

                if((!$commaPos && !$pointPos))
                    return floatval($numeric);
                
                if($commaPos && !$pointPos)
                    return floatval(str_replace(",", ".", $numeric));

                if($commaPos > $pointPos)
                    return floatval(str_replace(",", ".", str_replace(".", "", $numeric)));

                if($commaPos < $pointPos)
                    return floatval(str_replace(",", "", $numeric));
            }
        }
    }

    if(!function_exists('apotekName')){
        function apotekName(){
            $profilApotek = new ProfileApotekSettings(); 
            return $profilApotek->nama_apotek;
        }
    }

    if(!function_exists('apotekLogo')){
        function apotekLogo(){
            $profilApotek = new ProfileApotekSettings();
            return $profilApotek->logo;
        }
    }