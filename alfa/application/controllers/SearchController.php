<?php

class SearchController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $obControl = Zend_Controller_Front::getInstance();
        $this->appIni = $obControl->getParam("bootstrap")->getOptions();
        // $this->_helper->viewRenderer->setNoRender(true);

        require_once(APPLICATION_PATH.'/../library/Simple/Utils.php');

        $this->view->utils = new Utils();
        $this->view->appIni = $this->appIni;
        
        $this->getPlayProducts();
    }

    public function indexAction()
    {
        $breadcrumb[] = array("Home" => "/");
        $this->view->breadcrumb = $breadcrumb;

        $this->view->title = "Search";

        $arPost = $this->getRequest()->getRawBody();

        if ($arPost != '') {

            $arPost = explode("&", $arPost);

            $arData = array();

            foreach ($arPost as $arInfo) {
                $arInfo = explode("=", $arInfo);
                if ($arInfo[0] != 'id' && $arInfo[0] != 'submit' ) {
                    if ($arInfo[1] != '') {
                        $arData['must'] = array($arInfo[0] => $arInfo[1]);
                    }
                }
            }

            if (!isset($arData['must'])) {
                $arData = array( 'must' => array ( 'uuid' => '*') );
            }

            $response = $this->searchOnApi($arData);

            $this->view->content = $_SERVER['HTTP_HOST'];
            $this->view->request = json_encode($arData);
            $this->view->response = $response;
        }

    }


    private function getPlayProducts()
    {
        $this->view->productAId = rand(1,1323);
        $this->view->productBId = rand(1,1323);

        $this->view->productA = $this->productOnApi(array("must" => array("product_id" => $this->view->productAId)));
        $this->view->productB = $this->productOnApi(array("must" => array("product_id" => $this->view->productBId)));

        $productA = json_decode($this->view->productA, true);
        $productB = json_decode($this->view->productB, true);

        $this->view->productALocalImage = $this->appIni['alfa']['host']."/img/product/".$this->view->productAId.".jpg";
        $this->view->productBLocalImage = $this->appIni['alfa']['host']."/img/product/".$this->view->productBId.".jpg";

        if ($productA['hits']['hits'][0]) {
            $this->view->productARemoteImage = $productA['hits']['hits'][0]['_source']['image_url'];
        }

        if ($productB['hits']['hits'][0]) {
            $this->view->productBRemoteImage = $productB['hits']['hits'][0]['_source']['image_url'];
        }

    }

    public function searchOnApi($ar_data, $event=false)
    {
        // action body
        require_once(APPLICATION_PATH.'/../library/Simple/Pest.php');

        $url = "/v1/search";

        $pest = new Pest($this->appIni['api']['host']);
        $pest->post($url, json_encode($ar_data));

        $pest->log_request($this->appIni['includePaths']['logs']."/api.log", date('Y-m-d H:i:s')." - ".$url.": REQUEST - ".json_encode($pest->last_request));
        $pest->log_request($this->appIni['includePaths']['logs']."/api.log", date('Y-m-d H:i:s')." - ".$url.": RESPONSE - ".json_encode($pest->lastBody()));

        return $pest->lastBody();
    }


    public function productOnApi($ar_data, $event=false)
    {
        // action body
        require_once(APPLICATION_PATH.'/../library/Simple/Pest.php');

        $url = "/v1/product";

        $pest = new Pest($this->appIni['api']['host']);
        $pest->post($url, json_encode($ar_data));

        $pest->log_request($this->appIni['includePaths']['logs']."/api.log", date('Y-m-d H:i:s')." - ".$url.": REQUEST - ".json_encode($pest->last_request));
        $pest->log_request($this->appIni['includePaths']['logs']."/api.log", date('Y-m-d H:i:s')." - ".$url.": RESPONSE - ".json_encode($pest->lastBody()));

        return $pest->lastBody();
    }

}
