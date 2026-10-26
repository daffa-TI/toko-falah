<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    try {
        if ($aksi === 'tambah') {
            $sql = "INSERT INTO satuan (nama_satuan, status) VALUES (?, ?)";
            $pdo->prepare($sql)->execute([trim($_POST['nama_satuan']), $_POST['status']]);
            pesan('success', 'Satuan berhasil ditambahkan.');
        } elseif ($aksi === 'ubah') {
            $sql = "UPDATE satuan SET nama_satuan = ?, status = ? WHERE idsatuan = ?";
            $pdo->prepare($sql)->execute([trim($_POST['nama_satuan']), $_POST['status'], $_POST['id']]);
            pesan('success', 'Satuan berhasil diubah.');
        } elseif ($aksi === 'hapus') {
            $sql = "DELETE FROM satuan WHERE idsatuan = ?";
            $pdo->prepare($sql)->execute([$_POST['id']]);
            pesan('success', 'Satuan berhasil dihapus.');
        }
    } catch (PDOException $e) {
        pesan('danger', $e->getCode() == 23000
            ? 'Gagal: data tidak valid atau masih dipakai di tabel lain.'
            : 'Error: ' . $e->getMessage());
    }
    header('Location: satuan.php');
    exit;
}

$data = $pdo->query("SELECT * FROM satuan ORDER BY idsatuan")->fetchAll();
$judul = 'Satuan';
include 'header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-rulers"></i> Data Satuan</h4>
    <button class="btn btn-hijau" data-bs-toggle="modal" data-bs-target="#modalForm"><i class="bi bi-plus-lg"></i> Tambah</button>
</div>

<div class="card"><div class="table-responsive">
<table class="table table-hover align-middle mb-0">
<thead class="table-light"><tr><th>#</th><th>Nama Satuan</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
<tbody>
<?php foreach ($data as $r): ?>
    <tr>
    <td><?= e($r['idsatuan']) ?></td>
    <td><?= e($r['nama_satuan']) ?></td>
    <td><?= $r['status'] == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' ?></td>
    <td class="text-end">
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalForm"
        data-id="<?= e($r['idsatuan']) ?>" data-nama_satuan="<?= e($r['nama_satuan']) ?>" data-status="<?= e($r['status']) ?>">
        <i class="bi bi-pencil-square"></i></button>
    <form method="post" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
        <input type="hidden" name="aksi" value="hapus">
        <input type="hidden" name="id" value="<?= e($r['idsatuan']) ?>">
        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
<?php if (!$data): ?><tr><td colspan="4" class="text-center text-muted py-4">Belum ada data.</td></tr><?php endif; ?>
</tbody>
</table>
</div></div>

<div class="modal fade" id="modalForm" data-judul="Satuan" tabindex="-1">
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
        <label class="form-label">Nama Satuan</label>
        <input type="text" name="nama_satuan" class="form-control" maxlength="45" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select"><option value="1">Aktif</option><option value="0">Nonaktif</option></select>
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