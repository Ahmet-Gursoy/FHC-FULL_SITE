<!-- Topbar Start -->
<?php
// Veritabanı bağlantısı - topbar ayarları için
require_once __DIR__ . '../includes/db.php';

try {
    $pdo_topbar = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo_topbar->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Footer ayarlarından adres ve iletişim bilgilerini getir
    $stmt = $pdo_topbar->prepare("SELECT adres, iletisim_no FROM footer_ayar ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $topbar_ayar = $stmt->fetch(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $topbar_ayar = null;
}

// Varsayılan değerler
$topbar_adres = ($topbar_ayar && !empty($topbar_ayar['adres'])) ? $topbar_ayar['adres'] : 'Elmasbahçeler Mah.3. Baş Sokak No 46/1';
$topbar_telefon = ($topbar_ayar && !empty($topbar_ayar['iletisim_no'])) ? $topbar_ayar['iletisim_no'] : '0224 272 0168';
?>

<div class="container-fluid bg-dark p-0">
        <div class="row gx-0 d-none d-lg-flex">
            <div class="col-lg-7 px-5 text-start">
                <div class="h-100 d-inline-flex align-items-center me-4">
                    <small class="fa fa-map-marker-alt text-primary me-2"></small>
                    <small><?php echo htmlspecialchars($topbar_adres); ?></small>
                </div>
            </div>
            
            <div class="col-lg-5 px-5 text-end">
                <div class="h-100 d-inline-flex align-items-center me-4">
                    <small class="fa fa-phone-alt text-primary me-2"></small>
                    <small><?php echo htmlspecialchars($topbar_telefon); ?></small>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->