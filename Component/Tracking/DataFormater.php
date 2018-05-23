<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Component\Tracking;

use OxidEsales\Eshop\Core\Str;

/**
 * Formats data/values/params by eliminating named entities and xml-entities.
 */
class DataFormater
{
    /**
     * @param string $value
     * @return mixed|null|string
     */
    public static function format($value)
    {
        //null check
        if (is_null($value)) {
            return null;
        }

        //$sStr = urldecode($sStr);
        $value = htmlspecialchars_decode($value, ENT_QUOTES);
        $value = Str::getStr()->html_entity_decode($value);
        $value = strip_tags($value);
        $value = trim($value);

        //2007-05-10 replace translated &nbsp; with spaces
        $nbsp = chr(0xa0);
        $value = str_replace($nbsp, " ", $value);
        $value = str_replace("\"", "", $value);
        $value = str_replace("'", "", $value);
        $value = str_replace("%", "", $value);
        $value = str_replace(",", "", $value);
        $value = str_replace(";", "", $value);
        /* remove unnecessary white spaces*/
        while (true) {
            $sStr_temp = $value;
            $value = str_replace("  ", " ", $value);

            if ($value == $sStr_temp) {
                break;
            }
        }
        $value = str_replace(" / ", "/", $value);
        $value = str_replace(" /", "/", $value);
        $value = str_replace("/ ", "/", $value);

        $value = Str::getStr()->substr($value, 0, 254);

        return $value;
    }
}
