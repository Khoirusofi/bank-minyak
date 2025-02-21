
document.addEventListener("DOMContentLoaded", function() {
    let notifPopup = document.getElementById('notif-popup');
    let notifMessage = document.getElementById("notif-message");

    if (notifPopup) {
        setTimeout(() => {
            notifPopup.classList.remove('opacity-0');
            notifPopup.classList.add('opacity-100');
        }, 200);

        setTimeout(() => {
            notifPopup.classList.remove('opacity-100');
            notifPopup.classList.add('opacity-0');

            setTimeout(() => notifPopup.remove(), 500);
        }, 7000);
    }

    if (notifMessage) {
        setTimeout(() => {
            notifMessage.classList.remove("opacity-0");
            notifMessage.classList.add("opacity-100");
        }, 100);

        setTimeout(() => {
            notifMessage.classList.remove("opacity-100");
            notifMessage.classList.add("opacity-0");

            setTimeout(() => notifMessage.remove(), 500);
        }, 7000);
    }
});

document.querySelectorAll('input[name="method"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        if (radio.value === 'transfer') {
            document.getElementById('transferDetails').classList.remove('hidden');
        } else {
            document.getElementById('transferDetails').classList.add('hidden');
        }
    });
});

document.querySelectorAll('img').forEach((img) => {
  img.addEventListener('dragstart', (event) => {
      event.preventDefault(); // Mencegah drag
  });

  img.addEventListener('contextmenu', (event) => {
      event.preventDefault(); // Mencegah klik kanan
  });
});
