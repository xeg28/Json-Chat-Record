<?php

namespace App\Controllers;
use App\Models\JsonModel;
use App\Models\MessageModel;
use CodeIgniter\HTTP\Message;

class Search extends BaseController
{
    public function index()
    {
        $jsonModel = new JsonModel();
        $messageModel = new MessageModel();
        $query = $this->request->getVar('query');
        $data['title'] = 'Search Results';

        $jsonResult = $jsonModel->searchJsons($query);
        $data['jsonfiles'] = $jsonResult['jsons'];
        $data['locations'] = $jsonResult['locations'];
        $data['visitors'] = $jsonResult['visitors'];

        $messageResult = $messageModel->searchMessages($query);
        $data['messages'] = $messageResult['messages'];
        $data['fileData'] = $messageResult['fileData'];
        $data['senders'] = $messageResult['senders'];

        $data['query'] = trim($query);
        echo view('templates/header', $data);
        echo view('content/search', $data);
        echo view('templates/footer');
    }
}
