<?php

class Application_Form_Search extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setName('search_form');
        // $this->setEnctype('multipart/form-data');

        $this->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array('tag' => 'dl', 'class' => 'search_form'))
             ->addDecorator('Form');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('')
             ->setRequired(true)
             ->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(array($id, $name, $submit));

    }
}
