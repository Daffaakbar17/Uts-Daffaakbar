<?php

require_once _DIR_ . '/data/includes/fungsi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($nilai): string
{
    return htmlspecialchars(
        (string) $nilai,
        ENT_QUOTES,
        'UTF-8'
    );
}

function rupiah(int $nilai): string
{
    return 'Rp' . number_format(
        $nilai,
        0,
        ',',
        '.'
    );
}

function hitungSubtotal(array $keranjang): int
{
    $subtotal = 0;

    foreach ($keranjang as $item) {
        $subtotal += $item['harga'] * $item['jumlah'];
    }

    return $subtotal;
}

function hitungDiskon(int $subtotal): int
{
    return $subtotal >= 50000
        ? (int) round($subtotal * 0.10)
        : 0;
}

function hitungTotal(int $subtotal): int
{
    return $subtotal - hitungDiskon($subtotal);
}

function setFlash(string $pesan, string $tipe = 'success'): void
{
    $_SESSION['flash'] = [
        'pesan' => $pesan,
        'tipe' => $tipe
    ];
}

function ambilFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    return $flash;
}

function redirectKe(string $halaman = 'index.php'): never
{
    header('Location: ' . $halaman);
    exit;
}