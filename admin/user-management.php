<?php
require_once __DIR__.'/includes/Auth.php';
requireAuth(['admin']);

// Veritabanı bağlantısı
require_once __DIR__ . '../includes/db.php';
if ($conn->connect_error) {
    die('Veritabanı bağlantı hatası: ' . $conn->connect_error);
}

$alert = '';

// Kullanıcı silme
if (isset($_GET['sil']) && is_numeric($_GET['sil'])) {
    $id = (int)$_GET['sil'];
    // Kendi hesabını silemesin
    if ($id != $_SESSION['user_id']) {
        $conn->query("DELETE FROM users WHERE id=$id");
        $alert = '<div class="alert alert-success">Kullanıcı başarıyla silindi.</div>';
    } else {
        $alert = '<div class="alert alert-danger">Kendi hesabınızı silemezsiniz!</div>';
    }
}

// Kullanıcı aktif/pasif durumu değiştirme
if (isset($_GET['durum_degistir']) && is_numeric($_GET['durum_degistir'])) {
    $id = (int)$_GET['durum_degistir'];
    $result = $conn->query("SELECT aktif FROM users WHERE id=$id");
    if ($result && $row = $result->fetch_assoc()) {
        $yeni_durum = ($row['aktif'] == 1) ? 0 : 1;
        $conn->query("UPDATE users SET aktif=$yeni_durum WHERE id=$id");
        $durum_text = $yeni_durum ? 'aktif' : 'pasif';
        $alert = '<div class="alert alert-success">Kullanıcı durumu '.$durum_text.' olarak değiştirildi.</div>';
    }
}

// Kullanıcı ekleme/güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $role = $conn->real_escape_string($_POST['role']);
    $aktif = isset($_POST['aktif']) ? 1 : 0;
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    if ($id > 0) {
        // Güncelleme
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET username='$username', password='$hashed_password', role='$role', aktif=$aktif WHERE id=$id");
        } else {
            $conn->query("UPDATE users SET username='$username', role='$role', aktif=$aktif WHERE id=$id");
        }
        $alert = '<div class="alert alert-success">Kullanıcı bilgileri güncellendi.</div>';
    } else {
        // Ekleme
        if (!empty($password)) {
            // Username kontrolü
            $check = $conn->query("SELECT id FROM users WHERE username='$username'");
            if ($check && $check->num_rows > 0) {
                $alert = '<div class="alert alert-danger">Bu kullanıcı adı zaten mevcut!</div>';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $conn->query("INSERT INTO users (username, password, role, aktif) VALUES ('$username', '$hashed_password', '$role', $aktif)");
                $alert = '<div class="alert alert-success">Yeni kullanıcı eklendi.</div>';
            }
        } else {
            $alert = '<div class="alert alert-danger">Şifre alanı boş olamaz!</div>';
        }
    }
}

// Kullanıcıları çek
$users = $conn->query("SELECT id, username, role, aktif, created_at FROM users ORDER BY id DESC");

