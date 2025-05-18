<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard with Sidebar Menu</title>
  <!-- Bootstrap CSS CDN -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
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

    /* STYLE CONTENT */
    .table thead th {
      text-align: center;
      vertical-align: middle;
    }
    .table tbody td, .table tbody th {
      vertical-align: middle;
    }
    .col-center {
      text-align: center;
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
    <h1>Hasil Perhitungan TOPSIS</h1>
    <p class="lead">
    </p>
    <hr />
      <div class="container">
      <form action="proses/simpan-hasil.php" method="POST" onsubmit="return confirm('Yakin ingin menghitung dan menyimpan hasil?');">
        <button type="submit" class="btn btn-warning mb-3" >
          Hitung Rangking
        </button>
      </form>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-primary">
          <tr>
            <th scope="col" class="col-center">No</th>
            <th scope="col">Merk</th>
            <th scope="col" class="col-center">Hasil Bobot</th>
            <th scope="col" class="col-center">Rank</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($data as $idx => $value):?>
          <tr>
            <th scope="row" class="col-center"><?php echo $idx+1 ?></th>
            <td><?php echo $value['nama']?></td>
            <td class="col-center"><?php echo $value['skor'] ?></td>
            <td class="col-center"><?php echo $value['peringkat'] ?></td>
          </tr>
          <?php endforeach?>
        </tbody>
      </table>
    </div>
  </div>
  </main>

  <!-- Bootstrap Icons CDN -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
  />
  <!-- Bootstrap Bundle JS CDN (includes Popper) -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
  ></script>
</body>
</html>

