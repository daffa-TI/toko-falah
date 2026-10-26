<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    try {
        if ($aksi === 'tambah') {
            $sql = "INSERT INTO `user` (username, password, idrole) VALUES (?, SHA2(?, 256), ?)";
            $pdo->prepare($sql)->execute([trim($_POST['username']), $_POST['password'], $_POST['idrole']]);
            pesan('success', 'User berhasil ditambahkan.');
        } elseif ($aksi === 'ubah') {
            if ($_POST['password'] !== '') {
                $sql = "UPDATE `user` SET username = ?, password = SHA2(?, 256), idrole = ? WHERE iduser = ?";
                $pdo->prepare($sql)->execute([trim($_POST['username']), $_POST['password'], $_POST['idrole'], $_POST['id']]);
            } else {
                // password dikosongkan = password lama tidak diganti
                $sql = "UPDATE `user` SET username = ?, idrole = ? WHERE iduser = ?";
                $pdo->prepare($sql)->execute([trim($_POST['username']), $_POST['idrole'], $_POST['id']]);
            }
            pesan('success', 'User berhasil diubah.');
        } elseif ($aksi === 'hapus') {
            $sql = "DELETE FROM `user` WHERE iduser = ?";
            $pdo->prepare($sql)->execute([$_POST['id']]);
            pesan('success', 'User berhasil dihapus.');
        }
    } catch (PDOException $e) {
        pesan('danger', $e->getCode() == 23000
            ? 'Gagal: data tidak valid atau masih dipakai di tabel lain.'
            : 'Error: ' . $e->getMessage());
    }
    header('Location: user.php');
    exit;
}

$data = $pdo->query("SELECT u.iduser, u.username, u.idrole, r.nama_role
                    FROM `user` u
                    JOIN role r ON r.idrole = u.idrole
                    ORDER BY u.iduser")->fetchAll();
$daftar_role = $pdo->query("SELECT idrole, nama_role FROM role ORDER BY nama_role")->fetchAll();

$judul = 'User';
include 'header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
<h4 class="mb-0"><i class="bi bi-people"></i> Data User</h4>
<button class="btn btn-hijau" data-bs-toggle="modal" data-bs-target="#modalForm"><i class="bi bi-plus-lg"></i> Tambah</button>
</div>

<div class="card"><div class="table-responsive">
<table class="table table-hover align-middle mb-0">
<thead class="table-light"><tr><th>#</th><th>Username</th><th>Role</th><th class="text-end">Aksi</th></tr></thead>
<tbody>
<?php foreach ($data as $r): ?>
    <tr>
    <td><?= e($r['iduser']) ?></td>
    <td><?= e($r['username']) ?></td>
    <td><span class="badge bg-info text-dark"><?= e($r['nama_role']) ?></span></td>
    <td class="text-end">
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalForm"
        data-id="<?= e($r['iduser']) ?>" data-username="<?= e($r['username']) ?>" data-idrole="<?= e($r['idrole']) ?>">
        <i class="bi bi-pencil-square"></i></button>
        <form method="post" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
        <input type="hidden" name="aksi" value="hapus">
        <input type="hidden" name="id" value="<?= e($r['iduser']) ?>">
        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
        </form>
        </td>
    </tr>
        <?php endforeach; ?>
        <?php if (!$data): ?><tr><td colspan="4" class="text-center text-muted py-4">Belum ada data.</td></tr><?php endif; ?>
    </tbody>
</table>
</div></div>

<div class="modal fade" id="modalForm" data-judul="User" tabindex="-1">
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
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" maxlength="45" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control">
        <div class="form-text">Saat mengubah data, kosongkan jika password tidak ingin diganti.</div>
    </div>
    <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="idrole" class="form-select" required>
        <?php foreach ($daftar_role as $r): ?><option value="<?= e($r['idrole']) ?>"><?= e($r['nama_role']) ?></option><?php endforeach; ?>
        </select>
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