<?php

use Phalcon\Mvc\Model\Criteria;

class MessagesGrid extends MyGrid
{
    protected $model_name = 'Messages';

    protected $fields = array(
        'id' => array(),
        'name' => array('title'=>'Name', 'search'=>true, 'insert'=>true),
        'phone' => array('title'=>'Phone', 'search'=>true, 'insert'=>true),
        'email' => array('title'=>'E-Mail', 'search'=>true, 'insert'=>true),
        'text' => array('title'=>'Message', 'search_'=>true, 'insert'=>true),
        'add_date' => array('title'=>'Date'),
    );
    protected $order = 'add_date DESC, id DESC';
    protected $limit = 10;
}
