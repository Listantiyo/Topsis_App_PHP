<?php 

function debug($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die;
}
function mapDataHpToGroup($data){
    $linear = [];

    foreach ($data as $row) {
        $hpId = $row['hp_id'];

        if (!isset($linear[$hpId])) {
            $linear[$hpId]['nama_hp'] = $row['nama_hp'];
        }

        // Ambil kata pertama dari nama kriteria, lalu ubah ke lowercase
        $key = strtolower(explode(' ', $row['nama_kriteria'])[0]);

        $linear[$hpId][$key] = $row['nilai'];
    }

    return $linear;
}