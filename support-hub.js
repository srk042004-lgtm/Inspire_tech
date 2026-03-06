// support-hub.js
(function() {
    // 1. Create the CSS
    const style = document.createElement('style');
    style.innerHTML = `
        .support-wrapper { position: fixed; bottom: 30px; left: 30px; z-index: 9999; font-family: 'Segoe UI', sans-serif; }
        .support-main-btn { width: 65px; height: 65px; background: linear-gradient(45deg, #00ffd5, #00a8ff); border: none; border-radius: 50%; color: #000; font-size: 24px; cursor: pointer; box-shadow: 0 10px 25px rgba(0, 255, 213, 0.4); position: relative; display: flex; align-items: center; justify-content: center; transition: 0.3s; }
        .support-main-btn:hover { transform: scale(1.1); }
        .ping { position: absolute; width: 100%; height: 100%; background: #00ffd5; border-radius: 50%; z-index: -1; animation: support-ping 2s infinite; }
        .support-menu { position: absolute; bottom: 80px; left: 0; width: 220px; background: #1e293b; border: 1px solid #334155; border-radius: 15px; padding: 10px; display: none; flex-direction: column; gap: 8px; box-shadow: 0 15px 35px rgba(0,0,0,0.5); }
        .support-menu.show { display: flex; animation: fadeInUp 0.3s; }
        .support-item { display: flex; align-items: center; gap: 12px; padding: 12px 15px; text-decoration: none; color: #f8fafc; border-radius: 10px; font-size: 14px; transition: 0.2s; }
        .support-item i { font-size: 18px; width: 25px; text-align: center; }
        .whatsapp:hover { background: rgba(37, 211, 102, 0.15); color: #25d366; }
        .phone:hover { background: rgba(0, 168, 255, 0.15); color: #00a8ff; }
        .email:hover { background: rgba(255, 255, 255, 0.1); color: #cbd5e1; }
        @keyframes support-ping {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(1.6); opacity: 0; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .d-none { display: none; }
    `;
    document.head.appendChild(style);

    // 2. Create the HTML
    const hub = document.createElement('div');
    hub.innerHTML = `
        <div class="support-wrapper">
            <div class="support-menu" id="supportMenu">
                <a href="https://wa.me/923462345453" target="_blank" class="support-item whatsapp"><i class="fab fa-whatsapp"></i><span>WhatsApp Support</span></a>
                <a href="tel:03462345453" class="support-item phone"><i class="fas fa-phone-alt"></i><span>Call Raheel Ahmad</span></a>
                <a href="mailto:support@inspiretech.com" class="support-item email"><i class="fas fa-envelope"></i><span>Email Inquiry</span></a>
            </div>

            <button class="support-main-btn" id="mainSupportBtn">
                <i class="fas fa-headset" id="supportIcon"></i>
                <i class="fas fa-times d-none" id="closeIcon"></i>
                <span class="ping"></span>
            </button>
        </div>
    `;
    document.body.appendChild(hub);

    // 3. The Logic
    const btn = document.getElementById('mainSupportBtn');
    const menu = document.getElementById('supportMenu');
    const sIcon = document.getElementById('supportIcon');
    const cIcon = document.getElementById('closeIcon');

    btn.onclick = function() {
        menu.classList.toggle('show');
        sIcon.classList.toggle('d-none');
        cIcon.classList.toggle('d-none');
    };
})();
