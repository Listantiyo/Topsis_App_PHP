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

    /* STYE CONTENT */

    .title {
      margin-top: 2rem;
      margin-bottom: 2rem;
      font-weight: 700;
      color: #0d6efd;
    }
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
      <a class="nav-link active" href="index.php?page=kelola-kriteria"><i class="bi bi-gear"></i> Kelola Kriteria</a>
      <a class="nav-link" href="index.php?page=hasil-perhitungan"><i class="bi bi-envelope"></i> Hasil Perhitungan</a>
      <!-- <a class="nav-link" href="index.php?page=evaluasi"><i class="bi bi-bar-chart"></i> Evaluasi</a> -->
      <a class="nav-link" href="index.php?page=logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>
  </nav>
  <main class="content">
    <h1>Proses Pembobotan Kriteria</h1>
    <p class="lead">
    </p>
    <hr />
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
      <h3 class="mb-0 title">Kriteria TOPSIS</h3>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editBobotModal">
        Edit Bobot
      </button>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle" id="topsisTable">
        <thead class="table-info">
          <tr>
            <th scope="col" class="col-center">Kode</th>
            <th scope="col">Nama Kriteria</th>
            <th scope="col" class="col-center">Jenis</th>
            <th scope="col" class="col-center">Bobot</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($data as $value):?>
          <tr data-kode="<?php echo "C" . $value['id']?>">
            <th scope="row" class="col-center"><?php echo "C" . $value['id'] ?></th>
            <td><?php echo $value['nama'] ?></td>
            <td class="col-center text-<?php echo $value['tipe'] == 'cost' ?'danger':'success' ?>"><?php echo ucfirst($value['tipe']) ?></td>
            <td class="col-center bobot-cell"><?php echo number_format($value['bobot'], 2) ?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal -->
  <div
    class="modal fade"
    id="editBobotModal"
    tabindex="-1"
    aria-labelledby="editBobotModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <form id="bobotForm" action="proses/update-bobot.php" method="POST" class="modal-content needs-validation" novalidate>
        <div class="modal-header">
          <h5 class="modal-title" id="editBobotModalLabel">Edit Bobot Kriteria</h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div id="inputFieldsContainer"></div>
          <div class="form-text mb-2">
            Masukkan nilai bobot antara 0 dan 10.
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            Batal
          </button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
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
    (function () {
      'use strict';

      const bobotForm = document.getElementById('bobotForm');
      const inputFieldsContainer = document.getElementById('inputFieldsContainer');
      const topsisTable = document.getElementById('topsisTable');
      const modalElement = document.getElementById('editBobotModal');
      const bootstrapModal = bootstrap.Modal.getOrCreateInstance(modalElement);

      // On modal show: populate input fields with current bobot values
      modalElement.addEventListener('show.bs.modal', () => {
        inputFieldsContainer.innerHTML = '';
        const rows = topsisTable.querySelectorAll('tbody tr');

        rows.forEach(row => {
          const kode = row.getAttribute('data-kode');
          const nama = row.cells[1].textContent;
          const bobot = row.querySelector('.bobot-cell').textContent.trim();

          const fieldGroup = document.createElement('div');
          fieldGroup.className = 'mb-3';

          const label = document.createElement('label');
          label.setAttribute('for', `bobot-${kode}`);
          label.className = 'form-label fw-semibold';
          label.textContent = `${kode} - ${nama}`;

          const input = document.createElement('input');
          input.type = 'number';
          input.step = '0.01';
          input.min = '0';
          input.max = '10';
          input.className = 'form-control';
          input.id = `bobot-${kode}`;
          input.name = kode;
          input.value = bobot;
          input.required = true;

          const invalidFeedback = document.createElement('div');
          invalidFeedback.className = 'invalid-feedback';
          invalidFeedback.textContent = 'Masukkan angka antara 0 dan 10';

          fieldGroup.appendChild(label);
          fieldGroup.appendChild(input);
          fieldGroup.appendChild(invalidFeedback);
          inputFieldsContainer.appendChild(fieldGroup);
        });
      });

      // Form validation and submit event
      bobotForm.addEventListener('submit', event => {
        // event.preventDefault();
        event.stopPropagation();

        if (!bobotForm.checkValidity()) {
          bobotForm.classList.add('was-validated');
          return;
        }

        // Validate each input value in range 0-1
        const inputs = bobotForm.querySelectorAll('input[type=number]');
        let valid = true;
        inputs.forEach(input => {
          const val = parseFloat(input.value);
          if (isNaN(val) || val < 0 || val > 10) {
            input.classList.add('is-invalid');
            valid = false;
          } else {
            input.classList.remove('is-invalid');
          }
        });
        if (!valid) return;

        // Update table with new bobot values
        inputs.forEach(input => {
          const kode = input.name;
          const newValue = parseFloat(input.value).toFixed(2);
          const row = topsisTable.querySelector(`tbody tr[data-kode="${kode}"]`);
          if (row) {
            row.querySelector('.bobot-cell').textContent = newValue;
          }
        });

        // Hide modal and reset validation
        bootstrapModal.hide();
        bobotForm.classList.remove('was-validated');
      });
    })();
  </script>
</body>
</html>

