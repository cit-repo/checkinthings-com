<?php

class LoginController extends Zend_Controller_Action
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
        $breadcrumb[] = array("Home" => "/");

        $this->view->breadcrumb = $breadcrumb;

        $this->view->title = "Login";
    }

}


