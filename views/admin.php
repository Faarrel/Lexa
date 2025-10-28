  <?php 
  if(!$user || $user['role'] !== 'admin'){ set_flash('Akses ditolak'); header('Location:?page=app'); exit; }
  $db = load_db();
  $notaris = array_filter($db['users'], function($u){ return $u['role']==='notaris'; });
  $editId = isset($_GET['edit']) ? $_GET['edit'] : null;
  $editItem = null;
  if($editId){ foreach($notaris as $n){ if($n['id']===$editId) { $editItem = $n; break; } } }
  ?>
  <h2 class="page-title">Admin Panel ⚙️</h2>
  <form method="post" enctype="multipart/form-data" style="margin-bottom:16px;">
    <?php if($editItem): ?>
      <input type="hidden" name="action" value="admin_update_notaris" />
      <input type="hidden" name="id" value="<?=h($editItem['id'])?>" />
    <?php else: ?>
      <input type="hidden" name="action" value="admin_add_notaris" />
    <?php endif; ?>
    <input name="name" value="<?=h($editItem['name'] ?? '')?>" placeholder="Nama Notaris" required />
    <input name="email" value="<?=h($editItem['email'] ?? '')?>" placeholder="Email (opsional, auto jika kosong)" />
    <input name="password" value="" placeholder="Password (kosongkan untuk tidak mengubah)" />
    <input name="specialization" value="<?=h($editItem['specialization'] ?? '')?>" placeholder="Spesialisasi (cth: Akta Jual Beli & Perjanjian)" />
    <input name="city" value="<?=h($editItem['city'] ?? '')?>" placeholder="Kota/Kabupaten" />
    <input name="phone" value="<?=h($editItem['phone'] ?? '')?>" placeholder="No. Telepon" />
    <input name="rating" value="<?=h(isset($editItem['rating']) ? $editItem['rating'] : '')?>" placeholder="Rating (0-5, cth: 4.7)" />
    <input name="reviews" value="<?=h(isset($editItem['reviews']) ? $editItem['reviews'] : '')?>" placeholder="Jumlah Review (cth: 180)" />
    <div class="small" style="margin:6px 0 4px;font-weight:600;">Tentang</div>
    <textarea name="tentang" placeholder="Deskripsi singkat tentang notaris, pengalaman, latar belakang pendidikan, dll."><?=h($editItem['tentang'] ?? $editItem['bio'] ?? '')?></textarea>
    <div class="small" style="margin:6px 0 4px;font-weight:600;">Layanan & Tarif</div>
    <textarea name="layanan_tarif" placeholder="Daftar layanan yang ditawarkan dan tarif, contoh:
- Akta Jual Beli Tanah: Rp 2.000.000
- Akta Hibah: Rp 1.500.000
- dll."><?=h($editItem['layanan_tarif'] ?? '')?></textarea>
    <div class="small" style="margin:6px 0 4px;font-weight:600;">Pengalaman & Statistik</div>
    <input name="pengalaman" value="<?=h($editItem['pengalaman'] ?? '')?>" placeholder="Contoh: 10+ Tahun" />
    <input name="akta_selesai" value="<?=h($editItem['akta_selesai'] ?? '')?>" placeholder="Contoh: 500+" />
    <div class="small" style="margin:6px 0 4px;">Foto Profil (opsional)</div>
    <input type="file" name="avatar" accept="image/*" />
    <button class="btn-primary" type="submit"><?php echo $editItem ? 'Simpan Perubahan' : '+ Tambah Notaris'; ?></button>
    <?php if($editItem): ?><a class="btn-secondary" href="?page=admin" style="margin-top:8px;">Batal</a><?php endif; ?>
  </form>
  <div class="list">
    <?php foreach($notaris as $n): ?>
      <div class="card">
        <div>
          <div style="font-weight:600;margin-bottom:4px;"><?=h($n['name'])?></div>
          <div class="muted"><?=h($n['email'])?></div>
          <?php if(!empty($n['specialization'])): ?>
          <div class="small">Spesialis: <?=h($n['specialization'])?></div>
          <?php endif; ?>
          <?php if(isset($n['rating'])): ?>
          <div class="small">Rating: <?=h($n['rating'])?> (<?=h($n['reviews'] ?? 0)?> review)</div>
          <?php endif; ?>
        </div>
        <form method="post" style="margin:0;display:flex;gap:8px;align-items:center;">
          <input type="hidden" name="action" value="admin_del_notaris" />
          <input type="hidden" name="id" value="<?=h($n['id'])?>" />
          <button class="btn-small btn-ghost" type="submit">Hapus</button>
        </form>
        <a class="btn-small" href="?page=admin&edit=<?=h($n['id'])?>">Edit</a>
      </div>
    <?php endforeach; ?>
  </div>


