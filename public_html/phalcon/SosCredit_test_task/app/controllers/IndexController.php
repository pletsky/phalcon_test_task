<?php

class IndexController extends BaseController
{

    public function indexAction()
    {
        return $this->forward("messages/index");
    }

    /**
     * Shows the form to create a new message
     */
    public function newAction() // adding form (empty)
    {
        return $this->forward("messages/new");
    }

    /**
     * Edits a message based on its id
     */
    public function editAction($id) // edit form (with data)
    {
        return $this->forward("messages/edit/".$id);
    }


    /**
     * Creates a new message
     */
    public function createAction() // insert message
    {
        return $this->forward("messages/create");
    }


    /**
     * Saves current message in screen
     *
     */
    public function saveAction() // update message
    {
        return $this->forward("messages/save");
    }

    /**
     * Deletes a message
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        return $this->forward("messages/delete/".$id);
    }

}

