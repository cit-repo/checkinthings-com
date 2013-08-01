<?php
    require_once('inc/db.inc.php');
    require_once('class/mysqlSimple.class.php');
    require_once('class/couchdbSimple.class.php');
    require_once('class/elasticsearchSimple.class.php');

    $mysql_options['database'] = "admin_cit";
    $mysql_options['table'] = "product";

    $mysql = new mysqlSimple($link, $mysql_options);

    $couchdb_options['host'] = "127.0.0.1";
    $couchdb_options['port'] = "5984";
    $couchdb_options['database'] = "es_".$mysql_options['database']."_".$mysql_options['table'];

    $couchdb = new couchdbSimple($couchdb_options);

    $elasticsearch_options['host'] = "127.0.0.1";
    $elasticsearch_options['port'] = "9200";
    $elasticsearch_options['index'] = "es_".$mysql_options['database']."_".$mysql_options['table'];

    $elasticsearch = new elasticsearchSimple($elasticsearch_options);

    if (isset($argv[1]) && $argv[1] == 1) $insertAll = $argv[1];
    if (isset($argv[2]) && $argv[2] == 1) $updateAll = $argv[2];

    if (isset($insertAll)) {
        var_dump($couchdb->deleteDatabase());
        var_dump($couchdb->createDatabase());

        // var_dump($mysql->readAllDatabases());
        // var_dump($mysql->getLastInsertID());

        $arRows = $mysql->readAllRows();

        // $id = 1;
        // $arRows = $mysql->readRow($id);

        // var_dump($arRows);

        foreach ($arRows as $row) {
            $ret = $couchdb->createDocument($row);
            echo $ret."\n";

            $ar = json_decode($ret, true);

            $uuid = $ar['id'];

            $mysql->updateRow(array("id" => $row[$mysql_options['table'].'_id'], "uuid" => $uuid));
        }
    }

    if (isset($updateAll)) {
        $arRows = $mysql->readUpdatedRows(5);

        // var_dump($arRows);

        if (is_array($arRows))
        foreach ($arRows as $row) {
            $ret = $couchdb->updateDocument($row['uuid'], $row)."\n";
            echo $ret."\n";
        }
    }

?>
