<?php
require_once __DIR__.'/includes/Auth.php';
requireAuth(['admin']);

// Veritabanı bağlantısı
$conn = new mysqli('localhost', 'root', '', 'fhc');
if ($conn->connect_error) {
    die('Veritabanı bağlantı hatası: ' . $conn->connect_error);
}

$alert = '';

// Site İsmi ve Başlığı Kaydet
if (isset($_POST['site_ismi']) || isset($_POST['site_basligi'])) {
    $site_ismi = isset($_POST['site_ismi']) ? $conn->real_escape_string($_POST['site_ismi']) : '';
    $site_basligi = isset($_POST['site_basligi']) ? $conn->real_escape_string($_POST['site_basligi']) : '';
    
    $check = $conn->query("SELECT id FROM site_ayar LIMIT 1");
    if ($check && $check->num_rows > 0) {
        if (isset($_POST['site_ismi']) && isset($_POST['site_basligi'])) {
            $conn->query("UPDATE site_ayar SET site_ismi='$site_ismi', site_basligi='$site_basligi' WHERE id=1");
        } elseif (isset($_POST['site_ismi'])) {
            $conn->query("UPDATE site_ayar SET site_ismi='$site_ismi' WHERE id=1");
        } elseif (isset($_POST['site_basligi'])) {
            $conn->query("UPDATE site_ayar SET site_basligi='$site_basligi' WHERE id=1");
        }
    } else {
        if (isset($_POST['site_ismi']) && isset($_POST['site_basligi'])) {
            $conn->query("INSERT INTO site_ayar (site_ismi, site_basligi) VALUES ('$site_ismi', '$site_basligi')");
        } elseif (isset($_POST['site_ismi'])) {
            $conn->query("INSERT INTO site_ayar (site_ismi) VALUES ('$site_ismi')");
        } elseif (isset($_POST['site_basligi'])) {
            $conn->query("INSERT INTO site_ayar (site_basligi) VALUES ('$site_basligi')");
        }
    }
    $alert = '<div class="alert alert-success">Site bilgileri başarıyla kaydedildi.</div>';
}

// Mevcut site ismi ve başlığını çek
$site_ismi = '';
$site_basligi = '';
$res = $conn->query("SELECT site_ismi, site_basligi FROM site_ayar LIMIT 1");
if ($res && $row = $res->fetch_assoc()) {
    $site_ismi = $row['site_ismi'] ?? '';
    $site_basligi = $row['site_basligi'] ?? '';
}

// Çalışma Saatleri Fotoğraf Ekle/Güncelle
if (isset($_FILES['calisma_foto']) && $_FILES['calisma_foto']['error'] === UPLOAD_ERR_OK) {
    // Eski fotoğrafı sil
    $eski_foto = $conn->query("SELECT foto FROM calisma_saatleri LIMIT 1");
    if ($eski_foto && $row = $eski_foto->fetch_assoc() && $row['foto']) {
        if (file_exists(__DIR__ . '/' . $row['foto'])) {
            unlink(__DIR__ . '/' . $row['foto']);
        }
    }
    
    $ext = pathinfo($_FILES['calisma_foto']['name'], PATHINFO_EXTENSION);
    $foto = 'uploads/calisma_saatleri_' . time() . '.' . $ext;
    move_uploaded_file($_FILES['calisma_foto']['tmp_name'], __DIR__ . '/' . $foto);
    
    // Kayıt var mı kontrol et
    $check = $conn->query("SELECT id FROM calisma_saatleri LIMIT 1");
    if ($check && $check->num_rows > 0) {
        $conn->query("UPDATE calisma_saatleri SET foto='$foto' WHERE id=(SELECT id FROM (SELECT id FROM calisma_saatleri LIMIT 1) AS temp)");
    } else {
        $conn->query("INSERT INTO calisma_saatleri (foto) VALUES ('$foto')");
    }
    $alert = '<div class="alert alert-success">Çalışma saatleri fotoğrafı güncellendi.</div>';
}

// Mevcut çalışma saatleri fotoğrafını çek
$mevcut_calisma_foto = '';
$res = $conn->query("SELECT foto FROM calisma_saatleri LIMIT 1");
if ($res && $row = $res->fetch_assoc()) {
    $mevcut_calisma_foto = $row['foto'];
}



