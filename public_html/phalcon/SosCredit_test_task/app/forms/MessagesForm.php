<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Numericality;

class MessagesForm extends Form
{

    /**
     * Initialize the messages form
     */
    public function initialize($entity = null, $options = array())
    {
        if (!isset($options['edit'])) {
            $element = new Text("id");
            $this->add($element->setLabel("Id"));
        } else {
            $this->add(new Hidden("id"));
        }

        $name = new Text("name");
        $name->setLabel("Name");
        $name->setFilters(array('striptags', 'string'));
        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Name is required'
            ))
        ));
        $this->add($name);


        $phone = new Text("phone");
        $phone->setLabel("Phone");
        $name->setFilters(array('striptags'));
        $phone->addValidators(array(
            new PresenceOf(array(
                'message' => 'Phone is required',
                'cancelOnFail' => true
            )),
            new Regex(array(
                'message' => 'Phone format is incorrect',
                'pattern' => '/(\+[0-9]{1,2})?\(?([0-9]{3})\)?([ .-]?)([0-9]{3})((([ .-]?)([0-9]{2})){2})/'
            )),

        ));
        $this->add($phone);


        $email = new Text('email');
        $email->setLabel('E-Mail');
        $email->setFilters('email');
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'E-mail is required'
            )),
            new Email(array(
                'message' => 'E-mail is not valid'
            ))
        ));
        $this->add($email);



        $text = new Text('text');
        $text->setLabel('Message');
        $name->setFilters(array('striptags', 'string'));
        $text->addValidators(array(
            new PresenceOf(array(
                'message' => 'Message is required'
            ))
        ));
        $this->add($text);
    }
}