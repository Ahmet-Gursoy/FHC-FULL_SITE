<?php
// Güvenlik ve yetki kontrolü
require_once __DIR__.'/includes/Auth.php';
requireAuth();

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
        <div class="card-body text-center">
          <h2 class="mb-3">Hoşgeldiniz!</h2>
          <p class="lead">Rolünüz: <strong><?php echo isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : ''; ?></strong></p>
          <p>Buradan admin işlemlerinizi gerçekleştirebilirsiniz.</p>
          
          <!-- Hızlı Erişim Kartları -->
          <div class="row mt-4">
            <div class="col-md-4 mb-3">
              <div class="card border-primary">
                <div class="card-body">
                  <h5 class="card-title">👥 Çalışanlar</h5>
                  <p class="card-text">Çalışan bilgilerini yönetin</p>
                  <a href="/admin/staff-settings.php" class="btn btn-primary">Yönet</a>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <div class="card border-success">
                <div class="card-body">
                  <h5 class="card-title">🖼️ Galeri</h5>
                  <p class="card-text">Fotoğrafları düzenleyin</p>
                  <a href="/admin/gallery.php" class="btn btn-success">Yönet</a>
                </div>
              </div>
            </div>
            <?php if (strtolower($_SESSION['role'] ?? '') === 'admin'): ?>
            <div class="col-md-4 mb-3">
              <div class="card border-warning">
                <div class="card-body">
                  <h5 class="card-title">⚙️ Site Ayarları</h5>
                  <p class="card-text">Site yapılandırmasını düzenleyin</p>
                  <a href="/admin/site-settings.php" class="btn btn-warning">Yönet</a>
                </div>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__.'/includes/footer.php'; ?> 