// Hizmetler Ekle/Güncelle
if (isset($_POST['hizmet_baslik'], $_POST['hizmet_aciklama'])) {
    $baslik = $conn->real_escape_string($_POST['hizmet_baslik']);
    $aciklama = $conn->real_escape_string($_POST['hizmet_aciklama']);
    $conn->query("INSERT INTO hizmetler (baslik, aciklama) VALUES ('$baslik', '$aciklama')");
    $alert = '<div class="alert alert-success">Hizmet eklendi.</div>';
}
// Hizmetleri çek
$hizmetler = $conn->query("SELECT * FROM hizmetler ORDER BY id DESC");

// Footer Ayarları Kaydet
if (isset($_POST['adres'], $_POST['iletisim_no'], $_POST['mail'], $_POST['hsm_adresi'], $_POST['tsm_adresi'], $_POST['copyright_yazisi'])) {
    $adres = $conn->real_escape_string($_POST['adres']);
    $iletisim_no = $conn->real_escape_string($_POST['iletisim_no']);
    $mail = $conn->real_escape_string($_POST['mail']);
    $hsm_adresi = $conn->real_escape_string($_POST['hsm_adresi']);
    $tsm_adresi = $conn->real_escape_string($_POST['tsm_adresi']);
    $copyright = $conn->real_escape_string($_POST['copyright_yazisi']);
    $check = $conn->query("SELECT id FROM footer_ayar LIMIT 1");
    if ($check && $check->num_rows > 0) {
        $conn->query("UPDATE footer_ayar SET adres='$adres', iletisim_no='$iletisim_no', mail='$mail', hsm_adresi='$hsm_adresi', tsm_adresi='$tsm_adresi', copyright_yazisi='$copyright' WHERE id=1");
    } else {
        $conn->query("INSERT INTO footer_ayar (adres, iletisim_no, mail, hsm_adresi, tsm_adresi, copyright_yazisi) VALUES ('$adres', '$iletisim_no', '$mail', '$hsm_adresi', '$tsm_adresi', '$copyright')");
    }
    $alert = '<div class="alert alert-success">Footer ayarları kaydedildi.</div>';
}
// Footer ayarlarını çek
$footer = [
    'adres' => '', 'iletisim_no' => '', 'mail' => '', 'hsm_adresi' => '', 'tsm_adresi' => '', 'copyright_yazisi' => ''
];
$res = $conn->query("SELECT * FROM footer_ayar LIMIT 1");
if ($res && $row = $res->fetch_assoc()) {
    $footer = $row;
}

