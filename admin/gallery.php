<?php
require_once __DIR__.'/includes/Auth.php';
requireAuth();

// Veritabanı bağlantısı
require_once __DIR__ . '../includes/db.php';
if ($conn->connect_error) {
    die('Veritabanı bağlantı hatası: ' . $conn->connect_error);
}

$alert = '';

// AJAX sıralama güncelleme
if (isset($_POST['ajax_siralama'])) {
    $siralar = json_decode($_POST['siralar'], true);
    if ($siralar) {
        foreach ($siralar as $index => $id) {
            $id = (int)$id;
            $sira = $index + 1;
            $conn->query("UPDATE galeri SET sira=$sira WHERE id=$id");
        }
        echo json_encode(['success' => true, 'message' => 'Sıralama güncellendi']);
        exit;
    }
}

// Fotoğraf silme
if (isset($_GET['sil']) && is_numeric($_GET['sil'])) {
    $id = (int)$_GET['sil'];
    // Fotoğraf dosyasını da sil
    $result = $conn->query("SELECT foto FROM galeri WHERE id=$id");
    if ($result && $row = $result->fetch_assoc()) {
        if (file_exists(__DIR__ . '/' . $row['foto'])) {
            unlink(__DIR__ . '/' . $row['foto']);
        }
    }
    $conn->query("DELETE FROM galeri WHERE id=$id");
    $alert = '<div class="alert alert-success">Fotoğraf başarıyla silindi.</div>';
}

// Durum değiştirme (aktif/pasif)
if (isset($_GET['durum_degistir']) && is_numeric($_GET['durum_degistir'])) {
    $id = (int)$_GET['durum_degistir'];
    $result = $conn->query("SELECT durum FROM galeri WHERE id=$id");
    if ($result && $row = $result->fetch_assoc()) {
        $yeni_durum = ($row['durum'] == 'aktif') ? 'pasif' : 'aktif';
        $conn->query("UPDATE galeri SET durum='$yeni_durum' WHERE id=$id");
        $alert = '<div class="alert alert-success">Durum başarıyla değiştirildi.</div>';
    }
}

// Sıra güncelleme
if (isset($_POST['sira_guncelle'])) {
    foreach ($_POST['sira'] as $id => $sira) {
        $id = (int)$id;
        $sira = (int)$sira;
        $conn->query("UPDATE galeri SET sira=$sira WHERE id=$id");
    }
    $alert = '<div class="alert alert-success">Sıralama güncellendi.</div>';
}

// Fotoğraf ekleme/güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['sira_guncelle']) && !isset($_POST['ajax_siralama'])) {
    $baslik = $conn->real_escape_string($_POST['baslik']);
    $aciklama = $conn->real_escape_string($_POST['aciklama']);
    $sira = (int)$_POST['sira'];
    $durum = $conn->real_escape_string($_POST['durum']);
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto = 'uploads/galeri_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . '/' . $foto);
    }
    
    if ($id > 0) {
        // Güncelleme
        if ($foto) {
            // Eski fotoğrafı sil
            $result = $conn->query("SELECT foto FROM galeri WHERE id=$id");
            if ($result && $row = $result->fetch_assoc()) {
                if (file_exists(__DIR__ . '/' . $row['foto'])) {
                    unlink(__DIR__ . '/' . $row['foto']);
                }
            }
            $conn->query("UPDATE galeri SET baslik='$baslik', aciklama='$aciklama', sira=$sira, durum='$durum', foto='$foto' WHERE id=$id");
        } else {
            $conn->query("UPDATE galeri SET baslik='$baslik', aciklama='$aciklama', sira=$sira, durum='$durum' WHERE id=$id");
        }
        $alert = '<div class="alert alert-success">Fotoğraf bilgileri güncellendi.</div>';
    } else {
        // Ekleme
        if ($foto) {
            $conn->query("INSERT INTO galeri (foto, baslik, aciklama, sira, durum) VALUES ('$foto', '$baslik', '$aciklama', $sira, '$durum')");
            $alert = '<div class="alert alert-success">Yeni fotoğraf eklendi.</div>';
        } else {
            $alert = '<div class="alert alert-danger">Fotoğraf seçmelisiniz.</div>';
        }
    }
}

