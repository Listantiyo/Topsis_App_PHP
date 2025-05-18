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

    .table thead th {
      vertical-align: middle;
      text-align: center;
    }
    .table tbody td,
    .table tbody th {
      vertical-align: middle;
    }
    .col-center {
      text-align: center;
    }
    .btn-action {
      min-width: 65px;
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
      <a class="nav-link" href="index.php?page=hasil-perhitungan"><i class="bi bi-envelope"></i> Hasil Perhitungan</a>
      <!-- <a class="nav-link active" href="index.php?page=evaluasi"><i class="bi bi-bar-chart"></i> Evaluasi</a> -->
      <a class="nav-link" href="index.php?page=logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>
  </nav>
  <main class="content">
    <h1>Proses Nilai Evaluasi</h1>
    <p class="lead">
    </p>
    <hr />
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
      <h1>Tabel Keputusan TOPSIS</h1>
      <button
        type="button"
        class="btn btn-primary"
        id="btnInput"
        data-bs-toggle="modal"
        data-bs-target="#inputDataModal"
      >
        Input
      </button>
    </div>
    <div class="table-responsive">
      <table
        class="table table-bordered table-striped align-middle"
        id="decisionTable"
      >
        <thead class="table-primary">
          <tr>
            <th scope="col" class="col-center">No</th>
            <th scope="col">Merk</th>
            <th scope="col" class="col-center">C1 (Harga)</th>
            <th scope="col" class="col-center">C2 (RAM)</th>
            <th scope="col" class="col-center">C3 (Internal Memori)</th>
            <th scope="col" class="col-center">C4 (Kamera)</th>
            <th scope="col" class="col-center">C5 (Baterai)</th>
            <th scope="col" class="col-center">C6 (Processor)</th>
            <th scope="col" class="col-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row" class="col-center">1</th>
            <td>Samsung Galaxy S23</td>
            <td class="col-center">15000000</td>
            <td class="col-center">8</td>
            <td class="col-center">128</td>
            <td class="col-center">50</td>
            <td class="col-center">3900</td>
            <td class="col-center">Snapdragon 8 Gen 2</td>
            <td class="col-center">
              <button class="btn btn-sm btn-warning btn-action edit-btn">Edit</button>
            </td>
          </tr>
          <tr>
            <th scope="row" class="col-center">2</th>
            <td>iPhone 14 Pro</td>
            <td class="col-center">20000000</td>
            <td class="col-center">6</td>
            <td class="col-center">256</td>
            <td class="col-center">48</td>
            <td class="col-center">3200</td>
            <td class="col-center">Apple A16 Bionic</td>
            <td class="col-center">
              <button class="btn btn-sm btn-warning btn-action edit-btn">Edit</button>
            </td>
          </tr>
          <tr>
            <th scope="row" class="col-center">3</th>
            <td>Xiaomi Redmi Note 12</td>
            <td class="col-center">3000000</td>
            <td class="col-center">4</td>
            <td class="col-center">64</td>
            <td class="col-center">48</td>
            <td class="col-center">5000</td>
            <td class="col-center">Snapdragon 4 Gen 1</td>
            <td class="col-center">
              <button class="btn btn-sm btn-warning btn-action edit-btn">Edit</button>
            </td>
          </tr>
          <tr>
            <th scope="row" class="col-center">4</th>
            <td>Realme GT 2 Pro</td>
            <td class="col-center">7500000</td>
            <td class="col-center">12</td>
            <td class="col-center">256</td>
            <td class="col-center">50</td>
            <td class="col-center">5000</td>
            <td class="col-center">Snapdragon 8 Gen 1</td>
            <td class="col-center">
              <button class="btn btn-sm btn-warning btn-action edit-btn">Edit</button>
            </td>
          </tr>
          <tr>
            <th scope="row" class="col-center">5</th>
            <td>Google Pixel 7</td>
            <td class="col-center">11000000</td>
            <td class="col-center">8</td>
            <td class="col-center">128</td>
            <td class="col-center">50</td>
            <td class="col-center">4355</td>
            <td class="col-center">Google Tensor G2</td>
            <td class="col-center">
              <button class="btn btn-sm btn-warning btn-action edit-btn">Edit</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal -->
  <div
    class="modal fade"
    id="inputDataModal"
    tabindex="-1"
    aria-labelledby="inputDataModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <form class="modal-content needs-validation" id="inputDataForm" novalidate>
        <div class="modal-header">
          <h5 class="modal-title" id="inputDataModalLabel">Input Data Keputusan TOPSIS</h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editRowIndex" value="-1" />
          <div class="row g-3">
            <div class="col-md-12">
              <label for="merkInput" class="form-label">Merk</label>
              <input
                type="text"
                class="form-control"
                id="merkInput"
                name="merkInput"
                required
              />
              <div class="invalid-feedback">Harap isi merk.</div>
            </div>
            <div class="col-md-6 col-lg-4">
              <label for="c1Input" class="form-label">C1 (Harga)</label>
              <input
                type="number"
                class="form-control"
                id="c1Input"
                name="c1Input"
                min="0"
                step="any"
                required
              />
              <div class="invalid-feedback">Harap isi harga yang valid.</div>
            </div>
            <div class="col-md-6 col-lg-4">
              <label for="c2Input" class="form-label">C2 (RAM)</label>
              <input
                type="number"
                class="form-control"
                id="c2Input"
                name="c2Input"
                min="0"
                step="any"
                required
              />
              <div class="invalid-feedback">Harap isi RAM yang valid.</div>
            </div>
            <div class="col-md-6 col-lg-4">
              <label for="c3Input" class="form-label">C3 (Internal Memori)</label>
              <input
                type="number"
                class="form-control"
                id="c3Input"
                name="c3Input"
                min="0"
                step="any"
                required
              />
              <div class="invalid-feedback">Harap isi internal memori yang valid.</div>
            </div>
            <div class="col-md-6 col-lg-4">
              <label for="c4Input" class="form-label">C4 (Kamera)</label>
              <input
                type="number"
                class="form-control"
                id="c4Input"
                name="c4Input"
                min="0"
                step="any"
                required
              />
              <div class="invalid-feedback">Harap isi kamera yang valid.</div>
            </div>
            <div class="col-md-6 col-lg-4">
              <label for="c5Input" class="form-label">C5 (Baterai)</label>
              <input
                type="number"
                class="form-control"
                id="c5Input"
                name="c5Input"
                min="0"
                step="any"
                required
              />
              <div class="invalid-feedback">Harap isi baterai yang valid.</div>
            </div>
            <div class="col-md-6 col-lg-4">
              <label for="c6Input" class="form-label">C6 (Processor)</label>
              <input
                type="text"
                class="form-control"
                id="c6Input"
                name="c6Input"
                required
              />
              <div class="invalid-feedback">Harap isi processor.</div>
            </div>
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


  <!-- Modal -->
  <div
    class="modal fade"
    id="editBobotModal"
    tabindex="-1"
    aria-labelledby="editBobotModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <form id="bobotForm" class="modal-content needs-validation" novalidate>
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
            Masukkan nilai bobot antara 0 dan 1.
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
    (() => {
      const decisionTable = document.getElementById('decisionTable');
      const inputDataModal = document.getElementById('inputDataModal');
      const inputDataForm = document.getElementById('inputDataForm');

      const editRowIndexInput = document.getElementById('editRowIndex');
      const merkInput = document.getElementById('merkInput');
      const c1Input = document.getElementById('c1Input');
      const c2Input = document.getElementById('c2Input');
      const c3Input = document.getElementById('c3Input');
      const c4Input = document.getElementById('c4Input');
      const c5Input = document.getElementById('c5Input');
      const c6Input = document.getElementById('c6Input');

      // Handle Input button (new entry)
      document.getElementById('btnInput').addEventListener('click', () => {
        editRowIndexInput.value = '-1'; // new entry
        inputDataForm.reset();
        inputDataForm.classList.remove('was-validated');
      });

      // Handle Edit button clicks on each row
      decisionTable.addEventListener('click', (e) => {
        if (e.target.classList.contains('edit-btn')) {
          const tr = e.target.closest('tr');
          const rowIndex = Array.from(decisionTable.tBodies[0].rows).indexOf(tr);

          editRowIndexInput.value = rowIndex;

          // Fill form inputs with data from the row
          merkInput.value = tr.cells[1].textContent.trim();
          c1Input.value = tr.cells[2].textContent.trim();
          c2Input.value = tr.cells[3].textContent.trim();
          c3Input.value = tr.cells[4].textContent.trim();
          c4Input.value = tr.cells[5].textContent.trim();
          c5Input.value = tr.cells[6].textContent.trim();
          c6Input.value = tr.cells[7].textContent.trim();

          // Show modal
          const modal = new bootstrap.Modal(inputDataModal);
          modal.show();
        }
      });

      // Handle form submission to add or edit row
      inputDataForm.addEventListener('submit', (e) => {
        e.preventDefault();
        e.stopPropagation();

        if (!inputDataForm.checkValidity()) {
          inputDataForm.classList.add('was-validated');
          return;
        }

        const merk = merkInput.value.trim();
        const c1 = c1Input.value.trim();
        const c2 = c2Input.value.trim();
        const c3 = c3Input.value.trim();
        const c4 = c4Input.value.trim();
        const c5 = c5Input.value.trim();
        const c6 = c6Input.value.trim();

        const editingIndex = Number(editRowIndexInput.value);
        const tbody = decisionTable.tBodies[0];

        if (editingIndex >= 0) {
          // Update existing row
          const row = tbody.rows[editingIndex];
          row.cells[1].textContent = merk;
          row.cells[2].textContent = c1;
          row.cells[3].textContent = c2;
          row.cells[4].textContent = c3;
          row.cells[5].textContent = c4;
          row.cells[6].textContent = c5;
          row.cells[7].textContent = c6;
        } else {
          // Add new row
          const newRow = tbody.insertRow();
          const rowNumber = tbody.rows.length;

          // No
          const th = document.createElement('th');
          th.scope = 'row';
          th.className = 'col-center';
          th.textContent = rowNumber;
          newRow.appendChild(th);

          // Merk
          const tdMerk = newRow.insertCell();
          tdMerk.textContent = merk;

          // C1–C6
          const tdC1 = newRow.insertCell();
          tdC1.className = 'col-center';
          tdC1.textContent = c1;

          const tdC2 = newRow.insertCell();
          tdC2.className = 'col-center';
          tdC2.textContent = c2;

          const tdC3 = newRow.insertCell();
          tdC3.className = 'col-center';
          tdC3.textContent = c3;

          const tdC4 = newRow.insertCell();
          tdC4.className = 'col-center';
          tdC4.textContent = c4;

          const tdC5 = newRow.insertCell();
          tdC5.className = 'col-center';
          tdC5.textContent = c5;

          const tdC6 = newRow.insertCell();
          tdC6.className = 'col-center';
          tdC6.textContent = c6;

          // Action button cell
          const tdAction = newRow.insertCell();
          tdAction.className = 'col-center';
          const editBtn = document.createElement('button');
          editBtn.type = 'button';
          editBtn.className = 'btn btn-sm btn-warning btn-action edit-btn';
          editBtn.textContent = 'Edit';
          tdAction.appendChild(editBtn);
        }

        // Hide modal
        const modal = bootstrap.Modal.getInstance(inputDataModal);
        modal.hide();

        // Reset form validation state
        inputDataForm.classList.remove('was-validated');
      });
    })();
  </script>
</body>
</html>

