<?php

require_once __DIR__ . '/../models/alternatif.php';
$modelAlt = new Alternatif();

$modelAlt->hapusHP($_POST['id']);
header("Location: ../index.php?page=data-hp&status=sukses");