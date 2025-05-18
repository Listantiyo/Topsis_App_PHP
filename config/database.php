<?php

// Inisialisasi parameter mysqli
$host = "localhost";
$username = "root";
$password = "";
$database = "topsis_app";

// Buat koneksi ke mysqli
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
$connErr = $conn->connect_error;
if($connErr){
    if(php_sapi_name() === 'cli'){
        fwrite(STDERR, "Connection failed :" . $connErr . PHP_EOL);
        exit(0);
    }else{
        echo "<pre style='color:red;font-weight:bold'>$connErr<pre>";
        die;
    }
}

// var_dump("Connected successfully to MySQL database $database" . PHP_EOL);

return $conn;