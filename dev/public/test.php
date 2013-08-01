<?php
    if ($_POST) $json = json_encode($_POST);
    if ($_GET) $json = json_encode($_GET);

    echo $json;
?>