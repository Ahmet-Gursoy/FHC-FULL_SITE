<?php
if (!defined('ADMIN_SIDEBAR')) die('Erişim engellendi!');

// Kullanıcı rolünü al
$user_role = $_SESSION['role'] ?? '';
?>
<!-- Mobil için menü butonu -->
<button class="btn btn-primary d-md-none position-fixed" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas" style="top: 70px; left: 10px; z-index: 1040;">
  <i class="bi bi-list"></i> ☰
</button>

<!-- Desktop Sidebar -->
<div class="d-none d-md-block">
  <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 220px; min-height: calc(100vh - 56px);">
    <ul class="nav nav-pills flex-column mb-auto">
      <li class="nav-item mb-1">
        <a href="/admin/dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
          📊 Dashboard
        </a>
      </li>
      <?php if (strtolower($user_role) === 'admin'): ?>
      <li class="nav-item mb-1">
        <a href="/admin/site-settings.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'site-settings.php' ? 'active' : ''; ?>">
          ⚙️ Site Ayarları
        </a>
      </li>
      <li class="nav-item mb-1">
        <a href="/admin/user-management.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'user-management.php' ? 'active' : ''; ?>">
          👤 Kullanıcı Yönetimi
        </a>
      </li>
      <?php endif; ?>
      <li class="nav-item mb-1">
        <a href="/admin/staff-settings.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'staff-settings.php' ? 'active' : ''; ?>">
          👥 Çalışan Ayarları
        </a>
      </li>
      <li class="nav-item mb-1">
        <a href="/admin/gallery.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : ''; ?>">
          🖼️ Galeri
        </a>
      </li>
      <li class="nav-item mt-3">
        <a href="/admin/logout.php" class="nav-link text-danger">
          🚪 Çıkış Yap
        </a>
      </li>
    </ul>
    <div class="mt-auto pt-3 border-top">
      <small class="text-muted">
        <strong>Rol:</strong> <?php echo ucfirst($user_role); ?><br>
        <strong>Kullanıcı:</strong> <?php echo $_SESSION['user_id'] ?? 'Bilinmiyor'; ?>
      </small>
    </div>
  </div>
</div>

<!-- Mobil Offcanvas Sidebar -->
<div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
  <div class="offcanvas-header bg-primary text-white">
    <h5 class="offcanvas-title" id="sidebarOffcanvasLabel">Admin Paneli</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-0">
    <ul class="nav nav-pills flex-column">
      <li class="nav-item">
        <a href="/admin/dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
          📊 Dashboard
        </a>
      </li>
      <?php if (strtolower($user_role) === 'admin'): ?>
      <li class="nav-item">
        <a href="/admin/site-settings.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'site-settings.php' ? 'active' : ''; ?>">
          ⚙️ Site Ayarları
        </a>
      </li>
      <li class="nav-item">
        <a href="/admin/user-management.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'user-management.php' ? 'active' : ''; ?>">
          👤 Kullanıcı Yönetimi
        </a>
      </li>
      <?php endif; ?>
      <li class="nav-item">
        <a href="/admin/staff-settings.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'staff-settings.php' ? 'active' : ''; ?>">
          👥 Çalışan Ayarları
        </a>
      </li>
      <li class="nav-item">
        <a href="/admin/gallery.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : ''; ?>">
          🖼️ Galeri
        </a>
      </li>
      <li class="nav-item border-top mt-3 pt-3">
        <div class="px-3 pb-3">
          <small class="text-muted">
            <strong>Rol:</strong> <?php echo ucfirst($user_role); ?><br>
            <strong>Kullanıcı:</strong> <?php echo $_SESSION['user_id'] ?? 'Bilinmiyor'; ?>
          </small>
        </div>
        <a href="/admin/logout.php" class="nav-link text-danger">
          🚪 Çıkış Yap
        </a>
      </li>
    </ul>
  </div>
</div> 