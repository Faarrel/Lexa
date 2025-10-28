  <?php 
  if(!$user || $user['role'] !== 'admin'){ header('Location:?page=login'); exit; }
  $db = load_db();
  $totalUsers = count(array_filter($db['users'], function($u){ return $u['role'] === 'user'; }));
  $totalNotaris = count(array_filter($db['users'], function($u){ return $u['role'] === 'notaris'; }));
  $totalChats = count($db['chats']);
  ?>
  
  <!-- Welcome Section -->
  <div style="padding: 20px 0; text-align: left;">
    <h2 style="margin: 0 0 4px 0; font-size: 24px; font-weight: 600; color: var(--text-dark);">
      Admin Dashboard
    </h2>
    <h3 style="margin: 0; font-size: 20px; font-weight: 500; color: var(--text-dark);">
      Selamat Datang, <?=h($user['name'])?>
    </h3>
    <p style="margin: 8px 0 0 0; color: var(--text-light); font-size: 14px;">
      Panel Administrator Lexa
    </p>
  </div>

  <!-- Stats Cards -->
  <div class="admin-stats">
    <div class="stat-card">
      <div class="stat-icon" style="background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke="white" stroke-width="2"/>
          <circle cx="9" cy="7" r="4" stroke="white" stroke-width="2"/>
          <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke="white" stroke-width="2"/>
        </svg>
      </div>
      <div class="stat-info">
        <div class="stat-value"><?=$totalUsers?></div>
        <div class="stat-label">Total Users</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke="white" stroke-width="2"/>
          <circle cx="12" cy="7" r="4" stroke="white" stroke-width="2"/>
        </svg>
      </div>
      <div class="stat-info">
        <div class="stat-value"><?=$totalNotaris?></div>
        <div class="stat-label">Total Notaris</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" stroke="white" stroke-width="2"/>
        </svg>
      </div>
      <div class="stat-info">
        <div class="stat-value"><?=$totalChats?></div>
        <div class="stat-label">Total Konsultasi</div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="quick-actions">
    <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600; color: var(--text-dark);">
      Aksi Cepat
    </h3>
    <div class="admin-action-cards">
      <a href="?page=admin" class="action-card">
        <div class="action-icon">⚙️</div>
        <div class="action-label">Kelola Notaris</div>
      </a>
      <a href="?page=chatbot" class="action-card">
        <div class="action-icon">💬</div>
        <div class="action-label">Test Chatbot</div>
      </a>
      <a href="?page=users_list" class="action-card">
        <div class="action-icon">👥</div>
        <div class="action-label">Daftar User</div>
      </a>
      <a href="?page=reports" class="action-card">
        <div class="action-icon">📊</div>
        <div class="action-label">Laporan</div>
      </a>
    </div>
  </div>


