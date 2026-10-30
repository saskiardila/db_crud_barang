
<?php
require_once 'functions.php';
wajibLogin();

$barang = $pdo->query(
    'SELECT *, (harga * stok) AS nilai
     FROM barang ORDER BY nama ASC'
)->fetchAll();

$totalStok = 0;
$totalNilai = 0;

foreach ($barang as $b) {
    $totalStok += (int)$b['stok'];
    $totalNilai += (float)$b['nilai'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Laporan Barang</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
@media print {
    .no-print { display: none !important; }
}
</style>
</head>
<body class="bg-light">
<div class="container py-4">
<div class="d-flex justify-content-between mb-3 no-print">
<a href="dashboard.php" class="btn btn-secondary">Kembali</a>
<button onclick="window.print()" class="btn btn-primary">Cetak / Simpan PDF</button>
</div>

<div class="card p-4">
<h2>Laporan Persediaan Barang</h2>
<p>Tanggal: <?= date('d-m-Y H:i') ?></p>

<div class="table-responsive">
<table class="table table-bordered">
<thead>
<tr>
<th>No.</th><th>Kode</th><th>Nama</th><th>Kategori</th>
<th>Harga</th><th>Stok</th><th>Nilai Stok</th>
</tr>
</thead>
<tbody>
<?php foreach ($barang as $i => $b): ?>
<tr>
<td><?= $i + 1 ?></td>
<td><?= e($b['kode']) ?></td>
<td><?= e($b['nama']) ?></td>
<td><?= e($b['kategori']) ?></td>
<td><?= e(rupiah($b['harga'])) ?></td>
<td><?= (int)$b['stok'] ?></td>
<td><?= e(rupiah($b['nilai'])) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
<tfoot>
<tr>
<th colspan="5">Total</th>
<th><?= $totalStok ?></th>
<th><?= e(rupiah($totalNilai)) ?></th>
</tr>
</tfoot>
</table>
</div>
</div>
</div>
</body>
</html>
