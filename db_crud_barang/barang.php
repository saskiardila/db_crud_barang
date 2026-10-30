
<?php
require_once 'functions.php';
wajibLogin();

$q = trim($_GET['q'] ?? '');

$sql = 'SELECT * FROM barang';
$params = [];

if ($q !== '') {
    $sql .= ' WHERE kode LIKE ? OR nama LIKE ? OR kategori LIKE ?';
    $term = '%' . $q . '%';
    $params = [$term, $term, $term];
}

$sql .= ' ORDER BY id DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$barang = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Data Barang</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

<div class="d-flex justify-content-between mb-3">
<h2>Data Barang</h2>
<a href="dashboard.php" class="btn btn-secondary">Kembali</a>
</div>

<?php if (!empty($_SESSION['pesan'])): ?>
<div class="alert alert-success">
<?= e($_SESSION['pesan']) ?>
</div>
<?php unset($_SESSION['pesan']); endif; ?>

<a href="barang_form.php" class="btn btn-primary mb-3">
Tambah Barang
</a>

<form method="get" class="d-flex gap-2 mb-3">
<input class="form-control" name="q"
value="<?= e($q) ?>" placeholder="Cari barang...">
<button class="btn btn-outline-primary">Cari</button>
<a class="btn btn-outline-secondary" href="barang.php">Reset</a>
</form>

<div class="card p-3">
<div class="table-responsive">
<table class="table table-striped align-middle">
<thead>
<tr>
<th>Kode</th><th>Nama</th><th>Kategori</th>
<th>Harga</th><th>Stok</th><th>Aksi</th>
</tr>
</thead>
<tbody>
<?php if (!$barang): ?>
<tr><td colspan="6">Belum ada data barang.</td></tr>
<?php endif; ?>

<?php foreach ($barang as $b): ?>
<tr>
<td><?= e($b['kode']) ?></td>
<td><?= e($b['nama']) ?></td>
<td><?= e($b['kategori']) ?></td>
<td><?= e(rupiah($b['harga'])) ?></td>
<td><?= (int)$b['stok'] ?></td>
<td>
<a class="btn btn-sm btn-warning"
href="barang_form.php?id=<?= (int)$b['id'] ?>">Edit</a>

<form action="barang_hapus.php" method="post"
class="d-inline"
onsubmit="return confirm('Hapus barang ini?')">
<input type="hidden" name="csrf_token"
value="<?= e(tokenCSRF()) ?>">
<input type="hidden" name="id"
value="<?= (int)$b['id'] ?>">
<button class="btn btn-sm btn-danger">Hapus</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>
</div>
</body>
</html>
