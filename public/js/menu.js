function openPersonalizeModal(dish) {
    document.getElementById('modal_id').value = dish.id;
    document.getElementById('modal_name').innerText = dish.name;
    document.getElementById('modal_desc').innerText = dish.description;
    document.getElementById('modal_price').innerText = '$' + parseFloat(dish.price).toFixed(2);
    document.getElementById('modal_img').src = dish.image;
    document.getElementById('modal_qty').value = 1;

    const list = document.getElementById('ingredients_list');
    list.innerHTML = '';
    dish.ingredients.forEach(ing => {
        const ingName = typeof ing === 'object' ? ing.name : ing;
        list.innerHTML += `
            <label class="flex items-center space-x-3 cursor-pointer group p-2 hover:bg-white rounded-xl transition shadow-sm border border-transparent hover:border-green-100">
                <input type="checkbox" name="ingredients[]" value="${ingName}" checked class="rounded-md border-gray-300 text-[#1a4731] focus:ring-[#1a4731] w-5 h-5">
                <span class="text-xs font-bold text-gray-600 group-hover:text-[#1a4731] transition-colors">${ingName}</span>
            </label>
        `;
    });

    const modal = document.getElementById('modal_personalize');
    modal.classList.add('active');
}

function closePersonalizeModal() {
    const modal = document.getElementById('modal_personalize');
    modal.classList.remove('active');
}

function changeQty(val) {
    const input = document.getElementById('modal_qty');
    let newVal = parseInt(input.value) + val;
    if (newVal >= 1 && newVal <= 10) input.value = newVal;
}

window.onclick = function (event) {
    const modal = document.getElementById('modal_personalize');
    if (event.target == modal) closePersonalizeModal();
}