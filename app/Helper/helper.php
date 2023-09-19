<?php

    if(!function_exists('toDecimalDBFormat')){
        function toDBDecimalFormat($decimal){
            if(!is_numeric($decimal)&&!is_float($decimal))
                return str_replace(",",".",$decimal);
        }
    }