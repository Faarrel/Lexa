  <?php 
  if(!$user || $user['role'] !== 'notaris'){ set_flash('Akses ditolak'); header('Location:?page=app'); exit; }
  $db = load_db();
  $items = [];
  foreach($db['chats'] as $k=>$c){
      if(in_array($user['id'], $c['participants'])) $items[$k] = $c;
  }
  ?>
  <h2 class="page-title">Inbox Notaris 📥</h2>
  <?php if(empty($items)): ?>
  <div class="inbox-empty">
    <div class="inbox-empty-icon">📭</div>
    <div class="inbox-empty-text">Tidak ada konsultasi saat ini</div>
    <div class="inbox-empty-desc">Konsultasi dari klien akan muncul di sini</div>
  </div>
  <?php else: ?>
  <div class="list">
    <?php foreach($items as $k=>$c): ?>
      <div class="card inbox-card">
        <div style="flex:1">
          <?php 
            $otherId = null; 
            foreach($c['participants'] as $pid){ if($pid !== $user['id']) { $otherId = $pid; break; } }
            $otherName = $otherId; 
            foreach($db['users'] as $uu){ if($uu['id'] === $otherId){ $otherName = $uu['name']; break; } }
          ?>
          <div style="font-weight:600;margin-bottom:4px;"><?=h($otherName)?></div>
          <div class="small"><?=h(end($c['messages'])['text'])?></div>
        </div>
        <a class="btn-small" href="?page=notaris_chat&chat=<?=h($k)?>">Buka</a>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>


