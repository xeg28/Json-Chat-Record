<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\SenderModel;
use App\Models\DataModel;
use DateTime;
class MessageModel extends Model {
    protected $table = 'messages';
    protected $allowedFields = ['id', 'senderId', 'type', 'time', 'msg', 'jsonfileId', 'dataId'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $db;

    public function __construct() {
        $this->db = db_connect();
    }

    public function dateFilter($datetime, $jsonfileId, $filterType) {
        if($filterType == 'Before') {
            $sql = "SELECT * FROM ". $this->table .
            " WHERE time <= ? and jsonfileId = ? 
            ORDER BY time";
        } else {
            $sql = "SELECT * FROM ". $this->table .
            " WHERE time >= ? and jsonfileId = ? 
            ORDER BY time";
        }

        return $this->db->query($sql, array($datetime, $jsonfileId))->getResult();
    }

    public function getMessageById($id) {
        return $this->where('id', $id)->first();
    }

    public function getMessagesByJson($id) {
        parent::__construct();
        $query = $this->where('jsonfileId', $id);
        return $query->get()->getResult();
    }

    public function getAllFileDataById($id) {
        $dataModel = new DataModel();
        $query = $this->where('jsonfileId', $id);
        $messages = $query->get()->getResult();

        $fileData = [];
        foreach($messages as $row) {
            $data = $dataModel->getDataById($row->dataId);
            $fileData[$row->id] = $data;
        }

        return $fileData;
    }

    public function getFileData($messages) {
        $dataModel = new DataModel();
        $fileData = [];
        foreach($messages as $row) {
            $data = $dataModel->getDataById($row->dataId);
            $fileData[$row->id] = $data;
        }

        return $fileData;
    }

    public function getAllSendersById($id) {
        $senderModel = new SenderModel();
        $query = $this->where('jsonfileId', $id);
        $messages = $query->get()->getResult();

        $senders = [];
        foreach($messages as $row) {
            $sender = $senderModel->getSenderById($row->senderId);
            $senders[$row->id] = $sender;
        }

        return $senders;
    }

    public function getSenders($messages) {
        $senderModel = new SenderModel();

        $senders = [];
        foreach($messages as $row) {
            $sender = $senderModel->getSenderById($row->senderId);
            $senders[$row->id] = $sender;
        }

        return $senders;
    }

    public function deleteMessagesByJsonId($id) {
        $sql = "DELETE FROM ". $this->table ." where jsonfileId = ?";
        $this->db->query($sql, array($id));
    }

    public function saveMessages($data, $jsonfileId) {
        $this->deleteMessagesByJsonId($jsonfileId);
        $senderModel = new SenderModel();
        $dataModel = new DataModel();
        foreach($data as $message) {
            $senderId = null;
            $sender = $senderModel->getSender($message['sender']);
            $msg =  $message['msg'] ?? null;
            $dataId = null;
            if(array_key_exists('data', $message)) {
                $dataId = $dataModel->saveData($message['data']);
            }
            if(!isset($sender)) {
               $senderId = $senderModel->saveSender($message['sender']);
            }
            else{
                $senderId = $sender['id'];
            }
            $datetime = new DateTime($message['time']);
            $time = $datetime->format('Y-m-d H:i:s');


            
            $data = [
                'senderId' => $senderId,
                'type' => $message['type'],
                'time' => $time,
                'msg' => $msg,
                'jsonfileId' => $jsonfileId,
                'dataId' => $dataId
            ];

            $this->save($data);
        }
    }

    public function getLastTenUpdated() {
        $builder = $this->db->table($this->table)->orderBy('uploaded_at','DESC')->limit(10);
        $query = $builder->get();
        return $query->getResult();
    }

    public function searchMessages($query) {
        $query = trim($this->db->escapeString($query));

        $words = null;
        if(str_contains($query, " ")) {
            $words = explode(" ", $query);
        }

        $sqlQuery = "SELECT * FROM " . $this->table .
                     " WHERE msg LIKE '%" . $query . "%'" ;

        if(!empty($words)) {
            foreach($words as $word) {
                if(trim($word) == '') {
                    continue;
                }
                $sqlQuery = $sqlQuery . " OR msg LIKE '%". $word . "%'"; 
            }
        }

        $sqlQuery = $sqlQuery . ';';

        $result = $this->query($sqlQuery);
        $messages = $result->getResult();
        $queryResult = [
            'messages' => $messages,
            'fileData' => $this->getFileData($messages),
            'senders' => $this->getSenders($messages)
        ];

        return $queryResult;
        
    }

    public function beforeInsert(array $data) {
        return $data;
    }

    public function beforeUpdate(array $data) {
        return $data;
    }

 }
?>