<!-- Team Start -->
<?php
// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fhc";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Aile Hekimleri departmanındaki çalışanları getir
    $stmt = $pdo->prepare("SELECT *, CONCAT(ad, ' ', soyad) as ad_soyad, foto as fotograf, cinsiyet FROM calisanlar WHERE departman = 'aile_hekimleri' ORDER BY id ASC");
    $stmt->execute();
    $aile_hekimleri = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $aile_hekimleri = [];
}
?>

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 class="mb-4">Aile Hekimlerimiz</h1>
        </div>

        <div class="row g-4">
            <?php if (!empty($aile_hekimleri)): ?>
                <?php 
                $delay = 0.1;
                foreach ($aile_hekimleri as $hekim): 
                    // Fotoğraf belirleme mantığı
                    if (!empty($hekim['foto'])) {
                      $fotoYolu = "../" . htmlspecialchars($hekim['foto']);
                    } else {
                        // Cinsiyete göre varsayılan foto seç
                        if ($hekim['cinsiyet'] === 'kadın') {
                            $fotoYolu = "kadındoktor.jpg";
                        } else {
                            $fotoYolu = "erkekdoktor.jpg";
                        }
                    }
                ?>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                        <div class="team-item rounded overflow-hidden">
                            <div class="d-flex justify-content-center">
                                <img class="img-fluid w-75" src="/<?php echo $fotoYolu = 'admin/' . $hekim['foto']; ?>" alt="<?php echo htmlspecialchars($hekim['ad_soyad']); ?>">

                            </div>
                            <div class="p-4 text-center">
                                <h5><?php echo htmlspecialchars($hekim['ad_soyad']); ?></h5>
                                <?php if (!empty($hekim['pozisyon'])): ?>
                                    <p class="text-muted mb-0"><?php echo htmlspecialchars($hekim['pozisyon']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php 
                $delay += 0.2;
                endforeach; 
                ?>
            <?php else: ?>
                <!-- Varsayılan kart -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="team-item rounded overflow-hidden">
                        <div class="d-flex justify-content-center">
                            <img class="img-fluid w-75" src="../admin/uploads/erkekdoktor.jpg" alt="Varsayılan">
                        </div>
                        <div class="p-4 text-center">
                            <h5>Aile Hekimi</h5>
                            <p class="text-muted mb-0">Lütfen admin panelinden aile hekimlerini ekleyiniz</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

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
<!-- Team End -->
