<?php
// Shared helper functions

function load_db(){
	global $dbFile;
	if(!file_exists($dbFile)){
		file_put_contents($dbFile, json_encode(["users"=>[],"chats"=>new stdClass()], JSON_PRETTY_PRINT));
	}
	$s = file_get_contents($dbFile);
	return json_decode($s, true);
}

function save_db($db){
	global $dbFile;
	$tmp = tempnam(sys_get_temp_dir(), 'db');
	file_put_contents($tmp, json_encode($db, JSON_PRETTY_PRINT));
	rename($tmp, $dbFile);
}

function current_user(){ return isset($_SESSION['user']) ? $_SESSION['user'] : null; }

function flash(){ if(isset($_SESSION['flash'])){ $t = $_SESSION['flash']; unset($_SESSION['flash']); return $t; } return null; }

function set_flash($m){ $_SESSION['flash'] = $m; }

function h($s){ return htmlspecialchars($s, ENT_QUOTES); }

?>

