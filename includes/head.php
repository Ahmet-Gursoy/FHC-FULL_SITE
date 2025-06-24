<?php
// Veritabanı bağlantısı - site başlığı için
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fhc";

try {
    $pdo_head = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo_head->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Site başlığını getir - debug için tüm verileri çek
    $stmt = $pdo_head->prepare("SELECT * FROM site_ayar ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $head_ayar = $stmt->fetch(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $head_ayar = null;
}

// Site başlığını belirle
if ($head_ayar && isset($head_ayar['site_basligi']) && !empty($head_ayar['site_basligi'])) {
    $site_title = $head_ayar['site_basligi'];
} else {
    $site_title = 'Bursa Osmangazi 39 NO\'lu Elmasbahçeler ASM';
}
?>
<base href="/" />
<meta charset="utf-8">
    <title><?php echo htmlspecialchars($site_title); ?></title>
    <style> .navbar-brand h2 {color: red !important;}</style>
    <link rel="icon" type="image/png" href="../img/saglikbakan.png">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="../img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Roboto:wght@500;700;900&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../lib/animate/animate.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">
    