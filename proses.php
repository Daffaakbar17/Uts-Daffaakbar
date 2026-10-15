<?php
require_once _DIR_ . '/includes/fungsi.php';
require_once _DIR_ . '/data/menu.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectKe();
}

$aksi = $_POST['aksi'] ?? '';
$keranjang = $_SESSION['keranjang'] ?? [];

if (!is_array($keranjang)) {
    $keranjang = [];
}

if ($aksi === 'tambah') {
    $kode = $_POST['kode'] ?? '';
    $jumlahInput = $_POST['jumlah'] ?? '';

    if (!is_string($kode) || !isset($menu[$kode])) {
        setFlash('Kode menu tidak valid.', 'error');
        redirectKe();
    }

    // Hanya menerima bilangan bulat dalam bentuk teks.
    if (
        !is_string($jumlahInput) ||
        !preg_match('/^\d+$/', $jumlahInput)
    ) {
        setFlash('Jumlah harus berupa bilangan bulat.', 'error');
        redirectKe();
    }

    $jumlah = (int) $jumlahInput;

    if ($jumlah < 1 || $jumlah > 10) {
        setFlash('Jumlah harus antara 1 sampai 10.', 'error');
        redirectKe();
    }

    $jumlahLama = $keranjang[$kode]['jumlah'] ?? 0;

    if ($jumlahLama + $jumlah > 10) {
        setFlash(
            'Jumlah menu tersebut maksimal 10. Keranjang tidak diubah.',
            'error'
        );
        redirectKe();
    }

    $keranjang[$kode] = [
        'nama' => $menu[$kode]['nama'],
        'harga' => $menu[$kode]['harga'],
        'jumlah' => $jumlahLama + $jumlah
    ];

    $_SESSION['keranjang'] = $keranjang;
    setFlash('Pesanan berhasil ditambahkan.');
    redirectKe('keranjang.php');
}

if ($aksi === 'hapus') {
    $kode = $_POST['kode'] ?? '';

    if (is_string($kode) && isset($keranjang[$kode])) {
        unset($keranjang[$kode]);
        $_SESSION['keranjang'] = $keranjang;
        setFlash('Item berhasil dihapus.');
    } else {
        setFlash('Item tidak ditemukan.', 'error');
    }

    redirectKe('keranjang.php');
}

if ($aksi === 'kosongkan') {
    $_SESSION['keranjang'] = [];
    setFlash('Keranjang berhasil dikosongkan.');
    redirectKe('keranjang.php');
}

setFlash('Aksi tidak dikenal.', 'error');
redirectKe();