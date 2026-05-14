document.addEventListener('DOMContentLoaded', () => {
    console.log("Biconoir's Final System Initialized");
});

function openEditModal(dish) {
    document.getElementById('edit_id').value = dish.id;
    document.getElementById('edit_name').value = dish.name;
    document.getElementById('edit_description').value = dish.description;
    document.getElementById('edit_price').value = dish.price;
    document.getElementById('edit_image').value = dish.image || '';

    const container = document.getElementById('edit_ingredients_container');
    const checkboxes = container.querySelectorAll('input[type="checkbox"]');
    const quantityInputs = container.querySelectorAll('input[type="number"]');

    checkboxes.forEach(cb => cb.checked = false);
    quantityInputs.forEach(input => input.value = '');

    if (dish.ingredients) {
        dish.ingredients.forEach(ing => {
            const cb = container.querySelector(`input[data-sku="${ing.sku_code}"]`);
            const qtyInput = container.querySelector(`input[data-qty-sku="${ing.sku_code}"]`);

            if (cb) cb.checked = true;
            if (qtyInput && ing.pivot) {
                qtyInput.value = ing.pivot.quantity_required;
            }
        });
    }

    document.getElementById('modal_edit').classList.remove('hidden');
}