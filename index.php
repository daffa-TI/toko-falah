<?php
require 'koneksi.php';

$kartu = [
    ['Barang', "SELECT COUNT(*) FROM barang",           'bi-box-seam',    'barang.php', 'success'],
    ['Satuan', "SELECT COUNT(*) FROM satuan",           'bi-rulers',      'satuan.php', 'primary'],
    ['Vendor', "SELECT COUNT(*) FROM vendor",           'bi-truck',       'vendor.php', 'warning'],
    ['Margin', "SELECT COUNT(*) FROM margin_penjualan", 'bi-percent',     'margin.php', 'danger'],
    ['User',   "SELECT COUNT(*) FROM `user`",           'bi-people',      'user.php',   'info'],
    ['Role',   "SELECT COUNT(*) FROM role",             'bi-shield-check','role.php',   'secondary'],
];
$terbaru = $pdo->query("SELECT b.nama, s.nama_satuan, b.harga
                        FROM barang b
                        JOIN satuan s ON s.idsatuan = b.idsatuan
                        ORDER BY b.idbarang DESC
                        LIMIT 5")->fetchAll();

$judul = 'Beranda';
include 'header.php';
?>
<h4 class="mb-1">Selamat datang di Omah Kulineran</h4>
<p class="text-muted">Kelola data master toko: barang, satuan, vendor, margin, user, dan role.</p>

<div class="row g-3 mb-4">
    <?php foreach ($kartu as $k): ?>
    <div class="col-6 col-md-4 col-lg-2">
    <a href="<?= $k[3] ?>" class="kartu-menu">
    <div class="card p-3 text-center">
    <div class="fs-2 text-<?= $k[4] ?>"><i class="bi <?= $k[2] ?>"></i></div>
    <div class="fs-3 fw-bold"><?= $pdo->query($k[1])->fetchColumn() ?></div>
    <div class="text-muted"><?= e($k[0]) ?></div>
   </div>
  </a>
 </div>
 <?php endforeach; ?>
</div>

<div class="card">
    <div class="card-header bg-white fw-semibold">Barang terbaru</div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>Nama</th><th>Satuan</th><th class="text-end">Harga</th></tr></thead>
    <tbody>
    <?php foreach ($terbaru as $b): ?>
    <tr>
        <td><?= e($b['nama']) ?></td>
        <td><?= e($b['nama_satuan']) ?></td>
        <td class="text-end">Rp <?= number_format($b['harga'], 0, ',', '.') ?></td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$terbaru): ?><tr><td colspan="3" class="text-center text-muted py-4">Belum ada data.</td></tr><?php endif; ?>
   </tbody>
  </table>
 </div>
</div>
<?php include 'footer.php'; ?>