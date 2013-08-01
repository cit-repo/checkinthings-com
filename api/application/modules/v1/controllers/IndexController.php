<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $obControl = Zend_Controller_Front::getInstance();
        $this->appIni = $obControl->getParam("bootstrap")->getOptions();
    }

    public function indexAction()
    {
        // action body
        // $this->view->content = $this->appIni['api']['host'];
        $this->view->content = $_SERVER['HTTP_HOST'];
    }

}
