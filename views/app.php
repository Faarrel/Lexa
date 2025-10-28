  <?php 
  if(!$user){ header('Location:?page=login'); exit; }
  
  // Redirect to appropriate beranda page based on role
  if($user['role'] === 'user'){
    header('Location:?page=beranda');
    exit;
  } elseif($user['role'] === 'notaris'){
    header('Location:?page=beranda_notaris');
    exit;
  } elseif($user['role'] === 'admin'){
    header('Location:?page=beranda_admin');
    exit;
  }
  ?>


