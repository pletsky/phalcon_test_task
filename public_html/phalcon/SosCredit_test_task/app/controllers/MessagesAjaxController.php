<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class MessagesAjaxController extends BaseController
{
    public function rowAction($id)
    {
        $this->grid = new MessagesGrid();
        $this->view->page = $this->grid->getRow($id);
    }

    public function createAction()
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
                 return $this->forward("messagesajax/row/".$message->id);
            }
        }

        echo json_encode($ar);
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

