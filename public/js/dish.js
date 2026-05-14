document.addEventListener('DOMContentLoaded', function () {
    const addDishForm = document.querySelector('form[action="index.php?action=add_dish"]');
    const editDishForm = document.querySelector('form[action="index.php?action=edit_dish"]');

    const validateDishForm = function (e) {
        const form = e.target;
        const nameInput = form.querySelector('input[name="name"]');
        const priceInput = form.querySelector('input[name="price"]');

        if (nameInput && priceInput) {
            const name = nameInput.value.trim();
            const price = parseFloat(priceInput.value);

            if (!name || isNaN(price)) {
                e.preventDefault();
                alert('Por favor, ingrese un nombre y precio válidos para el plato.');
            } else if (price <= 0) {
                e.preventDefault();
                alert('El precio debe ser mayor a 0.');
            }
        }

        const qtyInputs = form.querySelectorAll('.qty-input');
        let hasNegative = false;
        qtyInputs.forEach(input => {
            if (input.value !== '' && parseFloat(input.value) < 0) {
                hasNegative = true;
            }
        });

        if (hasNegative) {
            e.preventDefault();
            alert('Las cantidades de los ingredientes no pueden ser negativas.');
        }
    };

    if (addDishForm) addDishForm.addEventListener('submit', validateDishForm);
    if (editDishForm) editDishForm.addEventListener('submit', validateDishForm);
});

window.filterIngredients = function (type) {
    const inputId = type === 'add' ? 'search_add_ingredients' : 'search_edit_ingredients';
    const containerId = type === 'add' ? 'list_add_ingredients' : 'edit_ingredients_container';

    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase();
    const container = document.getElementById(containerId);

    if (!container) return;

    const items = container.querySelectorAll('.ingredient-item');
    items.forEach(item => {
        const name = item.getAttribute('data-name');
        if (name.indexOf(filter) > -1) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
};
