<?php ob_start(); ?>

<section class="py-24 flex items-center justify-center">
    <div class="container mx-auto px-6 max-w-3xl">
        <div class="card-gourmet">
            <h2 class="text-4xl font-bold text-center mb-2 uppercase tracking-tighter">Reserva tu Mesa</h2>
            <p class="text-center text-gray-400 mb-12 italic">Cenas íntimas, eventos o reuniones corporativas.</p>
            
            <form action="index.php?action=reservations" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="form-label">Nombre Completo</label>
                    <input type="text" name="customer" required placeholder="Ej. Juan Pérez" class="input-biconoir">
                </div>
                
                <div>
                    <label class="form-label">Fecha</label>
                    <input type="date" name="date" required class="input-biconoir">
                </div>
                
                <div>
                    <label class="form-label">Hora</label>
                    <input type="time" name="time" required class="input-biconoir">
                </div>
                
                <div>
                    <label class="form-label">Comensales</label>
                    <input type="number" name="pax" required min="1" placeholder="Número de personas" class="input-biconoir">
                </div>
                
                <div>
                    <label class="form-label">Tipo de Reserva</label>
                    <select name="type" required class="input-biconoir">
                        <option value="Normal">Cena Normal</option>
                        <option value="Evento">Evento Especial</option>
                        <option value="Corporativo">Cena Corporativa</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="form-label">Notas Adicionales</label>
                    <textarea name="notes" placeholder="Alergias, peticiones especiales..." class="input-biconoir h-32"></textarea>
                </div>
                
                <div class="md:col-span-2 pt-6">
                    <button type="submit" class="w-full btn-primary py-5 text-xl">Confirmar Reserva</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php 
$content = ob_get_clean(); 
include __DIR__ . '/layout.php'; 
?>
