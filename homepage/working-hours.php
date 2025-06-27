<?php
// Veritabanı bağlantısı
require_once __DIR__ . '../includes/db.php';


try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Çalışma saatleri fotoğrafını getir
    $stmt = $pdo->prepare("SELECT foto FROM calisma_saatleri ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $calisma_saatleri = $stmt->fetch(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $calisma_saatleri = null;
}
?>

<!DOCTYPE html>
<html>
<head>
<?php include'../includes/head.php'?>
<?php include'../includes/topbar.php' ?>
</head>
<body>
 <?php include'../includes/spinner.php' ?> 
<?php include'../includes/navbar.php' ?>
<div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Çalışma Saatleri</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="#">Anasayfa</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="#">Çalışma Saatleri</a></li>
                    
                </ol>
            </nav>
        </div>
    </div>
<hr>
<div class="text-center">
    
    <?php if ($calisma_saatleri && !empty($calisma_saatleri['foto'])): ?>
        <?php 
        // Dosya yolunu düzenle
        $foto_yolu = $calisma_saatleri['foto'];
        if (strpos($foto_yolu, 'uploads/') === 0) {
            $foto_yolu = '../admin/' . $foto_yolu;
        }
        ?>
        <img src="<?php echo htmlspecialchars($foto_yolu); ?>" 
             alt="Çalışma Saatleri" class="img-fluid" style="max-width: 800px;">
    <?php else: ?>
        <div class="alert alert-warning">
            <h5>Çalışma Saatleri Fotoğrafı Bulunamadı</h5>
            <p>Lütfen admin panelinden çalışma saatleri fotoğrafını yükleyiniz.</p>
        </div>
    <?php endif; ?>
</div>
<hr>
<?php include'../includes/js-library.php'?>
<?php include'../includes/footer.php'?>
</body>