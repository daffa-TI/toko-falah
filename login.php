<?php
require 'koneksi.php';

if ($sudah_login) { header('Location: index.php'); exit; }

$galat = '';
$username = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // password di database disimpan dengan SHA2(..., 256), sama dengan hash('sha256') di PHP
    $stmt = $pdo->prepare("SELECT u.iduser, u.username, u.password, r.nama_role
                           FROM `user` u JOIN role r ON r.idrole = u.idrole
                           WHERE u.username = ?");
    $stmt->execute([$username]);
    $u = $stmt->fetch();

    if ($u && hash_equals($u['password'], hash('sha256', $password))) {
        session_regenerate_id(true);
        $_SESSION['iduser']    = $u['iduser'];
        $_SESSION['username']  = $u['username'];
        $_SESSION['nama_role'] = $u['nama_role'];
        header('Location: index.php');
        exit;
    }
    $galat = 'Username atau password salah.';
}

$judul = 'Masuk';
include 'auth_atas.php';
?>
        <h1 class="mb-1">Masuk</h1>
        <p class="text-muted mb-3">Gunakan akun yang diberikan administrator.</p>

        <?php if (!empty($_SESSION['pesan'])): [$jenis, $isi] = $_SESSION['pesan']; unset($_SESSION['pesan']); ?>
        <div class="alert alert-<?= e($jenis) ?> py-2"><?= e($isi) ?></div>
        <?php endif; ?>
        <?php if ($galat): ?><div class="alert alert-danger py-2"><?= e($galat) ?></div><?php endif; ?>

        <form method="post" autocomplete="on">
            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="mis. admin1"
                       value="<?= e($username) ?>" maxlength="45" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-masuk w-100">Masuk</button>
        </form>
        <a href="register.php" class="tautan d-inline-block mt-4">Belum punya akun? Daftar sekarang</a>
<?php include 'auth_bawah.php'; ?>
