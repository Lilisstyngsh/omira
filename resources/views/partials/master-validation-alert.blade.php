{{-- Validasi Master Plant / Line; SweetAlert2 already loaded by parent view. --}}
<style>
    /* Above Master add/edit modals (their overlays use z-index: 9999). */
    .omira-master-warning-layer { z-index: 20000 !important; }
    .omira-master-warning-popup {
        width: 340px !important;
        padding: 24px 24px 22px !important;
        border-radius: 22px !important;
        box-shadow: 0 20px 55px rgba(15,23,42,.20) !important;
    }
    .omira-master-warning-popup .swal2-icon {
        border: none !important;
        width: 88px !important;
        height: 76px !important;
        margin: 2px auto 14px !important;
    }
    .omira-master-warning-popup .swal2-icon svg { width: 78px; height: 72px; }
    .omira-master-warning-title {
        font-size: 18px !important;
        font-weight: 700 !important;
        color: #26342d !important;
        padding: 0 !important;
    }
    .omira-master-warning-text {
        font-size: 13px !important;
        line-height: 1.55 !important;
        color: #475569 !important;
    }
    .omira-master-warning-confirm {
        min-width: 105px;
        min-height: 38px;
        padding: 8px 20px;
        border: none;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        background: #5f9278;
    }
    .omira-master-warning-confirm:hover { background: #49775f; }
    @keyframes omiraMasterWarningEnter {
        0% { opacity: 0; transform: translateY(12px) scale(.9); }
        70% { opacity: 1; transform: translateY(0) scale(1.025); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes omiraMasterWarningExit {
        to { opacity: 0; transform: translateY(8px) scale(.95); }
    }
    .omira-master-warning-enter { animation: omiraMasterWarningEnter .3s ease-out both; }
    .omira-master-warning-exit { animation: omiraMasterWarningExit .18s ease-in both; }
    @media (prefers-reduced-motion: reduce) {
        .omira-master-warning-enter, .omira-master-warning-exit { animation: none; }
    }
    /* Form errors on these two pages appear in SweetAlert, not a duplicate banner. */
    .content > .alert-danger { display: none; }
</style>
<script>
    function showOmiraMasterWarning(message) {
        return Swal.fire({
            icon: 'warning',
            iconHtml: `<svg viewBox="0 0 80 74" fill="none" aria-hidden="true"><path d="M35.7 11.5c2-3.5 6.6-3.5 8.6 0L75 64c2 3.5-.5 7.5-4.5 7.5h-61C5.5 71.5 3 67.5 5 64L35.7 11.5Z" stroke="#dc4545" stroke-width="5" stroke-linejoin="round"/><path d="M40 31v17" stroke="#dc4545" stroke-width="5" stroke-linecap="round"/><circle cx="40" cy="57" r="3" fill="#dc4545"/></svg>`,
            title: String(message).toLowerCase().includes('sudah terdaftar') ? 'Data Sudah Terdaftar' : 'Periksa Data',
            text: message,
            confirmButtonText: 'OK',
            showCancelButton: false,
            buttonsStyling: false,
            allowOutsideClick: false,
            showClass: { popup: 'omira-master-warning-enter' },
            hideClass: { popup: 'omira-master-warning-exit' },
            customClass: {
                container: 'omira-master-warning-layer',
                popup: 'omira-master-warning-popup',
                title: 'omira-master-warning-title',
                htmlContainer: 'omira-master-warning-text',
                confirmButton: 'omira-master-warning-confirm'
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function () {
        const serverError = @json($errors->first());
        const invalidMasterForm = @json(old('_master_form'));
        const invalidModalId = {
            plant: 'addPlantModal',
            line: 'addLineModal',
            'edit-plant': 'editPlantModal'
        }[invalidMasterForm];

        function hideMasterModal(modal) {
            if (modal) modal.style.display = 'none';
        }
        function restoreMasterModal(modal, input) {
            if (modal) modal.style.display = 'flex';
            if (input) input.focus();
        }

        // After a rejected POST, show the warning FIRST. Reopen the old form on OK
        // with its previously submitted values still intact.
        if (serverError) {
            const invalidModal = invalidModalId ? document.getElementById(invalidModalId) : null;
            hideMasterModal(invalidModal);
            showOmiraMasterWarning(serverError).then(function () {
                restoreMasterModal(invalidModal, invalidModal?.querySelector('input[name="name"]'));
            });
        }

        document.querySelectorAll('form[data-master-name-form]').forEach(function (form) {
            form.setAttribute('novalidate', 'novalidate');
            form.addEventListener('submit', function (event) {
                const input = form.querySelector('input[name="name"]');
                if (input && !input.value.trim()) {
                    event.preventDefault();
                    const modal = form.closest('.modal-overlay, .edit-overlay');
                    hideMasterModal(modal);
                    showOmiraMasterWarning('Nama ' + (form.dataset.masterNameForm === 'line' ? 'Line' : 'Plant') + ' wajib diisi.')
                        .then(function () { restoreMasterModal(modal, input); });
                } else if (!event.defaultPrevented) {
                    // Close the add/edit modal immediately after a valid submission.
                    hideMasterModal(form.closest('.modal-overlay, .edit-overlay'));
                }
            });
        });
    });
</script>
