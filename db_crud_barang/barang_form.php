
<?php
require_once 'functions.php';
wajibLogin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$edit = $id !== null && $id !== false && $id > 0;

$data = [
    'kode' => '',
    'nama' => '',
    'kategori' => '',
    'harga' => '',
    'stok' => ''
];

$error = '';

if ($edit) {
    $stmt = $pdo->prepare('SELECT * FROM barang WHERE id = ?');
    $stmt->execute([$id]);
    $found = $stmt->fetch();

    if (!$found) {
        exit('Barang tidak ditemukan.');
    }

    $data = $found;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    periksaCSRF();

    $kode = strtoupper(trim($_POST['kode'] ?? ''));
    $nama = trim($_POST['nama'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $harga = trim($_POST['harga'] ?? '');
    $stok = trim($_POST['stok'] ?? '');

    $data = compact('kode', 'nama', 'kategori', 'harga', 'stok');

    if (
        $kode === '' || $nama === '' || $kategori === '' ||
        !is_numeric($harga) || (float)$harga < 0 ||
        filter_var($stok, FILTER_VALIDATE_INT) === false ||
        (int)$stok < 0
    ) {
        $error = 'Periksa kembali data yang kamu masukkan.';
    } else {
        try {
            if ($edit) {
                $stmt = $pdo->prepare(
                    'UPDATE barang
                     SET kode=?, nama=?, kategori=?, harga=?, stok=?
                     WHERE id=?'
                );
                $stmt->execute([
                    $kode, $nama, $kategori, $harga, $stok, $id
                ]);
                $_SESSION['pesan'] = 'Barang berhasil diperbarui.';
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO barang (kode,nama,kategori,harga,stok)
                     VALUES (?,?,?,?,?)'
                );
                $stmt->execute([
                    $kode, $nama, $kategori, $harga, $stok
                ]);
                $_SESSION['pesan'] = 'Barang berhasil ditambahkan.';
            }

            header('Location: barang.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Kode barang mungkin sudah digunakan.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Form Barang</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:650px">
<div class="card p-4 shadow-sm">
<h3><?= $edit ? 'Edit Barang' : 'Tambah Barang' ?></h3>

<?php if ($error !== ''): ?>
<div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<form method="post">
<input type="hidden" name="csrf_token"
value="<?= e(tokenCSRF()) ?>">

<label class="form-label">Kode barang</label>
<input class="form-control mb-3" name="kode"
maxlength="30" value="<?= e($data['kode']) ?>" required>

<label class="form-label">Nama barang</label>
<input class="form-control mb-3" name="nama"
maxlength="120" value="<?= e($data['nama']) ?>" required>

<label class="form-label">Kategori</label>
<input class="form-control mb-3" name="kategori"
maxlength="80" value="<?= e($data['kategori']) ?>" required>

<label class="form-label">Harga</label>
<input class="form-control mb-3" type="number"
name="harga" min="0" step="0.01"
value="<?= e($data['harga']) ?>" required>

<label class="form-label">Stok</label>
<input class="form-control mb-3" type="number"
name="stok" min="0" step="1"
value="<?= e($data['stok']) ?>" required>

<button class="btn btn-primary">Simpan</button>
<a href="barang.php" class="btn btn-secondary">Batal</a>
</form>
</div>
</div>
</body>
</html>
