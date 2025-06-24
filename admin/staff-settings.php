<?php
require_once __DIR__.'/includes/Auth.php';
requireAuth();

$conn = new mysqli('localhost', 'root', '', 'fhc');
if ($conn->connect_error) {
    die('Veritabanı bağlantı hatası: ' . $conn->connect_error);
}

$alert = '';

if (isset($_GET['sil']) && is_numeric($_GET['sil'])) {
    $id = (int)$_GET['sil'];
    $conn->query("DELETE FROM calisanlar WHERE id=$id");
    $alert = '<div class="alert alert-success">Çalışan başarıyla silindi.</div>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = $conn->real_escape_string($_POST['ad']);
    $soyad = $conn->real_escape_string($_POST['soyad']);
    $cinsiyet = $conn->real_escape_string($_POST['cinsiyet']);
    $pozisyon = $conn->real_escape_string($_POST['pozisyon']);
    $departman = $conn->real_escape_string($_POST['departman']);
    $telefon = $conn->real_escape_string($_POST['telefon']);
    $email = $conn->real_escape_string($_POST['email']);
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto = 'uploads/calisan-' . time() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . '/' . $foto);
    } else if ($id === 0) {
        $prefix = '/uploads/';
        if ($departman === 'aile_hekimleri') {
            $foto = $prefix . ($cinsiyet === 'erkek' ? 'erkekdoktor.jpg' : 'kadındoktor.jpg');
        } else if ($departman === 'aile_sagligi_calisanlari') {
            $foto = $prefix . ($cinsiyet === 'erkek' ? 'staff2.png' : 'staff.png');
        } else if ($departman === 'yardimci_personel') {
            $foto = $prefix . ($cinsiyet === 'erkek' ? 'staff3.png' : 'staff4.jpg');
        } else {
            $foto = $prefix . 'default.jpg';
        }
    }

    if ($id > 0) {
        if ($foto) {
            $conn->query("UPDATE calisanlar SET ad='$ad', soyad='$soyad', cinsiyet='$cinsiyet', pozisyon='$pozisyon', departman='$departman', telefon='$telefon', email='$email', foto='$foto' WHERE id=$id");
        } else {
            $conn->query("UPDATE calisanlar SET ad='$ad', soyad='$soyad', cinsiyet='$cinsiyet', pozisyon='$pozisyon', departman='$departman', telefon='$telefon', email='$email' WHERE id=$id");
        }
        $alert = '<div class="alert alert-success">Çalışan bilgileri güncellendi.</div>';
    } else {
        if ($foto) {
            $conn->query("INSERT INTO calisanlar (ad, soyad, cinsiyet, pozisyon, departman, telefon, email, foto) VALUES ('$ad', '$soyad', '$cinsiyet', '$pozisyon', '$departman', '$telefon', '$email', '$foto')");
        } else {
            $conn->query("INSERT INTO calisanlar (ad, soyad, cinsiyet, pozisyon, departman, telefon, email) VALUES ('$ad', '$soyad', '$cinsiyet', '$pozisyon', '$departman', '$telefon', '$email')");
        }
        $alert = '<div class="alert alert-success">Yeni çalışan eklendi.</div>';
    }
}

$aile_hekimleri = $conn->query("SELECT * FROM calisanlar WHERE departman='aile_hekimleri' ORDER BY ad, soyad");
$aile_sagligi = $conn->query("SELECT * FROM calisanlar WHERE departman='aile_sagligi_calisanlari' ORDER BY ad, soyad");
$yardimci = $conn->query("SELECT * FROM calisanlar WHERE departman='yardimci_personel' ORDER BY ad, soyad");

$edit_calisan = null;
if (isset($_GET['duzenle']) && is_numeric($_GET['duzenle'])) {
    $id = (int)$_GET['duzenle'];
    $result = $conn->query("SELECT * FROM calisanlar WHERE id=$id");
    if ($result && $result->num_rows > 0) {
        $edit_calisan = $result->fetch_assoc();
    }
}

