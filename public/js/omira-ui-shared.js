/* Shared SweetAlert behavior. Flash success is server-confirmed; not shown on submit. */
(function () {
    'use strict';
    const visual = {
        popup: 'omira-swal-popup',
        title: 'omira-swal-title',
        htmlContainer: 'omira-swal-text',
        confirmButton: 'omira-swal-confirm',
        cancelButton: 'omira-swal-cancel'
    };
    window.OmiraAlert = {
        confirmDelete: function (title, text, confirmText) {
            if (!window.Swal) return Promise.resolve({ isConfirmed: false });
            return Swal.fire({
                icon: 'warning',
                title: title || 'Hapus data?',
                text: text || 'Data yang dihapus tidak dapat dikembalikan.',
                showCancelButton: true,
                confirmButtonText: confirmText || 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: visual
            });
        },
        success: function (message) {
            if (!window.Swal || typeof message !== 'string' || !message.trim()) return;
            // Static artwork, with message injected as textContent (never as HTML).
            const confetti = [
                ['8%', '36%', '-22deg', '.05s', '8px', '8px', '#d9f5dc'],
                ['17%', '13%', '28deg', '.15s', '8px', '8px', '#b5eeb9'],
                ['28%', '24%', '-35deg', '.11s', '5px', '10px', '#e3f8e4'],
                ['77%', '14%', '35deg', '.10s', '7px', '12px', '#cbf2cc'],
                ['90%', '32%', '-25deg', '.19s', '10px', '9px', '#def7df'],
                ['85%', '68%', '22deg', '.14s', '6px', '9px', '#b9ecc0'],
                ['10%', '73%', '38deg', '.18s', '6px', '11px', '#d7f4d8'],
                ['24%', '86%', '-15deg', '.17s', '5px', '5px', '#e3f7e5'],
                ['70%', '83%', '-22deg', '.09s', '6px', '6px', '#caefcb'],
                ['59%', '5%', '30deg', '.25s', '5px', '8px', '#e0f6e1']
            ].map(function (c) {
                return '<span class="omira-success-confetti" style="--x:' + c[0]
                    + ';--y:' + c[1] + ';--rotation:' + c[2] + ';--delay:' + c[3]
                    + ';--w:' + c[4] + ';--h:' + c[5] + ';--color:' + c[6] + '"></span>';
            }).join('');
            const artwork = '<div class="omira-success-content">'
                + '<div class="omira-success-art" aria-hidden="true">' + confetti
                + '<div class="omira-success-circle">'
                + '<svg class="omira-success-check" viewBox="0 0 64 64" aria-hidden="true">'
                + '<path d="M14 33 L27 45 L51 20"/></svg></div></div>'
                + '<div class="omira-success-heading">Berhasil!</div>'
                + '<p class="omira-success-message"></p></div>';
            return Swal.fire({
                html: artwork,
                timer: 2000,
                timerProgressBar: false,
                // Keep SweetAlert2 in control of the popup lifecycle; artwork animates inside.
                showClass: { popup: '' },
                hideClass: { popup: '' },
                showConfirmButton: false,
                showCancelButton: false,
                allowEscapeKey: true,
                heightAuto: false,
                customClass: {
                    popup: 'omira-swal-popup omira-swal-success'
                },
                didOpen: function (popup) {
                    const text = popup.querySelector('.omira-success-message');
                    if (text) text.textContent = message;
                    // Fallback only if the SweetAlert2 timer fails; never close a newer dialog.
                    popup.omiraCloseFallback = window.setTimeout(function () {
                        if (window.Swal && Swal.getPopup() === popup) Swal.close();
                    }, 2100);
                },
                willClose: function (popup) {
                    window.clearTimeout(popup.omiraCloseFallback);
                }
            });
        }
    };
    function showFlash() {
        const message = window.OMIRA_SUCCESS_MESSAGE;
        if (typeof message === 'string' && message.trim()) {
            window.OmiraAlert.success(message);
        }
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', showFlash, {once:true});
    else showFlash();
})();
