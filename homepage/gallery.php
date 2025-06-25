<?php
// Veritabanı bağlantısı - galeri için
$servername = "94.138.202.35";
$username = "_SBA";         
$password = "Sba1171212311";
$dbname = "fhc";

try {
    $pdo_galeri = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo_galeri->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Aktif galeri fotoğraflarını sıralı olarak getir
    $stmt = $pdo_galeri->prepare("SELECT * FROM galeri WHERE durum = 'aktif' ORDER BY sira ASC, id ASC");
    $stmt->execute();
    $galeri_fotograflari = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $galeri_fotograflari = [];
}
?>

<?php include '../includes/topbar.php'; ?>
<?php include '../includes/navbar.php'; ?>
<?php include '../includes/head.php'; ?>
<?php include '../includes/spinner.php'; ?>
<div class="container-fluid page-header py-5 mb-5">
    <div class="container py-5">
        <h1 class="display-3 text-white mb-3 animated slideInDown">Aile Sağlık Merkezimizin Fotoğrafları</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-white" href="#">Anasayfa</a></li>
                <li class="breadcrumb-item"><a class="text-white" href="#">Galeri</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Aile Sağlık Merkezimizin Fotoğrafları</li>
            </ol>
        </nav>
    </div>
</div>

<div class="gallery">
    <?php if (!empty($galeri_fotograflari)): ?>
        <?php foreach ($galeri_fotograflari as $index => $foto): ?>
            <div class="foto-container">
                <img src="../admin/<?php echo htmlspecialchars($foto['foto']); ?>" 
                     class="thumbnail" 
                     alt="<?php echo htmlspecialchars($foto['baslik'] ?: ($index + 1)); ?>"
                     data-baslik="<?php echo htmlspecialchars($foto['baslik']); ?>"
                     data-aciklama="<?php echo htmlspecialchars($foto['aciklama']); ?>">
                <?php if (!empty($foto['baslik']) || !empty($foto['aciklama'])): ?>
                    <div class="foto-overlay">
                        <?php if (!empty($foto['baslik'])): ?>
                            <h6><?php echo htmlspecialchars($foto['baslik']); ?></h6>
                        <?php endif; ?>
                        <?php if (!empty($foto['aciklama'])): ?>
                            <p><?php echo htmlspecialchars(substr($foto['aciklama'], 0, 60) . (strlen($foto['aciklama']) > 60 ? '...' : '')); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Varsayılan fotoğraflar - veritabanında kayıt yoksa -->
        <div class="alert alert-info text-center w-100">
            <h5>Henüz galeri fotoğrafı eklenmemiş</h5>
            <p>Admin panelinden galeri fotoğrafları eklendiğinde burada görünecektir.</p>
        </div>
    <?php endif; ?>
</div>

<div class="lightbox" id="lightbox">
    <span class="close" id="closeBtn">&times;</span>
    <img class="lightbox-img" id="lightboxImg" src="">
    <div class="lightbox-info" id="lightboxInfo">
        <h4 id="lightboxBaslik"></h4>
        <p id="lightboxAciklama"></p>
    </div>
    <a class="prev" id="prevBtn">&#10094;</a>
    <a class="next" id="nextBtn">&#10095;</a>
</div>



<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0px;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .gallery {
        display: flex;
        flex-wrap: wrap;    
        gap: 20px;
        justify-content: center;
        margin-top: 50px;
    }

    .foto-container {
        position: relative;
        width: 220px;
        height: 150px;
        overflow: hidden;
        border-radius: 10px;
        border: 3px solid #ccc;
        transition: transform 0.3s ease, border-color 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .foto-container:hover {
        transform: scale(1.07);
        border-color: #666;
    }

    .thumbnail {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
        border-radius: 7px;
        transition: transform 0.3s ease;
    }

    .foto-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.8));
        color: white;
        padding: 10px;
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }

    .foto-container:hover .foto-overlay {
        transform: translateY(0);
    }

    .foto-overlay h6 {
        font-size: 14px;
        margin: 0 0 5px 0;
        font-weight: bold;
    }

    .foto-overlay p {
        font-size: 12px;
        margin: 0;
        opacity: 0.9;
    }

    .lightbox {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        align-items: center;
        justify-content: center;
        padding: 10px;
    }

    .lightbox-img {
        max-width: 100%;
        max-height: 90vh;
        border-radius: 10px;
        box-shadow: 0 0 25px rgba(255, 255, 255, 0.3);
    }

    .lightbox-info {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        text-align: center;
        max-width: 80%;
    }

    .lightbox-info h4 {
        margin: 0 0 10px 0;
        font-size: 18px;
    }

    .lightbox-info p {
        margin: 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .close,
    .prev,
    .next {
        position: absolute;
        color: white;
        font-size: 50px;
        font-weight: bold;
        cursor: pointer;
        user-select: none;
        transition: color 0.3s ease;
    }

    .close {
        top: 20px;
        right: 30px;
    }

    .prev {
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
    }

    .next {
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
    }

    .close:hover,
    .prev:hover,
    .next:hover {
        color: #ccc;
    }

    /* 📱 Mobil uyumluluk */
    @media (max-width: 600px) {
        .thumbnail {
            width: 90%;
            height: auto;
        }

        .prev,
        .next {
            font-size: 40px;
        }

        .close {
            font-size: 40px;
            right: 15px;
            top: 10px;
        }
    }

    .gallery , .lightbox {
        flex: 1;
    }
</style>
<script>
    const thumbnails = document.querySelectorAll('.thumbnail');
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxInfo = document.getElementById('lightboxInfo');
    const lightboxBaslik = document.getElementById('lightboxBaslik');
    const lightboxAciklama = document.getElementById('lightboxAciklama');
    const closeBtn = document.getElementById('closeBtn');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    let currentIndex = 0;

    const images = Array.from(thumbnails).map(img => ({
        src: img.src,
        baslik: img.dataset.baslik || '',
        aciklama: img.dataset.aciklama || ''
    }));

    function showLightbox(index) {
        lightbox.style.display = 'flex';
        lightboxImg.src = images[index].src;
        
        // Başlık ve açıklama göster
        if (images[index].baslik || images[index].aciklama) {
            lightboxBaslik.textContent = images[index].baslik;
            lightboxAciklama.textContent = images[index].aciklama;
            lightboxInfo.style.display = 'block';
        } else {
            lightboxInfo.style.display = 'none';
        }
        
        currentIndex = index;
    }

    thumbnails.forEach((img, index) => {
        img.addEventListener('click', () => {
            showLightbox(index);
        });
    });

    closeBtn.onclick = () => {
        lightbox.style.display = 'none';
    };

    prevBtn.onclick = () => {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        showLightbox(currentIndex);
    };

    nextBtn.onclick = () => {
        currentIndex = (currentIndex + 1) % images.length;
        showLightbox(currentIndex);
    };

    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            lightbox.style.display = 'none';
        }
    });
</script>

<footer><?php include '../includes/footer.php'; ?></footer>
<?php include '../includes/js-library.php'; ?>