<?php
// Veritabanı bağlantısı
$conn = new mysqli('94.138.202.35', '_SBA', 'Sba1171212311', 'fhc');
$conn->set_charset("utf8mb4");
if ($conn->connect_error) {
    die("Veritabanı bağlantı hatası: " . $conn->connect_error);
}

// ID kontrolü
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Geçersiz bağlantı.');
}

$id = (int)$_GET['id'];

// Veritabanından rehber çek
$stmt = $conn->prepare("SELECT * FROM rehberler WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$rehber = $result->fetch_assoc();

if (!$rehber) {
    die('Rehber bulunamadı.');
}
?>

<?php include'../includes/topbar.php'; ?>
<?php include'../includes/navbar.php'; ?>
<?php include'../includes/head.php'; ?>
<?php include'../includes/spinner.php'; ?>

<!-- Sayfa Başlığı -->
<div class="container-fluid page-header py-5 mb-5">
    <div class="container py-5">
        <h1 class="display-3 text-white mb-3 animated slideInDown"><?php echo htmlspecialchars($rehber['baslik']); ?></h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-white" href="#">Anasayfa</a></li>
                <li class="breadcrumb-item"><a class="text-white" href="#">ASM Rehberi</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page"><?php echo htmlspecialchars($rehber['baslik']); ?></li>
            </ol>
        </nav>
    </div>
</div>

<!-- Rehber Görseli (isteğe bağlı) -->
<?php if (!empty($rehber['resim_yolu'])): ?>
    <img src="<?php echo htmlspecialchars($rehber['resim_yolu']); ?>" class="img-fluid mx-auto d-block mb-4" alt="Görsel">
<?php endif; ?>

<!-- Rehber İçeriği -->
<?php echo $rehber['icerik']; ?>

<?php include'../includes/footer.php'; ?>
<?php include'../includes/js-library.php'; ?>
