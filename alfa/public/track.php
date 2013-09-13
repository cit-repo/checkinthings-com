<?php
    $url = "http://api.cit.localhost/v1/track";

    $raw = file_get_contents("php://input");

    $arData = json_decode($raw, true);

    $arData["remote_addr"] = $_SERVER['REMOTE_ADDR'];
    $arData["user_agent"] = $_SERVER['HTTP_USER_AGENT'];

    $arData["session_id"] = $_COOKIE['PHPSESSID'];

    if (!isset($_COOKIE['cookie_id'])) {
        $cookieId = md5(microtime());
        setcookie("cookie_id", $cookieId);
        $arData["cookie_id"] = $cookieId;
    } else {
        $arData["cookie_id"] = $_COOKIE['cookie_id'];
    }


    $arData["last_updated"] = date('Y-m-d H:i-s');

    $data = json_encode($arData);

    $chlead = curl_init();
    curl_setopt($chlead, CURLOPT_URL, $url);
    curl_setopt($chlead, CURLOPT_USERAGENT, 'Connector/1.0');
    curl_setopt($chlead, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data)));
    curl_setopt($chlead, CURLOPT_VERBOSE, 1);
    curl_setopt($chlead, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chlead, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($chlead, CURLOPT_POSTFIELDS, $data);
    curl_setopt($chlead, CURLOPT_SSL_VERIFYPEER, 0);
    $chleadresult = curl_exec($chlead);
    $chleadapierr = curl_errno($chlead);
    $chleaderrmsg = curl_error($chlead);
    curl_close($chlead);
