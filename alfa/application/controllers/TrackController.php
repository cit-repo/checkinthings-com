<?php

class TrackController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $obControl = Zend_Controller_Front::getInstance();
        $this->appIni = $obControl->getParam("bootstrap")->getOptions();
        $this->_helper->layout()->disableLayout();
        // $this->_helper->viewRenderer->setNoRender(true);

        $this->sess = new Zend_Session_Namespace('session');

    }

    public function indexAction()
    {
        $url = $this->appIni['api']['host']."/v1/track";

        $jsonRequest = $this->view->request= $this->getRequest()->getRawBody();
        $arData = json_decode($jsonRequest, true);

        $arData["remote_addr"] = $_SERVER['REMOTE_ADDR'];
        $arData["user_agent"] = $_SERVER['HTTP_USER_AGENT'];

        $arData["session_id"] = $_COOKIE['PHPSESSID'];

        if (isset($this->sess->customer_uuid)) {
            $arData["customer_uuid"] = $this->sess->customer_uuid;
        }

        if (!isset($_COOKIE['PHPCOOKID'])) {
            $cookieId = substr(md5(microtime()),0,26);
            setcookie("PHPCOOKID", $cookieId);
            $arData["cookie_id"] = $cookieId;
        } else {
            $arData["cookie_id"] = $_COOKIE['PHPCOOKID'];
        }

        $arData["last_updated"] = date('Y-m-d H:i:s');

        $data = json_encode($arData);

        echo $data; die;

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

        if (!$chleadapierr) {
            $this->view->response = '{"track":"true"}';
        } else {
            $this->view->response = '{"track":"false"}';
        }

    }

}
