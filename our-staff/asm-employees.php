<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Veritabanı bağlantısı
$conn = new mysqli('localhost', 'root', '', 'fhc');
if ($conn->connect_error) {
    die('Veritabanı bağlantı hatası: ' . $conn->connect_error);
}
$conn->set_charset("utf8");

// Veritabanından sadece aile sağlığı çalışanlarını çeker
$departman = 'aile_sagligi_calisanlari';
$sql = "SELECT * FROM calisanlar WHERE departman = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $departman);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<?php include '../includes/head.php' ?>
<body>

<?php include '../includes/navbar.php' ?>

<!-- Sayfa başlığı ve breadcrumb -->
<div class="container-fluid page-header py-5 mb-5">
  <div class="container py-5">
    <h1 class="display-3 text-white mb-3 animated slideInDown">Aile Sağlığı Çalışanlarımız</h1>
    <nav aria-label="breadcrumb animated slideInDown">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a class="text-white" href="#">Anasayfa</a></li>
        <li class="breadcrumb-item"><a class="text-white" href="#">Kadromuz</a></li>
        <li class="breadcrumb-item text-white active" aria-current="page">Aile Sağlığı Çalışanlarımız</li>
      </ol>
    </nav>
  </div>
</div>

<!-- Kadro Başlık -->
<div class="container-xxl py-5">
  <div class="container">
    <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
      <h6 class="text-primary">Kadromuz</h6>
      <h1 class="mb-4">Aile Sağlığı Çalışanlarımız</h1>
    </div>

    <!-- ÇALIŞAN KARTLARI -->
    <div class="row g-4">
      <?php 
      $delay = 0.1;
      while($row = $result->fetch_assoc()): 
        $adSoyad = htmlspecialchars($row['ad'] . ' ' . $row['soyad']);
        $pozisyon = htmlspecialchars($row['pozisyon'] ?? '');
        $cinsiyet = strtolower($row['cinsiyet'] ?? '');
        
        if (!empty($row['foto'])) {
          $fotoYolu = '../admin/' . $row['foto'];
        } else {
          $fotoYolu = ($cinsiyet === 'kadın') 
            ? '../admin/uploads/staff.png' 
            : '../admin/uploads/staff2.png';
        }
      ?>
        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="<?= $delay ?>s">
          <div class="team-item rounded overflow-hidden text-center p-4">
            <img class="img-fluid rounded-circle mb-3" src="<?= $fotoYolu ?>" alt="<?= $adSoyad ?>">
            <h5 class="mb-0"><?= $adSoyad ?></h5>
            <?php if (!empty($pozisyon)): ?>
              <p class="text-muted mb-0"><?= $pozisyon ?></p>
            <?php endif; ?>
          </div>
        </div>
      <?php 
        $delay += 0.2;
      endwhile; 
      ?>
    </div>
    <!-- /ÇALIŞAN KARTLARI -->

  </div>
</div>

<!-- Kart Stili -->
<style>
.team-item {
  background: #ffffff;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.team-item:hover {
  transform: translateY(-10px);
  box-shadow: 0 12px 24px rgba(0,0,0,0.2);
}
</style>

<?php include '../includes/js-library.php' ?>
<?php include '../includes/footer.php' ?>
</body>
</html>
