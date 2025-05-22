<?php
require_once 'models/alternatif.php';
require 'functions/topsis.php';

$model = new Alternatif();

$rawData = $model->getAllAlternatif();

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

/* Prepare Data To be Render */
$decisionMatrixRenderData = mergeMatrixWithKriteria($decisionMatrix['matriks'], $listKriteria, $listAlternatif);
$scaledMatrixRenderData = mergeMatrixWithKriteria($scaledMatrix, $listKriteria, $listAlternatif);
$normalizedMatrixRenderData = mergeMatrixWithKriteria($normalizedMatrix, $listKriteria, $listAlternatif);
$weightedMatrixRenderData = mergeMatrixWithKriteria($weightedMatrix, $listKriteria, $listAlternatif);
$idealPositiveRenderData = mergeKriteriaNames($idealPositive, $listKriteria);
$idealNegativeRenderData = mergeKriteriaNames($idealNegative, $listKriteria);
$distancePositiveRenderData = mergeAltNames($distancePositive, $listAlternatif);
$distanceNegativeRenderData = mergeAltNames($distanceNegative, $listAlternatif);
$preferencesRenderData = mergeAltNames($preferenceScores, $listAlternatif);
$rangkingsRenderData = [];

foreach ($rankings as $hp_id => $ranking) {
  $data = [
    'hp_id' => $hp_id,
    'nama_hp' => $listAlternatif[$hp_id],
    'skor' => floatval(number_format($ranking['score'], 2, '.', '')),
    'peringkat' => $ranking['rank'],
  ];

  // Tambahkan tiap nilai kriteria sebagai kolom
  foreach ($matriks[$hp_id] as $kriteria_id => $nilai) {
    $nama_kolom = strtolower(str_replace([' ', '(', ')'], ['_', '', ''], $listKriteria[$kriteria_id]['nama']));
    $data[$nama_kolom] = floatval(number_format($nilai, 2, '.', ''));
  }

  $rangkingsRenderData[] = $data;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Hasil Rekomendasi Smartphone</title>
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #fefefe;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .sidebar {
      min-height: 100vh;
      background-color: #343a40;
      color: #fff;
      padding-top: 1rem;
      position: fixed;
      width: 250px;
    }

    .sidebar .nav-link {
      color: #adb5bd;
      font-weight: 500;
      padding: 12px 20px;
      transition: background-color 0.3s, color 0.3s;
    }

    .sidebar .nav-link:hover, .sidebar .nav-link.active {
      background-color: #495057;
      color: #fff;
      border-radius: 0.375rem;
    }

    .sidebar .nav-link i {
      margin-right: 10px;
    }

    .content {
      margin-left: 250px;
      padding: 2rem;
    }

    @media (max-width: 767.98px) {
      .sidebar {
        position: relative;
        width: 100%;
        min-height: auto;
      }
      .content {
        margin-left: 0;
      }
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

    /* Paginate */
    .paginateTable .paginationjs {
      display: flex;
      justify-content: flex-start;
      padding: 15px 0;
      font-family: Arial, sans-serif;
    }

    .paginateTable .paginationjs-pages ul {
      list-style: none;
      margin: 0;
      padding: 0;
      display: flex;
      gap: 5px;
    }

    .paginateTable .paginationjs-page,
    .paginateTable .paginationjs-prev,
    .paginateTable .paginationjs-next {
      display: inline-block;
    }

    .paginateTable .paginationjs-page a,
    .paginateTable .paginationjs-prev a,
    .paginateTable .paginationjs-next a {
      padding: 8px 12px;
      text-decoration: none;
      border: 1px solid #ddd;
      color: #333;
      border-radius: 4px;
      transition: background-color 0.2s;
      cursor: pointer;
    }

    .paginateTable .paginationjs-page.active a {
      background-color: #007bff;
      color: white;
      border-color: #007bff;
    }

    .paginateTable .paginationjs-page a:hover,
    .paginateTable .paginationjs-prev a:hover,
    .paginateTable .paginationjs-next a:hover {
      background-color: #f0f0f0;
    }

    .paginateTable .paginationjs-prev.disabled a,
    .paginateTable .paginationjs-next.disabled a {
      color: #ccc;
      cursor: not-allowed;
      border-color: #eee;
      background-color: #fafafa;
    }
  </style>
</head>

<body>
<nav class="sidebar d-flex flex-column">
    <a href="#" class="text-white fs-4 fw-bold text-center mb-4">Dashboard Admin</a>
    <nav class="nav flex-column">
      <a class="nav-link" href="index.php?page=index"><i class="bi bi-speedometer2"></i>Halaman Utama</a>
      <a class="nav-link" href="index.php?page=data-hp"><i class="bi bi-person"></i>Data Smartphone</a>
      <a class="nav-link" href="index.php?page=kelola-kriteria"><i class="bi bi-gear"></i> Kelola Kriteria</a>
      <a class="nav-link active" href="index.php?page=hasil-perhitungan"><i class="bi bi-envelope"></i> Hasil Perhitungan</a>
      <!-- <a class="nav-link" href="index.php?page=evaluasi"><i class="bi bi-bar-chart"></i> Evaluasi</a> -->
      <a class="nav-link" href="index.php?page=logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>
  </nav>
  <main class="content">
    <div class="container">
      <h1>Perhitungan Topsis</h1>
      <!-- Matriks Keputusan (alternatif x kriteria) awal -->
      <h5>Matriks Keputusan (alternatif x kriteria) awal</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-primary">
            <tr>
              <th scope="col">Merk</th>
              <th scope="col">Harga (Rp)</th>
              <th scope="col">RAM (GB)</th>
              <th scope="col">Kamera (MP)</th>
              <th scope="col">Baterai (mAh)</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
          <tfoot id="datadecisionMatrixRenderData" class="paginateTable">
            <!-- List Paginate -->
          </tfoot>
        </table>
      </div>
      <!-- Matriks Keputusan Ternilai (data mentah dikonversi ke skala) -->
      <h5>Matriks Keputusan Ternilai (data mentah dikonversi ke skala)</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-primary">
            <tr>
              <th scope="col">Merk</th>
              <th scope="col">Harga (Rp)</th>
              <th scope="col">RAM (GB)</th>
              <th scope="col">Kamera (MP)</th>
              <th scope="col">Baterai (mAh)</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
          <tfoot id="datascaledMatrixRenderData" class="paginateTable">
            <!-- List Paginate -->
          </tfoot>
        </table>
      </div>
      <!-- Matriks Normalisasi -->
      <h5>Matriks Normalisasi</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-primary">
            <tr>
              <th scope="col">Merk</th>
              <th scope="col">Harga (Rp)</th>
              <th scope="col">RAM (GB)</th>
              <th scope="col">Kamera (MP)</th>
              <th scope="col">Baterai (mAh)</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
          <tfoot id="datanormalizedMatrixRenderData" class="paginateTable">
            <!-- List Paginate -->
          </tfoot>
        </table>
      </div>
      <!-- Matriks Normalisasi Terbobot -->
      <h5>Matriks Normalisasi Terbobot</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-primary">
            <tr>
              <th scope="col">Merk</th>
              <th scope="col">Harga (Rp)</th>
              <th scope="col">RAM (GB)</th>
              <th scope="col">Kamera (MP)</th>
              <th scope="col">Baterai (mAh)</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
          <tfoot id="dataweightedMatrixRenderData" class="paginateTable">
            <!-- List Paginate -->
          </tfoot>
        </table>
      </div>
      <!-- Table Ideal Positive -->
      <h5>Solusi Ideal Positive (A+)</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-primary">
            <tr>
              <th scope="col">Kriteria</th>
              <th scope="col">Ideal Positive</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
          <tfoot id="dataIdealPositive" class="paginateTable">
            <!-- List Paginate -->
          </tfoot>
        </table>
      </div>
      <!-- Table Ideal Negatif -->
      <h5>Solusi Ideal Negatif (A−)</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-primary">
            <tr>
              <th scope="col">Kriteria</th>
              <th scope="col">Ideal Negative</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
          <tfoot id="dataIdealNegative" class="paginateTable">
            <!-- List Paginate -->
          </tfoot>
        </table>
      </div>
      <!-- Table Distance Positive -->
      <h5>Jarak ke Solusi Ideal Positif (D+)</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-primary">
            <tr>
              <th scope="col">Merk</th>
              <th scope="col">Distance Positive</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
          <tfoot id="dataDistancePositive" class="paginateTable">
            <!-- List Paginate -->
          </tfoot>
        </table>
      </div>
      <!-- Table Distance Negative -->
      <h5>Jarak ke Solusi Ideal Negatif (D−)</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-primary">
            <tr>
              <th scope="col">Merk</th>
              <th scope="col">Distance Negative</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
          <tfoot id="dataDistanceNegative" class="paginateTable">
            <!-- List Paginate -->
          </tfoot>
        </table>
      </div>
      <!-- Table Preferences -->
      <h5>Nilai Preferensi (Vᵢ)</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-primary">
            <tr>
              <th scope="col">Merk</th>
              <th scope="col">Score Preferences</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
          <tfoot id="preferenceScores" class="paginateTable">
            <!-- List Paginate -->
          </tfoot>
        </table>
      </div>
      <!-- Table Final Rangkings Topsis -->
      <h5>Peringkat Alternatif Smartphone</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-primary">
            <tr>
              <th scope="col">Merk</th>
              <th scope="col">Harga (Rp)</th>
              <th scope="col">RAM (GB)</th>
              <th scope="col">Kamera (MP)</th>
              <th scope="col">Baterai (mAh)</th>
              <th scope="col">Skor</th>
              <th scope="col">Rank</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
          <tfoot id="dataPeringkat" class="paginateTable">
            <!-- List Paginate -->
          </tfoot>
        </table>
      </div>
    </div>
  </main>
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="views/js/pagination.min.js"></script>
  <script>
    /* Prepare Render final dataPeringkat */
    let dataPeringkat = <?php echo json_encode($rangkingsRenderData) ?>;
    let columnsDataPeringkat = ['nama_hp', 'harga_juta', 'ram_gb', 'kamera_mp', 'baterai_mah', 'skor', 'peringkat'];
    /* Prepare Render dataPreference */
    let dataPreference = <?php echo json_encode($preferencesRenderData) ?>;
    let columnsdataPreference = false;
    /* Prepare Render dataDistanceNegative */
    let dataDistanceNegative = <?php echo json_encode($distanceNegativeRenderData) ?>;
    let columnsdataDistanceNegative = false;
    /* Prepare Render dataDistancePositive */
    let dataDistancePositive = <?php echo json_encode($distancePositiveRenderData) ?>;
    let columnsdataDistancePositive = false;
    /* Prepare Render dataidealNegative */
    let dataIdealNegative = <?php echo json_encode($idealNegativeRenderData) ?>;
    let columnsdataidealNegative = false;
    /* Prepare Render dataidealPositive */
    let dataIdealPositive = <?php echo json_encode($idealPositiveRenderData) ?>;
    let columnsdataidealPositive = false;
    /* Prepare Render dataweightedMatrixRenderData */
    let dataweightedMatrixRenderData = <?php echo json_encode($weightedMatrixRenderData) ?>;
    let columnsdataweightedMatrixRenderData = ['nama_hp', 'harga_juta', 'ram_gb', 'kamera_mp', 'baterai_mah'];
    /* Prepare Render datanormalizedMatrixRenderData */
    let datanormalizedMatrixRenderData = <?php echo json_encode($normalizedMatrixRenderData) ?>;
    let columnsdatanormalizedMatrixRenderData = ['nama_hp', 'harga_juta', 'ram_gb', 'kamera_mp', 'baterai_mah'];
    /* Prepare Render datascaledMatrixRenderData */
    let datascaledMatrixRenderData = <?php echo json_encode($scaledMatrixRenderData) ?>;
    let columnsdatascaledMatrixRenderData = ['nama_hp', 'harga_juta', 'ram_gb', 'kamera_mp', 'baterai_mah'];
    /* Prepare Render datadecisionMatrixRenderData */
    let datadecisionMatrixRenderData = <?php echo json_encode($decisionMatrixRenderData) ?>;
    let columnsdatadecisionMatrixRenderData = ['nama_hp', 'harga_juta', 'ram_gb', 'kamera_mp', 'baterai_mah'];



    /* Renders */
    renderTable($('#datadecisionMatrixRenderData'), datadecisionMatrixRenderData, columnsdatadecisionMatrixRenderData);
    renderTable($('#datascaledMatrixRenderData'), datascaledMatrixRenderData, columnsdatascaledMatrixRenderData);
    renderTable($('#datanormalizedMatrixRenderData'), datanormalizedMatrixRenderData, columnsdatanormalizedMatrixRenderData);
    renderTable($('#dataweightedMatrixRenderData'), dataweightedMatrixRenderData, columnsdataweightedMatrixRenderData);
    renderTable($('#dataIdealPositive'), dataIdealPositive, columnsdataidealPositive);
    renderTable($('#dataIdealNegative'), dataIdealNegative, columnsdataidealNegative);
    renderTable($('#dataDistancePositive'), dataDistancePositive, columnsdataDistancePositive);
    renderTable($('#dataDistanceNegative'), dataDistanceNegative, columnsdataDistanceNegative);
    renderTable($('#preferenceScores'), dataPreference, columnsdataPreference);
    renderTable($('#dataPeringkat'), dataPeringkat, columnsDataPeringkat);

    function renderTable(elm, datas, columns) {
      elm.pagination({
        dataSource: datas,
        pageSize: 5,
        showPrevious: true,
        showNext: true,
        callback: function (data, pagination) {
          // template method of yourself
          var html = template(data, columns);
          elm.prev().html(html);
        }
      })
    }

    function template(data, columns) {
      return data.map(value => {
        if (typeof value !== 'object') return false;
        let tds = '';
        switch (columns !== false) {
          case true:
            for (const idx in columns) {
              tds += `<td>${value[columns[idx]]}</td>`
            };
            break;
          case false:
            for (const idx in value) {
              tds += `<td>${value[idx]}</td>`
            };
            break;
        }
        return `<tr> ${tds} </tr>`;
      }).reduce((prev, next) => {
        console.log(next);
        return prev += next;
      }, '');
    }
  </script>
</body>

</html>