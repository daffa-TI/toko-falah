<?php
require 'koneksi.php';

$_SESSION = [];
session_destroy();
session_start();
pesan('success', 'Anda sudah keluar.');
header('Location: login.php');
exit;
