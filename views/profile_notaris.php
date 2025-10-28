  <?php if(!$user || $user['role'] !== 'notaris'){ header('Location:?page=login'); exit; } ?>
  
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
    <p class="profile-email">Notaris Professional</p>
    <?php if(isset($user['specialization']) && $user['specialization']): ?>
      <p class="profile-specialization"><?=h($user['specialization'])?></p>
    <?php endif; ?>
    <?php if(isset($user['rating']) && $user['rating']): ?>
      <div class="profile-rating">
        <span>⭐</span>
        <span><?=number_format($user['rating'], 1)?></span>
        <?php if(isset($user['reviews'])): ?>
          <span class="profile-reviews">(<?=$user['reviews']?> ulasan)</span>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Profile Menu -->
  <div class="profile-menu">
    <div class="profile-section">
      <h3 class="profile-section-title">Akun</h3>
      <a href="?page=edit_profile_notaris" class="profile-menu-item">
        <div class="profile-menu-icon">👤</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Edit Profil</div>
          <div class="profile-menu-desc">Ubah informasi profil notaris</div>
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
      <h3 class="profile-section-title">Layanan</h3>
      <a href="?page=kelola_layanan" class="profile-menu-item">
        <div class="profile-menu-icon">💼</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Kelola Layanan & Tarif</div>
          <div class="profile-menu-desc">Atur layanan dan harga</div>
        </div>
        <div class="profile-menu-arrow">›</div>
      </a>
      <a href="?page=jadwal_praktik" class="profile-menu-item">
        <div class="profile-menu-icon">📅</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Jadwal Praktik</div>
          <div class="profile-menu-desc">Atur ketersediaan waktu</div>
        </div>
        <div class="profile-menu-arrow">›</div>
      </a>
    </div>

    <div class="profile-section">
      <h3 class="profile-section-title">Aktivitas</h3>
      <a href="?page=statistik" class="profile-menu-item">
        <div class="profile-menu-icon">📊</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Statistik</div>
          <div class="profile-menu-desc">Lihat performa dan aktivitas</div>
        </div>
        <div class="profile-menu-arrow">›</div>
      </a>
      <a href="?page=riwayat_notaris" class="profile-menu-item">
        <div class="profile-menu-icon">📄</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Riwayat Akta</div>
          <div class="profile-menu-desc">Dokumen dan akta yang telah dibuat</div>
        </div>
        <div class="profile-menu-arrow">›</div>
      </a>
    </div>

    <div class="profile-section">
      <h3 class="profile-section-title">Lainnya</h3>
      <a href="?page=bantuan_notaris" class="profile-menu-item">
        <div class="profile-menu-icon">❓</div>
        <div class="profile-menu-content">
          <div class="profile-menu-label">Bantuan</div>
          <div class="profile-menu-desc">Panduan untuk notaris</div>
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
        <p>Apakah Anda yakin ingin keluar dari akun notaris?</p>
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


