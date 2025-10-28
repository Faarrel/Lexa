  <h2 class="page-title">Daftar Akun</h2>
  <form method="post">
    <input type="hidden" name="action" value="register" />
    <input name="name" placeholder="Nama Lengkap" required />
    <input name="email" placeholder="Email" required />
    <input name="password" type="password" placeholder="Password" required />
    <input type="hidden" name="role" value="user">
    <button class="btn-primary" type="submit">Buat Akun</button>
    <a class="btn-secondary" href="?page=login">Kembali ke Login</a>
  </form>


