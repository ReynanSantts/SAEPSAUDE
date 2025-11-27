document.addEventListener('DOMContentLoaded', function() {
    const loginPopup = document.getElementById('login-popup');
    const openLoginBtn = document.getElementById('open-login-popup');
    const closeLoginBtn = document.getElementById('close-login-popup');
    const cancelLoginBtn = document.getElementById('cancel-login-popup');

    if (openLoginBtn) {
        openLoginBtn.addEventListener('click', () => {
            if (loginPopup) loginPopup.style.display = 'flex';
        });
    }

    if (closeLoginBtn) {
        closeLoginBtn.addEventListener('click', () => {
            if (loginPopup) loginPopup.style.display = 'none';
        });
    }

    if (cancelLoginBtn) {
        cancelLoginBtn.addEventListener('click', () => {
            if (loginPopup) loginPopup.style.display = 'none';
        });
    }

    if (loginPopup) {
        loginPopup.addEventListener('click', (e) => {
            if (e.target === loginPopup) {
                loginPopup.style.display = 'none';
            }
        });
    }
});
