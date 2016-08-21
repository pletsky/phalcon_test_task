<?php

use Phalcon\Mvc\Controller;

class BaseController extends Controller
{
    public $page;

    protected function initialize()
    {
        $ar = $this->parse_uri($this->request->get('_url'));
        if (array_key_exists('params', $ar) &&
            array_key_exists(0, $ar['params']) &&
            $ar['params'][0] == 'page' &&
            array_key_exists(1, $ar['params'])
        ) {
            $this->page = (int)$ar['params'][1];
        } else {
            $this->page = 1;
        }

        $this->tag->prependTitle('SOS Credit | ');
//        $this->view->setTemplateAfter('main');
    }

    protected function parse_uri($uri)
    {
        $uriParts = explode('/', trim($uri, '/'));
        $params = array_slice($uriParts, 2);

        return array (
            'controller' => $uriParts[0],
            'action' => (array_key_exists(1, $uriParts) ? $uriParts[1] : 'index'),
            'params' => $params
        );
    }

    protected function forward($uri)
    {
        return $this->dispatcher->forward($this->parse_uri($uri));
    }
}
