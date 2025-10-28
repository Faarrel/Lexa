// Chat page for notaris
let notarisChatConfig = {};
let notarisLastMessageCount = 0;
let notarisPollInterval = null;

function initNotarisChatPage(config) {
    notarisChatConfig = config;
    notarisLastMessageCount = config.initialCount;
    scrollNotarisToBottom();
    hideNotarisTyping();
    
    // Poll for new messages every 2 seconds
    notarisPollInterval = setInterval(fetchNotarisMessages, 2000);
    
    // Focus input
    const input = document.getElementById('notarisMessageInput');
    if(input) input.focus();
}

function scrollNotarisToBottom() {
    const chatArea = document.getElementById('chatAreaNotaris');
    if(chatArea) {
        chatArea.scrollTop = chatArea.scrollHeight;
    }
    
    // Also scroll typing indicator into view if visible
    const typingIndicator = document.getElementById('notarisTypingIndicator');
    if(typingIndicator && typingIndicator.style.display === 'block') {
        typingIndicator.scrollIntoView({ behavior: 'smooth', block: 'end' });
    }
}

function showNotarisTyping() {
    const indicator = document.getElementById('notarisTypingIndicator');
    if(indicator) {
        indicator.style.display = 'block';
        scrollNotarisToBottom();
    }
}

function hideNotarisTyping() {
    const indicator = document.getElementById('notarisTypingIndicator');
    if(indicator) {
        indicator.style.display = 'none';
    }
}

function addNotarisMessage(text, isMe = true) {
    const chatArea = document.getElementById('chatAreaNotaris');
    if(!chatArea) return;
    
    const bubble = document.createElement('div');
    bubble.className = 'bubble ' + (isMe ? 'right' : 'left');
    bubble.style.opacity = '0';
    bubble.style.transform = 'translateY(10px)';
    bubble.style.transition = 'all 0.3s ease';
    bubble.textContent = text;
    chatArea.appendChild(bubble);
    
    // Trigger animation
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
                    // New messages arrived
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
    
    const input = document.getElementById('notarisMessageInput');
    const sendBtn = document.getElementById('notarisSendBtn');
    const text = input.value.trim();
    
    if(!text) return false;
    
    // Disable input and button
    input.disabled = true;
    sendBtn.disabled = true;
    sendBtn.textContent = 'Mengirim...';
    
    // Add notaris message immediately with animation
    addNotarisMessage(text, true);
    
    // Clear input
    input.value = '';
    
    // Send to server via AJAX
    const formData = new FormData();
    formData.append('chatId', notarisChatConfig.chatId);
    formData.append('text', text);
    
    fetch('?api=send_message', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            // Message sent successfully
            // The message will appear via polling
        } else {
            alert('Gagal mengirim pesan: ' + (data.error || 'Unknown error'));
        }
        
        // Re-enable input and button
        input.disabled = false;
        sendBtn.disabled = false;
        sendBtn.textContent = 'Kirim';
        input.focus();
    })
    .catch(err => {
        console.error('Error sending message:', err);
        alert('Terjadi kesalahan saat mengirim pesan');
        
        // Re-enable input and button
        input.disabled = false;
        sendBtn.disabled = false;
        sendBtn.textContent = 'Kirim';
        input.focus();
    });
    
    return false;
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if(notarisPollInterval) {
        clearInterval(notarisPollInterval);
    }
});
