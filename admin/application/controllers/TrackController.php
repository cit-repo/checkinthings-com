<?php

class TrackController extends Zend_Controller_Action
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
        $this->view->title = "Track";
        $this->view->headTitle($this->view->title);

        $this->view->config = $this->appIni;

        $track = new Application_Model_DbTable_Track();
        $arTracks = $track->fetchAll('last_updated >= YEAR(NOW())',
                                     'last_updated DESC',
                                     100,
                                     0);
        $this->view->tracks = $arTracks;
    }

    public function createAction()
    {
        $breadcrumb[] = array("Admin" => "/");
        $breadcrumb[] = array("Track" => "/track");
        $this->view->breadcrumb = $breadcrumb;
        $this->view->title = "Create";
        $this->view->headTitle($this->view->title);

        $form = new Application_Form_Track();

        $form->submit->setLabel('Create');
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            if ($form->isValid($formData)) {
                $event = $form->getValue('event');
                $link = $form->getValue('link');
                $httpReferer = $form->getValue('http_referer');

                $track = new Application_Model_DbTable_Track();
                $track->createTrack($event, $link, $httpReferer);
                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }
        }

    }

    public function updateAction()
    {
        $breadcrumb[] = array("Admin" => "/");
        $breadcrumb[] = array("Track" => "/track");
        $this->view->breadcrumb = $breadcrumb;
        $this->view->title = "Update";
        $this->view->headTitle($this->view->title);

        $form = new Application_Form_Track();
        $form->submit->setLabel('Update');
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            if ($form->isValid($formData)) {
                $trackId = (int)$form->getValue('track_id');
                $event = $form->getValue('event');
                $link = $form->getValue('link');
                $httpReferer = $form->getValue('http_referer');

                $track = new Application_Model_DbTable_Track();
                $track->updateTrack($trackId, $event, $link, $httpReferer);

                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }

            $this->_helper->redirector('index');

        } else {
            $trackId = $this->_getParam('id', 0);
            if ($trackId > 0) {
                $track = new Application_Model_DbTable_Track();
                $form->populate($track->readTrack(($trackId)));
            }
        }
    }

    public function deleteAction()
    {
        $breadcrumb[] = array("Admin" => "/");
        $breadcrumb[] = array("Track" => "/track");
        $this->view->breadcrumb = $breadcrumb;
        $this->view->title = "Delete";
        $this->view->headTitle($this->view->title);

        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Yes') {
                $trackId = $this->getRequest()->getPost('track_id');
                $track = new Application_Model_DbTable_Track();
                $track->deleteTrack($trackId);
            }
            $this->_helper->redirector('index');
        } else {
            $id = $this->_getParam('id', 0);
            $track = new Application_Model_DbTable_Track();
            $this->view->track = $track->readTrack($id);
        }
    }
}
