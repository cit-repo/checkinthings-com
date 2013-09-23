<?php

class Application_Form_Product extends Zend_Form
{

    public function init()
    {
        $this->setName('product');

        $productId = new Zend_Form_Element_Hidden('product_id');

        $productId->addFilter('Int');

        $feed = new Zend_Form_Element_Text('feed');
        $feed->setLabel('Feed')
             ->setAttrib('size', '64')
             ->setRequired(true)
             ->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty');

        $channel = new Zend_Form_Element_Text('channel');
        $channel->setLabel('Channel')
            ->setAttrib('size', '64')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');

        $mainCategory = new Zend_Form_Element_Text('main_category');
        $mainCategory->setLabel('MainCategory')
            ->setAttrib('size', '64')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name')
             ->setAttrib('size', '64')
             ->setRequired(true)
             ->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty');

        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Description')
                    ->setRequired(true)
                    ->addFilter('StripTags')
                    ->addFilter('StringTrim')
                    ->addValidator('NotEmpty');

        $productUrl = new Zend_Form_Element_Text('product_url');
        $productUrl->setLabel('ProductUrl')
                   ->setAttrib('size', '64')
                   ->setRequired(false)
                   ->addFilter('StripTags')
                   ->addFilter('StringTrim')
                   ->addValidator('NotEmpty');

        $imageUrl = new Zend_Form_Element_Text('image_url');
        $imageUrl->setLabel('ImageUrl')
                 ->setAttrib('size', '64')
                 ->setRequired(false)
                 ->addFilter('StripTags')
                 ->addFilter('StringTrim')
                 ->addValidator('NotEmpty');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(array($productId, $feed, $channel, $mainCategory, $name, $description, $productUrl, $imageUrl, $submit));
    }
}

