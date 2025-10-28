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
    <form id="chatForm" method="post" action="">
      <input type="hidden" name="action" value="send_msg" />
      <input type="hidden" name="chatId" value="<?=h($chatId)?>" />
      <input id="messageInput" name="text" placeholder="Tulis pesan..." required autocomplete="off" />
      <button class="btn-primary" type="submit" id="sendBtn">Kirim</button>
      <a class="btn-secondary" href="?page=app">← Kembali</a>
    </form>
  <?php endif; ?>

  <script>
  // Inline chat script for better reliability
  (function() {
    let chatConfig = {
      chatId: <?=json_encode($chatId)?>,
      currentUserId: <?=json_encode($user['id'])?>,
      initialCount: <?=count($chat['messages'])?>
    };
    let lastMessageCount = chatConfig.initialCount;
    let pollInterval = null;

    function scrollToBottom() {
      const chatArea = document.getElementById('chatArea');
      if(chatArea) {
        chatArea.scrollTop = chatArea.scrollHeight;
      }
    }

    function addMessage(text, isMe) {
      const chatArea = document.getElementById('chatArea');
      if(!chatArea) return;
      
      const bubble = document.createElement('div');
      bubble.className = 'bubble ' + (isMe ? 'right' : 'left');
      bubble.style.opacity = '0';
      bubble.style.transform = 'translateY(10px)';
      bubble.style.transition = 'all 0.3s ease';
      bubble.textContent = text;
      chatArea.appendChild(bubble);
      
      setTimeout(() => {
        bubble.style.opacity = '1';
        bubble.style.transform = 'translateY(0)';
      }, 10);
      
      lastMessageCount++;
      scrollToBottom();
    }

    function fetchMessages() {
      if(!chatConfig.chatId) return;
      
      fetch('?api=get_messages&chat=' + encodeURIComponent(chatConfig.chatId))
        .then(res => res.json())
        .then(data => {
          if(data.success && data.messages) {
            const newCount = data.messages.length;
            if(newCount > lastMessageCount) {
              renderMessages(data.messages);
              lastMessageCount = newCount;
            }
          }
        })
        .catch(err => console.error('Error fetching messages:', err));
    }

    function renderMessages(messages) {
      const chatArea = document.getElementById('chatArea');
      if(!chatArea) return;
      
      chatArea.innerHTML = '';
      messages.forEach(m => {
        const isMe = m.from === chatConfig.currentUserId;
        const cls = m.from === 'system' ? 'left' : (isMe ? 'right' : 'left');
        const bubble = document.createElement('div');
        bubble.className = 'bubble ' + cls;
        bubble.textContent = m.text;
        chatArea.appendChild(bubble);
      });
      scrollToBottom();
    }

    function sendMessage(event) {
      event.preventDefault();
      event.stopPropagation();
      
      const input = document.getElementById('messageInput');
      const sendBtn = document.getElementById('sendBtn');
      const text = input.value.trim();
      
      if(!text) return false;
      
      input.disabled = true;
      sendBtn.disabled = true;
      sendBtn.textContent = 'Mengirim...';
      
      addMessage(text, true);
      input.value = '';
      
      const formData = new FormData();
      formData.append('chatId', chatConfig.chatId);
      formData.append('text', text);
      
      fetch('?api=send_message', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if(!data.success) {
          alert('Gagal mengirim pesan: ' + (data.error || 'Unknown error'));
        }
        input.disabled = false;
        sendBtn.disabled = false;
        sendBtn.textContent = 'Kirim';
        input.focus();
      })
      .catch(err => {
        console.error('Error sending message:', err);
        alert('Terjadi kesalahan saat mengirim pesan');
        input.disabled = false;
        sendBtn.disabled = false;
        sendBtn.textContent = 'Kirim';
        input.focus();
      });
      
      return false;
    }

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
      scrollToBottom();
      
      // Start polling for new messages
      pollInterval = setInterval(fetchMessages, 2000);
      
      // Override form submission
      const chatForm = document.getElementById('chatForm');
      if(chatForm) {
        chatForm.addEventListener('submit', sendMessage);
        console.log('Chat real-time mode enabled');
      }
      
      const input = document.getElementById('messageInput');
      if(input) input.focus();
      
      // Cleanup on page unload
      window.addEventListener('beforeunload', function() {
        if(pollInterval) clearInterval(pollInterval);
      });
    });
  })();
  </script>


