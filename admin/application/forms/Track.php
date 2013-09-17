<?php

class Application_Form_Track extends Zend_Form
{

    public function init()
    {
        $this->setName('track');

        $trackId = new Zend_Form_Element_Hidden('track_id');

        $trackId->addFilter('Int');

        $event = new Zend_Form_Element_Text('event');
        $event->setLabel('Event')
               ->setAttrib('size', '58')
               ->setRequired(true)
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('NotEmpty');

        $link = new Zend_Form_Element_Text('link');
        $link->setLabel('Link')
             ->setAttrib('size', '58')
             ->setRequired(true)
             ->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty');

        $httpReferer = new Zend_Form_Element_Text('http_referer');
        $httpReferer->setLabel('HttpReferer')
                    ->setAttrib('size', '58')
                    ->setRequired(true)
                    ->addFilter('StripTags')
                    ->addFilter('StringTrim')
                    ->addValidator('NotEmpty');

        $userAgent = new Zend_Form_Element_Text('user_agent');
        $userAgent->setLabel('UserAgent')
                  ->setAttrib('size', '58')
                  ->setRequired(true)
                  ->addFilter('StripTags')
                  ->addFilter('StringTrim')
                  ->addValidator('NotEmpty');

        $remoteAddr = new Zend_Form_Element_Text('remote_addr');
        $remoteAddr->setLabel('RemoteAddr')
                    ->setAttrib('size', '58')
                    ->setRequired(true)
                    ->addFilter('StripTags')
                    ->addFilter('StringTrim')
                    ->addValidator('NotEmpty');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(array($trackId, $event, $link, $httpReferer, $userAgent, $remoteAddr, $submit));
    }
}

