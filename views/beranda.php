  <?php if(!$user){ header('Location:?page=login'); exit; } ?>
  
  <!-- Welcome Section -->
  <div style="padding: 20px 0; text-align: left;">
    <h2 style="margin: 0 0 4px 0; font-size: 24px; font-weight: 600; color: var(--text-dark);">
      Selamat Datang,
    </h2>
    <h3 style="margin: 0; font-size: 20px; font-weight: 500; color: var(--text-dark);">
      <?=h($user['name'])?>
    </h3>
  </div>

  <!-- Main Cards -->
  <div class="beranda-cards">
    <!-- Chatbot Card -->
    <div class="beranda-card chatbot-card">
      <div class="beranda-card-icon">
        <svg width="80" height="80" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="50" cy="50" r="40" fill="#E8F5F1" stroke="#2DD4BF" stroke-width="2"/>
          <circle cx="40" cy="45" r="4" fill="#0F766E"/>
          <circle cx="60" cy="45" r="4" fill="#0F766E"/>
          <path d="M 35 60 Q 50 70 65 60" stroke="#0F766E" stroke-width="2" fill="none"/>
          <rect x="45" y="20" width="10" height="8" rx="2" fill="#2DD4BF"/>
          <circle cx="50" cy="23" r="3" fill="#fff"/>
        </svg>
      </div>
      <a href="?page=chatbot" class="beranda-card-btn">
        <span>💬</span>
        <span>Mulai Konsultasi Chatbot</span>
      </a>
    </div>

    <!-- Status Akta Card -->
    <div class="beranda-card status-card">
      <a href="?page=status_akta" class="beranda-card-link">
        <div class="beranda-card-icon-small">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 11l3 3L22 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" stroke="currentColor" stroke-width="2"/>
          </svg>
        </div>
        <span>Status Akta Terbaru</span>
      </a>
    </div>

    <!-- Pengurusan Akta Card -->
    <div class="beranda-card pengurusan-card">
      <a href="?page=pengurusan_akta" class="beranda-card-link">
        <div class="beranda-card-icon-small">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" stroke="currentColor" stroke-width="2"/>
            <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" stroke="currentColor" stroke-width="2"/>
          </svg>
        </div>
        <span>Pengurusan Akta Jual Beli - Tahap Verifikasi Dokumen</span>
      </a>
    </div>
  </div>


