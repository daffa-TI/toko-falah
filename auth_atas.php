<?php
// Kerangka tampilan bersama untuk halaman login & register.
// Variabel yang dipakai: $judul
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
    body {
        min-height:100vh; display:flex; align-items:center; justify-content:center; padding:24px;
        background:radial-gradient(circle at 20% 10%, #ffffff 0%, #eef5e8 45%, #e2eed9 100%);
        font-family:system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;
    }
    .auth-card {
        display:flex; width:100%; max-width:880px; min-height:500px; background:#fff;
        border-radius:20px; overflow:hidden; box-shadow:0 24px 60px rgba(15,74,36,.18);
    }
    .auth-kiri {
        flex:1; color:#fff; padding:32px 36px; display:flex; flex-direction:column; justify-content:space-between;
        background:linear-gradient(160deg, var(--hijau-tua) 0%, #2a7a1c 60%, var(--hijau-muda) 130%);
    }
    .auth-kanan { flex:1; padding:48px 40px; display:flex; flex-direction:column; justify-content:center; }
    .merek { display:flex; align-items:center; gap:12px; }
    .merek .ikon {
        width:42px; height:42px; border-radius:12px; background:var(--hijau-muda); color:var(--hijau-tua);
        display:flex; align-items:center; justify-content:center; font-size:1.4rem; overflow:hidden;
    }
    .merek .ikon img { width:100%; height:100%; object-fit:cover; }
    .merek strong { display:block; line-height:1.1; }
    .merek small { font-size:.72rem; opacity:.85; }
    .auth-kiri h2 { font-weight:700; font-size:1.7rem; line-height:1.25; }
    .auth-kiri p { font-size:.9rem; opacity:.9; margin:0; }
    .auth-kanan h1 { color:var(--hijau-tua); font-weight:800; font-size:1.9rem; }
    .auth-kanan .form-label { font-weight:600; font-size:.85rem; color:#4b5b50; margin-bottom:.3rem; }
    .auth-kanan .form-control { background:#f3f7f0; border:1px solid #dfe8d9; padding:.65rem .9rem; border-radius:10px; }
    .auth-kanan .form-control:focus { background:#f3f7f0; border-color:var(--hijau-muda); box-shadow:0 0 0 .2rem rgba(156,204,60,.35); }
    .btn-masuk {
        background:linear-gradient(90deg, var(--hijau-tua), var(--hijau)); color:#fff; border:0;
        padding:.7rem; border-radius:10px; font-weight:600;
    }
    .btn-masuk:hover { color:#fff; filter:brightness(1.1); }
    .tautan { color:var(--hijau-tua); }
    @media (max-width:767px) {
        .auth-card { flex-direction:column; min-height:0; }
        .auth-kiri { padding:24px; gap:16px; }
        .auth-kiri h2 { font-size:1.3rem; }
        .auth-kanan { padding:32px 24px; }
    }
</style>
</head>
<body>
<div class="auth-card">
    <div class="auth-kiri">
        <div class="merek">
            <div class="ikon"><img src="logo.png" alt="" onerror="this.replaceWith(Object.assign(document.createElement('i'),{className:'bi bi-cup-straw'}))"></div>
            <div><strong>Omah Kulineran</strong><small>Pengadaan &amp; Penjualan Sirup</small></div>
        </div>
        <h2>Kelola stok sirup dan hampers dari satu tempat.</h2>
        <p>Master data, vendor, dan margin penjualan tersaji sesuai peran Anda.</p>
    </div>
    <div class="auth-kanan">
