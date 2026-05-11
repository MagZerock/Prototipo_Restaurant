function handleIngredientSelection(input) {
    const val = input.value;
    const skuInput = document.getElementById('sku_code_hidden');
    const unitInput = document.getElementById('unit_input');
    const selected = ingredientsData.find(ing => ing.name === val);
    if (selected) {
        skuInput.value = selected.sku_code;
        unitInput.value = selected.unit_of_measurement;
    } else {
        skuInput.value = '';
    }
}

function openEditIngredientModal(item) {
    document.getElementById('edit_sku').value = item.sku_code;
    document.getElementById('edit_name').value = item.name;
    document.getElementById('edit_unit').value = item.unit;
    document.getElementById('display_stock').value = item.total_stock + ' ' + item.unit;
    document.getElementById('edit_total_stock').value = item.total_stock;
    document.getElementById('modal_edit_ingredient').classList.remove('hidden');
}