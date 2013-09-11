<?php

class CustomerController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $obControl = Zend_Controller_Front::getInstance();
        $this->appIni = $obControl->getParam("bootstrap")->getOptions();
    }

    public function indexAction()
    {
        $breadcrumb[] = array("Admin" => "/");

        $this->view->breadcrumb = $breadcrumb;

        $this->view->title = "Customer";

        $this->view->config = $this->appIni;

        $customer = new Application_Model_DbTable_Customer();
        $arCustomers = $customer->fetchAll();
        $this->view->customers = $arCustomers;
    }

    public function createAction()
    {
        $form = new Application_Form_Customer();

        $form->submit->setLabel('Create');
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            if ($form->isValid($formData)) {
                $firstname = $form->getValue('firstname');
                $lastname = $form->getValue('lastname');
                $email = $form->getValue('email');
                $password = $form->getValue('password');

                $customer = new Application_Model_DbTable_Customer();
                $customer->createCustomer($firstname, $lastname, $email, $password);
                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }
        }

    }

    public function updateAction()
    {
        $form = new Application_Form_Customer();
        $form->submit->setLabel('Update');
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            if ($form->isValid($formData)) {
                $customerId = (int)$form->getValue('customer_id');
                $firstname = $form->getValue('firstname');
                $lastname = $form->getValue('lastname');
                $email = $form->getValue('email');
                $password = $form->getValue('password');

                $customer = new Application_Model_DbTable_Customer();
                $customer->updateCustomer($customerId, $firstname, $lastname, $email, $password);

                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }

            $this->_helper->redirector('index');

        } else {
            $customerId = $this->_getParam('id', 0);
            if ($customerId > 0) {
                $customer = new Application_Model_DbTable_Customer();
                $form->populate($customer->readCustomer(($customerId)));
            }
        }
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Yes') {
                $customerId = $this->getRequest()->getPost('customer_id');
                $customer = new Application_Model_DbTable_Customer();
                $customer->deleteCustomer($customerId);
            }
            $this->_helper->redirector('index');
        } else {
            $id = $this->_getParam('id', 0);
            $customer = new Application_Model_DbTable_Customer();
            $this->view->customer = $customer->readCustomer($id);
        }
    }
}
