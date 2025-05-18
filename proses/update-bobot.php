<?php
require_once '../config/database.php'; // koneksi ke DB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        // Ambil angka dari "C1", "C2", dst → jadi 1, 2, 3, 4
        $id = intval(substr($key, 1));
        $bobot = floatval($value);

        // Validasi nilai bobot (misal wajib 0.0 – 1.0)
        if ($bobot >= 0 && $bobot <= 10) {
            $stmt = $conn->prepare("UPDATE kriteria SET bobot = ? WHERE id = ?");
            $stmt->bind_param("di", $bobot, $id);
            $stmt->execute();
        }
    }

    header("Location: ../index.php?page=kelola-kriteria&status=success");
    exit;
} else {
    echo "Invalid request.";
}
