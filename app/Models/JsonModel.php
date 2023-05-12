<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\VisitorModel;
use App\Models\LocationModel;
use DateTime;

class JsonModel extends Model {
    protected $table = 'jsonfiles';
    protected $allowedFields = ['id', 'type', 'pageId', 'messageCount', 'chatDuration', 
                                'rating', 'createdOn', 'domain', 'visitorId', 'locationId'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $db;
    public function __construct() {
        $this->db = db_connect();
        
    }

    public function dateFilter($datetime, $filterType) {
        if($filterType == 'Before') {
            $sql = "SELECT * FROM ". $this->table .
            " WHERE createdOn <= ? 
            ORDER BY createdOn DESC";
        } 
        else {
            $sql = "SELECT * FROM ". $this->table .
            " WHERE createdOn >= ? 
            ORDER BY createdOn DESC";
        }
       
        return $this->db->query($sql, array($datetime))->getResult();
    }

    public function getJsonById($id) {
        parent::__construct();
        return $this->where('id', $id)->first();
    }

    public function getAllLocations() {
        $locationModel = new LocationModel();
        $builder = $this->db->table($this->table);
        $query = $builder->get();
        $jsons = $query->getResult();

        $locations = [];
        foreach($jsons as $row) {
            $location = $locationModel->getLocationById($row->locationId);
            $locations[$row->id] = $location;
        }
        return $locations;
    }

    public function getLocations($jsons) {
        $locationModel = new LocationModel();
        $locations = [];
        foreach($jsons as $row) {
            $location = $locationModel->getLocationById($row->locationId);
            $locations[$row->id] = $location;
        }

        return $locations;
    }

    public function getAllVisitors() {
        $visitorModel = new VisitorModel();
        $builder = $this->db->table($this->table);
        $query = $builder->get();
        $jsons = $query->getResult();

        $visitors = [];
        foreach($jsons as $row) {
            $visitor = $visitorModel->getVisitorById($row->visitorId);
            $visitors[$row->id] = $visitor;
        }
        return $visitors;
    }

    public function getVisitors($jsons) {
        $visitorModel = new VisitorModel();
        $visitors = [];
        foreach($jsons as $row) {
            $visitor = $visitorModel->getVisitorById($row->visitorId);
            $visitors[$row->id] = $visitor;
        }

        return $visitors;
    }

    public function getAllJson() {
        $builder = $this->db->table($this->table);
        $query = $builder->get();
        return $query->getResult();
    }

    public function updateJson($data) {
        $sql = "UPDATE ". $this->table ." SET type = ?, pageId = ?,
        messageCount = ?, chatDuration = ?, rating = ?, createdOn = ?,
        domain = ?, visitorId = ?, locationId = ?  WHERE id = ?";

        $this->db->query($sql, $data);
    }

    public function saveJson($data) {
        $visitorModel = new VisitorModel();
        $locationModel = new LocationModel();
        $visitorId = $visitorModel->saveVisitor($data['visitor']);
        $locationId = $locationModel->saveLocation($data['location']);
        $datetime = new DateTime($data['createdOn']);
        $createdOn = $datetime->format('Y-m-d H:i:s');

        $newData = [
            $data['type'],
            $data['pageId'],
            $data['messageCount'],
            $data['chatDuration'],
            $data['rating'],
            $createdOn,
            $data['domain'],
            $visitorId,
            $locationId, 
            $data['id']
        ];

        if($this->getJsonById($data['id'])) {
            $this->updateJson($newData);
            return $data['id'];
        }
        $sql = "INSERT INTO ". $this->table ." (type, pageId, messageCount, chatDuration,
         rating, createdOn, domain, visitorId, locationId, id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $this->db->query($sql, $newData);
        return $data['id'];
    }

    public function searchJsons($query) {
        $query = trim($this->db->escapeString($query));

        $words = null;
        if(str_contains($query, " ")) {
            $words = explode(" ", $query);
        }

        $sqlQuery = "SELECT * FROM " . $this->table .
                     " WHERE id LIKE '%" . $query . "%'" ;

        if(!empty($words)) {
            foreach($words as $word) {
                if(trim($word) == '') {
                    continue;
                }
                $sqlQuery = $sqlQuery . " OR id LIKE '%". $word . "%'"; 
            }
        }

        $sqlQuery = $sqlQuery . ';';

        $result = $this->query($sqlQuery);
        $jsons = $result->getResult();
        $searchResult = [
            'jsons' => $jsons,
            'locations' => $this->getLocations($jsons),
            'visitors' => $this->getVisitors($jsons)
        ]; 
        return $searchResult;
        
    }

    public function beforeInsert(array $data) {
        return $data;
    }

    public function beforeUpdate(array $data) {
        return $data;
    }

 }
?>