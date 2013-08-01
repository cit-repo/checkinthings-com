<?php

    function camelCaseToUnderscores($string)
    {
        // example: parseServerInfo to parse_server_info
        $string = preg_replace('/(?<=\\w)(?=[A-Z])/', "_$1", $string);
        $string = strtolower($string);
        return $string;
    }

    function camelCaseToSpaces($string)
    {
        //example:BachJS to Bach J S
        $string = preg_replace('/(?<=\\w)(?=[A-Z])/', "$1", $string);
        return trim($string);
    }

    function curlGrab($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $html = curl_exec($ch);
        curl_close($ch);

        return $html;
    }

    function recursiveDump($tree, &$arStrings, $search = "name", $nl="\n")
    {

        foreach ($tree as $key => $value) {
            if (is_array($value)) {
                echo $nl;
                recursiveDump($value, $arStrings, $search);
            } else if (is_object($value)) {
                echo $nl;
                recursiveDump($value, $arStrings, $search);
            } else {
                if ($key == $search) {
                    $arStrings[] = $value;
                }
                echo " ".$key." - ".$value." ";
            }
        }

        echo $nl;
    }
