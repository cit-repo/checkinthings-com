<?php

class Application_Model_DbTable_Track extends Zend_Db_Table_Abstract
{

    protected $_name = 'track';

    public function init()
    {
        /* Initialize action controller here */
        $obControl = Zend_Controller_Front::getInstance();
        $this->appIni = $obControl->getParam("bootstrap")->getOptions();
    }

    public function createTrack($event, $link, $http_referer)
    {
        $data = array(
            'event' => $event,
            'link' => $link,
            'http_referer' => $http_referer,
            'last_updated' => date('Y-m-d H:i:s'),
        );
        $this->insert($data);
    }

    public function readTrack($track_id)
    {
        $row = $this->fetchRow('track_id = ' . (int)$track_id);
        if (!$row) {
            throw new Exception("Could not find row ".(int)$track_id);
        }
        return $row->toArray();
    }

    public function updateTrack($track_id, $event, $link, $http_referer)
    {
        $data = array(
            'event' => $event,
            'link' => $link,
            'http_referer' => $http_referer,
            'last_updated' => date('Y-m-d H:i:s'),
        );
        $this->update($data, 'track_id = '. (int)$track_id);
    }

    public function deleteTrack($track_id)
    {
        $this->delete('track_id =' . (int)$track_id);
    }

}
