// Chat page for user
let chatConfig = {};
let lastMessageCount = 0;
let pollInterval = null;

function initChatPage(config) {
    chatConfig = config;
    lastMessageCount = config.initialCount;
    scrollToBottom();
    hideTyping();
    
    // Poll for new messages every 2 seconds
    pollInterval = setInterval(fetchMessages, 2000);
    
    // Focus input
    const input = document.getElementById('messageInput');
    if(input) input.focus();
}

function scrollToBottom() {
    const chatArea = document.getElementById('chatArea');
    if(chatArea) {
        chatArea.scrollTop = chatArea.scrollHeight;
    }
    
    // Also scroll typing indicator into view if visible
    const typingIndicator = document.getElementById('typingIndicator');
    if(typingIndicator && typingIndicator.style.display === 'block') {
        typingIndicator.scrollIntoView({ behavior: 'smooth', block: 'end' });
    }
}

function showTyping() {
    const indicator = document.getElementById('typingIndicator');
    if(indicator) {
        indicator.style.display = 'block';
        scrollToBottom();
    }
}

function hideTyping() {
    const indicator = document.getElementById('typingIndicator');
    if(indicator) {
        indicator.style.display = 'none';
    }
}

function addMessage(text, isMe = true) {
    const chatArea = document.getElementById('chatArea');
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
                    // New messages arrived
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
    
    const input = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const text = input.value.trim();
    
    if(!text) return false;
    
    // Disable input and button
    input.disabled = true;
    sendBtn.disabled = true;
    sendBtn.textContent = 'Mengirim...';
    
    // Add user message immediately with animation
    addMessage(text, true);
    
    // Clear input
    input.value = '';
    
    // Send to server via AJAX
    const formData = new FormData();
    formData.append('chatId', chatConfig.chatId);
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
    if(pollInterval) {
        clearInterval(pollInterval);
    }
});
