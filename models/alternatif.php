<?php
require_once 'BaseModel.php';
class Alternatif extends Model{
    
    protected $db;

    public function __construct(){
        parent::__construct();
        $this->db = $this->getDB();
    }
    public function getAllAlternatif($hpIds = [])
    {
        $query = "
        SELECT 
            hp.id AS hp_id,
            hp.nama AS nama_hp,
            kriteria.id AS kriteria_id,
            kriteria.nama AS nama_kriteria,
            kriteria.bobot,
            nh.nilai
        FROM nilai_hp nh
        JOIN hp ON nh.hp_id = hp.id
        JOIN kriteria ON nh.kriteria_id = kriteria.id
        ";

        // Tambahkan filter jika ada hpIds
        if (!empty($hpIds)) {
            // Sanitasi angka & buat placeholder untuk prepared statement
            $ids = array_map('intval', $hpIds);
            $idList = implode(',', $ids);
            $query .= " WHERE hp.id IN ($idList)";
        }

        $query .= "ORDER BY hp.id, kriteria.id";

        $result = $this->db->query($query);
        return $this->getFetcAssoc($result);
    }
    public function getAlternatifName(){
        $query = "SELECT * FROM hp";
        $result = $this->db->query($query);

        return $this->getFetcAssoc($result);
    }
    public function getNilaiAlternatifById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT nh.*, k.nama as kriteria 
                FROM nilai_hp nh JOIN kriteria k ON k.id = nh.kriteria_id WHERE nh.hp_id = ?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $this->getFetcAssoc($result);
    }
    public function getRangkings()
    {
        $query = "SELECT hp.nama, ht.* FROM hasil_topsis ht JOIN hp ON hp.id = ht.hp_id";
        $result = $this->db->query($query);

        return $this->getFetcAssoc($result);
    }

    public function filterHP($filters, $kriteriaMap)
    {
        $whereClauses = [];
        $jumlah = 0;

        foreach ($filters as $key => $value) {
            if (!isset($kriteriaMap[$key]))
                continue;

            $kriteria_id = $kriteriaMap[$key];
            $value = floatval($value);
            $jumlah++;

            $operator = ($key === 'harga') ? '<=' : '>=';

            $whereClauses[] = "(kriteria_id = $kriteria_id AND nilai $operator $value)";
        }

        if ($jumlah === 0)
            return [];

        $whereSQL = implode(" OR ", $whereClauses);

        $query = "
        SELECT hp_id 
        FROM nilai_hp 
        WHERE $whereSQL 
        GROUP BY hp_id 
        HAVING COUNT(DISTINCT kriteria_id) = $jumlah
    ";

        $result = $this->db->query($query);
        return $this->getFetcAssoc($result); // atau fetch_all/fetch_assoc sesuai yang kamu pakai
    }


}