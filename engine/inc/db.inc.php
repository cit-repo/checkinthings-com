<?php
    date_default_timezone_set("UTC");

    $link = mysqli_connect("localhost", "root", "toor");

    mysqli_query($link, "SET NAMES 'utf8';");

    $database = "admin_cit";

    mysqli_query($link, "USE $database;");

    if (!$link) {
        die(mysqli_error($link));
    }
