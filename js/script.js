document.addEventListener('DOMContentLoaded', () => {
    // Get DOM elements
    const menuBtn = document.getElementById('menu-btn');
    const closeBtn = document.getElementById('close-btn');
    const sideBar = document.querySelector('.side-bar');
    const toggleBtn = document.getElementById('toggle-btn');
    const userBtn = document.getElementById('user-btn');
    const profileDropdown = document.querySelector('.profile-dropdown');
    const openChatbotBtn = document.getElementById('open-chatbot-btn');
    const closeChatbotBtn = document.getElementById('close-chatbot-btn');
    const chatbot = document.getElementById('chatbot');
    const sendChatbotMessageBtn = document.getElementById('send-chatbot-message');
    const messageInput = document.getElementById('chatbot-message-input');
    const messagesContainer = document.getElementById('chatbot-messages');
    const body = document.body;

    // Sidebar open/close toggle
    const toggleSidebar = () => {
        sideBar.classList.toggle('open');
        body.classList.toggle('sidebar-open');
        closeBtn.style.display = sideBar.classList.contains('open') ? 'block' : 'none';
    };

    menuBtn.addEventListener('click', toggleSidebar);
    closeBtn.addEventListener('click', () => {
        sideBar.classList.remove('open');
        body.classList.remove('sidebar-open');
        closeBtn.style.display = 'none';
    });

    // Dark Mode toggle
    const darkModeState = localStorage.getItem('dark-mode');

    const enableDarkMode = () => {
        toggleBtn.classList.replace('fa-sun', 'fa-moon');
        body.classList.add('dark-mode');
        localStorage.setItem('dark-mode', 'enabled');
    };

    const disableDarkMode = () => {
        toggleBtn.classList.replace('fa-moon', 'fa-sun');
        body.classList.remove('dark-mode');
        localStorage.setItem('dark-mode', 'disabled');
    };

    if (darkModeState === 'enabled') {
        enableDarkMode();
    }

    toggleBtn.addEventListener('click', () => {
        const currentState = localStorage.getItem('dark-mode');
        currentState === 'enabled' ? disableDarkMode() : enableDarkMode();
    });

    // Profile dropdown toggle
    userBtn.addEventListener('click', () => {
        profileDropdown.style.display = profileDropdown.style.display === 'block' ? 'none' : 'block';
    });

    // Close profile dropdown if clicked outside
    document.addEventListener('click', (e) => {
        if (!userBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
            profileDropdown.style.display = 'none';
        }
    });

    // Open Chatbot
    openChatbotBtn.addEventListener('click', () => {
        chatbot.style.display = 'flex';
        openChatbotBtn.style.display = 'none';
    });

    // Close Chatbot
    closeChatbotBtn.addEventListener('click', () => {
        chatbot.style.display = 'none';
        openChatbotBtn.style.display = 'block';
    });

    // Send Message on Button Click
    sendChatbotMessageBtn.addEventListener('click', () => {
        sendMessage('text'); // Default is text message
    });

    // Send Message on Enter Key
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage('text');
        }
    });

    // Function to append messages to chat window
    function appendMessage(sender, content, isImage = false) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', sender);

        if (isImage) {
            const img = document.createElement('img');
            img.src = content;
            img.alt = 'Image response';
            img.classList.add('message-image');
            messageDiv.appendChild(img);
        } else {
            const messageContent = document.createElement('div');
            messageContent.classList.add('message-content');
            messageContent.textContent = content;
            messageDiv.appendChild(messageContent);
        }

        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Function to send message to chatbot
    function sendMessage(type) {
        const userMessage = messageInput.value.trim();
        if (userMessage === '') return; // Don't send empty messages

        // Append user's message to the chat
        appendMessage('user', userMessage);

        // Prepare data for chatbot
        const data = {
            message: userMessage,
            type: type // Pass type as 'text' or 'image'
        };

        // Send message to chatbot via POST request
        fetch('chatbot.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (type === 'text') {
                appendMessage('bot', data.response || 'Sorry, I did not understand that.');
            } else if (type === 'image') {
                appendMessage('bot', data.image_url || 'Sorry, I could not generate the image.', true);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            appendMessage('bot', 'Sorry, there was an error processing your request.');
        });

        // Clear the input field after sending the message
        messageInput.value = '';
    }
window.botpressWebChat.sendEvent({ type: 'hide' });

});
