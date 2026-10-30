
<?php
require_once __DIR__ . '/config.php';

function e($value): string {
    return htmlspecialchars(
        (string)($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

function rupiah($angka): string {
    return 'Rp ' . number_format(
        (float)$angka,
        0,
        ',',
        '.'
    );
}

function wajibLogin(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function tokenCSRF(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] =
            bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function periksaCSRF(): void {
    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token) ||
        !hash_equals(
            $_SESSION['csrf_token'] ?? '',
            $token
        )
    ) {
        exit('Permintaan tidak valid. Muat ulang halaman.');
    }
}
