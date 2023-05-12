<?php

namespace App\Controllers;

use CodeIgniter\Files\File;
use App\Models\JsonModel;
use App\Models\MessageModel;
use App\Models\VisitorModel;
use ZipArchive;

class Upload extends BaseController
{

    public function index()
    {
        // Set upload configuration
        helper(['form', 'url', 'upload', 'date']);

        $file = $this->request->getFile('file');
        $targetPath = ROOTPATH . 'public/uploads/'; 

        if($file->isValid() && !$file->hasMoved()) {
            $targetFile = $targetPath.$file->getName();
            $info = pathinfo($file);

            if($file->getExtension() == 'json'){
                $jsonfileModel = new JsonModel();
                $messageModel = new MessageModel();
                $json = file_get_contents($file);
                $jsonData = json_decode($json, true);
                $data['json'] = $jsonData;
                $jsonfileId = $jsonfileModel->saveJson($jsonData);

                $messageModel->saveMessages($jsonData['messages'], $jsonfileId);
            }
            else {
                $file->move($targetPath, null);
                $zip = new ZipArchive();
                if($zip->open($targetFile)){
                    $zip->extractTo($targetPath.$file->getFilename());
                    $this->uploadZip($targetPath.$file->getFilename());
                    $this->deleteFolder($targetPath.$file->getFilename());
                    $zip->close();
                    if (file_exists($targetFile)) {
                        unlink($targetFile); // delete the file
                    }
                } 
            }   
        } 
    }


    private function uploadZip($folder) {
        $files = scandir($folder);

        foreach($files as $file) {
            if ($file === '.' || $file === '..' || $file[0] === '.') {
                continue;
            }

            $path = $folder . DIRECTORY_SEPARATOR . $file;

            if(is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'json') {
                $jsonfileModel = new JsonModel();
                $messageModel = new MessageModel();
                $json = file_get_contents($path);
                $jsonData = json_decode($json, true);
                $data['json'] = $jsonData;
                $jsonfileId = $jsonfileModel->saveJson($jsonData);

                $messageModel->saveMessages($jsonData['messages'], $jsonfileId);
            }
            else {
                $this->uploadZip($path);
            }
        }
    }

    public function deleteFolder($folder) {

        if (is_dir($folder)) {
            $files = scandir($folder);
        
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $path = $folder . '/' . $file;
                    if (is_dir($path)) {
                        $this->deleteFolder($path);
                    } else {
                        unlink($path);
                    }
                }
            }

            rmdir($folder);
        }
        
    }
}
