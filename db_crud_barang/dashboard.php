
<?php
require_once 'functions.php';
wajibLogin();

$totalBarang = (int)$pdo->query(
    'SELECT COUNT(*) FROM barang'
)->fetchColumn();

$totalStok = (int)$pdo->query(
    'SELECT COALESCE(SUM(stok), 0) FROM barang'
)->fetchColumn();

$totalNilai = (float)$pdo->query(
    'SELECT COALESCE(SUM(harga * stok), 0) FROM barang'
)->fetchColumn();

$barang = $pdo->query(
    'SELECT * FROM barang ORDER BY id DESC LIMIT 5'
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
<div class="container">
<a class="navbar-brand" href="dashboard.php">GudangKu</a>
<div>
<a class="btn btn-light btn-sm" href="barang.php">Data Barang</a>
<a class="btn btn-light btn-sm" href="laporan.php">Laporan</a>
<a class="btn btn-warning btn-sm" href="logout.php">Keluar</a>
</div>
</div>
</nav>

<div class="container py-4">
<h2>Dashboard</h2>
<p>Selamat datang, <?= e($_SESSION['user_nama']) ?>.</p>

<div class="row g-3 mb-4">
<div class="col-md-4">
<div class="card p-3">
Jumlah jenis barang
<h3><?= $totalBarang ?></h3>
</div>
</div>
<div class="col-md-4">
<div class="card p-3">
Total stok
<h3><?= $totalStok ?></h3>
</div>
</div>
<div class="col-md-4">
<div class="card p-3">
Nilai persediaan
<h3><?= e(rupiah($totalNilai)) ?></h3>
</div>
</div>
</div>

<div class="card p-3">
<h4>Barang Terbaru</h4>
<div class="table-responsive">
<table class="table table-striped">
<thead>
<tr>
<th>Kode</th><th>Nama</th><th>Kategori</th>
<th>Harga</th><th>Stok</th>
</tr>
</thead>
<tbody>
<?php foreach ($barang as $b): ?>
<tr>
<td><?= e($b['kode']) ?></td>
<td><?= e($b['nama']) ?></td>
<td><?= e($b['kategori']) ?></td>
<td><?= e(rupiah($b['harga'])) ?></td>
<td><?= (int)$b['stok'] ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<a href="barang.php" class="btn btn-primary">
Kelola Barang
</a>
</div>
</div>
</body>
</html>
