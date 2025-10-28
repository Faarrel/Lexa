  <?php 
  if(!$user){ header('Location:?page=login'); exit; }
  $db = load_db();
  $id = $_GET['id'] ?? '';
  $notaris = null;
  foreach($db['users'] as $u){ if($u['id'] === $id && $u['role'] === 'notaris'){ $notaris = $u; break; } }
  if(!$notaris){ set_flash('Profil notaris tidak ditemukan'); header('Location:?page=pilih_notaris'); exit; }

  $rating = isset($notaris['rating']) ? floatval($notaris['rating']) : 4.7; 
  $reviews = isset($notaris['reviews']) ? intval($notaris['reviews']) : 0; 
  function star_string($rating){ $full=floor($rating); $half=($rating-$full)>=0.5?1:0; $empty=5-$full-$half; return str_repeat('★',$full).str_repeat('☆',$empty+$half); }
  ?>
  <h2 class="page-title">Profil Notaris</h2>

  <div class="profile-card">
    <div style="display:flex; gap:12px; align-items:center;">
      <div class="avatar" style="width:56px;height:56px;border-radius:50%;border:2px solid var(--border);background-size:cover;background-position:center;<?php if(!empty($notaris['avatar'])): ?>background-image:url('<?=h($notaris['avatar'])?>');<?php endif; ?>"></div>
      <div>
        <div style="font-weight:700; font-size:16px;"><?=h($notaris['name'])?></div>
        <?php if(!empty($notaris['specialization'])): ?>
        <div class="small">Spesialis: <?=h($notaris['specialization'])?></div>
        <?php endif; ?>
      </div>
    </div>
    <div style="margin-top:8px;">
      <div class="stars" style="color:#ffb703;"><?=star_string($rating)?></div>
      <div class="small" style="margin-top:4px;"><?=number_format($rating,1)?> (<?=number_format($reviews)?> Review)</div>
    </div>
    <div class="stat-row" style="margin-top:10px;">
      <div class="stat">
        <div class="small" style="font-weight:700;">Pengalaman</div>
        <div class="small"><?=h($notaris['pengalaman'] ?? '10+ Tahun')?></div>
      </div>
      <div class="stat">
        <div class="small" style="font-weight:700;">Akta Selesai</div>
        <div class="small"><?=h($notaris['akta_selesai'] ?? '500+')?></div>
      </div>
    </div>
    <div class="tabs" style="margin-top:12px;">
      <div class="tab active" onclick="showTab('tentang')">Tentang</div>
      <div class="tab" onclick="showTab('layanan')">Layanan & Tarif</div>
    </div>
    <div id="tab-tentang" class="tab-content" style="margin-top:10px;">
      <div class="small"><?=nl2br(h($notaris['tentang'] ?? $notaris['bio'] ?? 'Belum ada informasi tentang notaris ini.'))?></div>
    </div>
    <div id="tab-layanan" class="tab-content" style="margin-top:10px;display:none;">
      <div class="small"><?=nl2br(h($notaris['layanan_tarif'] ?? 'Informasi layanan dan tarif belum tersedia.'))?></div>
    </div>
  </div>

  <script>
    function showTab(tab) {
      const tabs = document.querySelectorAll('.tab');
      const contents = document.querySelectorAll('.tab-content');
      tabs.forEach(t => t.classList.remove('active'));
      contents.forEach(c => c.style.display = 'none');
      
      if(tab === 'tentang') {
        tabs[0].classList.add('active');
        document.getElementById('tab-tentang').style.display = 'block';
      } else {
        tabs[1].classList.add('active');
        document.getElementById('tab-layanan').style.display = 'block';
      }
    }
  </script>

  <div class="cta">
    <form method="post" style="margin:0;">
      <input type="hidden" name="action" value="create_chat" />
      <input type="hidden" name="notaris" value="<?=h($notaris['id'])?>" />
      <button class="btn-primary" type="submit">Buat Janji / Mulai Chat</button>
    </form>
    <a class="btn-secondary" href="?page=pilih_notaris" style="margin-top:8px;">← Kembali</a>
  </div>


