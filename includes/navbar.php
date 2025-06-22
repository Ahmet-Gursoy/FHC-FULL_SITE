<!-- Navbar Start -->
<?php
// Veritabanı bağlantısı - site adını çekmek için
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fhc";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Site adını getir
    $stmt = $pdo->prepare("SELECT site_ismi FROM site_ayar ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $site_ayar = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $site_adi = ($site_ayar && !empty($site_ayar['site_ismi'])) ? $site_ayar['site_ismi'] : 'ELMASBAHÇELER ASM';
    
} catch(PDOException $e) {
    $site_adi = 'ELMASBAHÇELER ASM'; // Varsayılan değer
}
?>

<nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top p-0">
        <a href="../index.php" class="navbar-brand d-flex align-items-center border-end px-4 px-lg-5">
        <img src="../img/saglikbakan2.jpg" alt="Logo" style="height: 50px; margin-left: 0px; margin-right: 10px;">
            <h2 class="m-0 text-primary"><?php echo htmlspecialchars($site_adi); ?></h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="../index.php" class="nav-item nav-link ">Ana Sayfa</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Kurumsal</a>
                    <div class="dropdown-menu bg-light m-0">
                        <a href="../institutional/service-standards.php" class="dropdown-item">Hizmet Standartları</a>
                        <a href="../institutional/priority-patients.php" class="dropdown-item">Öncelikli Hastalar</a>
                    </div>
                </div>
                <a href="../homepage/announcements.php" class="nav-item nav-link">DUYURULAR</a>
                <a href="../homepage/gallery.php" class="nav-item nav-link">Galeri</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Kadromuz</a>
                    <div class="dropdown-menu bg-light m-0">
                        <a href="../our-staff/doctor-employees.php" class="dropdown-item">Aile Hekimlerimiz</a>
                        <a href="../our-staff/asm-employees.php" class="dropdown-item">Aile Sağlığı Çalışanlarımız</a> 
                        <a href="../our-staff/support-staff.php" class="dropdown-item">Yardımcı Personellerimiz</a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Dosyalar</a>
                    <div class="dropdown-menu bg-light m-0">
                        <a href="../filesasm/instructions.php" class="dropdown-item">ASM Talimatları</a>
                        <a href="../filesasm/useful-files.php" class="dropdown-item">Yararlı Dosyalar</a>
                        <a href="../filesasm/first-aid.php" class="dropdown-item">İlk Yardım</a>
                        <a href="../filesasm/smoking-harm.php" class="dropdown-item">Sigaranın Zararları</a>
                        <a href="../filesasm/disease-information.php" class="dropdown-item">Hastalıklar Hakkında </a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Bağlantılar</a>
                    <div class="dropdown-menu bg-light m-0">
                        <a href="https://www.saglik.gov.tr/" class="dropdown-item">T.C Sağlık Bakanlığı</a>
                        <a href="http://www.bursa.gov.tr/" class="dropdown-item">Bursa Valiliği</a>
                        <a href="https://bursaism.saglik.gov.tr/" class="dropdown-item">Bursa Sağlık Müdürlüğü</a>
                        <a href="https://enabiz.gov.tr/" class="dropdown-item">Tahlil-Tetkik Sorgu </a>
                        <a href="https://www.turkiye.gov.tr/aile-hekim-bilgisi" class="dropdown-item">Aile Hekimim Kim</a>
                        <a href="https://mhrs.gov.tr/vatandas/#/" class="dropdown-item">Mhrs Randevu Al </a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Sağlık Üzerine</a>
                    <div class="dropdown-menu bg-light m-0">
                        <a href="../on-health/vaccination-schedule.php" class="dropdown-item">Bebeğinizin Aşı Takvimi</a>
                        <a href="../on-health/activity.php" class="dropdown-item">Aktiviteye Göre Harcanan Kalori</a>
                        <a href="../on-health/waist-size.php" class="dropdown-item">Yetişkin Bel Çevresi Hesaplama</a>
                        <a href="../on-health/body-index.php" class="dropdown-item">Yetişkin Beden Kitle Endeksi</a>
                        <a href="../on-health/child-index.php" class="dropdown-item">Çocuk Beden Kitle Endeksi </a>
                        <a href="../on-health/percentile.php" class="dropdown-item">Persentil Eğrisi</a>
                        <a href="../on-health/kpercentile.php" class="dropdown-item">Kız Çocukları İçin Persentil Tablosu</a>
                        <a href="../on-health/epercentile.php" class="dropdown-item">Erkek Çocukları İçin Persentil Tablosu</a>
                        <a href="../on-health/pregnancy.php" class="dropdown-item">Gebelik Dönemi </a>
                        <a href="../on-health/newborn.php" class="dropdown-item">YENİDOĞAN</a>
                    </div>
                </div>
                <a href="communication.php" class="nav-item nav-link">İLETİŞİM</a>
            </div>
           
        </div>
    </nav>
    <!-- Navbar End -->