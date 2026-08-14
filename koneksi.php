<?php
// koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "ambas_sador";

$con = mysqli_connect($host, $user, $pass, $db_name);

// kalo gagal connect langsung stop aja
if (!$con) {
    echo "Gagal connect ke database: " . mysqli_connect_error();
    exit();
}
?>