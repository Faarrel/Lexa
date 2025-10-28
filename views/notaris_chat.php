  <?php 
  if(!$user || $user['role'] !== 'notaris'){ set_flash('Akses ditolak'); header('Location:?page=app'); exit; }
  $chatId = $_GET['chat'] ?? null; 
  if(!$chatId){ set_flash('Chat tidak ditemukan'); header('Location:?page=inbox'); exit; }
  $db = load_db(); 
  if(!isset($db['chats'][$chatId])){ set_flash('Chat tidak ditemukan'); header('Location:?page=inbox'); exit; }
  $chat = $db['chats'][$chatId];
  // Resolve other participant name
  $otherId = null;
  foreach($chat['participants'] as $pid){ if($pid !== $user['id']) { $otherId = $pid; break; } }
  $otherName = $otherId;
  foreach($db['users'] as $uu){ if($uu['id'] === $otherId){ $otherName = $uu['name']; break; } }
  ?>
  <h2 class="page-title">Chat: <?=h($otherName)?></h2>
  <div class="chat-area" id="chatAreaNotaris">
    <?php foreach($chat['messages'] as $m): 
      $isMe = isset($m['from']) && $m['from']===$user['id']; 
      $cls = ($m['from']==='system') ? 'left' : ($isMe ? 'right' : 'left'); 
    ?>
      <div class="bubble <?= $cls ?>"><?=h($m['text'])?></div>
    <?php endforeach; ?>
  </div>
  
  <!-- Typing indicator -->
  <div id="notarisTypingIndicator" class="typing-indicator">
    <div class="bubble left">
      <div class="typing-dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  
  <form id="notarisChatForm" method="post" action="?api=send_message">
    <input type="hidden" name="chatId" value="<?=h($chatId)?>" />
    <input id="notarisMessageInput" name="text" placeholder="Balas pesan..." required autocomplete="off" />
    <button class="btn-primary" type="submit" id="notarisSendBtn">Kirim</button>
    <a class="btn-secondary" href="?page=inbox">← Kembali</a>
  </form>

  <script src="assets/notaris_chat.js"></script>
  <script>
    // Check if functions are loaded
    if (typeof sendNotarisMessage === 'function' && typeof initNotarisChatPage === 'function') {
      // Override form submission with AJAX
      document.getElementById('notarisChatForm').onsubmit = function(e) {
        e.preventDefault();
        sendNotarisMessage(e);
        return false;
      };
      
      initNotarisChatPage({
        chatId: <?=json_encode($chatId)?>,
        userId: <?=json_encode($user['id'])?>,
        initialCount: <?=count($chat['messages'])?>
      });
    } else {
      console.error('Notaris chat functions not loaded. Form will use regular POST submission.');
    }
  </script>


