<?php
$judul = $judul ?? 'Pemesanan Kantin Kampus';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($judul) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="header">
    <h1>Pemesanan Kantin Kampus</h1>
    <nav>
        <a href="index.php">Daftar Menu</a>
        <a href="keranjang.php">
            Keranjang (<?= array_sum(
                array_column($_SESSION['keranjang'] ?? [], 'jumlah')
            ) ?>)
        </a>
    </nav>
</header>
<main class="container">