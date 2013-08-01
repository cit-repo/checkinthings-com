<?php

class ProductController extends Zend_Rest_Controller
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
        $this->getResponse()->setBody('Resource created');
        $this->getResponse()->setHttpResponseCode(200);
    }

    public function getAction()
    {
        require_once(APPLICATION_PATH.'/../library/Simple/Couchdb.php');

        $id = $this->_getParam('id');

        $entity = "product";

        $this->appIni['couchdb']['database'] = $this->appIni['couchdb']['database'].$entity;

        $couchdb = new SimpleCouchdb($this->appIni['couchdb']);

        $res = $couchdb->readDocument($id);

        header('Content-type: application/json; charset=utf-8');

        $res = str_replace("\u20ac", "€", $res);
        $res = str_replace("\u00a0", "", $res);

        $res = str_replace("\u00e1", "á", $res);
        $res = str_replace("\u00e9", "é", $res);
        $res = str_replace("\u00ed", "í", $res);
        $res = str_replace("\u00f3", "ó", $res);
        $res = str_replace("\u00fa", "ú", $res);

        $this->getResponse()->setBody($res);
        $this->getResponse()->setHttpResponseCode(200);
    }

    public function putAction()
    {
        $id = $this->_getParam('id');

        $this->getResponse()->setBody(sprintf('Resource #%s updated', $id));
        $this->getResponse()->setHttpResponseCode(200);
    }

    public function deleteAction()
    {
        $id = $this->_getParam('id');

        $this->getResponse()->setBody(sprintf('Resource #%s deleted', $id));
        $this->getResponse()->setHttpResponseCode(200);
    }

}
