function initScanPage() {
    const quantityInput = document.getElementById('quantity_input');
    if (quantityInput) {
        quantityInput.focus();
        quantityInput.select();
    }
}

// Ejecutar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', initScanPage);
