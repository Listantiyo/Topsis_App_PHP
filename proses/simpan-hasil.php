<?php
require_once __DIR__.'/../config/database.php'; // koneksi database
require_once __DIR__ .'/../models/alternatif.php';
require __DIR__.'/../functions/topsis.php';

$modelAlt = new Alternatif();
$rawData = $modelAlt->getAllAlternatif();

$decisionMatrix     = bentukMatriks($rawData);
$scaledMatrix       = mapToSkala($decisionMatrix, $skala);
$normalizedMatrix   = normalisasi($scaledMatrix);
$weightedMatrix     = kalikanBobot($normalizedMatrix, $normalizedBobot);
$idealPositive      = hitungIdealPositif($weightedMatrix, $allType);
$idealNegative      = hitungIdealNegatif($weightedMatrix, $allType);
$distancePositive   = hitungJarak($weightedMatrix, $idealPositive);
$distanceNegative   = hitungJarak($weightedMatrix, $idealNegative);
$preferenceScores   = hitungPreferensi($distancePositive, $distanceNegative);
$rankings           = urutkanRanking($preferenceScores);

// Kosongkan dulu tabel hasil_topsis
$conn->query("TRUNCATE TABLE hasil_topsis");

// Simpan hasil ranking ke database
$stmt = $conn->prepare("INSERT INTO hasil_topsis (hp_id, skor, peringkat) VALUES (?, ?, ?)");
foreach ($rankings as $hpId => $hasil) {
    $skor = $hasil['score'];
    $rank = $hasil['rank'];
    $stmt->bind_param("idi", $hpId, $skor, $rank);
    $stmt->execute();
}

// Redirect balik ke halaman hasil
header("Location: ../index.php?page=hasil-perhitungan&status=sukses");
exit;