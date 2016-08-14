<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class IndexController extends ControllerBase
{

    public function jsonAction($id)
    {
    }

    public function rowAction($id)
    {
        $parameters = array(    "conditions"=>"[id] = :id:",    "bind"=>array(        "id"=>$id,    ));
        $messages = Messages::find($parameters);

        if (count($messages))
        {
            $paginator = new Paginator(
                array(
                    "data"  => $messages,  // Данные для пагинации
                    "limit" => 1,          // Количество записей на страницу
                    "page"  => 1 // Активная страница
                 )
            );

            // Получаем активную страницу пагинатора
            $this->view->page = $paginator->getPaginate();
        }
    }

    public function indexAction()
    {
/*
        $this->session->conditions = null;
        $this->view->form = new ProductsForm;
*/
//vdump_e($this);
        $this->session->start();

        $numberPage = 1;
        if ($this->request->isPost())
        {
            if ($this->request->getPost('search')) {
                foreach (array('name', 'phone', 'email') as $k) {
                    $this->session->set($k, $this->request->getPost($k));
                }
            }
//vdump_e($this->session->get('name'), '$this->session');
            // Формируем условия запроса
            $query = Criteria::fromInput($this->di, "Messages", $this->request->getPost());
            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = array();
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }



        $messages = Messages::find($parameters);



        if (count($messages) == 0)
        {
            $this->flash->notice("No messages");
        } else {

            $paginator = new Paginator(
                array(
                    "data"  => $messages,  // Данные для пагинации
                    "limit" => 10,          // Количество записей на страницу
                    "page"  => $numberPage // Активная страница
                )
            );

            // Получаем активную страницу пагинатора
            $this->view->page = $paginator->getPaginate();
        }
    }

    /**
     * Shows the form to create a new message
     */
    public function newAction()
    {
        $this->view->form = new MessagesForm(null, array('edit' => true));
    }

    /**
     * Edits a message based on its id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $message = Messages::findFirstById($id);
            if (!$message) {
                $this->flash->error("Message was not found");
                return $this->forward("index");
            }

            $this->view->form = new MessagesForm($message, array('edit' => true));
        }
    }


    /**
     * Creates a new message
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->forward("index");
        }

        $form = new MessagesForm;
        $message = new Messages();

        $data = $this->request->getPost();
        if (!$form->isValid($data, $message)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('index/new');
        }

        if ($message->save() == false) {
            foreach ($message->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('index/new');
        }

        $form->clear();

        $this->flash->success("Message was created successfully");

        $this->forward("index");
    }



    public function ajax_createAction()
    {
        $ar = array();

        if (!$this->request->isPost()) {

            $ar = array('res'=>false, 'msg'=>'Request type is incorrect');

        } else {

            $form = new MessagesForm;
            $message = new Messages();

            $data = $this->request->getPost();

            if (!$form->isValid($data, $message)) {

                foreach ($form->getMessages() as $msg) {
                    $ar[] = $msg;
                }
                $ar = array('res'=>false, 'msg'=>implode("\n", $ar));

            } elseif ($message->save() == false) {

                foreach ($message->getMessages() as $msg) {
                    $ar[] = $msg;
                }
                $ar = array('res'=>false, 'msg'=>implode("\n", $ar));

            } else {
                 return $this->forward("index/row/".$message->id);
            }
        }

        echo json_encode($ar);
    }


    /**
     * Saves current message in screen
     *
     * @param string $id
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->forward("index");
        }

        $id = $this->request->getPost("id", "int");

        $message = Messages::findFirstById($id);
        if (!$message) {
            $this->flash->error("Message does not exist");
            return $this->forward("index");
        }

        $form = new MessagesForm;
        $this->view->form = $form;

        $data = $this->request->getPost();

        if (!$form->isValid($data, $message)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('index/edit/' . $id);
        }

        if ($message->save() == false) {
            foreach ($message->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('index/edit/' . $id);
        }

        $form->clear();

        $this->flash->success("Message was updated successfully");
//var_dump($id);exit;
        return $this->forward("index");
    }

    /**
     * Deletes a message
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $messages = Messages::findFirstById($id);
        if (!$messages) {
            $this->flash->error("Message was not found");
            return $this->forward("index");
        }

        if (!$messages->delete()) {
            foreach ($messages->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward("index");
        }

        $this->flash->success("Message was deleted");
        return $this->forward("index");
    }

    /**
     * Deletes a message
     *
     * @param string $id
     */
    public function ajax_deleteAction($id)
    {
        $messages = Messages::findFirstById($id);

        if (!$messages) {
            $ar = array('res'=>false, 'txt'=>"Message was not found");
        } elseif (!$messages->delete()) {
            foreach ($messages->getMessages() as $message) {
                $ar[] = $message;
            }
            $ar = array('res'=>false, 'txt'=>implode(';', $ar));
        } else {

            $ar = array('res'=>true, 'txt'=>"Message was deleted");
        }

        echo json_encode($ar);
    }

}

