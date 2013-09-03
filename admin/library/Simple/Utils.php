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

}
