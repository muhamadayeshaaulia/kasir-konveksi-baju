<?php include './app/app.php'; ?>
  <link rel="stylesheet" href="./style/chat.css">
  <div class="chat-container">
    <div class="chat-header">
      <h1>Forum Chat</h1>
    </div>

    <div id="chat-box"></div>

    <form id="chat-form">
      <input type="hidden" name="sender_id" value="<?= $_SESSION['user_id'] ?>">
      <textarea name="message" placeholder="Ketik pesan..." required></textarea>
      <button type="submit"
      <?php if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'Admin' && $_SESSION['level'] !== 'Owner' && $_SESSION['level'] !== 'Kasir') echo 'disabled style="opacity: 0.6; cursor: not-allowed;" title="Hanya admin, owner dan kasir yang bisa menyimpan"'; ?>>
      Kirim</button>
    </form>
  </div>

  <script>
  function loadChat() {
    fetch("./chat/get_forum_chat.php")
      .then(res => res.text())
      .then(data => {
        const chatBox = document.getElementById("chat-box");
        chatBox.innerHTML = data;
        chatBox.scrollTop = chatBox.scrollHeight;
      });
  }

  setInterval(loadChat, 1000);
  loadChat();

  const chatForm = document.getElementById("chat-form");
  const messageInput = chatForm.querySelector("textarea");

  function sendMessage() {
    fetch("./chat/send_message.php", {
      method: "POST",
      body: new FormData(chatForm)
    })
    .then(res => res.text())
    .then(res => {
      messageInput.value = "";
      loadChat();
      checkNewChatMessage();
    });
  }

  chatForm.onsubmit = function(e) {
    e.preventDefault();
    sendMessage();
  };

  messageInput.addEventListener("keydown", function(e) {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault();
      chatForm.requestSubmit(); 
    }
  });
</script>

