document.addEventListener("DOMContentLoaded", () => {
  const chatForm = document.getElementById("chatForm");
  const chatMessages = document.getElementById("chatMessages");
  if (!chatForm || !chatMessages) return;

  chatForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const messageInput = chatForm.elements["message"];
    const receiverInput = chatForm.elements["receiver_id"];
    const message = (messageInput?.value || '').trim();
    const receiver_id = parseInt(receiverInput?.value || '0', 10);
    if (!message || !receiver_id) return;

    appendMessage("You", message);
    messageInput.value = "";

    const fd = new FormData();
    fd.append("receiver_id", receiver_id);
    fd.append("message", message);
    await fetch('../php/chat/send_message.php', { method: 'POST', body: fd });
  });

  function appendMessage(sender, text) {
    const p = document.createElement("p");
    p.innerHTML = `<strong>${sender}:</strong> ${text}`;
    chatMessages.appendChild(p);
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }
});
