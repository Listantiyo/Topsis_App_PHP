<?php
session_start();
include '../config/database.php'; // koneksi DB

$username = $_POST['username'];
$password = $_POST['password'];

// Query cek user
$query = "SELECT * FROM users WHERE username = ? LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && md5($password) === $user['password']) {
    // Set session
    $_SESSION['login'] = true;
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    header("Location: ../index.php");
    exit;
} else {
    // Gagal login
    echo "<script>alert('Username atau password salah'); window.location.href='../auth/login.php';</script>";
}
