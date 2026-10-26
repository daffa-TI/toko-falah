<?php
require 'koneksi.php';

$labelJenis = ['A' => 'Sirup', 'B' => 'Hampers/Paket'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    try {
        if ($aksi === 'tambah') {
            $sql = "INSERT INTO barang (jenis, nama, idsatuan, status, harga) VALUES (?, ?, ?, ?, ?)";
            $pdo->prepare($sql)->execute([$_POST['jenis'], trim($_POST['nama']), $_POST['idsatuan'], $_POST['status'], $_POST['harga']]);
            pesan('success', 'Barang berhasil ditambahkan.');
        } elseif ($aksi === 'ubah') {
            $sql = "UPDATE barang SET jenis = ?, nama = ?, idsatuan = ?, status = ?, harga = ? WHERE idbarang = ?";
            $pdo->prepare($sql)->execute([$_POST['jenis'], trim($_POST['nama']), $_POST['idsatuan'], $_POST['status'], $_POST['harga'], $_POST['id']]);
            pesan('success', 'Barang berhasil diubah.');
        } elseif ($aksi === 'hapus') {
            $sql = "DELETE FROM barang WHERE idbarang = ?";
            $pdo->prepare($sql)->execute([$_POST['id']]);
            pesan('success', 'Barang berhasil dihapus.');
        }
    } catch (PDOException $e) {
        pesan('danger', $e->getCode() == 23000
            ? 'Gagal: data tidak valid atau masih dipakai di tabel lain.'
            : 'Error: ' . $e->getMessage());
    }
    header('Location: barang.php');
    exit;
}

$cari = trim($_GET['q'] ?? '');
$st = $pdo->prepare("SELECT b.idbarang, b.jenis, b.nama, b.idsatuan, s.nama_satuan, b.status, b.harga
                    FROM barang b
                    JOIN satuan s ON s.idsatuan = b.idsatuan
                    WHERE b.nama LIKE ?
                    ORDER BY b.idbarang");
$st->execute(['%' . $cari . '%']);
$data = $st->fetchAll();
$daftar_satuan = $pdo->query("SELECT idsatuan, nama_satuan FROM satuan WHERE status = 1 ORDER BY nama_satuan")->fetchAll();

$judul = 'Barang';
include 'header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
<h4 class="mb-0"><i class="bi bi-box-seam"></i> Data Barang</h4>
<div class="d-flex gap-2">
    <form method="get" class="d-flex">
    <input type="text" name="q" value="<?= e($cari) ?>" class="form-control" placeholder="Cari nama barang...">
    <button class="btn btn-outline-success ms-1"><i class="bi bi-search"></i></button>
    </form>
    <button class="btn btn-hijau" data-bs-toggle="modal" data-bs-target="#modalForm"><i class="bi bi-plus-lg"></i> Tambah</button>
    </div>
</div>

<div class="card"><div class="table-responsive">
<table class="table table-hover align-middle mb-0">
<thead class="table-light"><tr><th>#</th><th>Nama</th><th>Jenis</th><th>Satuan</th><th class="text-end">Harga</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
<tbody>
<?php foreach ($data as $r): ?>
    <tr>
    <td><?= e($r['idbarang']) ?></td>
    <td><?= e($r['nama']) ?></td>
    <td><?= e($labelJenis[$r['jenis']] ?? $r['jenis']) ?></td>
    <td><?= e($r['nama_satuan']) ?></td>
    <td class="text-end">Rp <?= number_format($r['harga'], 0, ',', '.') ?></td>
    <td><?= $r['status'] == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' ?></td>
    <td class="text-end">
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalForm"
        data-id="<?= e($r['idbarang']) ?>" data-nama="<?= e($r['nama']) ?>" data-jenis="<?= e($r['jenis']) ?>"
        data-idsatuan="<?= e($r['idsatuan']) ?>" data-harga="<?= e($r['harga']) ?>" data-status="<?= e($r['status']) ?>">
        <i class="bi bi-pencil-square"></i></button>
        <form method="post" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
        <input type="hidden" name="aksi" value="hapus">
        <input type="hidden" name="id" value="<?= e($r['idbarang']) ?>">
        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
<?php if (!$data): ?><tr><td colspan="7" class="text-center text-muted py-4">Data tidak ditemukan.</td></tr><?php endif; ?>
</tbody>
</table>
</div></div>

<div class="modal fade" id="modalForm" data-judul="Barang" tabindex="-1">
<div class="modal-dialog">
<form method="post" class="modal-content">
<div class="modal-header">
    <h5 class="modal-title"></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <input type="hidden" name="aksi" value="tambah">
    <input type="hidden" name="id" value="">
    <div class="mb-3">
        <label class="form-label">Nama Barang</label>
        <input type="text" name="nama" class="form-control" maxlength="45" required>
    </div>
    <div class="row">
        <div class="col-6 mb-3">
        <label class="form-label">Jenis</label>
        <select name="jenis" class="form-select">
        <?php foreach ($labelJenis as $kode => $label): ?><option value="<?= $kode ?>"><?= $label ?></option><?php endforeach; ?>
        </select>
        </div>
        <div class="col-6 mb-3">
        <label class="form-label">Satuan</label>
        <select name="idsatuan" class="form-select" required>
        <?php foreach ($daftar_satuan as $s): ?><option value="<?= e($s['idsatuan']) ?>"><?= e($s['nama_satuan']) ?></option><?php endforeach; ?>
        </select>
        </div>
    </div>
    <div class="row">
        <div class="col-6 mb-3">
        <label class="form-label">Harga (Rp)</label>
        <input type="number" name="harga" class="form-control" min="0" required>
        </div>
            <div class="col-6 mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select"><option value="1">Aktif</option><option value="0">Nonaktif</option></select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
    <button type="submit" class="btn btn-hijau">Simpan</button>
    </div>
    </form>
</div>
</div>
<?php include 'footer.php'; ?>