<?php

class Utils
{

    public function recursiveDump($tree, &$arStrings, $search = "name", $nl="\n")
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

    public function url_exists($url)
    {
        $hdrs = @get_headers($url);
        return is_array($hdrs) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$hdrs[0]) : false;
    }

}
