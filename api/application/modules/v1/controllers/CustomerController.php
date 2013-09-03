<?php

class CustomerController extends Zend_Rest_Controller
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
        require_once(APPLICATION_PATH.'/../library/Simple/Elasticsearch.php');

        $entity = "customer";

        $this->appIni['elasticsearch']['index'] = $this->appIni['elasticsearch']['index'].$entity;

        $elasticsearch = new SimpleElasticsearch($this->appIni['elasticsearch']);

        $rawBody = $this->getRequest()->getRawBody();

        $arPost = json_decode($rawBody, true);

        $arMust = array();
        $arMustNot = array();
        $arShould = array();
        $from = 0;
        $size = 100;
        $sort = true;

        if (isset($arPost['must'])) {
            $arMust = $arPost['must'];
        }

        if (isset($arPost['must_not'])) {
            $arMustNot = $arPost['must_not'];
        }

        if (isset($arPost['should'])) {
            $arShould = $arPost['should'];
        }

        if (isset($arPost['from'])) {
            $from = $arPost['from'];
        }

        if (isset($arPost['size'])) {
            $size = $arPost['size'];
        }

        if (isset($arPost['sort'])) {
            $sort = $arPost['sort'];
        }

        $res = $elasticsearch->search($arMust, $arMustNot, $arShould, $from, $size, $sort, $this->appIni['elasticsearch']['index']);

        $this->getResponse()->setBody($res);

        $this->getResponse()->setHttpResponseCode(200);
    }

    public function getAction()
    {
        require_once(APPLICATION_PATH.'/../library/Simple/Couchdb.php');

        $id = $this->_getParam('id');

        $entity = "customer";

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
        require_once(APPLICATION_PATH.'/../library/Simple/Couchdb.php');

        $couchdb = new SimpleCouchdb($this->appIni['couchdb']);

        $rawBody = $this->getRequest()->getRawBody();

        $arPost = json_decode($rawBody, true);

        $res = $couchdb->createDocument($arPost);

        $this->getResponse()->setBody($res);
        $this->getResponse()->setHttpResponseCode(200);
    }

    public function deleteAction()
    {
        $id = $this->_getParam('id');

        $this->getResponse()->setBody(sprintf('Customer #%s delete', $id));
        $this->getResponse()->setHttpResponseCode(200);
    }

}
