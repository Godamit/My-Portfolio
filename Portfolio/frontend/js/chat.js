/*****************
 *  Cursor blob  *
 *****************/
const cursor = document.getElementById("cursor");
window.addEventListener("mousemove", e => {
  cursor.style.left = `${e.clientX}px`;
  cursor.style.top  = `${e.clientY}px`;
});

/*****************
 *  Chat logic   *
 *****************/
const chatBox   = document.getElementById("chatMessages");
const input     = document.getElementById("chatInput");
const serverURL = "../backend/chat.php";

/*— Send when button clicked —*/
function sendMessage() {
  const raw = input.value.trim();
  if (!raw) return;
  appendUserMsg(raw);
  input.value = "";
  askBackend(raw);
}

/*— Send on Enter —*/
input.addEventListener("keydown", e => {
  if (e.key === "Enter" && !e.shiftKey) {
    e.preventDefault();
    sendMessage();
  }
});

/*— Helpers —*/
function appendUserMsg(text) {
  const div = document.createElement("div");
  div.className = "message sent";
  div.textContent = text;
  chatBox.appendChild(div);
  chatBox.scrollTop = chatBox.scrollHeight;
}

function appendBotMsg_markdownTyping(markdown) {
  const div = document.createElement("div");
  div.className = "message received";
  chatBox.appendChild(div);
  chatBox.scrollTop = chatBox.scrollHeight;

  /* type-writer with markdown → html on each step */
  let i = 0, buffer = "";

  (function type() {
    if (i < markdown.length) {
      buffer += markdown.charAt(i++);
      div.innerHTML = marked.parse(buffer);   // convert & render
      chatBox.scrollTop = chatBox.scrollHeight;
      setTimeout(type, 25);                   // typing speed (ms)
    }
  })();
}

function askBackend(message) {
  const body = new FormData();
  body.append("message", message);

  fetch(serverURL, { method: "POST", body })
    .then(res => res.json())
    .then(({ reply }) => {
      appendBotMsg_markdownTyping(reply || "_(no reply)_");
    })
    .catch(err => {
      console.error(err);
      appendBotMsg_markdownTyping("**BOT:** Something went wrong!");
    });
}

/*— Clear chat button —*/
function clearChat() {
  chatBox.innerHTML = "";
}
