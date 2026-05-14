document.addEventListener('DOMContentLoaded', function () {
    const batchForm = document.querySelector('form[action="index.php?action=add_inventory_batch"]');

    if (batchForm) {
        batchForm.addEventListener('submit', function (e) {
            const skuInput = batchForm.querySelector('input[name="sku_code"]');
            const skuSelect = batchForm.querySelector('select[name="sku_code"]');
            const typeInput = batchForm.querySelector('select[name="type"]');
            const quantityInput = batchForm.querySelector('input[name="quantity"]');

            const skuVal = skuInput ? skuInput.value : (skuSelect ? skuSelect.value : '');

            if (skuVal !== undefined && typeInput && quantityInput) {
                if (!skuVal.trim() || !typeInput.value || !quantityInput.value) {
                    e.preventDefault();
                    alert('Por favor, complete todos los campos del lote de inventario.');
                } else if (parseFloat(quantityInput.value) <= 0) {
                    e.preventDefault();
                    alert('La cantidad debe ser mayor a 0.');
                }
            }
        });
    }
});
