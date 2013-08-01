<?php

    require_once("inc/db.inc.php");
    require_once("inc/functions.inc.php");

    mysqli_query($link, "TRUNCATE $database.product_eav;");
    mysqli_query($link, "TRUNCATE $database.product;");
