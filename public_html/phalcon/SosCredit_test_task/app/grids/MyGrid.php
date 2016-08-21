<?php

use Phalcon\Mvc\Model\Criteria;

class MyGrid
{
    protected $page = 1;
    protected $fields;
    protected $search_params = array();
    protected $order;
    protected $query;
    protected $offset = 0;
    protected $limit = 5;
    protected $total_count;
    protected $model_name;

    public function __construct()
    {
        $this->di = Phalcon\DI::getDefault();
        $this->session = $this->di->get("session");
        $this->request = new \Phalcon\Http\Request();
    }

    public function getPaginate($page)
    {
        $items = $this->getItems($page);

        if ($this->limit >= 1) {
            $total_pages = ceil($this->total_count/$this->limit);
        } else {
            $total_pages = 1;
        }

        $res = array (
            'items' => $items,
            'items_count' => count($items), // on current page
            'total_count' => $this->total_count,
            'total_pages' => $total_pages,
            'before' => max(1, $page-1),
            'next' => min($page+1, $total_pages),
            'last' => $total_pages,
            'current' => $page,
        );

        return (object)$res;
    }

    public function setSearchParams()
    {
        if ($this->request->isPost() &&
            $this->request->getPost('search')
        ) {
            foreach ($this->getSearchFields() as $k) {
                $this->session->set($k, $this->request->getPost($k));
            }
        }
    }

    public function getSearchParams()
    {
        $ar = array();
        foreach ($this->getSearchFields() as $k) {
            $ar[$k] = $this->session->get($k);
        }
        return (array)Criteria::fromInput($this->di, $this->model_name, $ar)->getParams();
    }

    public function getItems($page)
    {
        $this->page = $page;

        $this->setSearchParams();

        // Формируем условия запроса
        $this->search_params = $this->getSearchParams();

//vdump_e($this->model_name, '$this->model_name');
        $this->total_count = call_user_func(array($this->model_name, 'count'), $this->search_params);

        if ($this->page > 1) {
            $this->offset = $this->limit*($this->page-1);
        }

        $params = array_merge(
            $this->search_params,
            array(
                'order' => $this->order,
                'limit' => $this->limit,
                'offset' => $this->offset,
                'columns' => implode(',', $this->getFields()),
            )
        );
//vdump_e($params, '$params');
        $items = call_user_func(array($this->model_name, 'find'), $params)->toArray();

        return $items;
    }

    public function getFields() {
        return array_keys($this->fields);
    }

    public function getFieldsTitles() {
        $res = array();
        foreach ($this->fields as $k=>$v) {
            $res[] = (array_key_exists('title', $v) ? $v['title'] : $k);
        }
        return $res;
    }

    public function getSearchFields() {
        $res = array();
        foreach ($this->fields as $k=>$v) {
            $res[] = (array_key_exists('search', $v) && $v['search'] ? $k : '');
        }
        return $res;
    }

    public function getInsertFields() {
        $res = array();
        foreach ($this->fields as $k=>$v) {
            $res[] = (array_key_exists('insert', $v) && $v['insert'] ? $k : '');
        }
        return $res;
    }

    public function getRow($id) {
        $item = call_user_func(array($this->model_name, 'findFirstById'), $id)->toArray();
        return (object)array(
            'items' => array(
                0 => $item
            )
        );
    }

}
