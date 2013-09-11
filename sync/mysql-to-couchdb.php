<?php
    require_once('inc/db.inc.php');
    require_once('class/mysqlSimple.class.php');
    require_once('class/couchdbSimple.class.php');

    $mysql_options['database'] = "admin_cit";
    $mysql_options['table'] = $argv[1];

    $mysql = new mysqlSimple($link, $mysql_options);

    $couchdb_options['host'] = "127.0.0.1";
    $couchdb_options['port'] = "5984";
    $couchdb_options['database'] = "es_".$mysql_options['database']."_".$mysql_options['table'];

    $couchdb = new couchdbSimple($couchdb_options);

    if (isset($argv[2]) && $argv[2] == 1) $insertAll = $argv[2];
    if (isset($argv[3]) && $argv[3] == 1) $updateAll = $argv[3];
    if (isset($argv[4]) && $argv[4] == 1) $clearAll = $argv[4];
    if (isset($argv[5])) $minutes = $argv[5];

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
            echo date('Y-m-d H:i:s')." - createDocument:".$ret;

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

            if ($mysql_options['table'] == "product") {
                $arEav = $mysql->readEav($row[$mysql_options['table'].'_id']);

                if ($arEav)
                    foreach ($arEav as $eav) {
                        foreach ($eav as $key => $value) {

                            if ($key == 'attribute') {
                                $row[$value] = $eav['value'];
                            }
                        }
                    }
            }

            if (!$row['uuid']) {
                $row['uuid'] = $couchdb->getUUID();
            }

            $ret = $couchdb->updateDocument($row['uuid'], $row);
            echo date('Y-m-d H:i:s')." - updateDocument:".$ret;
        }
    }

?>
