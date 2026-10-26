<?php
session_start();

$host = 'localhost';
$db   = 'toko_Falah';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Koneksi gagal: ' . $e->getMessage());
}

// mengamankan teks sebelum ditampilkan di HTML
function e($teks) {
    return htmlspecialchars((string)$teks, ENT_QUOTES, 'UTF-8');
}

// menyimpan pesan yang ditampilkan satu kali setelah halaman dimuat ulang
function pesan($jenis, $isi) {
    $_SESSION['pesan'] = [$jenis, $isi];
}

// halaman yang boleh dibuka tanpa login
$halaman_publik = ['login.php', 'register.php'];
$sudah_login = !empty($_SESSION['iduser']);
if (!$sudah_login && !in_array(basename($_SERVER['PHP_SELF']), $halaman_publik, true)) {
    header('Location: login.php');
    exit;
}
