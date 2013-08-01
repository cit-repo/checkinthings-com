<?php

class SearchController extends Zend_Controller_Action
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

    public function searchOnApi($ar_data)
    {
        // action body
        require_once(APPLICATION_PATH.'/../library/Simple/Pest.php');

        $url = "/v1/search";

        $pest = new Pest($this->appIni['api']['host']);
        $pest->post($url, json_encode($ar_data));

        return $pest->lastBody();
    }

    public function recursiveDump($tree, &$arStrings, $search = "name", $nl="\n")
    {

        foreach ($tree as $key => $value) {
            if (is_array($value)) {
                echo $nl;
                recursiveDump($value, $arStrings, $search);
            } else if (is_object($value)) {
                echo $nl;
                recursiveDump($value, $arStrings, $search);
            } else {
                if ($key == $search) {
                    $arStrings[] = $value;
                }
                echo " ".$key." - ".$value." ";
            }
        }

        echo $nl;
    }
}
