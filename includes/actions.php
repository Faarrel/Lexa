<?php
// Handle POST actions (auth, chat, admin)

if($_SERVER['REQUEST_METHOD'] === 'POST'){
	$db = load_db();
	if(isset($_POST['action']) && $_POST['action'] === 'login'){
		$email = $_POST['email']; $pw = $_POST['password'];
		foreach($db['users'] as $u){
			if($u['email'] === $email && $u['password'] === $pw){
				$_SESSION['user'] = $u;
				header('Location: ?page=app'); exit;
			}
		}
		set_flash('Email atau password salah.');
		header('Location: ?page=login'); exit;
	}
	if(isset($_POST['action']) && $_POST['action'] === 'register'){
	  $name = trim($_POST['name']); 
	  $email = trim($_POST['email']); 
	  $pw = $_POST['password']; 
	  $role = 'user';
		foreach($db['users'] as $u) if($u['email'] === $email){ set_flash('Email sudah terdaftar'); header('Location:?page=register'); exit; }
		$id = $role . time();
		$new = ['id'=>$id, 'name'=>$name, 'email'=>$email, 'role'=>$role, 'password'=>$pw];
		$db['users'][] = $new; save_db($db); $_SESSION['user'] = $new;
		header('Location:?page=app'); exit;
	}
	if(isset($_POST['action']) && $_POST['action'] === 'logout'){
		session_destroy(); session_start(); header('Location:?page=home'); exit;
	}
	if(isset($_POST['action']) && $_POST['action'] === 'create_chat'){
		$db = load_db();
		$user = current_user(); if(!$user){ set_flash('Silakan login'); header('Location:?page=login'); exit; }
		$notarisId = $_POST['notaris'];
		$chatId = "chat_{$user['id']}_{$notarisId}";
		if(!isset($db['chats'][$chatId])){
			$db['chats'][$chatId] = ['participants'=>[$user['id'],$notarisId], 'messages'=>[['from'=>'system','text'=>'Sesi konsultasi dibuat (simulasi).']], 'paid'=>false];
			save_db($db);
		}
		header("Location:?page=chat&chat={$chatId}"); exit;
	}
	if(isset($_POST['action']) && $_POST['action'] === 'send_msg'){
		$db = load_db(); $user = current_user(); if(!$user){ set_flash('Silakan login'); header('Location:?page=login'); exit; }
		$chatId = $_POST['chatId']; $text = trim($_POST['text']);
		if(!isset($db['chats'][$chatId])){ set_flash('Chat tidak ditemukan'); header('Location:?page=app'); exit; }
		$isNotaris = ($user['role']==='notaris');
		if(!$db['chats'][$chatId]['paid'] && !$isNotaris){
			set_flash('Silakan lakukan pembayaran simulasi terlebih dahulu.'); header("Location:?page=chat&chat={$chatId}"); exit;
		}
		$db['chats'][$chatId]['messages'][] = ['from'=>$user['id'],'text'=>$text,'ts'=>time()];
		save_db($db);
		// Redirect based on user role
		$redirectPage = $isNotaris ? 'notaris_chat' : 'chat';
		header("Location:?page={$redirectPage}&chat={$chatId}"); exit;
	}
	if(isset($_POST['action']) && $_POST['action'] === 'pay_sim'){
		$db = load_db(); $chatId = $_POST['chatId']; if(isset($db['chats'][$chatId])){ $db['chats'][$chatId]['paid'] = true; $db['chats'][$chatId]['messages'][] = ['from'=>'system','text'=>'Pembayaran simulasi berhasil. Anda dapat berkonsultasi sekarang.','ts'=>time()]; save_db($db); }
		header("Location:?page=chat&chat={$chatId}"); exit;
	}
    if(isset($_POST['action']) && $_POST['action'] === 'admin_add_notaris'){
        $db = load_db();
        $name = trim($_POST['name']);
        $nameSlug = strtolower(preg_replace('/\s+/', '', $name));
        $id = $nameSlug . time();
        $email = trim($_POST['email'] ?? '');
        if($email === ''){ $email = $nameSlug . '@nd.com'; }
        $password = trim($_POST['password'] ?? 'notaris');
        $specialization = trim($_POST['specialization'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $rating = isset($_POST['rating']) && $_POST['rating'] !== '' ? floatval($_POST['rating']) : null;
        $reviews = isset($_POST['reviews']) && $_POST['reviews'] !== '' ? intval($_POST['reviews']) : null;
        $tentang = trim($_POST['tentang'] ?? '');
        $layanan_tarif = trim($_POST['layanan_tarif'] ?? '');
        $pengalaman = trim($_POST['pengalaman'] ?? '');
        $akta_selesai = trim($_POST['akta_selesai'] ?? '');

        $new = [
            'id'=>$id,
            'name'=>$name,
            'email'=>$email,
            'role'=>'notaris',
            'password'=>$password,
        ];
        // handle avatar upload
        if(isset($_FILES['avatar']) && is_array($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK){
            $tmp = $_FILES['avatar']['tmp_name'];
            $orig = $_FILES['avatar']['name'] ?? '';
            $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
            if(!in_array($ext, ['jpg','jpeg','png','gif','webp'])){ $ext = 'jpg'; }
            $dir = __DIR__ . '/../uploads/avatars';
            if(!is_dir($dir)){ @mkdir($dir, 0777, true); }
            $rel = 'uploads/avatars/' . $id . '.' . $ext;
            $dest = __DIR__ . '/../' . $rel;
            @move_uploaded_file($tmp, $dest);
            if(file_exists($dest)) $new['avatar'] = $rel;
        }
        if($specialization !== '') $new['specialization'] = $specialization;
        if($city !== '') $new['city'] = $city;
        if($phone !== '') $new['phone'] = $phone;
        if($rating !== null) $new['rating'] = $rating;
        if($reviews !== null) $new['reviews'] = $reviews;
        if($tentang !== '') $new['tentang'] = $tentang;
        if($layanan_tarif !== '') $new['layanan_tarif'] = $layanan_tarif;
        if($pengalaman !== '') $new['pengalaman'] = $pengalaman;
        if($akta_selesai !== '') $new['akta_selesai'] = $akta_selesai;

        $db['users'][] = $new;
        save_db($db); header('Location:?page=admin'); exit;
    }
	if(isset($_POST['action']) && $_POST['action'] === 'admin_del_notaris'){
		$db = load_db(); $id = $_POST['id']; $db['users'] = array_values(array_filter($db['users'], function($u) use($id){ return !($u['id']===$id && $u['role']==='notaris'); }));
		save_db($db); header('Location:?page=admin'); exit;
	}
    if(isset($_POST['action']) && $_POST['action'] === 'admin_update_notaris'){
        $db = load_db();
        $id = $_POST['id'];
        foreach($db['users'] as &$u){
            if($u['id'] === $id && $u['role'] === 'notaris'){
                $u['name'] = trim($_POST['name']);
                $email = trim($_POST['email'] ?? '');
                if($email !== '') $u['email'] = $email;
                $pwd = trim($_POST['password'] ?? '');
                if($pwd !== '') $u['password'] = $pwd;
                $u['specialization'] = trim($_POST['specialization'] ?? '');
                $u['city'] = trim($_POST['city'] ?? '');
                $u['phone'] = trim($_POST['phone'] ?? '');
                if(isset($_POST['rating']) && $_POST['rating'] !== '') $u['rating'] = floatval($_POST['rating']);
                if(isset($_POST['reviews']) && $_POST['reviews'] !== '') $u['reviews'] = intval($_POST['reviews']);
                $u['tentang'] = trim($_POST['tentang'] ?? '');
                $u['layanan_tarif'] = trim($_POST['layanan_tarif'] ?? '');
                $u['pengalaman'] = trim($_POST['pengalaman'] ?? '');
                $u['akta_selesai'] = trim($_POST['akta_selesai'] ?? '');
                if(isset($_FILES['avatar']) && is_array($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK){
                    $tmp = $_FILES['avatar']['tmp_name'];
                    $orig = $_FILES['avatar']['name'] ?? '';
                    $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
                    if(!in_array($ext, ['jpg','jpeg','png','gif','webp'])){ $ext = 'jpg'; }
                    $dir = __DIR__ . '/../uploads/avatars';
                    if(!is_dir($dir)){ @mkdir($dir, 0777, true); }
                    $rel = 'uploads/avatars/' . $id . '.' . $ext;
                    $dest = __DIR__ . '/../' . $rel;
                    @move_uploaded_file($tmp, $dest);
                    if(file_exists($dest)) $u['avatar'] = $rel;
                }
                break;
            }
        }
        unset($u);
        save_db($db); header('Location:?page=admin'); exit;
    }
}

?>

