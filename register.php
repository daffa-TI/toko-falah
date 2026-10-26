<?php
require 'koneksi.php';

if ($sudah_login) { header('Location: index.php'); exit; }

$galat = '';
$username = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi'] ?? '';

    if ($username === '' || strlen($username) > 45) {
        $galat = 'Username wajib diisi (maksimal 45 karakter).';
    } elseif (strlen($password) < 6) {
        $galat = 'Password minimal 6 karakter.';
    } elseif ($password !== $konfirmasi) {
        $galat = 'Konfirmasi password tidak sama.';
    } else {
        $cek = $pdo->prepare("SELECT COUNT(*) FROM `user` WHERE username = ?");
        $cek->execute([$username]);
        $role = $pdo->query("SELECT idrole FROM role WHERE LOWER(nama_role) = 'kasir' LIMIT 1")->fetchColumn();

        if ($cek->fetchColumn() > 0) {
            $galat = 'Username sudah dipakai.';
        } elseif (!$role) {
            $galat = 'Role Kasir belum tersedia di database.';
        } else {
            $pdo->prepare("INSERT INTO `user` (username, password, idrole) VALUES (?, SHA2(?, 256), ?)")
                ->execute([$username, $password, $role]);
            pesan('success', 'Akun berhasil dibuat. Silakan masuk.');
            header('Location: login.php');
            exit;
        }
    }
}

$judul = 'Daftar';
include 'auth_atas.php';
?>
        <h1 class="mb-1">Daftar pengguna</h1>
        <p class="text-muted mb-3">Buat akun baru dengan role Kasir.</p>

        <?php if ($galat): ?><div class="alert alert-danger py-2"><?= e($galat) ?></div><?php endif; ?>

        <form method="post" autocomplete="off">
            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="mis. userbaru"
                       value="<?= e($username) ?>" maxlength="45" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 6 karakter" minlength="6" required>
            </div>
            <div class="mb-4">
                <label class="form-label" for="konfirmasi">Konfirmasi password</label>
                <input type="password" id="konfirmasi" name="konfirmasi" class="form-control" placeholder="Masukkan ulang password" required>
            </div>
            <button type="submit" class="btn btn-masuk w-100">Buat akun</button>
        </form>
        <a href="login.php" class="tautan d-inline-block mt-4">Kembali ke halaman masuk</a>
<?php include 'auth_bawah.php'; ?>
