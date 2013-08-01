<?php

class Application_Model_DbTable_Product extends Zend_Db_Table_Abstract
{

    protected $_name = 'product';

    public function init()
    {
        /* Initialize action controller here */
        $obControl = Zend_Controller_Front::getInstance();
        $this->appIni = $obControl->getParam("bootstrap")->getOptions();
    }

    public function createProduct($feed, $channel, $main_category, $name, $description, $image_url)
    {
        $data = array(
            'feed' => $feed,
            'channel' => $channel,
            'main_category' => $main_category,
            'name' => $name,
            'description' => $description,
            'image_url' => $image_url,
        );
        $this->insert($data);
    }

    public function readProduct($product_id)
    {
        $row = $this->fetchRow('product_id = ' . (int)$product_id);
        if (!$row) {
            throw new Exception("Could not find row ".(int)$product_id);
        }
        return $row->toArray();
    }

    public function updateProduct($product_id, $feed, $channel, $main_category, $name, $description, $image_url)
    {
        $data = array(
            'feed' => $feed,
            'channel' => $channel,
            'main_category' => $main_category,
            'name' => $name,
            'description' => $description,
            'image_url' => $image_url,
        );
        $this->update($data, 'product_id = '. (int)$product_id);
    }

    public function deleteProduct($product_id)
    {
        $this->delete('product_id =' . (int)$product_id);
    }

    public function readAllByFeed($feed)
    {
        require_once(APPLICATION_PATH.'/../library/Simple/Mysql.php');

        $limit = 10000;

        $select = $this->select()
                       ->where('feed = ?', $feed)
                       ->limit($limit);

        $feedProducts = $this->fetchAll($select);
        // $feedProducts = $this->fetchAll();

        $mysql = new SimpleMysql(false, $this->appIni['resources']['db']['params']);

        $res = array();

        foreach ($feedProducts as $feedProduct) {

            foreach($feedProduct as $key => $value) {
                if ($value) $res[$feedProduct->product_id][$key] = $value;
            }

            $arEav = $mysql->readEav('product', $feedProduct->product_id);

            foreach ($arEav as $key => $value) {
                if ($value['value']) $res[$feedProduct->product_id]["(eav) ".$value['attribute']] = $value['value'];
            }

        }

        return $res;

    }
}
