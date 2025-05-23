<?php
require_once __DIR__ . '/../../models/alternatif.php';
require __DIR__ . '/../../functions/mapping.php';
require __DIR__ . '/../../functions/topsis.php';

$filters = [
  'ram' => $_GET['ram'] ?? null,
  'baterai' => $_GET['baterai'] ?? null,
  'kamera' => $_GET['kamera'] ?? null,
  'harga' => (isset($_GET['harga']) && is_numeric($_GET['harga']))
    ? round(floatval($_GET['harga']) / 1_000_000, 2)
    : null,

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
$rangkingsRenderData = [];
$rawData = $model->getAllAlternatifByIds($ids);
if(!empty($rawData)){
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
  // $decisionMatrixRenderData = mergeMatrixWithKriteria($decisionMatrix['matriks'], $listKriteria, $listAlternatif);
  // $scaledMatrixRenderData = mergeMatrixWithKriteria($scaledMatrix, $listKriteria, $listAlternatif);
  // $normalizedMatrixRenderData = mergeMatrixWithKriteria($normalizedMatrix, $listKriteria, $listAlternatif);
  // $weightedMatrixRenderData = mergeMatrixWithKriteria($weightedMatrix, $listKriteria, $listAlternatif);
  // $idealPositiveRenderData = mergeKriteriaNames($idealPositive, $listKriteria);
  // $idealNegativeRenderData = mergeKriteriaNames($idealNegative, $listKriteria);
  // $distancePositiveRenderData = mergeAltNames($distancePositive, $listAlternatif);
  // $distanceNegativeRenderData = mergeAltNames($distanceNegative, $listAlternatif);
  // $preferencesRenderData = mergeAltNames($preferenceScores, $listAlternatif);

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
  <a href="../../index.php?page=rekomendasi" class="btn btn-outline-primary btn-home">Home</a>
  <div class="container">
    <!-- <h1>Perhitungan Topsis</h1> -->
    <!-- Matriks Keputusan (alternatif x kriteria) awal -->
    <h5 class="d-none">Matriks Keputusan (alternatif x kriteria) awal</h5>
    <div class="table-responsive d-none">
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
    <h5 class="d-none">Matriks Keputusan Ternilai (data mentah dikonversi ke skala)</h5>
    <div class="table-responsive d-none">
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
    <h5 class="d-none">Matriks Normalisasi</h5>
    <div class="table-responsive d-none">
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
    <h5 class="d-none">Matriks Normalisasi Terbobot</h5>
    <div class="table-responsive d-none">
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
    <h5 class="d-none">Solusi Ideal Positive (A+)</h5>
    <div class="table-responsive d-none">
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
    <h5 class="d-none">Solusi Ideal Negatif (A−)</h5>
    <div class="table-responsive d-none">
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
    <h5 class="d-none">Jarak ke Solusi Ideal Positif (D+)</h5>
    <div class="table-responsive d-none">
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
    <h5 class="d-none">Jarak ke Solusi Ideal Negatif (D−)</h5>
    <div class="table-responsive d-none">
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
    <h5 class="d-none">Nilai Preferensi (Vᵢ)</h5>
    <div class="table-responsive d-none">
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
    <h1>Peringkat Alternatif Smartphone</h1>
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
  <!-- Bootstrap JS Bundle -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
  ></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="../js/pagination.min.js"></script>
  <script>
    /* Prepare Render final dataPeringkat */
    let dataPeringkat = <?php echo json_encode($rangkingsRenderData)?>;
    let columnsDataPeringkat = ['nama_hp','harga_juta','ram_gb','kamera_mp','baterai_mah','skor','peringkat'];
    /* Prepare Render dataPreference */
    // let dataPreference = <?php //echo json_encode($preferencesRenderData)?>;
    // let columnsdataPreference = false;
    // /* Prepare Render dataDistanceNegative */
    // let dataDistanceNegative = <?php //echo json_encode($distanceNegativeRenderData)?>;
    // let columnsdataDistanceNegative = false;
    // /* Prepare Render dataDistancePositive */
    // let dataDistancePositive = <?php //echo json_encode($distancePositiveRenderData)?>;
    // let columnsdataDistancePositive = false;
    // /* Prepare Render dataidealNegative */
    // let dataIdealNegative = <?php //echo json_encode($idealNegativeRenderData)?>;
    // let columnsdataidealNegative = false;
    // /* Prepare Render dataidealPositive */
    // let dataIdealPositive = <?php //echo json_encode($idealPositiveRenderData)?>;
    // let columnsdataidealPositive = false;
    // /* Prepare Render dataweightedMatrixRenderData */
    // let dataweightedMatrixRenderData = <?php //echo json_encode($weightedMatrixRenderData) ?>;
    // let columnsdataweightedMatrixRenderData = ['nama_hp','harga_juta','ram_gb','kamera_mp','baterai_mah'];
    // /* Prepare Render datanormalizedMatrixRenderData */
    // let datanormalizedMatrixRenderData = <?php //echo json_encode($normalizedMatrixRenderData) ?>;
    // let columnsdatanormalizedMatrixRenderData = ['nama_hp','harga_juta','ram_gb','kamera_mp','baterai_mah'];
    // /* Prepare Render datascaledMatrixRenderData */
    // let datascaledMatrixRenderData = <?php //echo json_encode($scaledMatrixRenderData) ?>;
    // let columnsdatascaledMatrixRenderData = ['nama_hp','harga_juta','ram_gb','kamera_mp','baterai_mah'];
    // /* Prepare Render datadecisionMatrixRenderData */
    // let datadecisionMatrixRenderData = <?php //echo json_encode($decisionMatrixRenderData) ?>;
    // let columnsdatadecisionMatrixRenderData = ['nama_hp','harga_juta','ram_gb','kamera_mp','baterai_mah'];
    
    
    
    /* Renders */
    // renderTable($('#datadecisionMatrixRenderData'), datadecisionMatrixRenderData, columnsdatadecisionMatrixRenderData);
    // renderTable($('#datascaledMatrixRenderData'), datascaledMatrixRenderData, columnsdatascaledMatrixRenderData);
    // renderTable($('#datanormalizedMatrixRenderData'), datanormalizedMatrixRenderData, columnsdatanormalizedMatrixRenderData);
    // renderTable($('#dataweightedMatrixRenderData'), dataweightedMatrixRenderData, columnsdataweightedMatrixRenderData);
    // renderTable($('#dataIdealPositive'), dataIdealPositive, columnsdataidealPositive);
    // renderTable($('#dataIdealNegative'), dataIdealNegative, columnsdataidealNegative);
    // renderTable($('#dataDistancePositive'), dataDistancePositive, columnsdataDistancePositive);
    // renderTable($('#dataDistanceNegative'), dataDistanceNegative, columnsdataDistanceNegative);
    // renderTable($('#preferenceScores'), dataPreference, columnsdataPreference);
    renderTable($('#dataPeringkat'), dataPeringkat, columnsDataPeringkat);

    function renderTable(elm, datas, columns){
      elm.pagination({
          dataSource: datas,
          pageSize: 5,
          showPrevious: true,
          showNext: true,
          callback: function(data, pagination) {
              // template method of yourself
              var html = template(data, columns);
              elm.prev().html(html);
          }
      })
    }

    function template(data,columns){
      return data.map(value => {
        if(typeof value !== 'object') return false;
          let tds = '';
          switch(columns !== false){
            case true :
              for(const idx in columns ){
                tds += `<td>${value[columns[idx]]}</td>`
              };
            break;
            case false:
              for(const idx in value ){
                tds += `<td>${value[idx]}</td>`
              };
            break;
          }
        return `<tr> ${tds} </tr>`;
      }).reduce((prev,next) => {
        console.log(next);
        return prev += next;
      },'');
    }
  </script>
</body>
</html>

