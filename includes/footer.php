 <!-- Footer Start -->
 <?php
// Veritabanı bağlantısı - footer ayarları için
require_once __DIR__ . '../includes/db.php';

try {
    $pdo_footer = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo_footer->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Footer ayarlarını getir
    $stmt = $pdo_footer->prepare("SELECT * FROM footer_ayar ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $footer_ayar = $stmt->fetch(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $footer_ayar = null;
}

// Varsayılan değerler
$adres = ($footer_ayar && !empty($footer_ayar['adres'])) ? $footer_ayar['adres'] : 'Elmasbahçeler Mah.3. Baş Sokak No 46/1';
$iletisim_no = ($footer_ayar && !empty($footer_ayar['iletisim_no'])) ? $footer_ayar['iletisim_no'] : '0224 272 0168';
$mail = ($footer_ayar && !empty($footer_ayar['mail'])) ? $footer_ayar['mail'] : 'info@elmasbahcelerasm.com.tr';
$hsm_adresi = ($footer_ayar && !empty($footer_ayar['hsm_adresi'])) ? $footer_ayar['hsm_adresi'] : 'Bursa İl Sağlık Müdürlüğü';
$tsm_adresi = ($footer_ayar && !empty($footer_ayar['tsm_adresi'])) ? $footer_ayar['tsm_adresi'] : 'Osmangazi İlçe Sağlık Müdürlüğü';
$copyright_yazisi = ($footer_ayar && !empty($footer_ayar['copyright_yazisi'])) ? $footer_ayar['copyright_yazisi'] : 'Bursa Osmangazi 39 Nolu Elmasbahçeler Aile Sağlığı Merkezi';
?>
 
 <div class="container-fluid bg-dark text-body footer mt-5 pt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4">Adres</h5>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i><?php echo htmlspecialchars($adres); ?></p>
                    <br><hr>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i><?php echo htmlspecialchars($iletisim_no); ?></p>
                    <br><hr>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i><?php echo htmlspecialchars($mail); ?></p>
                    <br><hr>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>HSM:: <?php echo htmlspecialchars($hsm_adresi); ?></p>
                  <br><hr>
                  <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>TSM:: <?php echo htmlspecialchars($tsm_adresi); ?></p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4" >Sağlık Hesaplamaları</h5>
                    <a class="btn btn-link" href="/../on-health/vaccination-schedule"> Bebeğinizin Aşı Takvimi</a>
                    <a class="btn btn-link" href="/../on-health/activity">Aktiviteye Göre Harcanan Kalori</a>
                    <a class="btn btn-link" href="/../on-health/waist-size">Yetişkin Bel Çevresi Hesaplama</a>
                    <a class="btn btn-link" href="/../on-health/child-index">Çocuk Beden Kitle İndeksi</a>
                    <a class="btn btn-link" href="/../on-health/body-index">Yetişkin Beden Kitle İndeksi</a>
                    <a class="btn btn-link" href="/../on-health/percentile"> Persentil Eğrisi</a>
                    <a class="btn btn-link" href="/../on-health/kpercentile">Kız Çocuklar İçin Persentil Tablosu</a>
                    <a class="btn btn-link" href="/../on-health/epercentile">Erkek Çocuklar İçin Persentil Tablosu</a>
                    <a class="btn btn-link" href="/../on-health/pregnancy">GEBELİK DÖNEMİ</a>
                    <a class="btn btn-link" href="/../on-health/newborn"> YENİDOĞAN</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4">ASM Rehberi</h5>
                    <a class="btn btn-link" href="/../homepage/coming-to-asm"> ASM'ye Gelirken</a>
                    <a class="btn btn-link" href="/../homepage/examination"> Tetkik İşlemleri</a>
                    <a class="btn btn-link" href="/../homepage/patient-rights"> Hasta Hakları</a>
                    <a class="btn btn-link" href="/../institutional/service-standards"> Hizmet Standartları</a>
                    <a class="btn btn-link" href="/../homepage/service-policy">Hizmet Politikamız</a>
                    <a class="btn btn-link" href="/../homepage/inspection-procedures"> Muayene İşlemleri</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4">Dosyalar</h5>
                    <a class="btn btn-link" href="/../filesasm/instructions"> ASM Talimatları</a>
                    <a class="btn btn-link" href="/../filesasm/useful-files"> Yararlı Dosyalar</a>
                    <a class="btn btn-link" href="/../filesasm/first-aid">İlk Yardım</a>
                    <a class="btn btn-link" href="/../filesasm/smoking-harm">Sigaranın Zararları</a>
                    <a class="btn btn-link" href="/../filesasm/disease-information">Hastalıklar Hakkında</a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a href="#"><?php echo htmlspecialchars($copyright_yazisi); ?></a>, All Right Reserved.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                       
                        Designed By <a href="https://www.linkedin.com/in/ahmet-g%C3%BCrsoy-86aa93364/" target="_blank">Ahmet Gürsoy</a>
                        <br>Distributed By: <a href="https://atlasyazilim.org/" target="_blank">Atlas Yazılım</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->