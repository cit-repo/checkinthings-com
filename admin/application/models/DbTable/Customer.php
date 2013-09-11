<?php

class Application_Model_DbTable_Customer extends Zend_Db_Table_Abstract
{

    protected $_name = 'customer';

    public function init()
    {
        /* Initialize action controller here */
        $obControl = Zend_Controller_Front::getInstance();
        $this->appIni = $obControl->getParam("bootstrap")->getOptions();
    }

    public function createCustomer($firstname, $lastname, $email, $password)
    {
        $data = array(
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => md5($this->appIni['md5']['seed'].$password),
            'last_updated' => date('Y-m-d H:i:s'),
        );
        $this->insert($data);
    }

    public function readCustomer($customer_id)
    {
        $row = $this->fetchRow('customer_id = ' . (int)$customer_id);
        if (!$row) {
            throw new Exception("Could not find row ".(int)$customer_id);
        }
        return $row->toArray();
    }

    public function updateCustomer($customer_id, $firstname, $lastname, $email, $password)
    {
        $data = array(
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'last_updated' => date('Y-m-d H:i:s'),
        );

        $customer = $this->readCustomer($customer_id);
        if ($customer['password'] != $password) {
            $data['password'] = md5($this->appIni['md5']['seed'].$password);
        }

        $this->update($data, 'customer_id = '. (int)$customer_id);
    }

    public function deleteCustomer($customer_id)
    {
        $this->delete('customer_id =' . (int)$customer_id);
    }

}
