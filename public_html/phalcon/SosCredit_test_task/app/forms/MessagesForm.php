<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\StringLength;


class MessagesForm extends Form
{

    /**
     * Initialize the messages form
     */
    public function initialize($entity = null, $options = array())
    {
        $this->add(new Hidden("id"));

        $name = new Text("name");
        $name->setLabel("Name");
        $name->setFilters(array('striptags', 'string', 'trim'));
        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Name is required',
                'cancelOnFail' => true
            )),
            new StringLength(array(
                'min' => 3,
                'max' => 100,
                'messageMinimum' => '"Name" must be not less than 3 symbols',
                'messageMaximum' => '"Name" must be not longer than 100 symbols',
                'cancelOnFail' => true
            )),
        ));
        $this->add($name);


        $phone = new Text("phone");
        $phone->setLabel("Phone");
        $phone->setFilters(array('striptags', 'trim'));
        $phone->addValidators(array(
            new PresenceOf(array(
                'message' => 'Phone is required',
                'cancelOnFail' => true
            )),
            new Regex(array(
                'message' => '"Phone" format is incorrect',
                'pattern' => '/^(\+[0-9]{1,2})?\(?([0-9]{3})\)?([ .-]?)([0-9]{3})((([ .-]?)([0-9]{2})){2})$/',
                'cancelOnFail' => true
            )),
            new StringLength(array(
                'max' => 20,
                'messageMaximum' => '"Phone" must be not longer than 20 symbols',
                'cancelOnFail' => true
            )),

        ));
        $this->add($phone);


        $email = new Text('email');
        $email->setLabel('E-Mail');
        $email->setFilters(array('email', 'trim'));
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => '"E-mail" is required',
                'cancelOnFail' => true
            )),
            new Email(array(
                'message' => '"E-mail" is not valid',
                'cancelOnFail' => true
            )),
            new StringLength(array(
                'max' => 100,
                'messageMaximum' => '"E-Mail" must be not longer than 100 symbols',
                'cancelOnFail' => true
            )),
        ));
        $this->add($email);



        $text = new Text('text');
        $text->setLabel('Message');
        $text->setFilters(array('striptags', 'string', 'trim'));
        $text->addValidators(array(
            new PresenceOf(array(
                'message' => '"Message" is required',
                'cancelOnFail' => true
            )),
             new StringLength(array(
                'max' => 200,
                'messageMaximum' => '"Message" length should be not more 200 symbols',
            )),
        ));
        $this->add($text);
    }
}