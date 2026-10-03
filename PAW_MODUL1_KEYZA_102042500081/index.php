<?php
session_start();
 
$dataAwal = [
    ["nama" => "Monitor 24 Inch",       "kategori" => "Monitor",   "harga" => 1800000, "stok" => 4],
    ["nama" => "lenovo legion pro 5 16irx9",   "kategori" => "Laptop",    "harga" => 8500000, "stok" => 3],
    ["nama" => "Mouse Wireless",        "kategori" => "Aksesoris", "harga" => 150000,  "stok" => 12],
    ["nama" => "Mechanical Keyboard",   "kategori" => "Aksesoris", "harga" => 650000,  "stok" => 0],
    ["nama" => "IEM Headset",        "kategori" => "Audio",     "harga" => 1000000, "stok" => 5],
    ["nama" => "Webcam 1080p",        "kategori" => "Kamera",    "harga" => 450000,  "stok" => 0],
    ["nama" => "Smartphone Android",    "kategori" => "Gadget",    "harga" => 3200000, "stok" => 7],
];
 
function formatRupiah($angka) {
    return "Rp" . number_format($angka, 0, ",", ".");
}
 
function hitungDiskon($harga, $persen) {
    return $harga - ($harga * $persen / 100);
}
 
$batasDiskon   = 1000000;
$persenDiskon  = 10;
 
if (!isset($_SESSION["produk"]) || isset($_GET["reset"])) {
    $_SESSION["produk"] = $dataAwal;
}
 
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["beli"])) {
    $i = (int) $_POST["beli"];
    if (isset($_SESSION["produk"][$i]) && $_SESSION["produk"][$i]["stok"] > 0) {
        $_SESSION["produk"][$i]["stok"]--;
        $_SESSION["pesan"] = "Berhasil membeli " . $_SESSION["produk"][$i]["nama"] . "!";
    } else {
        $_SESSION["pesan"] = "Maaf, stok produk sudah habis.";
    }
    header("Location: index.php#produk");
    exit;
}
if (isset($_GET["reset"])) {
    header("Location: index.php");
    exit;
}
 
$produk      = $_SESSION["produk"];
$totalProduk = count($produk);
$pesan       = $_SESSION["pesan"] ?? "";
unset($_SESSION["pesan"]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cia Store</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
 
    <header>
        <div class="container nav">
            <div class="logo">Cia Store</div>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#produk">Products</a></li>
                <li><a href="#">About</a></li>
            </ul>
        </div>
    </header>
 
    <main class="container">
        <section class="hero">
            <small>CIA STORE</small>
            <h1>Simple Tech Store.</h1>
            <p>Temukan berbagai perangkat dan aksesoris teknologi untuk kebutuhanmu.</p>
            <a href="#produk" class="btn-hero">Lihat Produk</a>
        </section>
 
        <section id="produk">
            <div class="catalog-head">
                <div>
                    <small>OUR PRODUCTS</small>
                    <h2>Katalog Produk</h2>
                </div>
                <div class="total">Total Produk: <strong><?= $totalProduk; ?></strong> &nbsp;|&nbsp; <a href="?reset=1">Reset Stok</a></div>
            </div>
 
            <?php if ($pesan): ?>
                <div class="notif"><?= htmlspecialchars($pesan); ?></div>
            <?php endif; ?> 
 
            <div class="grid">
                <?php foreach ($produk as $i => $item): ?>
                    <?php
                        $dapatDiskon = $item["harga"] >= $batasDiskon;
                        $hargaAkhir  = $dapatDiskon
                            ? hitungDiskon($item["harga"], $persenDiskon)
                            : $item["harga"];
                        $tersedia = $item["stok"] > 0;
                    ?>
                    <article class="card">
                        <div class="kategori">
                            <?= htmlspecialchars($item["kategori"]); ?>
                            <?php if ($dapatDiskon): ?>
                                <span class="badge-diskon">DISKON <?= $persenDiskon; ?>%</span>
                            <?php endif; ?>
                        </div>
                        <h3><?= htmlspecialchars($item["nama"]); ?></h3>
 
                        <?php if ($dapatDiskon): ?>
                            <div class="harga-normal"><?= formatRupiah($item["harga"]); ?></div>
                        <?php else: ?>
                            <div class="spacer"></div>
                        <?php endif; ?>
                        <div class="harga"><?= formatRupiah($hargaAkhir); ?></div>
 
                        <div class="info">
                            <span>Stok: <?= $item["stok"]; ?></span>
                            <?php if ($tersedia): ?>
                                <span class="status ada">Tersedia</span>
                            <?php else: ?>
                                <span class="status habis">Stok Habis</span>
                            <?php endif; ?>
                        </div>
 
                        <?php if ($tersedia): ?>
                            <form method="post">
                                <input type="hidden" name="beli" value="<?= $i; ?>">
                                <button type="submit" class="btn-beli">Beli Sekarang</button>
                            </form>
                        <?php else: ?>
                            <button class="btn-beli" disabled>Stok Habis</button>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
 
    <footer>
        <div class="container">&copy; <?= date("Y"); ?> Cia Store. All rights reserved.</div>
    </footer>
 
</body>
</html>
 