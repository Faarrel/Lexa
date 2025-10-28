<?php
// API endpoints for realtime chat

if(isset($_GET['api']) && $_GET['api'] === 'get_messages'){
	header('Content-Type: application/json');
	$chatId = $_GET['chat'] ?? $_GET['chatId'] ?? null;
	if($chatId){
		$db = load_db();
		if(isset($db['chats'][$chatId])){
			echo json_encode(['success'=>true, 'messages'=>$db['chats'][$chatId]['messages'], 'paid'=>$db['chats'][$chatId]['paid']]);
		} else {
			echo json_encode(['success'=>false]);
		}
	} else {
		echo json_encode(['success'=>false]);
	}
	exit;
}

if(isset($_GET['api']) && $_GET['api'] === 'send_message'){
	header('Content-Type: application/json');
	$user = current_user();
	if(!$user){ echo json_encode(['success'=>false,'error'=>'Not logged in']); exit; }

	$chatId = $_POST['chatId'] ?? null;
	$text = trim($_POST['text'] ?? '');

	if(!$chatId || !$text){ echo json_encode(['success'=>false,'error'=>'Invalid data']); exit; }

	$db = load_db();
	if(!isset($db['chats'][$chatId])){ echo json_encode(['success'=>false,'error'=>'Chat not found']); exit; }

	$isNotaris = ($user['role']==='notaris');
	if(!$db['chats'][$chatId]['paid'] && !$isNotaris){
		echo json_encode(['success'=>false,'error'=>'Payment required']);
		exit;
	}

	$db['chats'][$chatId]['messages'][] = ['from'=>$user['id'],'text'=>$text,'ts'=>time()];
	save_db($db);

	echo json_encode(['success'=>true, 'messages'=>$db['chats'][$chatId]['messages']]);
	exit;
}

if(isset($_GET['api']) && $_GET['api'] === 'clear_bot_chat'){
	header('Content-Type: application/json');
	unset($_SESSION['bot_msgs']);
	echo json_encode(['success'=>true]);
	exit;
}

if(isset($_GET['api']) && $_GET['api'] === 'bot_chat'){
	header('Content-Type: application/json');
	$user = current_user();
	if(!$user){ echo json_encode(['success'=>false,'error'=>'Not logged in']); exit; }
	
	$userText = trim($_POST['bot_text'] ?? '');
	if(empty($userText)){ echo json_encode(['success'=>false,'error'=>'Empty message']); exit; }
	
	if(!isset($_SESSION['bot_msgs'])) $_SESSION['bot_msgs'] = [];
	
	// Simpan pesan user
	$_SESSION['bot_msgs'][] = ['from'=>$user['id'],'text'=>$userText];
	
	// Panggil AI
	$aiResult = gemini_chat($userText);
	
	// Simpan respons bot
	$_SESSION['bot_msgs'][] = ['from'=>'bot','text'=>$aiResult['response']];
	
	// Cek apakah perlu redirect
	$needRedirect = false;
	if(!$aiResult['can_answer']){
		$_SESSION['bot_msgs'][] = [
			'from'=>'bot',
			'text'=>'💬 Sepertinya pertanyaan Anda memerlukan konsultasi langsung dengan notaris profesional.',
			'action'=>'redirect'
		];
		$needRedirect = true;
	}
	
	echo json_encode([
		'success'=>true,
		'response'=>$aiResult['response'],
		'redirect'=>$needRedirect
	]);
	exit;
}

?>

