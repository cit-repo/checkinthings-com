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

    // var_dump($elasticsearch->deleteRiver());
    // var_dump($elasticsearch->deleteIndex($elasticsearch_options['index']));
    // var_dump($elasticsearch->createRiverIndex($elasticsearch_options['index']));

    $arMust = array("feed" => "tradedoubler", "description" => "LPG");
    $arMustNot = array();
    $arShould = array();
    $from = 0;
    $size = 100;

    $res = $elasticsearch->search($arMust, $arMustNot, $arShould, $from, $size);
    var_dump($res);

    $arRes = json_decode($res, true);

    foreach ($arRes['hits'] as $key => $value) {
        switch ($key) {
            case "total":
            case "max_score":
                echo "$key: $value\n";
                break;
            default:
                var_dump($value);
        }
    }

?>
