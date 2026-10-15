<?php
require_once __DIR__ . '/includes/fungsi.php';
require_once __DIR__ . '/data/menu.php';

$kategoriTersedia = ['Makanan', 'Minuman', 'Camilan'];
$kategoriGET = $_GET['kategori'] ?? null;

if (
    is_string($kategoriGET) &&
    in_array($kategoriGET, $kategoriTersedia, true)
) {
    $kategoriAktif = $kategoriGET;

    setcookie(
        'kategori_favorit',
        $kategoriAktif,
        time() + (7 * 24 * 60 * 60),
        '/'
    );
} else {
    $cookie = $_COOKIE['kategori_favorit'] ?? '';

    $kategoriAktif = in_array(
        $cookie,
        $kategoriTersedia,
        true
    ) ? $cookie : '';
}

$cari = $_GET['cari'] ?? '';
if (!is_string($cari)) {
    $cari = '';
}

$hasilMenu = [];

foreach ($menu as $kode => $item) {
    $cocokCari = $cari === '' ||
        stripos($item['nama'], $cari) !== false;

    $cocokKategori = $kategoriAktif === '' ||
        $item['kategori'] === $kategoriAktif;

    if ($cocokCari && $cocokKategori) {
        $hasilMenu[$kode] = $item;
    }
}

$flash = ambilFlash();
$judul = 'Daftar Menu';
require __DIR__ . '/includes/header.php';
?>

<h2>Daftar Menu Kantin</h2>

<?php if ($flash): ?>
    <div class="alert <?= e($flash['tipe']) ?>">
        <?= e($flash['pesan']) ?>
    </div>
<?php endif; ?>

<section class="card">
    <form method="get" action="index.php">
        <label for="cari">Cari nama menu:</label>
        <input
            id="cari"
            type="text"
            name="cari"
            value="<?= e($cari) ?>"
            placeholder="Contoh: Nasi Goreng"
        >

        <label for="kategori">Kategori favorit:</label>
        <select id="kategori" name="kategori">
            <option value="">Semua kategori</option>
            <?php foreach ($kategoriTersedia as $kategori): ?>
                <option
                    value="<?= e($kategori) ?>"
                    <?= $kategoriAktif === $kategori ? 'selected' : '' ?>
                >
                    <?= e($kategori) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Cari / Filter</button>
        <a class="button" href="index.php">Reset</a>
    </form>
</section>

<?php if (!$hasilMenu): ?>
    <div class="alert error">
        Menu tidak ditemukan. Coba kata kunci atau kategori lain.
    </div>
<?php else: ?>
    <div class="grid">
        <?php foreach ($hasilMenu as $kode => $item): ?>
            <article class="card">
                <h3><?= e($item['nama']) ?></h3>
                <p>Kode: <?= e($kode) ?></p>
                <p>Kategori: <?= e($item['kategori']) ?></p>
                <p>Harga: <?= rupiah($item['harga']) ?></p>

                <form action="proses.php" method="post">
                    <input type="hidden" name="aksi" value="tambah">
                    <input
                        type="hidden"
                        name="kode"
                        value="<?= e($kode) ?>"
                    >
                    <label>
                        Jumlah (1–10):
                        <input
                            type="number"
                            name="jumlah"
                            min="1"
                            max="10"
                            step="1"
                            required
                        >
                    </label>
                    <button type="submit">Tambah ke Keranjang</button>
                </form>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require _DIR_ . '/includes/footer.php'; ?>