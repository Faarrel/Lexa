  <?php 
  if(!$user){ header('Location:?page=login'); exit; }
  $chatId = isset($_GET['chat']) ? $_GET['chat'] : null;
  if(!$chatId){ set_flash('Chat tidak ditemukan'); header('Location:?page=app'); exit; }
  $db = load_db(); 
  if(!isset($db['chats'][$chatId])){ set_flash('Chat tidak ditemukan'); header('Location:?page=app'); exit; }
  $chat = $db['chats'][$chatId];
  $other = array_filter($chat['participants'], function($p) use($user){ return $p !== $user['id']; });
  $notarisId = array_values($other)[0];
  $notarisUser = null; 
  foreach($db['users'] as $u) if($u['id']===$notarisId) $notarisUser = $u;
  ?>
  <h2 class="page-title">Chat: <?=h($notarisUser ? $notarisUser['name'] : $notarisId)?></h2>
  <div class="chat-area" id="chatArea">
    <?php foreach($chat['messages'] as $m): 
      $isMe = isset($m['from']) && $m['from'] === $user['id'];
      $cls = $m['from'] === 'system' ? 'left' : ($isMe ? 'right' : 'left');
    ?>
      <div class="bubble <?= $cls ?>"><?=h($m['text'])?></div>
    <?php endforeach; ?>
  </div>
  
  <!-- Typing indicator -->
  <div id="typingIndicator" class="typing-indicator">
    <div class="bubble left">
      <div class="typing-dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>

  <?php if(!$chat['paid'] && $user['role'] !== 'notaris'): ?>
    <div class="small" style="margin-bottom:12px;">
      Untuk memulai konsultasi 1-on-1, silakan lakukan <strong>pembayaran simulasi</strong>.
    </div>
    <form method="post">
      <input type="hidden" name="action" value="pay_sim" />
      <input type="hidden" name="chatId" value="<?=h($chatId)?>" />
      <button class="btn-primary" type="submit">💳 Bayar Sekarang (Simulasi)</button>
    </form>
    <div class="small" style="margin-top:8px;text-align:center;">
      Catatan: ini hanya simulasi — tidak melakukan pembayaran nyata.
    </div>
  <?php else: ?>
    <form id="chatForm" method="post" action="?api=send_message">
      <input type="hidden" name="chatId" value="<?=h($chatId)?>" />
      <input id="messageInput" name="text" placeholder="Tulis pesan..." required autocomplete="off" />
      <button class="btn-primary" type="submit" id="sendBtn">Kirim</button>
      <a class="btn-secondary" href="?page=app">← Kembali</a>
    </form>
  <?php endif; ?>

  <script src="assets/chat.js"></script>
  <script>
    // Check if functions are loaded
    if (typeof sendMessage === 'function' && typeof initChatPage === 'function') {
      // Override form submission with AJAX
      document.getElementById('chatForm').onsubmit = function(e) {
        e.preventDefault();
        sendMessage(e);
        return false;
      };
      
      initChatPage({
        chatId: <?=json_encode($chatId)?>,
        currentUserId: <?=json_encode($user['id'])?>,
        initialCount: <?=count($chat['messages'])?>
      });
    } else {
      console.error('Chat functions not loaded. Form will use regular POST submission.');
    }
  </script>