$departman_isimleri = [
    'aile_hekimleri' => 'Aile Hekimleri',
    'aile_sagligi_calisanlari' => 'Aile Sağlığı Çalışanları',
    'yardimci_personel' => 'Yardımcı Personellerimiz'
];

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
          <h2 class="mb-4">Çalışan Ayarları</h2>
          <?php if ($alert) echo $alert; ?>
          
          <!-- Çalışan Ekleme/Düzenleme Formu -->
          <div class="card mb-4">
            <div class="card-header">
              <h5><?php echo $edit_calisan ? 'Çalışan Düzenle' : 'Yeni Çalışan Ekle'; ?></h5>
            </div>
            <div class="card-body">
              <form method="post" enctype="multipart/form-data">
                <?php if ($edit_calisan): ?>
                  <input type="hidden" name="id" value="<?php echo $edit_calisan['id']; ?>">
                <?php endif; ?>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="ad" class="form-label">Ad</label>
                    <input type="text" class="form-control" id="ad" name="ad" value="<?php echo $edit_calisan ? htmlspecialchars($edit_calisan['ad']) : ''; ?>" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="soyad" class="form-label">Soyad</label>
                    <input type="text" class="form-control" id="soyad" name="soyad" value="<?php echo $edit_calisan ? htmlspecialchars($edit_calisan['soyad']) : ''; ?>" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="pozisyon" class="form-label">Pozisyon</label>
                    <input type="text" class="form-control" id="pozisyon" name="pozisyon" value="<?php echo $edit_calisan ? htmlspecialchars($edit_calisan['pozisyon']) : ''; ?>" placeholder="Örn: Doktor, Hemşire, Sekreter">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="departman" class="form-label">Departman</label>
                    <select class="form-control" id="departman" name="departman" required>
                      <option value="">Departman Seçin</option>
                      <?php foreach ($departman_isimleri as $key => $isim): ?>
                        <option value="<?php echo $key; ?>" <?php echo ($edit_calisan && $edit_calisan['departman'] == $key) ? 'selected' : ''; ?>>
                          <?php echo $isim; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="telefon" class="form-label">Telefon</label>
                    <input type="text" class="form-control" id="telefon" name="telefon" value="<?php echo $edit_calisan ? htmlspecialchars($edit_calisan['telefon']) : ''; ?>" placeholder="Örn: 0555 123 45 67">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $edit_calisan ? htmlspecialchars($edit_calisan['email']) : ''; ?>" placeholder="ornek@email.com">
                  </div>
                </div>
                <div class="form-group">
                 <label for="cinsiyet">Cinsiyet</label>
                  <select name="cinsiyet" id="cinsiyet" class="form-control" required>
                   <option value="">Seçiniz</option>
                    <option value="kadın">Kadın</option>
                   <option value="erkek">Erkek</option>
                    </select>
                    </div>

                <div class="mb-3">
                  <label for="foto" class="form-label">Fotoğraf</label>
                  <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                  <?php if ($edit_calisan && $edit_calisan['foto']): ?>
                    <div class="mt-2">
                      <img src="<?php echo $edit_calisan['foto']; ?>" alt="Mevcut Foto" style="max-width: 100px; max-height: 100px;">
                    </div>
                  <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary"><?php echo $edit_calisan ? 'Güncelle' : 'Ekle'; ?></button>
                <?php if ($edit_calisan): ?>
                  <a href="staff-settings.php" class="btn btn-secondary">İptal</a>
                <?php endif; ?>
              </form>
            </div>
          </div>

          <!-- Çalışanlar Listesi -->
          <ul class="nav nav-tabs mb-3" id="staffTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="aile-hekimleri-tab" data-bs-toggle="tab" data-bs-target="#aile-hekimleri" type="button" role="tab">Aile Hekimleri</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="aile-sagligi-tab" data-bs-toggle="tab" data-bs-target="#aile-sagligi" type="button" role="tab">Aile Sağlığı Çalışanları</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="yardimci-tab" data-bs-toggle="tab" data-bs-target="#yardimci" type="button" role="tab">Yardımcı Personel</button>
            </li>
          </ul>
          
          <div class="tab-content" id="staffTabContent">
            <!-- Aile Hekimleri -->
            <div class="tab-pane fade show active" id="aile-hekimleri" role="tabpanel">
              <div class="table-responsive">
                <table class="table table-bordered table-striped">
                  <thead class="table-dark">
                    <tr>
                      <th>Foto</th>
                      <th>Ad Soyad</th>
                      <th>Pozisyon</th>
                      <th>Telefon</th>
                      <th>E-mail</th>
                      <th>İşlemler</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($aile_hekimleri && $aile_hekimleri->num_rows > 0):
                      while($row = $aile_hekimleri->fetch_assoc()): ?>
                      <tr>
                        <td>
                          <?php if ($row['foto']): ?>
                            <img src="<?php echo $row['foto']; ?>" alt="Foto" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                          <?php else: ?>
                            <div style="width: 50px; height: 50px; background: #ddd; border-radius: 50%; display: flex; align-items: center; justify-content: center;">👤</div>
                          <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['ad'] . ' ' . $row['soyad']); ?></td>
                        <td><?php echo htmlspecialchars($row['pozisyon']); ?></td>
                        <td><?php echo htmlspecialchars($row['telefon']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                          <a href="?duzenle=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                          <a href="?sil=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinizden emin misiniz?')">Sil</a>
                        </td>
                      </tr>
                    <?php endwhile; else: ?>
                      <tr><td colspan="6" class="text-center">Kayıt bulunamadı</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
            
            <!-- Aile Sağlığı Çalışanları -->
            <div class="tab-pane fade" id="aile-sagligi" role="tabpanel">
              <div class="table-responsive">
                <table class="table table-bordered table-striped">
                  <thead class="table-dark">
                    <tr>
                      <th>Foto</th>
                      <th>Ad Soyad</th>
                      <th>Pozisyon</th>
                      <th>Telefon</th>
                      <th>E-mail</th>
                      <th>İşlemler</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($aile_sagligi && $aile_sagligi->num_rows > 0):
                      while($row = $aile_sagligi->fetch_assoc()): ?>
                      <tr>
                        <td>
                          <?php if ($row['foto']): ?>
                            <img src="/<?php echo $row['foto']; ?>" alt="Foto" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                          <?php else: ?>
                            <div style="width: 50px; height: 50px; background: #ddd; border-radius: 50%; display: flex; align-items: center; justify-content: center;">👤</div>
                          <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['ad'] . ' ' . $row['soyad']); ?></td>
                        <td><?php echo htmlspecialchars($row['pozisyon']); ?></td>
                        <td><?php echo htmlspecialchars($row['telefon']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                          <a href="?duzenle=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                          <a href="?sil=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinizden emin misiniz?')">Sil</a>
                        </td>
                      </tr>
                    <?php endwhile; else: ?>
                      <tr><td colspan="6" class="text-center">Kayıt bulunamadı</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
            
            <!-- Yardımcı Personel -->
            <div class="tab-pane fade" id="yardimci" role="tabpanel">
              <div class="table-responsive">
                <table class="table table-bordered table-striped">
                  <thead class="table-dark">
                    <tr>
                      <th>Foto</th>
                      <th>Ad Soyad</th>
                      <th>Pozisyon</th>
                      <th>Telefon</th>
                      <th>E-mail</th>
                      <th>İşlemler</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($yardimci && $yardimci->num_rows > 0):
                      while($row = $yardimci->fetch_assoc()): ?>
                      <tr>
                        <td>
                          <?php if ($row['foto']): ?>
                            <img src="/<?php echo $row['foto']; ?>" alt="Foto" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                          <?php else: ?>
                            <div style="width: 50px; height: 50px; background: #ddd; border-radius: 50%; display: flex; align-items: center; justify-content: center;">👤</div>
                          <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['ad'] . ' ' . $row['soyad']); ?></td>
                        <td><?php echo htmlspecialchars($row['pozisyon']); ?></td>
                        <td><?php echo htmlspecialchars($row['telefon']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                          <a href="?duzenle=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                          <a href="?sil=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinizden emin misiniz?')">Sil</a>
                        </td>
                      </tr>
                    <?php endwhile; else: ?>
                      <tr><td colspan="6" class="text-center">Kayıt bulunamadı</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__.'/includes/footer.php'; ?> 
