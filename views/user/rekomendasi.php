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
    h3 {
      text-align: center;
      margin-bottom: 2rem;
      font-weight: 700;
      color: #0d6efd;
    }
    .table td, .table th {
      vertical-align: middle;
    }
  </style>
</head>
<body>
  <nav class="sidebar d-flex flex-column">
    <a href="#" class="text-white fs-4 fw-bold text-center mb-4">Dashboard User</a>
    <nav class="nav flex-column">
      <a class="nav-link" href="index.php?page=index"><i class="bi bi-speedometer2"></i>Home</a>
      <a class="nav-link active" href="index.php?page=rekomendasi"><i class="bi bi-bar-chart"></i> Rekomendasi</a>
      <a class="nav-link" href="index.php?page=logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>
  </nav>
  <main class="content">
    <h1></h1>
    <p class="lead">
    </p>
    <hr />
    <div class="container">
    <h3>Cari Rekomendasi Smartphone</h3>
    <form id="searchForm" action="views/user/hasil-rekomendasi.php" method="GET">
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-primary">
            <tr>
              <th>Kriteria</th>
              <th>Spesifikasi</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Harga</td>
              <td>
                <input type="number" class="form-control" id="hargaInput" name="harga" placeholder="Masukkan harga maksimal" min="0" />
              </td>
            </tr>
            <tr>
              <td>RAM (GB)</td>
              <td>
                <input type="number" class="form-control" id="ramInput" name="ram" placeholder="Masukkan minimal RAM" min="0" />
              </td>
            </tr>
            <tr>
              <td>Kamera (MP)</td>
              <td>
                <input type="number" class="form-control" id="kameraInput" name="kamera" placeholder="Masukkan minimal megapixel kamera" min="0" />
              </td>
            </tr>
            <tr>
              <td>Baterai (mAh)</td>
              <td>
                <input type="number" class="form-control" id="bateraiInput" name="baterai" placeholder="Masukkan minimal kapasitas baterai" min="0" />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-center">
        <button type="submit" class="btn btn-primary px-5">Cari</button>
        <!-- <a href="./hasil-rekomendasi.html" class="btn btn-primary px-5">Cari</a> -->
      </div>
    </form>
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

    <!-- <script>
    document.getElementById('searchForm').addEventListener('submit', function (e) {
      e.preventDefault();
      // Here you can add your search logic or API call
      const formData = new FormData(this);
      const filters = Object.fromEntries(formData.entries());

      // Example: just log the filter criteria
      console.log('Filter Criteria:', filters);

      alert('Fungsi pencarian belum diimplementasikan. Lihat konsol untuk data input.');
    });
  </script> -->
</body>
</html>

