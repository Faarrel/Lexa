  <?php 
  if(!$user || $user['role'] !== 'notaris'){ header('Location:?page=login'); exit; }
  $db = load_db();
  $inboxCount = 0;
  foreach($db['chats'] as $c){
      if(in_array($user['id'], $c['participants'])) $inboxCount++;
  }
  ?>
  
  <!-- Welcome Section -->
  <div style="padding: 20px 0; text-align: left;">
    <h2 style="margin: 0 0 4px 0; font-size: 24px; font-weight: 600; color: var(--text-dark);">
      Selamat Datang,
    </h2>
    <h3 style="margin: 0; font-size: 20px; font-weight: 500; color: var(--text-dark);">
      <?=h($user['name'])?>
    </h3>
    <p style="margin: 8px 0 0 0; color: var(--text-light); font-size: 14px;">
      Notaris Professional
    </p>
  </div>

  <!-- Stats Cards -->
  <div class="notaris-stats">
    <div class="stat-card">
      <div class="stat-icon" style="background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" stroke="white" stroke-width="2"/>
        </svg>
      </div>
      <div class="stat-info">
        <div class="stat-value"><?=$inboxCount?></div>
        <div class="stat-label">Konsultasi Aktif</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" stroke="white" stroke-width="2"/>
          <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" stroke="white" stroke-width="2"/>
        </svg>
      </div>
      <div class="stat-info">
        <div class="stat-value"><?=$user['akta_selesai'] ?? '0'?></div>
        <div class="stat-label">Akta Selesai</div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="quick-actions">
    <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600; color: var(--text-dark);">
      Aksi Cepat
    </h3>
    <div class="action-cards">
      <a href="?page=inbox" class="action-card">
        <div class="action-icon">📥</div>
        <div class="action-label">Inbox</div>
      </a>
      <a href="?page=jadwal" class="action-card">
        <div class="action-icon">📅</div>
        <div class="action-label">Jadwal</div>
      </a>
      <a href="?page=dokumen" class="action-card">
        <div class="action-icon">📄</div>
        <div class="action-label">Dokumen</div>
      </a>
      <a href="?page=riwayat" class="action-card">
        <div class="action-icon">📊</div>
        <div class="action-label">Riwayat</div>
      </a>
    </div>
  </div>


