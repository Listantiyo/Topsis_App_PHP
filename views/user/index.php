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
    img.hp-img {
      max-width: 80px;
      height: auto;
      border-radius: 6px;
    }
  </style>
</head>
<body>
  <nav class="sidebar d-flex flex-column">
    <a href="#" class="text-white fs-4 fw-bold text-center mb-4">Dashboard User</a>
    <nav class="nav flex-column">
      <a class="nav-link active" href="index.php?page=index"><i class="bi bi-speedometer2"></i>Home</a>
      <a class="nav-link" href="index.php?page=rekomendasi"><i class="bi bi-bar-chart"></i> Rekomendasi</a>
      <a class="nav-link" href="index.php?page=logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>
  </nav>
  <main class="content">
    <h1></h1>
    <p class="lead">
    </p>
    <hr />
    <div class="container">
      <h2 class="mb-4 text-center">Daftar HP</h2>
      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <!-- <th>Gambar</th> -->
              <th>Nama HP</th>
              <th>Spesifikasi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($data as $value):?>
            <tr>
              <td><?php echo $value['nama_hp']?></td>
              <td>
                RAM: <?php echo $value['ram']?>  GB<br>
                Kamera: <?php echo $value['kamera']?>  MP<br>
                Baterai: <?php echo $value['baterai']?> mAh<br>
                Rp <?php echo number_format($value['harga'] * 1000000, 0, ',', '.'); ?>
              </td>
            </tr>
            <?php endforeach?>
            <!-- <tr>
              <td><img src="https://fdn2.gsmarena.com/vv/pics/samsung/samsung-galaxy-s21-ultra-5g-1.jpg" alt="Samsung Galaxy S21 Ultra" class="hp-img" /></td>
              <td>Samsung Galaxy S21 Ultra</td>
              <td>
                Layar: 6.8 inci Dynamic AMOLED 2X<br>
                Chipset: Exynos 2100 / Snapdragon 888<br>
                Kamera: 108 + 10 + 10 + 12 MP<br>
                Baterai: 5000 mAh<br>
                OS: Android 11
              </td>
            </tr>
            <tr>
              <td><img src="https://fdn2.gsmarena.com/vv/pics/oneplus/oneplus-nord-ce-2-5g-1.jpg" alt="OnePlus Nord CE 2" class="hp-img" /></td>
              <td>OnePlus Nord CE 2</td>
              <td>
                Layar: 6.43 inci AMOLED<br>
                Chipset: MediaTek Dimensity 900<br>
                Kamera: 64 + 2 + 2 MP<br>
                Baterai: 4500 mAh<br>
                OS: Android 11
              </td>
            </tr>
            <tr>
              <td><img src="https://cdn1.smartprix.com/rx-izthhEdsG-w420-h420/xiaomi-redmi-note-11.webp" alt="Xiaomi Redmi Note 11 Pro" class="hp-img" /></td>
              <td>Xiaomi Redmi Note 11 Pro</td>
              <td>
                Layar: 6.67 inci AMOLED 120Hz<br>
                Chipset: Qualcomm Snapdragon 695<br>
                Kamera: 108 + 8 + 2 MP<br>
                Baterai: 4500 mAh<br>
                OS: Android 11
              </td>
            </tr>
            <tr>
              <td><img src="https://cdn1.smartprix.com/rx-iT6xOrv0t-w420-h420/google-pixel-6-pro-1.webp" alt="Google Pixel 6" class="hp-img" /></td>
              <td>Google Pixel 6</td>
              <td>
                Layar: 6.4 inci AMOLED<br>
                Chipset: Google Tensor<br>
                Kamera: 50 + 12 MP<br>
                Baterai: 4614 mAh<br>
                OS: Android 12
              </td>
            </tr> -->
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

    <script>
    document.getElementById('searchForm').addEventListener('submit', function (e) {
      e.preventDefault();
      // Here you can add your search logic or API call
      const formData = new FormData(this);
      const filters = Object.fromEntries(formData.entries());

      // Example: just log the filter criteria
      console.log('Filter Criteria:', filters);

      alert('Fungsi pencarian belum diimplementasikan. Lihat konsol untuk data input.');
    });
  </script>
</body>
</html>

