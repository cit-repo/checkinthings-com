<?php
    require_once('inc/db.inc.php');
    require_once('class/mysqlSimple.class.php');
    require_once('class/couchdbSimple.class.php');

    $mysql_options['database'] = "admin_cit";
    $mysql_options['table'] = "product";

    $mysql = new mysqlSimple($link, $mysql_options);

    $couchdb_options['host'] = "127.0.0.1";
    $couchdb_options['port'] = "5984";
    $couchdb_options['database'] = "es_".$mysql_options['database']."_".$mysql_options['table'];

    $couchdb = new couchdbSimple($couchdb_options);

    if (isset($argv[1]) && $argv[1] == 1) $insertAll = $argv[1];
    if (isset($argv[2]) && $argv[2] == 1) $updateAll = $argv[2];
    if (isset($argv[3]) && $argv[3] == 1) $clearAll = $argv[3];
    if (isset($argv[4])) $minutes = $argv[4];

    if (isset($clearAll)) {
        $res = $couchdb->deleteDatabase();
        // var_dump($res);

        $res = $couchdb->createDatabase();
        // var_dump($res);
    }

    if (isset($insertAll)) {
        $res = $mysql->readAllDatabases();
        // var_dump($res);

        $res = $mysql->getLastInsertID();
        // var_dump($res);

        $arRows = $mysql->readAllRows();

        // $id = 1;
        // $arRows = $mysql->readRow($id);

        // var_dump($arRows);

        $n = 0;

        foreach ($arRows as $row) {
            $arEav = $mysql->readEav($row['product_id']);

            foreach ($arEav as $eav) {
                foreach ($eav as $key => $value) {

                    if ($key == 'attribute') {
                        $row[$value] = $eav['value'];
                    }
                }
            }

            $ret = $couchdb->createDocument($row);
            echo $n++."\r";

            $ar = json_decode($ret, true);

            if (isset($ar['id'])) {
                $uuid = $ar['id'];
            } else {
                $uuid = 0;
            }

            $mysql->updateRow(array("id" => $row[$mysql_options['table'].'_id'], "uuid" => $uuid));
        }
    }

    if (isset($updateAll)) {

        if (!$minutes) {
            $minutes = 5;
        }

        $arRows = $mysql->readUpdatedRows($minutes);

        // var_dump($arRows);

        if (is_array($arRows))
        foreach ($arRows as $row) {

            $arEav = $mysql->readEav($row['product_id']);

            foreach ($arEav as $eav) {
                foreach ($eav as $key => $value) {

                    if ($key == 'attribute') {
                        $row[$value] = $eav['value'];
                    }
                }
            }

            $ret = $couchdb->updateDocument($row['uuid'], $row)."\n";
            echo $ret."\n";
        }
    }

?>
