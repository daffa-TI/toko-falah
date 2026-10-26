<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    try {
        if ($aksi === 'tambah') {
            $sql = "INSERT INTO vendor (nama_vendor, badan_hukum, status) VALUES (?, ?, ?)";
            $pdo->prepare($sql)->execute([trim($_POST['nama_vendor']), $_POST['badan_hukum'], $_POST['status']]);
            pesan('success', 'Vendor berhasil ditambahkan.');
        } elseif ($aksi === 'ubah') {
            $sql = "UPDATE vendor SET nama_vendor = ?, badan_hukum = ?, status = ? WHERE idvendor = ?";
            $pdo->prepare($sql)->execute([trim($_POST['nama_vendor']), $_POST['badan_hukum'], $_POST['status'], $_POST['id']]);
            pesan('success', 'Vendor berhasil diubah.');
        } elseif ($aksi === 'hapus') {
            $sql = "DELETE FROM vendor WHERE idvendor = ?";
            $pdo->prepare($sql)->execute([$_POST['id']]);
            pesan('success', 'Vendor berhasil dihapus.');
        }
    } catch (PDOException $e) {
        pesan('danger', $e->getCode() == 23000
            ? 'Gagal: data tidak valid atau masih dipakai di tabel lain.'
            : 'Error: ' . $e->getMessage());
    }
    header('Location: vendor.php');
    exit;
}

$data = $pdo->query("SELECT * FROM vendor ORDER BY idvendor")->fetchAll();
$judul = 'Vendor';
include 'header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
<h4 class="mb-0"><i class="bi bi-truck"></i> Data Vendor</h4>
<button class="btn btn-hijau" data-bs-toggle="modal" data-bs-target="#modalForm"><i class="bi bi-plus-lg"></i> Tambah</button>
</div>

<div class="card"><div class="table-responsive">
<table class="table table-hover align-middle mb-0">
<thead class="table-light"><tr><th>#</th><th>Nama Vendor</th><th>Badan Hukum</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
<tbody>
<?php foreach ($data as $r): ?>
<tr>
    <td><?= e($r['idvendor']) ?></td>
    <td><?= e($r['nama_vendor']) ?></td>
    <td><?= $r['badan_hukum'] == 'Y' ? 'Ya' : 'Tidak' ?></td>
    <td><?= $r['status'] == 'A' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' ?></td>
    <td class="text-end">
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalForm"
        data-id="<?= e($r['idvendor']) ?>" data-nama_vendor="<?= e($r['nama_vendor']) ?>"
        data-badan_hukum="<?= e($r['badan_hukum']) ?>" data-status="<?= e($r['status']) ?>">
        <i class="bi bi-pencil-square"></i></button>
        <form method="post" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
        <input type="hidden" name="aksi" value="hapus">
        <input type="hidden" name="id" value="<?= e($r['idvendor']) ?>">
        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
<?php if (!$data): ?><tr><td colspan="5" class="text-center text-muted py-4">Belum ada data.</td></tr><?php endif; ?>
</tbody>
</table>
</div></div>

<div class="modal fade" id="modalForm" data-judul="Vendor" tabindex="-1">
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
        <label class="form-label">Nama Vendor</label>
        <input type="text" name="nama_vendor" class="form-control" maxlength="100" required>
    </div>
    <div class="row">
        <div class="col-6 mb-3">
        <label class="form-label">Badan Hukum</label>
        <select name="badan_hukum" class="form-select"><option value="Y">Ya</option><option value="T">Tidak</option></select>
        </div>
        <div class="col-6 mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select"><option value="A">Aktif</option><option value="N">Nonaktif</option></select>
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