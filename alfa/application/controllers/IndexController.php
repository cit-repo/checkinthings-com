<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $obControl = Zend_Controller_Front::getInstance();
        $this->appIni = $obControl->getParam("bootstrap")->getOptions();
        // $this->_helper->viewRenderer->setNoRender(true);
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

        if (!isset($arData['must'])) {
            $arData = array( 'must' => array ( 'main_category' => $categories[rand(0, sizeof($categories))] ),
                             'from' => 0, 'size' => '6');
        }

        $response = $this->searchOnApi($arData);

        $this->view->content = $_SERVER['HTTP_HOST'];
        $this->view->request = json_encode($arData);
        $this->view->response = $response;

        $this->view->specialOffer = rand(1,1323);

        $this->searchForm();

        // action body
        // $this->view->content = $_SERVER['HTTP_HOST'];
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

    public function loginAction()
    {

    }

    public function searchOnApi($ar_data)
    {
        // action body
        require_once(APPLICATION_PATH.'/../library/Simple/Pest.php');

        $url = "/v1/search";

        $pest = new Pest($this->appIni['api']['host']);
        $pest->post($url, json_encode($ar_data));

        return $pest->lastBody();
    }

}


