<?php
$menu = [
    ['index.php',  'Beranda', 'bi-house-door'],
    ['barang.php', 'Barang',  'bi-box-seam'],
    ['satuan.php', 'Satuan',  'bi-rulers'],
    ['vendor.php', 'Vendor',  'bi-truck'],
    ['margin.php', 'Margin',  'bi-percent'],
    ['user.php',   'User',    'bi-people'],
    ['role.php',   'Role',    'bi-shield-check'],
];
$halaman = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($judul) ?> - Omah Kulineran</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
    :root { --hijau-tua:#0f4a24; --hijau:#3a8f1f; --hijau-muda:#9ccc3c; }
    body { background:#f2f6ef; }
    h4 { color:var(--hijau-tua); font-weight:700; }
    .navbar { background:var(--hijau-tua); border-bottom:3px solid var(--hijau-muda); }
    .navbar .nav-link { color:#cfe3c4; }
    .navbar .nav-link:hover, .navbar .nav-link.active { color:#fff; font-weight:600; }
    .logo { width:46px; height:46px; object-fit:cover; border-radius:50%; background:#fff; }
    .btn-hijau { background:var(--hijau); border-color:var(--hijau); color:#fff; }
    .btn-hijau:hover { background:var(--hijau-tua); border-color:var(--hijau-tua); color:#fff; }
    .card { border:0; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(15,74,36,.10); }
    .card .table thead th { background:var(--hijau-tua); color:#fff; border-color:var(--hijau-tua); }
    .table th, .table td { padding:.75rem 1rem; }
    .kartu-menu { text-decoration:none; color:inherit; display:block; transition:transform .15s; }
    .kartu-menu:hover { transform:translateY(-3px); }
    th { white-space:nowrap; }
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark mb-4">
<div class="container">
<a class="navbar-brand d-flex align-items-center fw-bold" href="index.php">
<img src="logo.png" alt="Logo Omah Kulineran" class="logo me-2" onerror="this.style.display='none'">
</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuUtama">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="menuUtama">
    <ul class="navbar-nav ms-auto">
    <?php foreach ($menu as $m): ?>
    <li class="nav-item">
    <a class="nav-link <?= $halaman === $m[0] ? 'active' : '' ?>" href="<?= $m[0] ?>">
    <i class="bi <?= $m[2] ?>"></i> <?= $m[1] ?>
    </a>
    </li>
    <?php endforeach; ?>
    <li class="nav-item ms-lg-3 d-flex align-items-center">
    <span class="text-white-50 small me-2"><i class="bi bi-person-circle"></i> <?= e($_SESSION['username'] ?? '') ?> (<?= e($_SESSION['nama_role'] ?? '') ?>)</span>
    <a class="btn btn-sm btn-outline-light" href="logout.php"><i class="bi bi-box-arrow-right"></i> Keluar</a>
    </li>
    </ul>
    </div>
</div>
</nav>
<div class="container pb-5">
<?php if (!empty($_SESSION['pesan'])): [$jenis, $isi] = $_SESSION['pesan']; unset($_SESSION['pesan']); ?>
<div class="alert alert-<?= e($jenis) ?> alert-dismissible fade show">
<?= e($isi) ?>
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>