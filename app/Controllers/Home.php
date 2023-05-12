<?php

namespace App\Controllers;
use App\Models\JsonModel;
use DateTime;

class Home extends BaseController
{
    public function index()
    {
        $jsonModel = new JsonModel();
        
        $inputValue = $this->request->getVar('datetime');
        if(isset($inputValue)) {
            $datetime = new DateTime($inputValue);

            if($datetime == false) {
                return redirect()->to('/'); 
            }
            $date = $datetime->format('Y-m-d H:i:s');
            $filterType = $this->request->getVar('filterType');

            $jsonfiles = $jsonModel->dateFilter($date, $filterType); 
            $data['title'] = 'Home';
            $data['jsonfiles'] = $jsonfiles;
            $data['locations'] = $jsonModel->getLocations($jsonfiles);
            $data['visitors'] = $jsonModel->getVisitors($jsonfiles);     
            
            echo view('templates/header', $data);
            echo view('content/home', $data);
            echo view('templates/footer');
            return;
        }

        $data['title'] = 'Home';
        $data['jsonfiles'] = $jsonModel->getAllJson();
        $data['locations'] = $jsonModel->getAllLocations();
        $data['visitors'] = $jsonModel->getAllVisitors();
        echo view('templates/header', $data);
        echo view('content/home', $data);
        echo view('templates/footer');

    }
}
