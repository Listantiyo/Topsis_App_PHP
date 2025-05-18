<?php
require_once 'BaseModel.php';
class Nilai extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->getDB();
    }

    public function getAllSkala(){
        $query = "SELECT * FROM skala_nilai";

        $result = $this->db->query($query);

        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row['kriteria_id']][] = [
                'min' => $row['nilai_min'],
                'max' => $row['nilai_max'],
                'poin' => $row['poin']
            ];
        }
        return $data;
    }
}