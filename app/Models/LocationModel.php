<?php namespace App\Models;

use CodeIgniter\Model;

class LocationModel extends Model {
    protected $table = 'locations';
    protected $allowedFields = ['id', 'countryCode', 'city'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $db;
    public function __construct() {
        $this->db = db_connect();
        
    }

    public function getLocationById($id) {
        parent::__construct();
        return $this->where('id', $id)->first();
    }

    public function getLocation($data) {
        parent::__construct();
        return $this->where($data)->first(); 
    }

    public function saveLocation($data) {
        if($this->getLocation($data)) {
            return $this->getLocation($data)['id'];
        }

        $this->save($data);
        return $this->insertID();
    }

    public function getLastTenUpdated() {
        $builder = $this->db->table($this->table)->orderBy('uploaded_at','DESC')->limit(10);
        $query = $builder->get();
        return $query->getResult();
    }

    public function getAllByName() {
        $builder = $this->db->table($this->table)->orderBy('name', 'ASC');
        $query = $builder->get();
        return $query->getResult();
    }

    public function getImageQuery($query) {
        $db = db_connect();
        $result = $db->query($query);
        return $result->getResult();
    }

    public function deleteImage($id) {
        $this->delete($id);
    }

    public function getImagePath($id) {
        // Does not work without the parent construct call
        // If you add this to the contructor, it breaks the upload.
        parent::__construct();  
        
        $image = $this->where('id', $id)->first();
        return (isset($image)) ? $image['path'] : null;
    }

    public function searchImages($query) {
        $query = trim($this->db->escapeString($query));

        $words = null;
        if(str_contains($query, " ")) {
            $words = explode(" ", $query);
        }

        $sqlQuery = "SELECT *, 'N/A' as duration, 'Image' as filetype FROM " . $this->table .
                     " WHERE name LIKE '%" . $query . "%'" ;

        if(!empty($words)) {
            foreach($words as $word) {
                if(trim($word) == '') {
                    continue;
                }
                $sqlQuery = $sqlQuery . " OR name LIKE '%". $word . "%'"; 
            }
        }

        $sqlQuery = $sqlQuery . ';';

        $result = $this->query($sqlQuery);

        return $result->getResult();
        
    }

    public function beforeInsert(array $data) {
        return $data;
    }

    public function beforeUpdate(array $data) {
        return $data;
    }

 }
?>