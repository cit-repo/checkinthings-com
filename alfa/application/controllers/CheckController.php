<?php

class CheckController extends Zend_Controller_Action
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
        $url = $this->appIni['api']['host']."/v1/".$this->getRequest()->getParam('entity')."/".$this->getRequest()->getParam('attribute')."/".$this->getRequest()->getParam('value');

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

        if (strstr($chleadresult, $this->getRequest()->getParam('value'))) {
            $this->view->response = '{"check":"true"}';
        } else {
            $this->view->response = '{"check":"false"}';
        }

    }

    private function getRawDataFromRequestUri($request_uri)
    {
        $arData = array();

        if (strstr($request_uri, "?")) {
            $request_uri = explode("?", $request_uri);

            $request_uri = explode("&", $request_uri[1]);

            foreach ($request_uri as $keyvalue) {
                $keyvalue = explode("=", $keyvalue);

                $arData[$keyvalue[0]] = $keyvalue[1];
            }
        }

        return $arData;
    }
}