define('ADMIN_HEADER', true);
define('ADMIN_SIDEBAR', true);
define('ADMIN_FOOTER', true);
include __DIR__.'/includes/header.php';
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-2 p-0">
      <?php include __DIR__.'/includes/sidebar.php'; ?>
    </div>
    <div class="col-md-10 col-12 p-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h2 class="mb-4">Site Ayarları</h2>
          <?php if ($alert) echo $alert; ?>
          <ul class="nav nav-tabs mb-3" id="settingsTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="site-tab" data-bs-toggle="tab" data-bs-target="#site" type="button" role="tab">Site Bilgileri</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="calisma-tab" data-bs-toggle="tab" data-bs-target="#calisma" type="button" role="tab">Çalışma Saatleri</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="hizmet-tab" data-bs-toggle="tab" data-bs-target="#hizmet" type="button" role="tab">Hizmetler</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="footer-tab" data-bs-toggle="tab" data-bs-target="#footer" type="button" role="tab">Footer Ayarları</button>
            </li>
          </ul>
          <div class="tab-content" id="settingsTabContent">
            <!-- Site İsmi -->
            <div class="tab-pane fade show active" id="site" role="tabpanel">
              <form method="post">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="site_ismi" class="form-label">Site İsmi</label>
                    <input type="text" class="form-control" id="site_ismi" name="site_ismi" value="<?php echo htmlspecialchars($site_ismi); ?>" placeholder="Örn: ELMASBAHÇELER ASM">
                    <div class="form-text">Navbar'da görünen site ismi</div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="site_basligi" class="form-label">Site Başlığı</label>
                    <input type="text" class="form-control" id="site_basligi" name="site_basligi" value="<?php echo htmlspecialchars($site_basligi); ?>" placeholder="Örn: Bursa Osmangazi 39 NO'lu Elmasbahçeler ASM">
                    <div class="form-text">Tarayıcı sekmesinde görünen başlık</div>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-save"></i> Kaydet
                </button>
              </form>
            </div>
            <!-- Çalışma Saatleri -->
            <div class="tab-pane fade" id="calisma" role="tabpanel">
              <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                  <label for="calisma_foto" class="form-label">Çalışma Saatleri Fotoğrafı</label>
                  <input type="file" class="form-control" id="calisma_foto" name="calisma_foto" accept="image/*">
                  <div class="form-text">Sadece bir adet fotoğraf yükleyebilirsiniz. Yeni yüklenen fotoğraf eskisinin yerine geçer.</div>
                </div>
                <button type="submit" class="btn btn-primary">Fotoğrafı Güncelle</button>
              </form>
              <hr>
              <h5>Mevcut Çalışma Saatleri Fotoğrafı</h5>
              <div class="text-center">
                <?php if ($mevcut_calisma_foto && file_exists(__DIR__ . '/' . $mevcut_calisma_foto)): ?>
                  <img src="<?php echo $mevcut_calisma_foto; ?>" alt="Çalışma Saatleri" class="img-fluid rounded shadow" style="max-width: 500px; max-height: 400px;">
                  <p class="mt-2 text-muted">Son güncelleme: <?php echo date('d.m.Y H:i'); ?></p>
                <?php else: ?>
                  <div class="alert alert-info">
                    <h6>Henüz çalışma saatleri fotoğrafı yüklenmemiş</h6>
                    <p>Yukarıdaki formdan bir fotoğraf yükleyebilirsiniz.</p>
                  </div>
                <?php endif; ?>
              </div>
            </div>
            <!-- Hizmetler -->
            <div class="tab-pane fade" id="hizmet" role="tabpanel">
              <form method="post">
                <div class="mb-3">
                  <label for="hizmet_baslik" class="form-label">Hizmet Başlığı</label>
                  <input type="text" class="form-control" id="hizmet_baslik" name="hizmet_baslik" placeholder="Hizmet başlığı">
                </div>
                <div class="mb-3">
                  <label for="hizmet_aciklama" class="form-label">Açıklama</label>
                  <textarea class="form-control" id="hizmet_aciklama" name="hizmet_aciklama" rows="3" placeholder="Hizmet açıklaması"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Ekle / Güncelle</button>
              </form>
              <hr>
              <h5>Ekli Hizmetler</h5>
              <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle">
                  <thead><tr><th>Başlık</th><th>Açıklama</th></tr></thead>
                  <tbody>
                  <?php if ($hizmetler && $hizmetler->num_rows > 0):
                    while($row = $hizmetler->fetch_assoc()): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($row['baslik']); ?></td>
                      <td><?php echo nl2br(htmlspecialchars($row['aciklama'])); ?></td>
                    </tr>
                  <?php endwhile; else: ?>
                    <tr><td colspan="2">Kayıt yok</td></tr>
                  <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- Footer Ayarları -->
            <div class="tab-pane fade" id="footer" role="tabpanel">
              <form method="post">
                <div class="mb-3">
                  <label for="adres" class="form-label">Adres</label>
                  <input type="text" class="form-control" id="adres" name="adres" value="<?php echo htmlspecialchars($footer['adres']); ?>" placeholder="Adres">
                </div>
                <div class="mb-3">
                  <label for="iletisim_no" class="form-label">İletişim Numarası</label>
                  <input type="text" class="form-control" id="iletisim_no" name="iletisim_no" value="<?php echo htmlspecialchars($footer['iletisim_no']); ?>" placeholder="İletişim numarası">
                </div>
                <div class="mb-3">
                  <label for="mail" class="form-label">Mail</label>
                  <input type="email" class="form-control" id="mail" name="mail" value="<?php echo htmlspecialchars($footer['mail']); ?>" placeholder="Mail adresi">
                </div>
                <div class="mb-3">
                  <label for="hsm_adresi" class="form-label">HSM Adresi</label>
                  <input type="text" class="form-control" id="hsm_adresi" name="hsm_adresi" value="<?php echo htmlspecialchars($footer['hsm_adresi']); ?>" placeholder="HSM adresi">
                </div>
                <div class="mb-3">
                  <label for="tsm_adresi" class="form-label">TSM Adresi</label>
                  <input type="text" class="form-control" id="tsm_adresi" name="tsm_adresi" value="<?php echo htmlspecialchars($footer['tsm_adresi']); ?>" placeholder="TSM adresi">
                </div>
                <div class="mb-3">
                  <label for="copyright_yazisi" class="form-label">Copyright Yazısı</label>
                  <input type="text" class="form-control" id="copyright_yazisi" name="copyright_yazisi" value="<?php echo htmlspecialchars($footer['copyright_yazisi']); ?>" placeholder="Copyright yazısı">
                </div>
                <button type="submit" class="btn btn-primary">Kaydet</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__.'/includes/footer.php'; ?> 
