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

function filterTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase();
    const table = document.getElementById(tableId);
    if (!table) return;
    const trs = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
    
    for (let i = 0; i < trs.length; i++) {
        let rowText = trs[i].textContent || trs[i].innerText;
        if (rowText.toLowerCase().indexOf(filter) > -1) {
            trs[i].style.display = "";
        } else {
            trs[i].style.display = "none";
        }
    }
}

function confirmDeleteIngredient(sku) {
    if (confirm('¿Estás seguro de que deseas eliminar este ingrediente? Esta acción no se puede deshacer.')) {
        window.location.href = 'index.php?action=delete_ingredient&sku=' + sku;
    }
}