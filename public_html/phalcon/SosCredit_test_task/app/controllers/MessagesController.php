<?php


class MessagesController extends BaseController
{

    public function initialize()
    {
        $this->tag->setTitle('Messages Control | ');
        parent::initialize();
    }

    public function indexAction()
    {
        $this->grid = new MessagesGrid();
        $this->view->page = $this->grid->getPaginate($this->page);
        $this->view->fields = $this->grid->getFields();
        $this->view->fields_titles = $this->grid->getFieldsTitles();
        $this->view->search_fields = $this->grid->getSearchFields();
        $this->view->insert_fields = $this->grid->getInsertFields();

        if (count($this->view->page->items) == 0) {
            $this->flash->notice("No messages");
        }
    }

    /**
     * Shows the form to create a new message
     */
    public function newAction() // adding form (empty)
    {
        $this->view->form = new MessagesForm(null, array('edit' => true));
    }

    /**
     * Edits a message based on its id
     */
    public function editAction($id) // edit form (with data)
    {
        if (!$this->request->isPost()) {

            $message = Messages::findFirstById($id);
            if (!$message) {
                $this->flash->error("Message was not found");
                return $this->forward("messages/index");
            }

            $this->view->form = new MessagesForm($message);
        }
    }


    /**
     * Creates a new message
     */
    public function createAction() // insert message
    {
        if (!$this->request->isPost()) {
            return $this->forward("messages/index");
        }

        $form = new MessagesForm;
        $message = new Messages();

        $data = $this->request->getPost();
        if (!$form->isValid($data, $message)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('messages/new');
        }

        if ($message->save() == false) {
            foreach ($message->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('messages/new');
        }

        $form->clear();

        $this->flash->success("Message was created successfully");

        $this->forward("messages/index");
    }




    /**
     * Saves current message in screen
     *
     */
    public function saveAction() // update message
    {
        if (!$this->request->isPost()) {
            return $this->forward("messages/index");
        }

        $id = $this->request->getPost("id", "int");

        $message = Messages::findFirstById($id);
        if (!$message) {
            $this->flash->error("Message does not exist");
            return $this->forward("messages/index");
        }

        $form = new MessagesForm;
        $this->view->form = $form;

        $data = $this->request->getPost();

        if (!$form->isValid($data, $message)) {
            foreach ($form->getMessages() as $msg) {
                $this->flash->error($msg);
            }
            return $this->forward('messages/edit/'.$id);
        }

        if ($message->save() == false) {
            foreach ($message->getMessages() as $msg) {
                $this->flash->error($msg);
            }
            return $this->forward('messages/edit/'.$id);
        }

        $form->clear();

        $this->flash->success("Message was updated successfully");
//var_dump($id);exit;
        return $this->forward("messages/index");
    }

    /**
     * Deletes a message
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $message = Messages::findFirstById($id);
        if (!$message) {
            $this->flash->error("Message was not found");
            return $this->forward("messages/index");
        }

        if (!$message->delete()) {
            foreach ($message->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward("messages/index");
        }

        $this->flash->success("Message was deleted");
        return $this->forward("messages/index");
    }

}

