<?php
    require_once('inc/db.inc.php');
    require_once('class/mysqlSimple.class.php');

    $mysql_options['database'] = "admin_cit";
    $mysql_options['table'] = "product";

    $mysql = new mysqlSimple($link, $mysql_options);

    echo "TRUNCATE TABLE ".$argv[1]."\n";

    $mysql->truncate($argv[1]);