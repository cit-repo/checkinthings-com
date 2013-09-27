<?php

class Application_Model_DbTable_Category extends Zend_Db_Table_Abstract
{

    protected $_name = 'category';

    public function init()
    {
        /* Initialize action controller here */
        $obControl = Zend_Controller_Front::getInstance();
        $this->appIni = $obControl->getParam("bootstrap")->getOptions();
    }

    public function createCategory($name, $url, $description, $parent, $uuid, $active)
    {
        $data = array(
            'name' => $name,
            'url' => $url,
            'description' => $description,
            'parent' => $parent,
            'uuid' => $uuid,
            'active' => $active,
            'last_updated' => date('Y-m-d H:i:s'),
        );

        $this->insert($data);
    }

    public function readCategory($category_id)
    {
        $row = $this->fetchRow('category_id = ' . (int)$category_id);

        if (!$row) {
            throw new Exception("Could not find row ".(int)$category_id);
        }

        return $row->toArray();
    }

    public function updateCategory($category_id, $name, $url, $description, $parent, $uuid, $active)
    {
        $data = array(
            'name' => $name,
            'url' => $url,
            'description' => $description,
            'parent' => $parent,
            'uuid' => $uuid,
            'active' => $active,
            'last_updated' => date('Y-m-d H:i:s'),
        );

        $this->update($data, 'category_id = '. (int)$category_id);
    }

    public function deleteCategory($category_id)
    {
        $this->delete('category_id =' . (int)$category_id);
    }

}
