<?php
session_start();
$conn = new mysqli('94.138.202.35', '_SBA', 'Sba1171212311', 'fhc');
if ($conn->connect_error) {
    die('Veritabanı bağlantı hatası: ' . $conn->connect_error);
}
$conn->set_charset("utf8");

$departman = 'yardimci_personel';
$sql = "SELECT * FROM calisanlar WHERE departman = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $departman);
$stmt->execute();
$result = $stmt->get_result();

?>


<!DOCTYPE html>
<html>
<?php include '../includes/topbar.php' ?>
<head>
    <?php include '../includes/head.php' ?>
</head>

<body>
    <?php include '../includes/spinner.php' ?>
    <?php include '../includes/navbar.php' ?>
    
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Yardımcı Personellerimiz</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="#">Anasayfa</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="#">Kadromuz</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Yardımcı Personellerimiz</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Team Start -->
    <div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h6 class="text-primary">Kadromuz</h6>
            <h1 class="mb-4">Aile Sağlığı Çalışanlarımız</h1>
        </div>

        <div class="row g-4">
           <?php while($row = $result->fetch_assoc()): 
  $adSoyad = htmlspecialchars($row['ad'] . ' ' . $row['soyad']);
  $pozisyon = htmlspecialchars($row['pozisyon'] ?? '');
  $cinsiyet = strtolower($row['cinsiyet'] ?? '');

  // Fotoğraf yolu belirleme
  if (!empty($row['foto'])) {
    $fotoYolu = '../admin/' . $row['foto'];
  } else {
    $fotoYolu = ($cinsiyet === 'kadın') 
      ? '../admin/uploads/staff4.jpg' 
      : '../admin/uploads/staff3.png';
  }
?>
  <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
    <div class="team-item rounded overflow-hidden text-center p-4">
      <img class="img-fluid rounded-circle mb-3" src="<?= $fotoYolu ?>" alt="<?= $adSoyad ?>">
      <h5 class="mb-0"><?= $adSoyad ?></h5>
      <?php if (!empty($pozisyon)): ?>
        <p class="text-muted mb-0"><?= $pozisyon ?></p>
      <?php endif; ?>
    </div>
  </div>
<?php endwhile; ?>

        </div>
    </div>
</div>


<style>
    .team-item {
    background: #ffffff;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Kartların hover hali */
.team-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.2);
}
.image-container {
    width: 100%;
    height: 300px;
    overflow: hidden;
    position: relative;
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}


.custom-card {
    background: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}


.custom-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.2);
}


.custom-card h5 {
    margin-top: 15px;
    font-weight: bold;
    color: #333;
}
        .image-container {
            width: 100%;
            height: 300px;
            overflow: hidden;
            position: relative;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>


    <!-- Team End -->
    <?php include '../includes/js-library.php' ?>
    <?php include '../includes/footer.php' ?>

</body>