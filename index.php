<?php
// Prevent any output before headers
ob_start();

session_start();
$dbFile = __DIR__ . '/db.json';

// ---------- Includes ----------
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/bot.php';

// simple_bot_reply loaded from includes/bot.php

// API endpoints loaded from includes/api.php
require_once __DIR__ . '/includes/api.php';
// POST action handlers
require_once __DIR__ . '/includes/actions.php';

// ---------- Routing ----------
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// handle actions moved to includes/actions.php
$user = current_user();
$flash = flash();

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Lexa</title>
<link rel="stylesheet" href="assets/style.css" />
</head>
<body>
<div class="mobile-container">
  
  <?php if($user && $page !== 'home'): ?>
  <div class="header-nav">
    <div style="font-weight:700;">Lexa</div>
  </div>
  <?php endif; ?>

  <?php if($flash): ?>
  <div class="flash-message"><?=h($flash)?></div>
  <?php endif; ?>

  <div id="pageContent" class="page-transition">

<?php
// Include views based on $page
switch($page){
  case 'home':
    require __DIR__ . '/views/home.php';
    break;
  case 'login':
    require __DIR__ . '/views/login.php';
    break;
  case 'register':
    require __DIR__ . '/views/register.php';
    break;
  case 'app':
    require __DIR__ . '/views/app.php';
    break;
  case 'beranda':
    require __DIR__ . '/views/beranda.php';
    break;
  case 'beranda_notaris':
    require __DIR__ . '/views/beranda_notaris.php';
    break;
  case 'beranda_admin':
    require __DIR__ . '/views/beranda_admin.php';
    break;
  case 'profile':
    require __DIR__ . '/views/profile.php';
    break;
  case 'profile_notaris':
    require __DIR__ . '/views/profile_notaris.php';
    break;
  case 'profile_admin':
    require __DIR__ . '/views/profile_admin.php';
    break;
  case 'chatbot':
    require __DIR__ . '/views/chatbot.php';
    break;
  case 'pilih_notaris':
    require __DIR__ . '/views/pilih_notaris.php';
    break;
  case 'notaris_profile':
    require __DIR__ . '/views/notaris_profile.php';
    break;
  case 'chat':
    require __DIR__ . '/views/chat.php';
    break;
  case 'inbox':
    require __DIR__ . '/views/inbox.php';
    break;
  case 'notaris_chat':
    require __DIR__ . '/views/notaris_chat.php';
    break;
  case 'admin':
    require __DIR__ . '/views/admin.php';
    break;
  default:
  echo "<div class='small'>Halaman tidak ditemukan</div>";
  echo "<a class='btn-secondary' href='?page=beranda'>Kembali ke Beranda</a>";
}
?>

  </div>

  <!-- Bottom Navigation - Outside pageContent to avoid animation -->
  <?php if($user && in_array($page, ['beranda', 'pilih_notaris', 'profile', 'beranda_notaris', 'inbox', 'profile_notaris', 'beranda_admin', 'admin', 'profile_admin'])): ?>
  <nav class="bottom-nav">
    <?php if($user['role'] === 'user'): ?>
      <a href="?page=beranda" class="nav-item <?= $page === 'beranda' ? 'active' : '' ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" stroke="currentColor" stroke-width="2"/>
          <path d="M9 22V12h6v10" stroke="currentColor" stroke-width="2"/>
        </svg>
        <span>Beranda</span>
      </a>
      <a href="?page=pilih_notaris" class="nav-item <?= $page === 'pilih_notaris' ? 'active' : '' ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
          <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2"/>
        </svg>
        <span>Cari Notaris</span>
      </a>
      <a href="?page=profile" class="nav-item <?= $page === 'profile' ? 'active' : '' ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="2"/>
          <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
        </svg>
        <span>Profil Saya</span>
      </a>
    <?php elseif($user['role'] === 'notaris'): ?>
      <a href="?page=beranda_notaris" class="nav-item <?= $page === 'beranda_notaris' ? 'active' : '' ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" stroke="currentColor" stroke-width="2"/>
          <path d="M9 22V12h6v10" stroke="currentColor" stroke-width="2"/>
        </svg>
        <span>Beranda</span>
      </a>
      <a href="?page=inbox" class="nav-item <?= $page === 'inbox' ? 'active' : '' ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M22 12h-6l-2 3h-4l-2-3H2" stroke="currentColor" stroke-width="2"/>
          <path d="M5.45 5.11L2 12v6a2 2 0 002 2h16a2 2 0 002-2v-6l-3.45-6.89A2 2 0 0016.76 4H7.24a2 2 0 00-1.79 1.11z" stroke="currentColor" stroke-width="2"/>
        </svg>
        <span>Inbox</span>
      </a>
      <a href="?page=profile_notaris" class="nav-item <?= $page === 'profile_notaris' ? 'active' : '' ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="2"/>
          <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
        </svg>
        <span>Profil Saya</span>
      </a>
    <?php elseif($user['role'] === 'admin'): ?>
      <a href="?page=beranda_admin" class="nav-item <?= $page === 'beranda_admin' ? 'active' : '' ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" stroke="currentColor" stroke-width="2"/>
          <path d="M9 22V12h6v10" stroke="currentColor" stroke-width="2"/>
        </svg>
        <span>Beranda</span>
      </a>
      <a href="?page=admin" class="nav-item <?= $page === 'admin' ? 'active' : '' ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
          <path d="M12 1v6m0 6v6M5.64 5.64l4.24 4.25m4.25 4.24l4.24 4.25M1 12h6m6 0h6M5.64 18.36l4.24-4.24m4.25-4.25l4.24-4.24" stroke="currentColor" stroke-width="2"/>
        </svg>
        <span>Admin Panel</span>
      </a>
      <a href="?page=profile_admin" class="nav-item <?= $page === 'profile_admin' ? 'active' : '' ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="2"/>
          <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
        </svg>
        <span>Profil Saya</span>
      </a>
    <?php endif; ?>
  </nav>
  <?php endif; ?>

  <footer>
    <div class="muted">Lexa - Notaris Digital</div>
  </footer>
