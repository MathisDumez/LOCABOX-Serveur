function simpleConfirm(message, callback) {
    const popup = document.getElementById("simple-confirm");
    const msg = document.getElementById("confirm-message");
    const yes = document.getElementById("btn-yes");
    const no = document.getElementById("btn-no");
  
    msg.textContent = message;
    popup.style.display = "flex";
  
    function clean() {
      popup.style.display = "none";
      yes.removeEventListener("click", onYes);
      no.removeEventListener("click", onNo);
    }
  
    function onYes() {
      clean();
      callback(true);
    }
  
    function onNo() {
      clean();
      callback(false);
    }
  
    yes.addEventListener("click", onYes);
    no.addEventListener("click", onNo);
  }