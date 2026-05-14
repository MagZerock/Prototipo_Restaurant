document.addEventListener('DOMContentLoaded', function() {
    const addIngredientForm = document.querySelector('form[action="index.php?action=add_ingredient"]');
    const editIngredientForm = document.querySelector('form[action="index.php?action=edit_ingredient"]');

    const validateIngredientForm = function(e) {
        const form = e.target;
        const nameInput = form.querySelector('input[name="name"]');
        const unitInput = form.querySelector('select[name="unit_of_measurement"]');
        const stockInput = form.querySelector('input[name="total_stock"]');
        
        if (nameInput && unitInput && stockInput) {
            if (!nameInput.value.trim() || !unitInput.value.trim() || stockInput.value === '') {
                e.preventDefault();
                alert('Por favor, complete todos los campos del ingrediente.');
            } else if (parseFloat(stockInput.value) < 0) {
                e.preventDefault();
                alert('El stock actual no puede ser negativo.');
            }
        }
    };

    if (addIngredientForm) addIngredientForm.addEventListener('submit', validateIngredientForm);
    if (editIngredientForm) editIngredientForm.addEventListener('submit', validateIngredientForm);
});
