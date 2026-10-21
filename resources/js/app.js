const toggle = document.querySelector('[data-menu-toggle]');
const menu = document.querySelector('[data-menu]');

// Toggle menu navigasi mobile
if (toggle && menu) {
    toggle.addEventListener('click', () => {
        const isOpen = menu.classList.toggle('is-open');

        toggle.setAttribute('aria-expanded', String(isOpen));
    });
}

// Otomatis ubah input nomor uji dan nomor rangka menjadi huruf kapital
document
    .querySelectorAll(
        'input[name="nouji"], input[name="chassis_last5"]'
    )
    .forEach((input) => {
        input.addEventListener('input', () => {
            const start = input.selectionStart;
            const end = input.selectionEnd;

            input.value = input.value.toUpperCase();

            // Pertahankan posisi kursor saat mengetik
            if (start !== null && end !== null) {
                input.setSelectionRange(start, end);
            }
        });
    });
