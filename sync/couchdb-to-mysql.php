<?php
    require_once('inc/db.inc.php');
    require_once('class/mysqlSimple.class.php');
    require_once('class/couchdbSimple.class.php');
    require_once('class/elasticsearchSimple.class.php');

    $mysql_options['database'] = "admin_cit";
    $mysql_options['table'] = $argv[1];

    $mysql = new mysqlSimple($link, $mysql_options);

    $couchdb_options['host'] = "127.0.0.1";
    $couchdb_options['port'] = "5984";
    $couchdb_options['database'] = "es_".$mysql_options['database']."_".$mysql_options['table'];

    $couchdb = new couchdbSimple($couchdb_options);

    $allDocs = $couchdb->readAllDocuments();

    $allDocs = json_decode($allDocs, true);

    // print_r($allDocs['rows']);

    switch ($mysql_options['table']) {

        case "customer":
            foreach ($allDocs['rows'] as $doc) {
                $doc = $couchdb->readDocument($doc['id']);

                $doc = json_decode($doc, true);

                $sel = "SELECT * FROM admin_cit.".$mysql_options['table']." WHERE email = '".$doc['email']."';";
                // echo $sel."\n";

                $res = mysqli_query($link, $sel);

                if (mysqli_num_rows($res) > 0) {
                    $row = mysqli_fetch_assoc($res);

                    if ($doc['_id'] != $row['uuid']) {
                        $upd = "UPDATE admin_cit.".$mysql_options['table']." SET uuid='".$doc['_id']."' WHERE email = '".$doc['email']."';";
                        echo date('Y-m-d H:i:s')." - ".$upd."\n";
                        $res = mysqli_query($link, $upd);
                    }

                    if ($doc['firstname'] != $row['firstname']) {
                        $upd = "UPDATE admin_cit.".$mysql_options['table']." SET firstname='".$doc['firstname']."' WHERE email = '".$doc['email']."';";
                        echo date('Y-m-d H:i:s')." - ".$upd."\n";
                        $res = mysqli_query($link, $upd);
                    }

                    if ($doc['lastname'] != $row['lastname']) {
                        $upd = "UPDATE admin_cit.".$mysql_options['table']." SET lastname='".$doc['lastname']."' WHERE email = '".$doc['email']."';";
                        echo date('Y-m-d H:i:s')." - ".$upd."\n";
                        $res = mysqli_query($link, $upd);
                    }

                    if ($doc['password'] != $row['password']) {
                        $upd = "UPDATE admin_cit.".$mysql_options['table']." SET password='".$doc['password']."' WHERE email = '".$doc['email']."';";
                        echo date('Y-m-d H:i:s')." - ".$upd."\n";
                        $res = mysqli_query($link, $upd);
                    }

                    if ($doc['engine_wants'] != $row['engine_wants']) {
                        $upd = "UPDATE admin_cit.".$mysql_options['table']." SET engine_wants='".$doc['engine_wants']."' WHERE email = '".$doc['email']."';";
                        echo date('Y-m-d H:i:s')." - ".$upd."\n";
                        $res = mysqli_query($link, $upd);
                    }

                    if ($doc['engine_haves'] != $row['engine_haves']) {
                        $upd = "UPDATE admin_cit.".$mysql_options['table']." SET engine_haves='".$doc['engine_haves']."' WHERE email = '".$doc['email']."';";
                        echo date('Y-m-d H:i:s')." - ".$upd."\n";
                        $res = mysqli_query($link, $upd);
                    }

                    if ($doc['engine_buys'] != $row['engine_buys']) {
                        $upd = "UPDATE admin_cit.".$mysql_options['table']." SET engine_buys='".$doc['engine_buys']."' WHERE email = '".$doc['email']."';";
                        echo date('Y-m-d H:i:s')." - ".$upd."\n";
                        $res = mysqli_query($link, $upd);
                    }

                    if ($doc['engine_sexys'] != $row['engine_sexys']) {
                        $upd = "UPDATE admin_cit.".$mysql_options['table']." SET engine_sexys='".$doc['engine_sexys']."' WHERE email = '".$doc['email']."';";
                        echo date('Y-m-d H:i:s')." - ".$upd."\n";
                        $res = mysqli_query($link, $upd);
                    }

                    if ($doc['last_updated'] != $row['last_updated']) {
                        $upd = "UPDATE admin_cit.".$mysql_options['table']." SET last_updated='".$doc['last_updated']."' WHERE email = '".$doc['email']."';";
                        echo date('Y-m-d H:i:s')." - ".$upd."\n";
                        $res = mysqli_query($link, $upd);
                    }

                } else {
                    $ins = "INSERT INTO admin_cit.".$mysql_options['table']." (firstname, lastname, email, password, session_id, cookie_id, uuid, last_updated) VALUES ('".$doc['firstname']."','".$doc['lastname']."','".$doc['email']."','".$doc['password']."','".$doc['session_id']."','".$doc['cookie_id']."','".$doc['_id']."','".$doc['last_updated']."');";
                    echo date('Y-m-d H:i:s')." - ".$ins."\n";
                    $res = mysqli_query($link, $ins);

                    $sel = "SELECT * FROM admin_cit.".$mysql_options['table']." WHERE email = '".$doc['email']."';";
                    // echo $sel."\n";

                    $res = mysqli_query($link, $sel);
                    $row = mysqli_fetch_assoc($res);

                    $doc[$mysql_options['table'].'_id'] = mysqli_insert_id($link);
                    $doc['last_updated'] = $row['last_updated'];

                    $ret = $couchdb->updateDocument($doc['_id'], $doc);

                    // var_dump($ret);
                }
            }
            break;

        case "track":
            foreach ($allDocs['rows'] as $doc) {
                $doc = $couchdb->readDocument($doc['id']);

                $doc = json_decode($doc, true);

                $sel = "SELECT * FROM admin_cit.".$mysql_options['table']." WHERE uuid = '".$doc['_id']."';";
                // echo $sel."\n";

                $res = mysqli_query($link, $sel);

                if (mysqli_num_rows($res) > 0) {

                } else {
                    $ins = "INSERT INTO admin_cit.".$mysql_options['table']." (event, link, http_referer, user_agent, remote_addr, session_id, cookie_id, uuid, last_updated)
                            VALUES
                            ('".$doc['event']."','".$doc['link']."','".$doc['http_referer']."','".$doc['user_agent']."','".$doc['remote_addr']."','".$doc['session_id']."','".$doc['cookie_id']."','".$doc['_id']."','".$doc['last_updated']."');";
                    echo date('Y-m-d H:i:s')." - ".$ins."\n";
                    $res = mysqli_query($link, $ins);

                    $sel = "SELECT * FROM admin_cit.".$mysql_options['table']." WHERE uuid = '".$doc['_id']."';";
                    // echo $sel."\n";

                    $res = mysqli_query($link, $sel);
                    $row = mysqli_fetch_assoc($res);

                    $doc[$mysql_options['table'].'_id'] = mysqli_insert_id($link);
                    $doc['last_updated'] = $row['last_updated'];

                    $ret = $couchdb->updateDocument($doc['_id'], $doc);

                    // var_dump($ret);
                }
            }
            break;

    }
