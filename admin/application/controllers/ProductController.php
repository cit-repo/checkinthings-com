<?php

class ProductController extends Zend_Controller_Action
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
        $this->view->title = "Product";
        $this->view->headTitle($this->view->title);

        $this->view->config = $this->appIni;

        $product = new Application_Model_DbTable_Product();
        $arProducts = $product->fetchAll('last_updated >= YEAR(NOW())',
                                         'last_updated DESC',
                                         100,
                                         0);
        $this->view->products = $arProducts;
    }

    public function createAction()
    {
        $breadcrumb[] = array("Admin" => "/");
        $breadcrumb[] = array("Product" => "/product");
        $this->view->breadcrumb = $breadcrumb;
        $this->view->title = "Create";
        $this->view->headTitle($this->view->title);

        $form = new Application_Form_Product();

        $form->submit->setLabel('Create');
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            if ($form->isValid($formData)) {
                $feed = $form->getValue('feed');
                $channel = $form->getValue('channel');
                $mainCategory = $form->getValue('main_category');
                $name = $form->getValue('name');
                $description = $form->getValue('description');
                $productUrl = $form->getValue('product_url');
                $imageUrl = $form->getValue('image_url');

                $product = new Application_Model_DbTable_Product();
                $product->createProduct($feed, $channel, $mainCategory, $name, $description, $productUrl, $imageUrl);
                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }
        }

    }

    public function feedAction()
    {
        $breadcrumb[] = array("Admin" => "/");
        $breadcrumb[] = array("Product" => "/product");
        $this->view->breadcrumb = $breadcrumb;
        $this->view->title = "Feed";
        $this->view->headTitle($this->view->title);

        $feed = $this->_getParam('code', 0);

        $this->view->title = $feed;

        $product = new Application_Model_DbTable_Product();
        $this->view->arProducts = $product->readAllByFeed($feed);
    }

    public function updateAction()
    {
        $breadcrumb[] = array("Admin" => "/");
        $breadcrumb[] = array("Product" => "/product");
        $this->view->breadcrumb = $breadcrumb;
        $this->view->title = "Update";
        $this->view->headTitle($this->view->title);

        $form = new Application_Form_Product();
        $form->submit->setLabel('Update');
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            if ($form->isValid($formData)) {
                $productId = (int)$form->getValue('product_id');
                $feed = $form->getValue('feed');
                $channel = $form->getValue('channel');
                $mainCategory = $form->getValue('main_category');
                $name = $form->getValue('name');
                $description = $form->getValue('description');
                $productUrl = $form->getValue('product_url');
                $imageUrl = $form->getValue('image_url');

                $product = new Application_Model_DbTable_Product();

                $arEav = $product->readProductEav($productId);

                foreach ($arEav as $key => $value) {
                    $newKey = "eav_".$key;
                    $arKeyValueEav[] = array($newKey => $value);
                }

                $product->updateProduct($productId, $feed, $channel, $mainCategory, $name, $description, $productUrl, $imageUrl);

                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }

            $this->_helper->redirector('index');

        } else {
            $productId = $this->_getParam('id', 0);
            if ($productId > 0) {
                $product = new Application_Model_DbTable_Product();
                $form->populate($product->readProduct(($productId)));
            }
        }
    }

    public function deleteAction()
    {
        $breadcrumb[] = array("Admin" => "/");
        $breadcrumb[] = array("Product" => "/product");
        $this->view->breadcrumb = $breadcrumb;
        $this->view->title = "Delete";
        $this->view->headTitle($this->view->title);

        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Yes') {
                $productId = $this->getRequest()->getPost('product_id');
                $product = new Application_Model_DbTable_Product();
                $product->deleteProduct($productId);
            }
            $this->_helper->redirector('index');
        } else {
            $id = $this->_getParam('id', 0);
            $product = new Application_Model_DbTable_Product();
            $this->view->product = $product->readProduct($id);
        }
    }
}
