<?php
require_once _DIR_ . '/includes/fungsi.php';

$keranjang = $_SESSION['keranjang'] ?? [];
$subtotal = hitungSubtotal($keranjang);
$diskon = hitungDiskon($subtotal);
$total = hitungTotal($subtotal);

$flash = ambilFlash();
$judul = 'Keranjang Belanja';

require _DIR_ . '/includes/header.php';
?>

<h2>Keranjang Pesanan</h2>

<?php if ($flash): ?>
    <div class="alert <?= e($flash['tipe']) ?>">
        <?= e($flash['pesan']) ?>
    </div>
<?php endif; ?>

<?php if (empty($keranjang)): ?>
    <div class="card">
        <p>Keranjang masih kosong.</p>
        <a class="button" href="index.php">Lihat Daftar Menu</a>
    </div>
<?php else: ?>
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($keranjang as $kode => $item): ?>
                    <tr>
                        <td><?= e($item['nama']) ?></td>
                        <td><?= rupiah($item['harga']) ?></td>
                        <td><?= (int) $item['jumlah'] ?></td>
                        <td>
                            <?= rupiah(
                                $item['harga'] * $item['jumlah']
                            ) ?>
                        </td>
                        <td>
                            <form action="proses.php" method="post">
                                <input
                                    type="hidden"
                                    name="aksi"
                                    value="hapus"
                                >
                                <input
                                    type="hidden"
                                    name="kode"
                                    value="<?= e($kode) ?>"
                                >
                                <button type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <section class="card">
        <p>Subtotal seluruh pesanan: <strong><?= rupiah($subtotal) ?></strong></p>
        <p>Diskon: <strong><?= rupiah($diskon) ?></strong></p>
        <h3>Total pembayaran: <?= rupiah($total) ?></h3>
        <p><small>Diskon 10% berlaku untuk subtotal minimal Rp50.000.</small></p>

        <form action="proses.php" method="post">
            <input type="hidden" name="aksi" value="kosongkan">
            <button type="submit">Kosongkan Keranjang</button>
        </form>

        <p><a class="button" href="index.php">Tambah Pesanan</a></p>
    </section>
<?php endif; ?>

<?php require _DIR_ . '/includes/footer.php'; ?>