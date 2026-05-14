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
