<?php

    require_once("../inc/db.inc.php");
    require_once("../inc/functions.inc.php");

    $url = "http://api.tradedoubler.com/1.0/productCategories;language=english?token=7023D1B2570F4348EBCDDE486B6FA61D1D0C23A1";

    $json = curlGrab($url);

    $obj = json_decode($json);

    $feed = "tradedoubler";

    // print_r($obj);

    $arStrings = array();

    foreach ($obj->categoryTrees as $tree) {
        recursiveDump($tree, $arStrings, "name");
    }

    print_r($arStrings);