// Galeri fotoğraflarını çek
$galeri = $conn->query("SELECT * FROM galeri ORDER BY sira ASC, id DESC");

// Düzenleme için fotoğraf bilgilerini çek
$edit_foto = null;
if (isset($_GET['duzenle']) && is_numeric($_GET['duzenle'])) {
    $id = (int)$_GET['duzenle'];
    $result = $conn->query("SELECT * FROM galeri WHERE id=$id");
    if ($result && $result->num_rows > 0) {
        $edit_foto = $result->fetch_assoc();
    }
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
          <h2 class="mb-4">Galeri Yönetimi</h2>
          <?php if ($alert) echo $alert; ?>
          
          <!-- Fotoğraf Ekleme/Düzenleme Formu -->
          <div class="card mb-4">
            <div class="card-header">
              <h5><?php echo $edit_foto ? 'Fotoğraf Düzenle' : 'Yeni Fotoğraf Ekle'; ?></h5>
            </div>
            <div class="card-body">
              <form method="post" enctype="multipart/form-data">
                <?php if ($edit_foto): ?>
                  <input type="hidden" name="id" value="<?php echo $edit_foto['id']; ?>">
                <?php endif; ?>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="baslik" class="form-label">Başlık</label>
                    <input type="text" class="form-control" id="baslik" name="baslik" value="<?php echo $edit_foto ? htmlspecialchars($edit_foto['baslik']) : ''; ?>" placeholder="Fotoğraf başlığı">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label for="sira" class="form-label">Sıra</label>
                    <input type="number" class="form-control" id="sira" name="sira" value="<?php echo $edit_foto ? $edit_foto['sira'] : '0'; ?>" min="0">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label for="durum" class="form-label">Durum</label>
                    <select class="form-control" id="durum" name="durum" required>
                      <option value="aktif" <?php echo ($edit_foto && $edit_foto['durum'] == 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                      <option value="pasif" <?php echo ($edit_foto && $edit_foto['durum'] == 'pasif') ? 'selected' : ''; ?>>Pasif</option>
                    </select>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="aciklama" class="form-label">Açıklama</label>
                  <textarea class="form-control" id="aciklama" name="aciklama" rows="3" placeholder="Fotoğraf açıklaması"><?php echo $edit_foto ? htmlspecialchars($edit_foto['aciklama']) : ''; ?></textarea>
                </div>
                <div class="mb-3">
                  <label for="foto" class="form-label">Fotoğraf</label>
                  <input type="file" class="form-control" id="foto" name="foto" accept="image/*" <?php echo !$edit_foto ? 'required' : ''; ?>>
                  <?php if ($edit_foto && $edit_foto['foto']): ?>
                    <div class="mt-2">
                      <img src="<?php echo $edit_foto['foto']; ?>" alt="Mevcut Foto" style="max-width: 200px; max-height: 150px; border-radius: 8px;">
                    </div>
                  <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary"><?php echo $edit_foto ? 'Güncelle' : 'Ekle'; ?></button>
                <?php if ($edit_foto): ?>
                  <a href="gallery.php" class="btn btn-secondary">İptal</a>
                <?php endif; ?>
              </form>
            </div>
          </div>

          <!-- Sıralama Bilgi Kartı -->
          <div class="alert alert-info">
            <h5><i class="bi bi-info-circle"></i> 📋 Sürükle-Bırak Sıralama</h5>
            <p class="mb-0">Fotoğrafları sürükleyerek sıralamalarını değiştirebilirsiniz. Değişiklikler otomatik olarak kaydedilir.</p>
          </div>

          <!-- Galeri Grid - Sürüklenebilir -->
          <div id="sortable-gallery" class="row">
            <?php if ($galeri && $galeri->num_rows > 0):
              while($row = $galeri->fetch_assoc()): ?>
              <div class="col-lg-3 col-md-4 col-sm-6 mb-4 sortable-item" data-id="<?php echo $row['id']; ?>">
                <div class="card h-100 <?php echo $row['durum'] == 'pasif' ? 'border-secondary' : ''; ?> sortable-card">
                  <div class="drag-handle">
                    <i class="bi bi-grip-vertical"></i> ↕️ Sürükle
                  </div>
                  <div style="height: 200px; overflow: hidden;">
                    <img src="<?php echo $row['foto']; ?>" class="card-img-top" style="width: 100%; height: 100%; object-fit: cover;" alt="<?php echo htmlspecialchars($row['baslik']); ?>">
                  </div>
                  <div class="card-body d-flex flex-column">
                    <h6 class="card-title"><?php echo htmlspecialchars($row['baslik'] ?: 'Başlıksız'); ?></h6>
                    <?php if ($row['aciklama']): ?>
                      <p class="card-text small text-muted"><?php echo nl2br(htmlspecialchars(substr($row['aciklama'], 0, 60) . (strlen($row['aciklama']) > 60 ? '...' : ''))); ?></p>
                    <?php endif; ?>
                    <div class="mt-auto">
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">Sıra: <?php echo $row['sira']; ?></small>
                        <span class="badge <?php echo $row['durum'] == 'aktif' ? 'bg-success' : 'bg-secondary'; ?>">
                          <?php echo ucfirst($row['durum']); ?>
                        </span>
                      </div>
                      <div class="btn-group w-100" role="group">
                        <a href="?duzenle=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                        <a href="?durum_degistir=<?php echo $row['id']; ?>" class="btn btn-sm <?php echo $row['durum'] == 'aktif' ? 'btn-secondary' : 'btn-success'; ?>">
                          <?php echo $row['durum'] == 'aktif' ? 'Pasif Yap' : 'Aktif Yap'; ?>
                        </a>
                        <a href="?sil=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinizden emin misiniz?')">Sil</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endwhile; else: ?>
              <div class="col-12">
                <div class="alert alert-info text-center">
                  <h5>Henüz fotoğraf eklenmemiş</h5>
                  <p>Galeriye ilk fotoğrafınızı eklemek için yukarıdaki formu kullanın.</p>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<!-- Sürükle-Bırak CSS -->
<style>
.sortable-card {
  cursor: move;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.sortable-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}
.drag-handle {
  background: #007bff;
  color: white;
  text-align: center;
  padding: 5px;
  font-size: 12px;
  cursor: grab;
}
.drag-handle:active {
  cursor: grabbing;
}
.sortable-ghost {
  opacity: 0.5;
}
.sortable-chosen {
  transform: rotate(5deg);
}
</style>

<!-- Sürükle-Bırak JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortableGallery = document.getElementById('sortable-gallery');
    
    if (sortableGallery) {
        const sortable = Sortable.create(sortableGallery, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            handle: '.drag-handle',
            onEnd: function(evt) {
                // Yeni sıralamayı al
                const items = sortableGallery.querySelectorAll('.sortable-item');
                const newOrder = Array.from(items).map(item => item.dataset.id);
                
                // AJAX ile sıralamayı güncelle
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Başarı mesajı göster
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-success alert-dismissible fade show';
                            alertDiv.innerHTML = `
                                ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            `;
                            document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('.card-body').firstChild);
                            
                            // 3 saniye sonra mesajı kaldır
                            setTimeout(() => {
                                if (alertDiv.parentNode) {
                                    alertDiv.remove();
                                }
                            }, 3000);
                        }
                    }
                };
                xhr.send('ajax_siralama=1&siralar=' + JSON.stringify(newOrder));
            }
        });
    }
});
</script>

<?php include __DIR__.'/includes/footer.php'; ?> 
