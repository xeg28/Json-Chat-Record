<?php

namespace App\Controllers;
use App\Models\MessageModel;
use DateTime;

class Chat extends BaseController
{
    public function index()
    {
        $messageModel = new MessageModel();
        $inputValue = $this->request->getVar('datetime');
        $id = $this->request->getVar('jsonId');
        if(isset($inputValue)) {
            $datetime = new DateTime($inputValue);
            
            if($datetime == false) {
                return redirect(previous_url());
            }
            $date = $datetime->format('Y-m-d H:i:s');
            
            $filterType = $this->request->getVar('filterType');
            $messages = $messageModel->dateFilter($date, $id, $filterType); 
            $data['title'] = 'Chat Details';
            $data['json'] = $id;
            $data['messages'] = $messages;
            $data['fileData'] = $messageModel->getFileData($messages);
            $data['senders'] = $messageModel->getSenders($messages);     
            
            echo view('templates/header', $data);
            echo view('content/chat', $data);
            echo view('templates/footer');
            return;
        }

        
        $data['title'] = 'Chat Details';
        $data['json'] = $id;
        $data['messages'] = $messageModel->getMessagesByJson($id);
        $data['fileData'] = $messageModel->getAllFileDataById($id);
        $data['senders'] = $messageModel->getAllSendersById($id);
        echo view('templates/header', $data);
        echo view('content/chat', $data);
        echo view('templates/footer');
    }
}
