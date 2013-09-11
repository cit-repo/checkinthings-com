<?php

class Application_Form_Customer extends Zend_Form
{

    public function init()
    {
        $this->setName('customer');

        $customerId = new Zend_Form_Element_Hidden('customer_id');

        $customerId->addFilter('Int');

        $firstname = new Zend_Form_Element_Text('firstname');
        $firstname->setLabel('Firstname')
                   ->setAttrib('size', '58')
                   ->setRequired(true)
                   ->addFilter('StripTags')
                   ->addFilter('StringTrim')
                   ->addValidator('NotEmpty');

        $lastname = new Zend_Form_Element_Text('lastname');
        $lastname->setLabel('Lastname')
                  ->setAttrib('size', '58')
                  ->setRequired(true)
                  ->addFilter('StripTags')
                  ->addFilter('StringTrim')
                  ->addValidator('NotEmpty');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
              ->setAttrib('size', '58')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty');

        $password = new Zend_Form_Element_Text('password');
        $password->setLabel('Password')
                 ->setAttrib('size', '58')
                 ->setRequired(true)
                 ->addFilter('StripTags')
                 ->addFilter('StringTrim')
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

        $this->addElements(array($customerId, $firstname, $lastname, $email, $password, $lastUpdated, $submit));
    }
}

