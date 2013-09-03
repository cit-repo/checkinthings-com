<?php

class CustomerController extends Zend_Controller_Action
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

        $this->view->title = "Customer";

        $arRequest = $this->getRequest()->getRawBody();

        $rawData = $this->getRawDataFromArRequest($arRequest);

        $this->view->request = $rawData;

        if ($_POST['event'] == 'login') {
            $this->view->login = $this->customerOnApi($rawData, "login");
        } else if ($_POST['event'] == 'register') {
            $this->view->register = $this->customerOnApi($rawData, "register");
        }

    }

    private function getRawDataFromArRequest ($ar_request) {
        if ($ar_request != '') {

            $ar_request = explode("&", $ar_request);

            $arData = array();

            foreach ($ar_request as $arInfo) {
                $arInfo = explode("=", $arInfo);
                if ($arInfo[0] != 'id' && $arInfo[0] != 'submit' ) {
                    if ($arInfo[1] != '') {
                        if ($arInfo[0] != "event") {
                            $arData[$arInfo[0]] = utf8_encode(urldecode($arInfo[1]));
                        }

                        if ($arInfo[0] == "password") {
                            $arData[$arInfo[0]] = md5($this->appIni['md5']['seed'].$arInfo[1]);
                        }
                    }
                }
            }
        }

        return $arData;
    }

    public function customerOnApi($ar_data, $event=false)
    {
        require_once(APPLICATION_PATH.'/../library/Simple/Pest.php');

        $url = "/v1/customer";

        $pest = new Pest($this->appIni['api']['host']);

        if ($event == "login") {
            $pest->post($url, json_encode($ar_data));

            // $ar_data = array();
            $ar_data['_id'] = 'b8b15cab474387f6aa07ed2cc3000971';

            $pest->get($url."/".$ar_data['_id']);

        } else if ($event == "register") {
            $pest->put($url, json_encode($ar_data));
        }

        return $pest->lastBody();
    }

}
