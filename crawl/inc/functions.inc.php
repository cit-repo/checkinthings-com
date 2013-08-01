<?php

    function camelCaseToUnderscores($string)
    {
        // example: parseServerInfo to parse_server_info
        $string = str_replace("’", "", $string);
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

    function curlImage($url, $file)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $rawdata = curl_exec ($ch);
        curl_close ($ch);

        $fp = fopen($file,'w');
        fwrite($fp, $rawdata);
        fclose($fp);
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
                echo " ".$key.": ".$value." ";
                echo $nl;
            }
        }

        echo $nl;
    }

    function recursiveDumpPro($tree, $spacing='', $level = 0, $nl = "\n")
    {
        if (is_array($tree) || is_object($tree)) {
            foreach ($tree as $key => $value) {
                if (is_array($value)) {
                    echo "    $spacing (".$key.": ".$level." array) -> ".recursiveDumpPro($value, "  ", $level++).$nl;
                    // recursiveDumpPro($value);
                } else if (is_object($value)) {
                    echo "    $spacing (".$key.": ".$level." object) -> ".recursiveDumpPro($value, "  ", $level++).$nl;
                    // recursiveDumpPro($value);
                } else {
                    if ($key != '_' && $key != 'Units') {
                        echo $nl."    $spacing $spacing $spacing $spacing $key: ".substr($value, 0, 100).$nl.$nl;
                    }
                }
            }
        } else {
            echo "$tree\n";
        }

    }

    function getJsonFromUrl($url, $file)
    {
        if (file_exists($file)) {
            // echo "Using File\n";
            $json = file_get_contents($file);

        } else {
            // echo "Using Curl\n";
            $json = curlGrab($url);
            file_put_contents($file, $json);
        }

        return $json;
    }
