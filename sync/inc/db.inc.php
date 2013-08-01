<?php

    $link = mysqli_connect("localhost", "citftp", "Cit2013");

    mysqli_query($link, "SET NAMES 'utf8';");

    $database = "admin_cit";

    mysqli_query($link, "USE $database;");

    if (!$link) {
        die(mysqli_error($link));
    }

