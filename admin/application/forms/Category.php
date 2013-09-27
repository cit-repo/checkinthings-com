<?php

class Application_Form_Category extends Zend_Form
{

    public function init()
    {
        $this->setName('category');

        $categoryId = new Zend_Form_Element_Hidden('category_id');

        $categoryId->addFilter('Int');

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name')
            ->setAttrib('size', '58')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');

        $url = new Zend_Form_Element_Text('url');
        $url->setLabel('Url')
            ->setAttrib('size', '58')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');

        $description = new Zend_Form_Element_Text('description');
        $description->setLabel('Description')
            ->setAttrib('size', '58')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');

        $parent = new Zend_Form_Element_Select('parent');
        $parent->setLabel('Parent')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setMultiOptions( $this->parentSelector("category_id", "name") )
            ->addValidator('NotEmpty');

        $active = new Zend_Form_Element_Radio('active');
        $active->setLabel('Active')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setMultiOptions( array('Y' => 'Yes', 'N' => 'No') )
            ->addValidator('NotEmpty');

        $lastUpdated = new Zend_Form_Element_Text('last_updated');
        $lastUpdated->setLabel('LastUpdated')
            ->setAttrib('size', '58')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(array($categoryId, $name, $url, $description, $parent, $active, $lastUpdated, $submit));
    }

    private function getForeignKeySelector() {
        $category = new Application_Model_DbTable_Category();
    }

    private function parentSelector() {
        $category = new Application_Model_DbTable_Category();

        $arCategories = $category->fetchAll();

        foreach ($arCategories->toArray() as $cat) {
            $arRes[] = array($cat['category_id'] => $cat['name']);
        }

        return $arRes;
    }
}
