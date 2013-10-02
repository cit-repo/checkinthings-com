<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $obControl = Zend_Controller_Front::getInstance();
        $this->appIni = $obControl->getParam("bootstrap")->getOptions();
        // $this->_helper->viewRenderer->setNoRender(true);

        $this->sess = new Zend_Session_Namespace('session');
        $this->view->sess = $this->sess;

        require_once(APPLICATION_PATH.'/../library/Simple/Utils.php');

        $this->view->utils = new Utils();
        $this->view->appIni = $this->appIni;
    }

    public function indexAction()
    {
        $this->view->title = "Home";

        $categories = array(
            'beauty',
            'healthcare',
            'leisure_offers',
            'shopping',
            'travel',
            'local',
            'tech',
            'media',
            'home',
            'art',
            'other'
        );

        $arData = array (
                          'must' => array ( 'main_category' => $categories[rand(0, sizeof($categories)-1)] ),
                          'from' => 0,
                          'size' => '6'
                        );

        $response = $this->productOnApi($arData);

        $this->view->content = $_SERVER['HTTP_HOST'];
        $this->view->request = json_encode($arData);
        $this->view->response = $response;

        $this->getSpecialOffer();
        $this->getPlayProducts();

        $this->searchForm();

        // action body
        // $this->view->content = $_SERVER['HTTP_HOST'];
    }

    private function getSpecialOffer()
    {
        $this->view->specialOfferId = rand(1,1323);

        $this->view->specialOfferProduct = $this->productOnApi(array("must" => array("product_id" => $this->view->specialOfferId)));

        $product = json_decode($this->view->specialOfferProduct, true);

        $this->view->specialOfferLocalImage = $this->appIni['alfa']['host']."/img/product/".$this->view->specialOfferId.".jpg";
        if ($product['hits']['hits'][0]) {
            $this->view->specialOfferRemoteImage = $product['hits']['hits'][0]['_source']['image_url'];
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

    public function searchForm()
    {
        $form = new Application_Form_Search();

        $form->setAction('/search');

        $form->submit->setLabel('Search');

        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $name = $form->getValue('name');

//              $albums = new Application_Model_DbTable_Albums();
//              $albums->addAlbum($artist, $title);

                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }
        }
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


