<!DOCTYPE html>
<html>
<head>
    <title>Mascot AI</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #121212;
            color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .chat-container {
            width: 400px;
            background: #1e1e1e;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            background: #282c34;
            color: #61dafb;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 18px;
        }

        #chat-box {
            padding: 15px;
            height: 400px;
            overflow-y: auto;
            flex-grow: 1;
        }

        .message {
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
        }

        .message.user {
            justify-content: flex-end;
        }

        .message.bot {
            justify-content: flex-start;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .message.user .avatar {
            margin-left: 8px;
            margin-right: 0;
        }

        .bubble {
            padding: 10px 14px;
            border-radius: 18px;
            max-width: 70%;
            font-size: 14px;
        }

        .user .bubble {
            background: #0078d7;
            color: white;
        }

        .bot .bubble {
            background: #2f333d;
            color: #ddd;
        }

        .chat-input {
            display: flex;
            border-top: 1px solid #333;
            background-color: #1e1e1e;
        }

        .chat-input input {
            flex: 1;
            padding: 12px;
            background: #121212;
            border: none;
            color: #ddd;
            font-size: 14px;
        }

        .chat-input button {
            padding: 12px 16px;
            background: #61dafb;
            color: #1e1e1e;
            border: none;
            cursor: pointer;
        }

        .chat-input button:hover {
            background: #21a1f1;
        }

      
        .typing {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 4px;
        }

        .typing span {
            height: 6px;
            width: 6px;
            background-color: #ccc;
            border-radius: 50%;
            display: inline-block;
            animation: blink 1.2s infinite ease-in-out;
        }

        .typing span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes blink {
            0%, 80%, 100% {
                opacity: 0;
            }
            40% {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">Mascot AI</div>
        <div id="chat-box"></div>
        <div class="chat-input">
            <input type="text" id="user-input" placeholder="Type a message..." />
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script>
        async function sendMessage() {
            const input = document.getElementById("user-input");
            const message = input.value.trim();
            if (!message) return;

            appendMessage("user", message);
            input.value = "";

            appendTyping();

            const response = await fetch("chat.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: `message=${encodeURIComponent(message)}`
            });

            const data = await response.json();

            removeTyping();
            appendMessage("bot", data.reply);
        }

        function appendMessage(role, text) {
            const chatBox = document.getElementById("chat-box");

            const messageDiv = document.createElement("div");
            messageDiv.className = `message ${role}`;

            const avatar = document.createElement("img");
            avatar.className = "avatar";
            avatar.src = role === "user"
                ? "user.jpg"       
                : "mascot.jpg";   
            const bubble = document.createElement("div");
            bubble.className = "bubble";
            bubble.textContent = text;

            if (role === "user") {
                messageDiv.appendChild(bubble);
                messageDiv.appendChild(avatar);
            } else {
                messageDiv.appendChild(avatar);
                messageDiv.appendChild(bubble);
            }

            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function appendTyping() {
            const chatBox = document.getElementById("chat-box");

            const messageDiv = document.createElement("div");
            messageDiv.className = "message bot";
            messageDiv.id = "typing-placeholder";

            const avatar = document.createElement("img");
            avatar.className = "avatar";
            avatar.src = "mascot.jpg";

            const typingAnim = document.createElement("div");
            typingAnim.className = "bubble typing";
            typingAnim.innerHTML = `<span></span><span></span><span></span>`;

            messageDiv.appendChild(avatar);
            messageDiv.appendChild(typingAnim);
            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function removeTyping() {
            const typing = document.getElementById("typing-placeholder");
            if (typing) typing.remove();
        }
    </script>
</body>
</html>
