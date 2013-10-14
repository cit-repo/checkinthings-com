<?php

    if (strstr($_SERVER['HTTP_HOST'], "localhost")) {
        $location = "/var/www/vhosts/admin/cit/alfa/public/img/product/";
    } else {
        $location = "/var/www/vhosts/checkinthings.com/httpdocs/alfa/public/img/product/";
    }

    $percent = 0.2;

    // File and new size
    $productId = $_GET['product_id'];
    if (isset($_GET['percent'])) {
        $percent = $_GET['percent'];
    }

    $filename = $location.$productId.".jpg";

    // echo $filename;

    // Content type
    header('Content-Type: image/jpeg');

    // Get new sizes
    list($width, $height) = getimagesize($filename);
    $newwidth = $width * $percent;
    $newheight = $height * $percent;

    // Load
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    $source = imagecreatefromjpeg($filename);

    // Resize
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    // Output
    imagejpeg($thumb);
?>
