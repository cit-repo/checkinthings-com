<?php

    require_once("inc/functions.inc.php");
    require_once("inc/db.inc.php");

    $sel = "SELECT product_id, image_url FROM product WHERE 1";
    $res = mysqli_query($link, $sel);

    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $arRows[] = $row;

            $file = "../alfa/public/img/product/".$row['product_id'].".jpg";

            if (!is_file($file)) {
                echo $row['image_url']."\n";
                curlImage($row['image_url'], $file);
                echo $file."\n\n";
            }
        }
    } else {
        die(mysqli_error($link));
    }
