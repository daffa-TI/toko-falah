<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    try {
        if ($aksi === 'tambah') {
            $sql = "INSERT INTO margin_penjualan (persen, status, iduser) VALUES (?, ?, ?)";
            $pdo->prepare($sql)->execute([$_POST['persen'], $_POST['status'], $_POST['iduser']]);
            pesan('success', 'Margin berhasil ditambahkan.');
        } elseif ($aksi === 'ubah') {
            $sql = "UPDATE margin_penjualan SET persen = ?, status = ?, iduser = ? WHERE idmargin_penjualan = ?";
            $pdo->prepare($sql)->execute([$_POST['persen'], $_POST['status'], $_POST['iduser'], $_POST['id']]);
            pesan('success', 'Margin berhasil diubah.');
        } elseif ($aksi === 'hapus') {
            $sql = "DELETE FROM margin_penjualan WHERE idmargin_penjualan = ?";
            $pdo->prepare($sql)->execute([$_POST['id']]);
            pesan('success', 'Margin berhasil dihapus.');
        }
    } catch (PDOException $e) {
        pesan('danger', $e->getCode() == 23000
            ? 'Gagal: data tidak valid atau masih dipakai di tabel lain.'
            : 'Error: ' . $e->getMessage());
    }
    header('Location: margin.php');
    exit;
}

$data = $pdo->query("SELECT m.idmargin_penjualan, m.persen, m.status, m.iduser, u.username, m.created_at
                    FROM margin_penjualan m
                    JOIN `user` u ON u.iduser = m.iduser
                    ORDER BY m.idmargin_penjualan")->fetchAll();
$daftar_user = $pdo->query("SELECT iduser, username FROM `user` ORDER BY username")->fetchAll();

$judul = 'Margin';
include 'header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
<h4 class="mb-0"><i class="bi bi-percent"></i> Data Margin Penjualan</h4>
<button class="btn btn-hijau" data-bs-toggle="modal" data-bs-target="#modalForm"><i class="bi bi-plus-lg"></i> Tambah</button>
</div>

<div class="card"><div class="table-responsive">
<table class="table table-hover align-middle mb-0">
    <thead class="table-light"><tr><th>#</th><th>Persen</th><th>Status</th><th>Dibuat oleh</th><th>Dibuat pada</th><th class="text-end">Aksi</th></tr></thead>
    <tbody>
    <?php foreach ($data as $r): ?>
    <tr>
    <td><?= e($r['idmargin_penjualan']) ?></td>
    <td><?= e($r['persen']) ?>%</td>
    <td><?= $r['status'] == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' ?></td>
    <td><?= e($r['username']) ?></td>
    <td><?= e($r['created_at']) ?></td>
    <td class="text-end">
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalForm"
        data-id="<?= e($r['idmargin_penjualan']) ?>" data-persen="<?= e($r['persen']) ?>"
        data-status="<?= e($r['status']) ?>" data-iduser="<?= e($r['iduser']) ?>">
        <i class="bi bi-pencil-square"></i></button>
        <form method="post" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
        <input type="hidden" name="aksi" value="hapus">
        <input type="hidden" name="id" value="<?= e($r['idmargin_penjualan']) ?>">
        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
<?php if (!$data): ?><tr><td colspan="6" class="text-center text-muted py-4">Belum ada data.</td></tr><?php endif; ?>
</tbody>
</table>
</div></div>

<div class="modal fade" id="modalForm" data-judul="Margin" tabindex="-1">
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
    <label class="form-label">Persen Margin (%)</label>
    <input type="number" name="persen" class="form-control" step="0.1" min="0" required>
    </div>
    <div class="row">
    <div class="col-6 mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select"><option value="1">Aktif</option><option value="0">Nonaktif</option></select>
    </div>
    <div class="col-6 mb-3">
        <label class="form-label">Dibuat oleh</label>
        <select name="iduser" class="form-select" required>
        <?php foreach ($daftar_user as $u): ?><option value="<?= e($u['iduser']) ?>"><?= e($u['username']) ?></option><?php endforeach; ?>
        </select>
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