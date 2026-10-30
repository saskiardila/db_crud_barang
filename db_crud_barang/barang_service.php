<?php
declare(strict_types=1);

function semua_barang(PDO $pdo, string $cari = ''): array
{
    if ($cari !== '') {
        $stmt = $pdo->prepare(
            'SELECT * FROM barang
             WHERE kode LIKE :cari
                OR nama LIKE :cari
                OR kategori LIKE :cari
             ORDER BY id DESC'
        );

        $stmt->execute([
            'cari' => '%' . $cari . '%'
        ]);

        return $stmt->fetchAll();
    }

    return $pdo
        ->query('SELECT * FROM barang ORDER BY id DESC')
        ->fetchAll();
}

function ambil_barang(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare(
        'SELECT * FROM barang WHERE id = ?'
    );

    $stmt->execute([$id]);
    $row = $stmt->fetch();

    return $row ?: null;
}

function simpan_barang(
    PDO $pdo,
    array $data,
    ?int $id = null
): void {
    if ($id === null) {
        $sql = 'INSERT INTO barang
                (kode, nama, kategori, harga, stok)
                VALUES
                (:kode, :nama, :kategori, :harga, :stok)';
    } else {
        $sql = 'UPDATE barang SET
                kode = :kode,
                nama = :nama,
                kategori = :kategori,
                harga = :harga,
                stok = :stok
                WHERE id = :id';
    }

    $stmt = $pdo->prepare($sql);

    if ($id !== null) {
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    }

    $stmt->bindValue(':kode', $data['kode']);
    $stmt->bindValue(':nama', $data['nama']);
    $stmt->bindValue(':kategori', $data['kategori']);
    $stmt->bindValue(':harga', (float) $data['harga']);
    $stmt->bindValue(':stok', (int) $data['stok'], PDO::PARAM_INT);

    $stmt->execute();
}

function hapus_barang(PDO $pdo, int $id): bool
{
    $stmt = $pdo->prepare(
        'DELETE FROM barang WHERE id = ?'
    );

    $stmt->execute([$id]);

    return $stmt->rowCount() > 0;
}

function ringkasan_barang(PDO $pdo): array
{
    $sql = 'SELECT
                COUNT(*) AS jumlah_barang,
                COALESCE(SUM(stok), 0) AS total_stok,
                COALESCE(SUM(harga * stok), 0) AS total_nilai
            FROM barang';

    $row = $pdo->query($sql)->fetch();

    return $row ?: [
        'jumlah_barang' => 0,
        'total_stok' => 0,
        'total_nilai' => 0
    ];
}