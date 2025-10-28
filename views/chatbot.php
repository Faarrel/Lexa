  <?php 
  if(!$user){ header('Location:?page=login'); exit; }
  
  if(!isset($_SESSION['bot_msgs']) || empty($_SESSION['bot_msgs'])){
      $_SESSION['bot_msgs'] = [[
          'from'=>'bot',
          'text'=>'Halo! 👋 Saya Lexa, asisten virtual untuk layanan Notaris Digital. Saya siap membantu Anda dengan pertanyaan seputar notaris, akta, dan legalitas dokumen. Apa yang bisa saya bantu hari ini?'
      ]];
  }
  
  $msgs = $_SESSION['bot_msgs'];
  ?>
  <h2 class="page-title">Lexa - AI Assistant 🤖</h2>
  <div class="chat-area" id="chatArea">
    <?php foreach($msgs as $m): ?>
      <div class="bubble <?= $m['from']==='bot' ? 'left' : 'right' ?>">
        <?=nl2br(h($m['text']))?>
      </div>
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
  
  <form id="chatForm" onsubmit="return sendBotMessage(event);">
    <input name="bot_text" id="botInput" placeholder="Tanyakan tentang notaris, akta, legalitas..." required autocomplete="off" />
    <div style="display:flex; gap:8px;">
      <button class="btn-primary" type="submit" id="sendBtn" style="flex:1;">Kirim</button>
      <a class="btn-secondary" href="?page=app" style="width:auto; padding:16px;">← Kembali</a>
    </div>
  </form>
  
  <?php 
  // Check if any message has redirect action - show button AFTER form
  $hasRedirect = false;
  foreach($msgs as $m) {
    if(isset($m['action']) && $m['action'] === 'redirect') {
      $hasRedirect = true;
      break;
    }
  }
  if($hasRedirect): ?>
    <div id="redirectButton" style="text-align:center; margin:16px 0;">
      <a href="?page=pilih_notaris" class="btn-primary" style="display:inline-block; background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); padding: 16px 24px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3); text-decoration: none;">
        📞 Chat dengan Notaris Sekarang
      </a>
    </div>
  <?php endif; ?>
  
  <div style="margin-top:12px; text-align:center;">
    <button onclick="showClearModal()" class="btn-ghost btn-small">🗑️ Hapus Riwayat Chat</button>
  </div>

  <!-- Modal Konfirmasi Hapus -->
  <div id="clearModal" class="modal">
    <div class="modal-overlay" onclick="hideClearModal()"></div>
    <div class="modal-content">
      <div class="modal-header">
        <h3>🗑️ Hapus Riwayat Chat?</h3>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus semua riwayat percakapan dengan Lexa?</p>
        <p style="color: var(--text-light); font-size: 14px; margin-top: 8px;">Tindakan ini tidak dapat dibatalkan.</p>
      </div>
      <div class="modal-footer">
        <button onclick="hideClearModal()" class="btn-secondary">Batal</button>
        <button onclick="confirmClearChat()" class="btn-danger">Hapus</button>
      </div>
    </div>
  </div>

  <script>
    // Auto scroll to bottom
    function scrollToBottom() {
      const chatArea = document.getElementById('chatArea');
      chatArea.scrollTop = chatArea.scrollHeight;
      
      // Also scroll typing indicator into view if visible
      const typingIndicator = document.getElementById('typingIndicator');
      if(typingIndicator.style.display === 'block') {
        typingIndicator.scrollIntoView({ behavior: 'smooth', block: 'end' });
      }
    }
    
    // Show typing indicator
    function showTyping() {
      document.getElementById('typingIndicator').style.display = 'block';
      scrollToBottom();
    }
    
    // Hide typing indicator
    function hideTyping() {
      document.getElementById('typingIndicator').style.display = 'none';
    }
    
    // Add message bubble to chat
    function addMessage(text, isBot = false) {
      const chatArea = document.getElementById('chatArea');
      const bubble = document.createElement('div');
      bubble.className = 'bubble ' + (isBot ? 'left' : 'right');
      bubble.style.opacity = '0';
      bubble.style.transform = 'translateY(10px)';
      bubble.style.transition = 'all 0.3s ease';
      bubble.innerHTML = text.replace(/\n/g, '<br>');
      chatArea.appendChild(bubble);
      
      // Trigger animation
      setTimeout(() => {
        bubble.style.opacity = '1';
        bubble.style.transform = 'translateY(0)';
      }, 10);
      
      scrollToBottom();
      return bubble;
    }
    
    // Send message via AJAX
    function sendBotMessage(event) {
      event.preventDefault();
      
      const input = document.getElementById('botInput');
      const sendBtn = document.getElementById('sendBtn');
      const text = input.value.trim();
      
      if(!text) return false;
      
      // Disable input and button
      input.disabled = true;
      sendBtn.disabled = true;
      sendBtn.textContent = 'Mengirim...';
      
      // Add user message immediately
      addMessage(text, false);
      
      // Clear input
      input.value = '';
      
      // Show typing indicator after short delay
      setTimeout(() => {
        showTyping();
      }, 300);
      
      // Send to server
      const formData = new FormData();
      formData.append('bot_text', text);
      
      fetch('?api=bot_chat', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        hideTyping();
        
        if(data.success) {
          // Add bot response with delay
          setTimeout(() => {
            addMessage(data.response, true);
            
            // Check if needs redirect to notaris
            if(data.redirect) {
              setTimeout(() => {
                // Remove existing redirect button if any
                const existingBtn = document.getElementById('redirectButton');
                if(existingBtn) {
                  existingBtn.remove();
                }
                
                // Add new redirect button AFTER form (not in chatArea)
                const redirectDiv = document.createElement('div');
                redirectDiv.id = 'redirectButton';
                redirectDiv.style.textAlign = 'center';
                redirectDiv.style.margin = '16px 0';
                redirectDiv.innerHTML = '<a href="?page=pilih_notaris" class="btn-primary" style="display:inline-block; background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); padding: 16px 24px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3); text-decoration: none; color: white;">📞 Chat dengan Notaris Sekarang</a>';
                
                // Insert after form, before clear button
                const chatForm = document.getElementById('chatForm');
                chatForm.parentNode.insertBefore(redirectDiv, chatForm.nextSibling);
              }, 500);
            }
          }, 500);
        } else {
          addMessage('Maaf, terjadi kesalahan. Silakan coba lagi.', true);
        }
        
        // Re-enable input and button
        input.disabled = false;
        sendBtn.disabled = false;
        sendBtn.textContent = 'Kirim';
        input.focus();
      })
      .catch(err => {
        console.error('Error:', err);
        hideTyping();
        addMessage('Maaf, terjadi kesalahan koneksi. Silakan coba lagi.', true);
        
        // Re-enable input and button
        input.disabled = false;
        sendBtn.disabled = false;
        sendBtn.textContent = 'Kirim';
        input.focus();
      });
      
      return false;
    }
    
    // Modal functions
    function showClearModal() {
      document.getElementById('clearModal').classList.add('show');
    }
    
    function hideClearModal() {
      document.getElementById('clearModal').classList.remove('show');
    }
    
    // Clear chat history
    function confirmClearChat() {
      fetch('?api=clear_bot_chat', {method: 'POST'})
        .then(() => location.reload());
    }
    
    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
      if(e.key === 'Escape') {
        hideClearModal();
      }
    });
    
    // Initial setup
    scrollToBottom();
    document.getElementById('botInput').focus();
    hideTyping();
  </script>

