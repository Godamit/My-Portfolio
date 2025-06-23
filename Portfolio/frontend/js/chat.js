const cursor = document.getElementById("cursor");
window.addEventListener("mousemove", (e) => {
  cursor.style.left = `${e.clientX}px`;
  cursor.style.top = `${e.clientY}px`;
});

function sendMessage() {
  const input = document.getElementById("chatInput");
  const message = input.value.trim();
  if (!message) return;

  const chatBox = document.getElementById("chatMessages");
  const serverPath = "../backend/chat.php";

  // Add user's message to chat
  const userMsg = document.createElement("div");
  userMsg.className = "message sent";
  userMsg.innerText = message;
  chatBox.appendChild(userMsg);
  chatBox.scrollTop = chatBox.scrollHeight;

  input.value = "";

  // Send message to PHP backend
  const messageData = new FormData();
  messageData.append("message", message);

  fetch(serverPath, {
      method: "POST",
      body: messageData
  })
  .then(response => response.json())
  .then(data => {
      const reply = data.reply || "Sorry, I didn't get that.";
      
      const botMsg = document.createElement("div");
      botMsg.className = "message received";
      chatBox.appendChild(botMsg);

      let i = 0;

      setTimeout(() => {
        let i = 0;
        const typeWriter = () => {
            if (i < reply.length) {
                botMsg.textContent += reply.charAt(i); // ✅ use textContent here
                i++;
                setTimeout(typeWriter, 30); // Typing speed
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        };
        typeWriter();
    }, 600); 

      // typeWriter();
  })
  .catch(error => {
      console.error("Error:", error);
      const errorMsg = document.createElement("div");
      errorMsg.className = "message received";
      errorMsg.innerText = "BOT: Something went wrong!";
      chatBox.appendChild(errorMsg);
  });
}



function clearChat() {
  document.getElementById("chatMessages").innerHTML = "";
}