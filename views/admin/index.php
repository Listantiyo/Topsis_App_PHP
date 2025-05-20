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
  </style>
</head>
<body>
  <nav class="sidebar d-flex flex-column">
    <a href="#" class="text-white fs-4 fw-bold text-center mb-4">Dashboard Admin</a>
    <nav class="nav flex-column">
      <a class="nav-link active" href="index.php?page=index"><i class="bi bi-speedometer2"></i>Halaman Utama</a>
      <a class="nav-link" href="index.php?page=data-hp"><i class="bi bi-person"></i>Data Smartphone</a>
      <a class="nav-link" href="index.php?page=kelola-kriteria"><i class="bi bi-gear"></i> Kelola Kriteria</a>
      <a class="nav-link" href="index.php?page=hasil-perhitungan"><i class="bi bi-envelope"></i> Hasil Perhitungan</a>
      <!-- <a class="nav-link" href="index.php?page=evaluasi"><i class="bi bi-bar-chart"></i> Evaluasi</a> -->
      <a class="nav-link" href="index.php?page=logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>
  </nav>
  <main class="content">
    <h1>Selamat Datang</h1>
    <p class="lead">
    </p>
    <hr />
    <div class="row g-4">
      <div class="col-md-12">
        <div class="p-3 bg-white rounded shadow-sm">
          <h5 class="text-center">Sistem pengambilan keputusan pemilihan smartphone menggunakan metode TOPSIS.</h5>
          <!-- <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Necessitatibus in repellendus molestiae porro aliquid cupiditate, repudiandae culpa enim, est ipsam, accusamus ipsa. Eveniet nemo animi quas doloremque quos fugit minima!</p> -->
        </div>
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

