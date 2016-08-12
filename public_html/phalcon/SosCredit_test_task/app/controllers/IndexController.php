<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
//var_dump(__LINE__);
//        $this->session->conditions = null;
//var_dump(__LINE__, '1');
//        $this->view->form = new MessagesForm;
//var_dump($this->view->form);

        $numberPage = 1;
        if ($this->request->isPost())
        {
            // Формируем условия запроса
            $query = Criteria::fromInput($this->di, "Messages", $this->request->getPost());
//            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = array();
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }
//var_dump(__LINE__);

//var_dump($parameters);
        $messages = Messages::find($parameters);
//var_dump(count($messages));
        if (count($messages) == 0)
        {
            $this->flash->notice("No messages");
            return $this->forward("index/new");
        }

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

    public function edit1Action($id)
    {
        if (!$this->request->isPost()) {

            $message = Messages::findFirstById($id);
            if (!$message) {
                $this->flash->error("Message was not found");
                return $this->forward("index");
            }

            $this->view->form = new MessagesForm($message, array('edit1' => true));
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
        return $this->forward("index");
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

}

