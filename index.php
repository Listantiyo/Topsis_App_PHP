<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'models/alternatif.php';
require_once 'models/nilai.php';
require_once 'models/kriteria.php';

require_once 'functions/mapping.php';
// require_once 'proses/hitung.php';

session_start();
if (!isset($_SESSION['login'])) {
    include 'auth/login.php';
    exit;
}

$role = $_SESSION['role'];
$page = $_GET['page'] ?? 'index';

if ($page == 'logout') {
    header("Location: auth/logout.php");
    exit;
}

if ($role == 'admin') {
    $allowed = ['index', 'data-hp', 'kelola-kriteria', 'hasil-perhitungan', 'evaluasi'];
    if (in_array($page, $allowed)) {
        $data = array();
        if($page == 'data-hp'){
            $modelAlt = new Alternatif();
            $data = mapDataHpToGroup($modelAlt->getAllAlternatif());
            // debug($data);
        }
        if($page == 'kelola-kriteria'){
            $modelKriteria = new Kriteria();
            $data = $modelKriteria->getAllKriteria();
            // debug($data);
        }
        if($page == 'hasil-perhitungan'){
            $modelAlt = new Alternatif();
            $data = $modelAlt->getRangkings();
        }
        include "views/admin/$page.php";
    } else {
        echo "404 Page Not Found";
    }
} else {
    $allowed = ['index', 'rekomendasi'];
    if (in_array($page, $allowed)) {
        include "views/user/$page.php";
    } else {
        echo "404 Page Not Found";
    }
}