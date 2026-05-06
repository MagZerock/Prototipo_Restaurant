// Biconoir's Interactive Scripts
document.addEventListener('DOMContentLoaded', () => {
    console.log("Biconoir's Final System Initialized");

    // Modal logic for Admin
    const modal = document.getElementById('modal');
    if (modal) {
        window.onclick = (event) => {
            if (event.target == modal) {
                modal.classList.add('hidden');
            }
        }
    }

    // Add some nice hover effects to cards via JS if needed
    const cards = document.querySelectorAll('.card-gourmet');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });
});

// Admin Modal Functions
function openEditModal(dish) {
    document.getElementById('edit_id').value = dish.id;
    document.getElementById('edit_name').value = dish.name;
    document.getElementById('edit_description').value = dish.description;
    document.getElementById('edit_price').value = dish.price;
    document.getElementById('edit_image').value = dish.image || '';
    
    document.getElementById('modal_edit').classList.remove('hidden');
}
