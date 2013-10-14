<?php
    require_once('inc/db.inc.php');
    require_once('class/mysqlSimple.class.php');
    require_once('class/couchdbSimple.class.php');
    require_once('class/elasticsearchSimple.class.php');

    $couchdb_options['host'] = "127.0.0.1";
    $couchdb_options['port'] = "5984";
    $couchdb_options['database'] = "es_admin_cit_track";
    $couchdb = new couchdbSimple($couchdb_options);
    $allTracks = $couchdb->readAllDocuments();
    $allTracks = json_decode($allTracks, true);

    $data = false;

    $arWants = array();
    $arHaves = array();
    $arBuys = array();
    $arSexys = array();

    $arCookies = array();

    $n = 0;

    foreach ($allTracks['rows'] as $track) {

        echo $n++."\r";

        if (isset($track['id'])) {
            $data = $couchdb->readDocument($track['id']);
            $arData = json_decode($data, true);

            if (strstr($arData['event'], "click")) {

                if (!isset($arCookies[$arData['customer_uuid']])) {
                    $arCookies[$arData['customer_uuid']] = array();
                }

                if (strstr($arData['link'], "wantit")) {
                    if (!isset($arCookies[$arData['customer_uuid']]['wants'])) {
                        $arCookies[$arData['customer_uuid']]['wants'] = array();
                    }

                    if (!in_array($arData['link'], $arCookies[$arData['customer_uuid']]['wants'])) {
                        $arCookies[$arData['customer_uuid']]['wants'][] = $arData['link'];
                    }
                } else if (strstr($arData['link'], "haveit")) {
                    if (!isset($arCookies[$arData['customer_uuid']]['haves'])) {
                        $arCookies[$arData['customer_uuid']]['haves'] = array();
                    }

                    if (!in_array($arData['link'], $arCookies[$arData['customer_uuid']]['haves'])) {
                        $arCookies[$arData['customer_uuid']]['haves'][] = $arData['link'];
                    }
                } else if (strstr($arData['link'], "buyit")) {
                    if (!isset($arCookies[$arData['customer_uuid']]['buys'])) {
                        $arCookies[$arData['customer_uuid']]['buys'] = array();
                    }

                    if (!in_array($arData['link'], $arCookies[$arData['customer_uuid']]['buys'])) {
                        $arCookies[$arData['customer_uuid']]['buys'][] = $arData['link'];
                    }
                } else if (strstr($arData['link'], "sexyit")) {
                    if (!isset($arCookies[$arData['customer_uuid']]['sexys'])) {
                        $arCookies[$arData['customer_uuid']]['sexys'] = array();
                    }

                    if (!in_array($arData['link'], $arCookies[$arData['customer_uuid']]['sexys'])) {
                        $arCookies[$arData['customer_uuid']]['sexys'][] = $arData['link'];
                    }
                }

            }

        }

    }

    echo $n." tracks processed\n";

    $couchdb_options['host'] = "127.0.0.1";
    $couchdb_options['port'] = "5984";
    $couchdb_options['database'] = "es_admin_cit_customer";

    $couchdb = new couchdbSimple($couchdb_options);
    $allTracks = $couchdb->readAllDocuments();
    $allTracks = json_decode($allTracks, true);

    $allCustomers = $couchdb->readAllDocuments();
    $allCustomers = json_decode($allCustomers, true);

    $m = 0;

    foreach ($allCustomers['rows'] as $customer) {

        if (isset($customer['id'])) {
            $dataC = $couchdb->readDocument($customer['id']);
            $arDataC = json_decode($dataC, true);

            foreach ($arCookies as $key => $value) {
                if ($arDataC['_id'] == $key) {
                    $arDataC['engine_wants'] = str_replace("wantit_", "", implode(",", $value['wants']));
                    $arDataC['engine_haves'] = str_replace("haveit_", "", implode(",", $value['haves']));
                    $arDataC['engine_buys'] = str_replace("buyit_", "", implode(",", $value['buys']));
                    $arDataC['engine_sexys'] = str_replace("sexyit_", "", implode(",", $value['sexys']));
                }
            }

            $res = $couchdb->updateDocument($customer['id'], $arDataC);

            if (isset($res)) {
                $m++;
            }
        }
    }

    echo $m." customers updated\n";