<?php
require_once 'BaseModel.php';
class Kriteria extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->getDB();
    }

    public function getAllKriteria()
    {
        $query = "SELECT * FROM kriteria";

        $result = $this->db->query($query);
        
        return $this->getFetcAssoc($result);
    }

    public function getAllNormalizedBobot(){
        $query = "SELECT id, bobot FROM kriteria";

        $result = $this->db->query($query);

        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row['id']] = [
                'bobot' => $row['bobot']/10,
            ];
        }
        return $data;
    }
    public function getAllKriteriaTipe(){
        $query = "SELECT id, tipe FROM kriteria";

        $result = $this->db->query($query);

        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row['id']] = [
                'tipe' => $row['tipe'],
            ];
        }
        return $data;
    }
}