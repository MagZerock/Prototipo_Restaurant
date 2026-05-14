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

function filterGrid(inputId, gridId) {
    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase();
    const grid = document.getElementById(gridId);
    if (!grid) return;
    
    const items = grid.children;
    
    for (let i = 0; i < items.length; i++) {
        let text = items[i].textContent || items[i].innerText;
        if (text.toLowerCase().indexOf(filter) > -1) {
            items[i].style.display = "flex";
        } else {
            items[i].style.display = "none";
        }
    }
}

function confirmDeleteDish(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este plato?')) {
        window.location.href = 'index.php?action=delete_dish&id=' + id;
    }
}

function confirmUpdateOrderStatus(id, status) {
    let msg = `¿Deseas marcar el pedido #${id} como ${status}?`;
    if (status === 'Completado') {
        msg += '\nEsto descontará automáticamente los ingredientes del inventario.';
    }
    if (confirm(msg)) {
        window.location.href = `index.php?action=update_order_status&id=${id}&status=${status}`;
    }
}
