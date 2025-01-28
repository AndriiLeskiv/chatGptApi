<style>
    #chatContainer {
        width: 400px;
        height: 500px;
        border: 1px solid #ccc;
        padding: 20px;
        display: flex;
        flex-direction: column;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
        margin: 10% auto;
    }
    #chatHeader {
        text-align: center;
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 10px;
        color: #333;
    }
    #messagesContainer {
        display: flex;
        flex-grow: 1;
        overflow-y: auto;
        flex-direction: column;
        border: 1px solid #ccc;
        background: #fff;
        border-radius: 5px;
    }
    .message {
        padding: 10px;
        border-radius: 10px;
        margin: 5px 0;
        max-width: 70%;
    }
    .userMessage {
        background-color: #b3d9ff;
        align-self: flex-end;
        margin-right: 10px;
    }
    .botResponse {
        background-color: #e6e6e6;
        align-self: flex-start;
        margin-left: 10px;
    }
    #chatForm {
        display: flex;
        flex-direction: column;
        margin-top: 20px;
    }
    #userMessage {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        resize: none;
        height: 80px;
    }
    button {
        padding: 10px;
        background-color: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    button:hover {
        background-color: #45a049;
    }
</style>
<div id="chatContainer">
    <div id="chatHeader">Chat with a bot</div>
    <div id="messagesContainer"></div>
    <form id="chatForm">
        <textarea id="userMessage" name="userMessage" required></textarea>
        <button type="submit">Send</button>
    </form>
</div>

<script>
    document.getElementById('chatContainer').addEventListener('submit', async function(e) {
        e.preventDefault();
        const message = document.getElementById('userMessage').value;

        const userMessageDiv = document.createElement('div');
        userMessageDiv.classList.add('message', 'userMessage');
        userMessageDiv.textContent = message;
        document.getElementById('messagesContainer').appendChild(userMessageDiv);


        document.getElementById('messagesContainer').scrollTop = document.getElementById('messagesContainer').scrollHeight;

        fetch('function.php', {
            method: 'POST',
            body: new URLSearchParams({
                'message': message,
                'action' : 'get_chat_response'
            })
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    const botMessageDiv = document.createElement('div');
                    botMessageDiv.classList.add('message', 'botResponse');
                    botMessageDiv.textContent = data.response;
                    document.getElementById('messagesContainer').appendChild(botMessageDiv);
                }else{
                    const errorDiv = document.createElement('div');
                    errorDiv.classList.add('message', 'botResponse');
                    errorDiv.textContent = 'Error: ' + data.error;
                    document.getElementById('messagesContainer').appendChild(errorDiv);
                }
                document.getElementById('userMessage').value = '';
            })
            .catch((error) => {
                console.error('Error:', error);
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('message', 'botResponse');
                errorDiv.textContent = 'Error sending request!';
                document.getElementById('messagesContainer').appendChild(errorDiv);

                document.getElementById('userMessage').value = '';
                document.getElementById('messagesContainer').scrollTop = document.getElementById('messagesContainer').scrollHeight;
            });
    });
</script>