</div>

<script>
// Page transition handler
document.addEventListener('DOMContentLoaded', function() {
  // Add smooth transition for internal navigation (except navbar)
  const navLinks = document.querySelectorAll('.beranda-card-link, .action-card, .profile-menu-item:not(.profile-logout-btn)');
  
  navLinks.forEach(link => {
    // Skip external links and buttons
    if(link.tagName === 'BUTTON' || link.getAttribute('target') === '_blank') return;
    
    link.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      
      // Only handle internal links with ?page=
      if(href && href.includes('?page=')) {
        e.preventDefault();
        
        // Add fade out animation
        const pageContent = document.getElementById('pageContent');
        if(pageContent) {
          pageContent.style.opacity = '0';
          pageContent.style.transform = 'translateY(-10px)';
          pageContent.style.transition = 'all 0.2s ease-out';
          
          // Navigate after animation
          setTimeout(() => {
            window.location.href = href;
          }, 200);
        } else {
          window.location.href = href;
        }
      }
    });
  });
  
  // Navbar items - instant navigation without animation
  const navItems = document.querySelectorAll('.nav-item');
  navItems.forEach(navItem => {
    navItem.addEventListener('click', function(e) {
      // Let default navigation happen without animation
      // No preventDefault, just instant navigation
    });
  });
  
  // Reset animation on page load
  const pageContent = document.getElementById('pageContent');
  if(pageContent) {
    pageContent.style.opacity = '1';
    pageContent.style.transform = 'translateY(0)';
  }
});

// Handle browser back/forward with smooth transition
window.addEventListener('pageshow', function(event) {
  if (event.persisted) {
    const pageContent = document.getElementById('pageContent');
    if(pageContent) {
      pageContent.classList.add('page-fade-in');
    }
  }
});
</script>

</body>
</html>
<?php
// Flush output buffer at the end
ob_end_flush();
?>