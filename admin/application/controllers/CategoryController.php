<?php

class CategoryController extends Zend_Controller_Action
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
        $this->view->title = "Category";
        $this->view->headTitle($this->view->title);

        $this->view->config = $this->appIni;

        $category = new Application_Model_DbTable_Category();
        $arCategories = $category->fetchAll('last_updated >= YEAR(NOW())', 'last_updated DESC', 100, 0);
        $this->view->categorys = $arCategories;
    }

    public function createAction()
    {
        $breadcrumb[] = array("Admin" => "/");
        $breadcrumb[] = array("Category" => "/category");
        $this->view->breadcrumb = $breadcrumb;
        $this->view->title = "Create";
        $this->view->headTitle($this->view->title);

        $form = new Application_Form_Category();

        $form->submit->setLabel('Create');
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            if ($form->isValid($formData)) {
                $name = $form->getValue('name');
                $url = $form->getValue('url');
                $description = $form->getValue('description');
                $parent = $form->getValue('parent');
                $uuid = $form->getValue('uuid');
                $active = $form->getValue('active');

                $category = new Application_Model_DbTable_Category();
                $category->createCategory($name, $url, $description, $parent, $uuid, $active);
                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }
        }

    }

    public function updateAction()
    {
        $breadcrumb[] = array("Admin" => "/");
        $breadcrumb[] = array("Category" => "/category");
        $this->view->breadcrumb = $breadcrumb;
        $this->view->title = "Update";
        $this->view->headTitle($this->view->title);

        $form = new Application_Form_Category();
        $form->submit->setLabel('Update');
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            if ($form->isValid($formData)) {
                $categoryId = (int)$form->getValue('category_id');
                $name = $form->getValue('name');
                $url = $form->getValue('url');
                $description = $form->getValue('description');
                $parent = $form->getValue('parent');
                $uuid = $form->getValue('uuid');
                $active = $form->getValue('active');

                $category = new Application_Model_DbTable_Category();
                $category->updateCategory($categoryId, $name, $url, $description, $parent, $uuid, $active);

                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }

            $this->_helper->redirector('index');

        } else {
            $categoryId = $this->_getParam('id', 0);
            if ($categoryId > 0) {
                $category = new Application_Model_DbTable_Category();
                $form->populate($category->readCategory(($categoryId)));
            }
        }
    }

    public function deleteAction()
    {
        $breadcrumb[] = array("Admin" => "/");
        $breadcrumb[] = array("Category" => "/category");
        $this->view->breadcrumb = $breadcrumb;
        $this->view->title = "Delete";
        $this->view->headTitle($this->view->title);

        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Yes') {
                $categoryId = $this->getRequest()->getPost('category_id');
                $category = new Application_Model_DbTable_Category();
                $category->deleteCategory($categoryId);
            }
            $this->_helper->redirector('index');
        } else {
            $id = $this->_getParam('id', 0);
            $category = new Application_Model_DbTable_Category();
            $this->view->category = $category->readCategory($id);
        }
    }
}
