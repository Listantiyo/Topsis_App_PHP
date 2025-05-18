<?php
require_once __DIR__ . '/../../models/alternatif.php';
require __DIR__ . '/../../functions/mapping.php';
require __DIR__ . '/../../functions/topsis.php';

$filters = [
  'ram' => $_GET['ram'] ?? null,
  'baterai' => $_GET['baterai'] ?? null,
  'kamera' => $_GET['kamera'] ?? null,
  'harga' => isset($_GET['harga']) ? round($_GET['harga'] / 1_000_000, 2) : null,
];

$filters = array_filter($filters); // buang null

$kriteriaMap = [
  'ram' => 1, // 1 = id_kriteria ram
  'baterai' => 2,
  'kamera' => 3,
  'harga' => 4,
];

$model = new Alternatif();
$filtered = $model->filterHP($filters, $kriteriaMap);

$ids = array();
foreach($filtered as $hp){
  $ids[] = $hp['hp_id'];
}

$rawData = $model->getAllAlternatif($ids);

$decisionMatrix = bentukMatriks($rawData, true);
$scaledMatrix = mapToSkala($decisionMatrix['matriks'], $skala);
$normalizedMatrix = normalisasi($scaledMatrix);
$weightedMatrix = kalikanBobot($normalizedMatrix, $normalizedBobot);
$idealPositive = hitungIdealPositif($weightedMatrix, $allType);
$idealNegative = hitungIdealNegatif($weightedMatrix, $allType);
$distancePositive = hitungJarak($weightedMatrix, $idealPositive);
$distanceNegative = hitungJarak($weightedMatrix, $idealNegative);
$preferenceScores = hitungPreferensi($distancePositive, $distanceNegative);
$rankings = urutkanRanking($preferenceScores);

$matriks = $decisionMatrix['matriks'];
$listAlternatif = $decisionMatrix['list_alternatif'];
$listKriteria = $decisionMatrix['list_kriteria'];

$gabungan = [];

$gabungan = [];

foreach ($matriks as $hp_id => $nilaiKriteria) {
  $data = [
    'hp_id' => $hp_id,
    'nama_hp' => $listAlternatif[$hp_id],
    'skor' => floatval(number_format($rankings[$hp_id]['score'], 2, '.', '')),
    'peringkat' => $rankings[$hp_id]['rank'],
  ];

  // Tambahkan tiap nilai kriteria sebagai kolom
  foreach ($nilaiKriteria as $kriteria_id => $nilai) {
    $nama_kolom = strtolower(str_replace([' ', '(', ')'], ['_', '', ''], $listKriteria[$kriteria_id]['nama']));
    $data[$nama_kolom] = floatval(number_format($nilai, 2, '.', ''));
  }

  $gabungan[] = $data;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Hasil Rekomendasi Smartphone</title>
  <!-- Bootstrap CSS CDN -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <style>
    body {
      background-color: #fefefe;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 1rem;
    }
    .btn-home {
      position: fixed;
      top: 1rem;
      left: 1rem;
      z-index: 1030;
    }
    h1 {
      margin-top: 3rem;
      margin-bottom: 2rem;
      text-align: center;
      color: #0d6efd;
      font-weight: 700;
    }
    .table thead th {
      text-align: center;
      vertical-align: middle;
    }
    .table tbody td {
      vertical-align: middle;
      text-align: center;
    }
    .table tbody td:first-child {
      text-align: left;
    }
  </style>
</head>
<body>
  <a href="/index.php?page=rekomendasi" class="btn btn-outline-primary btn-home">Home</a>
  <div class="container">
    <h1>Hasil Rekomendasi Smartphone</h1>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-primary">
          <tr>
            <th scope="col">Merk</th>
            <th scope="col">Harga (Rp)</th>
            <th scope="col">RAM (GB)</th>
            <th scope="col">Kamera (MP)</th>
            <th scope="col">Baterai (mAh)</th>
            <th scope="col">Rank</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($gabungan as $value):?>
          <tr>
            <td><?php echo $value['nama_hp']?></td>
            <td><?php echo $value['harga_juta'] ?> Jt</td>
            <td><?php echo $value['ram_gb'] ?> GB</td>
            <td><?php echo $value['kamera_mp'] ?> MP</td>
            <td><?php echo $value['baterai_mah'] ?> mAh</td>
            <td><?php echo $value['peringkat'] ?></td>
          </tr>
          <?php endforeach?>
        </tbody>
      </table>
    </div>
  </div>
  <!-- Bootstrap JS Bundle -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
  ></script>
</body>
</html>

