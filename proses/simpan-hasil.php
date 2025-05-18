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
// print_r("scaledMatrix".PHP_EOL);
// print_r($scaledMatrix);
// print_r("normalizedMatrix".PHP_EOL);
// print_r($normalizedMatrix);
// print_r("normalizedBobot".PHP_EOL);
// print_r($normalizedBobot);
// print_r("weightedMatrix".PHP_EOL);
// print_r($weightedMatrix);
// print_r("allType".PHP_EOL);
// print_r($allType);
// print_r("weightedMatrix".PHP_EOL);
// print_r($weightedMatrix);
// print_r("idealPositive".PHP_EOL);
// print_r($idealPositive);
// print_r("distancePositive".PHP_EOL);
// print_r($distancePositive);
// print_r("distanceNegative".PHP_EOL);
// print_r($distanceNegative);
// print_r("preferenceScores".PHP_EOL);
// print_r($preferenceScores);
// print_r("rankings".PHP_EOL);
// print_r($rankings);
// die;
// $scaledMatrixSample = array(
//     1 => array(
//         1 => 3,
//         2 => 4,
//         3 => 3,
//         4 => 3
//     ),
//     2 => array(
//         1 => 2,
//         2 => 3,
//         3 => 5,
//         4 => 3
//     ),
//     3 => array(
//         1 => 5,
//         2 => 5,
//         3 => 2,
//         4 => 4
//     ),
//     4 => array(
//         1 => 2,
//         2 => 2,
//         3 => 3,
//         4 => 2
//     )
// );