<?php
require_once 'BaseModel.php';
class Alternatif extends Model{
    
    protected $db;

    public function __construct(){
        parent::__construct();
        $this->db = $this->getDB();
    }
    public function getAllAlternatif()
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
    public function getAllAlternatifByIds($hpIds)
    {
        if (empty($hpIds)) return [];
        
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

        // Sanitasi angka & buat placeholder untuk prepared statement
        $ids = array_map('intval', $hpIds);
        $idList = implode(',', $ids);
        $query .= " WHERE hp.id IN ($idList)";

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

    public function simpanHp($merk){
        // Simpan ke tabel hp
        $stmt =  $this->db->prepare("INSERT INTO hp (nama) VALUES (?)");
        $stmt->bind_param("s", $merk);
        $stmt->execute();
        $hp_id = $stmt->insert_id;

        return $hp_id;
    }

    public function simpanNilaiHP($hp_id, $kriteria_id, $nilai_input){
        $stmt2 = $this->db->prepare("INSERT INTO nilai_hp (hp_id, kriteria_id, nilai) VALUES (?, ?, ?)");
        $stmt2->bind_param("iid", $hp_id, $kriteria_id, $nilai_input);
        $stmt2->execute();
    }

    public function hapusHP($id) {
        $this->db->begin_transaction();
    
        try {
            $stmt1 = $this->db->prepare("DELETE FROM hasil_topsis WHERE hp_id = ?");
            $stmt1->bind_param("i", $id);
            $stmt1->execute();
            $stmt1->close();
    
            $stmt2 = $this->db->prepare("DELETE FROM nilai_hp WHERE hp_id = ?");
            $stmt2->bind_param("i", $id);
            $stmt2->execute();
            $stmt2->close();
    
            $stmt3 = $this->db->prepare("DELETE FROM hp WHERE id = ?");
            $stmt3->bind_param("i", $id);
            $stmt3->execute();
            $stmt3->close();
    
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    


}