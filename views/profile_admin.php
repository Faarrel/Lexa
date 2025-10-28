  <?php if(!$user || $user['role'] !== 'admin'){ header('Location:?page=login'); exit; } ?>
  
  <!-- Profile Header -->
  <div class="profile-header">
    <div class="profile-avatar-large">
      <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="12" cy="12" r="3" stroke="white" stroke-width="2"/>
        <path d="M12 1v6m0 6v6M5.64 5.64l4.24 4.25m4.25 4.24l4.24 4.25M1 12h6m6 0h6M5.64 18.36l4.24-4.24m4.25-4.25l4.24-4.24" stroke="white" stroke-width="2"/>
      </svg>
    </div>
    <h2 class="profile-name"><?=h($user['name'])?></h2>
    <p class="profile-email">Administrator</p>
    <div class="admin-badge">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 15l-3 3-3-3" stroke="currentColor" stroke-width="2"/>
        <path d="M12 2v13" stroke="currentColor" stroke-width="2"/>
      </svg>
      <span>Super Admin</span>
    </div>
  </div>

  <!-- Profile Menu -->
  <div class="profile-menu">
    <div class="profile-section">
      <h3 class="profile-section-title">Akun</h3>
      <a href="?page=edit_profile_admin" class="profile-menu-item">
        <div class="profile-menu-icon">👤</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Edit Profil</div>
          <div class="profile-menu-desc">Ubah nama dan informasi akun</div>
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
      <h3 class="profile-section-title">Manajemen</h3>
      <a href="?page=admin" class="profile-menu-item">
        <div class="profile-menu-icon">⚙️</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Kelola Notaris</div>
          <div class="profile-menu-desc">Tambah, edit, dan hapus notaris</div>
        </div>
        <div class="profile-menu-arrow">›</div>
      </a>
      <a href="?page=users_management" class="profile-menu-item">
        <div class="profile-menu-icon">👥</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Kelola Users</div>
          <div class="profile-menu-desc">Manajemen pengguna aplikasi</div>
        </div>
        <div class="profile-menu-arrow">›</div>
      </a>
      <a href="?page=system_settings" class="profile-menu-item">
        <div class="profile-menu-icon">🛠️</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Pengaturan Sistem</div>
          <div class="profile-menu-desc">Konfigurasi aplikasi</div>
        </div>
        <div class="profile-menu-arrow">›</div>
      </a>
    </div>

    <div class="profile-section">
      <h3 class="profile-section-title">Lainnya</h3>
      <a href="?page=logs" class="profile-menu-item">
        <div class="profile-menu-icon">📋</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Log Aktivitas</div>
          <div class="profile-menu-desc">Riwayat aktivitas sistem</div>
        </div>
        <div class="profile-menu-arrow">›</div>
      </a>
      <a href="?page=bantuan_admin" class="profile-menu-item">
        <div class="profile-menu-icon">❓</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Bantuan</div>
          <div class="profile-menu-desc">Panduan administrator</div>
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
        <p>Apakah Anda yakin ingin keluar dari akun administrator?</p>
        <p style="color: var(--text-light); font-size: 14px; margin-top: 8px;">Anda perlu login kembali untuk mengakses panel admin.</p>
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


