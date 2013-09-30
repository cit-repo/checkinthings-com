<?php

class TrackController extends Zend_Rest_Controller
{

    public function init()
    {
        /* Initialize action controller here */
        $obControl = Zend_Controller_Front::getInstance();
        $this->appIni = $obControl->getParam("bootstrap")->getOptions();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function indexAction()
    {
        $this->getResponse()->setBody('List of resources');
        $this->getResponse()->setHttpResponseCode(200);
    }

    public function headAction()
    {
        // you should add your own logic here to check for cache headers from the request
        $this->getResponse()->setBody(null);
    }

    public function optionsAction()
    {
        $this->getResponse()->setBody(null);
        $this->getResponse()->setHeader('Allow', 'OPTIONS, HEAD, INDEX, GET, POST, PUT, DELETE');
    }

    public function postAction()
    {

    }

    public function getAction()
    {

    }

    public function putAction()
    {
        require_once(APPLICATION_PATH.'/../library/Simple/Couchdb.php');

        $couchdb = new SimpleCouchdb($this->appIni['couchdb']);

        $rawBody = $this->getRequest()->getRawBody();

        $arPost = json_decode($rawBody, true);

        $arPost['last_updated'] = date('Y-m-d H:i:s');

        if (isset($arPost['response'])) {
            $arPost['response'] = addslashes(json_encode($arPost['response']));
        }

        $res = $couchdb->createDocument($arPost, "track");

        $this->getResponse()->setBody($res);
        $this->getResponse()->setHttpResponseCode(200);
    }

    public function deleteAction()
    {

    }

}
