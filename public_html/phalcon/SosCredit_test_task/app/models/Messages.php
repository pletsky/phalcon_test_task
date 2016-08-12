<?php

use Phalcon\Mvc\Model;

class Messages extends Model
{
    public $id;

    public $name;

    public $phone;

    public $email;

    public $text;

    public $add_date;

    public function beforeCreate()
    {
//        $this->add_date = new RawValue('now()');
    }

}

