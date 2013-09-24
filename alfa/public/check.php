<?php
    if (strstr($_SERVER['HTTP_HOST'], "localhost")) {
        $baseUrl = "http://api.cit.localhost/v1";
    } else {
        $baseUrl = "http://api.checkinthings.com/v1";
    }

    $raw = file_get_contents("php://input");

    $arData = json_decode($raw, true);

    $url = $baseUrl."/customer/email/".$_GET['email'];

    $chlead = curl_init();
    curl_setopt($chlead, CURLOPT_URL, $url);
    curl_setopt($chlead, CURLOPT_USERAGENT, 'Connector/1.0');
    // curl_setopt($chlead, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($chlead, CURLOPT_VERBOSE, 1);
    curl_setopt($chlead, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chlead, CURLOPT_CUSTOMREQUEST, "GET");
    // curl_setopt($chlead, CURLOPT_POSTFIELDS, $data);
    curl_setopt($chlead, CURLOPT_SSL_VERIFYPEER, 0);
    $chleadresult = curl_exec($chlead);
    $chleadapierr = curl_errno($chlead);
    $chleaderrmsg = curl_error($chlead);
    curl_close($chlead);

    if (strstr($chleadresult, $_GET['email'])) {
        echo '{"check":"true"}';
    } else {
        echo '{"check":"false"}';
    }
