<?php include '../includes/topbar.php'; ?>
<?php include '../includes/navbar.php'; ?>
<?php include '../includes/head.php'; ?>
<?php include '../includes/spinner.php'; ?>

  
<?php
// ID'yi al
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Veritabanı bağlantısı
$conn = new mysqli('94.138.202.35', '_SBA', 'Sba1171212311', 'fhc');
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// ID üzerinden duyuruyu çek
$stmt = $conn->prepare("SELECT * FROM duyurular WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='container'><h2>Duyuru bulunamadı.</h2></div>";
    exit;
}

$duyuru = $result->fetch_assoc();
?>

<!-- HEADER -->
<div class="container-fluid page-header py-5 mb-5">
    <div class="container py-5">
        <h1 class="display-3 text-white mb-3 animated slideInDown"><?= $duyuru['baslik'] ?></h1>
    </div>
</div>

<!-- FOTO -->
<?php if (!empty($duyuru['foto_url'])): ?>
<div style="text-align: center;">
    <img src="<?= $duyuru['foto_url'] ?>" width="800" height="400" alt="<?= $duyuru['baslik'] ?>">
</div>
<?php endif; ?>

<br><br>

<!-- AÇIKLAMA -->
<?php if (!empty($duyuru['aciklama'])): ?>
<div class="container" style="text-align: center;">
    <?= $duyuru['aciklama'] ?>
</div>
<?php endif; ?>

<!-- VİDEO -->
<?php if (!empty($duyuru['video_embed'])): ?>
<div style="text-align: center;">
    <?= $duyuru['video_embed'] ?>
</div>
<?php endif; ?>

<!-- HARİCİ LİNK -->
<?php if (!empty($duyuru['harici_link'])): ?>
<p style="text-align: center;">
</p>
<?php endif; ?>

<?php include '../includes/js-library.php'; ?>
<?php include '../includes/footer.php'; ?>