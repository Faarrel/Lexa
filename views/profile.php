  <?php if(!$user || $user['role'] !== 'user'){ header('Location:?page=login'); exit; } ?>
  
  <!-- Profile Header -->
  <div class="profile-header">
    <div class="profile-avatar-large">
      <?php if(isset($user['avatar']) && $user['avatar']): ?>
        <img src="<?=h($user['avatar'])?>" alt="Avatar">
      <?php else: ?>
        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/>
          <path d="M6 21v-2a4 4 0 014-4h4a4 4 0 014 4v2" stroke="currentColor" stroke-width="2"/>
        </svg>
      <?php endif; ?>
    </div>
    <h2 class="profile-name"><?=h($user['name'])?></h2>
    <p class="profile-email"><?=h($user['email'])?></p>
  </div>

  <!-- Profile Menu -->
  <div class="profile-menu">
    <div class="profile-section">
      <h3 class="profile-section-title">Akun</h3>
      <a href="?page=edit_profile" class="profile-menu-item">
        <div class="profile-menu-icon">👤</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Edit Profil</div>
          <div class="profile-menu-desc">Ubah nama, email, dan foto profil</div>
        </div>
        <div class="profile-menu-arrow">›</div>
      </a>
      <a href="?page=change_password" class="profile-menu-item">
        <div class="profile-menu-icon">🔒</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Ubah Password</div>
          <div class="profile-menu-desc">Perbarui kata sandi akun Anda</div>
        </div>
        <div class="profile-menu-arrow">›</div>
      </a>
    </div>

    <div class="profile-section">
      <h3 class="profile-section-title">Aktivitas</h3>
      <a href="?page=riwayat_konsultasi" class="profile-menu-item">
        <div class="profile-menu-icon">💬</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Riwayat Konsultasi</div>
          <div class="profile-menu-desc">Lihat konsultasi yang pernah dilakukan</div>
        </div>
        <div class="profile-menu-arrow">›</div>
      </a>
      <a href="?page=dokumen_saya" class="profile-menu-item">
        <div class="profile-menu-icon">📄</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Dokumen Saya</div>
          <div class="profile-menu-desc">Akses dokumen dan akta Anda</div>
        </div>
        <div class="profile-menu-arrow">›</div>
      </a>
    </div>

    <div class="profile-section">
      <h3 class="profile-section-title">Lainnya</h3>
      <a href="?page=bantuan" class="profile-menu-item">
        <div class="profile-menu-icon">❓</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Bantuan</div>
          <div class="profile-menu-desc">FAQ dan panduan penggunaan</div>
        </div>
        <div class="profile-menu-arrow">›</div>
      </a>
      <a href="?page=tentang" class="profile-menu-item">
        <div class="profile-menu-icon">ℹ️</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Tentang Aplikasi</div>
          <div class="profile-menu-desc">Versi 1.0.0</div>
        </div>
        <div class="profile-menu-arrow">›</div>
      </a>
    </div>

    <div class="profile-section">
      <button onclick="showLogoutModal()" class="profile-logout-btn">
        <span>🚪</span>
        <span>Keluar</span>
      </button>
    </div>
  </div>

  <!-- Logout Modal -->
  <div id="logoutModal" class="modal">
    <div class="modal-overlay" onclick="hideLogoutModal()"></div>
    <div class="modal-content">
      <div class="modal-header">
        <h3>🚪 Keluar dari Akun?</h3>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin keluar dari akun?</p>
        <p style="color: var(--text-light); font-size: 14px; margin-top: 8px;">Anda perlu login kembali untuk mengakses aplikasi.</p>
      </div>
      <div class="modal-footer">
        <button onclick="hideLogoutModal()" class="btn-secondary">Batal</button>
        <form method="post" style="display: inline;">
          <input type="hidden" name="action" value="logout">
          <button type="submit" class="btn-danger">Keluar</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    function showLogoutModal() {
      document.getElementById('logoutModal').classList.add('show');
    }
    
    function hideLogoutModal() {
      document.getElementById('logoutModal').classList.remove('show');
    }
    
    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
      if(e.key === 'Escape') {
        hideLogoutModal();
      }
    });
  </script>


