<?php
require_once __DIR__ .'/../models/alternatif.php';
require __DIR__ .'/../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;


// Cek apakah file diupload
if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] == 0) {
    $tmpFilePath = $_FILES['excelFile']['tmp_name'];

    try {
        $modelAlt = new Alternatif();
        $spreadsheet = IOFactory::load($tmpFilePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        for ($i = 1; $i < count($rows); $i++) {
            $merk = trim($rows[$i][0]);
            $harga = floatval($rows[$i][1]) / 1000000; // harga dibagi 1jt
            $ram = floatval($rows[$i][2]);
            $kamera = floatval($rows[$i][3]);
            $baterai = floatval($rows[$i][4]);

            if ($merk === '' || $harga === '' || $ram === '' || $kamera === '' || $baterai === '') {
                echo "<script>alert('Baris ke-" . ($i + 1) . " ada kolom kosong. Import dibatalkan.'); window.location.href='../index.php?page=data-hp';</script>";
                exit;
            }

            // Simpan ke tabel hp
            $hp_id =  $modelAlt->simpanHp($merk);

            // Simpan ke tabel nilai_hp
            $nilai = [
                1 => $ram,
                2 => $baterai,
                3 => $kamera,
                4 => $harga
            ];

            foreach ($nilai as $kriteria_id => $nilai_input) {
                $modelAlt->simpanNilaiHP($hp_id, $kriteria_id, $nilai_input);
            }
        }

        echo "<script>alert('Import berhasil!'); window.location.href='../index.php?page=data-hp';</script>";
    } catch (Exception $e) {
        echo "Gagal memproses file Excel: " . $e->getMessage();
    }
} else {
    echo "File tidak valid atau tidak diunggah.";
}