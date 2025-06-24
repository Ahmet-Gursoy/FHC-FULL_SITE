<?php
session_start();
function requireAuth($roles = []) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /admin/login.php');
        exit;
    }
    if (!empty($roles)) {
        $userRole = isset($_SESSION['role']) ? trim(strtolower($_SESSION['role'])) : '';
        $allowedRoles = array_map('strtolower', $roles);
        if (!$userRole || !in_array($userRole, $allowedRoles)) {
            // Şık uyarı ve 5 saniye geri sayım ile dashboard'a yönlendirme
            echo '<!DOCTYPE html><html lang="tr"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
            echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
            echo '<title>Erişim Yetkiniz Yok</title></head><body class="bg-light d-flex align-items-center" style="min-height:100vh;">';
            echo '<div class="container"><div class="row justify-content-center"><div class="col-md-6"><div class="alert alert-danger text-center mt-5">';
            echo '<h4 class="mb-3">Bu sayfaya erişim yetkiniz yok!</h4>';
            echo '<p>Mevcut rolünüz: <strong>' . htmlspecialchars($_SESSION['role'] ?? 'Tanımsız') . '</strong></p>';
            echo '<p>Gerekli roller: <strong>' . implode(', ', $roles) . '</strong></p>';
            echo '<p>5 saniye içinde <a href="/admin/dashboard" class="alert-link">Dashboard</a> sayfasına yönlendirileceksiniz.</p>';
            echo '<div id="countdown" class="display-5 fw-bold">5</div>';
            echo '</div></div></div></div>';
            echo '<script>
            let seconds = 5;
            const countdown = document.getElementById("countdown");
            setInterval(function() {
              seconds--;
              if (seconds > 0) {
                countdown.textContent = seconds;
              } else {
                window.location.href = "/admin/dashboard";
              }
            }, 1000);
            </script>';
            echo '</body></html>';
            exit;
        }
    }
} 