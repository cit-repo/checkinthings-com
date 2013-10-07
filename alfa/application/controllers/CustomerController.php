<?php

class CustomerController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $obControl = Zend_Controller_Front::getInstance();
        $this->appIni = $obControl->getParam("bootstrap")->getOptions();
        // $this->_helper->viewRenderer->setNoRender(true);

        $this->sess = new Zend_Session_Namespace('session');

        $this->getPlayProducts();
    }

    public function indexAction()
    {
        if ($this->sess->customer_uuid) {
            $arSearchFirst['attribute'] = "id";
            $arSearchFirst['value'] = $this->sess->customer_uuid;
            $this->view->customer = $this->customerOnApi($arSearchFirst, "search_first");
        } else if ($this->sess->facebook_uid) {

        }

        $breadcrumb[] = array("Home" => "/");

        $this->view->breadcrumb = $breadcrumb;

        $this->view->title = "Customer";

        $arRequest = $this->view->request= $this->getRequest()->getRawBody();

        $rawData = $this->getRawDataFromRawBody($arRequest);

        if (isset($_POST['event']) && $_POST['event'] == 'login') {
            $this->login($rawData);

        } else if (isset($_POST['event']) && $_POST['event'] == 'register') {
            $this->register($rawData);

        } else if (isset($_POST['event']) && $_POST['event'] == 'multistep') {
            $this->multistep($rawData);
        }

        $this->view->sess = $this->sess;
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

    public function login($raw_data)
    {
        // $raw_data['email'] = explode("@", $raw_data['email']);
        // $raw_data['email'] = $raw_data['email'][0];

        $password = "";

        if (isset($raw_data['email']) && isset($raw_data['password'])) {
            $this->view->login = $this->customerOnApi(array("attribute" => "email", "value" => $raw_data['email']), "search_first");

            $arLogin = json_decode($this->view->login, true);

            $password = md5($this->appIni['md5']['seed'].$raw_data['password']);
        }

        if (isset($arLogin['hits']['hits'][0]['_id']) && $arLogin['hits']['hits'][0]['_source']['password'] == $raw_data['password']) {
            $this->sess->customer_uuid = $arLogin['hits']['hits'][0]['_id'];
            $this->sess->customer_firstname = $arLogin['hits']['hits'][0]['_source']['firstname'];
            $this->sess->customer_lastname = $arLogin['hits']['hits'][0]['_source']['lastname'];
            $this->sess->customer_email = $arLogin['hits']['hits'][0]['_source']['email'];

            $this->view->sess = $this->sess;

            $this->view->success = "Hello ".$this->sess->customer_firstname.", good to have you back.";
        } else {
            $this->view->error = "Sorry, but we did not manage to recognize you.";

        }

        if ($this->sess->customer_uuid) {
            $arSearchFirst['attribute'] = "id";
            $arSearchFirst['value'] = $this->sess->customer_uuid;
            $this->view->customer = $this->customerOnApi($arSearchFirst, "search_first");
        }

    }

    public function register($raw_data)
    {
        if ($this->sess->customer_uuid) {
            $arSearchFirst['attribute'] = "id";
            $arSearchFirst['value'] = $this->sess->customer_uuid;
            $this->view->customer = $this->customerOnApi($arSearchFirst, "search_first");
        }

        $origin = $raw_data;

        $origin['email'] = explode("@", $origin['email']);
        $origin['email'] = $origin['email'][0];

        unset($origin['firstname']);
        unset($origin['lastname']);
        unset($origin['password']);
        unset($origin['cpassword']);

        $this->view->check = $this->requestApi("customer", "check", $origin);

        $arCheck = json_decode($this->view->check, true);

        if (isset($arCheck['hits']['hits'][0]['_id'])) {
            $this->view->error = "Sorry, but this email is already registered.";

        } else {
            $this->view->register = $this->requestApi("customer", "register", $raw_data);

            $res = json_decode($this->view->register, true);

            $this->view->success = "Welcome ".$raw_data['firstname'].", thanks for joining our world.";

            $this->sess->customer_uuid = $res['id'];
            $this->sess->customer_firstname = $raw_data['firstname'];
            $this->sess->customer_lastname = $raw_data['lastname'];
            $this->sess->customer_email = $raw_data['email'];
        }
    }

    public function multistep($raw_data)
    {
        if ($this->sess->customer_uuid) {
            $arSearchFirst['attribute'] = "id";
            $arSearchFirst['value'] = $this->sess->customer_uuid;
            $this->view->customer = $this->customerOnApi($arSearchFirst, "search_first");
        }

        $origin = $raw_data;

        // $origin['email'] = explode("@", $origin['email']);
        // $origin['email'] = $origin['email'][0];

        unset($origin['username']);
        unset($origin['firstname']);
        unset($origin['lastname']);
        unset($origin['password']);
        unset($origin['cpassword']);
        unset($origin['age']);
        unset($origin['gender']);
        unset($origin['country']);

        $origin['attribute'] = "email";
        $origin['value'] = $origin['email'];

        $this->view->check = $this->customerOnApi($origin, "search_first");

        // print_r($this->view->check);

        $arCheck = json_decode($this->view->check, true);

        if (isset($arCheck['hits']['hits'][0]['_id'])) {
            $this->view->error = "Sorry, but this email is already registered.";

        } else {
            unset($raw_data['cpassword']);

            if (isset($_COOKIE['PHPSESSID'])) $raw_data["session_id"] = $_COOKIE['PHPSESSID'];
            if (isset($_COOKIE['PHPCOOKID'])) $raw_data["cookie_id"] = $_COOKIE['PHPCOOKID'];

            $this->view->register = $this->requestApi("customer", "register", $raw_data);

            $res = json_decode($this->view->register, true);

            $this->view->success = "Welcome ".$raw_data['firstname'].", thanks for joining our world.";

            $this->sess->customer_uuid = $res['id'];
            $this->sess->customer_firstname = $raw_data['firstname'];
            $this->sess->customer_lastname = $raw_data['lastname'];
            $this->sess->customer_email = $raw_data['email'];

            $account = urlencode(trim("checkinthings"));
            $fromName = urlencode(trim("Check In Things"));

            $toName = urlencode(trim($raw_data['firstname']));
            $toAddr = urlencode(trim($raw_data['email']));

            $subject = urlencode(trim("Welcome to Check In Things, ".$toName));
            $body = urlencode(trim("Welcome to Check In Things, ".$toName.". We registered successfully your account with email ".$toAddr.". <br><br><br><b>Thanks for joining our world !!!</b>"));

            $url = "/rest/?account=$account&from_name=$fromName&to_name=$toName&to_addr=$toAddr&subject=$subject&body=$body";

            $this->sendEmail($url);
        }
    }

    public function sendEmail($url)
    {
        // action body
        require_once(APPLICATION_PATH.'/../library/Simple/Pest.php');

        $pest = new Pest("http://bidimail.com");
        $pest->get($url);

        $pest->log_request($this->appIni['includePaths']['logs']."/api.log", date('Y-m-d H:i:s')." - ".$url.": REQUEST - ".json_encode($pest->last_request));
        $pest->log_request($this->appIni['includePaths']['logs']."/api.log", date('Y-m-d H:i:s')." - ".$url.": RESPONSE - ".json_encode($pest->lastBody()));

        return $pest->lastBody();
    }

    public function logoutAction()
    {
        $breadcrumb[] = array("Home" => "/");
        $breadcrumb[] = array("Customer" => "/customer");

        $this->view->breadcrumb = $breadcrumb;

        $this->view->title = "Logout";

        $this->view->success = "Goodbye ".$this->sess->customer_firstname.", we hope to see you soon again.";

        $this->sess->unsetAll();
    }

    private function getRawDataFromRawBody($ar_request)
    {
        $arData = array();

        if ($ar_request != '') {

            $ar_request = explode("&", $ar_request);

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
        // action body
        require_once(APPLICATION_PATH.'/../library/Simple/Pest.php');

        $url = "/v1/customer";

        if ($event == "search_first") {
            $url = $url."/".$ar_data['attribute']."/".$ar_data['value'];
            // echo $url;
            $pest = new Pest($this->appIni['api']['host']);
            $pest->get($url);
        } else {
            $pest = new Pest($this->appIni['api']['host']);
            $pest->post($url, json_encode($ar_data));
        }

        $pest->log_request($this->appIni['includePaths']['logs']."/api.log", date('Y-m-d H:i:s')." - ".$url.": REQUEST - ".json_encode($pest->last_request));
        $pest->log_request($this->appIni['includePaths']['logs']."/api.log", date('Y-m-d H:i:s')." - ".$url.": RESPONSE - ".json_encode($pest->lastBody()));

        return $pest->lastBody();
    }

    public function requestApi($controller, $event, $ar_data)
    {
        require_once(APPLICATION_PATH.'/../library/Simple/Pest.php');

        $url = "/v1/$controller";

        $pest = new Pest($this->appIni['api']['host']);

        if ($event == "login") {
            $pest->post($url, json_encode(array("must" => $ar_data)));

        } else if ($event == "check") {
            $pest->post($url, json_encode(array("must" => $ar_data)));

        } else if ($event == "register") {
            $pest->put($url, json_encode($ar_data));

        }

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
