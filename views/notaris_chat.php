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
  
  <form id="notarisChatForm" method="post" action="">
    <input type="hidden" name="action" value="send_msg" />
    <input type="hidden" name="chatId" value="<?=h($chatId)?>" />
    <input id="notarisMessageInput" name="text" placeholder="Balas pesan..." required autocomplete="off" />
    <button class="btn-primary" type="submit" id="notarisSendBtn">Kirim</button>
    <a class="btn-secondary" href="?page=inbox">← Kembali</a>
  </form>

  <script>
  // Inline notaris chat script for better reliability
  (function() {
    let notarisChatConfig = {
      chatId: <?=json_encode($chatId)?>,
      userId: <?=json_encode($user['id'])?>,
      initialCount: <?=count($chat['messages'])?>
    };
    let notarisLastMessageCount = notarisChatConfig.initialCount;
    let notarisPollInterval = null;

    function scrollNotarisToBottom() {
      const chatArea = document.getElementById('chatAreaNotaris');
      if(chatArea) {
        chatArea.scrollTop = chatArea.scrollHeight;
      }
    }

    function addNotarisMessage(text, isMe) {
      const chatArea = document.getElementById('chatAreaNotaris');
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
      
      notarisLastMessageCount++;
      scrollNotarisToBottom();
    }

    function fetchNotarisMessages() {
      if(!notarisChatConfig.chatId) return;
      
      fetch('?api=get_messages&chat=' + encodeURIComponent(notarisChatConfig.chatId))
        .then(res => res.json())
        .then(data => {
          if(data.success && data.messages) {
            const newCount = data.messages.length;
            if(newCount > notarisLastMessageCount) {
              renderNotarisMessages(data.messages);
              notarisLastMessageCount = newCount;
            }
          }
        })
        .catch(err => console.error('Error fetching messages:', err));
    }

    function renderNotarisMessages(messages) {
      const chatArea = document.getElementById('chatAreaNotaris');
      if(!chatArea) return;
      
      chatArea.innerHTML = '';
      messages.forEach(m => {
        const isMe = m.from === notarisChatConfig.userId;
        const cls = m.from === 'system' ? 'left' : (isMe ? 'right' : 'left');
        const bubble = document.createElement('div');
        bubble.className = 'bubble ' + cls;
        bubble.textContent = m.text;
        chatArea.appendChild(bubble);
      });
      scrollNotarisToBottom();
    }

    function sendNotarisMessage(event) {
      event.preventDefault();
      event.stopPropagation();
      
      const input = document.getElementById('notarisMessageInput');
      const sendBtn = document.getElementById('notarisSendBtn');
      const text = input.value.trim();
      
      if(!text) return false;
      
      input.disabled = true;
      sendBtn.disabled = true;
      sendBtn.textContent = 'Mengirim...';
      
      addNotarisMessage(text, true);
      input.value = '';
      
      const formData = new FormData();
      formData.append('chatId', notarisChatConfig.chatId);
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
      scrollNotarisToBottom();
      
      // Start polling for new messages
      notarisPollInterval = setInterval(fetchNotarisMessages, 2000);
      
      // Override form submission
      const notarisChatForm = document.getElementById('notarisChatForm');
      if(notarisChatForm) {
        notarisChatForm.addEventListener('submit', sendNotarisMessage);
        console.log('Notaris chat real-time mode enabled');
      }
      
      const input = document.getElementById('notarisMessageInput');
      if(input) input.focus();
      
      // Cleanup on page unload
      window.addEventListener('beforeunload', function() {
        if(notarisPollInterval) clearInterval(notarisPollInterval);
      });
    });
  })();
  </script>


