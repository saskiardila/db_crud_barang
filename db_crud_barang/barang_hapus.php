
<?php
require_once 'functions.php';
wajibLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: barang.php');
    exit;
}

periksaCSRF();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    $_SESSION['pesan'] = 'ID barang tidak valid.';
} else {
    $stmt = $pdo->prepare('DELETE FROM barang WHERE id = ?');
    $stmt->execute([$id]);

    $_SESSION['pesan'] = $stmt->rowCount()
        ? 'Barang berhasil dihapus.'
        : 'Barang tidak ditemukan.';
}

header('Location: barang.php');
exit;