// Düzenleme için kullanıcı bilgilerini çek
$edit_user = null;
if (isset($_GET['duzenle']) && is_numeric($_GET['duzenle'])) {
    $id = (int)$_GET['duzenle'];
    $result = $conn->query("SELECT * FROM users WHERE id=$id");
    if ($result && $result->num_rows > 0) {
        $edit_user = $result->fetch_assoc();
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
          <h2 class="mb-4">👤 Kullanıcı Yönetimi</h2>
          <?php if ($alert) echo $alert; ?>
          
          <!-- Kullanıcı Ekleme/Düzenleme Formu -->
          <div class="card mb-4">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">
                <i class="bi bi-person-plus"></i> 
                <?php echo $edit_user ? 'Kullanıcı Düzenle' : 'Yeni Kullanıcı Ekle'; ?>
              </h5>
            </div>
            <div class="card-body">
              <form method="post">
                <?php if ($edit_user): ?>
                  <input type="hidden" name="id" value="<?php echo $edit_user['id']; ?>">
                <?php endif; ?>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="username" class="form-label">Kullanıcı Adı</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           value="<?php echo $edit_user ? htmlspecialchars($edit_user['username']) : ''; ?>" 
                           placeholder="Kullanıcı adı" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">
                      Şifre <?php echo $edit_user ? '(Boş bırakırsanız değişmez)' : ''; ?>
                    </label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Şifre" <?php echo !$edit_user ? 'required' : ''; ?>>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="role" class="form-label">Rol</label>
                    <select class="form-control" id="role" name="role" required>
                      <option value="">Rol Seçin</option>
                      <option value="admin" <?php echo ($edit_user && $edit_user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                      <option value="editor" <?php echo ($edit_user && $edit_user['role'] == 'editor') ? 'selected' : ''; ?>>Editör</option>
                      <option value="authority" <?php echo ($edit_user && $edit_user['role'] == 'authority') ? 'selected' : ''; ?>>Yetkili</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Durum</label>
                    <div class="form-check mt-2">
                      <input class="form-check-input" type="checkbox" id="aktif" name="aktif" 
                             <?php echo (!$edit_user || $edit_user['aktif'] == 1) ? 'checked' : ''; ?>>
                      <label class="form-check-label" for="aktif">
                        Aktif Kullanıcı
                      </label>
                    </div>
                  </div>
                </div>
                <div class="d-flex gap-2">
                  <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> <?php echo $edit_user ? 'Güncelle' : 'Ekle'; ?>
                  </button>
                  <?php if ($edit_user): ?>
                    <a href="user-management.php" class="btn btn-secondary">
                      <i class="bi bi-x-circle"></i> İptal
                    </a>
                  <?php endif; ?>
                </div>
              </form>
            </div>
          </div>

          <!-- Kullanıcı Listesi -->
          <div class="card">
            <div class="card-header bg-info text-white">
              <h5 class="mb-0">
                <i class="bi bi-people"></i> Kayıtlı Kullanıcılar
              </h5>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                                     <thead class="table-dark">
                     <tr>
                       <th style="width: 60px;">ID</th>
                       <th style="width: 150px;">Kullanıcı Adı</th>
                       <th style="width: 100px;">Rol</th>
                       <th style="width: 80px;">Durum</th>
                       <th style="width: 120px;" class="d-none d-md-table-cell">Kayıt Tarihi</th>
                       <th style="width: 200px;">İşlemler</th>
                     </tr>
                   </thead>
                  <tbody>
                    <?php if ($users && $users->num_rows > 0):
                      while($user = $users->fetch_assoc()): 
                        $is_current_user = ($user['id'] == $_SESSION['user_id']);
                    ?>
                      <tr class="<?php echo $is_current_user ? 'table-warning' : ''; ?>">
                        <td><?php echo $user['id']; ?></td>
                        <td>
                          <?php echo htmlspecialchars($user['username']); ?>
                          <?php if ($is_current_user): ?>
                            <small class="badge bg-warning text-dark">Siz</small>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php 
                          $role_badges = [
                            'admin' => '<span class="badge bg-danger">Admin</span>',
                            'editor' => '<span class="badge bg-primary">Editör</span>',
                            'authority' => '<span class="badge bg-success">Yetkili</span>'
                          ];
                          echo $role_badges[$user['role']] ?? '<span class="badge bg-secondary">'.ucfirst($user['role']).'</span>';
                          ?>
                        </td>
                        <td>
                          <?php if (isset($user['aktif'])): ?>
                            <?php if ($user['aktif'] == 1): ?>
                              <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                              <span class="badge bg-secondary">Pasif</span>
                            <?php endif; ?>
                          <?php else: ?>
                            <span class="badge bg-success">Aktif</span>
                          <?php endif; ?>
                        </td>
                                                 <td class="d-none d-md-table-cell"><?php echo date('d.m.Y H:i', strtotime($user['created_at'])); ?></td>
                        <td>
                          <div class="d-flex flex-wrap gap-1">
                            <a href="?duzenle=<?php echo $user['id']; ?>" 
                               class="btn btn-sm btn-primary" 
                               title="Düzenle" 
                               data-bs-toggle="tooltip">
                              <i class="bi bi-pencil-square"></i>
                              <span class="d-none d-lg-inline"> Düzenle</span>
                            </a>
                            
                            <?php if (isset($user['aktif'])): ?>
                              <a href="?durum_degistir=<?php echo $user['id']; ?>" 
                                 class="btn btn-sm btn-<?php echo $user['aktif'] == 1 ? 'warning' : 'success'; ?>" 
                                 title="<?php echo $user['aktif'] == 1 ? 'Pasif Yap' : 'Aktif Yap'; ?>"
                                 data-bs-toggle="tooltip">
                                <i class="bi bi-<?php echo $user['aktif'] == 1 ? 'pause-circle' : 'play-circle'; ?>"></i>
                                <span class="d-none d-xl-inline"><?php echo $user['aktif'] == 1 ? ' Pasif' : ' Aktif'; ?></span>
                              </a>
                            <?php endif; ?>
                            
                            <?php if (!$is_current_user): ?>
                              <a href="?sil=<?php echo $user['id']; ?>" 
                                 class="btn btn-sm btn-danger" 
                                 title="Sil"
                                 data-bs-toggle="tooltip"
                                 onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')">
                                <i class="bi bi-trash3"></i>
                                <span class="d-none d-xl-inline"> Sil</span>
                              </a>
                            <?php else: ?>
                              <span class="btn btn-sm btn-secondary disabled" title="Kendi hesabınızı silemezsiniz">
                                <i class="bi bi-shield-check"></i>
                                <span class="d-none d-xl-inline"> Korumalı</span>
                              </span>
                            <?php endif; ?>
                          </div>
                        </td>
                      </tr>
                    <?php endwhile; else: ?>
                                             <tr>
                         <td colspan="6" class="text-center py-4">
                           <div class="text-muted">
                             <i class="bi bi-people fs-1"></i>
                             <p class="mb-0">Henüz kullanıcı bulunmuyor</p>
                           </div>
                         </td>
                       </tr>
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

<!-- Tooltip'leri aktif et -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bootstrap tooltip'lerini başlat
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<?php include __DIR__.'/includes/footer.php'; ?> 
