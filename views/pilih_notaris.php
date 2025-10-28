  <?php if(!$user){ header('Location:?page=login'); exit; } 
  $db = load_db(); 
  $q = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';
  $notarisAll = array_filter($db['users'], function($u){ return $u['role']==='notaris'; });
  $notaris = array_filter($notarisAll, function($u) use($q){
    if($q==='') return true;
    return strpos(strtolower($u['name']), $q) !== false || strpos(strtolower($u['email']), $q) !== false;
  });
  // helpers
  function star_string($rating){
    $full = floor($rating); $half = ($rating - $full) >= 0.5 ? 1 : 0; $empty = 5 - $full - $half;
    return str_repeat('★', $full) . str_repeat('☆', $empty + $half); // no half star glyph for simplicity
  }
  ?>
  <h2 class="page-title">Cari Notaris</h2>

  <form method="get" class="search-row" style="margin-bottom:12px;display:flex;gap:8px;align-items:center;">
    <input type="hidden" name="page" value="pilih_notaris" />
    <input name="q" value="<?=h(isset($_GET['q'])?$_GET['q']:'')?>" placeholder="Cari notaris spesialis Akta Jual Beli..." />
    <button class="btn-small" type="submit">Cari</button>
  </form>

  <div class="chip-row" style="display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap;">
    <span class="chip">Notaris Online</span>
    <span class="chip">Rating Terbaik</span>
    <span class="chip">Spesialis Tanah</span>
  </div>

  <div class="list">
    <?php foreach($notaris as $n): 
      $rating = isset($n['rating']) ? floatval($n['rating']) : 4.7; 
      $reviews = isset($n['reviews']) ? intval($n['reviews']) : 180; 
      $spec = !empty($n['specialization']) ? $n['specialization'] : 'Akta Jual Beli & Perjanjian';
    ?>
      <div class="notary-card">
        <div class="avatar" style="background-size:cover;background-position:center;<?php if(!empty($n['avatar'])): ?>background-image:url('<?=h($n['avatar'])?>');<?php endif; ?>"></div>
        <div class="notary-main">
          <div class="name"><?=h($n['name'])?></div>
          <div class="spec">Spesialis: <?=h($spec)?></div>
          <div class="stars"><?=star_string($rating)?></div>
          <div class="reviews"><?=number_format($rating,1)?> (<?=number_format($reviews)?> Review)</div>
        </div>
        <div class="card-actions">
          <a class="btn-small btn-ghost" href="?page=notaris_profile&id=<?=h($n['id'])?>">Lihat Profil</a>
        </div>
      </div>
    <?php endforeach; ?>
    <?php if(empty($notaris)): ?>
      <div class="small">Tidak ditemukan notaris untuk pencarian "<?=h($q)?>".</div>
    <?php endif; ?>
  </div>


